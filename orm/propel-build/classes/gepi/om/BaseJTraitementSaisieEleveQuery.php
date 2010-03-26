<?php


/**
 * Base class that represents a query for the 'j_traitements_saisies' table.
 *
 * Table de jointure entre la saisie et le traitement des absences
 *
 * @method     JTraitementSaisieEleveQuery orderByASaisieId($order = Criteria::ASC) Order by the a_saisie_id column
 * @method     JTraitementSaisieEleveQuery orderByATraitementId($order = Criteria::ASC) Order by the a_traitement_id column
 *
 * @method     JTraitementSaisieEleveQuery groupByASaisieId() Group by the a_saisie_id column
 * @method     JTraitementSaisieEleveQuery groupByATraitementId() Group by the a_traitement_id column
 *
 * @method     JTraitementSaisieEleveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     JTraitementSaisieEleveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     JTraitementSaisieEleveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     JTraitementSaisieEleveQuery leftJoinAbsenceEleveSaisie($relationAlias = '') Adds a LEFT JOIN clause to the query using the AbsenceEleveSaisie relation
 * @method     JTraitementSaisieEleveQuery rightJoinAbsenceEleveSaisie($relationAlias = '') Adds a RIGHT JOIN clause to the query using the AbsenceEleveSaisie relation
 * @method     JTraitementSaisieEleveQuery innerJoinAbsenceEleveSaisie($relationAlias = '') Adds a INNER JOIN clause to the query using the AbsenceEleveSaisie relation
 *
 * @method     JTraitementSaisieEleveQuery leftJoinAbsenceEleveTraitement($relationAlias = '') Adds a LEFT JOIN clause to the query using the AbsenceEleveTraitement relation
 * @method     JTraitementSaisieEleveQuery rightJoinAbsenceEleveTraitement($relationAlias = '') Adds a RIGHT JOIN clause to the query using the AbsenceEleveTraitement relation
 * @method     JTraitementSaisieEleveQuery innerJoinAbsenceEleveTraitement($relationAlias = '') Adds a INNER JOIN clause to the query using the AbsenceEleveTraitement relation
 *
 * @method     JTraitementSaisieEleve findOne(PropelPDO $con = null) Return the first JTraitementSaisieEleve matching the query
 * @method     JTraitementSaisieEleve findOneByASaisieId(int $a_saisie_id) Return the first JTraitementSaisieEleve filtered by the a_saisie_id column
 * @method     JTraitementSaisieEleve findOneByATraitementId(int $a_traitement_id) Return the first JTraitementSaisieEleve filtered by the a_traitement_id column
 *
 * @method     array findByASaisieId(int $a_saisie_id) Return JTraitementSaisieEleve objects filtered by the a_saisie_id column
 * @method     array findByATraitementId(int $a_traitement_id) Return JTraitementSaisieEleve objects filtered by the a_traitement_id column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseJTraitementSaisieEleveQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseJTraitementSaisieEleveQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'JTraitementSaisieEleve', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new JTraitementSaisieEleveQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    JTraitementSaisieEleveQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof JTraitementSaisieEleveQuery) {
			return $criteria;
		}
		$query = new JTraitementSaisieEleveQuery();
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
	 * <code>
	 * $obj = $c->findPk(array(34, 634), $con);
	 * </code>
	 * @param     mixed $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = JTraitementSaisieElevePeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
	 * @return    JTraitementSaisieEleveQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		$this->addUsingAlias(JTraitementSaisieElevePeer::A_SAISIE_ID, $key[0], Criteria::EQUAL);
		$this->addUsingAlias(JTraitementSaisieElevePeer::A_TRAITEMENT_ID, $key[1], Criteria::EQUAL);
		
		return $this;
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    JTraitementSaisieEleveQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		foreach ($keys as $key) {
			$cton0 = $this->getNewCriterion(JTraitementSaisieElevePeer::A_SAISIE_ID, $key[0], Criteria::EQUAL);
			$cton1 = $this->getNewCriterion(JTraitementSaisieElevePeer::A_TRAITEMENT_ID, $key[1], Criteria::EQUAL);
			$cton0->addAnd($cton1);
			$this->addOr($cton0);
		}
		
		return $this;
	}

	/**
	 * Filter the query on the a_saisie_id column
	 * 
	 * @param     int|array $aSaisieId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JTraitementSaisieEleveQuery The current query, for fluid interface
	 */
	public function filterByASaisieId($aSaisieId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($aSaisieId)) {
			return $this->addUsingAlias(JTraitementSaisieElevePeer::A_SAISIE_ID, $aSaisieId, Criteria::IN);
		} else {
			return $this->addUsingAlias(JTraitementSaisieElevePeer::A_SAISIE_ID, $aSaisieId, $comparison);
		}
	}

	/**
	 * Filter the query on the a_traitement_id column
	 * 
	 * @param     int|array $aTraitementId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JTraitementSaisieEleveQuery The current query, for fluid interface
	 */
	public function filterByATraitementId($aTraitementId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($aTraitementId)) {
			return $this->addUsingAlias(JTraitementSaisieElevePeer::A_TRAITEMENT_ID, $aTraitementId, Criteria::IN);
		} else {
			return $this->addUsingAlias(JTraitementSaisieElevePeer::A_TRAITEMENT_ID, $aTraitementId, $comparison);
		}
	}

	/**
	 * Filter the query by a related AbsenceEleveSaisie object
	 *
	 * @param     AbsenceEleveSaisie $absenceEleveSaisie  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JTraitementSaisieEleveQuery The current query, for fluid interface
	 */
	public function filterByAbsenceEleveSaisie($absenceEleveSaisie, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(JTraitementSaisieElevePeer::A_SAISIE_ID, $absenceEleveSaisie->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the AbsenceEleveSaisie relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JTraitementSaisieEleveQuery The current query, for fluid interface
	 */
	public function joinAbsenceEleveSaisie($relationAlias = '', $joinType = Criteria::INNER_JOIN)
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
	public function useAbsenceEleveSaisieQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinAbsenceEleveSaisie($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AbsenceEleveSaisie', 'AbsenceEleveSaisieQuery');
	}

	/**
	 * Filter the query by a related AbsenceEleveTraitement object
	 *
	 * @param     AbsenceEleveTraitement $absenceEleveTraitement  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JTraitementSaisieEleveQuery The current query, for fluid interface
	 */
	public function filterByAbsenceEleveTraitement($absenceEleveTraitement, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(JTraitementSaisieElevePeer::A_TRAITEMENT_ID, $absenceEleveTraitement->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the AbsenceEleveTraitement relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JTraitementSaisieEleveQuery The current query, for fluid interface
	 */
	public function joinAbsenceEleveTraitement($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AbsenceEleveTraitement');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'AbsenceEleveTraitement');
		}
		
		return $this;
	}

	/**
	 * Use the AbsenceEleveTraitement relation AbsenceEleveTraitement object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveTraitementQuery A secondary query class using the current class as primary query
	 */
	public function useAbsenceEleveTraitementQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinAbsenceEleveTraitement($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AbsenceEleveTraitement', 'AbsenceEleveTraitementQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     JTraitementSaisieEleve $jTraitementSaisieEleve Object to remove from the list of results
	 *
	 * @return    JTraitementSaisieEleveQuery The current query, for fluid interface
	 */
	public function prune($jTraitementSaisieEleve = null)
	{
		if ($jTraitementSaisieEleve) {
			$this->addCond('pruneCond0', $this->getAliasedColName(JTraitementSaisieElevePeer::A_SAISIE_ID), $jTraitementSaisieEleve->getASaisieId(), Criteria::NOT_EQUAL);
			$this->addCond('pruneCond1', $this->getAliasedColName(JTraitementSaisieElevePeer::A_TRAITEMENT_ID), $jTraitementSaisieEleve->getATraitementId(), Criteria::NOT_EQUAL);
			$this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
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

} // BaseJTraitementSaisieEleveQuery
