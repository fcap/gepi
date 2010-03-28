<?php


/**
 * Base class that represents a query for the 'groupes' table.
 *
 * Groupe d'eleves permettant d'y affecter une matiere et un professeurs
 *
 * @method     GroupeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     GroupeQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     GroupeQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     GroupeQuery orderByRecalculRang($order = Criteria::ASC) Order by the recalcul_rang column
 *
 * @method     GroupeQuery groupById() Group by the id column
 * @method     GroupeQuery groupByName() Group by the name column
 * @method     GroupeQuery groupByDescription() Group by the description column
 * @method     GroupeQuery groupByRecalculRang() Group by the recalcul_rang column
 *
 * @method     GroupeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     GroupeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     GroupeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     GroupeQuery leftJoinJGroupesProfesseurs($relationAlias = '') Adds a LEFT JOIN clause to the query using the JGroupesProfesseurs relation
 * @method     GroupeQuery rightJoinJGroupesProfesseurs($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JGroupesProfesseurs relation
 * @method     GroupeQuery innerJoinJGroupesProfesseurs($relationAlias = '') Adds a INNER JOIN clause to the query using the JGroupesProfesseurs relation
 *
 * @method     GroupeQuery leftJoinJGroupesClasses($relationAlias = '') Adds a LEFT JOIN clause to the query using the JGroupesClasses relation
 * @method     GroupeQuery rightJoinJGroupesClasses($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JGroupesClasses relation
 * @method     GroupeQuery innerJoinJGroupesClasses($relationAlias = '') Adds a INNER JOIN clause to the query using the JGroupesClasses relation
 *
 * @method     GroupeQuery leftJoinCahierTexteCompteRendu($relationAlias = '') Adds a LEFT JOIN clause to the query using the CahierTexteCompteRendu relation
 * @method     GroupeQuery rightJoinCahierTexteCompteRendu($relationAlias = '') Adds a RIGHT JOIN clause to the query using the CahierTexteCompteRendu relation
 * @method     GroupeQuery innerJoinCahierTexteCompteRendu($relationAlias = '') Adds a INNER JOIN clause to the query using the CahierTexteCompteRendu relation
 *
 * @method     GroupeQuery leftJoinCahierTexteTravailAFaire($relationAlias = '') Adds a LEFT JOIN clause to the query using the CahierTexteTravailAFaire relation
 * @method     GroupeQuery rightJoinCahierTexteTravailAFaire($relationAlias = '') Adds a RIGHT JOIN clause to the query using the CahierTexteTravailAFaire relation
 * @method     GroupeQuery innerJoinCahierTexteTravailAFaire($relationAlias = '') Adds a INNER JOIN clause to the query using the CahierTexteTravailAFaire relation
 *
 * @method     GroupeQuery leftJoinCahierTexteNoticePrivee($relationAlias = '') Adds a LEFT JOIN clause to the query using the CahierTexteNoticePrivee relation
 * @method     GroupeQuery rightJoinCahierTexteNoticePrivee($relationAlias = '') Adds a RIGHT JOIN clause to the query using the CahierTexteNoticePrivee relation
 * @method     GroupeQuery innerJoinCahierTexteNoticePrivee($relationAlias = '') Adds a INNER JOIN clause to the query using the CahierTexteNoticePrivee relation
 *
 * @method     GroupeQuery leftJoinJEleveGroupe($relationAlias = '') Adds a LEFT JOIN clause to the query using the JEleveGroupe relation
 * @method     GroupeQuery rightJoinJEleveGroupe($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JEleveGroupe relation
 * @method     GroupeQuery innerJoinJEleveGroupe($relationAlias = '') Adds a INNER JOIN clause to the query using the JEleveGroupe relation
 *
 * @method     GroupeQuery leftJoinCreditEcts($relationAlias = '') Adds a LEFT JOIN clause to the query using the CreditEcts relation
 * @method     GroupeQuery rightJoinCreditEcts($relationAlias = '') Adds a RIGHT JOIN clause to the query using the CreditEcts relation
 * @method     GroupeQuery innerJoinCreditEcts($relationAlias = '') Adds a INNER JOIN clause to the query using the CreditEcts relation
 *
 * @method     GroupeQuery leftJoinEdtEmplacementCours($relationAlias = '') Adds a LEFT JOIN clause to the query using the EdtEmplacementCours relation
 * @method     GroupeQuery rightJoinEdtEmplacementCours($relationAlias = '') Adds a RIGHT JOIN clause to the query using the EdtEmplacementCours relation
 * @method     GroupeQuery innerJoinEdtEmplacementCours($relationAlias = '') Adds a INNER JOIN clause to the query using the EdtEmplacementCours relation
 *
 * @method     Groupe findOne(PropelPDO $con = null) Return the first Groupe matching the query
 * @method     Groupe findOneById(int $id) Return the first Groupe filtered by the id column
 * @method     Groupe findOneByName(string $name) Return the first Groupe filtered by the name column
 * @method     Groupe findOneByDescription(string $description) Return the first Groupe filtered by the description column
 * @method     Groupe findOneByRecalculRang(string $recalcul_rang) Return the first Groupe filtered by the recalcul_rang column
 *
 * @method     array findById(int $id) Return Groupe objects filtered by the id column
 * @method     array findByName(string $name) Return Groupe objects filtered by the name column
 * @method     array findByDescription(string $description) Return Groupe objects filtered by the description column
 * @method     array findByRecalculRang(string $recalcul_rang) Return Groupe objects filtered by the recalcul_rang column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseGroupeQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseGroupeQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'Groupe', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new GroupeQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    GroupeQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof GroupeQuery) {
			return $criteria;
		}
		$query = new GroupeQuery();
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
	 * @return    Groupe|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = GroupePeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
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
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(GroupePeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(GroupePeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($id)) {
			return $this->addUsingAlias(GroupePeer::ID, $id, Criteria::IN);
		} else {
			return $this->addUsingAlias(GroupePeer::ID, $id, $comparison);
		}
	}

	/**
	 * Filter the query on the name column
	 * 
	 * @param     string $name The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByName($name = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($name)) {
			return $this->addUsingAlias(GroupePeer::NAME, $name, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $name)) {
			return $this->addUsingAlias(GroupePeer::NAME, str_replace('*', '%', $name), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(GroupePeer::NAME, $name, $comparison);
		}
	}

	/**
	 * Filter the query on the description column
	 * 
	 * @param     string $description The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByDescription($description = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($description)) {
			return $this->addUsingAlias(GroupePeer::DESCRIPTION, $description, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $description)) {
			return $this->addUsingAlias(GroupePeer::DESCRIPTION, str_replace('*', '%', $description), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(GroupePeer::DESCRIPTION, $description, $comparison);
		}
	}

	/**
	 * Filter the query on the recalcul_rang column
	 * 
	 * @param     string $recalculRang The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByRecalculRang($recalculRang = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($recalculRang)) {
			return $this->addUsingAlias(GroupePeer::RECALCUL_RANG, $recalculRang, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $recalculRang)) {
			return $this->addUsingAlias(GroupePeer::RECALCUL_RANG, str_replace('*', '%', $recalculRang), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(GroupePeer::RECALCUL_RANG, $recalculRang, $comparison);
		}
	}

	/**
	 * Filter the query by a related JGroupesProfesseurs object
	 *
	 * @param     JGroupesProfesseurs $jGroupesProfesseurs  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByJGroupesProfesseurs($jGroupesProfesseurs, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(GroupePeer::ID, $jGroupesProfesseurs->getIdGroupe(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JGroupesProfesseurs relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function joinJGroupesProfesseurs($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JGroupesProfesseurs');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JGroupesProfesseurs');
		}
		
		return $this;
	}

	/**
	 * Use the JGroupesProfesseurs relation JGroupesProfesseurs object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JGroupesProfesseursQuery A secondary query class using the current class as primary query
	 */
	public function useJGroupesProfesseursQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJGroupesProfesseurs($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JGroupesProfesseurs', 'JGroupesProfesseursQuery');
	}

	/**
	 * Filter the query by a related JGroupesClasses object
	 *
	 * @param     JGroupesClasses $jGroupesClasses  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByJGroupesClasses($jGroupesClasses, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(GroupePeer::ID, $jGroupesClasses->getIdGroupe(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JGroupesClasses relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function joinJGroupesClasses($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JGroupesClasses');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JGroupesClasses');
		}
		
		return $this;
	}

	/**
	 * Use the JGroupesClasses relation JGroupesClasses object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JGroupesClassesQuery A secondary query class using the current class as primary query
	 */
	public function useJGroupesClassesQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJGroupesClasses($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JGroupesClasses', 'JGroupesClassesQuery');
	}

	/**
	 * Filter the query by a related CahierTexteCompteRendu object
	 *
	 * @param     CahierTexteCompteRendu $cahierTexteCompteRendu  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByCahierTexteCompteRendu($cahierTexteCompteRendu, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(GroupePeer::ID, $cahierTexteCompteRendu->getIdGroupe(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the CahierTexteCompteRendu relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function joinCahierTexteCompteRendu($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('CahierTexteCompteRendu');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'CahierTexteCompteRendu');
		}
		
		return $this;
	}

	/**
	 * Use the CahierTexteCompteRendu relation CahierTexteCompteRendu object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    CahierTexteCompteRenduQuery A secondary query class using the current class as primary query
	 */
	public function useCahierTexteCompteRenduQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinCahierTexteCompteRendu($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'CahierTexteCompteRendu', 'CahierTexteCompteRenduQuery');
	}

	/**
	 * Filter the query by a related CahierTexteTravailAFaire object
	 *
	 * @param     CahierTexteTravailAFaire $cahierTexteTravailAFaire  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByCahierTexteTravailAFaire($cahierTexteTravailAFaire, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(GroupePeer::ID, $cahierTexteTravailAFaire->getIdGroupe(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the CahierTexteTravailAFaire relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function joinCahierTexteTravailAFaire($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('CahierTexteTravailAFaire');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'CahierTexteTravailAFaire');
		}
		
		return $this;
	}

	/**
	 * Use the CahierTexteTravailAFaire relation CahierTexteTravailAFaire object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    CahierTexteTravailAFaireQuery A secondary query class using the current class as primary query
	 */
	public function useCahierTexteTravailAFaireQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinCahierTexteTravailAFaire($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'CahierTexteTravailAFaire', 'CahierTexteTravailAFaireQuery');
	}

	/**
	 * Filter the query by a related CahierTexteNoticePrivee object
	 *
	 * @param     CahierTexteNoticePrivee $cahierTexteNoticePrivee  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByCahierTexteNoticePrivee($cahierTexteNoticePrivee, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(GroupePeer::ID, $cahierTexteNoticePrivee->getIdGroupe(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the CahierTexteNoticePrivee relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function joinCahierTexteNoticePrivee($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('CahierTexteNoticePrivee');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'CahierTexteNoticePrivee');
		}
		
		return $this;
	}

	/**
	 * Use the CahierTexteNoticePrivee relation CahierTexteNoticePrivee object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    CahierTexteNoticePriveeQuery A secondary query class using the current class as primary query
	 */
	public function useCahierTexteNoticePriveeQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinCahierTexteNoticePrivee($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'CahierTexteNoticePrivee', 'CahierTexteNoticePriveeQuery');
	}

	/**
	 * Filter the query by a related JEleveGroupe object
	 *
	 * @param     JEleveGroupe $jEleveGroupe  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByJEleveGroupe($jEleveGroupe, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(GroupePeer::ID, $jEleveGroupe->getIdGroupe(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JEleveGroupe relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function joinJEleveGroupe($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JEleveGroupe');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JEleveGroupe');
		}
		
		return $this;
	}

	/**
	 * Use the JEleveGroupe relation JEleveGroupe object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JEleveGroupeQuery A secondary query class using the current class as primary query
	 */
	public function useJEleveGroupeQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJEleveGroupe($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JEleveGroupe', 'JEleveGroupeQuery');
	}

	/**
	 * Filter the query by a related CreditEcts object
	 *
	 * @param     CreditEcts $creditEcts  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByCreditEcts($creditEcts, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(GroupePeer::ID, $creditEcts->getIdGroupe(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the CreditEcts relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function joinCreditEcts($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('CreditEcts');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'CreditEcts');
		}
		
		return $this;
	}

	/**
	 * Use the CreditEcts relation CreditEcts object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    CreditEctsQuery A secondary query class using the current class as primary query
	 */
	public function useCreditEctsQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinCreditEcts($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'CreditEcts', 'CreditEctsQuery');
	}

	/**
	 * Filter the query by a related EdtEmplacementCours object
	 *
	 * @param     EdtEmplacementCours $edtEmplacementCours  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByEdtEmplacementCours($edtEmplacementCours, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(GroupePeer::ID, $edtEmplacementCours->getIdGroupe(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the EdtEmplacementCours relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    GroupeQuery The current query, for fluid interface
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
	 * Filter the query by a related UtilisateurProfessionnel object
	 * using the j_groupes_professeurs table as cross reference
	 *
	 * @param     UtilisateurProfessionnel $utilisateurProfessionnel the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function filterByUtilisateurProfessionnel($utilisateurProfessionnel, $comparison = Criteria::EQUAL)
	{
		return $this
			->useJGroupesProfesseursQuery()
				->filterByUtilisateurProfessionnel($utilisateurProfessionnel, $comparison)
			->endUse();
	}
	
	/**
	 * Exclude object from result
	 *
	 * @param     Groupe $groupe Object to remove from the list of results
	 *
	 * @return    GroupeQuery The current query, for fluid interface
	 */
	public function prune($groupe = null)
	{
		if ($groupe) {
			$this->addUsingAlias(GroupePeer::ID, $groupe->getId(), Criteria::NOT_EQUAL);
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

} // BaseGroupeQuery
