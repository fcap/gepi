<?php
/*
 * $Id$
 *
 * Copyright 2001, 2007 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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
$resultat_session = resumeSession();
if ($resultat_session == 'c') {
    header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
    die();
} else if ($resultat_session == '0') {
    header("Location: ../logout.php?auto=1");
    die();
};

if (!checkAccess()) {
    header("Location: ../logout.php?auto=1");
    die();
}

$id_classe = isset($_POST['id_classe']) ? $_POST['id_classe'] : (isset($_GET['id_classe']) ? $_GET['id_classe'] : NULL);

if (isset($id_classe)) {
	// On regarde si le type est correct :
	if (!is_numeric($id_classe)) {
		tentative_intrusion("2", "Changement de la valeur de id_classe pour un type non num�rique.");
		echo "Erreur.";
		require ("../lib/footer.inc.php");
		die();
	}
	// On teste si le professeur a le droit d'acc�der � cette classe
	if ($_SESSION['statut'] == "professeur" AND getSettingValue("GepiAccesMoyennesProfToutesClasses") != "yes") {
		$test = mysql_num_rows(mysql_query("SELECT jgc.* FROM j_groupes_classes jgc, j_groupes_professeurs jgp WHERE (jgp.login='".$_SESSION['login']."' AND jgc.id_groupe = jgp.id_groupe AND jgc.id_classe = '".$id_classe."')"));
		if ($test == "0") {
			tentative_intrusion("2", "Tentative d'acc�s par un prof � une classe dans laquelle il n'enseigne pas, sans en avoir l'autorisation.");
			echo "Vous ne pouvez pas acc�der � cette classe car vous n'y �tes pas professeur !";
			require ("../lib/footer.inc.php");
			die();
		}
	}
}

//**************** EN-TETE *****************
$titre_page = "Visualisation des notes";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
?>
<p class=bold><a href='../accueil.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Accueil</a>
<?php
if (isset($id_classe)) {
    $current_eleve_classe = sql_query1("SELECT classe FROM classes WHERE id='$id_classe'");
    echo " | <a href=\"index2.php\">Choisir une autre classe</a>";


	// ===========================================
	// Ajout lien classe pr�c�dente / classe suivante
	if($_SESSION['statut']=='scolarite'){
		$sql = "SELECT DISTINCT c.id,c.classe FROM classes c, periodes p, j_scol_classes jsc WHERE p.id_classe = c.id  AND jsc.id_classe=c.id AND jsc.login='".$_SESSION['login']."' ORDER BY classe";
	}
	elseif($_SESSION['statut']=='professeur'){
		$sql="SELECT DISTINCT c.id,c.classe FROM classes c, periodes p, j_groupes_classes jgc, j_groupes_professeurs jgp WHERE p.id_classe = c.id AND jgc.id_classe=c.id AND jgp.id_groupe=jgc.id_groupe AND jgp.login='".$_SESSION['login']."' ORDER BY c.classe";
	}
	elseif($_SESSION['statut']=='cpe'){
		$sql="SELECT DISTINCT c.id,c.classe FROM classes c, periodes p, j_eleves_classes jec, j_eleves_cpe jecpe WHERE
			p.id_classe = c.id AND
			jec.id_classe=c.id AND
			jec.periode=p.num_periode AND
			jecpe.e_login=jec.login AND
			jecpe.cpe_login='".$_SESSION['login']."'
			ORDER BY classe";
	}
	$res_class_tmp=mysql_query($sql);
	if(mysql_num_rows($res_class_tmp)>0){
		$id_class_prec=0;
		$id_class_suiv=0;
		$temoin_tmp=0;
		while($lig_class_tmp=mysql_fetch_object($res_class_tmp)){
			if($lig_class_tmp->id==$id_classe){
				$temoin_tmp=1;
				if($lig_class_tmp=mysql_fetch_object($res_class_tmp)){
					$id_class_suiv=$lig_class_tmp->id;
				}
				else{
					$id_class_suiv=0;
				}
			}
			if($temoin_tmp==0){
				$id_class_prec=$lig_class_tmp->id;
			}
		}
	}
	// =================================
	if(isset($id_class_prec)){
		if($id_class_prec!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_prec'>Classe pr�c�dente</a>";}
	}
	if(isset($id_class_suiv)){
		if($id_class_suiv!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_suiv'>Classe suivante</a>";}
	}
	//fin ajout lien classe pr�c�dente / classe suivante
	// ===========================================


	echo " | Classe : ".$current_eleve_classe."</p>";
    echo "<form target=\"_blank\" name=\"visu_toutes_notes\" method=\"post\" action=\"visu_toutes_notes.php\">\n";
    echo "<table border=\"1\" cellspacing=\"1\" cellpadding=\"10\"><tr>";
    echo "<td valign=\"top\"><b>Choisissez&nbsp;la&nbsp;p�riode&nbsp;:&nbsp;</b><br />\n";
    include "../lib/periodes.inc.php";
    $i="1";
    while ($i < $nb_periode) {
        echo "<br />\n<input type=\"radio\" name=\"num_periode\" value=\"$i\" ";
        if ($i == 1) echo "checked ";
        echo "/>&nbsp;".ucfirst($nom_periode[$i]);
    $i++;
    }
   echo "<br />\n<input type=\"radio\" name=\"num_periode\" value=\"annee\" />&nbsp;Ann�e enti�re";
   echo "\n</td><td valign=\"top\">";

    echo "<b>Param�tres d'affichage</b><br />\n";
	echo "<input type=\"hidden\" name=\"id_classe\" value=\"".$id_classe."\" />";

	echo "<table border='0' width='100%'>\n";
	echo "<tr>\n";
	echo "<td>\n";

		echo "<table border='0'>\n";
		echo "<tr>\n";
		echo "<td>Largeur en pixel du tableau : </td>\n";
		echo "<td><input type=text name=larg_tab size=3 value=\"680\" /></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>Bords en pixel du tableau : </td>\n";
		echo "<td><input type=text name=bord size=3 value=\"1\" /></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td>Couleurs de fond des lignes altern�es : </td>\n";
		echo "<td><input type=\"checkbox\" name=\"couleur_alterne\" checked /></td>\n";
		echo "</tr>\n";
		echo "</table>\n";

	echo "</td>\n";
	echo "<td>\n";

		echo "<table border='0'>\n";
		echo "<tr>\n";
		echo "<td><input type=\"checkbox\" name=\"aff_abs\" checked /></td>\n";
		echo "<td>Afficher les absences</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td><input type=\"checkbox\" name=\"aff_reg\" checked /></td>\n";
		echo "<td>Afficher le r�gime</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<td><input type=\"checkbox\" name=\"aff_doub\" checked /></td>\n";
		echo "<td>Afficher la mention doublant</td>\n";
		echo "</tr>\n";

		$affiche_rang = sql_query1("SELECT display_rang FROM classes WHERE id='".$id_classe."'");
		// On teste la pr�sence d'au moins un coeff pour afficher la colonne des coef
		$test_coef = mysql_num_rows(mysql_query("SELECT coef FROM j_groupes_classes WHERE (id_classe='".$id_classe."' and coef > 0)"));

		if (($affiche_rang == 'y') and ($test_coef != 0)) {
			echo "<tr>\n";
			echo "<td><input type=\"checkbox\" name=\"aff_rang\" checked /></td>\n";
			echo "<td>Afficher le rang des �l�ves</td>\n";
			echo "</tr>\n";
		}

		echo "<tr>\n";
		echo "<td><input type=\"checkbox\" name=\"aff_date_naiss\" /></td>\n";
		echo "<td>Afficher la date de naissance des �l�ves</td>\n";
		echo "</tr>\n";
		echo "</table>\n";

	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

/*
    echo "<br />\nLargeur en pixel du tableau : <input type=text name=larg_tab size=3 value=\"680\" />";
    echo "<br />\nBords en pixel du tableau : <input type=text name=bord size=3 value=\"1\" />";
    echo "<br />\nCouleurs de fond des lignes altern�es : <input type=\"checkbox\" name=\"couleur_alterne\" checked />";
    echo "<br /><br /><table cellpadding=\"3\"><tr><td>\n<input type=\"checkbox\" name=\"aff_abs\" checked />Afficher les absences</td>
    <td><input type=\"checkbox\" name=\"aff_reg\" checked /> Afficher le r�gime</td>
    <td><input type=\"checkbox\" name=\"aff_doub\" checked />Afficher la mention doublant</td>";
    $affiche_rang = sql_query1("SELECT display_rang FROM classes WHERE id='".$id_classe."'");
    // On teste la pr�sence d'au moins un coeff pour afficher la colonne des coef
    $test_coef = mysql_num_rows(mysql_query("SELECT coef FROM j_groupes_classes WHERE (id_classe='".$id_classe."' and coef > 0)"));


    if (($affiche_rang == 'y') and ($test_coef != 0)) {
       echo "<td><input type=\"checkbox\" name=\"aff_rang\" checked />Afficher le rang des �l�ves</td>";
    }
    echo "</tr></table>";
*/
    echo "<br />\n<center><input type=\"submit\" name=\"ok\" value=\"Valider\" /></center>";
    echo "<br />\n<span class='small'>Remarque : le tableau des notes s'affiche sans en-t�te et dans une nouvelle page. Pour revenir � cet �cran, il vous suffit de fermer la fen�tre du tableau des notes.</span>";
    if ($_SESSION['statut'] == "professeur"
	AND getSettingValue("GepiAccesMoyennesProfToutesClasses") != "yes"
	AND getSettingValue("GepiAccesMoyennesProfToutesTousEleves") != "yes") {
		echo "<br />\n<span class='small'>Si vous n'enseignez pas � des classes enti�res, seuls les �l�ves auxquels vous enseignez appara�tront dans la liste, et les moyennes calcul�s ne prendront en compte que les �l�ves affich�s.</span>";
	}
    echo "</td></tr>\n</table>\n</form>\n";
} else {
    //echo "</p><b>Visualiser les notes par classe :</b><br />";
    echo "</p><b>Visualiser les moyennes par classe :</b><br />";
    //$appel_donnees = mysql_query("SELECT DISTINCT c.* FROM classes c, periodes p WHERE p.id_classe = c.id  ORDER BY classe");
    //$appel_donnees = mysql_query("SELECT DISTINCT c.* FROM classes c, periodes p, j_scol_classes jsc WHERE p.id_classe = c.id  AND jsc.id_classe=c.id AND jsc.login='".$_SESSION['login']."' ORDER BY classe");
	if($_SESSION['statut']=='scolarite'){
		$appel_donnees = mysql_query("SELECT DISTINCT c.* FROM classes c, periodes p, j_scol_classes jsc WHERE p.id_classe = c.id  AND jsc.id_classe=c.id AND jsc.login='".$_SESSION['login']."' ORDER BY classe");
	}
	elseif($_SESSION['statut'] == 'professeur' and getSettingValue("GepiAccesMoyennesProfToutesClasses") != "yes"){
		$appel_donnees = mysql_query("SELECT DISTINCT c.* FROM classes c, periodes p, j_groupes_classes jgc, j_groupes_professeurs jgp WHERE p.id_classe = c.id AND jgc.id_classe=c.id AND jgp.id_groupe=jgc.id_groupe AND jgp.login='".$_SESSION['login']."' ORDER BY c.classe");
	}
	elseif($_SESSION['statut'] == 'professeur' and getSettingValue("GepiAccesMoyennesProfToutesClasses") == "yes") {
		$appel_donnees = mysql_query("SELECT DISTINCT c.* FROM classes c  ORDER BY c.classe");
	}
	elseif($_SESSION['statut']=='cpe'){
		$appel_donnees=mysql_query("SELECT DISTINCT c.* FROM classes c, periodes p, j_eleves_classes jec, j_eleves_cpe jecpe WHERE
			p.id_classe = c.id AND
			jec.id_classe=c.id AND
			jec.periode=p.num_periode AND
			jecpe.e_login=jec.login AND
			jecpe.cpe_login='".$_SESSION['login']."'
			ORDER BY classe");
	}
    $lignes = mysql_num_rows($appel_donnees);

	if($lignes==0){
		echo "<p>Aucune classe ne vous est attribu�e.<br />Contactez l'administrateur pour qu'il effectue le param�trage appropri� dans la Gestion des classes.</p>\n";
	}
	else{
		$i = 0;
		$nb_class_par_colonne=round($lignes/3);
			//echo "<table width='100%' border='1'>\n";
			echo "<table width='100%'>\n";
			echo "<tr valign='top' align='center'>\n";
			echo "<td align='left'>\n";
		while($i < $lignes){
		$id_classe = mysql_result($appel_donnees, $i, "id");
		$display_class = mysql_result($appel_donnees, $i, "classe");
		if(($i>0)&&(round($i/$nb_class_par_colonne)==$i/$nb_class_par_colonne)){
			echo "</td>\n";
			//echo "<td style='padding: 0 10px 0 10px'>\n";
			echo "<td align='left'>\n";
		}
		echo "<a href='index2.php?id_classe=$id_classe'>".ucfirst($display_class)."</a><br />\n";
		$i++;
		}
		echo "</table>\n";
	}
}
require("../lib/footer.inc.php");
?>