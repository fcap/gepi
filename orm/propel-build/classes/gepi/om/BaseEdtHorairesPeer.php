<?php

/**
 * Base static class for performing query and update operations on the 'horaires_etablissement' table.
 *
 * Table contenant les heures d'ouverture et de fermeture de l'etablissement par journee
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseEdtHorairesPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'gepi';

	/** the table name for this class */
	const TABLE_NAME = 'horaires_etablissement';

	/** the related Propel class for this table */
	const OM_CLASS = 'EdtHoraires';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'gepi.EdtHoraires';

	/** the related TableMap class for this table */
	const TM_CLASS = 'EdtHorairesTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 7;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the ID_HORAIRE_ETABLISSEMENT field */
	const ID_HORAIRE_ETABLISSEMENT = 'horaires_etablissement.ID_HORAIRE_ETABLISSEMENT';

	/** the column name for the DATE_HORAIRE_ETABLISSEMENT field */
	const DATE_HORAIRE_ETABLISSEMENT = 'horaires_etablissement.DATE_HORAIRE_ETABLISSEMENT';

	/** the column name for the JOUR_HORAIRE_ETABLISSEMENT field */
	const JOUR_HORAIRE_ETABLISSEMENT = 'horaires_etablissement.JOUR_HORAIRE_ETABLISSEMENT';

	/** the column name for the OUVERTURE_HORAIRE_ETABLISSEMENT field */
	const OUVERTURE_HORAIRE_ETABLISSEMENT = 'horaires_etablissement.OUVERTURE_HORAIRE_ETABLISSEMENT';

	/** the column name for the FERMETURE_HORAIRE_ETABLISSEMENT field */
	const FERMETURE_HORAIRE_ETABLISSEMENT = 'horaires_etablissement.FERMETURE_HORAIRE_ETABLISSEMENT';

	/** the column name for the PAUSE_HORAIRE_ETABLISSEMENT field */
	const PAUSE_HORAIRE_ETABLISSEMENT = 'horaires_etablissement.PAUSE_HORAIRE_ETABLISSEMENT';

	/** the column name for the OUVERT_HORAIRE_ETABLISSEMENT field */
	const OUVERT_HORAIRE_ETABLISSEMENT = 'horaires_etablissement.OUVERT_HORAIRE_ETABLISSEMENT';

	/**
	 * An identiy map to hold any loaded instances of EdtHoraires objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array EdtHoraires[]
	 */
	public static $instances = array();


	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('IdHoraireEtablissement', 'DateHoraireEtablissement', 'JourHoraireEtablissement', 'OuvertureHoraireEtablissement', 'FermetureHoraireEtablissement', 'PauseHoraireEtablissement', 'OuvertHoraireEtablissement', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('idHoraireEtablissement', 'dateHoraireEtablissement', 'jourHoraireEtablissement', 'ouvertureHoraireEtablissement', 'fermetureHoraireEtablissement', 'pauseHoraireEtablissement', 'ouvertHoraireEtablissement', ),
		BasePeer::TYPE_COLNAME => array (self::ID_HORAIRE_ETABLISSEMENT, self::DATE_HORAIRE_ETABLISSEMENT, self::JOUR_HORAIRE_ETABLISSEMENT, self::OUVERTURE_HORAIRE_ETABLISSEMENT, self::FERMETURE_HORAIRE_ETABLISSEMENT, self::PAUSE_HORAIRE_ETABLISSEMENT, self::OUVERT_HORAIRE_ETABLISSEMENT, ),
		BasePeer::TYPE_RAW_COLNAME => array ('ID_HORAIRE_ETABLISSEMENT', 'DATE_HORAIRE_ETABLISSEMENT', 'JOUR_HORAIRE_ETABLISSEMENT', 'OUVERTURE_HORAIRE_ETABLISSEMENT', 'FERMETURE_HORAIRE_ETABLISSEMENT', 'PAUSE_HORAIRE_ETABLISSEMENT', 'OUVERT_HORAIRE_ETABLISSEMENT', ),
		BasePeer::TYPE_FIELDNAME => array ('id_horaire_etablissement', 'date_horaire_etablissement', 'jour_horaire_etablissement', 'ouverture_horaire_etablissement', 'fermeture_horaire_etablissement', 'pause_horaire_etablissement', 'ouvert_horaire_etablissement', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('IdHoraireEtablissement' => 0, 'DateHoraireEtablissement' => 1, 'JourHoraireEtablissement' => 2, 'OuvertureHoraireEtablissement' => 3, 'FermetureHoraireEtablissement' => 4, 'PauseHoraireEtablissement' => 5, 'OuvertHoraireEtablissement' => 6, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('idHoraireEtablissement' => 0, 'dateHoraireEtablissement' => 1, 'jourHoraireEtablissement' => 2, 'ouvertureHoraireEtablissement' => 3, 'fermetureHoraireEtablissement' => 4, 'pauseHoraireEtablissement' => 5, 'ouvertHoraireEtablissement' => 6, ),
		BasePeer::TYPE_COLNAME => array (self::ID_HORAIRE_ETABLISSEMENT => 0, self::DATE_HORAIRE_ETABLISSEMENT => 1, self::JOUR_HORAIRE_ETABLISSEMENT => 2, self::OUVERTURE_HORAIRE_ETABLISSEMENT => 3, self::FERMETURE_HORAIRE_ETABLISSEMENT => 4, self::PAUSE_HORAIRE_ETABLISSEMENT => 5, self::OUVERT_HORAIRE_ETABLISSEMENT => 6, ),
		BasePeer::TYPE_RAW_COLNAME => array ('ID_HORAIRE_ETABLISSEMENT' => 0, 'DATE_HORAIRE_ETABLISSEMENT' => 1, 'JOUR_HORAIRE_ETABLISSEMENT' => 2, 'OUVERTURE_HORAIRE_ETABLISSEMENT' => 3, 'FERMETURE_HORAIRE_ETABLISSEMENT' => 4, 'PAUSE_HORAIRE_ETABLISSEMENT' => 5, 'OUVERT_HORAIRE_ETABLISSEMENT' => 6, ),
		BasePeer::TYPE_FIELDNAME => array ('id_horaire_etablissement' => 0, 'date_horaire_etablissement' => 1, 'jour_horaire_etablissement' => 2, 'ouverture_horaire_etablissement' => 3, 'fermeture_horaire_etablissement' => 4, 'pause_horaire_etablissement' => 5, 'ouvert_horaire_etablissement' => 6, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, )
	);

	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException - if the specified name could not be found in the fieldname mappings.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. EdtHorairesPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(EdtHorairesPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      Criteria $criteria object containing the columns to add.
	 * @param      string   $alias    optional table alias
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria, $alias = null)
	{
		if (null === $alias) {
			$criteria->addSelectColumn(EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT);
			$criteria->addSelectColumn(EdtHorairesPeer::DATE_HORAIRE_ETABLISSEMENT);
			$criteria->addSelectColumn(EdtHorairesPeer::JOUR_HORAIRE_ETABLISSEMENT);
			$criteria->addSelectColumn(EdtHorairesPeer::OUVERTURE_HORAIRE_ETABLISSEMENT);
			$criteria->addSelectColumn(EdtHorairesPeer::FERMETURE_HORAIRE_ETABLISSEMENT);
			$criteria->addSelectColumn(EdtHorairesPeer::PAUSE_HORAIRE_ETABLISSEMENT);
			$criteria->addSelectColumn(EdtHorairesPeer::OUVERT_HORAIRE_ETABLISSEMENT);
		} else {
			$criteria->addSelectColumn($alias . '.ID_HORAIRE_ETABLISSEMENT');
			$criteria->addSelectColumn($alias . '.DATE_HORAIRE_ETABLISSEMENT');
			$criteria->addSelectColumn($alias . '.JOUR_HORAIRE_ETABLISSEMENT');
			$criteria->addSelectColumn($alias . '.OUVERTURE_HORAIRE_ETABLISSEMENT');
			$criteria->addSelectColumn($alias . '.FERMETURE_HORAIRE_ETABLISSEMENT');
			$criteria->addSelectColumn($alias . '.PAUSE_HORAIRE_ETABLISSEMENT');
			$criteria->addSelectColumn($alias . '.OUVERT_HORAIRE_ETABLISSEMENT');
		}
	}

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(EdtHorairesPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			EdtHorairesPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(EdtHorairesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		// BasePeer returns a PDOStatement
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     EdtHoraires
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = EdtHorairesPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return EdtHorairesPeer::populateObjects(EdtHorairesPeer::doSelectStmt($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
	 *
	 * Use this method directly if you want to work with an executed statement durirectly (for example
	 * to perform your own object hydration).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con The connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     PDOStatement The executed PDOStatement object.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(EdtHorairesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			EdtHorairesPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		// BasePeer returns a PDOStatement
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * Adds an object to the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doSelect*()
	 * methods in your stub classes -- you may need to explicitly add objects
	 * to the cache in order to ensure that the same objects are always returned by doSelect*()
	 * and retrieveByPK*() calls.
	 *
	 * @param      EdtHoraires $value A EdtHoraires object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(EdtHoraires $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getIdHoraireEtablissement();
			} // if key === null
			self::$instances[$key] = $obj;
		}
	}

	/**
	 * Removes an object from the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doDelete
	 * methods in your stub classes -- you may need to explicitly remove objects
	 * from the cache in order to prevent returning objects that no longer exist.
	 *
	 * @param      mixed $value A EdtHoraires object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof EdtHoraires) {
				$key = (string) $value->getIdHoraireEtablissement();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or EdtHoraires object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} // removeInstanceFromPool()

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
	 * @return     EdtHoraires Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
	 * @see        getPrimaryKeyHash()
	 */
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; // just to be explicit
	}
	
	/**
	 * Clear the instance pool.
	 *
	 * @return     void
	 */
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	/**
	 * Method to invalidate the instance pool of all tables related to horaires_etablissement
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
	}

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     string A string version of PK or NULL if the components of primary key in result array are all null.
	 */
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
		// If the PK cannot be derived from the row, return NULL.
		if ($row[$startcol] === null) {
			return null;
		}
		return (string) $row[$startcol];
	}

	/**
	 * Retrieves the primary key from the DB resultset row 
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, an array of the primary key columns will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     mixed The primary key of the row
	 */
	public static function getPrimaryKeyFromRow($row, $startcol = 0)
	{
		return (int) $row[$startcol];
	}
	
	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = EdtHorairesPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = EdtHorairesPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = EdtHorairesPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				EdtHorairesPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}
	/**
	 * Populates an object of the default type or an object that inherit from the default.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     array (EdtHoraires object, last column rank)
	 */
	public static function populateObject($row, $startcol = 0)
	{
		$key = EdtHorairesPeer::getPrimaryKeyHashFromRow($row, $startcol);
		if (null !== ($obj = EdtHorairesPeer::getInstanceFromPool($key))) {
			// We no longer rehydrate the object, since this can cause data loss.
			// See http://propel.phpdb.org/trac/ticket/509
			// $obj->hydrate($row, $startcol, true); // rehydrate
			$col = $startcol + EdtHorairesPeer::NUM_COLUMNS;
		} else {
			$cls = EdtHorairesPeer::OM_CLASS;
			$obj = new $cls();
			$col = $obj->hydrate($row, $startcol);
			EdtHorairesPeer::addInstanceToPool($obj, $key);
		}
		return array($obj, $col);
	}
	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * Add a TableMap instance to the database for this peer class.
	 */
	public static function buildTableMap()
	{
	  $dbMap = Propel::getDatabaseMap(BaseEdtHorairesPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseEdtHorairesPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new EdtHorairesTableMap());
	  }
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * If $withPrefix is true, the returned path
	 * uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @param      boolean $withPrefix Whether or not to return the path with the class name
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass($withPrefix = true)
	{
		return $withPrefix ? EdtHorairesPeer::CLASS_DEFAULT : EdtHorairesPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a EdtHoraires or Criteria object.
	 *
	 * @param      mixed $values Criteria or EdtHoraires object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(EdtHorairesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from EdtHoraires object
		}

		if ($criteria->containsKey(EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT) && $criteria->keyContainsValue(EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT.')');
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

		return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a EdtHoraires or Criteria object.
	 *
	 * @param      mixed $values Criteria or EdtHoraires object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(EdtHorairesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT);
			$value = $criteria->remove(EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT);
			if ($value) {
				$selectCriteria->add(EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT, $value, $comparison);
			} else {
				$selectCriteria->setPrimaryTableName(EdtHorairesPeer::TABLE_NAME);
			}

		} else { // $values is EdtHoraires object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	/**
	 * Method to DELETE all rows from the horaires_etablissement table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(EdtHorairesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(EdtHorairesPeer::TABLE_NAME, $con);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			EdtHorairesPeer::clearInstancePool();
			EdtHorairesPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a EdtHoraires or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or EdtHoraires object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(EdtHorairesPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			EdtHorairesPeer::clearInstancePool();
			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof EdtHoraires) { // it's a model object
			// invalidate the cache for this single object
			EdtHorairesPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else { // it's a primary key, or an array of pks
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT, (array) $values, Criteria::IN);
			// invalidate the cache for this object(s)
			foreach ((array) $values as $singleval) {
				EdtHorairesPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			EdtHorairesPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given EdtHoraires object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      EdtHoraires $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(EdtHoraires $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(EdtHorairesPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(EdtHorairesPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		return BasePeer::doValidate(EdtHorairesPeer::DATABASE_NAME, EdtHorairesPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     EdtHoraires
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = EdtHorairesPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(EdtHorairesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(EdtHorairesPeer::DATABASE_NAME);
		$criteria->add(EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT, $pk);

		$v = EdtHorairesPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      PropelPDO $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(EdtHorairesPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(EdtHorairesPeer::DATABASE_NAME);
			$criteria->add(EdtHorairesPeer::ID_HORAIRE_ETABLISSEMENT, $pks, Criteria::IN);
			$objs = EdtHorairesPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} // BaseEdtHorairesPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseEdtHorairesPeer::buildTableMap();
