<?php


/**
 * This class adds structure of 'ct_devoirs_documents' table to 'gepi' DatabaseMap object.
 *
 *
 *
 * These statically-built map classes are used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    gepi.map
 */
class CtDevoirDocumentMapBuilder implements MapBuilder {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'gepi.map.CtDevoirDocumentMapBuilder';

	/**
	 * The database map.
	 */
	private $dbMap;

	/**
	 * Tells us if this DatabaseMapBuilder is built so that we
	 * don't have to re-build it every time.
	 *
	 * @return     boolean true if this DatabaseMapBuilder is built, false otherwise.
	 */
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	/**
	 * Gets the databasemap this map builder built.
	 *
	 * @return     the databasemap
	 */
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	/**
	 * The doBuild() method builds the DatabaseMap
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap(CtDevoirDocumentPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(CtDevoirDocumentPeer::TABLE_NAME);
		$tMap->setPhpName('CtDevoirDocument');
		$tMap->setClassname('CtDevoirDocument');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('ID_CT_DEVOIR', 'IdCtDevoir', 'INTEGER', 'ct_devoirs_entry', 'ID_CT', true, null);

		$tMap->addColumn('TITRE', 'Titre', 'VARCHAR', true, 255);

		$tMap->addColumn('TAILLE', 'Taille', 'INTEGER', true, null);

		$tMap->addColumn('EMPLACEMENT', 'Emplacement', 'VARCHAR', true, 255);

	} // doBuild()

} // CtDevoirDocumentMapBuilder
