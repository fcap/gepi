<?php
/**
 *
 * @version $Id$
 *
 * Copyright 2001, 2007 Thomas Belliard, Laurent Delineau, Eric Lebrun, Stephane Boireau, Julien Jocal
 *
 * This file is part of GEPI.
 *
 * GEPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GEPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GEPI; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

// Initialisation des feuilles de style apr�s modification pour am�liorer l'accessibilit�
$accessibilite="y";

// Initialisations files
require_once("../lib/initialisationsPropel.inc.php");
require_once("../lib/initialisations.inc.php");
// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
    header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
    die();
} else if ($resultat_session == '0') {
    header("Location: ../logout.php?auto=1");
    die();
}

if (!checkAccess()) {
    header("Location: ../logout.php?auto=1");
    die();
}

//recherche de l'utilisateur avec propel
$utilisateur = UtilisateurProfessionnelPeer::getUtilisateursSessionEnCours();
if ($utilisateur == null) {
	header("Location: ../logout.php?auto=1");
	die();
}

//On v�rifie si le module est activ�
if (getSettingValue("active_module_absence")!='2') {
    die("Le module n'est pas activ�.");
}

if ($utilisateur->getStatut()=="professeur" &&  getSettingValue("active_module_absence_professeur")!='y') {
    die("Le module n'est pas activ�.");
}

//r�cup�ration des param�tres de la requ�te
$id_saisie = isset($_POST["id_saisie"]) ? $_POST["id_saisie"] :(isset($_GET["id_saisie"]) ? $_GET["id_saisie"] :NULL);
$date_debut = isset($_POST["date_debut"]) ? $_POST["date_debut"] :(isset($_GET["date_debut"]) ? $_GET["date_debut"] :NULL);
$date_fin = isset($_POST["date_fin"]) ? $_POST["date_fin"] :(isset($_GET["date_fin"]) ? $_GET["date_fin"] :NULL);
$commentaire = isset($_POST["commentaire"]) ? $_POST["commentaire"] :(isset($_GET["commentaire"]) ? $_GET["commentaire"] :NULL);
$total_traitements = isset($_POST["total_traitements"]) ? $_POST["total_traitements"] :(isset($_GET["total_traitements"]) ? $_GET["total_traitements"] :0);
$ajout_type_absence = isset($_POST["ajout_type_absence"]) ? $_POST["ajout_type_absence"] :(isset($_GET["ajout_type_absence"]) ? $_GET["ajout_type_absence"] :null);

$message_enregistrement = '';
$saisie = AbsenceEleveSaisieQuery::create()->findPk($id_saisie);
if ($saisie == null) {
    $message_enregistrement .= 'Modification impossible : saisie non trouv�e.';
    include("visu_saisie.php");
    die();
}

//la saisie est-elle modifiable ?
//Une saisie est modifiable ssi : elle appartient � l'utilisateur de la session,
//elle date de moins d'une heure et l'option a ete coch� partie admin
if (!getSettingValue("abs2_modification_saisie_une_heure")=='y' || !$saisie->getUtilisateurId() == $utilisateur->getPrimaryKey() || !$saisie->getCreatedAt('U') > (time() - 3600)) {
    $message_enregistrement .= 'Modification non autoris�e.';
    include("visu_saisie.php");
    die();
}

if ($commentaire != null) {
    $saisie->setCommentaire($commentaire);
}

for($i=0; $i<$total_traitements; $i++) {

    //on test si on a un traitement a modifer
    if (!(isset($_POST['id_traitement'][$i]) && $_POST['id_traitement'][$i] != -1) ) {
	$message_enregistrement .= "Probleme avec l'id traitement : ".$_POST['id_traitement'][$i]."<br/>";
	continue;
    }

    //il faut trouver le traitement corespondant � l'id
    $criteria = new Criteria();
    $criteria->add(AbsenceEleveTraitementPeer::ID, $_POST['id_traitement'][$i]);
    $traitement = $saisie->getAbsenceEleveTraitements($criteria);
    if ($traitement->count() != 1) {
	$message_enregistrement .= "Probleme avec l'id traitement : ".$_POST['id_traitement'][$i]."<br/>";
	continue;
    }

    //on test si on a un traitement a modifer
    $type = AbsenceEleveTypeQuery::create()->findPk($_POST['type_traitement'][$i]);
    $traitement->getFirst()->setAbsenceEleveType($type);
    $traitement->getFirst()->save();
}


if ($ajout_type_absence != null && $ajout_type_absence != -1) {
    $type = AbsenceEleveTypeQuery::create()->findPk($ajout_type_absence);
    if ($type != null) {
	if ($type->isStatutAutorise($utilisateur->getStatut())) {
	    //on va creer un traitement avec le type d'absence associ�
	    $traitement = new AbsenceEleveTraitement();
	    $traitement->addAbsenceEleveSaisie($saisie);
	    $traitement->setAbsenceEleveType($type);
	    $traitement->setUtilisateurProfessionnel($utilisateur);
	    $traitement->save();
	} else {
	    $message_enregistrement .= "Type d'absence non autoris� pour ce statut : ".$_POST['type_absence_eleve'][$i]."<br/>";
	}
    } else {
	$message_enregistrement .= "Probleme avec l'id du type d'absence : ".$_POST['type_absence_eleve'][$i]."<br/>";
    }
}

$saisie->save();

$message_enregistrement .= 'Modification enregistr�e';

include("visu_saisie.php");
?>