<?php

/**
 * Classe pour l'authentification dans gepi
 *
 * Provides the same interface as Auth_Simple.
 *
 * @package simpleSAMLphp
 * @version $Id$
 */
class SimpleSAML_Auth_GepiSimple extends SimpleSAML_Auth_Simple {


	/**
	 * Initialise une authentification en utilisant les paramêtre renseignés dans gepi
	 *
	 * @param string|NULL $auth  The authentication source. Si non précisé, utilise la source configurée dans gepi.
	 */
	public function __construct($auth = null) {
		if ($auth == null) {
		    //on va sélectionner la source d'authentification gepi
		    $path = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
		    include_once("$path/secure/connect.inc.php");
		    // Database connection
		    require_once("$path/lib/mysql.inc");
		    require_once("$path/lib/settings.inc");
		    // Load settings
		    if (!loadSettings()) {
				die("Erreur chargement settings");
		    }
		    $auth = getSettingValue('auth_simpleSAML_source');
		}
		
		$config = SimpleSAML_Configuration::getOptionalConfig('authsources.php');
		$sources = $config->getOptions();
		if (!count($sources)) {
			echo 'Erreur simplesaml : Aucune source configurée';
			die;
		}
		if (!in_array($auth, $sources)) {
			echo 'Erreur simplesaml : source '.$auth.' non configurée. Utilisation par défaut de la source : «Authentification au choix entre toutes les sources configurees».';
			$auth = 'Authentification au choix entre toutes les sources configurees';
		}
			
		parent::__construct($auth);
	}

	/**
	 * Ajouter pour gepi : utilisation des cookies et requetes organisation
	 * Start an authentication process.
	 *
	 * This function never returns.
	 *
	 * This function accepts an array $params, which controls some parts of
	 * the authentication. The accepted parameters depends on the authentication
	 * source being used. Some parameters are generic:
	 *  - 'ErrorURL': An URL that should receive errors from the authentication.
	 *  - 'KeepPost': If the current request is a POST request, keep the POST
	 *    data until after the authentication.
	 *  - 'ReturnTo': The URL the user should be returned to after authentication.
	 *  - 'ReturnCallback': The function we should call after the user has
	 *    finished authentication.
	 *
	 * @param array $params  Various options to the authentication request.
	 */
	public function login(array $params = array()) {
		if (!isset($params['multiauth:preselect'])) {
			if (isset($_REQUEST['source'])) {
				$params['multiauth:preselect'] = $_REQUEST['source'];
			} else if (isset($_COOKIE['source'])) {
				$params['multiauth:preselect'] = $_COOKIE['source'];
			}
		}

		if (!isset($params['core:organization'])) {
			if (isset($_REQUEST['organization'])) {
				$params['core:organization'] = $_REQUEST['organization'];
			} else if (isset($_COOKIE['organization'])) {
				$params['core:organization'] = $_COOKIE['organization'];
			} else if (isset($_REQUEST['rne'])) {
				$params['core:organization'] = $_REQUEST['rne'];
			} else if (isset($_COOKIE['RNE'])) {
				$params['core:organization'] = $_COOKIE['RNE'];
			}
		}
		
		parent::login($params);
	}
}
