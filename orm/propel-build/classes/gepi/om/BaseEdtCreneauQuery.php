<?php


/**
 * Base class that represents a query for the 'edt_creneaux' table.
 *
 * Table contenant les creneaux de chaque journee (M1, M2...S1, S2...)
 *
 * @method     EdtCreneauQuery orderByIdDefiniePeriode($order = Criteria::ASC) Order by the id_definie_periode column
 * @method     EdtCreneauQuery orderByNomDefiniePeriode($order = Criteria::ASC) Order by the nom_definie_periode column
 * @method     EdtCreneauQuery orderByHeuredebutDefiniePeriode($order = Criteria::ASC) Order by the heuredebut_definie_periode column
 * @method     EdtCreneauQuery orderByHeurefinDefiniePeriode($order = Criteria::ASC) Order by the heurefin_definie_periode column
 * @method     EdtCreneauQuery orderBySuiviDefiniePeriode($order = Criteria::ASC) Order by the suivi_definie_periode column
 * @method     EdtCreneauQuery orderByTypeCreneau($order = Criteria::ASC) Order by the type_creneau column
 * @method     EdtCreneauQuery orderByJourCreneau($order = Criteria::ASC) Order by the jour_creneau column
 *
 * @method     EdtCreneauQuery groupByIdDefiniePeriode() Group by the id_definie_periode column
 * @method     EdtCreneauQuery groupByNomDefiniePeriode() Group by the nom_definie_periode column
 * @method     EdtCreneauQuery groupByHeuredebutDefiniePeriode() Group by the heuredebut_definie_periode column
 * @method     EdtCreneauQuery groupByHeurefinDefiniePeriode() Group by the heurefin_definie_periode column
 * @method     EdtCreneauQuery groupBySuiviDefiniePeriode() Group by the suivi_definie_periode column
 * @method     EdtCreneauQuery groupByTypeCreneau() Group by the type_creneau column
 * @method     EdtCreneauQuery groupByJourCreneau() Group by the jour_creneau column
 *
 * @method     EdtCreneauQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     EdtCreneauQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     EdtCreneauQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     EdtCreneauQuery leftJoinAbsenceEleveSaisie($relationAlias = '') Adds a LEFT JOIN clause to the query using the AbsenceEleveSaisie relation
 * @method     EdtCreneauQuery rightJoinAbsenceEleveSaisie($relationAlias = '') Adds a RIGHT JOIN clause to the query using the AbsenceEleveSaisie relation
 * @method     EdtCreneauQuery innerJoinAbsenceEleveSaisie($relationAlias = '') Adds a INNER JOIN clause to the query using the AbsenceEleveSaisie relation
 *
 * @method     EdtCreneauQuery leftJoinEdtEmplacementCours($relationAlias = '') Adds a LEFT JOIN clause to the query using the EdtEmplacementCours relation
 * @method     EdtCreneauQuery rightJoinEdtEmplacementCours($relationAlias = '') Adds a RIGHT JOIN clause to the query using the EdtEmplacementCours relation
 * @method     EdtCreneauQuery innerJoinEdtEmplacementCours($relationAlias = '') Adds a INNER JOIN clause to the query using the EdtEmplacementCours relation
 *
 * @method     EdtCreneau findOne(PropelPDO $con = null) Return the first EdtCreneau matching the query
 * @method     EdtCreneau findOneByIdDefiniePeriode(int $id_definie_periode) Return the first EdtCreneau filtered by the id_definie_periode column
 * @method     EdtCreneau findOneByNomDefiniePeriode(string $nom_definie_periode) Return the first EdtCreneau filtered by the nom_definie_periode column
 * @method     EdtCreneau findOneByHeuredebutDefiniePeriode(string $heuredebut_definie_periode) Return the first EdtCreneau filtered by the heuredebut_definie_periode column
 * @method     EdtCreneau findOneByHeurefinDefiniePeriode(string $heurefin_definie_periode) Return the first EdtCreneau filtered by the heurefin_definie_periode column
 * @method     EdtCreneau findOneBySuiviDefiniePeriode(int $suivi_definie_periode) Return the first EdtCreneau filtered by the suivi_definie_periode column
 * @method     EdtCreneau findOneByTypeCreneau(string $type_creneau) Return the first EdtCreneau filtered by the type_creneau column
 * @method     EdtCreneau findOneByJourCreneau(string $jour_creneau) Return the first EdtCreneau filtered by the jour_creneau column
 *
 * @method     array findByIdDefiniePeriode(int $id_definie_periode) Return EdtCreneau objects filtered by the id_definie_periode column
 * @method     array findByNomDefiniePeriode(string $nom_definie_periode) Return EdtCreneau objects filtered by the nom_definie_periode column
 * @method     array findByHeuredebutDefiniePeriode(string $heuredebut_definie_periode) Return EdtCreneau objects filtered by the heuredebut_definie_periode column
 * @method     array findByHeurefinDefiniePeriode(string $heurefin_definie_periode) Return EdtCreneau objects filtered by the heurefin_definie_periode column
 * @method     array findBySuiviDefiniePeriode(int $suivi_definie_periode) Return EdtCreneau objects filtered by the suivi_definie_periode column
 * @method     array findByTypeCreneau(string $type_creneau) Return EdtCreneau objects filtered by the type_creneau column
 * @method     array findByJourCreneau(string $jour_creneau) Return EdtCreneau objects filtered by the jour_creneau column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseEdtCreneauQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseEdtCreneauQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'EdtCreneau', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new EdtCreneauQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    EdtCreneauQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof EdtCreneauQuery) {
			return $criteria;
		}
		$query = new EdtCreneauQuery();
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
		if ((null !== ($obj = EdtCreneauPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			return $this
				->filterByPrimaryKey($key)
				->findOne($con);
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
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(EdtCreneauPeer::ID_DEFINIE_PERIODE, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(EdtCreneauPeer::ID_DEFINIE_PERIODE, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id_definie_periode column
	 * 
	 * @param     int|array $idDefiniePeriode The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByIdDefiniePeriode($idDefiniePeriode = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($idDefiniePeriode)) {
			return $this->addUsingAlias(EdtCreneauPeer::ID_DEFINIE_PERIODE, $idDefiniePeriode, Criteria::IN);
		} else {
			return $this->addUsingAlias(EdtCreneauPeer::ID_DEFINIE_PERIODE, $idDefiniePeriode, $comparison);
		}
	}

	/**
	 * Filter the query on the nom_definie_periode column
	 * 
	 * @param     string $nomDefiniePeriode The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByNomDefiniePeriode($nomDefiniePeriode = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($nomDefiniePeriode)) {
			return $this->addUsingAlias(EdtCreneauPeer::NOM_DEFINIE_PERIODE, $nomDefiniePeriode, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $nomDefiniePeriode)) {
			return $this->addUsingAlias(EdtCreneauPeer::NOM_DEFINIE_PERIODE, str_replace('*', '%', $nomDefiniePeriode), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(EdtCreneauPeer::NOM_DEFINIE_PERIODE, $nomDefiniePeriode, $comparison);
		}
	}

	/**
	 * Filter the query on the heuredebut_definie_periode column
	 * 
	 * @param     string|array $heuredebutDefiniePeriode The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByHeuredebutDefiniePeriode($heuredebutDefiniePeriode = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($heuredebutDefiniePeriode)) {
			if (array_values($heuredebutDefiniePeriode) === $heuredebutDefiniePeriode) {
				return $this->addUsingAlias(EdtCreneauPeer::HEUREDEBUT_DEFINIE_PERIODE, $heuredebutDefiniePeriode, Criteria::IN);
			} else {
				if (isset($heuredebutDefiniePeriode['min'])) {
					$this->addUsingAlias(EdtCreneauPeer::HEUREDEBUT_DEFINIE_PERIODE, $heuredebutDefiniePeriode['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($heuredebutDefiniePeriode['max'])) {
					$this->addUsingAlias(EdtCreneauPeer::HEUREDEBUT_DEFINIE_PERIODE, $heuredebutDefiniePeriode['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCreneauPeer::HEUREDEBUT_DEFINIE_PERIODE, $heuredebutDefiniePeriode, $comparison);
		}
	}

	/**
	 * Filter the query on the heurefin_definie_periode column
	 * 
	 * @param     string|array $heurefinDefiniePeriode The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByHeurefinDefiniePeriode($heurefinDefiniePeriode = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($heurefinDefiniePeriode)) {
			if (array_values($heurefinDefiniePeriode) === $heurefinDefiniePeriode) {
				return $this->addUsingAlias(EdtCreneauPeer::HEUREFIN_DEFINIE_PERIODE, $heurefinDefiniePeriode, Criteria::IN);
			} else {
				if (isset($heurefinDefiniePeriode['min'])) {
					$this->addUsingAlias(EdtCreneauPeer::HEUREFIN_DEFINIE_PERIODE, $heurefinDefiniePeriode['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($heurefinDefiniePeriode['max'])) {
					$this->addUsingAlias(EdtCreneauPeer::HEUREFIN_DEFINIE_PERIODE, $heurefinDefiniePeriode['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCreneauPeer::HEUREFIN_DEFINIE_PERIODE, $heurefinDefiniePeriode, $comparison);
		}
	}

	/**
	 * Filter the query on the suivi_definie_periode column
	 * 
	 * @param     int|array $suiviDefiniePeriode The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterBySuiviDefiniePeriode($suiviDefiniePeriode = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($suiviDefiniePeriode)) {
			if (array_values($suiviDefiniePeriode) === $suiviDefiniePeriode) {
				return $this->addUsingAlias(EdtCreneauPeer::SUIVI_DEFINIE_PERIODE, $suiviDefiniePeriode, Criteria::IN);
			} else {
				if (isset($suiviDefiniePeriode['min'])) {
					$this->addUsingAlias(EdtCreneauPeer::SUIVI_DEFINIE_PERIODE, $suiviDefiniePeriode['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($suiviDefiniePeriode['max'])) {
					$this->addUsingAlias(EdtCreneauPeer::SUIVI_DEFINIE_PERIODE, $suiviDefiniePeriode['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(EdtCreneauPeer::SUIVI_DEFINIE_PERIODE, $suiviDefiniePeriode, $comparison);
		}
	}

	/**
	 * Filter the query on the type_creneau column
	 * 
	 * @param     string $typeCreneau The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByTypeCreneau($typeCreneau = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($typeCreneau)) {
			return $this->addUsingAlias(EdtCreneauPeer::TYPE_CRENEAU, $typeCreneau, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $typeCreneau)) {
			return $this->addUsingAlias(EdtCreneauPeer::TYPE_CRENEAU, str_replace('*', '%', $typeCreneau), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(EdtCreneauPeer::TYPE_CRENEAU, $typeCreneau, $comparison);
		}
	}

	/**
	 * Filter the query on the jour_creneau column
	 * 
	 * @param     string $jourCreneau The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByJourCreneau($jourCreneau = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($jourCreneau)) {
			return $this->addUsingAlias(EdtCreneauPeer::JOUR_CRENEAU, $jourCreneau, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $jourCreneau)) {
			return $this->addUsingAlias(EdtCreneauPeer::JOUR_CRENEAU, str_replace('*', '%', $jourCreneau), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(EdtCreneauPeer::JOUR_CRENEAU, $jourCreneau, $comparison);
		}
	}

	/**
	 * Filter the query by a related AbsenceEleveSaisie object
	 *
	 * @param     AbsenceEleveSaisie $absenceEleveSaisie  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByAbsenceEleveSaisie($absenceEleveSaisie, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(EdtCreneauPeer::ID_DEFINIE_PERIODE, $absenceEleveSaisie->getIdEdtCreneau(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the AbsenceEleveSaisie relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function joinAbsenceEleveSaisie($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AbsenceEleveSaisie');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'AbsenceEleveSaisie');
		}
		
		return $this;
	}

	/**
	 * Use the AbsenceEleveSaisie relation AbsenceEleveSaisie object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveSaisieQuery A secondary query class using the current class as primary query
	 */
	public function useAbsenceEleveSaisieQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinAbsenceEleveSaisie($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AbsenceEleveSaisie', 'AbsenceEleveSaisieQuery');
	}

	/**
	 * Filter the query by a related EdtEmplacementCours object
	 *
	 * @param     EdtEmplacementCours $edtEmplacementCours  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function filterByEdtEmplacementCours($edtEmplacementCours, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(EdtCreneauPeer::ID_DEFINIE_PERIODE, $edtEmplacementCours->getIdDefiniePeriode(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the EdtEmplacementCours relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function joinEdtEmplacementCours($relationAlias = '', $joinType = Criteria::INNER_JOIN)
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
	public function useEdtEmplacementCoursQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinEdtEmplacementCours($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'EdtEmplacementCours', 'EdtEmplacementCoursQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     EdtCreneau $edtCreneau Object to remove from the list of results
	 *
	 * @return    EdtCreneauQuery The current query, for fluid interface
	 */
	public function prune($edtCreneau = null)
	{
		if ($edtCreneau) {
			$this->addUsingAlias(EdtCreneauPeer::ID_DEFINIE_PERIODE, $edtCreneau->getIdDefiniePeriode(), Criteria::NOT_EQUAL);
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

} // BaseEdtCreneauQuery
