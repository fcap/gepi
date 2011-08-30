<?php



/**
 * Skeleton subclass for representing a row from the 'a_traitements' table.
 *
 * Un traitement peut gerer plusieurs saisies et consiste à definir les motifs/justifications... de ces absences saisies
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.gepi
 */
class AbsenceEleveTraitement extends BaseAbsenceEleveTraitement {

	/**
	 * @string to store description
	 */
	protected $description;

	/**
	 *
	 * Renvoi une description intelligible du traitement
	 *
	 * @return     String description
	 *
	 */
	public function getDescription() {
	    if (!isset($description) || $description === null) {
		$desc = 'n� '.$this->getId();
		$desc .= ' cr�� le ';
		$desc .= strftime("%a %d/%m/%Y", $this->getUpdatedAt('U'));
		$eleve_col = new PropelCollection();
		foreach ($this->getAbsenceEleveSaisies() as $abs_saisie) {
		    if ($abs_saisie->getEleve() != null) {
			$eleve_col->add($abs_saisie->getEleve());
		    }
		}
		foreach ($eleve_col as $eleve) {
		    if ($eleve_col->isFirst()) {
			$desc .= '; ';
		    }
		    $desc .= $eleve->getNom().' '.$eleve->getPrenom();
		    if (!$eleve_col->isLast()) {
			$desc .= ', ';
		    }
		}
		if ($this->getAbsenceEleveType() != null) {
		    $desc .= "; type : ".$this->getAbsenceEleveType()->getNom();
		}
		if ($this->getAbsenceEleveMotif() != null) {
		    $desc .= "; motif : ".$this->getAbsenceEleveMotif()->getNom();
		}
		if ($this->getAbsenceEleveJustification() != null) {
		    $desc .= "; justification : ".$this->getAbsenceEleveJustification()->getNom();
		}
		$notif = false;
		foreach ($this->getAbsenceEleveNotifications() as $notification) {
		    if ($notification->getStatutEnvoi() == AbsenceEleveNotificationPeer::STATUT_ENVOI_SUCCES
			    || $notification->getStatutEnvoi() == AbsenceEleveNotificationPeer::STATUT_ENVOI_SUCCES_AVEC_ACCUSE_DE_RECEPTION) {
			$notif = true;
			break;
		    }
		}
		if ($notif) {
		    $desc .= "; Notifi�";
		}
		if ($this->getCommentaire() != null && $this->getCommentaire() != '') {
		    $desc .= "; Commentaire : ".$this->getCommentaire();
		}
		$description = $desc;
	    }
	    return $description;
	}

	public function isTypeHydrated() {
	    if ($this->a_type_id !== null && $this->aAbsenceEleveType === null) {
		return 'non';
	    }
	    return 'oui';
	}

	public function isNotificationHydrated() {
	    if ($this->collAbsenceEleveNotifications !== null) {
		return 'oui';
	    }
	    return 'non';
	}

	public function isJustificationHydrated() {
	    if ($this->a_justification_id !== null && $this->aAbsenceEleveJustification === null) {
		return 'non';
	    }
	    return 'oui';
	}

	/**
	 *
	 * Renvoi true / false suivant que le traitement est modifiable ou pas
	 *
	 * @return     String description
	 *
	 */
	public function getModifiable() {

	    //modifiable uniquement si aucune notifications n'a �t� envoy�
	    foreach ($this->getAbsenceEleveNotifications() as $notification) {
		if ($notification->getStatutEnvoi() != AbsenceEleveNotificationPeer::STATUT_ENVOI_ETAT_INITIAL) {
		    return false;
		}
	    }
	    return true;
	}

	/**
	 *
	 * Renvoi la liste de tout les responsables l�gaux des saisies associees a ce traitement
	 *
	 * @return     PropelObjectCollection collection d'objets de la classe ResponsableInformation
	 *
	 */
	public function getResponsablesInformationsSaisies() {
	    $resp_col = new PropelObjectCollection();
	    $resp_col->setModel('ResponsableInformation');
	    foreach ($this->getAbsenceEleveSaisies() as $saisie) {
		$eleve = $saisie->getEleve();
		if ($eleve!= null) {
		    foreach ($eleve->getResponsableInformations() as $responsable_information) {
			$resp_col->add($responsable_information);
		    }
		}
	    }
	    return $resp_col;
	}

	/**
	 *
	 *
	 * Renvoi true ou false si l'eleve est en manque de ses obligation de presence
	 * une saisie qui n'est pas un manquement ne sera pas comptee dans le bulletin
	 * une saisie qui est un manquement sera comptee dans le bulletin
	 * Cette propri�t� est calcul� avec par l'intermediaire des types de traitement
	 * si on a un type de manquement specifie a non_precise (comme le type 'erreur de saisie'),
	 * on renvoi un non manquement (sinon l'utilisateur aurait specifier un type MANQU_OBLIG_PRESE_VRAI)
	 *
	 * @return     boolean
	 *
	 */
	public function getManquementObligationPresence() {
	    if ($this->getAbsenceEleveType() == null) {
		return (getSettingValue("abs2_saisie_par_defaut_sans_manquement")!='y');
	    } else {
		return (
			$this->getAbsenceEleveType()->getManquementObligationPresence() == AbsenceEleveType::MANQU_OBLIG_PRESE_VRAI);
	    }
	}

	/**
	 *
	 * Renvoi true ou false si l'eleve etait sous la responsabilite de l'etablissement (infirmerie ou autre)
	 * une saisie qui n'est pas sous la responsabilite de l'etablissement sere comptee dans le bulletin
	 * une saisie qui est sous la responsabilite de l'etablissement ne sera pas comptee dans le bulletin
	 * si on a un type de responsabilite specifi� a non_precis� (comme le type 'erreur de saisie'),
	 * on renvoi une resp etab a vrai (sinon l'utilisateur aurait specifier un type MANQU_OBLIG_PRESE_VRAI)
	 * @return     boolean
	 *
	 */
	public function getSousResponsabiliteEtablissement() {
	    if ($this->getAbsenceEleveType() == null) {
		return (getSettingValue("abs2_saisie_par_defaut_sous_responsabilite_etab")!='y');
	    } else {
		return (
			$this->getAbsenceEleveType()->getSousResponsabiliteEtablissement() == AbsenceEleveType::SOUS_RESP_ETAB_NON_PRECISE
			|| $this->getAbsenceEleveType()->getSousResponsabiliteEtablissement() == AbsenceEleveType::SOUS_RESP_ETAB_VRAI);
	    }
	}


	/**
	 * Ajout manuel : renseignement automatique de l'utilisateur qui a cr�� ou modifi� la saisie
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isNew()) {
			if ($this->getUtilisateurId() == null) {
				$utilisateur = UtilisateurProfessionnelPeer::getUtilisateursSessionEnCours();
				if ($utilisateur != null) {
					$this->setUtilisateurProfessionnel($utilisateur);
				}
			}
		} else {
			$utilisateur = UtilisateurProfessionnelPeer::getUtilisateursSessionEnCours();
			if ($utilisateur != null) {
				$this->setModifieParUtilisateur($utilisateur);
			}
		}
	    $result = parent::save($con);
	    
	    
	    return $result;
	}
	
	/**
	 * Removes this object from datastore and sets delete attribute. Custom : suppression des notifications et jointures associ�es et calcul de la table d'agr�gation
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		$saisieColOld = $this->getAbsenceEleveSaisies();
		AbsenceEleveNotificationQuery::create()->filterByAbsenceEleveTraitement($this)->delete();
		//JTraitementSaisieEleveQuery::create()->filterByAbsenceEleveTraitement($this)->delete(); //ne pas supprimer pour pourvoir faire la jointure entre le traitement supprim� et l'�l�ve saisi
		parent::delete();
	}
	
	/**
	 * Met � jour la table d'agr�gation pour toutes les saisies de ce traitement
	 *
	 * @return     void
	 */
	public function updateAgregationTable() {
		foreach($this->getAbsenceEleveSaisies() as $saisie) {
			if ($saisie->getEleve() != null) {
				$saisie->getEleve()->updateAbsenceAgregationTable($saisie->getDebutAbs(null),$saisie->getFinAbs(null));
				$saisie->getEleve()->checkAndUpdateSynchroAbsenceAgregationTable($saisie->getDebutAbs(null),$saisie->getFinAbs(null));
			}
		}
	}
	
	public function getAlreadyInSave() {
		return $this->alreadyInSave;
	}
} // AbsenceEleveTraitement
