<?php


/**
 * Base class that represents a query for the 'a_traitements' table.
 *
 * Un traitement peut gerer plusieurs saisies et consiste à definir les motifs/justifications... de ces absences saisies
 *
 * @method     AbsenceEleveTraitementQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     AbsenceEleveTraitementQuery orderByUtilisateurId($order = Criteria::ASC) Order by the utilisateur_id column
 * @method     AbsenceEleveTraitementQuery orderByATypeId($order = Criteria::ASC) Order by the a_type_id column
 * @method     AbsenceEleveTraitementQuery orderByAMotifId($order = Criteria::ASC) Order by the a_motif_id column
 * @method     AbsenceEleveTraitementQuery orderByAJustificationId($order = Criteria::ASC) Order by the a_justification_id column
 * @method     AbsenceEleveTraitementQuery orderByTexteJustification($order = Criteria::ASC) Order by the texte_justification column
 * @method     AbsenceEleveTraitementQuery orderByAActionId($order = Criteria::ASC) Order by the a_action_id column
 * @method     AbsenceEleveTraitementQuery orderByCommentaire($order = Criteria::ASC) Order by the commentaire column
 * @method     AbsenceEleveTraitementQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     AbsenceEleveTraitementQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     AbsenceEleveTraitementQuery groupById() Group by the id column
 * @method     AbsenceEleveTraitementQuery groupByUtilisateurId() Group by the utilisateur_id column
 * @method     AbsenceEleveTraitementQuery groupByATypeId() Group by the a_type_id column
 * @method     AbsenceEleveTraitementQuery groupByAMotifId() Group by the a_motif_id column
 * @method     AbsenceEleveTraitementQuery groupByAJustificationId() Group by the a_justification_id column
 * @method     AbsenceEleveTraitementQuery groupByTexteJustification() Group by the texte_justification column
 * @method     AbsenceEleveTraitementQuery groupByAActionId() Group by the a_action_id column
 * @method     AbsenceEleveTraitementQuery groupByCommentaire() Group by the commentaire column
 * @method     AbsenceEleveTraitementQuery groupByCreatedAt() Group by the created_at column
 * @method     AbsenceEleveTraitementQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     AbsenceEleveTraitementQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     AbsenceEleveTraitementQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     AbsenceEleveTraitementQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     AbsenceEleveTraitementQuery leftJoinUtilisateurProfessionnel($relationAlias = '') Adds a LEFT JOIN clause to the query using the UtilisateurProfessionnel relation
 * @method     AbsenceEleveTraitementQuery rightJoinUtilisateurProfessionnel($relationAlias = '') Adds a RIGHT JOIN clause to the query using the UtilisateurProfessionnel relation
 * @method     AbsenceEleveTraitementQuery innerJoinUtilisateurProfessionnel($relationAlias = '') Adds a INNER JOIN clause to the query using the UtilisateurProfessionnel relation
 *
 * @method     AbsenceEleveTraitementQuery leftJoinAbsenceEleveType($relationAlias = '') Adds a LEFT JOIN clause to the query using the AbsenceEleveType relation
 * @method     AbsenceEleveTraitementQuery rightJoinAbsenceEleveType($relationAlias = '') Adds a RIGHT JOIN clause to the query using the AbsenceEleveType relation
 * @method     AbsenceEleveTraitementQuery innerJoinAbsenceEleveType($relationAlias = '') Adds a INNER JOIN clause to the query using the AbsenceEleveType relation
 *
 * @method     AbsenceEleveTraitementQuery leftJoinAbsenceEleveMotif($relationAlias = '') Adds a LEFT JOIN clause to the query using the AbsenceEleveMotif relation
 * @method     AbsenceEleveTraitementQuery rightJoinAbsenceEleveMotif($relationAlias = '') Adds a RIGHT JOIN clause to the query using the AbsenceEleveMotif relation
 * @method     AbsenceEleveTraitementQuery innerJoinAbsenceEleveMotif($relationAlias = '') Adds a INNER JOIN clause to the query using the AbsenceEleveMotif relation
 *
 * @method     AbsenceEleveTraitementQuery leftJoinAbsenceEleveJustification($relationAlias = '') Adds a LEFT JOIN clause to the query using the AbsenceEleveJustification relation
 * @method     AbsenceEleveTraitementQuery rightJoinAbsenceEleveJustification($relationAlias = '') Adds a RIGHT JOIN clause to the query using the AbsenceEleveJustification relation
 * @method     AbsenceEleveTraitementQuery innerJoinAbsenceEleveJustification($relationAlias = '') Adds a INNER JOIN clause to the query using the AbsenceEleveJustification relation
 *
 * @method     AbsenceEleveTraitementQuery leftJoinAbsenceEleveAction($relationAlias = '') Adds a LEFT JOIN clause to the query using the AbsenceEleveAction relation
 * @method     AbsenceEleveTraitementQuery rightJoinAbsenceEleveAction($relationAlias = '') Adds a RIGHT JOIN clause to the query using the AbsenceEleveAction relation
 * @method     AbsenceEleveTraitementQuery innerJoinAbsenceEleveAction($relationAlias = '') Adds a INNER JOIN clause to the query using the AbsenceEleveAction relation
 *
 * @method     AbsenceEleveTraitementQuery leftJoinJTraitementSaisieEleve($relationAlias = '') Adds a LEFT JOIN clause to the query using the JTraitementSaisieEleve relation
 * @method     AbsenceEleveTraitementQuery rightJoinJTraitementSaisieEleve($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JTraitementSaisieEleve relation
 * @method     AbsenceEleveTraitementQuery innerJoinJTraitementSaisieEleve($relationAlias = '') Adds a INNER JOIN clause to the query using the JTraitementSaisieEleve relation
 *
 * @method     AbsenceEleveTraitementQuery leftJoinJTraitementEnvoiEleve($relationAlias = '') Adds a LEFT JOIN clause to the query using the JTraitementEnvoiEleve relation
 * @method     AbsenceEleveTraitementQuery rightJoinJTraitementEnvoiEleve($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JTraitementEnvoiEleve relation
 * @method     AbsenceEleveTraitementQuery innerJoinJTraitementEnvoiEleve($relationAlias = '') Adds a INNER JOIN clause to the query using the JTraitementEnvoiEleve relation
 *
 * @method     AbsenceEleveTraitement findOne(PropelPDO $con = null) Return the first AbsenceEleveTraitement matching the query
 * @method     AbsenceEleveTraitement findOneById(int $id) Return the first AbsenceEleveTraitement filtered by the id column
 * @method     AbsenceEleveTraitement findOneByUtilisateurId(string $utilisateur_id) Return the first AbsenceEleveTraitement filtered by the utilisateur_id column
 * @method     AbsenceEleveTraitement findOneByATypeId(int $a_type_id) Return the first AbsenceEleveTraitement filtered by the a_type_id column
 * @method     AbsenceEleveTraitement findOneByAMotifId(int $a_motif_id) Return the first AbsenceEleveTraitement filtered by the a_motif_id column
 * @method     AbsenceEleveTraitement findOneByAJustificationId(int $a_justification_id) Return the first AbsenceEleveTraitement filtered by the a_justification_id column
 * @method     AbsenceEleveTraitement findOneByTexteJustification(string $texte_justification) Return the first AbsenceEleveTraitement filtered by the texte_justification column
 * @method     AbsenceEleveTraitement findOneByAActionId(int $a_action_id) Return the first AbsenceEleveTraitement filtered by the a_action_id column
 * @method     AbsenceEleveTraitement findOneByCommentaire(string $commentaire) Return the first AbsenceEleveTraitement filtered by the commentaire column
 * @method     AbsenceEleveTraitement findOneByCreatedAt(string $created_at) Return the first AbsenceEleveTraitement filtered by the created_at column
 * @method     AbsenceEleveTraitement findOneByUpdatedAt(string $updated_at) Return the first AbsenceEleveTraitement filtered by the updated_at column
 *
 * @method     array findById(int $id) Return AbsenceEleveTraitement objects filtered by the id column
 * @method     array findByUtilisateurId(string $utilisateur_id) Return AbsenceEleveTraitement objects filtered by the utilisateur_id column
 * @method     array findByATypeId(int $a_type_id) Return AbsenceEleveTraitement objects filtered by the a_type_id column
 * @method     array findByAMotifId(int $a_motif_id) Return AbsenceEleveTraitement objects filtered by the a_motif_id column
 * @method     array findByAJustificationId(int $a_justification_id) Return AbsenceEleveTraitement objects filtered by the a_justification_id column
 * @method     array findByTexteJustification(string $texte_justification) Return AbsenceEleveTraitement objects filtered by the texte_justification column
 * @method     array findByAActionId(int $a_action_id) Return AbsenceEleveTraitement objects filtered by the a_action_id column
 * @method     array findByCommentaire(string $commentaire) Return AbsenceEleveTraitement objects filtered by the commentaire column
 * @method     array findByCreatedAt(string $created_at) Return AbsenceEleveTraitement objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return AbsenceEleveTraitement objects filtered by the updated_at column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseAbsenceEleveTraitementQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseAbsenceEleveTraitementQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'AbsenceEleveTraitement', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new AbsenceEleveTraitementQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    AbsenceEleveTraitementQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof AbsenceEleveTraitementQuery) {
			return $criteria;
		}
		$query = new AbsenceEleveTraitementQuery();
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
		if ((null !== ($obj = AbsenceEleveTraitementPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(AbsenceEleveTraitementPeer::ID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(AbsenceEleveTraitementPeer::ID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($id)) {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::ID, $id, Criteria::IN);
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::ID, $id, $comparison);
		}
	}

	/**
	 * Filter the query on the utilisateur_id column
	 * 
	 * @param     string $utilisateurId The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByUtilisateurId($utilisateurId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($utilisateurId)) {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::UTILISATEUR_ID, $utilisateurId, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $utilisateurId)) {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::UTILISATEUR_ID, str_replace('*', '%', $utilisateurId), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::UTILISATEUR_ID, $utilisateurId, $comparison);
		}
	}

	/**
	 * Filter the query on the a_type_id column
	 * 
	 * @param     int|array $aTypeId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByATypeId($aTypeId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($aTypeId)) {
			if (array_values($aTypeId) === $aTypeId) {
				return $this->addUsingAlias(AbsenceEleveTraitementPeer::A_TYPE_ID, $aTypeId, Criteria::IN);
			} else {
				if (isset($aTypeId['min'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::A_TYPE_ID, $aTypeId['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($aTypeId['max'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::A_TYPE_ID, $aTypeId['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::A_TYPE_ID, $aTypeId, $comparison);
		}
	}

	/**
	 * Filter the query on the a_motif_id column
	 * 
	 * @param     int|array $aMotifId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByAMotifId($aMotifId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($aMotifId)) {
			if (array_values($aMotifId) === $aMotifId) {
				return $this->addUsingAlias(AbsenceEleveTraitementPeer::A_MOTIF_ID, $aMotifId, Criteria::IN);
			} else {
				if (isset($aMotifId['min'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::A_MOTIF_ID, $aMotifId['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($aMotifId['max'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::A_MOTIF_ID, $aMotifId['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::A_MOTIF_ID, $aMotifId, $comparison);
		}
	}

	/**
	 * Filter the query on the a_justification_id column
	 * 
	 * @param     int|array $aJustificationId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByAJustificationId($aJustificationId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($aJustificationId)) {
			if (array_values($aJustificationId) === $aJustificationId) {
				return $this->addUsingAlias(AbsenceEleveTraitementPeer::A_JUSTIFICATION_ID, $aJustificationId, Criteria::IN);
			} else {
				if (isset($aJustificationId['min'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::A_JUSTIFICATION_ID, $aJustificationId['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($aJustificationId['max'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::A_JUSTIFICATION_ID, $aJustificationId['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::A_JUSTIFICATION_ID, $aJustificationId, $comparison);
		}
	}

	/**
	 * Filter the query on the texte_justification column
	 * 
	 * @param     string $texteJustification The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByTexteJustification($texteJustification = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($texteJustification)) {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::TEXTE_JUSTIFICATION, $texteJustification, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $texteJustification)) {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::TEXTE_JUSTIFICATION, str_replace('*', '%', $texteJustification), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::TEXTE_JUSTIFICATION, $texteJustification, $comparison);
		}
	}

	/**
	 * Filter the query on the a_action_id column
	 * 
	 * @param     int|array $aActionId The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByAActionId($aActionId = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($aActionId)) {
			if (array_values($aActionId) === $aActionId) {
				return $this->addUsingAlias(AbsenceEleveTraitementPeer::A_ACTION_ID, $aActionId, Criteria::IN);
			} else {
				if (isset($aActionId['min'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::A_ACTION_ID, $aActionId['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($aActionId['max'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::A_ACTION_ID, $aActionId['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::A_ACTION_ID, $aActionId, $comparison);
		}
	}

	/**
	 * Filter the query on the commentaire column
	 * 
	 * @param     string $commentaire The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByCommentaire($commentaire = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($commentaire)) {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::COMMENTAIRE, $commentaire, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $commentaire)) {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::COMMENTAIRE, str_replace('*', '%', $commentaire), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::COMMENTAIRE, $commentaire, $comparison);
		}
	}

	/**
	 * Filter the query on the created_at column
	 * 
	 * @param     string|array $createdAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByCreatedAt($createdAt = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($createdAt)) {
			if (array_values($createdAt) === $createdAt) {
				return $this->addUsingAlias(AbsenceEleveTraitementPeer::CREATED_AT, $createdAt, Criteria::IN);
			} else {
				if (isset($createdAt['min'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($createdAt['max'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::CREATED_AT, $createdAt, $comparison);
		}
	}

	/**
	 * Filter the query on the updated_at column
	 * 
	 * @param     string|array $updatedAt The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByUpdatedAt($updatedAt = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($updatedAt)) {
			if (array_values($updatedAt) === $updatedAt) {
				return $this->addUsingAlias(AbsenceEleveTraitementPeer::UPDATED_AT, $updatedAt, Criteria::IN);
			} else {
				if (isset($updatedAt['min'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($updatedAt['max'])) {
					$this->addUsingAlias(AbsenceEleveTraitementPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AbsenceEleveTraitementPeer::UPDATED_AT, $updatedAt, $comparison);
		}
	}

	/**
	 * Filter the query by a related UtilisateurProfessionnel object
	 *
	 * @param     UtilisateurProfessionnel $utilisateurProfessionnel  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByUtilisateurProfessionnel($utilisateurProfessionnel, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AbsenceEleveTraitementPeer::UTILISATEUR_ID, $utilisateurProfessionnel->getLogin(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the UtilisateurProfessionnel relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function joinUtilisateurProfessionnel($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('UtilisateurProfessionnel');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'UtilisateurProfessionnel');
		}
		
		return $this;
	}

	/**
	 * Use the UtilisateurProfessionnel relation UtilisateurProfessionnel object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    UtilisateurProfessionnelQuery A secondary query class using the current class as primary query
	 */
	public function useUtilisateurProfessionnelQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinUtilisateurProfessionnel($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'UtilisateurProfessionnel', 'UtilisateurProfessionnelQuery');
	}

	/**
	 * Filter the query by a related AbsenceEleveType object
	 *
	 * @param     AbsenceEleveType $absenceEleveType  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByAbsenceEleveType($absenceEleveType, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AbsenceEleveTraitementPeer::A_TYPE_ID, $absenceEleveType->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the AbsenceEleveType relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function joinAbsenceEleveType($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AbsenceEleveType');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'AbsenceEleveType');
		}
		
		return $this;
	}

	/**
	 * Use the AbsenceEleveType relation AbsenceEleveType object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveTypeQuery A secondary query class using the current class as primary query
	 */
	public function useAbsenceEleveTypeQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinAbsenceEleveType($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AbsenceEleveType', 'AbsenceEleveTypeQuery');
	}

	/**
	 * Filter the query by a related AbsenceEleveMotif object
	 *
	 * @param     AbsenceEleveMotif $absenceEleveMotif  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByAbsenceEleveMotif($absenceEleveMotif, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AbsenceEleveTraitementPeer::A_MOTIF_ID, $absenceEleveMotif->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the AbsenceEleveMotif relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function joinAbsenceEleveMotif($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AbsenceEleveMotif');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'AbsenceEleveMotif');
		}
		
		return $this;
	}

	/**
	 * Use the AbsenceEleveMotif relation AbsenceEleveMotif object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveMotifQuery A secondary query class using the current class as primary query
	 */
	public function useAbsenceEleveMotifQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinAbsenceEleveMotif($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AbsenceEleveMotif', 'AbsenceEleveMotifQuery');
	}

	/**
	 * Filter the query by a related AbsenceEleveJustification object
	 *
	 * @param     AbsenceEleveJustification $absenceEleveJustification  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByAbsenceEleveJustification($absenceEleveJustification, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AbsenceEleveTraitementPeer::A_JUSTIFICATION_ID, $absenceEleveJustification->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the AbsenceEleveJustification relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function joinAbsenceEleveJustification($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AbsenceEleveJustification');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'AbsenceEleveJustification');
		}
		
		return $this;
	}

	/**
	 * Use the AbsenceEleveJustification relation AbsenceEleveJustification object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveJustificationQuery A secondary query class using the current class as primary query
	 */
	public function useAbsenceEleveJustificationQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinAbsenceEleveJustification($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AbsenceEleveJustification', 'AbsenceEleveJustificationQuery');
	}

	/**
	 * Filter the query by a related AbsenceEleveAction object
	 *
	 * @param     AbsenceEleveAction $absenceEleveAction  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByAbsenceEleveAction($absenceEleveAction, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AbsenceEleveTraitementPeer::A_ACTION_ID, $absenceEleveAction->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the AbsenceEleveAction relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function joinAbsenceEleveAction($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AbsenceEleveAction');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'AbsenceEleveAction');
		}
		
		return $this;
	}

	/**
	 * Use the AbsenceEleveAction relation AbsenceEleveAction object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveActionQuery A secondary query class using the current class as primary query
	 */
	public function useAbsenceEleveActionQuery($relationAlias = '', $joinType = Criteria::LEFT_JOIN)
	{
		return $this
			->joinAbsenceEleveAction($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AbsenceEleveAction', 'AbsenceEleveActionQuery');
	}

	/**
	 * Filter the query by a related JTraitementSaisieEleve object
	 *
	 * @param     JTraitementSaisieEleve $jTraitementSaisieEleve  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByJTraitementSaisieEleve($jTraitementSaisieEleve, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AbsenceEleveTraitementPeer::ID, $jTraitementSaisieEleve->getATraitementId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JTraitementSaisieEleve relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function joinJTraitementSaisieEleve($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JTraitementSaisieEleve');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JTraitementSaisieEleve');
		}
		
		return $this;
	}

	/**
	 * Use the JTraitementSaisieEleve relation JTraitementSaisieEleve object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JTraitementSaisieEleveQuery A secondary query class using the current class as primary query
	 */
	public function useJTraitementSaisieEleveQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJTraitementSaisieEleve($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JTraitementSaisieEleve', 'JTraitementSaisieEleveQuery');
	}

	/**
	 * Filter the query by a related JTraitementEnvoiEleve object
	 *
	 * @param     JTraitementEnvoiEleve $jTraitementEnvoiEleve  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByJTraitementEnvoiEleve($jTraitementEnvoiEleve, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AbsenceEleveTraitementPeer::ID, $jTraitementEnvoiEleve->getATraitementId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JTraitementEnvoiEleve relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function joinJTraitementEnvoiEleve($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JTraitementEnvoiEleve');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JTraitementEnvoiEleve');
		}
		
		return $this;
	}

	/**
	 * Use the JTraitementEnvoiEleve relation JTraitementEnvoiEleve object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JTraitementEnvoiEleveQuery A secondary query class using the current class as primary query
	 */
	public function useJTraitementEnvoiEleveQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJTraitementEnvoiEleve($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JTraitementEnvoiEleve', 'JTraitementEnvoiEleveQuery');
	}

	/**
	 * Filter the query by a related AbsenceEleveSaisie object
	 * using the j_traitements_saisies table as cross reference
	 *
	 * @param     AbsenceEleveSaisie $absenceEleveSaisie the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByAbsenceEleveSaisie($absenceEleveSaisie, $comparison = Criteria::EQUAL)
	{
		return $this
			->useJTraitementSaisieEleveQuery()
				->filterByAbsenceEleveSaisie($absenceEleveSaisie, $comparison)
			->endUse();
	}
	
	/**
	 * Filter the query by a related AbsenceEleveEnvoi object
	 * using the j_traitements_envois table as cross reference
	 *
	 * @param     AbsenceEleveEnvoi $absenceEleveEnvoi the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function filterByAbsenceEleveEnvoi($absenceEleveEnvoi, $comparison = Criteria::EQUAL)
	{
		return $this
			->useJTraitementEnvoiEleveQuery()
				->filterByAbsenceEleveEnvoi($absenceEleveEnvoi, $comparison)
			->endUse();
	}
	
	/**
	 * Exclude object from result
	 *
	 * @param     AbsenceEleveTraitement $absenceEleveTraitement Object to remove from the list of results
	 *
	 * @return    AbsenceEleveTraitementQuery The current query, for fluid interface
	 */
	public function prune($absenceEleveTraitement = null)
	{
		if ($absenceEleveTraitement) {
			$this->addUsingAlias(AbsenceEleveTraitementPeer::ID, $absenceEleveTraitement->getId(), Criteria::NOT_EQUAL);
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

	// timestampable behavior
	
	/**
	 * Filter by the latest updated
	 *
	 * @param      int $nbDays Maximum age of the latest update in days
	 *
	 * @return     AbsenceEleveTraitementQuery The current query, for fuid interface
	 */
	public function recentlyUpdated($nbDays = 7)
	{
		return $this->addUsingAlias(AbsenceEleveTraitementPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Filter by the latest created
	 *
	 * @param      int $nbDays Maximum age of in days
	 *
	 * @return     AbsenceEleveTraitementQuery The current query, for fuid interface
	 */
	public function recentlyCreated($nbDays = 7)
	{
		return $this->addUsingAlias(AbsenceEleveTraitementPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
	}
	
	/**
	 * Order by update date desc
	 *
	 * @return     AbsenceEleveTraitementQuery The current query, for fuid interface
	 */
	public function lastUpdatedFirst()
	{
		return $this->addDescendingOrderByColumn(AbsenceEleveTraitementPeer::UPDATED_AT);
	}
	
	/**
	 * Order by update date asc
	 *
	 * @return     AbsenceEleveTraitementQuery The current query, for fuid interface
	 */
	public function firstUpdatedFirst()
	{
		return $this->addAscendingOrderByColumn(AbsenceEleveTraitementPeer::UPDATED_AT);
	}
	
	/**
	 * Order by create date desc
	 *
	 * @return     AbsenceEleveTraitementQuery The current query, for fuid interface
	 */
	public function lastCreatedFirst()
	{
		return $this->addDescendingOrderByColumn(AbsenceEleveTraitementPeer::CREATED_AT);
	}
	
	/**
	 * Order by create date asc
	 *
	 * @return     AbsenceEleveTraitementQuery The current query, for fuid interface
	 */
	public function firstCreatedFirst()
	{
		return $this->addAscendingOrderByColumn(AbsenceEleveTraitementPeer::CREATED_AT);
	}

} // BaseAbsenceEleveTraitementQuery