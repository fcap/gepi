<?php

/**
 *
 *
 * @version $Id$
 *
 * Copyright 2001, 2002 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Julien Jocal
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

// ========== Initialisation =============

$titre_page = "G�rer les groupes de l'EdT<br />El�ves";
$affiche_connexion = 'yes';
$niveau_arbo = 1;

// Initialisations files
require_once("../lib/initialisations.inc.php");

// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
   header("Location:../utilisateurs/mon_compte.php?change_mdp=yes&retour=accueil#changemdp");
   die();
} else if ($resultat_session == '0') {
    header("Location: ../logout.php?auto=1");
    die();
}

// S�curit�
if (!checkAccess()) {
    header("Location: ./logout.php?auto=2");
    die();
}

// ===================== fin de l'initialisation ================

// ============================== initialisation des VARIABLES ==============================

$id_gr = isset($_GET["id_gr"]) ? $_GET["id_gr"] : NULL;
$classe_e = isset($_GET["cla"]) ? $_GET["cla"] : NULL;
$id_eleve = isset($_GET["id_eleve"]) ? $_GET["id_eleve"] : NULL;
$action = isset($_GET["action"]) ? $_GET["action"] : NULL;
$aff_classes_g = $aff_classes_m = $aff_gr_d = $aff_entete = NULL;

// ============================== fin de l'initialisation des variables =====================

// En haut, on affiche les informations sur ce groupe
$query_i = mysql_query("SELECT * FROM edt_gr_nom WHERE id = '".$id_gr."'") OR trigger_error('Ce groupe n\'existe pas !', E_USER_ERROR);
$rep_gr = mysql_fetch_array($query_i);

// Et on ajoute le nom des professeurs qui le composent
// $query_p = mysql_query("SELECT login FROM edt_gr_utilisateurs WHERE id_gr_nom = '".$id_gr."'");
// $rep_p = mysql_fetch_array($query_p);

$aff_entete = '<p style="color: brown; border: 1px solid brown; padding: 2px;">'.$rep_gr["nom"].' :</p>'."\n";

// On traite les entr�es dans le groupe
if ($id_eleve != NULL AND $action != 'del_eleve_gr') {
	// On v�rifie s'il s'agit de la classe enti�re
	if ($id_eleve == "tous") {
		// la classe $classe_e doit alors �tre entr�e enti�rement dans ce groupe
		echo 'Cette fonctionnalit� n\'est pas encore pr�te, d�sol�';

	}else{

		// Un seul �l�ve est appel� : $id_eleve
		if (is_numeric($id_eleve)) {

			$sql_el = "INSERT INTO edt_gr_eleves (id, id_gr_nom, id_eleve) VALUES ('', '".$id_gr."', '".$id_eleve."')";
			$query_el = mysql_query($sql_el) OR trigger_error('Impossible d\'enregistrer cet �l�ve.', E_USER_ERROR);
			//echo $sql_el;
			// On v�rifie si sa classe est d�j� enregistr�e dans la base sinon on l'enregistre
			$id_classe_ele = get_class_from_ele_login(get_login_eleve($id_eleve));
			$test = mysql_query("SELECT id_classe, id FROM edt_gr_classes WHERE id_gr_nom = '".$id_gr."' LIMIT 1");
			if (mysql_num_rows($test) >= 1) {
				// On ajoute une classe dans la ligne ci-dessus
				$classes = mysql_fetch_array($test);
				$test2 = explode("|", $classes["id_classe"]);
				$up = 'oui';

				for($a = 0; $a < count($test2); $a++){
					if ($test2[$a] == $id_classe_ele["id0"]) {
						$up = 'non';

					}
				}
				if ($up == 'oui') {
					$ajout = $classes["id_classe"].$id_classe_ele["id0"].'|';
					$update = mysql_query("UPDATE edt_gr_classes SET id_classe = '".$ajout."' WHERE id = '".$test["id"]."'");
				}

			}else{

				$enregistre = mysql_query("INSERT INTO edt_gr_classes (id, id_gr_nom, id_classe) VALUES ('', '".$id_gr."', '".$id_classe_ele["id0"]."|')");

			}
		}

	}
} // if ($id_eleve != NULL)
elseif($action == "del_eleve_gr"){
	// Il est demand� d'effacer un �l�ve d'un groupe
	if (is_numeric($id_eleve) AND is_numeric($id_gr)) {
		// Apr�s cette petite v�rfication, on efface
		$sql_del = "DELETE FROM edt_gr_eleves WHERE id_gr_nom = '".$id_gr."' AND id_eleve = '".$id_eleve."' LIMIT 1";
		$query_del = mysql_query($sql_del) OR trigger_error('Impossible d\'effacer cet �l�ve !', E_USER_ERROR);

	}
}


// On affiche les trois colonnes :
// � droite : la liste actuelle des �l�ves de ce gr
// � gauche la liste des classes
// au milieu, la liste des �l�ves d'une classe

// ------------------------------------------- DIV de gauche ------------------------------------------ //

// Affichage de la liste des classes par $aff_classes_g

	$req_liste_classe = mysql_query("SELECT id, classe FROM classes ORDER BY classe");
	$nbre_classe = mysql_num_rows($req_liste_classe);

	for($a=0; $a<$nbre_classe; $a++) {
		$liste_classe[$a]["id"] = mysql_result($req_liste_classe, $a, "id");
		$liste_classe[$a]["classe"] = mysql_result($req_liste_classe, $a, "classe");

		$aff_classes_g .= "
			<tr>
				<td style=\"width: 196px;\">
					<a href=\"./edt_liste_eleves.php?id_gr=".$id_gr."&amp;cla=".$liste_classe[$a]["id"]."\">El�ves de la ".$liste_classe[$a]["classe"]."</a>
				</td>
			</tr>\n";
	}

// ------------------------------------------- DIV du milieu ------------------------------------------ //

// Affichage de la liste des �l�ves de la classe choisie
if ($classe_e AND is_numeric($classe_e) AND $rep_gr["subdivision_type"] != "classe") {


	$aff_nom_classe = mysql_fetch_array(mysql_query("SELECT classe FROM classes WHERE id = '".$classe_e."'"));

	// R�cup�rer la liste des �l�ves de la classe en question
	$req_ele = mysql_query("SELECT DISTINCT e.login, e.id_eleve, nom, prenom, sexe
					FROM j_eleves_classes jec, eleves e
					WHERE id_classe = '".$classe_e."'
					AND jec.login = e.login ORDER BY nom, prenom")
						OR DIE('Erreur dans la requ�te $req_ele : '.mysql_error());
	$nbre_ele_m = mysql_num_rows($req_ele);

	$aff_classes_m .= "
		<p class=\"red\">Classe de ".$aff_nom_classe["classe"]." : </p>

	<table class=\"aid_tableau\" summary=\"Liste des &eacute;l&egrave;ves\">
	";
		// Ligne paire, ligne impaire (inutile dans un premier temps), on s'en sert pour faire la diff�rence avec une ligne vide.
			$aff_tr_css = "gr_lignepaire";
		// On ajoute un lien qui permet d'int�grer toute la classe d'un coup
		$aff_classes_m .= "
		<tr class=\"".$aff_tr_css."\">
			<td>
				<a href=\"./edt_liste_eleves.php?cla=".$classe_e."&amp;id_eleve=tous&amp;id_gr=".$id_gr."\">
				<img src=\"../images/icons/add_user.png\" alt=\"Ajouter\" title=\"Ajouter\" /> Toute la classe
				</a>
			</td>
		</tr>
		<tr>
			<td>Liste des �l�ves
			</td>
		</tr>
						";

	for($b=0; $b<$nbre_ele_m; $b++) {
		$aff_ele_m[$b]["login"] = mysql_result($req_ele, $b, "login") OR DIE('Erreur requ�te liste_eleves : '.mysql_error());

			$aff_ele_m[$b]["id_eleve"] = mysql_result($req_ele, $b, "id_eleve");
			$aff_ele_m[$b]["nom"] = mysql_result($req_ele, $b, "nom");
			$aff_ele_m[$b]["prenom"] = mysql_result($req_ele, $b, "prenom");
			$aff_ele_m[$b]["sexe"] = mysql_result($req_ele, $b, "sexe");

			// On v�rifie que cet �l�ve n'est pas d�j� membre de l'AID
			$req_verif = mysql_query("SELECT id_eleve FROM edt_gr_eleves WHERE id_eleve = '".$aff_ele_m[$b]["id_eleve"]."' AND id_gr_nom = '".$id_gr."'");
			$nbre_verif = mysql_num_rows($req_verif);

				if ($nbre_verif >> 0) {
					$aff_classes_m .= "
					<tr class=\"gr_ligneimpaire\">
					<td></td></tr>
					";
				}
				else {
					$aff_classes_m .= "
					<tr class=\"".$aff_tr_css."\">
					<td><a href=\"./edt_liste_eleves.php?cla=".$classe_e."&amp;id_eleve=".$aff_ele_m[$b]["id_eleve"]."&amp;id_gr=".$id_gr."\">
							<img src=\"../images/icons/add_user.png\" alt=\"Ajouter\" title=\"Ajouter\" /> ".$aff_ele_m[$b]["nom"]." ".$aff_ele_m[$b]["prenom"]."
							</a></td></tr>
					";
				}
	}// for $b


	$aff_classes_m .= "</table>\n";


}elseif($rep_gr["subdivision_type"] == "classe"){

	$aff_classes_m = '
		<p>Vous ne pouvez pas modifier la liste des �l�ves de ce groupe car elle correspond � l\'effectif de la classe</p>
		<p>Si vous voulez modifier cette liste, vous devez d\'abord modifier le type du groupe � la page pr�c�dente</p>';
}

// ------------------------------------------- DIV de droite ------------------------------------------ //

// Pour terminer on se charge de l'affichage du div de droite qui liste les �l�ves de ce groupe

// On r�cup�re la liste des �l�ves
if ($rep_gr["subdivision_type"] == 'classe') {
	// Alors la liste des �l�ves est celle de la classe sus-dite $rep_gr["subdivision"]
	$sql_e = "SELECT DISTINCT e.nom, e.prenom, e.login, e.id_eleve, c.classe, c.id FROM eleves e, j_eleves_classes jec, classes c
									WHERE jec.login = e.login
									AND	jec.id_classe = '".$rep_gr["subdivision"]."'
									AND jec.id_classe = c.id
									ORDER BY nom, prenom";
	$req_ele_gr = mysql_query($sql_e) OR trigger_error('Impossible de r�cup�rer la liste des �l�ves', E_USER_ERROR);


}else{

	$req_ele_gr = mysql_query("SELECT DISTINCT ege.id_eleve, e.nom, e.prenom, e.login, c.classe, c.id
										FROM edt_gr_eleves ege, eleves e, j_eleves_classes jec, classes c
										WHERE ege.id_gr_nom = '".$id_gr."' AND
										ege.id_eleve = e.id_eleve AND
										e.login = jec.login AND
										jec.id_classe = c.id
										ORDER BY c.classe, e.nom, e.prenom")
									OR trigger_error('Impossible de r�cup�rer la liste des �l�ves : '.mysql_error(), E_USER_ERROR);

}

	$nbre = mysql_num_rows($req_ele_gr);
		$s = "";
		if ($nbre >= 2) {
			$s = "s";
		}
		else {
			$s = "";
		}
		$aff_gr_d .= "\n<br />".$nbre." �l�ve".$s.".<br />";

	while($rep_ele_gr = mysql_fetch_array($req_ele_gr)){

		$aff_gr_d .= "<br />
			<a href='./edt_liste_eleves.php?id_eleve=".$rep_ele_gr["id_eleve"]."&amp;id_gr=".$id_gr."&amp;cla=".$rep_ele_gr["id"]."&amp;action=del_eleve_gr'>
				<img src=\"../images/icons/delete.png\" title=\"Supprimer cet �l�ve\" alt=\"Supprimer\" />
			</a>".$rep_ele_gr["nom"]." ".$rep_ele_gr["prenom"]." ".$rep_ele_gr["classe"]."\n";

	}


// ++++++++++++++++++++++ Header +++++++++++++++++++++++++++++++++++++
$style_specifique = "/edt_gestion_gr/style2_edt";
$javascript_specifique = "edt_gestion_gr/script/fonctions_edt2";
$utilisation_win = 'oui';
$_SESSION['cacher_header'] = "n"; // pour enlever le header sur cette page
require_once("../lib/header.inc");
echo '
<!-- fin du header -->
';
// +++++++++++++++++++++ fin du header ++++++++++++++++++++++++

?>
<p onclick="window.opener.location.href='./edt_win.php?var=<?php echo $id_gr; ?>&amp;var2=liste_e'; window.close();" style="cursor: pointer;">FERMER</p>

<?php echo $aff_entete; ?>

<br />

<div id="edt_gr_g">
	<table>
		<thead><tr><th>Liste des classes</th></tr></thead>

		<?php echo $aff_classes_g; ?>

	</table>
</div>


<div id="edt_gr_d">

	<?php echo $aff_gr_d; ?>

</div>


<div id="edt_gr_m">

	<?php echo $aff_classes_m; ?>

</div>

<?php
require_once("../lib/footer.inc.php");
?>