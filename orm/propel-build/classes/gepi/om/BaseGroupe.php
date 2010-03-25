<?php

/**
 * Base class that represents a row from the 'groupes' table.
 *
 * Groupe d'eleves permettant d'y affecter une matiere et un professeurs
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseGroupe extends BaseObject  implements Persistent
{

	/**
	 * Peer class name
	 */
  const PEER = 'GroupePeer';

	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        GroupePeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the name field.
	 * @var        string
	 */
	protected $name;

	/**
	 * The value for the description field.
	 * @var        string
	 */
	protected $description;

	/**
	 * The value for the recalcul_rang field.
	 * @var        string
	 */
	protected $recalcul_rang;

	/**
	 * @var        array JGroupesProfesseurs[] Collection to store aggregation of JGroupesProfesseurs objects.
	 */
	protected $collJGroupesProfesseurss;

	/**
	 * @var        array JGroupesClasses[] Collection to store aggregation of JGroupesClasses objects.
	 */
	protected $collJGroupesClassess;

	/**
	 * @var        array CahierTexteCompteRendu[] Collection to store aggregation of CahierTexteCompteRendu objects.
	 */
	protected $collCahierTexteCompteRendus;

	/**
	 * @var        array CahierTexteTravailAFaire[] Collection to store aggregation of CahierTexteTravailAFaire objects.
	 */
	protected $collCahierTexteTravailAFaires;

	/**
	 * @var        array CahierTexteNoticePrivee[] Collection to store aggregation of CahierTexteNoticePrivee objects.
	 */
	protected $collCahierTexteNoticePrivees;

	/**
	 * @var        array JEleveGroupe[] Collection to store aggregation of JEleveGroupe objects.
	 */
	protected $collJEleveGroupes;

	/**
	 * @var        array CreditEcts[] Collection to store aggregation of CreditEcts objects.
	 */
	protected $collCreditEctss;

	/**
	 * @var        array EdtEmplacementCours[] Collection to store aggregation of EdtEmplacementCours objects.
	 */
	protected $collEdtEmplacementCourss;

	/**
	 * @var        array UtilisateurProfessionnel[] Collection to store aggregation of UtilisateurProfessionnel objects.
	 */
	protected $collUtilisateurProfessionnels;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	/**
	 * Get the [id] column value.
	 * Clee primaire du groupe
	 * @return     int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the [name] column value.
	 * Nom du groupe
	 * @return     string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the [description] column value.
	 * Description du groupe
	 * @return     string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Get the [recalcul_rang] column value.
	 * recalcul_rang
	 * @return     string
	 */
	public function getRecalculRang()
	{
		return $this->recalcul_rang;
	}

	/**
	 * Set the value of [id] column.
	 * Clee primaire du groupe
	 * @param      int $v new value
	 * @return     Groupe The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = GroupePeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [name] column.
	 * Nom du groupe
	 * @param      string $v new value
	 * @return     Groupe The current object (for fluent API support)
	 */
	public function setName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = GroupePeer::NAME;
		}

		return $this;
	} // setName()

	/**
	 * Set the value of [description] column.
	 * Description du groupe
	 * @param      string $v new value
	 * @return     Groupe The current object (for fluent API support)
	 */
	public function setDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = GroupePeer::DESCRIPTION;
		}

		return $this;
	} // setDescription()

	/**
	 * Set the value of [recalcul_rang] column.
	 * recalcul_rang
	 * @param      string $v new value
	 * @return     Groupe The current object (for fluent API support)
	 */
	public function setRecalculRang($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->recalcul_rang !== $v) {
			$this->recalcul_rang = $v;
			$this->modifiedColumns[] = GroupePeer::RECALCUL_RANG;
		}

		return $this;
	} // setRecalculRang()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->description = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->recalcul_rang = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			return $startcol + 4; // 4 = GroupePeer::NUM_COLUMNS - GroupePeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Groupe object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(GroupePeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = GroupePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->collJGroupesProfesseurss = null;

			$this->collJGroupesClassess = null;

			$this->collCahierTexteCompteRendus = null;

			$this->collCahierTexteTravailAFaires = null;

			$this->collCahierTexteNoticePrivees = null;

			$this->collJEleveGroupes = null;

			$this->collCreditEctss = null;

			$this->collEdtEmplacementCourss = null;

		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(GroupePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			if ($ret) {
				GroupePeer::doDelete($this, $con);
				$this->postDelete($con);
				$con->commit();
				$this->setDeleted(true);
			} else {
				$con->commit();
			}
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(GroupePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
			} else {
				$ret = $ret && $this->preUpdate($con);
			}
			if ($ret) {
				$affectedRows = $this->doSave($con);
				if ($isInsert) {
					$this->postInsert($con);
				} else {
					$this->postUpdate($con);
				}
				$this->postSave($con);
				GroupePeer::addInstanceToPool($this);
			} else {
				$affectedRows = 0;
			}
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			if ($this->isNew() ) {
				$this->modifiedColumns[] = GroupePeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$criteria = $this->buildCriteria();
					if ($criteria->keyContainsValue(GroupePeer::ID) ) {
						throw new PropelException('Cannot insert a value for auto-increment primary key ('.GroupePeer::ID.')');
					}

					$pk = BasePeer::doInsert($criteria, $con);
					$affectedRows = 1;
					$this->setId($pk);  //[IMV] update autoincrement primary key
					$this->setNew(false);
				} else {
					$affectedRows = GroupePeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collJGroupesProfesseurss !== null) {
				foreach ($this->collJGroupesProfesseurss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collJGroupesClassess !== null) {
				foreach ($this->collJGroupesClassess as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collCahierTexteCompteRendus !== null) {
				foreach ($this->collCahierTexteCompteRendus as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collCahierTexteTravailAFaires !== null) {
				foreach ($this->collCahierTexteTravailAFaires as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collCahierTexteNoticePrivees !== null) {
				foreach ($this->collCahierTexteNoticePrivees as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collJEleveGroupes !== null) {
				foreach ($this->collJEleveGroupes as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collCreditEctss !== null) {
				foreach ($this->collCreditEctss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collEdtEmplacementCourss !== null) {
				foreach ($this->collEdtEmplacementCourss as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = GroupePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collJGroupesProfesseurss !== null) {
					foreach ($this->collJGroupesProfesseurss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collJGroupesClassess !== null) {
					foreach ($this->collJGroupesClassess as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collCahierTexteCompteRendus !== null) {
					foreach ($this->collCahierTexteCompteRendus as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collCahierTexteTravailAFaires !== null) {
					foreach ($this->collCahierTexteTravailAFaires as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collCahierTexteNoticePrivees !== null) {
					foreach ($this->collCahierTexteNoticePrivees as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collJEleveGroupes !== null) {
					foreach ($this->collJEleveGroupes as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collCreditEctss !== null) {
					foreach ($this->collCreditEctss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collEdtEmplacementCourss !== null) {
					foreach ($this->collEdtEmplacementCourss as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = GroupePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getName();
				break;
			case 2:
				return $this->getDescription();
				break;
			case 3:
				return $this->getRecalculRang();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. 
	 *                    Defaults to BasePeer::TYPE_PHPNAME.
	 * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
	 *
	 * @return    array an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = GroupePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getName(),
			$keys[2] => $this->getDescription(),
			$keys[3] => $this->getRecalculRang(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = GroupePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setName($value);
				break;
			case 2:
				$this->setDescription($value);
				break;
			case 3:
				$this->setRecalculRang($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = GroupePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setDescription($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setRecalculRang($arr[$keys[3]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(GroupePeer::DATABASE_NAME);

		if ($this->isColumnModified(GroupePeer::ID)) $criteria->add(GroupePeer::ID, $this->id);
		if ($this->isColumnModified(GroupePeer::NAME)) $criteria->add(GroupePeer::NAME, $this->name);
		if ($this->isColumnModified(GroupePeer::DESCRIPTION)) $criteria->add(GroupePeer::DESCRIPTION, $this->description);
		if ($this->isColumnModified(GroupePeer::RECALCUL_RANG)) $criteria->add(GroupePeer::RECALCUL_RANG, $this->recalcul_rang);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(GroupePeer::DATABASE_NAME);
		$criteria->add(GroupePeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Returns true if the primary key for this object is null.
	 * @return     boolean
	 */
	public function isPrimaryKeyNull()
	{
		return null === $this->getId();
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Groupe (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{
		$copyObj->setName($this->name);
		$copyObj->setDescription($this->description);
		$copyObj->setRecalculRang($this->recalcul_rang);

		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getJGroupesProfesseurss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addJGroupesProfesseurs($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getJGroupesClassess() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addJGroupesClasses($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCahierTexteCompteRendus() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCahierTexteCompteRendu($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCahierTexteTravailAFaires() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCahierTexteTravailAFaire($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCahierTexteNoticePrivees() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCahierTexteNoticePrivee($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getJEleveGroupes() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addJEleveGroupe($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getCreditEctss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addCreditEcts($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getEdtEmplacementCourss() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addEdtEmplacementCours($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);
		$copyObj->setId(NULL); // this is a auto-increment column, so set to default value
	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     Groupe Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     GroupePeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new GroupePeer();
		}
		return self::$peer;
	}

	/**
	 * Clears out the collJGroupesProfesseurss collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addJGroupesProfesseurss()
	 */
	public function clearJGroupesProfesseurss()
	{
		$this->collJGroupesProfesseurss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collJGroupesProfesseurss collection.
	 *
	 * By default this just sets the collJGroupesProfesseurss collection to an empty array (like clearcollJGroupesProfesseurss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initJGroupesProfesseurss()
	{
		$this->collJGroupesProfesseurss = new PropelObjectCollection();
		$this->collJGroupesProfesseurss->setModel('JGroupesProfesseurs');
	}

	/**
	 * Gets an array of JGroupesProfesseurs objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Groupe is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     PropelCollection|array JGroupesProfesseurs[] List of JGroupesProfesseurs objects
	 * @throws     PropelException
	 */
	public function getJGroupesProfesseurss($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collJGroupesProfesseurss || null !== $criteria) {
			if ($this->isNew() && null === $this->collJGroupesProfesseurss) {
				// return empty collection
				$this->initJGroupesProfesseurss();
			} else {
				$collJGroupesProfesseurss = JGroupesProfesseursQuery::create(null, $criteria)
					->filterByGroupe($this)
					->find($con);
				if (null !== $criteria) {
					return $collJGroupesProfesseurss;
				}
				$this->collJGroupesProfesseurss = $collJGroupesProfesseurss;
			}
		}
		return $this->collJGroupesProfesseurss;
	}

	/**
	 * Returns the number of related JGroupesProfesseurs objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related JGroupesProfesseurs objects.
	 * @throws     PropelException
	 */
	public function countJGroupesProfesseurss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collJGroupesProfesseurss || null !== $criteria) {
			if ($this->isNew() && null === $this->collJGroupesProfesseurss) {
				return 0;
			} else {
				$query = JGroupesProfesseursQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByGroupe($this)
					->count($con);
			}
		} else {
			return count($this->collJGroupesProfesseurss);
		}
	}

	/**
	 * Method called to associate a JGroupesProfesseurs object to this object
	 * through the JGroupesProfesseurs foreign key attribute.
	 *
	 * @param      JGroupesProfesseurs $l JGroupesProfesseurs
	 * @return     void
	 * @throws     PropelException
	 */
	public function addJGroupesProfesseurs(JGroupesProfesseurs $l)
	{
		if ($this->collJGroupesProfesseurss === null) {
			$this->initJGroupesProfesseurss();
		}
		if (!$this->collJGroupesProfesseurss->contains($l)) { // only add it if the **same** object is not already associated
			$this->collJGroupesProfesseurss[]= $l;
			$l->setGroupe($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related JGroupesProfesseurss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getJGroupesProfesseurssJoinUtilisateurProfessionnel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = JGroupesProfesseursQuery::create(null, $criteria);
		$query->joinWith('JGroupesProfesseurs.UtilisateurProfessionnel', $join_behavior);

		return $this->getJGroupesProfesseurss($query, $con);
	}

	/**
	 * Clears out the collJGroupesClassess collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addJGroupesClassess()
	 */
	public function clearJGroupesClassess()
	{
		$this->collJGroupesClassess = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collJGroupesClassess collection.
	 *
	 * By default this just sets the collJGroupesClassess collection to an empty array (like clearcollJGroupesClassess());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initJGroupesClassess()
	{
		$this->collJGroupesClassess = new PropelObjectCollection();
		$this->collJGroupesClassess->setModel('JGroupesClasses');
	}

	/**
	 * Gets an array of JGroupesClasses objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Groupe is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     PropelCollection|array JGroupesClasses[] List of JGroupesClasses objects
	 * @throws     PropelException
	 */
	public function getJGroupesClassess($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collJGroupesClassess || null !== $criteria) {
			if ($this->isNew() && null === $this->collJGroupesClassess) {
				// return empty collection
				$this->initJGroupesClassess();
			} else {
				$collJGroupesClassess = JGroupesClassesQuery::create(null, $criteria)
					->filterByGroupe($this)
					->find($con);
				if (null !== $criteria) {
					return $collJGroupesClassess;
				}
				$this->collJGroupesClassess = $collJGroupesClassess;
			}
		}
		return $this->collJGroupesClassess;
	}

	/**
	 * Returns the number of related JGroupesClasses objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related JGroupesClasses objects.
	 * @throws     PropelException
	 */
	public function countJGroupesClassess(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collJGroupesClassess || null !== $criteria) {
			if ($this->isNew() && null === $this->collJGroupesClassess) {
				return 0;
			} else {
				$query = JGroupesClassesQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByGroupe($this)
					->count($con);
			}
		} else {
			return count($this->collJGroupesClassess);
		}
	}

	/**
	 * Method called to associate a JGroupesClasses object to this object
	 * through the JGroupesClasses foreign key attribute.
	 *
	 * @param      JGroupesClasses $l JGroupesClasses
	 * @return     void
	 * @throws     PropelException
	 */
	public function addJGroupesClasses(JGroupesClasses $l)
	{
		if ($this->collJGroupesClassess === null) {
			$this->initJGroupesClassess();
		}
		if (!$this->collJGroupesClassess->contains($l)) { // only add it if the **same** object is not already associated
			$this->collJGroupesClassess[]= $l;
			$l->setGroupe($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related JGroupesClassess from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getJGroupesClassessJoinClasse($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = JGroupesClassesQuery::create(null, $criteria);
		$query->joinWith('JGroupesClasses.Classe', $join_behavior);

		return $this->getJGroupesClassess($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related JGroupesClassess from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getJGroupesClassessJoinCategorieMatiere($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = JGroupesClassesQuery::create(null, $criteria);
		$query->joinWith('JGroupesClasses.CategorieMatiere', $join_behavior);

		return $this->getJGroupesClassess($query, $con);
	}

	/**
	 * Clears out the collCahierTexteCompteRendus collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCahierTexteCompteRendus()
	 */
	public function clearCahierTexteCompteRendus()
	{
		$this->collCahierTexteCompteRendus = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCahierTexteCompteRendus collection.
	 *
	 * By default this just sets the collCahierTexteCompteRendus collection to an empty array (like clearcollCahierTexteCompteRendus());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCahierTexteCompteRendus()
	{
		$this->collCahierTexteCompteRendus = new PropelObjectCollection();
		$this->collCahierTexteCompteRendus->setModel('CahierTexteCompteRendu');
	}

	/**
	 * Gets an array of CahierTexteCompteRendu objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Groupe is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     PropelCollection|array CahierTexteCompteRendu[] List of CahierTexteCompteRendu objects
	 * @throws     PropelException
	 */
	public function getCahierTexteCompteRendus($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collCahierTexteCompteRendus || null !== $criteria) {
			if ($this->isNew() && null === $this->collCahierTexteCompteRendus) {
				// return empty collection
				$this->initCahierTexteCompteRendus();
			} else {
				$collCahierTexteCompteRendus = CahierTexteCompteRenduQuery::create(null, $criteria)
					->filterByGroupe($this)
					->find($con);
				if (null !== $criteria) {
					return $collCahierTexteCompteRendus;
				}
				$this->collCahierTexteCompteRendus = $collCahierTexteCompteRendus;
			}
		}
		return $this->collCahierTexteCompteRendus;
	}

	/**
	 * Returns the number of related CahierTexteCompteRendu objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related CahierTexteCompteRendu objects.
	 * @throws     PropelException
	 */
	public function countCahierTexteCompteRendus(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collCahierTexteCompteRendus || null !== $criteria) {
			if ($this->isNew() && null === $this->collCahierTexteCompteRendus) {
				return 0;
			} else {
				$query = CahierTexteCompteRenduQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByGroupe($this)
					->count($con);
			}
		} else {
			return count($this->collCahierTexteCompteRendus);
		}
	}

	/**
	 * Method called to associate a CahierTexteCompteRendu object to this object
	 * through the CahierTexteCompteRendu foreign key attribute.
	 *
	 * @param      CahierTexteCompteRendu $l CahierTexteCompteRendu
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCahierTexteCompteRendu(CahierTexteCompteRendu $l)
	{
		if ($this->collCahierTexteCompteRendus === null) {
			$this->initCahierTexteCompteRendus();
		}
		if (!$this->collCahierTexteCompteRendus->contains($l)) { // only add it if the **same** object is not already associated
			$this->collCahierTexteCompteRendus[]= $l;
			$l->setGroupe($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related CahierTexteCompteRendus from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getCahierTexteCompteRendusJoinUtilisateurProfessionnel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = CahierTexteCompteRenduQuery::create(null, $criteria);
		$query->joinWith('CahierTexteCompteRendu.UtilisateurProfessionnel', $join_behavior);

		return $this->getCahierTexteCompteRendus($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related CahierTexteCompteRendus from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getCahierTexteCompteRendusJoinCahierTexteSequence($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = CahierTexteCompteRenduQuery::create(null, $criteria);
		$query->joinWith('CahierTexteCompteRendu.CahierTexteSequence', $join_behavior);

		return $this->getCahierTexteCompteRendus($query, $con);
	}

	/**
	 * Clears out the collCahierTexteTravailAFaires collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCahierTexteTravailAFaires()
	 */
	public function clearCahierTexteTravailAFaires()
	{
		$this->collCahierTexteTravailAFaires = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCahierTexteTravailAFaires collection.
	 *
	 * By default this just sets the collCahierTexteTravailAFaires collection to an empty array (like clearcollCahierTexteTravailAFaires());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCahierTexteTravailAFaires()
	{
		$this->collCahierTexteTravailAFaires = new PropelObjectCollection();
		$this->collCahierTexteTravailAFaires->setModel('CahierTexteTravailAFaire');
	}

	/**
	 * Gets an array of CahierTexteTravailAFaire objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Groupe is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     PropelCollection|array CahierTexteTravailAFaire[] List of CahierTexteTravailAFaire objects
	 * @throws     PropelException
	 */
	public function getCahierTexteTravailAFaires($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collCahierTexteTravailAFaires || null !== $criteria) {
			if ($this->isNew() && null === $this->collCahierTexteTravailAFaires) {
				// return empty collection
				$this->initCahierTexteTravailAFaires();
			} else {
				$collCahierTexteTravailAFaires = CahierTexteTravailAFaireQuery::create(null, $criteria)
					->filterByGroupe($this)
					->find($con);
				if (null !== $criteria) {
					return $collCahierTexteTravailAFaires;
				}
				$this->collCahierTexteTravailAFaires = $collCahierTexteTravailAFaires;
			}
		}
		return $this->collCahierTexteTravailAFaires;
	}

	/**
	 * Returns the number of related CahierTexteTravailAFaire objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related CahierTexteTravailAFaire objects.
	 * @throws     PropelException
	 */
	public function countCahierTexteTravailAFaires(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collCahierTexteTravailAFaires || null !== $criteria) {
			if ($this->isNew() && null === $this->collCahierTexteTravailAFaires) {
				return 0;
			} else {
				$query = CahierTexteTravailAFaireQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByGroupe($this)
					->count($con);
			}
		} else {
			return count($this->collCahierTexteTravailAFaires);
		}
	}

	/**
	 * Method called to associate a CahierTexteTravailAFaire object to this object
	 * through the CahierTexteTravailAFaire foreign key attribute.
	 *
	 * @param      CahierTexteTravailAFaire $l CahierTexteTravailAFaire
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCahierTexteTravailAFaire(CahierTexteTravailAFaire $l)
	{
		if ($this->collCahierTexteTravailAFaires === null) {
			$this->initCahierTexteTravailAFaires();
		}
		if (!$this->collCahierTexteTravailAFaires->contains($l)) { // only add it if the **same** object is not already associated
			$this->collCahierTexteTravailAFaires[]= $l;
			$l->setGroupe($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related CahierTexteTravailAFaires from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getCahierTexteTravailAFairesJoinUtilisateurProfessionnel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = CahierTexteTravailAFaireQuery::create(null, $criteria);
		$query->joinWith('CahierTexteTravailAFaire.UtilisateurProfessionnel', $join_behavior);

		return $this->getCahierTexteTravailAFaires($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related CahierTexteTravailAFaires from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getCahierTexteTravailAFairesJoinCahierTexteSequence($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = CahierTexteTravailAFaireQuery::create(null, $criteria);
		$query->joinWith('CahierTexteTravailAFaire.CahierTexteSequence', $join_behavior);

		return $this->getCahierTexteTravailAFaires($query, $con);
	}

	/**
	 * Clears out the collCahierTexteNoticePrivees collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCahierTexteNoticePrivees()
	 */
	public function clearCahierTexteNoticePrivees()
	{
		$this->collCahierTexteNoticePrivees = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCahierTexteNoticePrivees collection.
	 *
	 * By default this just sets the collCahierTexteNoticePrivees collection to an empty array (like clearcollCahierTexteNoticePrivees());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCahierTexteNoticePrivees()
	{
		$this->collCahierTexteNoticePrivees = new PropelObjectCollection();
		$this->collCahierTexteNoticePrivees->setModel('CahierTexteNoticePrivee');
	}

	/**
	 * Gets an array of CahierTexteNoticePrivee objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Groupe is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     PropelCollection|array CahierTexteNoticePrivee[] List of CahierTexteNoticePrivee objects
	 * @throws     PropelException
	 */
	public function getCahierTexteNoticePrivees($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collCahierTexteNoticePrivees || null !== $criteria) {
			if ($this->isNew() && null === $this->collCahierTexteNoticePrivees) {
				// return empty collection
				$this->initCahierTexteNoticePrivees();
			} else {
				$collCahierTexteNoticePrivees = CahierTexteNoticePriveeQuery::create(null, $criteria)
					->filterByGroupe($this)
					->find($con);
				if (null !== $criteria) {
					return $collCahierTexteNoticePrivees;
				}
				$this->collCahierTexteNoticePrivees = $collCahierTexteNoticePrivees;
			}
		}
		return $this->collCahierTexteNoticePrivees;
	}

	/**
	 * Returns the number of related CahierTexteNoticePrivee objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related CahierTexteNoticePrivee objects.
	 * @throws     PropelException
	 */
	public function countCahierTexteNoticePrivees(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collCahierTexteNoticePrivees || null !== $criteria) {
			if ($this->isNew() && null === $this->collCahierTexteNoticePrivees) {
				return 0;
			} else {
				$query = CahierTexteNoticePriveeQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByGroupe($this)
					->count($con);
			}
		} else {
			return count($this->collCahierTexteNoticePrivees);
		}
	}

	/**
	 * Method called to associate a CahierTexteNoticePrivee object to this object
	 * through the CahierTexteNoticePrivee foreign key attribute.
	 *
	 * @param      CahierTexteNoticePrivee $l CahierTexteNoticePrivee
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCahierTexteNoticePrivee(CahierTexteNoticePrivee $l)
	{
		if ($this->collCahierTexteNoticePrivees === null) {
			$this->initCahierTexteNoticePrivees();
		}
		if (!$this->collCahierTexteNoticePrivees->contains($l)) { // only add it if the **same** object is not already associated
			$this->collCahierTexteNoticePrivees[]= $l;
			$l->setGroupe($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related CahierTexteNoticePrivees from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getCahierTexteNoticePriveesJoinUtilisateurProfessionnel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = CahierTexteNoticePriveeQuery::create(null, $criteria);
		$query->joinWith('CahierTexteNoticePrivee.UtilisateurProfessionnel', $join_behavior);

		return $this->getCahierTexteNoticePrivees($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related CahierTexteNoticePrivees from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getCahierTexteNoticePriveesJoinCahierTexteSequence($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = CahierTexteNoticePriveeQuery::create(null, $criteria);
		$query->joinWith('CahierTexteNoticePrivee.CahierTexteSequence', $join_behavior);

		return $this->getCahierTexteNoticePrivees($query, $con);
	}

	/**
	 * Clears out the collJEleveGroupes collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addJEleveGroupes()
	 */
	public function clearJEleveGroupes()
	{
		$this->collJEleveGroupes = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collJEleveGroupes collection.
	 *
	 * By default this just sets the collJEleveGroupes collection to an empty array (like clearcollJEleveGroupes());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initJEleveGroupes()
	{
		$this->collJEleveGroupes = new PropelObjectCollection();
		$this->collJEleveGroupes->setModel('JEleveGroupe');
	}

	/**
	 * Gets an array of JEleveGroupe objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Groupe is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     PropelCollection|array JEleveGroupe[] List of JEleveGroupe objects
	 * @throws     PropelException
	 */
	public function getJEleveGroupes($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collJEleveGroupes || null !== $criteria) {
			if ($this->isNew() && null === $this->collJEleveGroupes) {
				// return empty collection
				$this->initJEleveGroupes();
			} else {
				$collJEleveGroupes = JEleveGroupeQuery::create(null, $criteria)
					->filterByGroupe($this)
					->find($con);
				if (null !== $criteria) {
					return $collJEleveGroupes;
				}
				$this->collJEleveGroupes = $collJEleveGroupes;
			}
		}
		return $this->collJEleveGroupes;
	}

	/**
	 * Returns the number of related JEleveGroupe objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related JEleveGroupe objects.
	 * @throws     PropelException
	 */
	public function countJEleveGroupes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collJEleveGroupes || null !== $criteria) {
			if ($this->isNew() && null === $this->collJEleveGroupes) {
				return 0;
			} else {
				$query = JEleveGroupeQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByGroupe($this)
					->count($con);
			}
		} else {
			return count($this->collJEleveGroupes);
		}
	}

	/**
	 * Method called to associate a JEleveGroupe object to this object
	 * through the JEleveGroupe foreign key attribute.
	 *
	 * @param      JEleveGroupe $l JEleveGroupe
	 * @return     void
	 * @throws     PropelException
	 */
	public function addJEleveGroupe(JEleveGroupe $l)
	{
		if ($this->collJEleveGroupes === null) {
			$this->initJEleveGroupes();
		}
		if (!$this->collJEleveGroupes->contains($l)) { // only add it if the **same** object is not already associated
			$this->collJEleveGroupes[]= $l;
			$l->setGroupe($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related JEleveGroupes from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getJEleveGroupesJoinEleve($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = JEleveGroupeQuery::create(null, $criteria);
		$query->joinWith('JEleveGroupe.Eleve', $join_behavior);

		return $this->getJEleveGroupes($query, $con);
	}

	/**
	 * Clears out the collCreditEctss collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addCreditEctss()
	 */
	public function clearCreditEctss()
	{
		$this->collCreditEctss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collCreditEctss collection.
	 *
	 * By default this just sets the collCreditEctss collection to an empty array (like clearcollCreditEctss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initCreditEctss()
	{
		$this->collCreditEctss = new PropelObjectCollection();
		$this->collCreditEctss->setModel('CreditEcts');
	}

	/**
	 * Gets an array of CreditEcts objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Groupe is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     PropelCollection|array CreditEcts[] List of CreditEcts objects
	 * @throws     PropelException
	 */
	public function getCreditEctss($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collCreditEctss || null !== $criteria) {
			if ($this->isNew() && null === $this->collCreditEctss) {
				// return empty collection
				$this->initCreditEctss();
			} else {
				$collCreditEctss = CreditEctsQuery::create(null, $criteria)
					->filterByGroupe($this)
					->find($con);
				if (null !== $criteria) {
					return $collCreditEctss;
				}
				$this->collCreditEctss = $collCreditEctss;
			}
		}
		return $this->collCreditEctss;
	}

	/**
	 * Returns the number of related CreditEcts objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related CreditEcts objects.
	 * @throws     PropelException
	 */
	public function countCreditEctss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collCreditEctss || null !== $criteria) {
			if ($this->isNew() && null === $this->collCreditEctss) {
				return 0;
			} else {
				$query = CreditEctsQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByGroupe($this)
					->count($con);
			}
		} else {
			return count($this->collCreditEctss);
		}
	}

	/**
	 * Method called to associate a CreditEcts object to this object
	 * through the CreditEcts foreign key attribute.
	 *
	 * @param      CreditEcts $l CreditEcts
	 * @return     void
	 * @throws     PropelException
	 */
	public function addCreditEcts(CreditEcts $l)
	{
		if ($this->collCreditEctss === null) {
			$this->initCreditEctss();
		}
		if (!$this->collCreditEctss->contains($l)) { // only add it if the **same** object is not already associated
			$this->collCreditEctss[]= $l;
			$l->setGroupe($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related CreditEctss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getCreditEctssJoinEleve($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = CreditEctsQuery::create(null, $criteria);
		$query->joinWith('CreditEcts.Eleve', $join_behavior);

		return $this->getCreditEctss($query, $con);
	}

	/**
	 * Clears out the collEdtEmplacementCourss collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addEdtEmplacementCourss()
	 */
	public function clearEdtEmplacementCourss()
	{
		$this->collEdtEmplacementCourss = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collEdtEmplacementCourss collection.
	 *
	 * By default this just sets the collEdtEmplacementCourss collection to an empty array (like clearcollEdtEmplacementCourss());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initEdtEmplacementCourss()
	{
		$this->collEdtEmplacementCourss = new PropelObjectCollection();
		$this->collEdtEmplacementCourss->setModel('EdtEmplacementCours');
	}

	/**
	 * Gets an array of EdtEmplacementCours objects which contain a foreign key that references this object.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Groupe is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria
	 * @param      PropelPDO $con
	 * @return     PropelCollection|array EdtEmplacementCours[] List of EdtEmplacementCours objects
	 * @throws     PropelException
	 */
	public function getEdtEmplacementCourss($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collEdtEmplacementCourss || null !== $criteria) {
			if ($this->isNew() && null === $this->collEdtEmplacementCourss) {
				// return empty collection
				$this->initEdtEmplacementCourss();
			} else {
				$collEdtEmplacementCourss = EdtEmplacementCoursQuery::create(null, $criteria)
					->filterByGroupe($this)
					->find($con);
				if (null !== $criteria) {
					return $collEdtEmplacementCourss;
				}
				$this->collEdtEmplacementCourss = $collEdtEmplacementCourss;
			}
		}
		return $this->collEdtEmplacementCourss;
	}

	/**
	 * Returns the number of related EdtEmplacementCours objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related EdtEmplacementCours objects.
	 * @throws     PropelException
	 */
	public function countEdtEmplacementCourss(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collEdtEmplacementCourss || null !== $criteria) {
			if ($this->isNew() && null === $this->collEdtEmplacementCourss) {
				return 0;
			} else {
				$query = EdtEmplacementCoursQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByGroupe($this)
					->count($con);
			}
		} else {
			return count($this->collEdtEmplacementCourss);
		}
	}

	/**
	 * Method called to associate a EdtEmplacementCours object to this object
	 * through the EdtEmplacementCours foreign key attribute.
	 *
	 * @param      EdtEmplacementCours $l EdtEmplacementCours
	 * @return     void
	 * @throws     PropelException
	 */
	public function addEdtEmplacementCours(EdtEmplacementCours $l)
	{
		if ($this->collEdtEmplacementCourss === null) {
			$this->initEdtEmplacementCourss();
		}
		if (!$this->collEdtEmplacementCourss->contains($l)) { // only add it if the **same** object is not already associated
			$this->collEdtEmplacementCourss[]= $l;
			$l->setGroupe($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related EdtEmplacementCourss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getEdtEmplacementCourssJoinAidDetails($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = EdtEmplacementCoursQuery::create(null, $criteria);
		$query->joinWith('EdtEmplacementCours.AidDetails', $join_behavior);

		return $this->getEdtEmplacementCourss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related EdtEmplacementCourss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getEdtEmplacementCourssJoinEdtSalle($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = EdtEmplacementCoursQuery::create(null, $criteria);
		$query->joinWith('EdtEmplacementCours.EdtSalle', $join_behavior);

		return $this->getEdtEmplacementCourss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related EdtEmplacementCourss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getEdtEmplacementCourssJoinEdtCreneau($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = EdtEmplacementCoursQuery::create(null, $criteria);
		$query->joinWith('EdtEmplacementCours.EdtCreneau', $join_behavior);

		return $this->getEdtEmplacementCourss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related EdtEmplacementCourss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getEdtEmplacementCourssJoinEdtCalendrierPeriode($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = EdtEmplacementCoursQuery::create(null, $criteria);
		$query->joinWith('EdtEmplacementCours.EdtCalendrierPeriode', $join_behavior);

		return $this->getEdtEmplacementCourss($query, $con);
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Groupe is new, it will return
	 * an empty collection; or if this Groupe has previously
	 * been saved, it will retrieve related EdtEmplacementCourss from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Groupe.
	 */
	public function getEdtEmplacementCourssJoinUtilisateurProfessionnel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		$query = EdtEmplacementCoursQuery::create(null, $criteria);
		$query->joinWith('EdtEmplacementCours.UtilisateurProfessionnel', $join_behavior);

		return $this->getEdtEmplacementCourss($query, $con);
	}

	/**
	 * Clears out the collUtilisateurProfessionnels collection
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addUtilisateurProfessionnels()
	 */
	public function clearUtilisateurProfessionnels()
	{
		$this->collUtilisateurProfessionnels = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collUtilisateurProfessionnels collection.
	 *
	 * By default this just sets the collUtilisateurProfessionnels collection to an empty collection (like clearUtilisateurProfessionnels());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initUtilisateurProfessionnels()
	{
		$this->collUtilisateurProfessionnels = new PropelObjectCollection();
		$this->collUtilisateurProfessionnels->setModel('UtilisateurProfessionnel');
	}

	/**
	 * Gets a collection of UtilisateurProfessionnel objects related by a many-to-many relationship
	 * to the current object by way of the j_groupes_professeurs cross-reference table.
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this Groupe is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria Optional query object to filter the query
	 * @param      PropelPDO $con Optional connection object
	 *
	 * @return     PropelCollection|array UtilisateurProfessionnel[] List of UtilisateurProfessionnel objects
	 */
	public function getUtilisateurProfessionnels($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collUtilisateurProfessionnels || null !== $criteria) {
			if ($this->isNew() && null === $this->collUtilisateurProfessionnels) {
				// return empty collection
				$this->initUtilisateurProfessionnels();
			} else {
				$collUtilisateurProfessionnels = UtilisateurProfessionnelQuery::create(null, $criteria)
					->filterByGroupe($this)
					->find($con);
				if (null !== $criteria) {
					return $collUtilisateurProfessionnels;
				}
				$this->collUtilisateurProfessionnels = $collUtilisateurProfessionnels;
			}
		}
		return $this->collUtilisateurProfessionnels;
	}

	/**
	 * Gets the number of UtilisateurProfessionnel objects related by a many-to-many relationship
	 * to the current object by way of the j_groupes_professeurs cross-reference table.
	 *
	 * @param      Criteria $criteria Optional query object to filter the query
	 * @param      boolean $distinct Set to true to force count distinct
	 * @param      PropelPDO $con Optional connection object
	 *
	 * @return     int the number of related UtilisateurProfessionnel objects
	 */
	public function countUtilisateurProfessionnels($criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if(null === $this->collUtilisateurProfessionnels || null !== $criteria) {
			if ($this->isNew() && null === $this->collUtilisateurProfessionnels) {
				return 0;
			} else {
				$query = UtilisateurProfessionnelQuery::create(null, $criteria);
				if($distinct) {
					$query->distinct();
				}
				return $query
					->filterByGroupe($this)
					->count($con);
			}
		} else {
			return count($this->collUtilisateurProfessionnels);
		}
	}

	/**
	 * Associate a UtilisateurProfessionnel object to this object
	 * through the j_groupes_professeurs cross reference table.
	 *
	 * @param      UtilisateurProfessionnel $utilisateurProfessionnel The JGroupesProfesseurs object to relate
	 * @return     void
	 */
	public function addUtilisateurProfessionnel($utilisateurProfessionnel)
	{
		if ($this->collUtilisateurProfessionnels === null) {
			$this->initUtilisateurProfessionnels();
		}
		if (!$this->collUtilisateurProfessionnels->contains($utilisateurProfessionnel)) { // only add it if the **same** object is not already associated
			$jGroupesProfesseurs = new JGroupesProfesseurs();
			$jGroupesProfesseurs->setUtilisateurProfessionnel($utilisateurProfessionnel);
			$this->addJGroupesProfesseurs($jGroupesProfesseurs);
			
			$this->collUtilisateurProfessionnels[]= $utilisateurProfessionnel;
		}
	}

	/**
	 * Clears the current object and sets all attributes to their default values
	 */
	public function clear()
	{
		$this->id = null;
		$this->name = null;
		$this->description = null;
		$this->recalcul_rang = null;
		$this->clearAllReferences();
		$this->setNew(true);
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collJGroupesProfesseurss) {
				foreach ((array) $this->collJGroupesProfesseurss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collJGroupesClassess) {
				foreach ((array) $this->collJGroupesClassess as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCahierTexteCompteRendus) {
				foreach ((array) $this->collCahierTexteCompteRendus as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCahierTexteTravailAFaires) {
				foreach ((array) $this->collCahierTexteTravailAFaires as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCahierTexteNoticePrivees) {
				foreach ((array) $this->collCahierTexteNoticePrivees as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collJEleveGroupes) {
				foreach ((array) $this->collJEleveGroupes as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collCreditEctss) {
				foreach ((array) $this->collCreditEctss as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collEdtEmplacementCourss) {
				foreach ((array) $this->collEdtEmplacementCourss as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collJGroupesProfesseurss = null;
		$this->collJGroupesClassess = null;
		$this->collCahierTexteCompteRendus = null;
		$this->collCahierTexteTravailAFaires = null;
		$this->collCahierTexteNoticePrivees = null;
		$this->collJEleveGroupes = null;
		$this->collCreditEctss = null;
		$this->collEdtEmplacementCourss = null;
	}

	/**
	 * Catches calls to virtual methods
	 */
	public function __call($name, $params)
	{
		if (preg_match('/get(\w+)/', $name, $matches) && $this->hasVirtualColumn($matches[1])) {
			return $this->getVirtualColumn($matches[1]);
		}
		throw new PropelException('Call to undefined method: ' . $name);
	}

} // BaseGroupe
