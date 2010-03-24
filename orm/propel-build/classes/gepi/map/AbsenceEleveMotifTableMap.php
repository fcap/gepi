<?php


/**
 * This class defines the structure of the 'a_motifs' table.
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
class AbsenceEleveMotifTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'gepi.map.AbsenceEleveMotifTableMap';

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
		$this->setName('a_motifs');
		$this->setPhpName('AbsenceEleveMotif');
		$this->setClassname('AbsenceEleveMotif');
		$this->setPackage('gepi');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 11, null);
		$this->addColumn('NOM', 'Nom', 'VARCHAR', true, 250, null);
		$this->addColumn('COMMENTAIRE', 'Commentaire', 'LONGVARCHAR', false, null, null);
		$this->addColumn('ORDRE', 'Ordre', 'INTEGER', true, 3, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('AbsenceEleveTraitement', 'AbsenceEleveTraitement', RelationMap::ONE_TO_MANY, array('id' => 'a_motif_id', ), 'SET NULL', null);
	} // buildRelations()

} // AbsenceEleveMotifTableMap
