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

// SQL : INSERT INTO droits VALUES ( '/mod_discipline/edt_eleve.php', 'V', 'F', 'V', 'V', 'F', 'F', 'F', 'F', 'Discipline: EDT �l�ve', '');
// maj : $tab_req[] = "INSERT INTO droits VALUES ( '/mod_discipline/edt_eleve.php', 'V', 'F', 'V', 'V', 'F', 'F', 'F', 'F', 'Discipline: EDT �l�ve', '');;";
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

require('sanctions_func_lib.php');
include("../class_php/edt_cours.class.php");

//$msg="";

//$id_sanction=isset($_POST['id_sanction']) ? $_POST['id_sanction'] : (isset($_GET['id_sanction']) ? $_GET['id_sanction'] : NULL);
$ele_login=isset($_GET['ele_login']) ? $_GET['ele_login'] : NULL;
$sem = isset($_GET["sem"]) ? $_GET["sem"] : 0;
$aff_precedent = isset($_GET["sem"]) ? ($_GET["sem"] - 1) : (-1);
$aff_suivant = isset($_GET["sem"]) ? ($_GET["sem"] + 1) : (+1);


$javascript_specifique = "edt_organisation/script/fonctions_edt";
$style_specifique = "edt_organisation/style_edt";

$utilisation_prototype="ok";
$mode_header_reduit="y";
//**************** EN-TETE *****************
$titre_page = "Discipline: EDT �l�ve";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

//debug_var();

echo "<p class='bold'><a href='index.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour index</a>\n";
echo "</p>\n";


echo "<script type='text/javascript'>
	function clic_edt(heure, jour) {
		//window.opener.document.getElementById('heure_debut').value=heure;
		//window.opener.document.getElementById('date_retenue').value=jour;
		//self.close();

		alert(\"window.opener.document.getElementById('date_retenue').value=\"+window.opener.document.getElementById('date_retenue').value);

		alert(\"window.opener.document.getElementById('heure_debut').value=\"+window.opener.document.getElementById('heure_debut').value);
	}
</script>\n";

echo "<p>Test: <a href='#' onclick=\"clic_edt('a','b');return false;\">Clic</a></p>\n";

// gestion de l'affichage de l'edt de la semaine suivante

echo "<p><a href=\"edt_eleve.php?ele_login=".$ele_login."&amp;sem=".$aff_precedent."\">semaine pr�c.</a> - Emploi du temps de <strong>".p_nom($ele_login)."</strong>";
echo " (<em>";
$tmp_tab=get_class_from_ele_login($ele_login);
if(isset($tmp_tab['liste_nbsp'])) {echo $tmp_tab['liste_nbsp'];} else {echo "???";}
echo "</em>) - <a href=\"edt_eleve.php?ele_login=".$ele_login."&amp;sem=".$aff_suivant."\">semaine suiv.</a>";
echo ".</p>\n";

echo '<p style="color: red; font-size: 12 em;">'.edt::jours_de_la_semaine().'</p>';

/*/ Affichage de l'emploi du temps sur une semaine pr�cise

$cours = new edtAfficher(); // on instancie l'objet edtAfficher

$cours->sem = isset($_GET["sem"]) ? $_GET["sem"] : 0;

$cours->type_edt = 'eleve'; // on pr�cise le type
// on r�cup�re la liste des jours ouverts dans l'�tablissement (� d�finir depuis le module absences)
// La classe edt poss�de une m�thode publique donc accessible
$jours = edt::joursOuverts();

// On affiche l'ent�te
echo $cours->entete_creneaux();

for($a = 0 ; $a < $jours["nbre"] ; $a++){

	// On affiche l'edt de la semaine
	echo $cours->afficher_cours_jour($jours[$a], $ele_login);

}
*/
// Comme on veut utiliser les types de semaine, on pr�cise la variable
$utilise_type_semaine = 'y';
require_once("../edt_organisation/fonctions_edt.php");
premiere_ligne_tab_edt(); // On affiche la premi�re ligne
// le login de l'�l�ve est attrap� directement par la fonction avec $_GET["ele_login"]
// On r�cup�re le choix de l'admin sur l'affichage � gauche
$reglages_creneaux = GetSettingEdt("edt_aff_creneaux"); // on peut l'ajouter avec quelques lignes de code en plus
$reglages_creneaux = "noms";

if ($reglages_creneaux == "noms") {
	// affichage par nom de creneaux
	$tab_creneaux = retourne_creneaux();
}elseif($reglages_creneaux == "heures"){
	// Affichage par les horaires des cours
	$tab_creneaux = retourne_horaire();
}else{
	// par d�faut
	$tab_creneaux = retourne_creneaux();
}
	$i=0;
	$nbre = count($tab_creneaux);
	while($i < $nbre){

		$tab_id_creneaux = retourne_id_creneaux();
		$c=0;
		$nbre2 = count($tab_id_creneaux);

		while($c < $nbre2){

		echo("<tr><th rowspan=\"2\"><br />".$tab_creneaux[$i]."<br /><br /></th>".(construction_tab_edt($tab_id_creneaux[$c], "0"))."\n");
		echo("<tr>".(construction_tab_edt($tab_id_creneaux[$c], "0.5"))."\n");
		$i ++;
		$c ++;
		}
	}

echo '
</tbody>
</table>';


echo "<p><br /></p>\n";
?>
<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-xhtml10"
        alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
  </p>
<?php
echo "<p><em>Remarque&nbsp;:</em></p>\n";
echo "<blockquote>\n";
echo "<p>Cette page est destin�e � d�terminer les cr�neaux sans cours pour l'�l�ve.</p>\n";
echo "</blockquote>\n";
echo "<p><br /></p>\n";

require("../lib/footer.inc.php");
?>