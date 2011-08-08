<?php

/**
 *
 */
class sspmod_gepicas_Auth_Source_GepiCAS  extends sspmod_cas_Auth_Source_CAS  {
//class sspmod_gepicas_Auth_Source_GepiCAS  extends SimpleSAML_Auth_Source   {

	/**
	 * @var string search_table_name SQL name of the table
	 */
	private $_search_table_name;

	/**
	 * @var string search_table_name SQL name of the field
	 */
	private $_search_table_cas_uid_column;

	/**
	 * @var string search_table_name SQL name of the field
	 */
	private $_search_table_gepi_login_column;

	/**
	 * @var search_champ_uid_retour O� trouver l'UID entre $username et $casattributes
	 */
	private $_search_champ_uid_retour;

	/**
	 * Constructor for this authentication source.
	 *
	 * @param array $info  Information about this authentication source.
	 * @param array $config  Configuration.
	 */
	public function __construct($info, $config) {
		assert('is_array($info)');
		assert('is_array($config)');

		//le ldap n'est pas utilis�, mais il faut une configuration pour �viter une erreur de la classe parente CAS
		$config['ldap'] = array();
		
		/* Call the parent constructor first, as required by the interface. */
		parent::__construct($info, $config);

		if (!array_key_exists('search_table', $config)){
			throw new Exception('gepiCAS authentication source is not properly configured: missing [search_table]');
		}
		$search_table_array = $config['search_table'];
	
		if(isset($search_table_array['name'])){
			$this->_search_table_name = $search_table_array['name'];
		}else{
			throw new Exception("name not specified");
		}

		if(isset($search_table_array['cas_uid_column'])){
			$this->_search_table_cas_uid_column =  $search_table_array['cas_uid_column'];
		}else{
			throw new Exception("cas_uid_column not specified");
		}

		if(isset($search_table_array['gepi_login_column'])){
			$this->_search_table_gepi_login_column =  $search_table_array['gepi_login_column'];
		}else{
			throw new Exception("gepi_login_column not specified");
		}

		if(isset($search_table_array['champ_uid_retour'])){
			$this->_search_champ_uid_retour =  $search_table_array['champ_uid_retour'];
		}else{
			throw new Exception("champ_uid_retour not specified");
		}
	}

	/**
	 * Called by linkback, to finish validate/ finish logging in.
	 * @param state $state
	 * @return list username, casattributes/ldap attributes
	 */
	public function finalStep(&$state) {


		$ticket = $state['cas:ticket'];
		$stateID = SimpleSAML_Auth_State::saveState($state, self::STAGE_INIT);
		$service =  SimpleSAML_Module::getModuleURL('cas/linkback.php', array('stateID' => $stateID));
		list($username, $casattributes) = $this->casValidation($ticket, $service);

		//recherche du login gepi
		$path = dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))));
		require_once($path."/secure/connect.inc.php");
		// Database connection
		require_once($path."/lib/mysql.inc");
		
		if ($this->_search_champ_uid_retour == 'username') {
			$uid = $username;
		} else {
			$uid = $casattributes['uid'];
		}
		
		$requete = 'SELECT '.$this->_search_table_gepi_login_column.' FROM '.$this->_search_table_name.' WHERE '.$this->_search_table_cas_uid_column.'=\''.$uid.'\'';
		$result = mysql_query($requete);
		$valeur = mysql_fetch_array($result);
		$attributes['login'] = array($valeur[0]);
		$attributes['login_gepi'][0] = $valeur[0];
		
		$state['Attributes'] = $attributes;
		
		SimpleSAML_Auth_Source::completeAuth($state);
	}
}