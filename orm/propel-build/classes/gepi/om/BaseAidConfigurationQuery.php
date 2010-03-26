<?php


/**
 * Base class that represents a query for the 'aid_config' table.
 *
 * Liste des categories d'AID (Activites inter-Disciplinaires)
 *
 * @method     AidConfigurationQuery orderByNom($order = Criteria::ASC) Order by the nom column
 * @method     AidConfigurationQuery orderByNomComplet($order = Criteria::ASC) Order by the nom_complet column
 * @method     AidConfigurationQuery orderByNoteMax($order = Criteria::ASC) Order by the note_max column
 * @method     AidConfigurationQuery orderByOrderDisplay1($order = Criteria::ASC) Order by the order_display1 column
 * @method     AidConfigurationQuery orderByOrderDisplay2($order = Criteria::ASC) Order by the order_display2 column
 * @method     AidConfigurationQuery orderByTypeNote($order = Criteria::ASC) Order by the type_note column
 * @method     AidConfigurationQuery orderByDisplayBegin($order = Criteria::ASC) Order by the display_begin column
 * @method     AidConfigurationQuery orderByDisplayEnd($order = Criteria::ASC) Order by the display_end column
 * @method     AidConfigurationQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method     AidConfigurationQuery orderByDisplayNom($order = Criteria::ASC) Order by the display_nom column
 * @method     AidConfigurationQuery orderByIndiceAid($order = Criteria::ASC) Order by the indice_aid column
 * @method     AidConfigurationQuery orderByDisplayBulletin($order = Criteria::ASC) Order by the display_bulletin column
 * @method     AidConfigurationQuery orderByBullSimplifie($order = Criteria::ASC) Order by the bull_simplifie column
 * @method     AidConfigurationQuery orderByOutilsComplementaires($order = Criteria::ASC) Order by the outils_complementaires column
 * @method     AidConfigurationQuery orderByFeuillePresence($order = Criteria::ASC) Order by the feuille_presence column
 *
 * @method     AidConfigurationQuery groupByNom() Group by the nom column
 * @method     AidConfigurationQuery groupByNomComplet() Group by the nom_complet column
 * @method     AidConfigurationQuery groupByNoteMax() Group by the note_max column
 * @method     AidConfigurationQuery groupByOrderDisplay1() Group by the order_display1 column
 * @method     AidConfigurationQuery groupByOrderDisplay2() Group by the order_display2 column
 * @method     AidConfigurationQuery groupByTypeNote() Group by the type_note column
 * @method     AidConfigurationQuery groupByDisplayBegin() Group by the display_begin column
 * @method     AidConfigurationQuery groupByDisplayEnd() Group by the display_end column
 * @method     AidConfigurationQuery groupByMessage() Group by the message column
 * @method     AidConfigurationQuery groupByDisplayNom() Group by the display_nom column
 * @method     AidConfigurationQuery groupByIndiceAid() Group by the indice_aid column
 * @method     AidConfigurationQuery groupByDisplayBulletin() Group by the display_bulletin column
 * @method     AidConfigurationQuery groupByBullSimplifie() Group by the bull_simplifie column
 * @method     AidConfigurationQuery groupByOutilsComplementaires() Group by the outils_complementaires column
 * @method     AidConfigurationQuery groupByFeuillePresence() Group by the feuille_presence column
 *
 * @method     AidConfigurationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     AidConfigurationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     AidConfigurationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     AidConfigurationQuery leftJoinAidDetails($relationAlias = '') Adds a LEFT JOIN clause to the query using the AidDetails relation
 * @method     AidConfigurationQuery rightJoinAidDetails($relationAlias = '') Adds a RIGHT JOIN clause to the query using the AidDetails relation
 * @method     AidConfigurationQuery innerJoinAidDetails($relationAlias = '') Adds a INNER JOIN clause to the query using the AidDetails relation
 *
 * @method     AidConfigurationQuery leftJoinJAidUtilisateursProfessionnels($relationAlias = '') Adds a LEFT JOIN clause to the query using the JAidUtilisateursProfessionnels relation
 * @method     AidConfigurationQuery rightJoinJAidUtilisateursProfessionnels($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JAidUtilisateursProfessionnels relation
 * @method     AidConfigurationQuery innerJoinJAidUtilisateursProfessionnels($relationAlias = '') Adds a INNER JOIN clause to the query using the JAidUtilisateursProfessionnels relation
 *
 * @method     AidConfigurationQuery leftJoinJAidEleves($relationAlias = '') Adds a LEFT JOIN clause to the query using the JAidEleves relation
 * @method     AidConfigurationQuery rightJoinJAidEleves($relationAlias = '') Adds a RIGHT JOIN clause to the query using the JAidEleves relation
 * @method     AidConfigurationQuery innerJoinJAidEleves($relationAlias = '') Adds a INNER JOIN clause to the query using the JAidEleves relation
 *
 * @method     AidConfiguration findOne(PropelPDO $con = null) Return the first AidConfiguration matching the query
 * @method     AidConfiguration findOneByNom(string $nom) Return the first AidConfiguration filtered by the nom column
 * @method     AidConfiguration findOneByNomComplet(string $nom_complet) Return the first AidConfiguration filtered by the nom_complet column
 * @method     AidConfiguration findOneByNoteMax(int $note_max) Return the first AidConfiguration filtered by the note_max column
 * @method     AidConfiguration findOneByOrderDisplay1(string $order_display1) Return the first AidConfiguration filtered by the order_display1 column
 * @method     AidConfiguration findOneByOrderDisplay2(int $order_display2) Return the first AidConfiguration filtered by the order_display2 column
 * @method     AidConfiguration findOneByTypeNote(string $type_note) Return the first AidConfiguration filtered by the type_note column
 * @method     AidConfiguration findOneByDisplayBegin(int $display_begin) Return the first AidConfiguration filtered by the display_begin column
 * @method     AidConfiguration findOneByDisplayEnd(int $display_end) Return the first AidConfiguration filtered by the display_end column
 * @method     AidConfiguration findOneByMessage(string $message) Return the first AidConfiguration filtered by the message column
 * @method     AidConfiguration findOneByDisplayNom(string $display_nom) Return the first AidConfiguration filtered by the display_nom column
 * @method     AidConfiguration findOneByIndiceAid(int $indice_aid) Return the first AidConfiguration filtered by the indice_aid column
 * @method     AidConfiguration findOneByDisplayBulletin(string $display_bulletin) Return the first AidConfiguration filtered by the display_bulletin column
 * @method     AidConfiguration findOneByBullSimplifie(string $bull_simplifie) Return the first AidConfiguration filtered by the bull_simplifie column
 * @method     AidConfiguration findOneByOutilsComplementaires(string $outils_complementaires) Return the first AidConfiguration filtered by the outils_complementaires column
 * @method     AidConfiguration findOneByFeuillePresence(string $feuille_presence) Return the first AidConfiguration filtered by the feuille_presence column
 *
 * @method     array findByNom(string $nom) Return AidConfiguration objects filtered by the nom column
 * @method     array findByNomComplet(string $nom_complet) Return AidConfiguration objects filtered by the nom_complet column
 * @method     array findByNoteMax(int $note_max) Return AidConfiguration objects filtered by the note_max column
 * @method     array findByOrderDisplay1(string $order_display1) Return AidConfiguration objects filtered by the order_display1 column
 * @method     array findByOrderDisplay2(int $order_display2) Return AidConfiguration objects filtered by the order_display2 column
 * @method     array findByTypeNote(string $type_note) Return AidConfiguration objects filtered by the type_note column
 * @method     array findByDisplayBegin(int $display_begin) Return AidConfiguration objects filtered by the display_begin column
 * @method     array findByDisplayEnd(int $display_end) Return AidConfiguration objects filtered by the display_end column
 * @method     array findByMessage(string $message) Return AidConfiguration objects filtered by the message column
 * @method     array findByDisplayNom(string $display_nom) Return AidConfiguration objects filtered by the display_nom column
 * @method     array findByIndiceAid(int $indice_aid) Return AidConfiguration objects filtered by the indice_aid column
 * @method     array findByDisplayBulletin(string $display_bulletin) Return AidConfiguration objects filtered by the display_bulletin column
 * @method     array findByBullSimplifie(string $bull_simplifie) Return AidConfiguration objects filtered by the bull_simplifie column
 * @method     array findByOutilsComplementaires(string $outils_complementaires) Return AidConfiguration objects filtered by the outils_complementaires column
 * @method     array findByFeuillePresence(string $feuille_presence) Return AidConfiguration objects filtered by the feuille_presence column
 *
 * @package    propel.generator.gepi.om
 */
abstract class BaseAidConfigurationQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BaseAidConfigurationQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'gepi', $modelName = 'AidConfiguration', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new AidConfigurationQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    AidConfigurationQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof AidConfigurationQuery) {
			return $criteria;
		}
		$query = new AidConfigurationQuery();
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
		if ((null !== ($obj = AidConfigurationPeer::getInstanceFromPool((string) $key))) && $this->getFormatter()->isObjectFormatter()) {
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
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		return $this->addUsingAlias(AidConfigurationPeer::INDICE_AID, $key, Criteria::EQUAL);
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		return $this->addUsingAlias(AidConfigurationPeer::INDICE_AID, $keys, Criteria::IN);
	}

	/**
	 * Filter the query on the nom column
	 * 
	 * @param     string $nom The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByNom($nom = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($nom)) {
			return $this->addUsingAlias(AidConfigurationPeer::NOM, $nom, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $nom)) {
			return $this->addUsingAlias(AidConfigurationPeer::NOM, str_replace('*', '%', $nom), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::NOM, $nom, $comparison);
		}
	}

	/**
	 * Filter the query on the nom_complet column
	 * 
	 * @param     string $nomComplet The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByNomComplet($nomComplet = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($nomComplet)) {
			return $this->addUsingAlias(AidConfigurationPeer::NOM_COMPLET, $nomComplet, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $nomComplet)) {
			return $this->addUsingAlias(AidConfigurationPeer::NOM_COMPLET, str_replace('*', '%', $nomComplet), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::NOM_COMPLET, $nomComplet, $comparison);
		}
	}

	/**
	 * Filter the query on the note_max column
	 * 
	 * @param     int|array $noteMax The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByNoteMax($noteMax = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($noteMax)) {
			if (array_values($noteMax) === $noteMax) {
				return $this->addUsingAlias(AidConfigurationPeer::NOTE_MAX, $noteMax, Criteria::IN);
			} else {
				if (isset($noteMax['min'])) {
					$this->addUsingAlias(AidConfigurationPeer::NOTE_MAX, $noteMax['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($noteMax['max'])) {
					$this->addUsingAlias(AidConfigurationPeer::NOTE_MAX, $noteMax['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::NOTE_MAX, $noteMax, $comparison);
		}
	}

	/**
	 * Filter the query on the order_display1 column
	 * 
	 * @param     string $orderDisplay1 The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByOrderDisplay1($orderDisplay1 = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($orderDisplay1)) {
			return $this->addUsingAlias(AidConfigurationPeer::ORDER_DISPLAY1, $orderDisplay1, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $orderDisplay1)) {
			return $this->addUsingAlias(AidConfigurationPeer::ORDER_DISPLAY1, str_replace('*', '%', $orderDisplay1), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::ORDER_DISPLAY1, $orderDisplay1, $comparison);
		}
	}

	/**
	 * Filter the query on the order_display2 column
	 * 
	 * @param     int|array $orderDisplay2 The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByOrderDisplay2($orderDisplay2 = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($orderDisplay2)) {
			if (array_values($orderDisplay2) === $orderDisplay2) {
				return $this->addUsingAlias(AidConfigurationPeer::ORDER_DISPLAY2, $orderDisplay2, Criteria::IN);
			} else {
				if (isset($orderDisplay2['min'])) {
					$this->addUsingAlias(AidConfigurationPeer::ORDER_DISPLAY2, $orderDisplay2['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($orderDisplay2['max'])) {
					$this->addUsingAlias(AidConfigurationPeer::ORDER_DISPLAY2, $orderDisplay2['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::ORDER_DISPLAY2, $orderDisplay2, $comparison);
		}
	}

	/**
	 * Filter the query on the type_note column
	 * 
	 * @param     string $typeNote The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByTypeNote($typeNote = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($typeNote)) {
			return $this->addUsingAlias(AidConfigurationPeer::TYPE_NOTE, $typeNote, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $typeNote)) {
			return $this->addUsingAlias(AidConfigurationPeer::TYPE_NOTE, str_replace('*', '%', $typeNote), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::TYPE_NOTE, $typeNote, $comparison);
		}
	}

	/**
	 * Filter the query on the display_begin column
	 * 
	 * @param     int|array $displayBegin The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByDisplayBegin($displayBegin = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($displayBegin)) {
			if (array_values($displayBegin) === $displayBegin) {
				return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_BEGIN, $displayBegin, Criteria::IN);
			} else {
				if (isset($displayBegin['min'])) {
					$this->addUsingAlias(AidConfigurationPeer::DISPLAY_BEGIN, $displayBegin['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($displayBegin['max'])) {
					$this->addUsingAlias(AidConfigurationPeer::DISPLAY_BEGIN, $displayBegin['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_BEGIN, $displayBegin, $comparison);
		}
	}

	/**
	 * Filter the query on the display_end column
	 * 
	 * @param     int|array $displayEnd The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByDisplayEnd($displayEnd = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($displayEnd)) {
			if (array_values($displayEnd) === $displayEnd) {
				return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_END, $displayEnd, Criteria::IN);
			} else {
				if (isset($displayEnd['min'])) {
					$this->addUsingAlias(AidConfigurationPeer::DISPLAY_END, $displayEnd['min'], Criteria::GREATER_EQUAL);
				}
				if (isset($displayEnd['max'])) {
					$this->addUsingAlias(AidConfigurationPeer::DISPLAY_END, $displayEnd['max'], Criteria::LESS_EQUAL);
				}
				return $this;	
			}
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_END, $displayEnd, $comparison);
		}
	}

	/**
	 * Filter the query on the message column
	 * 
	 * @param     string $message The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByMessage($message = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($message)) {
			return $this->addUsingAlias(AidConfigurationPeer::MESSAGE, $message, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $message)) {
			return $this->addUsingAlias(AidConfigurationPeer::MESSAGE, str_replace('*', '%', $message), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::MESSAGE, $message, $comparison);
		}
	}

	/**
	 * Filter the query on the display_nom column
	 * 
	 * @param     string $displayNom The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByDisplayNom($displayNom = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($displayNom)) {
			return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_NOM, $displayNom, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $displayNom)) {
			return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_NOM, str_replace('*', '%', $displayNom), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_NOM, $displayNom, $comparison);
		}
	}

	/**
	 * Filter the query on the indice_aid column
	 * 
	 * @param     int|array $indiceAid The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByIndiceAid($indiceAid = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($indiceAid)) {
			return $this->addUsingAlias(AidConfigurationPeer::INDICE_AID, $indiceAid, Criteria::IN);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::INDICE_AID, $indiceAid, $comparison);
		}
	}

	/**
	 * Filter the query on the display_bulletin column
	 * 
	 * @param     string $displayBulletin The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByDisplayBulletin($displayBulletin = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($displayBulletin)) {
			return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_BULLETIN, $displayBulletin, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $displayBulletin)) {
			return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_BULLETIN, str_replace('*', '%', $displayBulletin), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::DISPLAY_BULLETIN, $displayBulletin, $comparison);
		}
	}

	/**
	 * Filter the query on the bull_simplifie column
	 * 
	 * @param     string $bullSimplifie The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByBullSimplifie($bullSimplifie = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($bullSimplifie)) {
			return $this->addUsingAlias(AidConfigurationPeer::BULL_SIMPLIFIE, $bullSimplifie, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $bullSimplifie)) {
			return $this->addUsingAlias(AidConfigurationPeer::BULL_SIMPLIFIE, str_replace('*', '%', $bullSimplifie), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::BULL_SIMPLIFIE, $bullSimplifie, $comparison);
		}
	}

	/**
	 * Filter the query on the outils_complementaires column
	 * 
	 * @param     string $outilsComplementaires The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByOutilsComplementaires($outilsComplementaires = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($outilsComplementaires)) {
			return $this->addUsingAlias(AidConfigurationPeer::OUTILS_COMPLEMENTAIRES, $outilsComplementaires, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $outilsComplementaires)) {
			return $this->addUsingAlias(AidConfigurationPeer::OUTILS_COMPLEMENTAIRES, str_replace('*', '%', $outilsComplementaires), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::OUTILS_COMPLEMENTAIRES, $outilsComplementaires, $comparison);
		}
	}

	/**
	 * Filter the query on the feuille_presence column
	 * 
	 * @param     string $feuillePresence The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByFeuillePresence($feuillePresence = null, $comparison = Criteria::EQUAL)
	{
		if (is_array($feuillePresence)) {
			return $this->addUsingAlias(AidConfigurationPeer::FEUILLE_PRESENCE, $feuillePresence, Criteria::IN);
		} elseif(preg_match('/[\%\*]/', $feuillePresence)) {
			return $this->addUsingAlias(AidConfigurationPeer::FEUILLE_PRESENCE, str_replace('*', '%', $feuillePresence), Criteria::LIKE);
		} else {
			return $this->addUsingAlias(AidConfigurationPeer::FEUILLE_PRESENCE, $feuillePresence, $comparison);
		}
	}

	/**
	 * Filter the query by a related AidDetails object
	 *
	 * @param     AidDetails $aidDetails  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByAidDetails($aidDetails, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AidConfigurationPeer::INDICE_AID, $aidDetails->getIndiceAid(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the AidDetails relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function joinAidDetails($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('AidDetails');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'AidDetails');
		}
		
		return $this;
	}

	/**
	 * Use the AidDetails relation AidDetails object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AidDetailsQuery A secondary query class using the current class as primary query
	 */
	public function useAidDetailsQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinAidDetails($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'AidDetails', 'AidDetailsQuery');
	}

	/**
	 * Filter the query by a related JAidUtilisateursProfessionnels object
	 *
	 * @param     JAidUtilisateursProfessionnels $jAidUtilisateursProfessionnels  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByJAidUtilisateursProfessionnels($jAidUtilisateursProfessionnels, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AidConfigurationPeer::INDICE_AID, $jAidUtilisateursProfessionnels->getIndiceAid(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JAidUtilisateursProfessionnels relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function joinJAidUtilisateursProfessionnels($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JAidUtilisateursProfessionnels');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JAidUtilisateursProfessionnels');
		}
		
		return $this;
	}

	/**
	 * Use the JAidUtilisateursProfessionnels relation JAidUtilisateursProfessionnels object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JAidUtilisateursProfessionnelsQuery A secondary query class using the current class as primary query
	 */
	public function useJAidUtilisateursProfessionnelsQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJAidUtilisateursProfessionnels($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JAidUtilisateursProfessionnels', 'JAidUtilisateursProfessionnelsQuery');
	}

	/**
	 * Filter the query by a related JAidEleves object
	 *
	 * @param     JAidEleves $jAidEleves  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function filterByJAidEleves($jAidEleves, $comparison = Criteria::EQUAL)
	{
		return $this
			->addUsingAlias(AidConfigurationPeer::INDICE_AID, $jAidEleves->getIndiceAid(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the JAidEleves relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function joinJAidEleves($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('JAidEleves');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'JAidEleves');
		}
		
		return $this;
	}

	/**
	 * Use the JAidEleves relation JAidEleves object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    JAidElevesQuery A secondary query class using the current class as primary query
	 */
	public function useJAidElevesQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->joinJAidEleves($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'JAidEleves', 'JAidElevesQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     AidConfiguration $aidConfiguration Object to remove from the list of results
	 *
	 * @return    AidConfigurationQuery The current query, for fluid interface
	 */
	public function prune($aidConfiguration = null)
	{
		if ($aidConfiguration) {
			$this->addUsingAlias(AidConfigurationPeer::INDICE_AID, $aidConfiguration->getIndiceAid(), Criteria::NOT_EQUAL);
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

} // BaseAidConfigurationQuery
