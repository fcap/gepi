<?php


/**
 * Base class that represents a query for the 'matieres' table.
 *
 * Matières
 *
 * @method     MatiereQuery orderByMatiere($order = Criteria::ASC) Order by the matiere column
 * @method     MatiereQuery orderByNomComplet($order = Criteria::ASC) Order by the nom_complet column
 * @method     MatiereQuery orderByPriority($order = Criteria::ASC) Order by the priority column
 * @method     MatiereQuery orderByMatiereAid($order = Criteria::ASC) Order by the matiere_aid column
 * @method     MatiereQuery orderByMatiereAtelier($order = Criteria::ASC) Order by the matiere_atelier column
 * @method     MatiereQuery orderByCategorieId($order = Criteria::ASC) Order by the categorie_id column
 *
 * @method     MatiereQuery groupByMatiere() Group by the matiere column
 * @method     MatiereQuery groupByNomComplet() Group by the nom_complet column
 * @method     MatiereQuery groupByPriority() Group by the priority column
 * @method     MatiereQuery groupByMatiereAid() Group by the matiere_aid column
 * @method     MatiereQuery groupByMatiereAtelier() Group by the matiere_atelier column
 * @method     MatiereQuery groupByCategorieId() Group by the categorie_id column
 *
 * @method     MatiereQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     MatiereQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     MatiereQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     MatiereQuery leftJoinCategorieMatiere($relationAlias = '') Adds a LEFT JOIN clause to the query using the CategorieMatiere relation
 * @method     MatiereQuery rightJoinCategorieMatiere($relationAlias = '') Adds a RIGHT JOIN clause to the query using the CategorieMatiere relation
 * @method     MatiereQuery innerJoinCategorieMatiere($relationAlias = '') Adds a INNER JOIN clause to the query using the CategorieMatiere relation
 *
 * @method     MatiereQuery leftJoinJGroupesMatieres($relationAlias = '') Adds a LEFT JOIN clause to the query using the JGroupesMatieres relation
 * @method     MatiereQuery rightJoinJGroupesMatieres($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JGroupesMatieres relation
 * @method     MatiereQuery innerJoinJGroupesMatieres($relationAlias = '') Adds a INNER JOIN clause to the query using the JGroupesMatieres relation
 *
 * @method     MatiereQuery leftJoinJProfesseursMatieres($relationAlias = '') Adds a LEFT JOIN clause to the query using the JProfesseursMatieres relation
 * @method     MatiereQuery rightJoinJProfesseursMatieres($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JProfesseursMatieres relation
 * @method     MatiereQuery innerJoinJProfesseursMatieres($relationAlias = '') Adds a INNER JOIN clause to the query using the JProfesseursMatieres relation
 *
 * @method     Matiere findOne(PropelPDO $con = null) Return the first Matiere matching the query
 * @method     Matiere findOneByMatiere(string $matiere) Return the first Matiere filtered by the matiere column
 * @method     Matiere findOneByNomComplet(string $nom_complet) Return the first Matiere filtered by the nom_complet column
 * @method     Matiere findOneByPriority(int $priority) Return the first Matiere filtered by the priority column
 * @method     Matiere findOneByMatiereAid(string $matiere_aid) Return the first Matiere filtered by the matiere_aid column
 * @method     Matiere findOneByMatiereAtelier(string $matiere_atelier) Return the first Matiere filtered by the matiere_atelier column
 * @method     Matiere findOneByCategorieId(int $categorie_id) Return the first Matiere filtered by the categorie_id column
 *
 * @method     array findByMatiere(string $matiere) Return Matiere objects filtered by the matiere column
 * @method     array findByNomComplet(string $nom_complet) Return Matiere objects filtered by the nom_complet column
 * @method     array findByPriority(int $priority) Return Matiere objects filtered by the priority column
 * @method     array findByMatiereAid(string $matiere_aid) Return Matiere objects filtered by the matiere_aid column
 * @method     array findByMatiereAtelier(string $matiere_atelier) Return Matiere objects filtered by the matiere_atelier column
 * @method     array findByCategorieId(int $categorie_id) Return Matiere objects filtered by the categorie_id column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseMatiereQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseMatiereQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'Matiere', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new MatiereQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    MatiereQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof MatiereQuery) {
			return $criteria;
		}
		$query = new MatiereQuery();
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
	 * @return    Matiere|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = MatierePeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(MatierePeer::MATIERE, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(MatierePeer::MATIERE, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the matiere column
	 * 
	 * @param     string $matiere The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByMatiere($matiere = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($matiere)) {
			return $this->addUsingAlias(MatierePeer::MATIERE, $matiere, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $matiere)) {
			return $this->addUsingAlias(MatierePeer::MATIERE, str_replace('*', '%', $matiere), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(MatierePeer::MATIERE, $matiere, $comparison);
		}
	}

	/**
	 * Filter the query on the nom_complet column
	 * 
	 * @param     string $nomComplet The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByNomComplet($nomComplet = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($nomComplet)) {
			return $this->addUsingAlias(MatierePeer::NOM_COMPLET, $nomComplet, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $nomComplet)) {
			return $this->addUsingAlias(MatierePeer::NOM_COMPLET, str_replace('*', '%', $nomComplet), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(MatierePeer::NOM_COMPLET, $nomComplet, $comparison);
		}
	}

	/**
	 * Filter the query on the priority column
	 * 
	 * @param     int|array $priority The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByPriority($priority = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($priority)) {
			if (array_values($priority) === $priority) {
				return $this->addUsingAlias(MatierePeer::PRIORITY, $priority, Criteria::IN);
			} else {
				if (isset($priority['min'])) {
					$this->addUsingAlias(MatierePeer::PRIORITY, $priority['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($priority['max'])) {
					$this->addUsingAlias(MatierePeer::PRIORITY, $priority['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(MatierePeer::PRIORITY, $priority, $comparison);
		}
	}

	/**
	 * Filter the query on the matiere_aid column
	 * 
	 * @param     string $matiereAid The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByMatiereAid($matiereAid = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($matiereAid)) {
			return $this->addUsingAlias(MatierePeer::MATIERE_AID, $matiereAid, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $matiereAid)) {
			return $this->addUsingAlias(MatierePeer::MATIERE_AID, str_replace('*', '%', $matiereAid), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(MatierePeer::MATIERE_AID, $matiereAid, $comparison);
		}
	}

	/**
	 * Filter the query on the matiere_atelier column
	 * 
	 * @param     string $matiereAtelier The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByMatiereAtelier($matiereAtelier = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($matiereAtelier)) {
			return $this->addUsingAlias(MatierePeer::MATIERE_ATELIER, $matiereAtelier, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $matiereAtelier)) {
			return $this->addUsingAlias(MatierePeer::MATIERE_ATELIER, str_replace('*', '%', $matiereAtelier), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(MatierePeer::MATIERE_ATELIER, $matiereAtelier, $comparison);
		}
	}

	/**
	 * Filter the query on the categorie_id column
	 * 
	 * @param     int|array $categorieId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByCategorieId($categorieId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($categorieId)) {
			if (array_values($categorieId) === $categorieId) {
				return $this->addUsingAlias(MatierePeer::CATEGORIE_ID, $categorieId, Criteria::IN);
			} else {
				if (isset($categorieId['min'])) {
					$this->addUsingAlias(MatierePeer::CATEGORIE_ID, $categorieId['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($categorieId['max'])) {
					$this->addUsingAlias(MatierePeer::CATEGORIE_ID, $categorieId['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(MatierePeer::CATEGORIE_ID, $categorieId, $comparison);
		}
	}

	/**
	 * Filter the query by a related CategorieMatiere object
	 *
	 * @param     CategorieMatiere $categorieMatiere  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByCategorieMatiere($categorieMatiere, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(MatierePeer::CATEGORIE_ID, $categorieMatiere->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the CategorieMatiere relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function joinCategorieMatiere($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('CategorieMatiere');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'CategorieMatiere');
		}
		
		return $this;
	}

	/**
	 * Use the CategorieMatiere relation CategorieMatiere object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    CategorieMatiereQuery A secondary query class using the current class as primary query
	 */
	public function useCategorieMatiereQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinCategorieMatiere($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'CategorieMatiere', 'CategorieMatiereQuery');
	}

	/**
	 * Filter the query by a related JGroupesMatieres object
	 *
	 * @param     JGroupesMatieres $jGroupesMatieres  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByJGroupesMatieres($jGroupesMatieres, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(MatierePeer::MATIERE, $jGroupesMatieres->getIdMatiere(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JGroupesMatieres relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function joinJGroupesMatieres($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JGroupesMatieres');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JGroupesMatieres');
		}
		
		return $this;
	}

	/**
	 * Use the JGroupesMatieres relation JGroupesMatieres object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JGroupesMatieresQuery A secondary query class using the current class as primary query
	 */
	public function useJGroupesMatieresQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJGroupesMatieres($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JGroupesMatieres', 'JGroupesMatieresQuery');
	}

	/**
	 * Filter the query by a related JProfesseursMatieres object
	 *
	 * @param     JProfesseursMatieres $jProfesseursMatieres  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByJProfesseursMatieres($jProfesseursMatieres, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(MatierePeer::MATIERE, $jProfesseursMatieres->getIdMatiere(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JProfesseursMatieres relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function joinJProfesseursMatieres($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JProfesseursMatieres');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JProfesseursMatieres');
		}
		
		return $this;
	}

	/**
	 * Use the JProfesseursMatieres relation JProfesseursMatieres object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JProfesseursMatieresQuery A secondary query class using the current class as primary query
	 */
	public function useJProfesseursMatieresQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJProfesseursMatieres($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JProfesseursMatieres', 'JProfesseursMatieresQuery');
	}

	/**
	 * Filter the query by a related Groupe object
	 * using the j_groupes_matieres table as cross reference
	 *
	 * @param     Groupe $groupe the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByGroupe($groupe, $comparison = Criteria::EQUAL)
	{
		return $this
			->useJGroupesMatieresQuery()
				->filterByGroupe($groupe, $comparison)
			->endUse();
	}
	
	/**
	 * Filter the query by a related UtilisateurProfessionnel object
	 * using the j_professeurs_matieres table as cross reference
	 *
	 * @param     UtilisateurProfessionnel $utilisateurProfessionnel the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function filterByProfesseur($utilisateurProfessionnel, $comparison = Criteria::EQUAL)
	{
		return $this
			->useJProfesseursMatieresQuery()
				->filterByProfesseur($utilisateurProfessionnel, $comparison)
			->endUse();
	}
	
	/**
	 * Exclude object from result
	 *
	 * @param     Matiere $matiere Object to remove from the list of results
	 *
	 * @return    MatiereQuery The current query, for fluid interface
	 */
	public function prune($matiere = null)
	{
		if ($matiere) {
			$this->addUsingAlias(MatierePeer::MATIERE, $matiere->getMatiere(), Criteria::NOT_EQUAL);
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

} // BaseMatiereQuery
