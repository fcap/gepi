<?php
/*
* $Id$
*
* Copyright 2001-2004 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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

extract($_GET, EXTR_OVERWRITE);
extract($_POST, EXTR_OVERWRITE);

// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
	header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
	die();
} else if ($resultat_session == '0') {
	header("Location: ../logout.php?auto=1");
	die();
}

include "../lib/periodes.inc.php";
if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}


//**************** EN-TETE *****************
$titre_page = "V�rification du remplissage des bulletins";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

// On teste si un professeur peut effectuer cette operation
if (($_SESSION['statut'] == 'professeur') and getSettingValue("GepiProfImprBul")!='yes') {
	die("Droits insuffisants pour effectuer cette op�ration");
}

//debug_var();

// Selection de la classe
if (!(isset($id_classe))) {
	echo "<p class=bold><a href='../accueil.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a> | \n";
	echo "<b>Choisissez la classe&nbsp;:</b></p>\n<br />\n";
	//<table><tr><td>\n";
	if ($_SESSION["statut"] == "scolarite") {
		//$appel_donnees = mysql_query("SELECT DISTINCT c.* FROM classes c, periodes p WHERE p.id_classe = c.id  ORDER BY classe");
		$appel_donnees = mysql_query("SELECT DISTINCT c.* FROM classes c, periodes p, j_scol_classes jsc WHERE p.id_classe = c.id  AND jsc.id_classe=c.id AND jsc.login='".$_SESSION['login']."' ORDER BY classe");
	}
	else {
		$appel_donnees = mysql_query("SELECT DISTINCT c.* FROM classes c, j_eleves_professeurs s, j_eleves_classes cc WHERE (s.professeur='" . $_SESSION['login'] . "' AND s.login = cc.login AND cc.id_classe = c.id)");
	}

	$lignes = mysql_num_rows($appel_donnees);


	if($lignes==0) {
		echo "<p>Aucune classe ne vous est attribu�e.<br />Contactez l'administrateur pour qu'il effectue le param�trage appropri� dans la Gestion des classes.</p>\n";
	}
	else{
		unset($lien_classe);
		unset($txt_classe);
		$i = 0;
		//while ($i < $nombreligne) {
		while ($i < $lignes) {
			$lien_classe[]="verif_bulletins.php?id_classe=".mysql_result($appel_donnees, $i, "id");
			$txt_classe[]=ucfirst(mysql_result($appel_donnees, $i, "classe"));
			$i++;
		}

		tab_liste($txt_classe,$lien_classe,3);
	}

	/*
	$nb_class_par_colonne=round($lignes/3);
	echo "<table width='100%'>\n";
	echo "<tr valign='top' align='center'>\n";

	$i = 0;

	echo "<td align='left'>\n";
	while($i < $lignes) {
		if(($i>0)&&(round($i/$nb_class_par_colonne)==$i/$nb_class_par_colonne)) {
			echo "</td>\n";
			echo "<td align='left'>\n";
		}

		$id_classe = mysql_result($appel_donnees, $i, "id");
		$display_class = mysql_result($appel_donnees, $i, "classe");
		echo "<a href='verif_bulletins.php?id_classe=$id_classe'>".ucfirst($display_class)."</a><br />\n";
		$i++;
	}
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	*/

	//echo "</td><td></td></table>";
} else if (!(isset($per))) {
	echo "<form action='".$_SERVER['PHP_SELF']."' name='form1' method='post'>\n";
	//echo "<p class=bold><a href='../accueil.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a> | \n";
	echo "<p class='bold'><a href='".$_SERVER['PHP_SELF']."'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>\n";
	//echo "<p class=bold><a href='".$_SERVER['PHP_SELF']."?id_classe=$id_classe'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a> | \n";



	// ===========================================
	// Ajout lien classe pr�c�dente / classe suivante
	//if($_SESSION['statut']=='scolarite') {
		$sql = "SELECT DISTINCT c.id,c.classe FROM classes c, periodes p, j_scol_classes jsc WHERE p.id_classe = c.id  AND jsc.id_classe=c.id AND jsc.login='".$_SESSION['login']."' ORDER BY classe";
	/*
	}
	elseif($_SESSION['statut']=='professeur') {
		$sql="SELECT DISTINCT c.id,c.classe FROM classes c, periodes p, j_groupes_classes jgc, j_groupes_professeurs jgp WHERE p.id_classe = c.id AND jgc.id_classe=c.id AND jgp.id_groupe=jgc.id_groupe AND jgp.login='".$_SESSION['login']."' ORDER BY c.classe";
	}
	elseif($_SESSION['statut']=='cpe') {
		$sql="SELECT DISTINCT c.id,c.classe FROM classes c, periodes p, j_eleves_classes jec, j_eleves_cpe jecpe WHERE
			p.id_classe = c.id AND
			jec.id_classe=c.id AND
			jec.periode=p.num_periode AND
			jecpe.e_login=jec.login AND
			jecpe.cpe_login='".$_SESSION['login']."'
			ORDER BY classe";
	}
	*/

	$chaine_options_classes="";
	$res_class_tmp=mysql_query($sql);
	if(mysql_num_rows($res_class_tmp)>0) {
		$id_class_prec=0;
		$id_class_suiv=0;
		$temoin_tmp=0;
		while($lig_class_tmp=mysql_fetch_object($res_class_tmp)) {
			if($lig_class_tmp->id==$id_classe) {
				$chaine_options_classes.="<option value='$lig_class_tmp->id' selected='true'>$lig_class_tmp->classe</option>\n";
				$temoin_tmp=1;
				if($lig_class_tmp=mysql_fetch_object($res_class_tmp)) {
					$chaine_options_classes.="<option value='$lig_class_tmp->id'>$lig_class_tmp->classe</option>\n";
					$id_class_suiv=$lig_class_tmp->id;
				}
				else{
					$id_class_suiv=0;
				}
			}
			else {
				$chaine_options_classes.="<option value='$lig_class_tmp->id'>$lig_class_tmp->classe</option>\n";
			}

			if($temoin_tmp==0) {
				$id_class_prec=$lig_class_tmp->id;
			}
		}
	}
	// =================================
	if(isset($id_class_prec)) {
		if($id_class_prec!=0) {
			echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_prec";
			echo "'>Classe pr�c�dente</a>\n";
		}
	}
	if($chaine_options_classes!="") {
		echo " | <select name='id_classe' onchange=\"document.forms['form1'].submit();\">\n";
		echo $chaine_options_classes;
		echo "</select>\n";
	}
	if(isset($id_class_suiv)) {
		if($id_class_suiv!=0) {
			echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_suiv";
			echo "'>Classe suivante</a>\n";
		}
	}
	echo "</form>\n";
	//fin ajout lien classe pr�c�dente / classe suivante
	// ===========================================


	// On teste si les �l�ves ont bien un CPE responsable

	$test1 = mysql_query("SELECT distinct(login) login from j_eleves_classes WHERE id_classe='" . $id_classe . "'");
	$nb_eleves = mysql_num_rows($test1);
	$j = 0;
	$flag = true;
	while ($j < $nb_eleves) {
		$login_e = mysql_result($test1, $j, "login");
		$test = mysql_result(mysql_query("SELECT count(*) FROM j_eleves_cpe WHERE e_login='" . $login_e . "'"), 0);
		if ($test == "0") {
			$flag = false;
			break;
		}
		$j++;
	}

	if (!$flag) {
		echo "<p>ATTENTION&nbsp;: certains �l�ves de cette classe n'ont pas de CPE responsable attribu�. Cela g�n�rera un message d'erreur sur la page d'�dition des bulletins. Il faut corriger ce probl�me avant impression (contactez l'administrateur).</p>\n";
	}

	$sql_classe="SELECT * FROM `classes`WHERE id=$id_classe";
	$call_classe=mysql_query($sql_classe);
	$nom_classe=mysql_result($call_classe,0,"classe");

	//echo "<p><b> Classe&nbsp;: $nom_classe - Choisissez la p�riode&nbsp;: </b></p><br />\n";
	echo "<p><b> Classe&nbsp;: $nom_classe - Choisissez la p�riode et les points � v�rifier: </b></p><br />\n";
	include "../lib/periodes.inc.php";


	echo "<table class='boireaus'>\n";
	echo "<tr>\n";
	echo "<th rowspan='2'>V�rifier</th>\n";
	$i=1;
	while ($i < $nb_periode) {
		echo "<th>".ucfirst($nom_periode[$i])."</th>\n";
		$i++;
	}
	echo "</tr>\n";

	echo "<tr>\n";
	//echo "<th>V�rifier</th>\n";
	$i=1;
	while ($i < $nb_periode) {
		echo "<th>";
		echo "<span style='font-size:x-small;'>";
		if ($ver_periode[$i] == "P")  {
			echo " (p�riode partiellement close, seule la saisie des avis du conseil de classe est possible)\n";
		} else if ($ver_periode[$i] == "O")  {
			echo " (p�riode enti�rement close, plus aucune saisie/modification n'est possible)\n";
		} else {
			echo " (p�riode ouverte, les saisies/modifications sont possibles)\n";
		}
		echo "</span>\n";
		echo "</th>\n";
		$i++;
	}
	echo "</tr>\n";

	/*
	$i="1";
	while ($i < $nb_periode) {
		echo "<p><a href='verif_bulletins.php?id_classe=$id_classe&amp;per=$i'>".ucfirst($nom_periode[$i])."</a>\n";
		if ($ver_periode[$i] == "P")  {
			echo " (p�riode partiellement close, seule la saisie des avis du conseil de classe est possible)\n";
		} else if ($ver_periode[$i] == "O")  {
			echo " (p�riode enti�rement close, plus aucune saisie/modification n'est possible)\n";
		} else {
			echo " (p�riode ouverte, les saisies/modifications sont possibles)\n";
		}
		//echo "<p>\n";
		echo "</p>\n";
		$i++;
	}
	*/

	$alt=1;
	$i="1";
	echo "<tr class='lig$alt white_hover'>\n";
	echo "<th>Notes et appr�ciations</th>\n";
	while ($i < $nb_periode) {

		echo "<td><a href='verif_bulletins.php?id_classe=$id_classe&amp;per=$i&amp;mode=note_app'>";
		echo "<img src='../images/icons/chercher.png' width='32' height='32' alt=\"".ucfirst($nom_periode[$i])." \" title=\"".ucfirst($nom_periode[$i])." \" /></a>";
		/*
		echo "<br />\n";
		echo "<span style='font-size:x-small;'>";
		if ($ver_periode[$i] == "P")  {
			echo " (p�riode partiellement close, seule la saisie des avis du conseil de classe est possible)\n";
		} else if ($ver_periode[$i] == "O")  {
			echo " (p�riode enti�rement close, plus aucune saisie/modification n'est possible)\n";
		} else {
			echo " (p�riode ouverte, les saisies/modifications sont possibles)\n";
		}
		echo "</span>\n";
		*/
		echo "</td>\n";
		$i++;
	}
	echo "</tr>\n";

	$alt=$alt*(-1);
	$i="1";
	echo "<tr class='lig$alt white_hover'>\n";
	echo "<th>Absences</th>\n";
	while ($i < $nb_periode) {

		echo "<td><a href='verif_bulletins.php?id_classe=$id_classe&amp;per=$i&amp;mode=abs'>";
		echo "<img src='../images/icons/chercher.png' width='32' height='32' alt=\"".ucfirst($nom_periode[$i])." \" title=\"".ucfirst($nom_periode[$i])." \" /></a>";
		/*
		echo "<br />\n";
		echo "<span style='font-size:x-small;'>";
		if ($ver_periode[$i] == "P")  {
			echo " (p�riode partiellement close, seule la saisie des avis du conseil de classe est possible)\n";
		} else if ($ver_periode[$i] == "O")  {
			echo " (p�riode enti�rement close, plus aucune saisie/modification n'est possible)\n";
		} else {
			echo " (p�riode ouverte, les saisies/modifications sont possibles)\n";
		}
		echo "</span>\n";
		*/
		echo "</td>\n";
		$i++;
	}
	echo "</tr>\n";

	$alt=$alt*(-1);
	$i="1";
	echo "<tr class='lig$alt white_hover'>\n";
	echo "<th>Avis du conseil</th>\n";
	while ($i < $nb_periode) {

		echo "<td><a href='verif_bulletins.php?id_classe=$id_classe&amp;per=$i&amp;mode=avis'>";
		echo "<img src='../images/icons/chercher.png' width='32' height='32' alt=\"".ucfirst($nom_periode[$i])." \" title=\"".ucfirst($nom_periode[$i])." \" /></a>";
		/*
		echo "<br />\n";
		echo "<span style='font-size:x-small;'>";
		if ($ver_periode[$i] == "P")  {
			echo " (p�riode partiellement close, seule la saisie des avis du conseil de classe est possible)\n";
		} else if ($ver_periode[$i] == "O")  {
			echo " (p�riode enti�rement close, plus aucune saisie/modification n'est possible)\n";
		} else {
			echo " (p�riode ouverte, les saisies/modifications sont possibles)\n";
		}
		echo "</span>\n";
		*/
		echo "</td>\n";
		$i++;
	}
	echo "</tr>\n";


	if(getSettingValue('avis_conseil_classe_a_la_mano')=='y') {
		$alt=$alt*(-1);
		$i="1";
		echo "<tr class='lig$alt white_hover'>\n";
		echo "<th>Tout sauf les avis du conseil</th>\n";
		while ($i < $nb_periode) {
	
			echo "<td><a href='verif_bulletins.php?id_classe=$id_classe&amp;per=$i&amp;mode=tout_sauf_avis'>";
			echo "<img src='../images/icons/chercher.png' width='32' height='32' alt=\"".ucfirst($nom_periode[$i])." \" title=\"".ucfirst($nom_periode[$i])." \" /></a>";
			/*
			echo "<br />\n";
			echo "<span style='font-size:x-small;'>";
			if ($ver_periode[$i] == "P")  {
				echo " (p�riode partiellement close, seule la saisie des avis du conseil de classe est possible)\n";
			} else if ($ver_periode[$i] == "O")  {
				echo " (p�riode enti�rement close, plus aucune saisie/modification n'est possible)\n";
			} else {
				echo " (p�riode ouverte, les saisies/modifications sont possibles)\n";
			}
			echo "</span>\n";
			*/
			echo "</td>\n";
			$i++;
		}
		echo "</tr>\n";
	}

	$alt=$alt*(-1);
	$i="1";
	echo "<tr class='lig$alt'>\n";
	echo "<th>Tout</th>\n";
	while ($i < $nb_periode) {

		echo "<td><a href='verif_bulletins.php?id_classe=$id_classe&amp;per=$i'>";
		echo "<img src='../images/icons/chercher.png' width='32' height='32' alt=\"".ucfirst($nom_periode[$i])." \" title=\"".ucfirst($nom_periode[$i])." \" /></a>";
		/*
		echo "<br />\n";
		echo "<span style='font-size:x-small;'>";
		if ($ver_periode[$i] == "P")  {
			echo " (p�riode partiellement close, seule la saisie des avis du conseil de classe est possible)\n";
		} else if ($ver_periode[$i] == "O")  {
			echo " (p�riode enti�rement close, plus aucune saisie/modification n'est possible)\n";
		} else {
			echo " (p�riode ouverte, les saisies/modifications sont possibles)\n";
		}
		echo "</span>\n";
		*/
		echo "</td>\n";
		$i++;
	}
	echo "</tr>\n";

    if ($gepiSettings['active_mod_ects'] == 'y') {
		$alt=$alt*(-1);
        $i="1";
        echo "<tr class='lig$alt'>\n";
        echo "<th>Cr�dits ECTS</th>\n";
        while ($i < $nb_periode) {

            echo "<td><a href='verif_bulletins.php?id_classe=$id_classe&amp;per=$i&amp;mode=ects'>";
            echo "<img src='../images/icons/chercher.png' width='32' height='32' alt=\"".ucfirst($nom_periode[$i])." \" title=\"".ucfirst($nom_periode[$i])." \" /></a>";
            echo "</td>\n";
            $i++;
        }
        echo "</tr>\n";
    }

	echo "</table>\n";

} else {

	$mode=isset($_POST['mode']) ? $_POST['mode'] : (isset($_GET['mode']) ? $_GET['mode'] : "");
	if( $mode!='note_app' && $mode!='abs' && $mode!='avis' && $mode != 'ects' && $mode!='tout_sauf_avis') {
		$mode="tout";
	}

	echo "<form action='".$_SERVER['PHP_SELF']."' name='form1' method='post'>\n";
	//echo "<p class=bold><a href='../accueil.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a> | \n";
	echo "<p class=bold><a href='".$_SERVER['PHP_SELF']."?id_classe=$id_classe'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>\n";


	// ===========================================
	// Ajout lien classe pr�c�dente / classe suivante
	//if($_SESSION['statut']=='scolarite') {
		$sql = "SELECT DISTINCT c.id,c.classe FROM classes c, periodes p, j_scol_classes jsc WHERE p.id_classe = c.id  AND jsc.id_classe=c.id AND jsc.login='".$_SESSION['login']."' ORDER BY classe";
	/*
	}
	elseif($_SESSION['statut']=='professeur') {
		$sql="SELECT DISTINCT c.id,c.classe FROM classes c, periodes p, j_groupes_classes jgc, j_groupes_professeurs jgp WHERE p.id_classe = c.id AND jgc.id_classe=c.id AND jgp.id_groupe=jgc.id_groupe AND jgp.login='".$_SESSION['login']."' ORDER BY c.classe";
	}
	elseif($_SESSION['statut']=='cpe') {
		$sql="SELECT DISTINCT c.id,c.classe FROM classes c, periodes p, j_eleves_classes jec, j_eleves_cpe jecpe WHERE
			p.id_classe = c.id AND
			jec.id_classe=c.id AND
			jec.periode=p.num_periode AND
			jecpe.e_login=jec.login AND
			jecpe.cpe_login='".$_SESSION['login']."'
			ORDER BY classe";
	}
	*/
	$chaine_options_classes="";
	$res_class_tmp=mysql_query($sql);
	if(mysql_num_rows($res_class_tmp)>0) {
		$id_class_prec=0;
		$id_class_suiv=0;
		$temoin_tmp=0;
		while($lig_class_tmp=mysql_fetch_object($res_class_tmp)) {
			if($lig_class_tmp->id==$id_classe) {
				$chaine_options_classes.="<option value='$lig_class_tmp->id' selected='true'>$lig_class_tmp->classe</option>\n";
				$temoin_tmp=1;
				if($lig_class_tmp=mysql_fetch_object($res_class_tmp)) {
					$chaine_options_classes.="<option value='$lig_class_tmp->id'>$lig_class_tmp->classe</option>\n";
					$id_class_suiv=$lig_class_tmp->id;
				}
				else{
					$id_class_suiv=0;
				}
			}
			else {
				$chaine_options_classes.="<option value='$lig_class_tmp->id'>$lig_class_tmp->classe</option>\n";
			}

			if($temoin_tmp==0) {
				$id_class_prec=$lig_class_tmp->id;
			}
		}
	}
	// =================================
	if(isset($id_class_prec)) {
		if($id_class_prec!=0) {echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_prec&amp;per=$per&amp;mode=$mode'>Classe pr�c�dente</a>\n";}
	}
	if($chaine_options_classes!="") {
		echo " | <select name='id_classe' onchange=\"document.forms['form1'].submit();\">\n";
		echo $chaine_options_classes;
		echo "</select>\n";
		echo "<input type='hidden' name='per' value='$per' />\n";
		echo "<input type='hidden' name='mode' value='$mode' />\n";
	}
	if(isset($id_class_suiv)) {
		if($id_class_suiv!=0) {echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_suiv&amp;per=$per&amp;mode=$mode'>Classe suivante</a>\n";}
	}
	echo "</form>\n";
	//fin ajout lien classe pr�c�dente / classe suivante
	// ===========================================



	$bulletin_rempli = 'yes';
	$call_classe = mysql_query("SELECT * FROM classes WHERE id = '$id_classe'");
	$classe = mysql_result($call_classe, "0", "classe");
	echo "<p>Classe&nbsp;: $classe - $nom_periode[$per] - Ann�e scolaire&nbsp;: ".getSettingValue("gepiYear")."</p>";

	//
	// V�rification de param�tres g�n�raux
	//
	$current_classe_nom_complet = mysql_result($call_classe, 0, "nom_complet");
	if ($current_classe_nom_complet == '') {
		$bulletin_rempli = 'no';
		echo "<p>Le nom long de la classe n'est pas d�fini !</p>\n";
	}
	$current_classe_suivi_par = mysql_result($call_classe, 0, "suivi_par");
	if ($current_classe_suivi_par == '') {
		$bulletin_rempli = 'no';
		echo "<p>La personne de l'administration charg�e de la classe n'est pas d�finie !</p>\n";
	}
	$current_classe_formule = mysql_result($call_classe, 0, "formule");
	if ($current_classe_formule == '') {
		$bulletin_rempli = 'no';
		echo "<p>La formule � la fin de chaque bulletin n'est pas d�finie !</p>\n";
	}
	$appel_donnees_eleves = mysql_query("SELECT e.login, e.nom, e.prenom FROM eleves e, j_eleves_classes j WHERE (j.id_classe='$id_classe' AND j.login = e.login and j.periode='$per') ORDER BY login");
	$nb_eleves = mysql_num_rows($appel_donnees_eleves);
	$j = 0;
	//
	//D�but de la boucle �l�ve
	//

	switch($mode) {
		case 'note_app':
			echo "<p class='bold'>V�rification du remplissage des moyennes et appr�ciations&nbsp;:</p>\n";
			break;
		case 'avis':
			echo "<p class='bold'>V�rification du remplissage des avis du conseil de classe&nbsp;:</p>\n";
			break;
		case 'abs':
			echo "<p class='bold'>V�rification du remplissage des absences&nbsp;:</p>\n";
			break;
		case 'ects':
			echo "<p class='bold'>V�rification du remplissage des cr�dits ECTS&nbsp;:</p>\n";
			break;
		case 'tout_sauf_avis':
			echo "<p class='bold'>V�rification du remplissage des moyennes, appr�ciations et absences&nbsp;:</p>\n";
			break;
		case 'tout':
			echo "<p class='bold'>V�rification du remplissage des moyennes, appr�ciations, absences et avis du conseil de classe&nbsp;:</p>\n";
			break;
	}

	// Tableau pour stocker les infos � envoyer aux profs � propos des notes/app non remplies
	$tab_alerte_prof=array();

	// Affichage sur 3 colonnes
	$nb_eleve_par_colonne=round($nb_eleves/2);

	echo "<table width='100%'>\n";
	echo "<tr valign='top' align='center'>\n";

	$cpt_i = 0;

	echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
	echo "<td align='left'>\n";


	$temoin_note_app=0;
	$temoin_avis=0;
	$temoin_aid=0;
	$temoin_abs=0;
    $temoin_ects=0;
    $temoin_has_ects = false; // Ce t�moin sert dans les cas o� en r�alit� aucun �l�ve ne suit d'enseignement ouvrant droit � ECTS.
	while($j < $nb_eleves) {

		//affichage 2 colonnes
		if(($cpt_i>0)&&(round($cpt_i/$nb_eleve_par_colonne)==$cpt_i/$nb_eleve_par_colonne)) {
			echo "</td>\n";
			echo "<td align='left'>\n";
		}


		$id_eleve[$j] = mysql_result($appel_donnees_eleves, $j, "login");
		$eleve_nom[$j] = mysql_result($appel_donnees_eleves, $j, "nom");
		$eleve_prenom[$j] = mysql_result($appel_donnees_eleves, $j, "prenom");


		$affiche_nom = 1;
		if(($mode=="note_app")||($mode=="tout")||($mode=="tout_sauf_avis")) {
			$groupeinfo = mysql_query("SELECT DISTINCT id_groupe FROM j_eleves_groupes WHERE login='" . $id_eleve[$j] ."'");
			$lignes_groupes = mysql_num_rows($groupeinfo);
			//
			//V�rification des appr�ciations
			//

			$i= 0;
			//
			//D�but de la boucle mati�re
			//

			// Variable remont�e hors du test sur $mode
			//$affiche_nom = 1;
			$affiche_mess_app = 1;
			$affiche_mess_note = 1;
			while($i < $lignes_groupes) {
				$group_id = mysql_result($groupeinfo, $i, "id_groupe");
				$current_group = get_group($group_id);

				if (in_array($id_eleve[$j], $current_group["eleves"][$per]["list"])) { // Si l'�l�ve suit cet enseignement pour la p�riode consid�r�e
					//
					//V�rification des appr�ciations :
					//
					$test_app = mysql_query("SELECT * FROM matieres_appreciations WHERE (login = '$id_eleve[$j]' and id_groupe = '" . $current_group["id"] . "' and periode = '$per')");
					//$app = @mysql_result($test_app, 0, 'appreciation');
					$app="";
					if(mysql_num_rows($test_app)>0) {$app=mysql_result($test_app, 0, 'appreciation');}
					if ($app == '') {
						$bulletin_rempli = 'no';
						if ($affiche_nom != 0) {
							//echo "<br /><br /><br />\n";
							echo "<p style='border:1px solid black;'><span class='bold'>$eleve_prenom[$j] $eleve_nom[$j]";
							//echo "<br />\n";
							echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'><img src='../images/icons/bulletin_simp.png' width='17' height='17' alt='bulletin simple dans une nouvelle page' title='bulletin simple dans une nouvelle page' /></a>)</span>&nbsp;:";
						}
						if ($affiche_mess_app != 0) {
							echo "<br /><br />\n";
							echo "<b>Appr�ciations non remplies</b> pour les mati�res suivantes&nbsp;: \n";
						}
						$affiche_nom = 0;
						$affiche_mess_app = 0;
						//============================================
						// MODIF: boireaus
						// Pour les mati�res comme Histoire & G�o,...
						//echo "<br />--> " . $current_group["description"] . " (" . $current_group["classlist_string"] . ")  --  (";
						echo "<br />--> " . htmlentities($current_group["description"]) . " (" . $current_group["classlist_string"] . ")  --  (";
						//============================================
						$m=0;
						$virgule = 1;
						foreach ($current_group["profs"]["list"] as $login_prof) {
							$email = retourne_email($login_prof);
							$nom_prof = $current_group["profs"]["users"][$login_prof]["nom"];
							$prenom_prof = $current_group["profs"]["users"][$login_prof]["prenom"];
							$civilite_prof = $current_group["profs"]["users"][$login_prof]["civilite"];

							if(!isset($tab_alerte_prof[$login_prof])) {
								$tab_alerte_prof[$login_prof]=array();
								$tab_alerte_prof[$login_prof]['civilite']=$civilite_prof;
								$tab_alerte_prof[$login_prof]['nom']=$nom_prof;
								$tab_alerte_prof[$login_prof]['prenom']=$prenom_prof;
								$tab_alerte_prof[$login_prof]['email']=$email;
							}

							if(!isset($tab_alerte_prof[$login_prof]['groupe'][$group_id])) {
								$tab_alerte_prof["$login_prof"]['groupe'][$group_id]['info']=$current_group["description"]." (".$current_group["classlist_string"].")";

								//echo "\$tab_alerte_prof[$login_prof]['groupe'][$group_id]['info']=".$tab_alerte_prof["$login_prof"]['groupe'][$group_id]['info']."<br />";
							}

							$tab_alerte_prof[$login_prof]['groupe'][$group_id]['app_manquante'][]=strtoupper($eleve_nom[$j])." ".ucfirst(strtolower($eleve_prenom[$j]));
							//echo "\$tab_alerte_prof[$login_prof]['groupe'][$group_id]['app_manquante'][]=".strtoupper($eleve_nom[$j])." ".ucfirst(strtolower($eleve_prenom[$j]))."<br />";

							//if($email!="") {
							if(($email!="")&&(check_mail($email))) {
								$sujet_mail="[Gepi]: Appreciation non remplie: ".$id_eleve[$j];
								$message_mail="Bonjour,\r\n\r\nCordialement";
								echo "<a href='mailto:$email?subject=$sujet_mail&amp;body=".rawurlencode($message_mail)."'>".ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof)."</a>";
							}
							else{
								echo ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof);
							}
							$m++;
							if ($m == count($current_group["profs"]["list"])) {$virgule = 0;}
							if ($virgule == 1) {echo ", ";}
						}
						echo ")\n";

						$temoin_note_app++;
					}
				}
				$i++;
			}

			//
			//V�rification des moyennes
			//
			$i= 0;
			//
			//D�but de la boucle mati�re
			//
			while($i < $lignes_groupes) {
				$group_id = mysql_result($groupeinfo, $i, "id_groupe");
				$current_group = get_group($group_id);

				if (in_array($id_eleve[$j], $current_group["eleves"][$per]["list"])) { // Si l'�l�ve suit cet enseignement pour la p�riode consid�r�e
					//
					//V�rification des moyennes :
					//
					$test_notes = mysql_query("SELECT * FROM matieres_notes WHERE (login = '$id_eleve[$j]' and id_groupe = '" . $current_group["id"] . "' and periode = '$per')");
					//$note = @mysql_result($test_notes, 0, 'note');
					$note="";
					if(mysql_num_rows($test_notes)>0) {$note=mysql_result($test_notes, 0, 'note');}
					if ($note == '') {
						$bulletin_rempli = 'no';
						if ($affiche_nom != 0) {
							//echo "<p><span class='bold'> $eleve_prenom[$j] $eleve_nom[$j] ";
							echo "<p style='border:1px solid black;'><span class='bold'>$eleve_prenom[$j] $eleve_nom[$j]";
							//echo "<br />\n";
							//echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'>bulletin simple dans une nouvelle page</a>)</span> :";
							echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'><img src='../images/icons/bulletin_simp.png' width='17' height='17' alt='bulletin simple dans une nouvelle page' title='bulletin simple dans une nouvelle page' /></a>)</span>&nbsp;:";
						}
						if ($affiche_mess_note != 0) {echo "<br /><br /><b>Moyennes non remplies</b> pour les mati�res suivantes&nbsp;: ";}
						$affiche_nom = 0;
						$affiche_mess_note = 0;
						//============================================
						// MODIF: boireaus
						// Pour les mati�res comme Histoire & G�o,...
						//echo "<br />--> " . $current_group["description"] . " (" . $current_group["classlist_string"] . ")  --  (";
						echo "<br />--> ".htmlentities($current_group["description"])." (" . $current_group["classlist_string"] . ")  --   (";
						//============================================
						$m=0;
						$virgule = 1;
						foreach ($current_group["profs"]["list"] as $login_prof) {
							$email = retourne_email($login_prof);
							$civilite_prof = $current_group["profs"]["users"][$login_prof]["civilite"];
							$nom_prof = $current_group["profs"]["users"][$login_prof]["nom"];
							$prenom_prof = $current_group["profs"]["users"][$login_prof]["prenom"];
							//echo "<a href='mailto:$email'>$prenom_prof $nom_prof</a>";

							if(!isset($tab_alerte_prof[$login_prof])) {
								$tab_alerte_prof[$login_prof]=array();
								$tab_alerte_prof[$login_prof]['civilite']=$civilite_prof;
								$tab_alerte_prof[$login_prof]['nom']=$nom_prof;
								$tab_alerte_prof[$login_prof]['prenom']=$prenom_prof;
								$tab_alerte_prof[$login_prof]['email']=$email;
							}

							if(!isset($tab_alerte_prof[$login_prof]['groupe'][$group_id])) {
								$tab_alerte_prof["$login_prof"]['groupe'][$group_id]['info']=$current_group["description"]." (".$current_group["classlist_string"].")";

								//echo "\$tab_alerte_prof[$login_prof]['groupe'][$group_id]['info']=".$tab_alerte_prof["$login_prof"]['groupe'][$group_id]['info']."<br />";
							}

							$tab_alerte_prof[$login_prof]['groupe'][$group_id]['moy_manquante'][]=strtoupper($eleve_nom[$j])." ".ucfirst(strtolower($eleve_prenom[$j]));
							//echo "\$tab_alerte_prof[$login_prof]['groupe'][$group_id]['moy_manquante'][]=".strtoupper($eleve_nom[$j])." ".ucfirst(strtolower($eleve_prenom[$j]))."<br />";

							//if($email!="") {
							if(($email!="")&&(check_mail($email))) {
								$sujet_mail="[Gepi]: Moyenne manquante: ".$eleve_nom[$j];
								$message_mail="Bonjour,\r\n\r\nCordialement";
								echo "<a href='mailto:$email?subject=$sujet_mail&amp;body=".rawurlencode($message_mail)."'>".ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof)."</a>";
							}
							else{
								echo ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof);
							}
							$m++;
							if ($m == count($current_group["profs"]["list"])) {$virgule = 0;}
							if ($virgule == 1) {echo ", ";}
						}
						echo ")\n";

						$temoin_note_app++;
					}
				}
				$i++;
			//Fin de la boucle mati�re
			}
		}


		if(($mode=="avis")||($mode=="tout")) {
			//
			//V�rification des avis des conseils de classe
			//
			$query_conseil = mysql_query("SELECT * FROM avis_conseil_classe WHERE (login = '$id_eleve[$j]' and periode = '$per')");
			//$avis = @mysql_result($query_conseil, 0, 'avis');
			$avis="";
			if(mysql_num_rows($query_conseil)>0) {$avis=mysql_result($query_conseil, 0, 'avis');}
			if ($avis == '') {
				$bulletin_rempli = 'no';
				if ($affiche_nom != 0) {
					//echo "<p><span class='bold'> $eleve_prenom[$j] $eleve_nom[$j]<br />\n";
					echo "<p style='border:1px solid black;'><span class='bold'>$eleve_prenom[$j] $eleve_nom[$j]";
					//echo "<br />\n";
					//echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'>bulletin simple dans une nouvelle page</a>)</span> :";
					echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'><img src='../images/icons/bulletin_simp.png' width='17' height='17' alt='bulletin simple dans une nouvelle page' title='bulletin simple dans une nouvelle page' /></a>)</span>&nbsp;:";
				}
				echo "<br /><br />\n";
				echo "<b>Avis du conseil de classe</b> non rempli !";
				$call_prof = mysql_query("SELECT u.login, u.nom, u.prenom FROM utilisateurs u, j_eleves_professeurs j WHERE (j.login = '$id_eleve[$j]' and j.id_classe='$id_classe' and u.login=j.professeur)");
				$nb_result = mysql_num_rows($call_prof);
				if ($nb_result != 0) {
					$login_prof = mysql_result($call_prof, 0, 'login');
					$email = retourne_email($login_prof);
					$nom_prof = mysql_result($call_prof, 0, 'nom');
					$prenom_prof = mysql_result($call_prof, 0, 'prenom');
					//echo " (<a href='mailto:$email'>$prenom_prof $nom_prof</a>)";
					//if($email!="") {
					if(($email!="")&&(check_mail($email))) {
						$sujet_mail="[Gepi]: Avis du conseil manquant: ".$id_eleve[$j];
						$message_mail="Bonjour,\r\n\r\nCordialement";
						echo "(<a href='mailto:$email?subject=$sujet_mail&amp;body=".rawurlencode($message_mail)."'>".ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof)."</a>)";
					}
					else{
						echo "(".ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof).")";
					}

				} else {
					echo " (pas de ".getSettingValue("gepi_prof_suivi").")";
				}

				$affiche_nom = 0;

				$temoin_avis++;

			}
		}


		if(($mode=="note_app")||($mode=="tout")||($mode=="tout_sauf_avis")) {
			//
			//V�rification des aid
			//
			$call_data = mysql_query("SELECT * FROM aid_config WHERE display_bulletin!='n' ORDER BY nom");
			$nb_aid = mysql_num_rows($call_data);
			$z=0;
			while ($z < $nb_aid) {
				$display_begin = @mysql_result($call_data, $z, "display_begin");
				$display_end = @mysql_result($call_data, $z, "display_end");
				if (($per >= $display_begin) and ($per <= $display_end)) {
					$indice_aid = @mysql_result($call_data, $z, "indice_aid");
					$type_note = @mysql_result($call_data, $z, "type_note");
					$call_data2 = mysql_query("SELECT * FROM aid_config WHERE indice_aid = '$indice_aid'");
					$nom_aid = @mysql_result($call_data2, 0, "nom");
					$aid_query = mysql_query("SELECT id_aid FROM j_aid_eleves WHERE (login='$id_eleve[$j]' and indice_aid='$indice_aid')");
					$aid_id = @mysql_result($aid_query, 0, "id_aid");
					if ($aid_id != '') {
						$aid_app_query = mysql_query("SELECT * FROM aid_appreciations WHERE (login='$id_eleve[$j]' AND periode='$per' and id_aid='$aid_id' and indice_aid='$indice_aid')");
						$query_resp = mysql_query("SELECT u.login, u.nom, u.prenom FROM utilisateurs u, j_aid_utilisateurs j WHERE (j.id_aid = '$aid_id' and u.login = j.id_utilisateur and j.indice_aid='$indice_aid')");
						$nb_prof = mysql_num_rows($query_resp);
						//
						// V�rification des appr�ciations
						//
						$aid_app = @mysql_result($aid_app_query, 0, "appreciation");
						if ($aid_app == '') {
							$bulletin_rempli = 'no';
							if ($affiche_nom != 0) {
								//echo "<p><span class='bold'> $eleve_prenom[$j] $eleve_nom[$j]<br />\n";
								echo "<p style='border:1px solid black;'><span class='bold'>$eleve_prenom[$j] $eleve_nom[$j]";
								//echo "<br />\n";
								//echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'>bulletin simple dans une nouvelle page</a>)</span>&nbsp;:";
								echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'><img src='../images/icons/bulletin_simp.png' width='17' height='17' alt='bulletin simple dans une nouvelle page' title='bulletin simple dans une nouvelle page' /></a>)</span>&nbsp;:";
							}
							echo "<br /><br />\n";
							echo "<b>Appr�ciation $nom_aid </b> non remplie (";
							$m=0;
							$virgule = 1;
							while ($m < $nb_prof) {
								$login_prof = @mysql_result($query_resp, $m, 'login');
								$email = retourne_email($login_prof);
								$nom_prof = @mysql_result($query_resp, $m, 'nom');
								$prenom_prof = @mysql_result($query_resp, $m, 'prenom');
								//echo "<a href='mailto:$email'>$prenom_prof $nom_prof</a>";
								//if($email!="") {
								if(($email!="")&&(check_mail($email))) {
									$sujet_mail="[Gepi]: Appreciation AID manquante: ".$eleve_nom[$j];
									$message_mail="Bonjour,\r\n\r\nCordialement";
									echo "<a href='mailto:$email?subject=$sujet_mail&amp;body=".rawurlencode($message_mail)."'>".ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof)."</a>";
								}
								else{
									echo ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof);
								}
								$m++;
								if ($m == $nb_prof) {$virgule = 0;}
								if ($virgule == 1) {echo ", ";}
							}
							echo ")\n";
							$affiche_nom = 0;

							$temoin_aid++;
						}
						//
						// V�rification des moyennes
						//
						$periode_query = mysql_query("SELECT * FROM periodes WHERE id_classe = '$id_classe'");
						$periode_max = mysql_num_rows($periode_query);
						if ($type_note == 'last') {$last_periode_aid = min($periode_max,$display_end);}
						if (($type_note=='every') or (($type_note=='last') and ($per == $last_periode_aid))) {
							$aid_note = @mysql_result($aid_app_query, 0, "note");
							$aid_statut = @mysql_result($aid_app_query, 0, "statut");


							if (($aid_note == '') or ($aid_statut == 'other')) {
								$bulletin_rempli = 'no';
								if ($affiche_nom != 0) {
									//echo "<p><span class='bold'> $eleve_prenom[$j] $eleve_nom[$j]<br />\n";
									echo "<p style='border:1px solid black;'><span class='bold'>$eleve_prenom[$j] $eleve_nom[$j]";
									//echo "<br />\n";
									//echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'>bulletin simple dans une nouvelle page)</a></span>&nbsp;:";
									echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'><img src='../images/icons/bulletin_simp.png' width='17' height='17' alt='bulletin simple dans une nouvelle page' title='bulletin simple dans une nouvelle page' /></a>)</span>&nbsp;:";
								}
								echo "<br /><br />\n";
								echo "<b>Note $nom_aid </b>non remplie (";
								$m=0;
								$virgule = 1;
								while ($m < $nb_prof) {
									$login_prof = @mysql_result($query_resp, $m, 'login');
									$email = retourne_email($login_prof);
									$nom_prof = @mysql_result($query_resp, $m, 'nom');
									$prenom_prof = @mysql_result($query_resp, $m, 'prenom');
									//echo "<a href='mailto:$email'>$prenom_prof $nom_prof</a>";
									//if($email!="") {
									if(($email!="")&&(check_mail($email))) {
										$sujet_mail="[Gepi]: Moyenne AID manquante: ".$eleve_nom[$j];
										$message_mail="Bonjour,\r\n\r\nCordialement";
										echo "<a href='mailto:$email?subject=$sujet_mail&amp;body=".rawurlencode($message_mail)."'>".ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof)."</a>";
									}
									else{
										echo ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof);
									}
									$m++;
									if ($m == $nb_prof) {$virgule = 0;}
									if ($virgule == 1) {echo ", ";}
								}
								echo ")\n";
								$affiche_nom = 0;

								$temoin_aid++;
							}
						}
					}
				}
				$z++;
			}
		}

		if(($mode=="abs")||($mode=="tout")||($mode=="tout_sauf_avis")) {
			//
			//V�rification des absences
			//
			$abs_query = mysql_query("SELECT * FROM absences WHERE (login='$id_eleve[$j]' AND periode='$per')");
			$abs1 = @mysql_result($abs_query, 0, "nb_absences");
			$abs2 = @mysql_result($abs_query, 0, "non_justifie");
			$abs3 = @mysql_result($abs_query, 0, "nb_retards");
			if (($abs1 == '') or ($abs2 == '') or ($abs3 == '')) {
				$bulletin_rempli = 'no';
				if ($affiche_nom != 0) {
					//echo "<p><span class='bold'> $eleve_prenom[$j] $eleve_nom[$j]<br />\n";
					echo "<p style='border:1px solid black;'><span class='bold'>$eleve_prenom[$j] $eleve_nom[$j]";
					//echo "<br />\n";
					//echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'>bulletin simple dans une nouvelle page)</a></span>&nbsp;:";
					echo "(<a href='../prepa_conseil/edit_limite.php?id_classe=$id_classe&amp;periode1=$per&amp;periode2=$per&amp;choix_edit=2&amp;login_eleve=$id_eleve[$j]' target='bull'><img src='../images/icons/bulletin_simp.png' width='17' height='17' alt='bulletin simple dans une nouvelle page' title='bulletin simple dans une nouvelle page' /></a>)</span>&nbsp;:";
				}
				echo "<br /><br />\n";
				echo "<b>Rubrique \"Absences\" </b> non remplie. (";
				$query_resp = mysql_query("SELECT u.login, u.nom, u.prenom FROM utilisateurs u, j_eleves_cpe j WHERE (j.e_login = '$id_eleve[$j]' AND u.login = j.cpe_login)");
				$nb_prof = mysql_num_rows($query_resp);
				$m=0;
				$virgule = 1;
				while ($m < $nb_prof) {
					$login_prof = @mysql_result($query_resp, $m, 'login');
					$email = retourne_email($login_prof);
					$nom_prof = @mysql_result($query_resp, $m, 'nom');
					$prenom_prof = @mysql_result($query_resp, $m, 'prenom');
					//echo "<a href='mailto:$email'>$prenom_prof $nom_prof</a>";
					//if($email!="") {
					if(($email!="")&&(check_mail($email))) {
						$sujet_mail="[Gepi]: Absences non remplies: ".$id_eleve[$j];
						$message_mail="Bonjour,\r\n\r\nCordialement";
						echo "<a href='mailto:$email?subject=$sujet_mail&amp;body=".rawurlencode($message_mail)."'>".ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof)."</a>";
					}
					else{
						echo ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof);
					}
					$m++;
					if ($m == $nb_prof) {$virgule = 0;}
					if ($virgule == 1) {echo ", ";}
				}
				echo ")\n";
				$affiche_nom = 0;

				$temoin_abs++;
			}
		}


		if($gepiSettings['active_mod_ects'] == 'y' && (($mode=="ects")||($mode=="tout")||($mode=="tout_sauf_avis"))) {
			//
			//V�rification des ECTS
			//

            // On commence par regarder si l'�l�ve a des groupes qui ouvrent droit � des ECTS.
            $query_groupes_ects = mysql_query("SELECT jgc.* FROM j_groupes_classes jgc, j_eleves_groupes jeg WHERE jgc.saisie_ects = '1' AND jgc.id_classe = '$id_classe' AND jgc.id_groupe = jeg.id_groupe AND jeg.login = '".$id_eleve[$j]."' AND jeg.periode = '$per'");
            if (mysql_num_rows($query_groupes_ects) > 0) {
                $temoin_has_ects = true;
                $query_conseil = mysql_query("SELECT ec.* FROM ects_credits ec, eleves e WHERE ec.id_eleve = e.id_eleve AND e.login = '$id_eleve[$j]' AND num_periode = '$per'");
                $nb = mysql_num_rows($query_conseil);
                if ($nb == 0) {
                    $bulletin_rempli = 'no';
                    if ($affiche_nom != 0) {
                        echo "<p style='border:1px solid black;'><span class='bold'>$eleve_prenom[$j] $eleve_nom[$j]</span>";
                    }
                    echo "<br /><br />\n";
                    echo "<b>Cr�dits ECTS</b> non remplis !";

                    // On r�cup�re le prof principal, si celui-ci est autoris� � saisir les ECTS
                    if ($gepiSettings['GepiAccesSaisieEctsPP'] == 'yes') {
                        $call_prof = mysql_query("SELECT u.login, u.nom, u.prenom FROM utilisateurs u, j_eleves_professeurs j WHERE (j.login = '$id_eleve[$j]' and j.id_classe='$id_classe' and u.login=j.professeur)");
                        $nb_result = mysql_num_rows($call_prof);
                        if ($nb_result != 0) {
                            $login_prof = mysql_result($call_prof, 0, 'login');
                            $email = retourne_email($login_prof);
                            $nom_prof = mysql_result($call_prof, 0, 'nom');
                            $prenom_prof = mysql_result($call_prof, 0, 'prenom');
                            //echo " (<a href='mailto:$email'>$prenom_prof $nom_prof</a>)";
                            //if($email!="") {
                            if(($email!="")&&(check_mail($email))) {
								$sujet_mail="[Gepi]: ECTS non remplis: ".$eleve_nom[$j];
								$message_mail="Bonjour,\r\n\r\nCordialement";
								echo " (<a href='mailto:$email?subject=$sujet_mail&amp;body=".rawurlencode($message_mail)."'>".ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof)."</a>)";
                            }
                            else{
                                echo " (".ucfirst(strtolower($prenom_prof))." ".strtoupper($nom_prof).")";
                            }

                        } else {
                            echo " (pas de ".getSettingValue("gepi_prof_suivi").")";
                        }
                    }
                    $affiche_nom = 0;
                    $temoin_ects++;
                }
            }
		}

		$j++;
		//Fin de la boucle �l�ve

		$cpt_i++;

		//flush();

	}

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	$tab_num_mail=array();
	if(count($tab_alerte_prof)>0) {
		$num=0;

		//echo "<div style='border: 1px solid black'>";
		echo "<p class='bold'>R�capitulatif&nbsp;:</p>\n";
		echo "<table class='boireaus' summary=\"Courriels\">\n";
		$alt=1;

		//$tab_alerte_prof[$login_prof]['groupe'][$group_id]['app_manquante'][]
		foreach($tab_alerte_prof as $login_prof => $tab_prof) {
			$alt=$alt*(-1);

			$info_prof=$tab_alerte_prof[$login_prof]['civilite']." ".casse_mot($tab_alerte_prof[$login_prof]['nom'],'maj')." ".casse_mot($tab_alerte_prof[$login_prof]['prenom'],'majf2');

			$message="Bonjour(soir) ".$info_prof.",\n\nDes moyennes et/ou appr�ciations ne sont pas remplies:\n";
			foreach($tab_prof['groupe'] as $group_id => $tab_group) {
				if(isset($tab_group['app_manquante'])) {
					$message.="Appr�ciation(s) manquante(s) en ".$tab_alerte_prof[$login_prof]['groupe'][$group_id]['info']." pour ";
					//echo count($tab_alerte_prof[$login_prof]['groupe'][$group_id]['app_manquante']);
					for($loop=0;$loop<count($tab_alerte_prof[$login_prof]['groupe'][$group_id]['app_manquante']);$loop++) {
						if($loop>0) {$message.=", ";}
						//$message.=$tab_group['app_manquante'][$loop];
						$message.=$tab_alerte_prof[$login_prof]['groupe'][$group_id]['app_manquante'][$loop];
					}
					$message.=".\n";
				}

				if(isset($tab_group['moy_manquante'])) {
					$message.="Moyenne(s) manquante(s) en ".$tab_group['info']." pour ";
					for($loop=0;$loop<count($tab_group['moy_manquante']);$loop++) {
						if($loop>0) {$message.=", ";}
						//$message.=$tab_group['moy_manquante'][$loop];
						$message.=$tab_alerte_prof[$login_prof]['groupe'][$group_id]['moy_manquante'][$loop];
					}
					$message.=".\n";
				}

			}

			$message.="\nLorsqu'un �l�ve n'a pas de note, veuillez saisir un tiret '-' pour signaler qu'il n'y a pas d'oubli de saisie de votre part.\nEn revanche, s'il s'agit d'une erreur d'affectation, vous disposez, en mode Visualisation d'un carnet de notes, d'un lien 'Signaler des erreurs d affectation' pour alerter l'administrateur Gepi sur un probl�me d'affectation d'�l�ves.\n";

			$message.="\nJe vous serais reconnaissant(e) de bien vouloir les remplir rapidement.\n\nD'avance merci.\n-- \n".civ_nom_prenom($_SESSION['login']);

			echo "<tr class='lig$alt'>\n";
			echo "<td>\n";
			if($tab_alerte_prof[$login_prof]['email']!="") {
				if(check_mail($tab_alerte_prof[$login_prof]['email'])) {
					$tab_num_mail[]=$num;
					$sujet_mail="[Gepi]: Appreciations et/ou moyennes manquantes";
					echo "<a href='mailto:".$tab_alerte_prof[$login_prof]['email']."?subject=$sujet_mail&amp;body=".rawurlencode($message)."'>".$info_prof."</a>";
					echo "<input type='hidden' name='sujet_$num' id='sujet_$num' value=\"$sujet_mail\" />\n";
					echo "<input type='hidden' name='mail_$num' id='mail_$num' value=\"".$tab_alerte_prof[$login_prof]['email']."\" />\n";
				}
			}
			else {
				echo $info_prof;
			}
			//echo "<br />";
			echo "</td>\n";
			echo "<td rowspan='2'>\n";
			//echo "<textarea id='message_$num' cols='50' rows='5'>$message</textarea>\n";
			echo "<textarea name='message_$num' id='message_$num' cols='50' rows='5'>$message</textarea>\n";
			//echo "<input type='hidden' name='message_$num' id='message_$num' value=\"".rawurlencode($message)."\" />\n";
			//echo "<input type='hidden' name='message_$num' id='message_$num' value=\"".rawurlencode(ereg_replace("\\\n",'_NEWLINE_',$message))."\" />\n";

			echo "</td>\n";
			if($ver_periode[$per]=="P") {
				echo "<td rowspan='2'>\n";
				$ajout="";
				if(count($tab_prof['groupe'])==1) {
					foreach($tab_prof['groupe'] as $group_id => $tab_group) {
						$ajout="&amp;periode=$per&amp;id_groupe=$group_id";
						break;
					}
				}
				echo "<a href='autorisation_exceptionnelle_saisie_app.php?id_classe=$id_classe".$ajout."' target='_blank'>Autoriser exceptionnellement la proposition de saisie bien que la p�riode soit partiellement close.</a>";
				echo "</td>\n";
			}
			echo "</tr>\n";

			echo "<tr class='lig$alt'>\n";
			echo "<td>\n";
			if(!in_array($num, $tab_num_mail)) {
				echo "<span style='color: red;'>Pas de mail</span>";
			}
			else {
				echo "<span id='mail_envoye_$num'><a href='#' onclick=\"envoi_mail($num);return false;\">Envoyer</a></span>";
			}
			echo "</td>\n";
			echo "</tr>\n";

			//echo "<a href='#' onclick=\"envoi_mail($num);return false;\">Envoyer</a>";
			//echo "<br />\n";
			$num++;
		}
		echo "</table>\n";
		//echo "</div>";
	}

	//echo "<input type='hidden' name='csrf_alea' id='csrf_alea' value='".$_SESSION['gepi_alea']."' />\n";
	echo add_token_field(true);

	echo "<script type='text/javascript'>
	// <![CDATA[
	function envoi_mail(num) {
		csrf_alea=document.getElementById('csrf_alea').value;
		destinataire=document.getElementById('mail_'+num).value;
		sujet_mail=document.getElementById('sujet_'+num).value;
		message=document.getElementById('message_'+num).value;
		//alert(message);
		//new Ajax.Updater($('mail_envoye_'+num),'envoi_mail.php?destinataire='+destinataire+'&sujet_mail='+sujet_mail+'&message='+message,{method: 'get'});
		new Ajax.Updater($('mail_envoye_'+num),'envoi_mail.php?destinataire='+destinataire+'&sujet_mail='+sujet_mail+'&message='+escape(message)+'&csrf_alea='+csrf_alea,{method: 'get'});
	}
	//]]>
</script>\n";



	//if ($bulletin_rempli == 'yes') {
	if (($bulletin_rempli == 'yes')&&(($mode=='tout')||($mode=='tout_sauf_avis'))) {
		echo "<p class='bold'>Toutes les rubriques des bulletins de cette classe ont �t� renseign�es, vous pouvez proc�der � l'impression finale.</p>\n";
		echo "<ul><li><p class='bold'>Acc�der directement au verrouillage de la p�riode en <a href='verrouillage.php?classe=$id_classe&periode=$per&action=rien'>cliquant ici.</a></p></li>\n";
		echo "<li><p class='bold'>Acc�der directement au verrouillage de la p�riode en <a href='verrouillage.php?classe=$id_classe&periode=$per&action=retour'>cliquant ici.</a> puis revenir � la page outil de v�rification.</p></li>\n";
		echo "<li><p class='bold'>Acc�der directement au verrouillage de la p�riode en <a href='verrouillage.php?classe=$id_classe&periode=$per&action=imprime_bull'>cliquant ici.</a> puis aller � la page impression des bulletins.</p></li>\n";
		//echo "<li><p class='bold'>Acc�der directement au verrouillage de la p�riode en <a href='verrouillage.php?classe=$id_classe&periode=$per&action=imprime_html'>cliquant ici.</a> puis aller � la page impression des bulletins HTML.</p></li>\n";
		//echo "<li><p class='bold'>Acc�der directement au verrouillage de la p�riode en <a href='verrouillage.php?classe=$id_classe&periode=$per&action=imprime_pdf'>cliquant ici.</a> puis aller � la page impression des bulletins PDF.</p></li></ul>\n";
	} elseif(($temoin_note_app==0)&&($temoin_aid==0)&&($mode=='note_app')) {
		echo "<p class='bold'>Toutes les moyennes et appr�ciations des bulletins de cette classe ont �t� renseign�es.</p>\n";
	} elseif(($temoin_avis==0)&&($mode=='avis')) {
		echo "<p class='bold'>Tous les avis de conseil de classe des bulletins de cette classe ont �t� renseign�s.</p>\n";
	} elseif(($temoin_abs==0)&&($mode=='abs')) {
		echo "<p class='bold'>Toutes les absences et retards des bulletins de cette classe ont �t� renseign�s.</p>\n";
    }elseif ($gepiSettings['active_mod_ects'] == 'y' && $temoin_ects == 0 && $mode=='ects' && $temoin_has_ects) {
        echo "<p class='bold'>Tous les cr�dits ECTS de cette classe ont �t� renseign�s.</p>\n";
	} else{
		echo "<br /><p class='bold'>*** Fin des v�rifications. ***</p>\n";
		/*
		echo "<ul><li><p class='bold'>Acc�der directement au verrouillage de la p�riode en <a href='verrouillage.php?classe=$id_classe&periode=$per&action=rien'>cliquant ici.</a></p></li>";
		echo "<li><p class='bold'>Acc�der directement au verrouillage de la p�riode en <a href='verrouillage.php?classe=$id_classe&periode=$per&action=retour'>cliquant ici.</a> puis revenir � la page outil de v�rification.</p></li>";
		echo "<li><p class='bold'>Acc�der directement au verrouillage de la p�riode en <a href='verrouillage.php?classe=$id_classe&periode=$per&action=imprime_html'>cliquant ici.</a> puis aller � la page impression des bulletins HTML.</p></li>";
		echo "<li><p class='bold'>Acc�der directement au verrouillage de la p�riode en <a href='verrouillage.php?classe=$id_classe&periode=$per&action=imprime_pdf'>cliquant ici.</a> puis aller � la page impression des bulletins PDF.</p></li></ul>";
			*/
	}
}
require("../lib/footer.inc.php");
?>