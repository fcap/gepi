<?php

/*
* $Id$
*
* Copyright 2001, 2011 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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
// Begin standart header
$niveau_arbo = 1;

// Initialisations files
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

// SQL : INSERT INTO droits VALUES ( '/mod_discipline/visu_disc.php', 'F', 'F', 'F', 'F', 'V', 'V', 'F', 'F', 'Discipline: Acc�s �l�ve/parent', '');
if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}

if(strtolower(substr(getSettingValue('active_mod_discipline'),0,1))!='y') {
	$mess=rawurlencode("Vous tentez d acc�der au module Discipline qui est d�sactiv� !");
	tentative_intrusion(1, "Tentative d'acc�s au module Discipline qui est d�sactiv�.");
	header("Location: ../accueil.php?msg=$mess");
	die();
}

//**************** EN-TETE *****************
$titre_page = "Discipline : Acc�s ".$_SESSION['statut'];
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

if($_SESSION['statut']=='eleve') {
	if(getSettingValue('visuEleDisc')!='yes') {
		echo "<p style='color:red'>Vous n'�tes pas autoris� � acc�der � cette page.</p>\n";
		tentative_intrusion(1, "Tentative d'acc�s au module Discipline sans y �tre autoris�.");
		require("../lib/footer.inc.php");
		die();
	}
}
elseif($_SESSION['statut']=='responsable') {
	if(getSettingValue('visuRespDisc')!='yes') {
		echo "<p style='color:red'>Vous n'�tes pas autoris� � acc�der � cette page.</p>\n";
		tentative_intrusion(1, "Tentative d'acc�s au module Discipline sans y �tre autoris�.");
		require("../lib/footer.inc.php");
		die();
	}
}

if($_SESSION['statut']=='eleve') {
	$ele_login=$_SESSION['login'];
}
else {
	// Lien de choix de l'�l�ve
	$ele_login=isset($_GET['ele_login']) ? $_GET['ele_login'] : NULL;

	$tab_ele_login=array();
	$tab_enfants=get_enfants_from_resp_login($_SESSION['login'],'avec_classe');
	for($i=0;$i<count($tab_enfants);$i+=2) {
		//echo "\$tab_enfants[$i]=".$tab_enfants[$i]."<br />";
		$tab_ele_login[]=$tab_enfants[$i];
	}

	if((isset($ele_login))&&(!in_array($ele_login,$tab_ele_login))) {
		echo "<p style='color:red'>Tentative d'acc�s au module Discipline pour un �l�ve dont vous n'�tes pas responsable.</p>\n";
		tentative_intrusion(1, "Tentative d'acc�s au module Discipline pour un �l�ve dont il n'est pas responsable : $ele_login");
		unset($ele_login);
	}

	if(!isset($ele_login)) {
		if(count($tab_ele_login)==1) {
			$ele_login=$tab_ele_login[0];
		}
		else {
			for($i=0;$i<count($tab_enfants);$i+=2) {
				echo "<a href='".$_SERVER['PHP_SELF']."?".$tab_enfants[$i]."'>".$tab_enfants[$i+1]."</a><br />\n";
			}

			require("../lib/footer.inc.php");
			die();
		}
	}

}

require_once("../mod_discipline/sanctions_func_lib.php");

$mode="";
$date_debut="";
$date_fin="";
//echo "<p>Tableau des incidents</p>\n";
echo tab_mod_discipline($ele_login,$mode,$date_debut,$date_fin);

require("../lib/footer.inc.php");

?>
