<?php


/**
 * Base class that represents a query for the 'responsables2' table.
 *
 * Table de jointure entre les eleves et leurs responsables legaux avec mention du niveau de ces responsables
 *
 * @method     ResponsableInformationQuery orderByEleId($order = Criteria::ASC) Order by the ele_id column
 * @method     ResponsableInformationQuery orderByPersId($order = Criteria::ASC) Order by the pers_id column
 * @method     ResponsableInformationQuery orderByRespLegal($order = Criteria::ASC) Order by the resp_legal column
 * @method     ResponsableInformationQuery orderByPersContact($order = Criteria::ASC) Order by the pers_contact column
 *
 * @method     ResponsableInformationQuery groupByEleId() Group by the ele_id column
 * @method     ResponsableInformationQuery groupByPersId() Group by the pers_id column
 * @method     ResponsableInformationQuery groupByRespLegal() Group by the resp_legal column
 * @method     ResponsableInformationQuery groupByPersContact() Group by the pers_contact column
 *
 * @method     ResponsableInformationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ResponsableInformationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ResponsableInformationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ResponsableInformationQuery leftJoinEleve($relationAlias = '') Adds a LEFT JOIN clause to the query using the Eleve relation
 * @method     ResponsableInformationQuery rightJoinEleve($relationAlias = '') Adds a RIGHT JOIN clause to the query using the Eleve relation
 * @method     ResponsableInformationQuery innerJoinEleve($relationAlias = '') Adds a INNER JOIN clause to the query using the Eleve relation
 *
 * @method     ResponsableInformationQuery leftJoinResponsableEleve($relationAlias = '') Adds a LEFT JOIN clause to the query using the ResponsableEleve relation
 * @method     ResponsableInformationQuery rightJoinResponsableEleve($relationAlias = '') Adds a RIGHT JOIN clause to the query using the ResponsableEleve relation
 * @method     ResponsableInformationQuery innerJoinResponsableEleve($relationAlias = '') Adds a INNER JOIN clause to the query using the ResponsableEleve relation
 *
 * @method     ResponsableInformation findOne(PropelPDO $con = null) Return the first ResponsableInformation matching the query
 * @method     ResponsableInformation findOneByEleId(string $ele_id) Return the first ResponsableInformation filtered by the ele_id column
 * @method     ResponsableInformation findOneByPersId(string $pers_id) Return the first ResponsableInformation filtered by the pers_id column
 * @method     ResponsableInformation findOneByRespLegal(string $resp_legal) Return the first ResponsableInformation filtered by the resp_legal column
 * @method     ResponsableInformation findOneByPersContact(string $pers_contact) Return the first ResponsableInformation filtered by the pers_contact column
 *
 * @method     array findByEleId(string $ele_id) Return ResponsableInformation objects filtered by the ele_id column
 * @method     array findByPersId(string $pers_id) Return ResponsableInformation objects filtered by the pers_id column
 * @method     array findByRespLegal(string $resp_legal) Return ResponsableInformation objects filtered by the resp_legal column
 * @method     array findByPersContact(string $pers_contact) Return ResponsableInformation objects filtered by the pers_contact column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseResponsableInformationQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseResponsableInformationQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'ResponsableInformation', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new ResponsableInformationQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    ResponsableInformationQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof ResponsableInformationQuery) {
			return $criteria;
		}
		$query = new ResponsableInformationQuery();
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
		if ((null !== ($obj = ResponsableInformationPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		$this->addUsingAlias(ResponsableInformationPeer::ELE_ID, $key[0], Criteria::EQUAL);
		$this->addUsingAlias(ResponsableInformationPeer::RESP_LEGAL, $key[1], Criteria::EQUAL);
		
		return $this;
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		foreach ($keys as $key) {
			$cton0 = $this->getNewCriterion(ResponsableInformationPeer::ELE_ID, $key[0], Criteria::EQUAL);
			$cton1 = $this->getNewCriterion(ResponsableInformationPeer::RESP_LEGAL, $key[1], Criteria::EQUAL);
			$cton0->addAnd($cton1);
			$this->addOr($cton0);
		}
		
		return $this;
	}

	/**
	 * Filter the query on the ele_id column
	 * 
	 * @param     string $eleId The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function filterByEleId($eleId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($eleId)) {
			return $this->addUsingAlias(ResponsableInformationPeer::ELE_ID, $eleId, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $eleId)) {
			return $this->addUsingAlias(ResponsableInformationPeer::ELE_ID, str_replace('*', '%', $eleId), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(ResponsableInformationPeer::ELE_ID, $eleId, $comparison);
		}
	}

	/**
	 * Filter the query on the pers_id column
	 * 
	 * @param     string $persId The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function filterByPersId($persId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($persId)) {
			return $this->addUsingAlias(ResponsableInformationPeer::PERS_ID, $persId, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $persId)) {
			return $this->addUsingAlias(ResponsableInformationPeer::PERS_ID, str_replace('*', '%', $persId), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(ResponsableInformationPeer::PERS_ID, $persId, $comparison);
		}
	}

	/**
	 * Filter the query on the resp_legal column
	 * 
	 * @param     string $respLegal The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function filterByRespLegal($respLegal = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($respLegal)) {
			return $this->addUsingAlias(ResponsableInformationPeer::RESP_LEGAL, $respLegal, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $respLegal)) {
			return $this->addUsingAlias(ResponsableInformationPeer::RESP_LEGAL, str_replace('*', '%', $respLegal), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(ResponsableInformationPeer::RESP_LEGAL, $respLegal, $comparison);
		}
	}

	/**
	 * Filter the query on the pers_contact column
	 * 
	 * @param     string $persContact The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function filterByPersContact($persContact = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($persContact)) {
			return $this->addUsingAlias(ResponsableInformationPeer::PERS_CONTACT, $persContact, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $persContact)) {
			return $this->addUsingAlias(ResponsableInformationPeer::PERS_CONTACT, str_replace('*', '%', $persContact), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(ResponsableInformationPeer::PERS_CONTACT, $persContact, $comparison);
		}
	}

	/**
	 * Filter the query by a related Eleve object
	 *
	 * @param     Eleve $eleve  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function filterByEleve($eleve, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(ResponsableInformationPeer::ELE_ID, $eleve->getEleId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the Eleve relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function joinEleve($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Eleve');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'Eleve');
		}
		
		return $this;
	}

	/**
	 * Use the Eleve relation Eleve object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    EleveQuery A secondary query class using the current class as primary query
	 */
	public function useEleveQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinEleve($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Eleve', 'EleveQuery');
	}

	/**
	 * Filter the query by a related ResponsableEleve object
	 *
	 * @param     ResponsableEleve $responsableEleve  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function filterByResponsableEleve($responsableEleve, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(ResponsableInformationPeer::PERS_ID, $responsableEleve->getPersId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the ResponsableEleve relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function joinResponsableEleve($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('ResponsableEleve');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'ResponsableEleve');
		}
		
		return $this;
	}

	/**
	 * Use the ResponsableEleve relation ResponsableEleve object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    ResponsableEleveQuery A secondary query class using the current class as primary query
	 */
	public function useResponsableEleveQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinResponsableEleve($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'ResponsableEleve', 'ResponsableEleveQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     ResponsableInformation $responsableInformation Object to remove from the list of results
	 *
	 * @return    ResponsableInformationQuery The current query, for fluid interface
	 */
	public function prune($responsableInformation = null)
	{
		if ($responsableInformation) {
			$this->addCond('pruneCond0', $this->getAliasedColName(ResponsableInformationPeer::ELE_ID), $responsableInformation->getEleId(), Criteria::NOT_EQUAL);
			$this->addCond('pruneCond1', $this->getAliasedColName(ResponsableInformationPeer::RESP_LEGAL), $responsableInformation->getRespLegal(), Criteria::NOT_EQUAL);
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

} // BaseResponsableInformationQuery