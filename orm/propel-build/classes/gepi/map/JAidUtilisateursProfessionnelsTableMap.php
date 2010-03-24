<?php


/**
 * This class defines the structure of the 'j_aid_utilisateurs' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.gepi.map
 */
class JAidUtilisateursProfessionnelsTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'gepi.map.JAidUtilisateursProfessionnelsTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('j_aid_utilisateurs');
		$this->setPhpName('JAidUtilisateursProfessionnels');
		$this->setClassname('JAidUtilisateursProfessionnels');
		$this->setPackage('gepi');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('ID_AID', 'IdAid', 'VARCHAR' , 'aid', 'ID', true, 100, null);
		$this->addForeignKey('ID_UTILISATEUR', 'IdUtilisateur', 'VARCHAR', 'utilisateurs', 'LOGIN', true, 100, null);
		$this->addForeignPrimaryKey('INDICE_AID', 'IndiceAid', 'INTEGER' , 'aid_config', 'INDICE_AID', true, 11, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('AidDetails', 'AidDetails', RelationMap::MANY_TO_ONE, array('id_aid' => 'id', ), 'CASCADE', null);
    $this->addRelation('UtilisateurProfessionnel', 'UtilisateurProfessionnel', RelationMap::MANY_TO_ONE, array('id_utilisateur' => 'login', ), 'CASCADE', null);
    $this->addRelation('AidConfiguration', 'AidConfiguration', RelationMap::MANY_TO_ONE, array('indice_aid' => 'indice_aid', ), 'CASCADE', null);
	} // buildRelations()

} // JAidUtilisateursProfessionnelsTableMap
