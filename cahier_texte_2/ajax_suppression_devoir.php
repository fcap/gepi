<?php
/*
 * $Id: index.php 2356 2008-09-05 14:02:27Z jjocal $
 *
 * Copyright 2001, 2007 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Gabriel Fischer
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


// On désamorce une tentative de contournement du traitement anti-injection lorsque register_globals=on
if (isset($_GET['traite_anti_inject']) OR isset($_POST['traite_anti_inject'])) $traite_anti_inject = "yes";
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

//On vérifie si le module est activé
if (getSettingValue("active_cahiers_texte")!='y') {
    die("Le module n'est pas activé.");
}

//récupération de la notice
$id_devoir = isset($_POST["id_devoir"]) ? $_POST["id_devoir"] :(isset($_GET["id_devoir"]) ? $_GET["id_devoir"] :NULL);
$ctTravailAFaire = CtTravailAFairePeer::retrieveByPK($id_devoir);


//si pas de notice précisé, erreur du script
if ($ctTravailAFaire == null) {
  echo("Erreur : pas de devoir trouv�.");
  die();
}

// Vérification : est-ce que l'utilisateur a le droit de supprimer cette entr� ?
$utilisateur = $_SESSION['utilisateur'];
if ($utilisateur == null) {
	header("Location: ../logout.php?auto=1");
    die();
}
if ($ctTravailAFaire->getIdLogin() != $utilisateur->getLogin()) {
  echo("Erreur : vous n'avez pas le droit de modifier cette notice.");
  die();
}

//suppression des documents joints
foreach ($ctTravailAFaire->getCtDevoirDocuments() as $documents) {
	$documents->delete();
}

$ctTravailAFaire->delete();
$utilisateur->clearAllReferences();
?>