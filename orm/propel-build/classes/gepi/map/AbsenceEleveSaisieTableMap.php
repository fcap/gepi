<?php


/**
 * This class defines the structure of the 'a_saisies' table.
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
class AbsenceEleveSaisieTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'gepi.map.AbsenceEleveSaisieTableMap';

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
		$this->setName('a_saisies');
		$this->setPhpName('AbsenceEleveSaisie');
		$this->setClassname('AbsenceEleveSaisie');
		$this->setPackage('gepi');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11, null);
		$this->addForeignKey('UTILISATEUR_ID', 'UtilisateurId', 'VARCHAR', 'utilisateurs', 'LOGIN', false, 100, null);
		$this->addForeignKey('ELEVE_ID', 'EleveId', 'INTEGER', 'eleves', 'ID_ELEVE', false, 11, -1);
		$this->addColumn('COMMENTAIRE', 'Commentaire', 'LONGVARCHAR', false, null, null);
		$this->addColumn('DEBUT_ABS', 'DebutAbs', 'TIME', false, null, null);
		$this->addColumn('FIN_ABS', 'FinAbs', 'TIME', false, null, null);
		$this->addForeignKey('ID_EDT_CRENEAU', 'IdEdtCreneau', 'INTEGER', 'edt_creneaux', 'ID_DEFINIE_PERIODE', false, 12, 0);
		$this->addForeignKey('ID_EDT_EMPLACEMENT_COURS', 'IdEdtEmplacementCours', 'INTEGER', 'edt_cours', 'ID_COURS', false, 12, 0);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('UtilisateurProfessionnel', 'UtilisateurProfessionnel', RelationMap::MANY_TO_ONE, array('utilisateur_id' => 'login', ), 'SET NULL', null);
    $this->addRelation('Eleve', 'Eleve', RelationMap::MANY_TO_ONE, array('eleve_id' => 'id_eleve', ), 'CASCADE', null);
    $this->addRelation('EdtCreneau', 'EdtCreneau', RelationMap::MANY_TO_ONE, array('id_edt_creneau' => 'id_definie_periode', ), 'SET NULL', null);
    $this->addRelation('EdtEmplacementCours', 'EdtEmplacementCours', RelationMap::MANY_TO_ONE, array('id_edt_emplacement_cours' => 'id_cours', ), 'SET NULL', null);
    $this->addRelation('JTraitementSaisieEleve', 'JTraitementSaisieEleve', RelationMap::ONE_TO_MANY, array('id' => 'a_saisie_id', ), 'SET NULL', null);
    $this->addRelation('AbsenceEleveTraitement', 'AbsenceEleveTraitement', RelationMap::MANY_TO_MANY, array(), 'SET NULL', null);
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
		);
	} // getBehaviors()

} // AbsenceEleveSaisieTableMap
