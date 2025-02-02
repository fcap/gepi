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


// SQL : INSERT INTO droits VALUES ( '/mod_discipline/check_nature_incident.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'F', 'Discipline: Recherche de natures d incident', '');
// maj : $tab_req[] = "INSERT INTO droits VALUES ( '/mod_discipline/check_nature_incident.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'F', 'Discipline: Recherche de natures d incident', '');";
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


header('Content-Type: text/html; charset=ISO-8859-1');

//$chaine_rech=isset($_GET['chaine_rech']) ? $_GET['chaine_rech'] : NULL;
$nature=isset($_POST['nature']) ? $_POST['nature'] : NULL;
$chaine_rech=$nature;

if(isset($chaine_rech)) {
	//check_token();

	// Pour debug:
	// echo "El�ments transmis: $chaine_rech<br />";

	// Filtrage des caract�res
	$chaine_rech=preg_replace("/[^A-Za-z0-9���������������������������� \._-]/","%",$chaine_rech);

	//$chaine_mysql="(";
	$chaine_mysql=" 1 AND (";
	//$tab=explode("_",substr($chaine_rech,1)); // On vire le _ de d�but de chaine
	//$tab=explode("_",preg_replace("/^_/","",$chaine_rech)); // On vire le _ de d�but de chaine
	$tab=explode(" ",$chaine_rech); // On vire le _ de d�but de chaine
	for($i=0;$i<count($tab);$i++) {
		if($tab[$i]!='') {
			if($i>0) {$chaine_mysql.=" OR ";}
			$chaine_mysql.="nature LIKE '%".addslashes($tab[$i])."%'";
		}
	}
	$chaine_mysql.=")";

	$DisciplineNaturesRestreintes=getSettingValue('DisciplineNaturesRestreintes');

	if($DisciplineNaturesRestreintes!=1) {
		$sql="SELECT DISTINCT nature FROM s_incidents WHERE $chaine_mysql ORDER BY nature;";
	}
	else {
		$sql="SELECT DISTINCT nature FROM s_natures WHERE $chaine_mysql ORDER BY nature;";
	}
	//echo "$sql<br />";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		echo "<ul>";
		$alt=1;
		while($lig=mysql_fetch_object($res)) {
			$alt=$alt*(-1);

			//echo "<div class='lig$alt white_hover'><a href='#' onclick=\"document.getElementById('nature').value='".addslashes(ucfirst($lig->nature))."';cacher_div('div_choix_nature2');document.getElementById('nature').focus();return false;\">".ucfirst($lig->nature)."</a></div>";

			echo '<li><a href="#" onclick="return false">'.$lig->nature.'</a></li>';
		}
		echo "</ul>";
	}
	else {
		echo "Aucun incident de m�me nature n'a �t� trouv�.";
	}
}
else {
	echo "Aucun �l�ment pour la recherche d'incident.";
}
?>