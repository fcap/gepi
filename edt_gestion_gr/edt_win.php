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

// Initialisations files
require_once("../lib/initialisations.inc.php");

// Resume session
$resultat_session = resumeSession();
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

$var = isset($_GET["var"]) ? $_GET["var"] : (isset($_POST["var"]) ? $_POST["var"] : NULL);
$var2 = isset($_GET["var2"]) ? $_GET["var2"] : NULL;
$nom_gr = isset($_POST["nom_gr"]) ? $_POST["nom_gr"] : NULL;
$nom_long_gr = isset($_POST["nom_long_gr"]) ? $_POST["nom_long_gr"] : NULL;
$action = isset($_POST["action"]) ? $_POST["action"] : NULL;
$aff_modif = NULL;

// On r�cup�re toutes les donn�es du groupe
if (isset($var) AND is_numeric($var)) {
	// On y va pour r�cup�rer les donn�es
	$query_d = mysql_query("SELECT nom, nom_long, subdivision_type, subdivision FROM edt_gr_nom WHERE id = '".$var."'");
	$rep_d = mysql_fetch_array($query_d);
}


if ($var2 == "changer_nom") {
	//echo '<p>Vous voulez changer de nom ?</p>';
	// On r�cup�re toutes les donn�es du groupe
	if (isset($var) AND is_numeric($var)) {

				$aff_modif .= '
		<fieldset id="ajoutGr2">
		<legend>&nbsp;Modifier un groupe d\'&eacute;l&egrave;ves pour l\'EdT&nbsp;</legend>

		<form name="ajout" action="edt_win.php" method="post">
			<input type="hidden" name="action" value="modifier_gr" />
			<input type="hidden" name="var" value="'.$var.'" />

			<p style="text-align: right; margin-right: 10px;">
			<label for="nomGr" title="Tel qu\'il doit apparaitre dans l\'EdT">Nom</label>
			<input type="text" id="nomGr" name="nom_gr" value="'.$rep_d["nom"].'" />
			</p>
			<p style="text-align: right; margin-right: 10px;">
			<label for="nomLongGr" title="Si n&eacute;cessaire !">Autre nom</label>
			<input type="text" id="nomLongGr" name="nom_long_gr" value="'.$rep_d["nom_long"].'" />
			</p>

			<input type="submit" name="enregistrer" value="Enregistrer les modifications" />

		</form>
	</fieldset>
	';

	}else{
		$aff_modif = '<p>Impossible de r�cup�rer les donn�es de ce groupe.</p>';
	}

}elseif($var2 == "liste_e"){

	// On v�rifie si ce gr ne correspond pas � une classe et � laquelle pr�cis�ment
	//$query_verif = mysql_fetch_array(mysql_query("SELECT subdivision_type, subdivision FROM edt_gr_nom WHERE id ='".$var."'"));

	if ($rep_d["subdivision_type"] == "classe") {
		// �a veut dire que ce groupe correspond � la classe dont l'id est $rep_d["subdivision"]
		// On r�cup�re donc la liste des �l�ves de cette classe
		$sql_e = "SELECT DISTINCT e.nom, e.prenom, e.login FROM eleves e, j_eleves_classes jec
											WHERE jec.login = e.login
											AND	jec.id_classe = '".$rep_d["subdivision"]."'
											ORDER BY nom, prenom";

	}else{
		// Permet d'afficher la liste des �l�ves
		$sql_e = "SELECT DISTINCT e.nom, e.prenom, e.login FROM eleves e, edt_gr_eleves ege
											WHERE ege.id_eleve = e.id_eleve
											AND	ege.id_gr_nom = '".$var."'
											ORDER BY nom, prenom";
	}

	// On met en place l'affichage
	$aff_modif .= '<p style="text-align: right;"><a href="edt_liste_eleves.php?id_gr='.$var.'" target="_blank">Modifier cette liste</a></p>';

	$query_e = mysql_query($sql_e) OR trigger_error('Impossible de r�cup�rer la liste des �l�ves', E_USER_ERROR);

	while($rep = mysql_fetch_array($query_e)){

		// On r�cup�re alors la classe
		$query_c = mysql_query("SELECT classe FROM j_eleves_classes jec, classes c
										WHERE jec.login = '".$rep["login"]."'
										AND jec.id_classe = c.id");
		$classe = mysql_result($query_c, "classe");

		$aff_modif .= $rep["nom"].'&nbsp;'.$rep["prenom"].' ('.$classe.').<br />';

	}


}
// On traite la modification si elle est demand�e
if ($action == "modifier_gr") {
	$sql_m = "UPDATE edt_gr_nom SET nom = '".$nom_gr."', nom_long = '".$nom_long_gr."' WHERE id = '".$var."'";
	$query_m = mysql_query($sql_m) OR trigger_error('Impossible de mettre � jour ce groupe '.mysql_error(), E_USER_ERROR);
	if ($query_m) {
		// On ferme la fen�tre
		echo '<html><body><p>La modification a bien �t� enregistr�e, vous pouvez fermer cette fen�tre et rafraichir votre navigateur.</p></body></html>';
		trigger_error('Impossible d\'aller plus loin.', E_USER_ERROR);
	}
}

?>

<html>
	<head><title>&nbsp;-&nbsp;</title></head>
	<body>
	<div style="border: 2px solid red;">

	<?php echo $aff_modif; ?>

	</div>
	</body>
</html>