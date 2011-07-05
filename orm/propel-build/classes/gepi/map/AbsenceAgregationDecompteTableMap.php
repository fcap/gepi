<?php



/**
 * This class defines the structure of the 'a_agregation_decompte' table.
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
class AbsenceAgregationDecompteTableMap extends TableMap
{

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'gepi.map.AbsenceAgregationDecompteTableMap';

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
		$this->setName('a_agregation_decompte');
		$this->setPhpName('AbsenceAgregationDecompte');
		$this->setClassname('AbsenceAgregationDecompte');
		$this->setPackage('gepi');
		$this->setUseIdGenerator(false);
		// columns
		$this->addForeignPrimaryKey('ELEVE_ID', 'EleveId', 'INTEGER' , 'eleves', 'ID_ELEVE', true, 11, null);
		$this->addPrimaryKey('DATE_DEMI_JOUNEE', 'DateDemiJounee', 'TIMESTAMP', true, null, null);
		$this->addColumn('MANQUEMENT_OBLIGATION_PRESENCE', 'ManquementObligationPresence', 'BOOLEAN', false, null, false);
		$this->addColumn('JUSTIFIEE', 'Justifiee', 'BOOLEAN', false, null, false);
		$this->addColumn('NOTIFIEE', 'Notifiee', 'BOOLEAN', false, null, false);
		$this->addColumn('NB_RETARDS', 'NbRetards', 'INTEGER', false, null, 0);
		$this->addColumn('NB_RETARDS_JUSTIFIES', 'NbRetardsJustifies', 'INTEGER', false, null, 0);
		$this->addColumn('MOTIFS_ABSENCE', 'MotifsAbsence', 'ARRAY', false, null, null);
		$this->addColumn('MOTIFS_RETARDS', 'MotifsRetards', 'ARRAY', false, null, null);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
		$this->addRelation('Eleve', 'Eleve', RelationMap::MANY_TO_ONE, array('eleve_id' => 'id_eleve', ), 'CASCADE', null);
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

} // AbsenceAgregationDecompteTableMap
