<?php


/**
 * This class defines the structure of the 'groupes' table.
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
class GroupeTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'gepi.map.GroupeTableMap';

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
		$this->setName('groupes');
		$this->setPhpName('Groupe');
		$this->setClassname('Groupe');
		$this->setPackage('gepi');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('NAME', 'Name', 'VARCHAR', true, 60, null);
		$this->addColumn('DESCRIPTION', 'Description', 'LONGVARCHAR', true, null, null);
		$this->addColumn('RECALCUL_RANG', 'RecalculRang', 'VARCHAR', false, 10, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('JGroupesProfesseurs', 'JGroupesProfesseurs', RelationMap::ONE_TO_MANY, array('id' => 'id_groupe', ), 'CASCADE', null);
    $this->addRelation('JGroupesClasses', 'JGroupesClasses', RelationMap::ONE_TO_MANY, array('id' => 'id_groupe', ), 'CASCADE', null);
    $this->addRelation('CahierTexteCompteRendu', 'CahierTexteCompteRendu', RelationMap::ONE_TO_MANY, array('id' => 'id_groupe', ), 'CASCADE', null);
    $this->addRelation('CahierTexteTravailAFaire', 'CahierTexteTravailAFaire', RelationMap::ONE_TO_MANY, array('id' => 'id_groupe', ), 'CASCADE', null);
    $this->addRelation('CahierTexteNoticePrivee', 'CahierTexteNoticePrivee', RelationMap::ONE_TO_MANY, array('id' => 'id_groupe', ), 'CASCADE', null);
    $this->addRelation('JEleveGroupe', 'JEleveGroupe', RelationMap::ONE_TO_MANY, array('id' => 'id_groupe', ), 'CASCADE', null);
    $this->addRelation('CreditEcts', 'CreditEcts', RelationMap::ONE_TO_MANY, array('id' => 'id_groupe', ), null, null);
    $this->addRelation('EdtEmplacementCours', 'EdtEmplacementCours', RelationMap::ONE_TO_MANY, array('id' => 'id_groupe', ), 'CASCADE', null);
    $this->addRelation('UtilisateurProfessionnel', 'UtilisateurProfessionnel', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null);
	} // buildRelations()

} // GroupeTableMap