<?php
/* $Id$ */
/*
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

//======================================================================================

$sql="SELECT 1=1 FROM droits WHERE id='/mod_genese_classes/select_eleves_options.php';";
$test=mysql_query($sql);
if(mysql_num_rows($test)==0) {
$sql="INSERT INTO droits SET id='/mod_genese_classes/select_eleves_options.php',
administrateur='V',
professeur='F',
cpe='F',
scolarite='F',
eleve='F',
responsable='F',
secours='F',
autre='F',
description='G�n�se des classes: Choix des options des �l�ves',
statut='';";
$insert=mysql_query($sql);
}

//======================================================================================
// Section checkAccess() � d�commenter en prenant soin d'ajouter le droit correspondant:
if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}
//======================================================================================

$projet=isset($_POST['projet']) ? $_POST['projet'] : (isset($_GET['projet']) ? $_GET['projet'] : NULL);


if(isset($_POST['is_posted'])) {
	//echo "";
	$eleve=isset($_POST['eleve']) ? $_POST['eleve'] : array();
	$id_classe_actuelle_eleve=isset($_POST['id_classe_actuelle_eleve']) ? $_POST['id_classe_actuelle_eleve'] : array();
	$moy=isset($_POST['moy']) ? $_POST['moy'] : array();
	$nb_absences=isset($_POST['nb_absences']) ? $_POST['nb_absences'] : array();
	$non_justifie=isset($_POST['non_justifie']) ? $_POST['non_justifie'] : array();
	$nb_retards=isset($_POST['nb_retards']) ? $_POST['nb_retards'] : array();

	if(count($eleve)>0) {

		$classe_fut=isset($_POST['classe_fut']) ? $_POST['classe_fut'] : array();
		$lv1=isset($_POST['lv1']) ? $_POST['lv1'] : array();
		$lv2=isset($_POST['lv2']) ? $_POST['lv2'] : array();
		$lv3=isset($_POST['lv3']) ? $_POST['lv3'] : array();
		$profil=isset($_POST['profil']) ? $_POST['profil'] : array();

		$sql="SELECT * FROM gc_options WHERE projet='$projet' AND type='autre' ORDER BY opt;";
		$res=mysql_query($sql);
		$nb_autre_opt=mysql_num_rows($res);

		$nb_reg=0;
		$nb_err=0;
		for($i=0;$i<count($eleve);$i++) {
			$liste_option_ele_courant="|";

			if(!isset($classe_fut[$i])) {$classe_fut[$i]="";}

			if((isset($lv1[$i]))&&($lv1[$i]!='')) {
				$liste_option_ele_courant.=$lv1[$i]."|";
			}

			if((isset($lv2[$i]))&&($lv2[$i]!='')) {
				$liste_option_ele_courant.=$lv2[$i]."|";
			}

			if((isset($lv3[$i]))&&($lv3[$i]!='')) {
				$liste_option_ele_courant.=$lv3[$i]."|";
			}

			for($j=0;$j<$nb_autre_opt;$j++) {
				$tmp_autre_opt=isset($_POST['autre_opt_'.$j]) ? $_POST['autre_opt_'.$j] : array();
				if((isset($tmp_autre_opt[$i]))&&($tmp_autre_opt[$i]!="")) {
					$liste_option_ele_courant.=$tmp_autre_opt[$i]."|";
				}
			}

			$sql="DELETE FROM gc_eleves_options WHERE login='$eleve[$i]' AND projet='$projet';";
			//echo "<p>$sql<br />\n";
			if($del=mysql_query($sql)) {
				/*
				echo "login='$eleve[$i]'<br />";
				echo "id_classe_actuelle='$id_classe_actuelle_eleve[$i]'<br />";
				echo "classe_future='$classe_fut[$i]'<br />";
				echo "liste_opt='$liste_option_ele_courant'<br />";
				echo "moy='$moy[$i]'<br />";
				echo "nb_absences='$nb_absences[$i]'<br />";
				echo "non_justifie='$non_justifie[$i]'<br />";
				echo "nb_retards='$nb_retards[$i]'<br />";
				echo "projet='$projet'<br />";
				echo "profil='$profil[$i]'<br />";
				*/
				$sql="INSERT INTO gc_eleves_options SET login='$eleve[$i]', id_classe_actuelle='$id_classe_actuelle_eleve[$i]', classe_future='$classe_fut[$i]', liste_opt='$liste_option_ele_courant', moy='$moy[$i]', nb_absences='$nb_absences[$i]', non_justifie='$non_justifie[$i]', nb_retards='$nb_retards[$i]', projet='$projet', profil='$profil[$i]';";
				//echo "$sql<br />\n";
				if($insert=mysql_query($sql)) {$nb_reg++;} else {$nb_err++;}
			}
			else {$nb_err++;}
		}
	}
/*
$_POST['colorisation']=	lv1
$_POST['eleve']=	Array (*)
$_POST['eleve'][0]=	BASSIER_F
$_POST['eleve'][1]=	CAHU_H
$_POST['eleve'][2]=	CONSTAN_J
$_POST['eleve'][3]=	CUDORGE_M
$_POST['eleve'][4]=	DOSSIN_R
$_POST['eleve'][5]=	FOULON_A
...
$_POST['eleve'][94]=	REUX_G
$_POST['eleve'][95]=	ROHAIS_B
$_POST['eleve'][96]=	ROSE_L
$_POST['classe_fut']=	Array (*)
$_POST['classe_fut'][0]=	Dep
$_POST['classe_fut'][1]=	Dep
$_POST['classe_fut'][2]=	Dep
...
$_POST['classe_fut'][94]=	
$_POST['classe_fut'][95]=	
$_POST['classe_fut'][96]=	
$_POST['lv1']=	Array (*)
$_POST['lv1'][0]=	ALL1
$_POST['lv1'][1]=	ALL1
...
$_POST['lv1'][94]=	AGL1
$_POST['lv1'][95]=	AGL1
$_POST['lv1'][96]=	ALL1
$_POST['lv2']=	Array (*)
$_POST['lv2'][0]=	ALL2
$_POST['lv2'][1]=	ALL2
...
$_POST['lv2'][94]=	ESP2
$_POST['lv2'][95]=	ALL2
$_POST['lv2'][96]=	AGL2

$_POST['choix_options_23']=	Valider

$_POST['autre_opt_0']=	Array (*)
$_POST['autre_opt_0'][22]=	ATHLE
$_POST['autre_opt_0'][24]=	ATHLE
$_POST['autre_opt_0'][25]=	ATHLE
$_POST['autre_opt_0'][28]=	ATHLE
$_POST['autre_opt_0'][30]=	ATHLE
$_POST['autre_opt_0'][32]=	ATHLE
$_POST['autre_opt_0'][38]=	ATHLE
$_POST['autre_opt_0'][39]=	ATHLE
$_POST['autre_opt_0'][40]=	ATHLE
$_POST['autre_opt_0'][41]=	ATHLE
$_POST['autre_opt_0'][42]=	ATHLE
$_POST['autre_opt_0'][43]=	ATHLE
$_POST['autre_opt_0'][44]=	ATHLE
$_POST['autre_opt_0'][45]=	ATHLE
$_POST['autre_opt_3']=	Array (*)
*/

}

$themessage  = 'Des informations ont �t� modifi�es. Voulez-vous vraiment quitter sans enregistrer ?';
//**************** EN-TETE *****************
$titre_page = "G�n�se classe: Choix options �l�ves";
//echo "<div class='noprint'>\n";
require_once("../lib/header.inc");
//echo "</div>\n";
//**************** FIN EN-TETE *****************

if((!isset($projet))||($projet=="")) {
	echo "<p style='color:red'>ERREUR: Le projet n'est pas choisi.</p>\n";
	require("../lib/footer.inc.php");
	die();
}

//debug_var();

//echo "<div class='noprint'>\n";
echo "<p class='bold'><a href='index.php?projet=$projet'";
echo " onclick=\"return confirm_abandon (this, change, '$themessage')\"";
echo ">Retour</a>";
echo "</p>\n";
//echo "</div>\n";

echo "<h2>Projet $projet</h2>\n";

echo "<p><a href='".$_SERVER['PHP_SELF']."?projet=$projet'";
echo " onclick=\"return confirm_abandon (this, change, '$themessage')\"";
echo ">Rafraichir sans enregistrer</a></p>\n";

//=================================
// R�cup�ration de la liste des classes actuelles et futures et de la liste des options
$classe_fut=array();
$sql="SELECT DISTINCT classe FROM gc_divisions WHERE projet='$projet' AND statut='future' ORDER BY classe;";
$res=mysql_query($sql);
if(mysql_num_rows($res)==0) {
	echo "<p>Aucune classe future n'est encore d�finie pour ce projet.</p>\n";
	// Est-ce que cela doit vraiment bloquer la saisie des options?
	require("../lib/footer.inc.php");
	die();
}
else {
	$tab_opt_exclue=array();

	while($lig=mysql_fetch_object($res)) {
		$classe_fut[]=$lig->classe;

		$tab_opt_exclue["$lig->classe"]=array();
		//=========================
		// Options exlues pour la classe
		$sql="SELECT opt_exclue FROM gc_options_classes WHERE projet='$projet' AND classe_future='$lig->classe';";
		$res_opt_exclues=mysql_query($sql);
		while($lig_opt_exclue=mysql_fetch_object($res_opt_exclues)) {
			$tab_opt_exclue["$lig->classe"][]=$lig_opt_exclue->opt_exclue;
		}
		//=========================
	}
	$classe_fut[]="Red";
	$classe_fut[]="Dep";
	$classe_fut[]=""; // Vide pour les Non Affect�s
}

$id_classe_actuelle=array();
$classe_actuelle=array();
$sql="SELECT DISTINCT id_classe,classe FROM gc_divisions WHERE projet='$projet' AND statut='actuelle' ORDER BY classe;";
$res=mysql_query($sql);
if(mysql_num_rows($res)==0) {
	echo "<p>Aucune classe actuelle n'est encore s�lectionn�e pour ce projet.</p>\n";
	require("../lib/footer.inc.php");
	die();
}
else {
	while($lig=mysql_fetch_object($res)) {
		$id_classe_actuelle[]=$lig->id_classe;
		$classe_actuelle[]=$lig->classe;
	}

	// On ajoute redoublants et arrivants
	$id_classe_actuelle[]='Red';
	$classe_actuelle[]='Red';

	$id_classe_actuelle[]='Arriv';
	$classe_actuelle[]='Arriv';
}

$lv1=array();
$sql="SELECT * FROM gc_options WHERE projet='$projet' AND type='lv1' ORDER BY opt;";
$res=mysql_query($sql);
if(mysql_num_rows($res)>0) {
	while($lig=mysql_fetch_object($res)) {
		$lv1[]=$lig->opt;
	}
}


$lv2=array();
$sql="SELECT * FROM gc_options WHERE projet='$projet' AND type='lv2' ORDER BY opt;";
$res=mysql_query($sql);
if(mysql_num_rows($res)>0) {
	while($lig=mysql_fetch_object($res)) {
		$lv2[]=$lig->opt;
	}
}

$lv3=array();
$sql="SELECT * FROM gc_options WHERE projet='$projet' AND type='lv3' ORDER BY opt;";
$res=mysql_query($sql);
if(mysql_num_rows($res)>0) {
	while($lig=mysql_fetch_object($res)) {
		$lv3[]=$lig->opt;
	}
}

$autre_opt=array();
$sql="SELECT * FROM gc_options WHERE projet='$projet' AND type='autre' ORDER BY opt;";
$res=mysql_query($sql);
if(mysql_num_rows($res)>0) {
	while($lig=mysql_fetch_object($res)) {
		$autre_opt[]=$lig->opt;
	}
}
//=================================

//=============================
include("lib_gc.php");
// On y initialise les couleurs
// Il faut que le tableaux $classe_fut soit initialis�.
//=============================

//=========================================
necessaire_bull_simple();
//=========================================

echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";

// Colorisation
echo "<p>Colorisation&nbsp;: ";
echo "<select name='colorisation' onchange='lance_colorisation()'>
<option value='classe_fut' selected>Classe future</option>
<option value='lv1'>LV1</option>
<option value='lv2'>LV2</option>
<option value='profil'>Profil</option>
</select>\n";

echo "</p>\n";

echo "<p align='center'><input type='submit' name='choix_options_first' value='Valider' /></p>\n";

$eff_tot=0;
$eff_tot_M=0;
$eff_tot_F=0;

// Nombre max de p�riodes pour faire les requ�tes pour les redoublants dont la classe n'est pas "connue"
$max_nb_per=0;

$chaine_id_classe="";
$cpt=0;
// Boucle sur les classes actuelles
for($j=0;$j<count($id_classe_actuelle);$j++) {

	$nb_per_classe=0;
	$sql="SELECT MAX(num_periode) AS maxper FROM periodes WHERE id_classe='$id_classe_actuelle[$j]';";
	$res_per=mysql_query($sql);
	if(mysql_num_rows($res_per)>0) {
		$lig_per=mysql_fetch_object($res_per);
		$nb_per_classe=$lig_per->maxper;
	}
	//echo "\$nb_per_classe=$nb_per_classe<br />\n";
	if($max_nb_per<$nb_per_classe) {$max_nb_per=$nb_per_classe;}

	$num_eleve1_id_classe_actuelle[$j]=$cpt;
	$eff_tot_classe_M=0;
	$eff_tot_classe_F=0;

	if($chaine_id_classe!="") {$chaine_id_classe.=",";}
	$chaine_id_classe.="'$id_classe_actuelle[$j]'";

	echo "<table class='boireaus' summary='Tableau des options'>\n";

	//==========================================
	echo "<tr>\n";
	echo "<th rowspan='2'>El�ve</th>\n";
	echo "<th rowspan='2'>Sexe</th>\n";
	echo "<th rowspan='2'>Classe<br />actuelle</th>\n";
	echo "<th rowspan='2'>Profil</th>\n";
	echo "<th rowspan='2'>Niveau</th>\n";
	echo "<th rowspan='2'>Absences Non.Just Retards</th>\n";

	//if(count($classe_fut)>0) {echo "<th colspan='".(count($classe_fut)+2)."'>Classes futures</th>\n";}
	if(count($classe_fut)>0) {echo "<th colspan='".count($classe_fut)."'>Classes futures</th>\n";}
	if(count($lv1)>0) {echo "<th colspan='".count($lv1)."'>LV1</th>\n";}
	if(count($lv2)>0) {echo "<th colspan='".count($lv2)."'>LV2</th>\n";}
	if(count($lv3)>0) {echo "<th colspan='".count($lv3)."'>LV3</th>\n";}
	if(count($autre_opt)>0) {echo "<th colspan='".count($autre_opt)."'>Autres options</th>\n";}
	echo "</tr>\n";
	//==========================================
	echo "<tr>\n";
	for($i=0;$i<count($classe_fut);$i++) {
		echo "<th>$classe_fut[$i]</th>\n";
	}
	/*
	echo "<th>Red</th>\n";
	echo "<th>Dep</th>\n";
	echo "<th>Vide</th>\n";
	*/
	for($i=0;$i<count($lv1);$i++) {
		echo "<th>$lv1[$i]</th>\n";
	}
	for($i=0;$i<count($lv2);$i++) {
		echo "<th>$lv2[$i]</th>\n";
	}
	for($i=0;$i<count($lv3);$i++) {
		echo "<th>$lv3[$i]</th>\n";
	}
	for($i=0;$i<count($autre_opt);$i++) {
		echo "<th>$autre_opt[$i]</th>\n";
	}
	echo "</tr>\n";
	//==========================================

	$num_per2=-1;
	if(($id_classe_actuelle[$j]!='Red')&&($id_classe_actuelle[$j]!='Arriv')) {
		$sql="SELECT DISTINCT e.* FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='$id_classe_actuelle[$j]' ORDER BY e.nom,e.prenom;";
		
		$sql_per="SELECT num_periode FROM periodes WHERE id_classe='$id_classe_actuelle[$j]' ORDER BY num_periode DESC LIMIT 1;";
		$res_per=mysql_query($sql_per);
		if(mysql_num_rows($res_per)>0) {
			$lig_per=mysql_fetch_object($res_per);
			$num_per2=$lig_per->num_periode;
		}
	}
	else {
		$sql="SELECT DISTINCT e.* FROM eleves e, gc_ele_arriv_red gc WHERE gc.login=e.login AND gc.statut='$id_classe_actuelle[$j]' AND gc.projet='$projet' ORDER BY e.nom,e.prenom;";
	}
	/*
	echo "<tr>\n";
	echo "<td colspan='19'>$sql</td>\n";
	echo "</tr>\n";
	*/
	$res=mysql_query($sql);
	$eff_tot_classe=mysql_num_rows($res);
	$eff_tot+=$eff_tot_classe;
	//==========================================
	echo "<tr>\n";
	echo "<th>Effectifs&nbsp;: <span id='eff_tot_".$id_classe_actuelle[$j]."'>&nbsp;</span></th>\n";
	echo "<th id='eff_tot_sexe_".$id_classe_actuelle[$j]."'>...</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	for($i=0;$i<count($classe_fut);$i++) {
		echo "<th id='eff_col_".$id_classe_actuelle[$j]."_classe_fut_".$i."'>...</th>\n";
	}
	/*
	echo "<th>...</th>\n";
	echo "<th>...</th>\n";
	echo "<th>...</th>\n";
	*/
	for($i=0;$i<count($lv1);$i++) {
		echo "<th id='eff_col_".$id_classe_actuelle[$j]."_lv1_".$i."'>...</th>\n";
	}
	for($i=0;$i<count($lv2);$i++) {
		echo "<th id='eff_col_".$id_classe_actuelle[$j]."_lv2_".$i."'>...</th>\n";
	}
	for($i=0;$i<count($lv3);$i++) {
		echo "<th id='eff_col_".$id_classe_actuelle[$j]."_lv3_".$i."'>...</th>\n";
	}
	for($i=0;$i<count($autre_opt);$i++) {
		echo "<th id='eff_col_".$id_classe_actuelle[$j]."_autre_opt_".$i."'>...</th>\n";
	}
	echo "</tr>\n";
	//==========================================
	echo "<tr>\n";
	echo "<th>Effectifs&nbsp;: $eff_tot_classe</th>\n";
	echo "<th id='eff_tot_classe_sexe_".$id_classe_actuelle[$j]."'>...</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	for($i=0;$i<count($classe_fut);$i++) {
		echo "<th id='eff_col_sexe_".$id_classe_actuelle[$j]."_classe_fut_".$i."'>...</th>\n";
	}
	/*
	echo "<th>...</th>\n";
	echo "<th>...</th>\n";
	echo "<th>...</th>\n";
	*/
	for($i=0;$i<count($lv1);$i++) {
		echo "<th id='eff_col_sexe_".$id_classe_actuelle[$j]."_lv1_".$i."'>...</th>\n";
	}
	for($i=0;$i<count($lv2);$i++) {
		echo "<th id='eff_col_sexe_".$id_classe_actuelle[$j]."_lv2_".$i."'>...</th>\n";
	}
	for($i=0;$i<count($lv3);$i++) {
		echo "<th id='eff_col_sexe_".$id_classe_actuelle[$j]."_lv3_".$i."'>...</th>\n";
	}
	for($i=0;$i<count($autre_opt);$i++) {
		echo "<th id='eff_col_sexe_".$id_classe_actuelle[$j]."_autre_opt_".$i."'>...</th>\n";
	}
	echo "</tr>\n";
	//==========================================
	// Effectifs utiles: sans les d�parts/red
	echo "<tr>\n";
	echo "<th>Eff.util&nbsp;: <span id='eff_ut_tot_".$id_classe_actuelle[$j]."'>&nbsp;</span></th>\n";
	echo "<th id='eff_ut_tot_sexe_".$id_classe_actuelle[$j]."'>...</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	for($i=0;$i<count($classe_fut);$i++) {
		echo "<th id='eff_ut_col_".$id_classe_actuelle[$j]."_classe_fut_".$i."'>...</th>\n";
	}
	/*
	echo "<th>...</th>\n";
	echo "<th>...</th>\n";
	echo "<th>...</th>\n";
	*/
	for($i=0;$i<count($lv1);$i++) {
		echo "<th id='eff_ut_col_".$id_classe_actuelle[$j]."_lv1_".$i."'>...</th>\n";
	}
	for($i=0;$i<count($lv2);$i++) {
		echo "<th id='eff_ut_col_".$id_classe_actuelle[$j]."_lv2_".$i."'>...</th>\n";
	}
	for($i=0;$i<count($lv3);$i++) {
		echo "<th id='eff_ut_col_".$id_classe_actuelle[$j]."_lv3_".$i."'>...</th>\n";
	}
	for($i=0;$i<count($autre_opt);$i++) {
		echo "<th id='eff_ut_col_".$id_classe_actuelle[$j]."_autre_opt_".$i."'>...</th>\n";
	}
	echo "</tr>\n";
	//==========================================
	echo "<tr>\n";
	echo "<th>Coches</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	echo "<th>&nbsp;</th>\n";
	for($i=0;$i<count($classe_fut);$i++) {
		echo "<th>\n";
		echo "<a href=\"javascript:modif_colonne('classe_fut_$i',$j,true);changement();\"><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a>";
		echo "</th>\n";
	}
	/*
	echo "<th>...</th>\n";
	echo "<th>...</th>\n";
	echo "<th>...</th>\n";
	*/
	for($i=0;$i<count($lv1);$i++) {
		echo "<th>\n";
		echo "<a href=\"javascript:modif_colonne('lv1_$i',$j,true);changement();\"><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a>";
		echo "</th>\n";
	}
	for($i=0;$i<count($lv2);$i++) {
		echo "<th>\n";
		echo "<a href=\"javascript:modif_colonne('lv2_$i',$j,true);changement();\"><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a>";
		echo "</th>\n";
	}
	for($i=0;$i<count($lv3);$i++) {
		echo "<th>\n";
		echo "<a href=\"javascript:modif_colonne('lv3_$i',$j,true);changement();\"><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a>";
		echo "</th>\n";
	}
	for($i=0;$i<count($autre_opt);$i++) {
		echo "<th>\n";
		echo "<a href=\"javascript:modif_colonne('autre_opt_$i',$j,true);changement();\"><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a>";
		echo " / <a href=\"javascript:modif_colonne('autre_opt_$i',$j,false);changement();\"><img src='../images/disabled.png' width='15' height='15' alt='Tout d�cocher' /></a>";
		echo "</th>\n";
	}
	echo "</tr>\n";
	//==========================================

	//$sql="SELECT DISTINCT e.* FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='$id_classe_actuelle[$j]' ORDER BY e.nom,e.prenom;";
	//$res=mysql_query($sql);

	// initialisation:
	$num_eleve2_id_classe_actuelle[$j]=-1;

	if(mysql_num_rows($res)>0) {
		while($lig=mysql_fetch_object($res)) {
			$num_eleve2_id_classe_actuelle[$j]=$cpt;
			if(strtoupper($lig->sexe)=='F') {$eff_tot_classe_F++;$eff_tot_F++;} else {$eff_tot_classe_M++;$eff_tot_M++;}

			echo "<tr id='tr_eleve_$cpt' class='white_hover'>\n";
			echo "<td>\n";
			echo "<a name='eleve$cpt'></a>\n";
			//if(file_exists("../photos/eleves/".$lig->elenoet.".jpg")) {
			if(nom_photo($lig->elenoet)) {
				echo "<a href='#eleve$cpt' onclick=\"affiche_photo('".nom_photo($lig->elenoet)."','".addslashes(strtoupper($lig->nom)." ".ucfirst(strtolower($lig->prenom)))."');afficher_div('div_photo','y',100,100);return false;\">";
				echo strtoupper($lig->nom)." ".ucfirst(strtolower($lig->prenom));
				echo "</a>\n";
			}
			else {
				echo strtoupper($lig->nom)." ".ucfirst(strtolower($lig->prenom));
			}
			//echo "<input type='hidden' name='eleve[$cpt]' value='$lig->login' />\n";
			echo "<input type='hidden' name='eleve[$cpt]' id='id_eleve_$cpt' value='$lig->login' />\n";
			echo "<input type='hidden' name='id_classe_actuelle_eleve[$cpt]' value='$id_classe_actuelle[$j]' />\n";
			//echo " $cpt";
			echo "</td>\n";
			//echo "<td id='eleve_sexe_$cpt'>";
			echo "<td>";
			//echo $lig->sexe;
			//echo "<span style='display:none'>".$lig->sexe."</span>";
			echo "<span style='display:none' id='eleve_sexe_$cpt'>".$lig->sexe."</span>";
			echo image_sexe($lig->sexe);
			echo "</td>\n";
			echo "<td>$classe_actuelle[$j]</td>\n";
			//echo "<td>Profil</td>\n";
			echo "<td>\n";
			$sql="SELECT profil FROM gc_eleves_options WHERE projet='$projet' AND login='$lig->login';";
			$res_profil=mysql_query($sql);
			if(mysql_num_rows($res_profil)==0) {
				$profil='RAS';
			}
			else {
				$lig_profil=mysql_fetch_object($res_profil);
				$profil=$lig_profil->profil;
			}

			echo "<input type='hidden' name='profil[$cpt]' id='profil_$cpt' value='$profil' />\n";

			echo "<div id='div_profil_$cpt' onclick=\"affiche_set_profil($cpt);changement();return false;\">$profil</div>\n";

			/*
			echo "<select name='profil[$cpt]'>\n";
			for($loop=0;$loop<count($tab_profil);$loop++) {
				echo "<option value='$tab_profil[$loop]'";
				if($tab_profil[$loop]==$profil) {echo " selected='true'";}
				echo ">$tab_profil[$loop]</option>\n";
			}
			echo "</select>\n";
			*/
			echo "</td>\n";

			//===================================
			echo "<td>\n";
			$sql="SELECT ROUND(AVG(note),1) AS moy FROM matieres_notes WHERE login='$lig->login' AND statut='';";
			$res_note=mysql_query($sql);
			if(mysql_num_rows($res_note)>0) {
				$lig_note=mysql_fetch_object($res_note);

				if($num_per2>0) {
					echo "<a href=\"#\" onclick=\"afficher_div('div_bull_simp','y',-100,40); affiche_bull_simp('$lig->login','".$id_classe_actuelle[$j]."','1','$num_per2');return false;\" style='text-decoration:none;'>";
				}

				if($lig_note->moy<7) {
					echo "<span style='color:red;'>";
				}
				elseif($lig_note->moy<9) {
					echo "<span style='color:orange;'>";
				}
				elseif($lig_note->moy<12) {
					echo "<span style='color:gray;'>";
				}
				elseif($lig_note->moy<15) {
					echo "<span style='color:green;'>";
				}
				else {
					echo "<span style='color:blue;'>";
				}
				if($lig_note->moy!="") {echo "$lig_note->moy\n";} else {echo "&nbsp;\n";}

				if($num_per2>0) {
					echo "</a>\n";
				}

				echo "</span>";
				echo "<input type='hidden' name='moy[$cpt]' value='$lig_note->moy' />\n";
			}
			else {
				echo "-\n";
				echo "<input type='hidden' name='moy[$cpt]' value='-' />\n";
			}
			echo "</td>\n";
			//===================================


			//===================================
			$current_eleve_absences=0;
			$current_eleve_nj=0;
			$current_eleve_retards=0;
			if($nb_per_classe==0) {$nb_per_classe_abs=$max_nb_per;}
			else {$nb_per_classe_abs=$nb_per_classe;}
			for($loop=1;$loop<=$nb_per_classe_abs;$loop++) {
				$sql="SELECT * FROM absences WHERE (login='".$lig->login."' AND periode='$loop');";
				$current_eleve_absences_query=mysql_query($sql);
				if(mysql_num_rows($current_eleve_absences_query)>0) {
					$current_eleve_absences+=@mysql_result($current_eleve_absences_query, 0, "nb_absences");
					$current_eleve_nj+=@mysql_result($current_eleve_absences_query, 0, "non_justifie");
					$current_eleve_retards+=@mysql_result($current_eleve_absences_query, 0, "nb_retards");
				}
				/*
				else {
					$current_eleve_absences="-";
					$current_eleve_nj="-";
					$current_eleve_retards="-";
				}
				*/
			}
			echo "<td>\n";
			//echo "<span style='font-size:small;'>".colorise_abs($current_eleve_absences,$current_eleve_nj,$current_eleve_retards)."</span>";
			echo colorise_abs($current_eleve_absences,$current_eleve_nj,$current_eleve_retards);
			echo "<input type='hidden' name='nb_absences[$cpt]' value='$current_eleve_absences' />\n";
			echo "<input type='hidden' name='non_justifie[$cpt]' value='$current_eleve_nj' />\n";
			echo "<input type='hidden' name='nb_retards[$cpt]' value='$current_eleve_retards' />\n";
			echo "</td>\n";
			//===================================


			$fut_classe="";
			$tab_ele_opt=array();
			$sql="SELECT * FROM gc_eleves_options WHERE projet='$projet' AND login='$lig->login';";
			$res_opt=mysql_query($sql);
			if(mysql_num_rows($res_opt)>0) {
				$lig_opt=mysql_fetch_object($res_opt);

				$fut_classe=$lig_opt->classe_future;

				$tmp_tab=explode("|",$lig_opt->liste_opt);
				for($loop=0;$loop<count($tmp_tab);$loop++) {
					if($tmp_tab[$loop]!="") {
						$tab_ele_opt[]=strtoupper($tmp_tab[$loop]);
					}
				}
			}
			else {
				// On r�cup�re les options de l'ann�e �coul�e
				$sql="SELECT * FROM j_eleves_groupes jeg, j_groupes_matieres jgm WHERE jeg.id_groupe=jgm.id_groupe AND jeg.login='$lig->login';";
				$res_opt=mysql_query($sql);
				if(mysql_num_rows($res_opt)>0) {
					while($lig_opt=mysql_fetch_object($res_opt)) {
						$tab_ele_opt[]=strtoupper($lig_opt->id_matiere);
					}
				}
			}

			for($i=0;$i<count($classe_fut);$i++) {
				echo "<td>\n";

				$coche_possible='y';
				if(($classe_fut[$i]!='Red')&&($classe_fut[$i]!='Dep')&&($classe_fut[$i]!='')) {
					for($loop=0;$loop<count($tab_ele_opt);$loop++) {
						if(in_array($tab_ele_opt[$loop],$tab_opt_exclue["$classe_fut[$i]"])) {
							$coche_possible='n';
							break;
						}
					}
				}

				if($coche_possible=='y') {
					echo "<input type='radio' name='classe_fut[$cpt]' id='classe_fut_".$i."_".$cpt."' value='$classe_fut[$i]' ";
					if(strtoupper($fut_classe)==strtoupper($classe_fut[$i])) {echo "checked ";}
					//alert('bip');
					echo "onchange=\"calcule_effectif('classe_fut',".count($classe_fut).");colorise_ligne('classe_fut',$cpt,$i);changement();\" ";
					//echo "title=\"$lig->login/$classe_fut[$i]\" ";
					echo "onmouseover=\"test_aff_classe3('".$lig->login."','".$classe_fut[$i]."');\" onmouseout=\"cacher_div('div_test_aff_classe2');\" ";
					echo "/>\n";

					//echo "'classe_fut_".$i."_".$cpt."'";
				}
				else {
					echo "_";
				}

				echo "</td>\n";
			}

			/*
			echo "<td>...</td>\n";
			echo "<td>...</td>\n";
			echo "<td>...</td>\n";
			*/

			for($i=0;$i<count($lv1);$i++) {
				echo "<td>\n";
				echo "<input type='radio' name='lv1[$cpt]' id='lv1_".$i."_".$cpt."' value='$lv1[$i]' ";
				if(in_array(strtoupper($lv1[$i]),$tab_ele_opt)) {echo "checked ";}
				echo "onchange=\"calcule_effectif('lv1',".count($lv1).");colorise_ligne('lv1',$cpt,$i);changement();\" ";
				echo "title=\"$lig->login/$lv1[$i]\" ";
				echo "/>\n";
				echo "</td>\n";
			}


			for($i=0;$i<count($lv2);$i++) {
				echo "<td>\n";
				echo "<input type='radio' name='lv2[$cpt]' id='lv2_".$i."_".$cpt."' value='$lv2[$i]' ";
				if(in_array(strtoupper($lv2[$i]),$tab_ele_opt)) {echo "checked ";}
				echo "onchange=\"calcule_effectif('lv2',".count($lv2).");colorise_ligne('lv2',$cpt,$i);changement();\" ";
				echo "title=\"$lig->login/$lv2[$i]\" ";
				echo "/>\n";
				echo "</td>\n";
			}


			for($i=0;$i<count($lv3);$i++) {
				echo "<td>\n";
				echo "<input type='radio' name='lv3[$cpt]' id='lv3_".$i."_".$cpt."' value='$lv3[$i]' ";
				if(in_array(strtoupper($lv3[$i]),$tab_ele_opt)) {echo "checked ";}
				echo "onchange=\"calcule_effectif('lv3',".count($lv3).");colorise_ligne('lv3',$cpt,$i);changement();\" ";
				echo "title=\"$lig->login/$lv3[$i]\" ";
				echo "/>\n";
				echo "</td>\n";
			}

			for($i=0;$i<count($autre_opt);$i++) {
				echo "<td>\n";
				echo "<input type='checkbox' name='autre_opt_".$i."[$cpt]' id='autre_opt_".$i."_".$cpt."' value='$autre_opt[$i]' ";
				if(in_array(strtoupper($autre_opt[$i]),$tab_ele_opt)) {echo "checked ";}
				echo "onchange=\"calcule_effectif('autre_opt',".count($autre_opt).");changement();\" ";
				echo "title=\"$lig->login/$autre_opt[$i]\" ";
				echo "/>\n";
				echo "</td>\n";
			}
			echo "</tr>\n";
			$cpt++;
		}
	}
	echo "</table>\n";

	echo "<script type='text/javascript'>
//document.getElementById('eff_tot_classe_sexe_'+".$id_classe_actuelle[$j].").innerHTML=$eff_tot_classe_M+'/'+$eff_tot_classe_F;
document.getElementById('eff_tot_classe_sexe_".$id_classe_actuelle[$j]."').innerHTML=$eff_tot_classe_M+'/'+$eff_tot_classe_F;
</script>\n";

	echo "<input type='hidden' name='is_posted' value='y' />\n";
	echo "<p align='center'><input type='submit' name='choix_options_".$id_classe_actuelle[$j]."' value='Valider' /></p>\n";
	echo "<hr width='200'/>\n";
}
echo "<input type='hidden' name='projet' value='$projet' />\n";
echo "</form>\n";



	//===============================================
	// Param�tres concernant le d�lais avant affichage d'une infobulle via delais_afficher_div()
	// Hauteur de la bande test�e pour la position de la souris:
	$hauteur_survol_infobulle=20;
	// Largeur de la bande test�e pour la position de la souris:
	$largeur_survol_infobulle=100;
	// D�lais en ms avant affichage:
	//$delais_affichage_infobulle=500;
	$delais_affichage_infobulle=2000;

	echo "<script type='text/javascript'>
	function test_aff_classe3(login,classe_fut) {
		//new Ajax.Updater($('div_test_aff_classe2'),'liste_classe_fut.php?ele_login='+login+'&classe_fut='+classe_fut+'&projet=$projet',{method: 'get'});
		new Ajax.Updater($('div_test_aff_classe2'),'liste_classe_fut.php?ele_login='+login+'&classe_fut='+classe_fut+'&projet=$projet&avec_classe_origine=y',{method: 'get'});
		delais_afficher_div('div_test_aff_classe2','y',10,-40,$delais_affichage_infobulle,$largeur_survol_infobulle,$hauteur_survol_infobulle);
	}
</script>\n";

echo "<div id='div_test_aff_classe2' class='infobulle_corps' style='position:absolute; border:1px solid black;'>Classes futures</div>\n";
	//===============================================

	//echo "<div id='div_set_profil' class='infobulle_corps' style='position:absolute; border:1px solid black;'>Profil</div>\n";

	$titre="S�lection du profil";
	$texte="<p style='text-align:center;'>";
	for($loop=0;$loop<count($tab_profil);$loop++) {
		if($loop>0) {$texte.=" - ";}
		$texte.="<a href='#' onclick=\"set_profil('".$tab_profil[$loop]."');return false;\">$tab_profil[$loop]</a>";
	}
	$texte.="</p>\n";
	$tabdiv_infobulle[]=creer_div_infobulle('div_set_profil',$titre,"",$texte,"",14,0,'y','y','n','n');

	echo "<input type='hidden' name='profil_courant' id='profil_courant' value='-1' />\n";

	echo "<script type='text/javascript'>

	var couleur_profil=new Array($chaine_couleur_profil);
	var tab_profil=new Array($chaine_profil);

	function set_profil(profil) {
		var cpt=document.getElementById('profil_courant').value;
		//alert('cpt='+cpt)
		if(document.getElementById('profil_'+cpt)) {
			document.getElementById('profil_'+cpt).value=profil;
			/*
			if(profil=='GC') {
				document.getElementById('div_profil_'+cpt).style.color='red';
			}
			if(profil=='C') {
				document.getElementById('div_profil_'+cpt).style.color='orange';
			}
			if(profil=='RAS') {
				document.getElementById('div_profil_'+cpt).style.color='gray';
			}
			if(profil=='B') {
				document.getElementById('div_profil_'+cpt).style.color='green';
			}
			if(profil=='TB') {
				document.getElementById('div_profil_'+cpt).style.color='blue';
			}
			*/
			for(m=0;m<couleur_profil.length;m++) {
				if(document.getElementById('profil_'+cpt).value==tab_profil[m]) {
					document.getElementById('div_profil_'+cpt).style.color=couleur_profil[m];
				}
			}
	
			document.getElementById('div_profil_'+cpt).innerHTML=profil;
		}
		cacher_div('div_set_profil');
	}
	
	function affiche_set_profil(cpt) {
		document.getElementById('profil_courant').value=cpt;
		afficher_div('div_set_profil','y',100,100);
	}

	for(i=0;i<$cpt;i++) {
		if(document.getElementById('profil_'+i)) {
			profil=document.getElementById('profil_'+i).value;
			/*
			if(profil=='GC') {
				document.getElementById('div_profil_'+i).style.color='red';
			}
			if(profil=='C') {
				document.getElementById('div_profil_'+i).style.color='orange';
			}
			if(profil=='RAS') {
				document.getElementById('div_profil_'+i).style.color='gray';
			}
			if(profil=='B') {
				document.getElementById('div_profil_'+i).style.color='green';
			}
			if(profil=='TB') {
				document.getElementById('div_profil_'+i).style.color='blue';
			}
			*/
			for(m=0;m<couleur_profil.length;m++) {
				if(document.getElementById('profil_'+i).value==tab_profil[m]) {
					document.getElementById('div_profil_'+i).style.color=couleur_profil[m];
				}
			}
		}
	}

</script>
\n";

	//===============================================



$titre="<span id='entete_div_photo_eleve'>El�ve</span>";
$texte="<div id='corps_div_photo_eleve' align='center'>\n";
//$texte.="<img src='../photos/eleves/".$photo."' id='id_photo_eleve' width='150' alt=\"Photo\" />";
//$texte.="<img src='../photos/eleves/".$photo."' id='id_photo_eleve' width='150' alt=\"Photo\" />";
$texte.="<br />\n";
$texte.="</div>\n";

$tabdiv_infobulle[]=creer_div_infobulle('div_photo',$titre,"",$texte,"",14,0,'y','y','n','n');

echo "<script type='text/javascript'>
function affiche_photo(photo,nom_prenom) {
	document.getElementById('entete_div_photo_eleve').innerHTML=nom_prenom;
	document.getElementById('corps_div_photo_eleve').innerHTML='<img src=\"'+photo+'\" width=\"150\" alt=\"Photo\" /><br />';
}

var tab_id_classe=new Array($chaine_id_classe);

function calcule_effectif(champ,n) {
	// Il faut d�clarer les variables k, i et j locales sans quoi l'appel r�cursif � calcule_effectif() m�ne � une boucle infinie.
	var k;
	var i;
	var j;

	var eff;
	var eff_M;
	var eff_F;
	var eff_ut_M;
	var eff_ut_F;
	var bidon;

	for(k=0;k<n;k++) {
		eff=0;
		eff_M=0;
		eff_F=0;

		eff_ut_M=0;
		eff_ut_F=0;

		for(i=0;i<$cpt;i++) {
			//alert('document.getElementById('+champ+'_'+i+')')
			// Le champ peut ne pas exister pour les classes futures (� cause des options exclues sur certaines classes)
			if(document.getElementById(champ+'_'+k+'_'+i)) {
				if(document.getElementById(champ+'_'+k+'_'+i).checked) {
					eff++;
					if(document.getElementById('eleve_sexe_'+i).innerHTML=='M') {eff_M++;} else {eff_F++;}
	
					if((document.getElementById('classe_fut_'+".(count($classe_fut)-2)."+'_'+i).checked)||(document.getElementById('classe_fut_'+".(count($classe_fut)-3)."+'_'+i).checked)) {
						// On ne compte pas comme effectif utile les Red et Dep
						bidon='y';
					}
					else {
						if(document.getElementById('eleve_sexe_'+i).innerHTML=='M') {eff_ut_M++;} else {eff_ut_F++;}
					}
				}
			}
		}

		for(j=0;j<tab_id_classe.length;j++) {
			document.getElementById('eff_col_'+tab_id_classe[j]+'_'+champ+'_'+k).innerHTML=eff;
			document.getElementById('eff_col_sexe_'+tab_id_classe[j]+'_'+champ+'_'+k).innerHTML=eff_M+'/'+eff_F;

			document.getElementById('eff_ut_col_'+tab_id_classe[j]+'_'+champ+'_'+k).innerHTML=eval(eff_ut_M+eff_ut_F)+'<br />'+eff_ut_M+'/'+eff_ut_F;

		}

		if(champ=='classe_fut') {
			// Il faut recalculer les effectifs utiles au cas o� on aurait augment�/r�duit l'effectif des Red/Dep

			//calcule_effectif('classe_fut',".count($classe_fut).");
			calcule_effectif('lv1',".count($lv1).");
			calcule_effectif('lv2',".count($lv2).");
			calcule_effectif('lv3',".count($lv3).");
			calcule_effectif('autre_opt',".count($autre_opt).");

			calcule_eff_utile_total();
		}

		//alert('eff='+eff);
	}
}

calcule_effectif('classe_fut',".count($classe_fut).");
calcule_effectif('lv1',".count($lv1).");
calcule_effectif('lv2',".count($lv2).");
calcule_effectif('lv3',".count($lv3).");
calcule_effectif('autre_opt',".count($autre_opt).");

function calcule_eff_utile_total() {
	var i;
	var j;
	var k;
	var eff_ut_M;
	var eff_ut_F;

	eff_ut_M=0;
	eff_ut_F=0;

	for(k=0;k<".count($classe_fut).";k++) {
		if((k!=".(count($classe_fut)-2).")&&(k!=".(count($classe_fut)-3).")) {
			for(i=0;i<$cpt;i++) {
				// Le champ peut ne pas exister pour les classes futures (� cause des options exclues sur certaines classes)
				if(document.getElementById('classe_fut_'+k+'_'+i)) {
					if(document.getElementById('classe_fut_'+k+'_'+i).checked) {
						if(document.getElementById('eleve_sexe_'+i)) {
							if(document.getElementById('eleve_sexe_'+i).innerHTML=='M') {eff_ut_M++;} else {eff_ut_F++;}
						}
					}
				}
			}
		}
	}

	for(j=0;j<tab_id_classe.length;j++) {
		if(document.getElementById('eff_ut_tot_'+tab_id_classe[j])) {
			//alert(eff_ut_M+'/'+eff_ut_F);
			//document.getElementById('eff_ut_tot_'+tab_id_classe[j]).innerHTML=eval(eff_ut_M+eff_ut_F)+'<br />'+eff_ut_M+'/'+eff_ut_F;
			document.getElementById('eff_ut_tot_'+tab_id_classe[j]).innerHTML=eval(eff_ut_M+eff_ut_F);
			document.getElementById('eff_ut_tot_sexe_'+tab_id_classe[j]).innerHTML=eff_ut_M+'/'+eff_ut_F;
		}
	}
}

for(j=0;j<tab_id_classe.length;j++) {
	document.getElementById('eff_tot_'+tab_id_classe[j]).innerHTML=$eff_tot;
	document.getElementById('eff_tot_sexe_'+tab_id_classe[j]).innerHTML=$eff_tot_M+'/'+$eff_tot_F;

}

var couleur_classe_fut=new Array($chaine_couleur_classe_fut);
var couleur_lv1=new Array($chaine_couleur_lv1);
var couleur_lv2=new Array($chaine_couleur_lv2);
var couleur_lv3=new Array($chaine_couleur_lv3);

function colorise(mode,n) {
	var k;
	var i;
	var m;

	for(k=0;k<n;k++) {
		for(i=0;i<$cpt;i++) {
			if(mode!='profil') {
				// Le champ peut ne pas exister pour les classes futures (� cause des options exclues sur certaines classes)
				if(document.getElementById(mode+'_'+k+'_'+i)) {
					if(document.getElementById(mode+'_'+k+'_'+i).checked) {
						if(mode=='classe_fut') {
							document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_classe_fut[k];
						}
						if(mode=='lv1') {
							document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_lv1[k];
						}
						if(mode=='lv2') {
							document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_lv2[k];
						}
						if(mode=='lv3') {
							document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_lv3[k];
						}
					}
				}
			}
			else {
				for(m=0;m<couleur_profil.length;m++) {
					if(document.getElementById('profil_'+i).value==tab_profil[m]) {
						document.getElementById('tr_eleve_'+i).style.backgroundColor=couleur_profil[m];
					}
				}
			}
		}
	}
}

colorise('classe_fut',".count($classe_fut).");

function colorise_ligne(cat,cpt,i) {
	if(document.forms[0].elements['colorisation'].options[document.forms[0].elements['colorisation'].selectedIndex].value==cat) {
		if(cat=='classe_fut') {
			document.getElementById('tr_eleve_'+cpt).style.backgroundColor=couleur_classe_fut[i];
		}
		if(cat=='lv1') {
			document.getElementById('tr_eleve_'+cpt).style.backgroundColor=couleur_lv1[i];
		}
		if(cat=='lv2') {
			document.getElementById('tr_eleve_'+cpt).style.backgroundColor=couleur_lv2[i];
		}
		if(cat=='lv3') {
			document.getElementById('tr_eleve_'+cpt).style.backgroundColor=couleur_lv3[i];
		}
		if(cat=='profil') {
			document.getElementById('tr_eleve_'+cpt).style.backgroundColor=couleur_profil[i];
		}
	}
}

function lance_colorisation() {
	cat=document.forms[0].elements['colorisation'].options[document.forms[0].elements['colorisation'].selectedIndex].value;
	//alert(cat);
	if(cat=='classe_fut') {
		colorise(cat,".count($classe_fut).");
	}
	if(cat=='lv1') {
		colorise(cat,".count($lv1).");
	}
	if(cat=='lv2') {
		colorise(cat,".count($lv2).");
	}
	if(cat=='lv3') {
		colorise(cat,".count($lv3).");
	}
	if(cat=='profil') {
		colorise(cat,".count($tab_profil).");
	}
}
";

// probleme: si une classe ou cat�gorie (red ou arriv) a un effectif nul le rang du premier et du dernier �l�ve ne sont pas affect�s et on obtient alors une erreur
$chaine_reperes_eleves_classes="";
for($i=0;$i<count($num_eleve1_id_classe_actuelle);$i++) {
	if($i==0) {$chaine_reperes_eleves_classes.="var num_eleve1_id_classe_actuelle=new Array('$num_eleve1_id_classe_actuelle[$i]'";}
	else {$chaine_reperes_eleves_classes.=",'$num_eleve1_id_classe_actuelle[$i]'";}
}
$chaine_reperes_eleves_classes.=");\n";

for($i=0;$i<count($num_eleve2_id_classe_actuelle);$i++) {
	if($i==0) {$chaine_reperes_eleves_classes.="var num_eleve2_id_classe_actuelle=new Array('$num_eleve2_id_classe_actuelle[$i]'";}
	else {$chaine_reperes_eleves_classes.=",'$num_eleve2_id_classe_actuelle[$i]'";}
}
$chaine_reperes_eleves_classes.=");\n";

echo $chaine_reperes_eleves_classes;

echo "function modif_colonne(col,j,mode) {
	/*
	alert('modif_colonne('+col+','+j+','+mode+')');
	alert('num_eleve1_id_classe_actuelle['+j+']='+num_eleve1_id_classe_actuelle[j]);
	alert('num_eleve2_id_classe_actuelle['+j+']='+num_eleve2_id_classe_actuelle[j]);
	alert('Premier eleve '+document.getElementById('id_eleve_'+num_eleve1_id_classe_actuelle[j]).value);
	*/

	for(i=num_eleve1_id_classe_actuelle[j];i<=num_eleve2_id_classe_actuelle[j];i++) {
		//alert(i);
		if(document.getElementById(col+'_'+i)) {
			document.getElementById(col+'_'+i).checked=mode;
		}
		/*
		else {
			alert('Pas de document.getElementById('+col+'_'+i+')')
		}
		*/
	}

	/*
	i1=num_eleve1_id_classe_actuelle[j];
	i2=num_eleve2_id_classe_actuelle[j];
	alert('i1='+i1+' et i2='+i2);

	for(i=i1;i<=i2;i++) {
		//alert(q);
		if(document.getElementById(col+'_'+q)) {
			document.getElementById(col+'_'+q).checked=mode;
		}
		else {
			alert('Pas de document.getElementById('+col+'_'+q+')')
		}
	}
	*/

	//alert('PLOP');

	cat=document.forms[0].elements['colorisation'].options[document.forms[0].elements['colorisation'].selectedIndex].value;
	if(col.substr(0,cat.length)==cat) {lance_colorisation();}
}
</script>\n";

require("../lib/footer.inc.php");
?>
