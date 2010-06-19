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

//r�cup�ration des param�tres de la requ�te
$id_traitement = isset($_POST["id_traitement"]) ? $_POST["id_traitement"] :(isset($_GET["id_traitement"]) ? $_GET["id_traitement"] :NULL);
$modif = isset($_POST["modif"]) ? $_POST["modif"] :(isset($_GET["modif"]) ? $_GET["modif"] :null);

$message_enregistrement = '';
$traitement = AbsenceEleveTraitementQuery::create()->findPk($id_traitement);
if ($traitement == null) {
    $message_enregistrement .= 'Modification impossible : traitement non trouv�e.';
    include("visu_traitement.php");
    die();
}

if ($modif == 'type') {
    if (!$traitement->getAbsenceEleveEnvois()->isEmpty()) {
	$message_enregistrement .= 'Modification impossible : courriers d�j� envoy�s.';
	include("visu_traitement.php");
	die();
    } else {
	$traitement->setAbsenceEleveType(AbsenceEleveTypeQuery::create()->findPk($_POST["id_type"]));
    }
} elseif ($modif == 'commentaire') {
    $traitement->setCommentaire($_POST["commentaire"]);
} elseif ($modif == 'justification') {
    $traitement->setAbsenceEleveJustification(AbsenceEleveJustificationQuery::create()->findPk($_POST["id_justification"]));
} elseif ($modif == 'motif') {
    $traitement->setAbsenceEleveMotif(AbsenceEleveMotifQuery::create()->findPk($_POST["id_motif"]));
}

if ($traitement->validate()) {
    $traitement->save();
    $message_enregistrement .= 'Modification enregistr�e';
} else {
    $no_br = true;
    foreach ($traitement->getValidationFailures() as $erreurs) {
	$message_enregistrement .= $erreurs;
	if ($no_br) {
	    $no_br = false;
	} else {
	    $message_enregistrement .= '<br/>';
	}
    }
    $traitement->reload();
}

include("visu_traitement.php");
?>