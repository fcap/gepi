<?php

/**
 * Base class that represents a row from the 'edt_cours' table.
 *
 * Liste de tous les creneaux de tous les emplois du temps
 *
 * @package    gepi.om
 */
abstract class BaseEdtEmplacementCours extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        EdtEmplacementCoursPeer
	 */
	protected static $peer;

	/**
	 * The value for the id_cours field.
	 * @var        int
	 */
	protected $id_cours;

	/**
	 * The value for the id_groupe field.
	 * @var        int
	 */
	protected $id_groupe;

	/**
	 * The value for the id_aid field.
	 * @var        int
	 */
	protected $id_aid;

	/**
	 * The value for the id_salle field.
	 * @var        int
	 */
	protected $id_salle;

	/**
	 * The value for the jour_semaine field.
	 * @var        string
	 */
	protected $jour_semaine;

	/**
	 * The value for the id_definie_periode field.
	 * @var        string
	 */
	protected $id_definie_periode;

	/**
	 * The value for the duree field.
	 * @var        string
	 */
	protected $duree;

	/**
	 * The value for the heuredeb_dec field.
	 * @var        string
	 */
	protected $heuredeb_dec;

	/**
	 * The value for the id_semaine field.
	 * @var        string
	 */
	protected $id_semaine;

	/**
	 * The value for the id_calendrier field.
	 * @var        string
	 */
	protected $id_calendrier;

	/**
	 * The value for the modif_edt field.
	 * @var        string
	 */
	protected $modif_edt;

	/**
	 * The value for the login_prof field.
	 * @var        string
	 */
	protected $login_prof;

	/**
	 * @var        Groupe
	 */
	protected $aGroupe;

	/**
	 * @var        AidDetails
	 */
	protected $aAidDetails;

	/**
	 * @var        EdtSalle
	 */
	protected $aEdtSalle;

	/**
	 * @var        EdtCreneau
	 */
	protected $aEdtCreneau;

	/**
	 * @var        EdtCalendrierPeriode
	 */
	protected $aEdtCalendrierPeriode;

	/**
	 * @var        UtilisateurProfessionnel
	 */
	protected $aUtilisateurProfessionnel;

	/**
	 * @var        array AbsenceEleveSaisie[] Collection to store aggregation of AbsenceEleveSaisie objects.
	 */
	protected $collAbsenceEleveSaisies;

	/**
	 * @var        Criteria The criteria used to select the current contents of collAbsenceEleveSaisies.
	 */
	private $lastAbsenceEleveSaisieCriteria = null;

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
	 * Initializes internal state of BaseEdtEmplacementCours object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
	}

	/**
	 * Get the [id_cours] column value.
	 * cle primaire
	 * @return     int
	 */
	public function getIdCours()
	{
		return $this->id_cours;
	}

	/**
	 * Get the [id_groupe] column value.
	 * id du groupe d'enseignement concerne - NULL sinon
	 * @return     int
	 */
	public function getIdGroupe()
	{
		return $this->id_groupe;
	}

	/**
	 * Get the [id_aid] column value.
	 * id de l'aid concerne - NULL sinon
	 * @return     int
	 */
	public function getIdAid()
	{
		return $this->id_aid;
	}

	/**
	 * Get the [id_salle] column value.
	 * id de la salle concernee
	 * @return     int
	 */
	public function getIdSalle()
	{
		return $this->id_salle;
	}

	/**
	 * Get the [jour_semaine] column value.
	 * jour de la semaine ou a lieu le cours : lundi, mardi etc...
	 * @return     string
	 */
	public function getJourSemaine()
	{
		return $this->jour_semaine;
	}

	/**
	 * Get the [id_definie_periode] column value.
	 * id du creneau de la journee ou a lieu le cours - voir table edt_creneaux 
	 * @return     string
	 */
	public function getIdDefiniePeriode()
	{
		return $this->id_definie_periode;
	}

	/**
	 * Get the [duree] column value.
	 * duree du cours definie en demi-heures.1h de cours correspond a une duree=2
	 * @return     string
	 */
	public function getDuree()
	{
		return $this->duree;
	}

	/**
	 * Get the [heuredeb_dec] column value.
	 * 0 si le cours commence au debut du creneau - 0.5 s'il commence au milieu
	 * @return     string
	 */
	public function getHeuredebDec()
	{
		return $this->heuredeb_dec;
	}

	/**
	 * Get the [id_semaine] column value.
	 * type de semaine - typiquement, 'A' ou 'B' si on a une alternance semaine A, semaine B
	 * @return     string
	 */
	public function getIdSemaine()
	{
		return $this->id_semaine;
	}

	/**
	 * Get the [id_calendrier] column value.
	 * NULL = le cours a lieu toute l'annee - sinon, id de la periode (EdtPeriodes) durant laquelle a lieu le cours
	 * @return     string
	 */
	public function getIdCalendrier()
	{
		return $this->id_calendrier;
	}

	/**
	 * Get the [modif_edt] column value.
	 * champ inutilise
	 * @return     string
	 */
	public function getModifEdt()
	{
		return $this->modif_edt;
	}

	/**
	 * Get the [login_prof] column value.
	 * login du prof qui dispense le cours
	 * @return     string
	 */
	public function getLoginProf()
	{
		return $this->login_prof;
	}

	/**
	 * Set the value of [id_cours] column.
	 * cle primaire
	 * @param      int $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setIdCours($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id_cours !== $v) {
			$this->id_cours = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::ID_COURS;
		}

		return $this;
	} // setIdCours()

	/**
	 * Set the value of [id_groupe] column.
	 * id du groupe d'enseignement concerne - NULL sinon
	 * @param      int $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setIdGroupe($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id_groupe !== $v) {
			$this->id_groupe = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::ID_GROUPE;
		}

		if ($this->aGroupe !== null && $this->aGroupe->getId() !== $v) {
			$this->aGroupe = null;
		}

		return $this;
	} // setIdGroupe()

	/**
	 * Set the value of [id_aid] column.
	 * id de l'aid concerne - NULL sinon
	 * @param      int $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setIdAid($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id_aid !== $v) {
			$this->id_aid = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::ID_AID;
		}

		if ($this->aAidDetails !== null && $this->aAidDetails->getId() !== $v) {
			$this->aAidDetails = null;
		}

		return $this;
	} // setIdAid()

	/**
	 * Set the value of [id_salle] column.
	 * id de la salle concernee
	 * @param      int $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setIdSalle($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id_salle !== $v) {
			$this->id_salle = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::ID_SALLE;
		}

		if ($this->aEdtSalle !== null && $this->aEdtSalle->getIdSalle() !== $v) {
			$this->aEdtSalle = null;
		}

		return $this;
	} // setIdSalle()

	/**
	 * Set the value of [jour_semaine] column.
	 * jour de la semaine ou a lieu le cours : lundi, mardi etc...
	 * @param      string $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setJourSemaine($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->jour_semaine !== $v) {
			$this->jour_semaine = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::JOUR_SEMAINE;
		}

		return $this;
	} // setJourSemaine()

	/**
	 * Set the value of [id_definie_periode] column.
	 * id du creneau de la journee ou a lieu le cours - voir table edt_creneaux 
	 * @param      string $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setIdDefiniePeriode($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->id_definie_periode !== $v) {
			$this->id_definie_periode = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::ID_DEFINIE_PERIODE;
		}

		if ($this->aEdtCreneau !== null && $this->aEdtCreneau->getIdDefiniePeriode() !== $v) {
			$this->aEdtCreneau = null;
		}

		return $this;
	} // setIdDefiniePeriode()

	/**
	 * Set the value of [duree] column.
	 * duree du cours definie en demi-heures.1h de cours correspond a une duree=2
	 * @param      string $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setDuree($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->duree !== $v) {
			$this->duree = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::DUREE;
		}

		return $this;
	} // setDuree()

	/**
	 * Set the value of [heuredeb_dec] column.
	 * 0 si le cours commence au debut du creneau - 0.5 s'il commence au milieu
	 * @param      string $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setHeuredebDec($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->heuredeb_dec !== $v) {
			$this->heuredeb_dec = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::HEUREDEB_DEC;
		}

		return $this;
	} // setHeuredebDec()

	/**
	 * Set the value of [id_semaine] column.
	 * type de semaine - typiquement, 'A' ou 'B' si on a une alternance semaine A, semaine B
	 * @param      string $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setIdSemaine($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->id_semaine !== $v) {
			$this->id_semaine = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::ID_SEMAINE;
		}

		return $this;
	} // setIdSemaine()

	/**
	 * Set the value of [id_calendrier] column.
	 * NULL = le cours a lieu toute l'annee - sinon, id de la periode (EdtPeriodes) durant laquelle a lieu le cours
	 * @param      string $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setIdCalendrier($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->id_calendrier !== $v) {
			$this->id_calendrier = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::ID_CALENDRIER;
		}

		if ($this->aEdtCalendrierPeriode !== null && $this->aEdtCalendrierPeriode->getIdCalendrier() !== $v) {
			$this->aEdtCalendrierPeriode = null;
		}

		return $this;
	} // setIdCalendrier()

	/**
	 * Set the value of [modif_edt] column.
	 * champ inutilise
	 * @param      string $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setModifEdt($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->modif_edt !== $v) {
			$this->modif_edt = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::MODIF_EDT;
		}

		return $this;
	} // setModifEdt()

	/**
	 * Set the value of [login_prof] column.
	 * login du prof qui dispense le cours
	 * @param      string $v new value
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 */
	public function setLoginProf($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->login_prof !== $v) {
			$this->login_prof = $v;
			$this->modifiedColumns[] = EdtEmplacementCoursPeer::LOGIN_PROF;
		}

		if ($this->aUtilisateurProfessionnel !== null && $this->aUtilisateurProfessionnel->getLogin() !== $v) {
			$this->aUtilisateurProfessionnel = null;
		}

		return $this;
	} // setLoginProf()

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
			// First, ensure that we don't have any columns that have been modified which aren't default columns.
			if (array_diff($this->modifiedColumns, array())) {
				return false;
			}

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

			$this->id_cours = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->id_groupe = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->id_aid = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->id_salle = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->jour_semaine = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->id_definie_periode = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->duree = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->heuredeb_dec = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
			$this->id_semaine = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->id_calendrier = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->modif_edt = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
			$this->login_prof = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 12; // 12 = EdtEmplacementCoursPeer::NUM_COLUMNS - EdtEmplacementCoursPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating EdtEmplacementCours object", $e);
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

		if ($this->aGroupe !== null && $this->id_groupe !== $this->aGroupe->getId()) {
			$this->aGroupe = null;
		}
		if ($this->aAidDetails !== null && $this->id_aid !== $this->aAidDetails->getId()) {
			$this->aAidDetails = null;
		}
		if ($this->aEdtSalle !== null && $this->id_salle !== $this->aEdtSalle->getIdSalle()) {
			$this->aEdtSalle = null;
		}
		if ($this->aEdtCreneau !== null && $this->id_definie_periode !== $this->aEdtCreneau->getIdDefiniePeriode()) {
			$this->aEdtCreneau = null;
		}
		if ($this->aEdtCalendrierPeriode !== null && $this->id_calendrier !== $this->aEdtCalendrierPeriode->getIdCalendrier()) {
			$this->aEdtCalendrierPeriode = null;
		}
		if ($this->aUtilisateurProfessionnel !== null && $this->login_prof !== $this->aUtilisateurProfessionnel->getLogin()) {
			$this->aUtilisateurProfessionnel = null;
		}
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
			$con = Propel::getConnection(EdtEmplacementCoursPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = EdtEmplacementCoursPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->aGroupe = null;
			$this->aAidDetails = null;
			$this->aEdtSalle = null;
			$this->aEdtCreneau = null;
			$this->aEdtCalendrierPeriode = null;
			$this->aUtilisateurProfessionnel = null;
			$this->collAbsenceEleveSaisies = null;
			$this->lastAbsenceEleveSaisieCriteria = null;

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
			$con = Propel::getConnection(EdtEmplacementCoursPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			EdtEmplacementCoursPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
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
			$con = Propel::getConnection(EdtEmplacementCoursPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			EdtEmplacementCoursPeer::addInstanceToPool($this);
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

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aGroupe !== null) {
				if ($this->aGroupe->isModified() || $this->aGroupe->isNew()) {
					$affectedRows += $this->aGroupe->save($con);
				}
				$this->setGroupe($this->aGroupe);
			}

			if ($this->aAidDetails !== null) {
				if ($this->aAidDetails->isModified() || $this->aAidDetails->isNew()) {
					$affectedRows += $this->aAidDetails->save($con);
				}
				$this->setAidDetails($this->aAidDetails);
			}

			if ($this->aEdtSalle !== null) {
				if ($this->aEdtSalle->isModified() || $this->aEdtSalle->isNew()) {
					$affectedRows += $this->aEdtSalle->save($con);
				}
				$this->setEdtSalle($this->aEdtSalle);
			}

			if ($this->aEdtCreneau !== null) {
				if ($this->aEdtCreneau->isModified() || $this->aEdtCreneau->isNew()) {
					$affectedRows += $this->aEdtCreneau->save($con);
				}
				$this->setEdtCreneau($this->aEdtCreneau);
			}

			if ($this->aEdtCalendrierPeriode !== null) {
				if ($this->aEdtCalendrierPeriode->isModified() || $this->aEdtCalendrierPeriode->isNew()) {
					$affectedRows += $this->aEdtCalendrierPeriode->save($con);
				}
				$this->setEdtCalendrierPeriode($this->aEdtCalendrierPeriode);
			}

			if ($this->aUtilisateurProfessionnel !== null) {
				if ($this->aUtilisateurProfessionnel->isModified() || $this->aUtilisateurProfessionnel->isNew()) {
					$affectedRows += $this->aUtilisateurProfessionnel->save($con);
				}
				$this->setUtilisateurProfessionnel($this->aUtilisateurProfessionnel);
			}


			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = EdtEmplacementCoursPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setNew(false);
				} else {
					$affectedRows += EdtEmplacementCoursPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collAbsenceEleveSaisies !== null) {
				foreach ($this->collAbsenceEleveSaisies as $referrerFK) {
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


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->aGroupe !== null) {
				if (!$this->aGroupe->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aGroupe->getValidationFailures());
				}
			}

			if ($this->aAidDetails !== null) {
				if (!$this->aAidDetails->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aAidDetails->getValidationFailures());
				}
			}

			if ($this->aEdtSalle !== null) {
				if (!$this->aEdtSalle->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aEdtSalle->getValidationFailures());
				}
			}

			if ($this->aEdtCreneau !== null) {
				if (!$this->aEdtCreneau->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aEdtCreneau->getValidationFailures());
				}
			}

			if ($this->aEdtCalendrierPeriode !== null) {
				if (!$this->aEdtCalendrierPeriode->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aEdtCalendrierPeriode->getValidationFailures());
				}
			}

			if ($this->aUtilisateurProfessionnel !== null) {
				if (!$this->aUtilisateurProfessionnel->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aUtilisateurProfessionnel->getValidationFailures());
				}
			}


			if (($retval = EdtEmplacementCoursPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collAbsenceEleveSaisies !== null) {
					foreach ($this->collAbsenceEleveSaisies as $referrerFK) {
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
		$pos = EdtEmplacementCoursPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getIdCours();
				break;
			case 1:
				return $this->getIdGroupe();
				break;
			case 2:
				return $this->getIdAid();
				break;
			case 3:
				return $this->getIdSalle();
				break;
			case 4:
				return $this->getJourSemaine();
				break;
			case 5:
				return $this->getIdDefiniePeriode();
				break;
			case 6:
				return $this->getDuree();
				break;
			case 7:
				return $this->getHeuredebDec();
				break;
			case 8:
				return $this->getIdSemaine();
				break;
			case 9:
				return $this->getIdCalendrier();
				break;
			case 10:
				return $this->getModifEdt();
				break;
			case 11:
				return $this->getLoginProf();
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
	 * @param      string $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                        BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. Defaults to BasePeer::TYPE_PHPNAME.
	 * @param      boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns.  Defaults to TRUE.
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = EdtEmplacementCoursPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getIdCours(),
			$keys[1] => $this->getIdGroupe(),
			$keys[2] => $this->getIdAid(),
			$keys[3] => $this->getIdSalle(),
			$keys[4] => $this->getJourSemaine(),
			$keys[5] => $this->getIdDefiniePeriode(),
			$keys[6] => $this->getDuree(),
			$keys[7] => $this->getHeuredebDec(),
			$keys[8] => $this->getIdSemaine(),
			$keys[9] => $this->getIdCalendrier(),
			$keys[10] => $this->getModifEdt(),
			$keys[11] => $this->getLoginProf(),
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
		$pos = EdtEmplacementCoursPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setIdCours($value);
				break;
			case 1:
				$this->setIdGroupe($value);
				break;
			case 2:
				$this->setIdAid($value);
				break;
			case 3:
				$this->setIdSalle($value);
				break;
			case 4:
				$this->setJourSemaine($value);
				break;
			case 5:
				$this->setIdDefiniePeriode($value);
				break;
			case 6:
				$this->setDuree($value);
				break;
			case 7:
				$this->setHeuredebDec($value);
				break;
			case 8:
				$this->setIdSemaine($value);
				break;
			case 9:
				$this->setIdCalendrier($value);
				break;
			case 10:
				$this->setModifEdt($value);
				break;
			case 11:
				$this->setLoginProf($value);
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
		$keys = EdtEmplacementCoursPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setIdCours($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setIdGroupe($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setIdAid($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setIdSalle($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setJourSemaine($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setIdDefiniePeriode($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setDuree($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setHeuredebDec($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setIdSemaine($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setIdCalendrier($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setModifEdt($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setLoginProf($arr[$keys[11]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(EdtEmplacementCoursPeer::DATABASE_NAME);

		if ($this->isColumnModified(EdtEmplacementCoursPeer::ID_COURS)) $criteria->add(EdtEmplacementCoursPeer::ID_COURS, $this->id_cours);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::ID_GROUPE)) $criteria->add(EdtEmplacementCoursPeer::ID_GROUPE, $this->id_groupe);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::ID_AID)) $criteria->add(EdtEmplacementCoursPeer::ID_AID, $this->id_aid);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::ID_SALLE)) $criteria->add(EdtEmplacementCoursPeer::ID_SALLE, $this->id_salle);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::JOUR_SEMAINE)) $criteria->add(EdtEmplacementCoursPeer::JOUR_SEMAINE, $this->jour_semaine);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::ID_DEFINIE_PERIODE)) $criteria->add(EdtEmplacementCoursPeer::ID_DEFINIE_PERIODE, $this->id_definie_periode);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::DUREE)) $criteria->add(EdtEmplacementCoursPeer::DUREE, $this->duree);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::HEUREDEB_DEC)) $criteria->add(EdtEmplacementCoursPeer::HEUREDEB_DEC, $this->heuredeb_dec);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::ID_SEMAINE)) $criteria->add(EdtEmplacementCoursPeer::ID_SEMAINE, $this->id_semaine);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::ID_CALENDRIER)) $criteria->add(EdtEmplacementCoursPeer::ID_CALENDRIER, $this->id_calendrier);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::MODIF_EDT)) $criteria->add(EdtEmplacementCoursPeer::MODIF_EDT, $this->modif_edt);
		if ($this->isColumnModified(EdtEmplacementCoursPeer::LOGIN_PROF)) $criteria->add(EdtEmplacementCoursPeer::LOGIN_PROF, $this->login_prof);

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
		$criteria = new Criteria(EdtEmplacementCoursPeer::DATABASE_NAME);

		$criteria->add(EdtEmplacementCoursPeer::ID_COURS, $this->id_cours);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getIdCours();
	}

	/**
	 * Generic method to set the primary key (id_cours column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setIdCours($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of EdtEmplacementCours (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setIdCours($this->id_cours);

		$copyObj->setIdGroupe($this->id_groupe);

		$copyObj->setIdAid($this->id_aid);

		$copyObj->setIdSalle($this->id_salle);

		$copyObj->setJourSemaine($this->jour_semaine);

		$copyObj->setIdDefiniePeriode($this->id_definie_periode);

		$copyObj->setDuree($this->duree);

		$copyObj->setHeuredebDec($this->heuredeb_dec);

		$copyObj->setIdSemaine($this->id_semaine);

		$copyObj->setIdCalendrier($this->id_calendrier);

		$copyObj->setModifEdt($this->modif_edt);

		$copyObj->setLoginProf($this->login_prof);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getAbsenceEleveSaisies() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addAbsenceEleveSaisie($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

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
	 * @return     EdtEmplacementCours Clone of current object.
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
	 * @return     EdtEmplacementCoursPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new EdtEmplacementCoursPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a Groupe object.
	 *
	 * @param      Groupe $v
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setGroupe(Groupe $v = null)
	{
		if ($v === null) {
			$this->setIdGroupe(NULL);
		} else {
			$this->setIdGroupe($v->getId());
		}

		$this->aGroupe = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the Groupe object, it will not be re-added.
		if ($v !== null) {
			$v->addEdtEmplacementCours($this);
		}

		return $this;
	}


	/**
	 * Get the associated Groupe object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     Groupe The associated Groupe object.
	 * @throws     PropelException
	 */
	public function getGroupe(PropelPDO $con = null)
	{
		if ($this->aGroupe === null && ($this->id_groupe !== null)) {
			$this->aGroupe = GroupePeer::retrieveByPK($this->id_groupe, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aGroupe->addEdtEmplacementCourss($this);
			 */
		}
		return $this->aGroupe;
	}

	/**
	 * Declares an association between this object and a AidDetails object.
	 *
	 * @param      AidDetails $v
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setAidDetails(AidDetails $v = null)
	{
		if ($v === null) {
			$this->setIdAid(NULL);
		} else {
			$this->setIdAid($v->getId());
		}

		$this->aAidDetails = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the AidDetails object, it will not be re-added.
		if ($v !== null) {
			$v->addEdtEmplacementCours($this);
		}

		return $this;
	}


	/**
	 * Get the associated AidDetails object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     AidDetails The associated AidDetails object.
	 * @throws     PropelException
	 */
	public function getAidDetails(PropelPDO $con = null)
	{
		if ($this->aAidDetails === null && ($this->id_aid !== null)) {
			$this->aAidDetails = AidDetailsPeer::retrieveByPK($this->id_aid, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aAidDetails->addEdtEmplacementCourss($this);
			 */
		}
		return $this->aAidDetails;
	}

	/**
	 * Declares an association between this object and a EdtSalle object.
	 *
	 * @param      EdtSalle $v
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setEdtSalle(EdtSalle $v = null)
	{
		if ($v === null) {
			$this->setIdSalle(NULL);
		} else {
			$this->setIdSalle($v->getIdSalle());
		}

		$this->aEdtSalle = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the EdtSalle object, it will not be re-added.
		if ($v !== null) {
			$v->addEdtEmplacementCours($this);
		}

		return $this;
	}


	/**
	 * Get the associated EdtSalle object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     EdtSalle The associated EdtSalle object.
	 * @throws     PropelException
	 */
	public function getEdtSalle(PropelPDO $con = null)
	{
		if ($this->aEdtSalle === null && ($this->id_salle !== null)) {
			$this->aEdtSalle = EdtSallePeer::retrieveByPK($this->id_salle, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aEdtSalle->addEdtEmplacementCourss($this);
			 */
		}
		return $this->aEdtSalle;
	}

	/**
	 * Declares an association between this object and a EdtCreneau object.
	 *
	 * @param      EdtCreneau $v
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setEdtCreneau(EdtCreneau $v = null)
	{
		if ($v === null) {
			$this->setIdDefiniePeriode(NULL);
		} else {
			$this->setIdDefiniePeriode($v->getIdDefiniePeriode());
		}

		$this->aEdtCreneau = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the EdtCreneau object, it will not be re-added.
		if ($v !== null) {
			$v->addEdtEmplacementCours($this);
		}

		return $this;
	}


	/**
	 * Get the associated EdtCreneau object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     EdtCreneau The associated EdtCreneau object.
	 * @throws     PropelException
	 */
	public function getEdtCreneau(PropelPDO $con = null)
	{
		if ($this->aEdtCreneau === null && (($this->id_definie_periode !== "" && $this->id_definie_periode !== null))) {
			$this->aEdtCreneau = EdtCreneauPeer::retrieveByPK($this->id_definie_periode, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aEdtCreneau->addEdtEmplacementCourss($this);
			 */
		}
		return $this->aEdtCreneau;
	}

	/**
	 * Declares an association between this object and a EdtCalendrierPeriode object.
	 *
	 * @param      EdtCalendrierPeriode $v
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setEdtCalendrierPeriode(EdtCalendrierPeriode $v = null)
	{
		if ($v === null) {
			$this->setIdCalendrier(NULL);
		} else {
			$this->setIdCalendrier($v->getIdCalendrier());
		}

		$this->aEdtCalendrierPeriode = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the EdtCalendrierPeriode object, it will not be re-added.
		if ($v !== null) {
			$v->addEdtEmplacementCours($this);
		}

		return $this;
	}


	/**
	 * Get the associated EdtCalendrierPeriode object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     EdtCalendrierPeriode The associated EdtCalendrierPeriode object.
	 * @throws     PropelException
	 */
	public function getEdtCalendrierPeriode(PropelPDO $con = null)
	{
		if ($this->aEdtCalendrierPeriode === null && (($this->id_calendrier !== "" && $this->id_calendrier !== null))) {
			$this->aEdtCalendrierPeriode = EdtCalendrierPeriodePeer::retrieveByPK($this->id_calendrier, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aEdtCalendrierPeriode->addEdtEmplacementCourss($this);
			 */
		}
		return $this->aEdtCalendrierPeriode;
	}

	/**
	 * Declares an association between this object and a UtilisateurProfessionnel object.
	 *
	 * @param      UtilisateurProfessionnel $v
	 * @return     EdtEmplacementCours The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setUtilisateurProfessionnel(UtilisateurProfessionnel $v = null)
	{
		if ($v === null) {
			$this->setLoginProf(NULL);
		} else {
			$this->setLoginProf($v->getLogin());
		}

		$this->aUtilisateurProfessionnel = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the UtilisateurProfessionnel object, it will not be re-added.
		if ($v !== null) {
			$v->addEdtEmplacementCours($this);
		}

		return $this;
	}


	/**
	 * Get the associated UtilisateurProfessionnel object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     UtilisateurProfessionnel The associated UtilisateurProfessionnel object.
	 * @throws     PropelException
	 */
	public function getUtilisateurProfessionnel(PropelPDO $con = null)
	{
		if ($this->aUtilisateurProfessionnel === null && (($this->login_prof !== "" && $this->login_prof !== null))) {
			$this->aUtilisateurProfessionnel = UtilisateurProfessionnelPeer::retrieveByPK($this->login_prof, $con);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aUtilisateurProfessionnel->addEdtEmplacementCourss($this);
			 */
		}
		return $this->aUtilisateurProfessionnel;
	}

	/**
	 * Clears out the collAbsenceEleveSaisies collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addAbsenceEleveSaisies()
	 */
	public function clearAbsenceEleveSaisies()
	{
		$this->collAbsenceEleveSaisies = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collAbsenceEleveSaisies collection (array).
	 *
	 * By default this just sets the collAbsenceEleveSaisies collection to an empty array (like clearcollAbsenceEleveSaisies());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initAbsenceEleveSaisies()
	{
		$this->collAbsenceEleveSaisies = array();
	}

	/**
	 * Gets an array of AbsenceEleveSaisie objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this EdtEmplacementCours has previously been saved, it will retrieve
	 * related AbsenceEleveSaisies from storage. If this EdtEmplacementCours is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array AbsenceEleveSaisie[]
	 * @throws     PropelException
	 */
	public function getAbsenceEleveSaisies($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EdtEmplacementCoursPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collAbsenceEleveSaisies === null) {
			if ($this->isNew()) {
			   $this->collAbsenceEleveSaisies = array();
			} else {

				$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

				AbsenceEleveSaisiePeer::addSelectColumns($criteria);
				$this->collAbsenceEleveSaisies = AbsenceEleveSaisiePeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

				AbsenceEleveSaisiePeer::addSelectColumns($criteria);
				if (!isset($this->lastAbsenceEleveSaisieCriteria) || !$this->lastAbsenceEleveSaisieCriteria->equals($criteria)) {
					$this->collAbsenceEleveSaisies = AbsenceEleveSaisiePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastAbsenceEleveSaisieCriteria = $criteria;
		return $this->collAbsenceEleveSaisies;
	}

	/**
	 * Returns the number of related AbsenceEleveSaisie objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related AbsenceEleveSaisie objects.
	 * @throws     PropelException
	 */
	public function countAbsenceEleveSaisies(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EdtEmplacementCoursPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collAbsenceEleveSaisies === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

				$count = AbsenceEleveSaisiePeer::doCount($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

				if (!isset($this->lastAbsenceEleveSaisieCriteria) || !$this->lastAbsenceEleveSaisieCriteria->equals($criteria)) {
					$count = AbsenceEleveSaisiePeer::doCount($criteria, $con);
				} else {
					$count = count($this->collAbsenceEleveSaisies);
				}
			} else {
				$count = count($this->collAbsenceEleveSaisies);
			}
		}
		$this->lastAbsenceEleveSaisieCriteria = $criteria;
		return $count;
	}

	/**
	 * Method called to associate a AbsenceEleveSaisie object to this object
	 * through the AbsenceEleveSaisie foreign key attribute.
	 *
	 * @param      AbsenceEleveSaisie $l AbsenceEleveSaisie
	 * @return     void
	 * @throws     PropelException
	 */
	public function addAbsenceEleveSaisie(AbsenceEleveSaisie $l)
	{
		if ($this->collAbsenceEleveSaisies === null) {
			$this->initAbsenceEleveSaisies();
		}
		if (!in_array($l, $this->collAbsenceEleveSaisies, true)) { // only add it if the **same** object is not already associated
			array_push($this->collAbsenceEleveSaisies, $l);
			$l->setEdtEmplacementCours($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this EdtEmplacementCours is new, it will return
	 * an empty collection; or if this EdtEmplacementCours has previously
	 * been saved, it will retrieve related AbsenceEleveSaisies from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in EdtEmplacementCours.
	 */
	public function getAbsenceEleveSaisiesJoinUtilisateurProfessionnel($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EdtEmplacementCoursPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collAbsenceEleveSaisies === null) {
			if ($this->isNew()) {
				$this->collAbsenceEleveSaisies = array();
			} else {

				$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

				$this->collAbsenceEleveSaisies = AbsenceEleveSaisiePeer::doSelectJoinUtilisateurProfessionnel($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

			if (!isset($this->lastAbsenceEleveSaisieCriteria) || !$this->lastAbsenceEleveSaisieCriteria->equals($criteria)) {
				$this->collAbsenceEleveSaisies = AbsenceEleveSaisiePeer::doSelectJoinUtilisateurProfessionnel($criteria, $con, $join_behavior);
			}
		}
		$this->lastAbsenceEleveSaisieCriteria = $criteria;

		return $this->collAbsenceEleveSaisies;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this EdtEmplacementCours is new, it will return
	 * an empty collection; or if this EdtEmplacementCours has previously
	 * been saved, it will retrieve related AbsenceEleveSaisies from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in EdtEmplacementCours.
	 */
	public function getAbsenceEleveSaisiesJoinEleve($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EdtEmplacementCoursPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collAbsenceEleveSaisies === null) {
			if ($this->isNew()) {
				$this->collAbsenceEleveSaisies = array();
			} else {

				$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

				$this->collAbsenceEleveSaisies = AbsenceEleveSaisiePeer::doSelectJoinEleve($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

			if (!isset($this->lastAbsenceEleveSaisieCriteria) || !$this->lastAbsenceEleveSaisieCriteria->equals($criteria)) {
				$this->collAbsenceEleveSaisies = AbsenceEleveSaisiePeer::doSelectJoinEleve($criteria, $con, $join_behavior);
			}
		}
		$this->lastAbsenceEleveSaisieCriteria = $criteria;

		return $this->collAbsenceEleveSaisies;
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this EdtEmplacementCours is new, it will return
	 * an empty collection; or if this EdtEmplacementCours has previously
	 * been saved, it will retrieve related AbsenceEleveSaisies from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in EdtEmplacementCours.
	 */
	public function getAbsenceEleveSaisiesJoinEdtCreneau($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(EdtEmplacementCoursPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collAbsenceEleveSaisies === null) {
			if ($this->isNew()) {
				$this->collAbsenceEleveSaisies = array();
			} else {

				$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

				$this->collAbsenceEleveSaisies = AbsenceEleveSaisiePeer::doSelectJoinEdtCreneau($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(AbsenceEleveSaisiePeer::ID_EDT_EMPLACEMENT_COURS, $this->id_cours);

			if (!isset($this->lastAbsenceEleveSaisieCriteria) || !$this->lastAbsenceEleveSaisieCriteria->equals($criteria)) {
				$this->collAbsenceEleveSaisies = AbsenceEleveSaisiePeer::doSelectJoinEdtCreneau($criteria, $con, $join_behavior);
			}
		}
		$this->lastAbsenceEleveSaisieCriteria = $criteria;

		return $this->collAbsenceEleveSaisies;
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
			if ($this->collAbsenceEleveSaisies) {
				foreach ((array) $this->collAbsenceEleveSaisies as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collAbsenceEleveSaisies = null;
			$this->aGroupe = null;
			$this->aAidDetails = null;
			$this->aEdtSalle = null;
			$this->aEdtCreneau = null;
			$this->aEdtCalendrierPeriode = null;
			$this->aUtilisateurProfessionnel = null;
	}

} // BaseEdtEmplacementCours
