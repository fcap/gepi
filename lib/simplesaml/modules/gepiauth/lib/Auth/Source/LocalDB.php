<?php

/**
 * Simple SQL authentication source
 *
 * This class is an example authentication source which authenticates an user
 * against a SQL database.
 *
 * @package simpleSAMLphp
 * @version $Id$
 */
class sspmod_gepiauth_Auth_Source_LocalDB extends sspmod_core_Auth_UserPassBase {

	/**
	 * Le statut requis pour cette connexion (utilis� pour l'admin simplesaml
	 */
	private $requiredStatut = null;

	/**
	 * Constructor for this authentication source.
	 *
	 * @param array $info  Information about this authentication source.
	 * @param array $config  Configuration.
	 */
	public function __construct($info, $config) {
		assert('is_array($info)');
		assert('is_array($config)');

		/* Call the parent constructor first, as required by the interface. */
		parent::__construct($info, $config);

		if (array_key_exists('required_statut', $config)) {
			$this->requiredStatut = $config['required_statut'];
		}
	}

	/**
	 * Attempt to log in using the given username and password.
	 *
	 * On a successful login, this function should return the users attributes. On failure,
	 * it should throw an exception. If the error was caused by the user entering the wrong
	 * username or password, a SimpleSAML_Error_Error('WRONGUSERPASS') should be thrown.
	 *
	 * Note that both the username and the password are UTF-8 encoded.
	 *
	 * @param string $username  The username the user wrote.
	 * @param string $password  The password the user wrote.
	 * @return array  Associative array with the users attributes.
	 */
	protected function login($username, $password) {
		assert('is_string($username)');
		assert('is_string($password)');

		$path = dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))));
		require_once("$path/secure/connect.inc.php");
		// Database connection
		require_once("$path/lib/mysql.inc");
		require_once("$path/lib/settings.inc");
		// Load settings
		if (!loadSettings()) {
		    die("Erreur chargement settings");
		}
		// Global configuration file
		require_once("$path/lib/global.inc");
		// Libraries
		include "$path/lib/share.inc.php";

		// Session related functions
		require_once("$path/lib/Session.class.php");
		
		$session_gepi = new Session();
		
		# L'instance de Session permettant de g�rer directement les authentifications
		# SSO, on ne s'emb�te pas :
		$auth = $session_gepi->authenticate_gepi($username, $password);
		
		if ($auth == "1") {
			//le load user data est utilis� pour r�cup�rer les attributs de l'utilisateur et les transemmettre en aval
			//pour une autentification de gepi vers saml il y a une redondance de l'appel car load user data est appel� en retour de login.
			$session_gepi->load_user_data();
			if ($this->requiredStatut != null && $this->requiredStatut != $_SESSION['statut']) {
				# Echec d'authentification pour ce statut
				$session_gepi->close('2');
				session_write_close();
				SimpleSAML_Logger::error('gepiauth:' . $this->authId .
					': not authenticated. Probably wrong username/password.');
				throw new SimpleSAML_Error_Error('WRONGUSERPASS');			
			}
		} else {
			# Echec d'authentification.
			$session_gepi->record_failed_login($username);
			session_write_close();
			SimpleSAML_Logger::error('gepiauth:' . $this->authId .
				': not authenticated. Probably wrong username/password.');
			throw new SimpleSAML_Error_Error('WRONGUSERPASS');			
		}
		
		SimpleSAML_Logger::info('gepiauth:' . $this->authId . ': authenticated');

		$attributes = array();
		$attributes['login'] = array($_SESSION['login']);
		$attributes['nom']= array($_SESSION['nom']);
		$attributes['prenom']= array($_SESSION['prenom']);
		$attributes['email']= array($_SESSION['email']);
		$attributes['statut']= array($_SESSION['statut']);
		$attributes['start']= array($_SESSION['start']);
		$attributes['matiere']= array($_SESSION['matiere']);
		$attributes['rne']= array($_SESSION['rne']);
		$attributes['current_auth_mode']= array($_SESSION['current_auth_mode']);
		
		SimpleSAML_Logger::info('gepiauth:' . $this->authId . ': Attributes: ' .
			implode(',', array_keys($attributes)));
			
		//on commence la session en base de donn�e
		$session_gepi->start = mysql_result(mysql_query("SELECT now();"),0);
		$_SESSION['start'] = $session_gepi->start;
		$session_gepi->insert_log();
			

		return $attributes;
	}

	/**
	 * This function is called when the user start a logout operation, for example
	 * by logging out of a SP that supports single logout.
	 *
	 * @param array &$state  The logout state array.
	 */
	public function logout(&$state) {
		//echo 'called';die;
		assert('is_array($state)');

		if (!session_id()) {
			/* session_start not called before. Do it here. */
			session_start();
		}

		// Session related functions
		$path = dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))));
		require_once("$path/lib/Session.class.php");
		
		$session_gepi = new Session();
		$auto = 0; //parametre sp�cifique gepi qui qualifie le type de fin de session
		if (isset($_GET['auto'])) {
			$auto = $_GET['auto'];
		}
		$session_gepi->close($auto);
	}
}

?>