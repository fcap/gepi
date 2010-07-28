<?php



/**
 * Skeleton subclass for representing a row from the 'a_saisies' table.
 *
 * Chaque saisie d'absence doit faire l'objet d'une ligne dans la table a_saisies. Une saisie peut etre : une plage horaire longue dur�e (plusieurs jours), d�fini avec les champs debut_abs et fin_abs. Un creneau horaire, le jour etant precis� dans debut_abs. Un cours de l'emploi du temps, le jours du cours etant precis� dans debut_abs.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.gepi
 */
class AbsenceEleveSaisie extends BaseAbsenceEleveSaisie {

	/**
	 * @var        bool to store aggregation of ResponsabiliteEtablissement value
	 */
	protected $responsabiliteEtablissement;
    
	/**
	 *
	 * Renvoi une description intelligible du traitement
	 *
	 * @return     String description
	 *
	 */
	public function getDescription() {
	    $desc = '';
	    if ($this->getEleve() != null) {
		$desc .= $this->getEleve()->getCivilite().' '.$this->getEleve()->getNom().' '.$this->getEleve()->getPrenom();
	    }
	    $desc .= ' '.$this->getDateDescription();
	    $desc .= ' ';
	    if ($this->getClasse() != null) {
		$desc .= "; classe : ".$this->getClasse()->getNomComplet();
	    }
	    if ($this->getGroupe() != null) {
		$desc .= "; groupe : ".$this->getGroupe()->getName();
	    }
	    if ($this->getAidDetails() != null) {
		$desc .= "; aid : ".$this->getAidDetails()->getNom();
	    }
//	    if ($this->getNotifiee() != null) { //desactive pour ameliorer les performances
//		$desc .= "; notifi�e";
//	    }
	    if ($this->getCommentaire() != null && $this->getCommentaire() != '') {
		$desc .= "; ".$this->getCommentaire();
	    }
	    return $desc;
	}

	/**
	 *
	 * Renvoi une liste intelligible des types associes
	 *
	 * @return     String description
	 *
	 */
	public function getTypesDescription() {
	    $traitement_col = $this->getAbsenceEleveTraitements();
	    $besoin_echo_type = true;
	    $besoin_echo_virgule = false;
	    foreach ($traitement_col as $bou_traitement) {
		if ($bou_traitement->getAbsenceEleveType() != null) {
		    if ($besoin_echo_type) {
			echo 'type : ';
			$besoin_echo_type = false;
		    }
		    if ($besoin_echo_virgule) {
			echo ', ';
			$besoin_echo_virgule = false;
		    }
		    echo $bou_traitement->getAbsenceEleveType()->getNom();
		    $besoin_echo_virgule = true;
		}
	    }
	}
	
	/**
	 *
	 * Renvoi true ou false en fonction des types associ�
	 *
	 * @return     boolean
	 *
	 */
	public function hasTypeSaisieDiscipline() {
	    $traitements = $this->getAbsenceEleveTraitements();
	    foreach ($traitements as $traitement) {
		if ($traitement->getAbsenceEleveType() != null &&
		    $traitement->getAbsenceEleveType()->getTypeSaisie() == 'DISCIPLINE') {
		    return true;
		}
	    }
	    return false;
	}

	/**
	 *
	 * Renvoi une chaine de caractere compr�hensible concernant les dates de debut et de fin
	 *
	 * @return     string
	 *
	 */
	public function getDateDescription() {
	    $message = '';
	    if ($this->getDebutAbs('d/m/Y') == $this->getFinAbs('d/m/Y')) {
		$message .= 'Le ';
		$message .= (strftime("%a %d %b", $this->getDebutAbs('U')));
		$message .= ' de ';
		$message .= $this->getDebutAbs('H:i');
		$message .= ' a ';
		$message .= $this->getFinAbs('H:i');

	    } else {
		$message .= 'Du ';
		$message .= (strftime("%a %d %b %H:%M", $this->getDebutAbs('U')));
		$message .= ' au ';
		$message .= (strftime("%a %d %b %H:%M", $this->getFinAbs('U')));
	    }
	    return $message;
	}

	/**
	 *
	 * Renvoi true ou false en fonction de la saisie
	 *
	 * @return     boolean
	 *
	 */
	public function getRetard() {
	    //est consid�r� retard toute absence inferieure a 30 min
	    //todo rendre ceci configurable
	    return (($this->getFinAbs('U') - $this->getDebutAbs('U')) < 60*30);
	}

	/**
	 *
	 * Renvoi true ou false si l'eleve etait sous la responsabilite de l'etablissement (infirmerie ou autre)
	 * si aucun traitement ou type n'est associ� on renvoi faux par defaut
	 *
	 * @return     boolean
	 *
	 */
	public function getResponsabiliteEtablissement() {
	    if (!isset($responsabiliteEtablissement) || $responsabiliteEtablissement === null) {
		$traitements = $this->getAbsenceEleveTraitements();
		foreach ($traitements as $traitement) {
		    if ($traitement->getAbsenceEleveType() != null &&
			$traitement->getAbsenceEleveType()->getResponsabiliteEtablissement()) {
			$responsabiliteEtablissement = true;
			return true;
		    }
		}
		$responsabiliteEtablissement = false;
		return false;
	    } else {
		return $responsabiliteEtablissement;
	    }
	}

	/**
	 *
	 * Renvoi true ou false en fonction des justifications apporte
	 *
	 * @return     boolean
	 *
	 */
	public function getJustifiee() {
	    foreach($this->getAbsenceEleveTraitements() as $traitement) {
		$traitement = new AbsenceEleveTraitement();
		if ($traitement->getAbsenceEleveJustification() != null) {
		    return true;
		}
	    }
	    return false;
	}

	/**
	 *
	 * Renvoi le nom du groupe (avec les classe) ou une chaine vide
	 * utilise pour les template tbs
	 * @return     string
	 *
	 */
	public function getGroupeNameAvecClasses() {
	    if ($this->getGroupe() != null) {
		return $this->getGroupe()->getNameAvecClasses();
	    } else {
		return '';
	    }
	}
	/**
	 *
	 * Renvoi le nom du groupe ou une chaine vide
	 * utilise pour les template tbs
	 * @return     string
	 *
	 */
	public function getGroupeName() {
	    if ($this->getGroupe() != null) {
		return $this->getGroupe()->getName();
	    } else {
		return '';
	    }
	}

	/**
	 *
	 * Renvoi true si un traitement est associe a la saisie
	 *
	 * @return     boolean
	 *
	 */
	public function getTraitee() {
	    return ($this->getAbsenceEleveTraitements()->count() != 0);
	}

	/**
	 *
	 * Renvoi true si une notification a ete recue par la famille
	 *
	 * @return     boolean
	 *
	 */
	public function getNotifiee() {
	    foreach ($this->getAbsenceEleveTraitements() as $traitement) {
		foreach ($traitement->getAbsenceEleveNotifications() as $notification) {
		    if ($notification->getStatutEnvoi() == AbsenceEleveNotification::$STATUT_SUCCES || $notification->getStatutEnvoi() == AbsenceEleveNotification::$STATUT_SUCCES_AR) {
			return true;
		    }
		}
	    }
	    return false;
	}

	/**
	 * Gets a collection of AbsenceEleveTraitement objects related by a many-to-many relationship
	 * to the current object by way of the j_traitements_saisies cross-reference table.
	 *
	 * ajout d'un join pour recuperer les types en meme temps que les traitements
	 *
	 * If the $criteria is not null, it is used to always fetch the results from the database.
	 * Otherwise the results are fetched from the database the first time, then cached.
	 * Next time the same method is called without $criteria, the cached collection is returned.
	 * If this AbsenceEleveSaisie is new, it will return
	 * an empty collection or the current collection; the criteria is ignored on a new object.
	 *
	 * @param      Criteria $criteria Optional query object to filter the query
	 * @param      PropelPDO $con Optional connection object
	 *
	 * @return     PropelCollection|array AbsenceEleveTraitement[] List of AbsenceEleveTraitement objects
	 */
	public function getAbsenceEleveTraitements($criteria = null, PropelPDO $con = null)
	{
		if(null === $this->collAbsenceEleveTraitements || null !== $criteria) {
			if ($this->isNew() && null === $this->collAbsenceEleveTraitements) {
				// return empty collection
				$this->initAbsenceEleveTraitements();
			} else {
				if ($this->collJTraitementSaisieEleves === null || null !== $criteria) {
				    $collAbsenceEleveTraitements = AbsenceEleveTraitementQuery::create(null, $criteria)
					    ->filterByAbsenceEleveSaisie($this)
					    ->leftJoin('AbsenceEleveTraitement.AbsenceEleveType')->with('AbsenceEleveType')
					    ->leftJoin('AbsenceEleveTraitement.AbsenceEleveNotification')->with('AbsenceEleveNotification')
					    ->leftJoin('AbsenceEleveTraitement.AbsenceEleveJustification')->with('AbsenceEleveJustification')
   					    ->find($con);
				    if (null !== $criteria) {
					    return $collAbsenceEleveTraitements;
				    }
				    $this->collAbsenceEleveTraitements = $collAbsenceEleveTraitements;
				} else {
				    $this->collAbsenceEleveTraitements = new PropelObjectCollection();
				    $this->collAbsenceEleveTraitements->setModel('AbsenceEleveTraitement');
				    foreach ($this->collJTraitementSaisieEleves as $jTraitementSaisieEleve) {
					$this->collAbsenceEleveTraitements->add($jTraitementSaisieEleve->getAbsenceEleveTraitement());
				    }
				}
			}
		}
		return $this->collAbsenceEleveTraitements;
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * ADDED : on ne verifie pas les objets lies car c'est exponentiel
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();

			if (($retval = AbsenceEleveSaisiePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collJTraitementSaisieEleves !== null) {
					foreach ($this->collJTraitementSaisieEleves as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 *
	 * Renvoi un liste de saisies qui sont en contradiction avec celle la
	 *
	 * @return PropelObjectCollection AbsenceEleveSaisie[]
	 *
	 */
	public function getSaisiesContradictoires() {
	    $result = new PropelObjectCollection();
	    $result->setModel('AbsenceEleveSaisie');
	    if ($this->getEleve() === null) {
		return $result;
	    }

	    //on regarde les saisies sur cet eleve
	    $eleve = $this->getEleve();
	    $resp = $this->getResponsabiliteEtablissement();
	    foreach ($eleve->getAbsenceEleveSaisiesFilterByDate($this->getDebutAbs(), $this->getFinAbs()) as $saisie) {
		if ($resp !== $saisie->getResponsabiliteEtablissement()) {
		    $result->append($saisie);
		}
	    }

	    if ($resp == false) {
		//on recupere les saisies qui se chevauchent avec celle-la
		//optimisation : utiliser la requete pour stocker ca
		if (isset($_REQUEST['query_AbsenceEleveSaisieQuery_getSaisiesContradictoires_'.$this->getDebutAbs('U').'_'.$this->getFinAbs('U')])
			&& $_REQUEST['query_AbsenceEleveSaisieQuery_getSaisiesContradictoires_'.$this->getDebutAbs('U').'_'.$this->getFinAbs('U')] != null) {
		    $saisie_col = $_REQUEST['query_AbsenceEleveSaisieQuery_getSaisiesContradictoires_'.$this->getDebutAbs('U').'_'.$this->getFinAbs('U')];
		} else {
		    $query = AbsenceEleveSaisieQuery::create();
		    $query->filterByFinAbs($this->getDebutAbs(), Criteria::GREATER_THAN);
		    $query->filterByDebutAbs($this->getFinAbs(), Criteria::LESS_EQUAL);
		    $query->leftJoinWith('AbsenceEleveSaisie.JTraitementSaisieEleve')
			->leftJoinWith('JTraitementSaisieEleve.AbsenceEleveTraitement')
			->leftJoinWith('AbsenceEleveTraitement.AbsenceEleveType');
		    $saisie_col = $query->find();
		    $_REQUEST['query_AbsenceEleveSaisieQuery_getSaisiesContradictoires_'.$this->getDebutAbs('U').'_'.$this->getFinAbs('U')] = $saisie_col;
		}

		//on va filtrer pour supprimer de la liste les aid classe ou groupe qui serait le meme que cette saisie la
		$temp_saisie_col = new PropelObjectCollection();
		$temp_saisie_col->setModel('AbsenceEleveSaisie');
		if ($this->getClasse() != null) {
		    foreach ($saisie_col as $saisie) {
			if ($saisie->getIdClasse() != $this->getIdClasse()) {
			    $temp_saisie_col->append($saisie);
			}
		    }
		} elseif ($this->getGroupe() != null) {
		    foreach ($saisie_col as $saisie) {
			if ($saisie->getIdGroupe() != $this->getIdGroupe()) {
			    $temp_saisie_col->append($saisie);
			}
		    }
		} elseif ($this->getAidDetails() != null) {
		    foreach ($saisie_col as $saisie) {
			if ($saisie->getIdAid() != $this->getIdAid()) {
			    $temp_saisie_col->append($saisie);
			}
		    }
		} else {
		    foreach ($saisie_col as $saisie) {
			if ($saisie->getId() != $this->getId()) {
			    $temp_saisie_col->append($saisie);
			}
		    }
		}
		$saisie_col = $temp_saisie_col;

		//on regarde si un groupe, classe ou aid auquel appartient cet eleve a �t� saisi et pour lequel l'eleve en question n'a pas ete saisi (c'est donc que l'eleve est present)
		//on va utiliser comme periode pour determiner les classes et groupes la periode correspondant au debut de l'absence
		$periode = $eleve->getPeriodeNote($this->getDebutAbs(null));

		//on recupere la liste des classes de l'eleve et on regarde si il y a eu des saisies pour ces classes
		$classes = $eleve->getClasses($periode);
		$saisie_col_classe_id_array = $saisie_col->toKeyValue('PrimaryKey','IdClasse');
		$saisie_col_array_copy = $saisie_col->getArrayCopy('Id');
		foreach ($classes as $classe) {
		    $keys = array_keys($saisie_col_classe_id_array, $classe->getId());
		    if (!empty($keys)) {
			//on a des saisies pour cette classe
			//est-ce que l'eleve a bien �t� saisi absent ?
			$temp_col = new PropelObjectCollection();
			$bool_eleve_saisi = false;
			foreach ($keys as $key) {
			    $saisie_temp = $saisie_col_array_copy[$key];
			    $temp_col->append($saisie_temp);
			    if ($this->getEleveId() == $saisie_temp->getEleveId()) {
				$bool_eleve_saisi = true;
			    }
			}
			if (!$bool_eleve_saisi) {
			    //l'eleve n'a pas ete saisi, c'est contradictoire
			    $result->addCollection($temp_col);
			}
		    }
		}
		
		//on recupere la liste des groupes de l'eleve et on regarde si il y a eu des saisies pour ces groupes
		$groupes = $eleve->getGroupes($periode);
		$saisie_col_groupe_id_array = $saisie_col->toKeyValue('PrimaryKey','IdGroupe');
		foreach ($groupes as $groupe) {
		    $keys = array_keys($saisie_col_groupe_id_array, $groupe->getId());
		    if (!empty($keys)) {
			//on a des saisies pour cette groupe
			//est-ce que l'eleve a bien �t� saisi absent ?
			$temp_col = new PropelObjectCollection();
			$bool_eleve_saisi = false;
			foreach ($keys as $key) {
			    $saisie_temp = $saisie_col_array_copy[$key];
			    $temp_col->append($saisie_temp);
			    if ($this->getEleveId() == $saisie_temp->getEleveId()) {
				$bool_eleve_saisi = true;
			    }
			}
			if (!$bool_eleve_saisi) {
			    //l'eleve n'a pas ete saisi, c'est contradictoire
			    $result->addCollection($temp_col);
			}
		    }
		}

		//on recupere la liste des aids de l'eleve et on regarde si il y a eu des saisies pour ces aids
		$aids = $eleve->getAidDetailss($periode);
		$saisie_col_aid_id_array = $saisie_col->toKeyValue('PrimaryKey','IdAid');
		foreach ($aids as $aid) {
		    $keys = array_keys($saisie_col_aid_id_array, $aid->getId());
		    if (!empty($keys)) {
			//on a des saisies pour cette aid
			//est-ce que l'eleve a bien �t� saisi absent ?
			$temp_col = new PropelObjectCollection();
			$bool_eleve_saisi = false;
			foreach ($keys as $key) {
			    $saisie_temp = $saisie_col_array_copy[$key];
			    $temp_col->append($saisie_temp);
			    if ($this->getEleveId() == $saisie_temp->getEleveId()) {
				$bool_eleve_saisi = true;
			    }
			}
			if (!$bool_eleve_saisi) {
			    //l'eleve n'a pas ete saisi, c'est contradictoire
			    $result->addCollection($temp_col);
			}
		    }
		}
	    }
	    return $result;
	}

	/**
	 *
	 * Renvoi true/false selon qu'il y a des saisies contradictoires
	 *
	 * @return PropelObjectCollection AbsenceEleveSaisie[]
	 *
	 */
	public function isSaisiesContradictoires() {
	    $result = new PropelObjectCollection();
	    $result->setModel('AbsenceEleveSaisie');
	    if ($this->getEleve() === null) {
		return false;
	    }

	    //on regarde les saisies sur cet eleve
	    $eleve = $this->getEleve();
	    $resp = $this->getResponsabiliteEtablissement();
	    foreach ($eleve->getAbsenceEleveSaisiesFilterByDate($this->getDebutAbs(), $this->getFinAbs()) as $saisie) {
		if ($resp !== $saisie->getResponsabiliteEtablissement()) {
		    return true;
		}
	    }

	    if ($resp == false) {
		//on recupere les saisies qui se chevauchent avec celle-la
		//optimisation : utiliser la requete pour stocker ca
		if (isset($_REQUEST['query_AbsenceEleveSaisieQuery_getSaisiesContradictoires_'.$this->getDebutAbs('U').'_'.$this->getFinAbs('U')])
			&& $_REQUEST['query_AbsenceEleveSaisieQuery_getSaisiesContradictoires_'.$this->getDebutAbs('U').'_'.$this->getFinAbs('U')] != null) {
		    $saisie_col = $_REQUEST['query_AbsenceEleveSaisieQuery_getSaisiesContradictoires_'.$this->getDebutAbs('U').'_'.$this->getFinAbs('U')];
		} else {
		    $query = AbsenceEleveSaisieQuery::create();
		    $query->filterByFinAbs($this->getDebutAbs(), Criteria::GREATER_THAN);
		    $query->filterByDebutAbs($this->getFinAbs(), Criteria::LESS_EQUAL);
		    $query->leftJoinWith('AbsenceEleveSaisie.JTraitementSaisieEleve')
			->leftJoinWith('JTraitementSaisieEleve.AbsenceEleveTraitement')
			->leftJoinWith('AbsenceEleveTraitement.AbsenceEleveType');
		    $saisie_col = $query->find();
		    $_REQUEST['query_AbsenceEleveSaisieQuery_getSaisiesContradictoires_'.$this->getDebutAbs('U').'_'.$this->getFinAbs('U')] = $saisie_col;
		}

		//on va filtrer pour supprimer de la liste les aid classe ou groupe qui serait le meme que cette saisie la
		$temp_saisie_col = new PropelObjectCollection();
		$temp_saisie_col->setModel('AbsenceEleveSaisie');
		if ($this->getClasse() != null) {
		    foreach ($saisie_col as $saisie) {
			if ($saisie->getIdClasse() != $this->getIdClasse()) {
			    $temp_saisie_col->append($saisie);
			}
		    }
		} elseif ($this->getGroupe() != null) {
		    foreach ($saisie_col as $saisie) {
			if ($saisie->getIdGroupe() != $this->getIdGroupe()) {
			    $temp_saisie_col->append($saisie);
			}
		    }
		} elseif ($this->getAidDetails() != null) {
		    foreach ($saisie_col as $saisie) {
			if ($saisie->getIdAid() != $this->getIdAid()) {
			    $temp_saisie_col->append($saisie);
			}
		    }
		} else {
		    foreach ($saisie_col as $saisie) {
			if ($saisie->getId() != $this->getId()) {
			    $temp_saisie_col->append($saisie);
			}
		    }
		}
		$saisie_col = $temp_saisie_col;

		//on regarde si un groupe, classe ou aid auquel appartient cet eleve a �t� saisi et pour lequel l'eleve en question n'a pas ete saisi (c'est donc que l'eleve est present)
		//on va utiliser comme periode pour determiner les classes et groupes la periode correspondant au debut de l'absence
		$periode = $eleve->getPeriodeNote($this->getDebutAbs(null));

		//on recupere la liste des classes de l'eleve et on regarde si il y a eu des saisies pour ces classes
		$classes = $eleve->getClasses($periode);
		$saisie_col_classe_id_array = $saisie_col->toKeyValue('PrimaryKey','IdClasse');
		$saisie_col_array_copy = $saisie_col->getArrayCopy('Id');
		foreach ($classes as $classe) {
		    $keys = array_keys($saisie_col_classe_id_array, $classe->getId());
		    if (!empty($keys)) {
			//on a des saisies pour cette classe
			//est-ce que l'eleve a bien �t� saisi absent ?
			$temp_col = new PropelObjectCollection();
			$bool_eleve_saisi = false;
			foreach ($keys as $key) {
			    $saisie_temp = $saisie_col_array_copy[$key];
			    $temp_col->append($saisie_temp);
			    if ($this->getEleveId() == $saisie_temp->getEleveId()) {
				$bool_eleve_saisi = true;
			    }
			}
			if (!$bool_eleve_saisi) {
			    //l'eleve n'a pas ete saisi, c'est contradictoire
			    return true;
			}
		    }
		}

		//on recupere la liste des groupes de l'eleve et on regarde si il y a eu des saisies pour ces groupes
		$groupes = $eleve->getGroupes($periode);
		$saisie_col_groupe_id_array = $saisie_col->toKeyValue('PrimaryKey','IdGroupe');
		foreach ($groupes as $groupe) {
		    $keys = array_keys($saisie_col_groupe_id_array, $groupe->getId());
		    if (!empty($keys)) {
			//on a des saisies pour cette groupe
			//est-ce que l'eleve a bien �t� saisi absent ?
			$temp_col = new PropelObjectCollection();
			$bool_eleve_saisi = false;
			foreach ($keys as $key) {
			    $saisie_temp = $saisie_col_array_copy[$key];
			    $temp_col->append($saisie_temp);
			    if ($this->getEleveId() == $saisie_temp->getEleveId()) {
				$bool_eleve_saisi = true;
			    }
			}
			if (!$bool_eleve_saisi) {
			    //l'eleve n'a pas ete saisi, c'est contradictoire
			    return true;
			}
		    }
		}

		//on recupere la liste des aids de l'eleve et on regarde si il y a eu des saisies pour ces aids
		$aids = $eleve->getAidDetailss($periode);
		$saisie_col_aid_id_array = $saisie_col->toKeyValue('PrimaryKey','IdAid');
		foreach ($aids as $aid) {
		    $keys = array_keys($saisie_col_aid_id_array, $aid->getId());
		    if (!empty($keys)) {
			//on a des saisies pour cette aid
			//est-ce que l'eleve a bien �t� saisi absent ?
			$temp_col = new PropelObjectCollection();
			$bool_eleve_saisi = false;
			foreach ($keys as $key) {
			    $saisie_temp = $saisie_col_array_copy[$key];
			    $temp_col->append($saisie_temp);
			    if ($this->getEleveId() == $saisie_temp->getEleveId()) {
				$bool_eleve_saisi = true;
			    }
			}
			if (!$bool_eleve_saisi) {
			    //l'eleve n'a pas ete saisi, c'est contradictoire
			    return true;
			}
		    }
		}
	    }
	    return !$result->isEmpty();
	}
} // AbsenceEleveSaisie
