<?php


/**
 * Base class that represents a query for the 'edt_calendrier' table.
 *
 * Liste des periodes datees de l'annee courante(pour definir par exemple les trimestres)
 *
 * @method     EdtCalendrierPeriodeQuery orderByIdCalendrier($order = Criteria::ASC) Order by the id_calendrier column
 * @method     EdtCalendrierPeriodeQuery orderByClasseConcerneCalendrier($order = Criteria::ASC) Order by the classe_concerne_calendrier column
 * @method     EdtCalendrierPeriodeQuery orderByNomCalendrier($order = Criteria::ASC) Order by the nom_calendrier column
 * @method     EdtCalendrierPeriodeQuery orderByDebutCalendrierTs($order = Criteria::ASC) Order by the debut_calendrier_ts column
 * @method     EdtCalendrierPeriodeQuery orderByFinCalendrierTs($order = Criteria::ASC) Order by the fin_calendrier_ts column
 * @method     EdtCalendrierPeriodeQuery orderByJourdebutCalendrier($order = Criteria::ASC) Order by the jourdebut_calendrier column
 * @method     EdtCalendrierPeriodeQuery orderByHeuredebutCalendrier($order = Criteria::ASC) Order by the heuredebut_calendrier column
 * @method     EdtCalendrierPeriodeQuery orderByJourfinCalendrier($order = Criteria::ASC) Order by the jourfin_calendrier column
 * @method     EdtCalendrierPeriodeQuery orderByHeurefinCalendrier($order = Criteria::ASC) Order by the heurefin_calendrier column
 * @method     EdtCalendrierPeriodeQuery orderByNumeroPeriode($order = Criteria::ASC) Order by the numero_periode column
 * @method     EdtCalendrierPeriodeQuery orderByEtabfermeCalendrier($order = Criteria::ASC) Order by the etabferme_calendrier column
 * @method     EdtCalendrierPeriodeQuery orderByEtabvacancesCalendrier($order = Criteria::ASC) Order by the etabvacances_calendrier column
 *
 * @method     EdtCalendrierPeriodeQuery groupByIdCalendrier() Group by the id_calendrier column
 * @method     EdtCalendrierPeriodeQuery groupByClasseConcerneCalendrier() Group by the classe_concerne_calendrier column
 * @method     EdtCalendrierPeriodeQuery groupByNomCalendrier() Group by the nom_calendrier column
 * @method     EdtCalendrierPeriodeQuery groupByDebutCalendrierTs() Group by the debut_calendrier_ts column
 * @method     EdtCalendrierPeriodeQuery groupByFinCalendrierTs() Group by the fin_calendrier_ts column
 * @method     EdtCalendrierPeriodeQuery groupByJourdebutCalendrier() Group by the jourdebut_calendrier column
 * @method     EdtCalendrierPeriodeQuery groupByHeuredebutCalendrier() Group by the heuredebut_calendrier column
 * @method     EdtCalendrierPeriodeQuery groupByJourfinCalendrier() Group by the jourfin_calendrier column
 * @method     EdtCalendrierPeriodeQuery groupByHeurefinCalendrier() Group by the heurefin_calendrier column
 * @method     EdtCalendrierPeriodeQuery groupByNumeroPeriode() Group by the numero_periode column
 * @method     EdtCalendrierPeriodeQuery groupByEtabfermeCalendrier() Group by the etabferme_calendrier column
 * @method     EdtCalendrierPeriodeQuery groupByEtabvacancesCalendrier() Group by the etabvacances_calendrier column
 *
 * @method     EdtCalendrierPeriodeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     EdtCalendrierPeriodeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     EdtCalendrierPeriodeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     EdtCalendrierPeriodeQuery leftJoinEdtEmplacementCours($relationAlias = '') Adds a LEFT JOIN clause to the query using the EdtEmplacementCours relation
 * @method     EdtCalendrierPeriodeQuery rightJoinEdtEmplacementCours($relationAlias = '') Adds a RIGHT JOIN clause to the query using the EdtEmplacementCours relation
 * @method     EdtCalendrierPeriodeQuery innerJoinEdtEmplacementCours($relationAlias = '') Adds a INNER JOIN clause to the query using the EdtEmplacementCours relation
 *
 * @method     EdtCalendrierPeriode findOne(PropelPDO $con = null) Return the first EdtCalendrierPeriode matching the query
 * @method     EdtCalendrierPeriode findOneByIdCalendrier(int $id_calendrier) Return the first EdtCalendrierPeriode filtered by the id_calendrier column
 * @method     EdtCalendrierPeriode findOneByClasseConcerneCalendrier(string $classe_concerne_calendrier) Return the first EdtCalendrierPeriode filtered by the classe_concerne_calendrier column
 * @method     EdtCalendrierPeriode findOneByNomCalendrier(string $nom_calendrier) Return the first EdtCalendrierPeriode filtered by the nom_calendrier column
 * @method     EdtCalendrierPeriode findOneByDebutCalendrierTs(string $debut_calendrier_ts) Return the first EdtCalendrierPeriode filtered by the debut_calendrier_ts column
 * @method     EdtCalendrierPeriode findOneByFinCalendrierTs(string $fin_calendrier_ts) Return the first EdtCalendrierPeriode filtered by the fin_calendrier_ts column
 * @method     EdtCalendrierPeriode findOneByJourdebutCalendrier(string $jourdebut_calendrier) Return the first EdtCalendrierPeriode filtered by the jourdebut_calendrier column
 * @method     EdtCalendrierPeriode findOneByHeuredebutCalendrier(string $heuredebut_calendrier) Return the first EdtCalendrierPeriode filtered by the heuredebut_calendrier column
 * @method     EdtCalendrierPeriode findOneByJourfinCalendrier(string $jourfin_calendrier) Return the first EdtCalendrierPeriode filtered by the jourfin_calendrier column
 * @method     EdtCalendrierPeriode findOneByHeurefinCalendrier(string $heurefin_calendrier) Return the first EdtCalendrierPeriode filtered by the heurefin_calendrier column
 * @method     EdtCalendrierPeriode findOneByNumeroPeriode(int $numero_periode) Return the first EdtCalendrierPeriode filtered by the numero_periode column
 * @method     EdtCalendrierPeriode findOneByEtabfermeCalendrier(int $etabferme_calendrier) Return the first EdtCalendrierPeriode filtered by the etabferme_calendrier column
 * @method     EdtCalendrierPeriode findOneByEtabvacancesCalendrier(int $etabvacances_calendrier) Return the first EdtCalendrierPeriode filtered by the etabvacances_calendrier column
 *
 * @method     array findByIdCalendrier(int $id_calendrier) Return EdtCalendrierPeriode objects filtered by the id_calendrier column
 * @method     array findByClasseConcerneCalendrier(string $classe_concerne_calendrier) Return EdtCalendrierPeriode objects filtered by the classe_concerne_calendrier column
 * @method     array findByNomCalendrier(string $nom_calendrier) Return EdtCalendrierPeriode objects filtered by the nom_calendrier column
 * @method     array findByDebutCalendrierTs(string $debut_calendrier_ts) Return EdtCalendrierPeriode objects filtered by the debut_calendrier_ts column
 * @method     array findByFinCalendrierTs(string $fin_calendrier_ts) Return EdtCalendrierPeriode objects filtered by the fin_calendrier_ts column
 * @method     array findByJourdebutCalendrier(string $jourdebut_calendrier) Return EdtCalendrierPeriode objects filtered by the jourdebut_calendrier column
 * @method     array findByHeuredebutCalendrier(string $heuredebut_calendrier) Return EdtCalendrierPeriode objects filtered by the heuredebut_calendrier column
 * @method     array findByJourfinCalendrier(string $jourfin_calendrier) Return EdtCalendrierPeriode objects filtered by the jourfin_calendrier column
 * @method     array findByHeurefinCalendrier(string $heurefin_calendrier) Return EdtCalendrierPeriode objects filtered by the heurefin_calendrier column
 * @method     array findByNumeroPeriode(int $numero_periode) Return EdtCalendrierPeriode objects filtered by the numero_periode column
 * @method     array findByEtabfermeCalendrier(int $etabferme_calendrier) Return EdtCalendrierPeriode objects filtered by the etabferme_calendrier column
 * @method     array findByEtabvacancesCalendrier(int $etabvacances_calendrier) Return EdtCalendrierPeriode objects filtered by the etabvacances_calendrier column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseEdtCalendrierPeriodeQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseEdtCalendrierPeriodeQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'EdtCalendrierPeriode', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new EdtCalendrierPeriodeQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    EdtCalendrierPeriodeQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof EdtCalendrierPeriodeQuery) {
			return $criteria;
		}
		$query = new EdtCalendrierPeriodeQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key
	 * Use instance pooling to avoid a database query if the object exists
	 * <code>
	 * $obj  = $c->findPk(12, $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = EdtCalendrierPeriodePeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			$stmt = $this
				->filterByPrimaryKey($key)
				->getSelectStatement($con);
			return $this->getFormatter()->formatOne($stmt);
		}
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(12, 56, 832), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{	
		return $this
			->filterByPrimaryKeys($keys)
			->find($con);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(EdtCalendrierPeriodePeer::ID_CALENDRIER, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(EdtCalendrierPeriodePeer::ID_CALENDRIER, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id_calendrier column
	 * 
	 * @param     int|array $idCalendrier The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByIdCalendrier($idCalendrier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($idCalendrier)) {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::ID_CALENDRIER, $idCalendrier, Criteria::IN);
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::ID_CALENDRIER, $idCalendrier, $comparison);
		}
	}

	/**
	 * Filter the query on the classe_concerne_calendrier column
	 * 
	 * @param     string $classeConcerneCalendrier The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByClasseConcerneCalendrier($classeConcerneCalendrier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($classeConcerneCalendrier)) {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::CLASSE_CONCERNE_CALENDRIER, $classeConcerneCalendrier, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $classeConcerneCalendrier)) {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::CLASSE_CONCERNE_CALENDRIER, str_replace('*', '%', $classeConcerneCalendrier), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::CLASSE_CONCERNE_CALENDRIER, $classeConcerneCalendrier, $comparison);
		}
	}

	/**
	 * Filter the query on the nom_calendrier column
	 * 
	 * @param     string $nomCalendrier The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByNomCalendrier($nomCalendrier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($nomCalendrier)) {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::NOM_CALENDRIER, $nomCalendrier, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $nomCalendrier)) {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::NOM_CALENDRIER, str_replace('*', '%', $nomCalendrier), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::NOM_CALENDRIER, $nomCalendrier, $comparison);
		}
	}

	/**
	 * Filter the query on the debut_calendrier_ts column
	 * 
	 * @param     string|array $debutCalendrierTs The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByDebutCalendrierTs($debutCalendrierTs = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($debutCalendrierTs)) {
			if (array_values($debutCalendrierTs) === $debutCalendrierTs) {
				return $this->addUsingAlias(EdtCalendrierPeriodePeer::DEBUT_CALENDRIER_TS, $debutCalendrierTs, Criteria::IN);
			} else {
				if (isset($debutCalendrierTs['min'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::DEBUT_CALENDRIER_TS, $debutCalendrierTs['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($debutCalendrierTs['max'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::DEBUT_CALENDRIER_TS, $debutCalendrierTs['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::DEBUT_CALENDRIER_TS, $debutCalendrierTs, $comparison);
		}
	}

	/**
	 * Filter the query on the fin_calendrier_ts column
	 * 
	 * @param     string|array $finCalendrierTs The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByFinCalendrierTs($finCalendrierTs = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($finCalendrierTs)) {
			if (array_values($finCalendrierTs) === $finCalendrierTs) {
				return $this->addUsingAlias(EdtCalendrierPeriodePeer::FIN_CALENDRIER_TS, $finCalendrierTs, Criteria::IN);
			} else {
				if (isset($finCalendrierTs['min'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::FIN_CALENDRIER_TS, $finCalendrierTs['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($finCalendrierTs['max'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::FIN_CALENDRIER_TS, $finCalendrierTs['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::FIN_CALENDRIER_TS, $finCalendrierTs, $comparison);
		}
	}

	/**
	 * Filter the query on the jourdebut_calendrier column
	 * 
	 * @param     string|array $jourdebutCalendrier The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByJourdebutCalendrier($jourdebutCalendrier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($jourdebutCalendrier)) {
			if (array_values($jourdebutCalendrier) === $jourdebutCalendrier) {
				return $this->addUsingAlias(EdtCalendrierPeriodePeer::JOURDEBUT_CALENDRIER, $jourdebutCalendrier, Criteria::IN);
			} else {
				if (isset($jourdebutCalendrier['min'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::JOURDEBUT_CALENDRIER, $jourdebutCalendrier['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($jourdebutCalendrier['max'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::JOURDEBUT_CALENDRIER, $jourdebutCalendrier['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::JOURDEBUT_CALENDRIER, $jourdebutCalendrier, $comparison);
		}
	}

	/**
	 * Filter the query on the heuredebut_calendrier column
	 * 
	 * @param     string|array $heuredebutCalendrier The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByHeuredebutCalendrier($heuredebutCalendrier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($heuredebutCalendrier)) {
			if (array_values($heuredebutCalendrier) === $heuredebutCalendrier) {
				return $this->addUsingAlias(EdtCalendrierPeriodePeer::HEUREDEBUT_CALENDRIER, $heuredebutCalendrier, Criteria::IN);
			} else {
				if (isset($heuredebutCalendrier['min'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::HEUREDEBUT_CALENDRIER, $heuredebutCalendrier['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($heuredebutCalendrier['max'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::HEUREDEBUT_CALENDRIER, $heuredebutCalendrier['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::HEUREDEBUT_CALENDRIER, $heuredebutCalendrier, $comparison);
		}
	}

	/**
	 * Filter the query on the jourfin_calendrier column
	 * 
	 * @param     string|array $jourfinCalendrier The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByJourfinCalendrier($jourfinCalendrier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($jourfinCalendrier)) {
			if (array_values($jourfinCalendrier) === $jourfinCalendrier) {
				return $this->addUsingAlias(EdtCalendrierPeriodePeer::JOURFIN_CALENDRIER, $jourfinCalendrier, Criteria::IN);
			} else {
				if (isset($jourfinCalendrier['min'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::JOURFIN_CALENDRIER, $jourfinCalendrier['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($jourfinCalendrier['max'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::JOURFIN_CALENDRIER, $jourfinCalendrier['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::JOURFIN_CALENDRIER, $jourfinCalendrier, $comparison);
		}
	}

	/**
	 * Filter the query on the heurefin_calendrier column
	 * 
	 * @param     string|array $heurefinCalendrier The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByHeurefinCalendrier($heurefinCalendrier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($heurefinCalendrier)) {
			if (array_values($heurefinCalendrier) === $heurefinCalendrier) {
				return $this->addUsingAlias(EdtCalendrierPeriodePeer::HEUREFIN_CALENDRIER, $heurefinCalendrier, Criteria::IN);
			} else {
				if (isset($heurefinCalendrier['min'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::HEUREFIN_CALENDRIER, $heurefinCalendrier['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($heurefinCalendrier['max'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::HEUREFIN_CALENDRIER, $heurefinCalendrier['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::HEUREFIN_CALENDRIER, $heurefinCalendrier, $comparison);
		}
	}

	/**
	 * Filter the query on the numero_periode column
	 * 
	 * @param     int|array $numeroPeriode The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByNumeroPeriode($numeroPeriode = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($numeroPeriode)) {
			if (array_values($numeroPeriode) === $numeroPeriode) {
				return $this->addUsingAlias(EdtCalendrierPeriodePeer::NUMERO_PERIODE, $numeroPeriode, Criteria::IN);
			} else {
				if (isset($numeroPeriode['min'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::NUMERO_PERIODE, $numeroPeriode['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($numeroPeriode['max'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::NUMERO_PERIODE, $numeroPeriode['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::NUMERO_PERIODE, $numeroPeriode, $comparison);
		}
	}

	/**
	 * Filter the query on the etabferme_calendrier column
	 * 
	 * @param     int|array $etabfermeCalendrier The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByEtabfermeCalendrier($etabfermeCalendrier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($etabfermeCalendrier)) {
			if (array_values($etabfermeCalendrier) === $etabfermeCalendrier) {
				return $this->addUsingAlias(EdtCalendrierPeriodePeer::ETABFERME_CALENDRIER, $etabfermeCalendrier, Criteria::IN);
			} else {
				if (isset($etabfermeCalendrier['min'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::ETABFERME_CALENDRIER, $etabfermeCalendrier['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($etabfermeCalendrier['max'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::ETABFERME_CALENDRIER, $etabfermeCalendrier['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::ETABFERME_CALENDRIER, $etabfermeCalendrier, $comparison);
		}
	}

	/**
	 * Filter the query on the etabvacances_calendrier column
	 * 
	 * @param     int|array $etabvacancesCalendrier The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByEtabvacancesCalendrier($etabvacancesCalendrier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($etabvacancesCalendrier)) {
			if (array_values($etabvacancesCalendrier) === $etabvacancesCalendrier) {
				return $this->addUsingAlias(EdtCalendrierPeriodePeer::ETABVACANCES_CALENDRIER, $etabvacancesCalendrier, Criteria::IN);
			} else {
				if (isset($etabvacancesCalendrier['min'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::ETABVACANCES_CALENDRIER, $etabvacancesCalendrier['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($etabvacancesCalendrier['max'])) {
					$this->addUsingAlias(EdtCalendrierPeriodePeer::ETABVACANCES_CALENDRIER, $etabvacancesCalendrier['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCalendrierPeriodePeer::ETABVACANCES_CALENDRIER, $etabvacancesCalendrier, $comparison);
		}
	}

	/**
	 * Filter the query by a related EdtEmplacementCours object
	 *
	 * @param     EdtEmplacementCours $edtEmplacementCours  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function filterByEdtEmplacementCours($edtEmplacementCours, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(EdtCalendrierPeriodePeer::ID_CALENDRIER, $edtEmplacementCours->getIdCalendrier(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the EdtEmplacementCours relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function joinEdtEmplacementCours($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('EdtEmplacementCours');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'EdtEmplacementCours');
		}
		
		return $this;
	}

	/**
	 * Use the EdtEmplacementCours relation EdtEmplacementCours object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    EdtEmplacementCoursQuery A secondary query class using the current class as primary query
	 */
	public function useEdtEmplacementCoursQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinEdtEmplacementCours($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'EdtEmplacementCours', 'EdtEmplacementCoursQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     EdtCalendrierPeriode $edtCalendrierPeriode Object to remove from the list of results
	 *
	 * @return    EdtCalendrierPeriodeQuery The current query, for fluid interface
	 */
	public function prune($edtCalendrierPeriode = null)
	{
		if ($edtCalendrierPeriode) {
			$this->addUsingAlias(EdtCalendrierPeriodePeer::ID_CALENDRIER, $edtCalendrierPeriode->getIdCalendrier(), Criteria::NOT_EQUAL);
	  }
	  
		return $this;
	}

	/**
	 * Code to execute before every SELECT statement
	 * 
	 * @param     PropelPDO $con The connection object used by the query
	 */
	protected function basePreSelect(PropelPDO $con)
	{
		return $this->preSelect($con);
	}

	/**
	 * Code to execute before every DELETE statement
	 * 
	 * @param     PropelPDO $con The connection object used by the query
	 */
	protected function basePreDelete(PropelPDO $con)
	{
		return $this->preDelete($con);
	}

	/**
	 * Code to execute before every UPDATE statement
	 * 
	 * @param     array $values The associatiove array of columns and values for the update
	 * @param     PropelPDO $con The connection object used by the query
	 */
	protected function basePreUpdate(&$values, PropelPDO $con)
	{
		return $this->preUpdate($values, $con);
	}

} // BaseEdtCalendrierPeriodeQuery
