<?php


/**
 * Base class that represents a query for the 'j_eleves_etablissements' table.
 *
 * Table de jointure pour connaitre l'etablissement precedent de l'eleve
 *
 * @method     JEleveAncienEtablissementQuery orderByIdEleve($order = Criteria::ASC) Order by the id_eleve column
 * @method     JEleveAncienEtablissementQuery orderByIdEtablissement($order = Criteria::ASC) Order by the id_etablissement column
 *
 * @method     JEleveAncienEtablissementQuery groupByIdEleve() Group by the id_eleve column
 * @method     JEleveAncienEtablissementQuery groupByIdEtablissement() Group by the id_etablissement column
 *
 * @method     JEleveAncienEtablissementQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     JEleveAncienEtablissementQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     JEleveAncienEtablissementQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     JEleveAncienEtablissementQuery leftJoinEleve($relationAlias = '') Adds a LEFT JOIN clause to the query using the Eleve relation
 * @method     JEleveAncienEtablissementQuery rightJoinEleve($relationAlias = '') Adds a RIGHT JOIN clause to the query using the Eleve relation
 * @method     JEleveAncienEtablissementQuery innerJoinEleve($relationAlias = '') Adds a INNER JOIN clause to the query using the Eleve relation
 *
 * @method     JEleveAncienEtablissementQuery leftJoinAncienEtablissement($relationAlias = '') Adds a LEFT JOIN clause to the query using the AncienEtablissement relation
 * @method     JEleveAncienEtablissementQuery rightJoinAncienEtablissement($relationAlias = '') Adds a RIGHT JOIN clause to the query using the AncienEtablissement relation
 * @method     JEleveAncienEtablissementQuery innerJoinAncienEtablissement($relationAlias = '') Adds a INNER JOIN clause to the query using the AncienEtablissement relation
 *
 * @method     JEleveAncienEtablissement findOne(PropelPDO $con = null) Return the first JEleveAncienEtablissement matching the query
 * @method     JEleveAncienEtablissement findOneByIdEleve(string $id_eleve) Return the first JEleveAncienEtablissement filtered by the id_eleve column
 * @method     JEleveAncienEtablissement findOneByIdEtablissement(string $id_etablissement) Return the first JEleveAncienEtablissement filtered by the id_etablissement column
 *
 * @method     array findByIdEleve(string $id_eleve) Return JEleveAncienEtablissement objects filtered by the id_eleve column
 * @method     array findByIdEtablissement(string $id_etablissement) Return JEleveAncienEtablissement objects filtered by the id_etablissement column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseJEleveAncienEtablissementQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseJEleveAncienEtablissementQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'JEleveAncienEtablissement', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new JEleveAncienEtablissementQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    JEleveAncienEtablissementQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof JEleveAncienEtablissementQuery) {
			return $criteria;
		}
		$query = new JEleveAncienEtablissementQuery();
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
		if ((null !== ($obj = JEleveAncienEtablissementPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    JEleveAncienEtablissementQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		$this->addUsingAlias(JEleveAncienEtablissementPeer::ID_ELEVE, $key[0], Criteria::EQUAL);
		$this->addUsingAlias(JEleveAncienEtablissementPeer::ID_ETABLISSEMENT, $key[1], Criteria::EQUAL);
		
		return $this;
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    JEleveAncienEtablissementQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		foreach ($keys as $key) {
			$cton0 = $this->getNewCriterion(JEleveAncienEtablissementPeer::ID_ELEVE, $key[0], Criteria::EQUAL);
			$cton1 = $this->getNewCriterion(JEleveAncienEtablissementPeer::ID_ETABLISSEMENT, $key[1], Criteria::EQUAL);
			$cton0->addAnd($cton1);
			$this->addOr($cton0);
		}
		
		return $this;
	}

	/**
	 * Filter the query on the id_eleve column
	 * 
	 * @param     string $idEleve The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JEleveAncienEtablissementQuery The current query, for fluid interface
	 */
	public function filterByIdEleve($idEleve = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($idEleve)) {
			return $this->addUsingAlias(JEleveAncienEtablissementPeer::ID_ELEVE, $idEleve, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $idEleve)) {
			return $this->addUsingAlias(JEleveAncienEtablissementPeer::ID_ELEVE, str_replace('*', '%', $idEleve), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(JEleveAncienEtablissementPeer::ID_ELEVE, $idEleve, $comparison);
		}
	}

	/**
	 * Filter the query on the id_etablissement column
	 * 
	 * @param     string $idEtablissement The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JEleveAncienEtablissementQuery The current query, for fluid interface
	 */
	public function filterByIdEtablissement($idEtablissement = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($idEtablissement)) {
			return $this->addUsingAlias(JEleveAncienEtablissementPeer::ID_ETABLISSEMENT, $idEtablissement, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $idEtablissement)) {
			return $this->addUsingAlias(JEleveAncienEtablissementPeer::ID_ETABLISSEMENT, str_replace('*', '%', $idEtablissement), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(JEleveAncienEtablissementPeer::ID_ETABLISSEMENT, $idEtablissement, $comparison);
		}
	}

	/**
	 * Filter the query by a related Eleve object
	 *
	 * @param     Eleve $eleve  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JEleveAncienEtablissementQuery The current query, for fluid interface
	 */
	public function filterByEleve($eleve, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(JEleveAncienEtablissementPeer::ID_ELEVE, $eleve->getIdEleve(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the Eleve relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JEleveAncienEtablissementQuery The current query, for fluid interface
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
	 * Filter the query by a related AncienEtablissement object
	 *
	 * @param     AncienEtablissement $ancienEtablissement  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    JEleveAncienEtablissementQuery The current query, for fluid interface
	 */
	public function filterByAncienEtablissement($ancienEtablissement, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(JEleveAncienEtablissementPeer::ID_ETABLISSEMENT, $ancienEtablissement->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the AncienEtablissement relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JEleveAncienEtablissementQuery The current query, for fluid interface
	 */
	public function joinAncienEtablissement($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AncienEtablissement');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'AncienEtablissement');
		}
		
		return $this;
	}

	/**
	 * Use the AncienEtablissement relation AncienEtablissement object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AncienEtablissementQuery A secondary query class using the current class as primary query
	 */
	public function useAncienEtablissementQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinAncienEtablissement($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AncienEtablissement', 'AncienEtablissementQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     JEleveAncienEtablissement $jEleveAncienEtablissement Object to remove from the list of results
	 *
	 * @return    JEleveAncienEtablissementQuery The current query, for fluid interface
	 */
	public function prune($jEleveAncienEtablissement = null)
	{
		if ($jEleveAncienEtablissement) {
			$this->addCond('pruneCond0', $this->getAliasedColName(JEleveAncienEtablissementPeer::ID_ELEVE), $jEleveAncienEtablissement->getIdEleve(), Criteria::NOT_EQUAL);
			$this->addCond('pruneCond1', $this->getAliasedColName(JEleveAncienEtablissementPeer::ID_ETABLISSEMENT), $jEleveAncienEtablissement->getIdEtablissement(), Criteria::NOT_EQUAL);
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

} // BaseJEleveAncienEtablissementQuery