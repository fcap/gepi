<?php



/**
 * Base class that represents a query for the 'periodes' table.
 *
 * Table regroupant les periodes de notes pour les classes
 *
 * @method     PeriodeNoteQuery orderByNomPeriode($order = Criteria::ASC) Order by the nom_periode column
 * @method     PeriodeNoteQuery orderByNumPeriode($order = Criteria::ASC) Order by the num_periode column
 * @method     PeriodeNoteQuery orderByVerouiller($order = Criteria::ASC) Order by the verouiller column
 * @method     PeriodeNoteQuery orderByIdClasse($order = Criteria::ASC) Order by the id_classe column
 * @method     PeriodeNoteQuery orderByDateVerrouillage($order = Criteria::ASC) Order by the date_verrouillage column
 *
 * @method     PeriodeNoteQuery groupByNomPeriode() Group by the nom_periode column
 * @method     PeriodeNoteQuery groupByNumPeriode() Group by the num_periode column
 * @method     PeriodeNoteQuery groupByVerouiller() Group by the verouiller column
 * @method     PeriodeNoteQuery groupByIdClasse() Group by the id_classe column
 * @method     PeriodeNoteQuery groupByDateVerrouillage() Group by the date_verrouillage column
 *
 * @method     PeriodeNoteQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     PeriodeNoteQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     PeriodeNoteQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     PeriodeNoteQuery leftJoinClasse($relationAlias = '') Adds a LEFT JOIN clause to the query using the Classe relation
 * @method     PeriodeNoteQuery rightJoinClasse($relationAlias = '') Adds a RIGHT JOIN clause to the query using the Classe relation
 * @method     PeriodeNoteQuery innerJoinClasse($relationAlias = '') Adds a INNER JOIN clause to the query using the Classe relation
 *
 * @method     PeriodeNote findOne(PropelPDO $con = null) Return the first PeriodeNote matching the query
 * @method     PeriodeNote findOneByNomPeriode(string $nom_periode) Return the first PeriodeNote filtered by the nom_periode column
 * @method     PeriodeNote findOneByNumPeriode(int $num_periode) Return the first PeriodeNote filtered by the num_periode column
 * @method     PeriodeNote findOneByVerouiller(string $verouiller) Return the first PeriodeNote filtered by the verouiller column
 * @method     PeriodeNote findOneByIdClasse(int $id_classe) Return the first PeriodeNote filtered by the id_classe column
 * @method     PeriodeNote findOneByDateVerrouillage(string $date_verrouillage) Return the first PeriodeNote filtered by the date_verrouillage column
 *
 * @method     array findByNomPeriode(string $nom_periode) Return PeriodeNote objects filtered by the nom_periode column
 * @method     array findByNumPeriode(int $num_periode) Return PeriodeNote objects filtered by the num_periode column
 * @method     array findByVerouiller(string $verouiller) Return PeriodeNote objects filtered by the verouiller column
 * @method     array findByIdClasse(int $id_classe) Return PeriodeNote objects filtered by the id_classe column
 * @method     array findByDateVerrouillage(string $date_verrouillage) Return PeriodeNote objects filtered by the date_verrouillage column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BasePeriodeNoteQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BasePeriodeNoteQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'PeriodeNote', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new PeriodeNoteQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    PeriodeNoteQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof PeriodeNoteQuery) {
			return $criteria;
		}
		$query = new PeriodeNoteQuery();
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
	 * $obj = $c->findPk(array(12, 34), $con);
	 * </code>
	 * @param     array[$num_periode, $id_classe] $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PeriodeNote|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = PeriodeNotePeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			$criteria = $this->isKeepQuery() ? clone $this : $this;
			$stmt = $criteria
				->filterByPrimaryKey($key)
				->getSelectStatement($con);
			return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
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
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{	
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		return $this
			->filterByPrimaryKeys($keys)
			->find($con);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		$this->addUsingAlias(PeriodeNotePeer::NUM_PERIODE, $key[0], Criteria::EQUAL);
		$this->addUsingAlias(PeriodeNotePeer::ID_CLASSE, $key[1], Criteria::EQUAL);
		
		return $this;
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		foreach ($keys as $key) {
			$cton0 = $this->getNewCriterion(PeriodeNotePeer::NUM_PERIODE, $key[0], Criteria::EQUAL);
			$cton1 = $this->getNewCriterion(PeriodeNotePeer::ID_CLASSE, $key[1], Criteria::EQUAL);
			$cton0->addAnd($cton1);
			$this->addOr($cton0);
		}
		
		return $this;
	}

	/**
	 * Filter the query on the nom_periode column
	 * 
	 * @param     string $nomPeriode The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function filterByNomPeriode($nomPeriode = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($nomPeriode)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $nomPeriode)) {
				$nomPeriode = str_replace('*', '%', $nomPeriode);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(PeriodeNotePeer::NOM_PERIODE, $nomPeriode, $comparison);
	}

	/**
	 * Filter the query on the num_periode column
	 * 
	 * @param     int|array $numPeriode The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function filterByNumPeriode($numPeriode = null, $comparison = null)
	{
		if (is_array($numPeriode) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(PeriodeNotePeer::NUM_PERIODE, $numPeriode, $comparison);
	}

	/**
	 * Filter the query on the verouiller column
	 * 
	 * @param     string $verouiller The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function filterByVerouiller($verouiller = null, $comparison = null)
	{
		if (null === $comparison) {
			if (is_array($verouiller)) {
				$comparison = Criteria::IN;
			} elseif (preg_match('/[\%\*]/', $verouiller)) {
				$verouiller = str_replace('*', '%', $verouiller);
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(PeriodeNotePeer::VEROUILLER, $verouiller, $comparison);
	}

	/**
	 * Filter the query on the id_classe column
	 * 
	 * @param     int|array $idClasse The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function filterByIdClasse($idClasse = null, $comparison = null)
	{
		if (is_array($idClasse) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(PeriodeNotePeer::ID_CLASSE, $idClasse, $comparison);
	}

	/**
	 * Filter the query on the date_verrouillage column
	 * 
	 * @param     string|array $dateVerrouillage The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function filterByDateVerrouillage($dateVerrouillage = null, $comparison = null)
	{
		if (is_array($dateVerrouillage)) {
			$useMinMax = false;
			if (isset($dateVerrouillage['min'])) {
				$this->addUsingAlias(PeriodeNotePeer::DATE_VERROUILLAGE, $dateVerrouillage['min'], Criteria::GREATER_EQUAL);
				$useMinMax = true;
			}
			if (isset($dateVerrouillage['max'])) {
				$this->addUsingAlias(PeriodeNotePeer::DATE_VERROUILLAGE, $dateVerrouillage['max'], Criteria::LESS_EQUAL);
				$useMinMax = true;
			}
			if ($useMinMax) {
				return $this;
			}
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		}
		return $this->addUsingAlias(PeriodeNotePeer::DATE_VERROUILLAGE, $dateVerrouillage, $comparison);
	}

	/**
	 * Filter the query by a related Classe object
	 *
	 * @param     Classe $classe  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function filterByClasse($classe, $comparison = null)
	{
		return $this
			->addUsingAlias(PeriodeNotePeer::ID_CLASSE, $classe->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the Classe relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function joinClasse($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('Classe');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'Classe');
		}
		
		return $this;
	}

	/**
	 * Use the Classe relation Classe object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    ClasseQuery A secondary query class using the current class as primary query
	 */
	public function useClasseQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinClasse($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'Classe', 'ClasseQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     PeriodeNote $periodeNote Object to remove from the list of results
	 *
	 * @return    PeriodeNoteQuery The current query, for fluid interface
	 */
	public function prune($periodeNote = null)
	{
		if ($periodeNote) {
			$this->addCond('pruneCond0', $this->getAliasedColName(PeriodeNotePeer::NUM_PERIODE), $periodeNote->getNumPeriode(), Criteria::NOT_EQUAL);
			$this->addCond('pruneCond1', $this->getAliasedColName(PeriodeNotePeer::ID_CLASSE), $periodeNote->getIdClasse(), Criteria::NOT_EQUAL);
			$this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
	  }
	  
		return $this;
	}

} // BasePeriodeNoteQuery
