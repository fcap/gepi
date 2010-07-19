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

if ($utilisateur->getStatut()!="cpe" && $utilisateur->getStatut()!="scolarite") {
    die("acces interdit");
}

include('include_requetes_filtre_de_recherche.php');

$page_number = isset($_POST["page_number"]) ? $_POST["page_number"] :(isset($_GET["page_number"]) ? $_GET["page_number"] :(isset($_SESSION["page_number"]) ? $_SESSION["page_number"] : NULL));
if (!is_numeric($page_number) || $reinit_filtre == 'y') {
    $page_number = 1;
}

$page_deplacement = isset($_POST["page_deplacement"]) ? $_POST["page_deplacement"] :(isset($_GET["page_deplacement"]) ? $_GET["page_deplacement"] :NULL);
if ($page_deplacement == "+") {
    $page_number = $page_number + 1;
} else if ($page_deplacement == "-") {
    $page_number = $page_number - 1;
}
if ($page_number < 1) {
    $page_number = 1;
}
if (isset($page_number) && $page_number != null) $_SESSION['page_number'] = $page_number;
//if (isset($page_deplacement) && $page_deplacement != null) $_SESSION['page_deplacement'] = $page_deplacement;

$item_per_page = isset($_POST["item_per_page"]) ? $_POST["item_per_page"] :(isset($_GET["item_per_page"]) ? $_GET["item_per_page"] :(isset($_SESSION["item_per_page"]) ? $_SESSION["item_per_page"] : NULL));
if (!is_numeric($item_per_page)) {
    $item_per_page = 14;
}
if ($item_per_page < 1) {
    $item_per_page = 1;
}
if (isset($item_per_page) && $item_per_page != null) $_SESSION['item_per_page'] = $item_per_page;

//==============================================
$style_specifique[] = "mod_abs2/lib/abs_style";
$style_specifique[] = "lib/DHTMLcalendar/calendarstyle";
$javascript_specifique[] = "lib/DHTMLcalendar/calendar";
$javascript_specifique[] = "lib/DHTMLcalendar/lang/calendar-fr";
$javascript_specifique[] = "lib/DHTMLcalendar/calendar-setup";
$titre_page = "Les absences";
$utilisation_jsdivdrag = "non";
$_SESSION['cacher_header'] = "y";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

//===========================

echo "<div id='aidmenu' style='display: none;'>test</div>\n";

include('menu_abs2.inc.php');
//===========================
echo "<div class='css-panes' id='containDiv' style='overflow : none; float : left; margin-top : -1px; border-width : 1px;'>\n";


$query = AbsenceEleveTraitementQuery::create();
if (isFiltreRechercheParam('filter_traitement_id')) {
    $query->filterById(getFiltreRechercheParam('filter_traitement_id'));
}
if (isFiltreRechercheParam('filter_utilisateur')) {
    $query->useUtilisateurProfessionnelQuery()->filterByNom('%'.getFiltreRechercheParam('filter_utilisateur').'%', Criteria::LIKE)->endUse();
}
if (isFiltreRechercheParam('filter_eleve')) {
    $query->useJTraitementSaisieEleveQuery()->useAbsenceEleveSaisieQuery()->useEleveQuery()
	    ->filterByNomOrPrenomLike(getFiltreRechercheParam('filter_eleve'))
	    ->endUse()->endUse()->endUse();
}
if (isFiltreRechercheParam('filter_classe')) {
    $query->useJTraitementSaisieEleveQuery()->endUse();
    $query->leftJoin('JTraitementSaisieEleve.AbsenceEleveSaisie');
    $query->leftJoin('AbsenceEleveSaisie.Eleve');
    $query->leftJoin('Eleve.JEleveClasse');
    $query->condition('cond1', 'JEleveClasse.IdClasse = ?', getFiltreRechercheParam('filter_classe'));
    $query->condition('cond2', 'AbsenceEleveSaisie.IdClasse = ?', getFiltreRechercheParam('filter_classe'));
    $query->where(array('cond1', 'cond2'), 'or');
}
if (isFiltreRechercheParam('filter_groupe')) {
    $query->useJTraitementSaisieEleveQuery()->endUse();
    $query->leftJoin('JTraitementSaisieEleve.AbsenceEleveSaisie');
    $query->leftJoin('AbsenceEleveSaisie.Eleve');
    $query->leftJoin('Eleve.JEleveGroupe');
    $query->condition('cond1', 'JEleveGroupe.IdGroupe = ?', getFiltreRechercheParam('filter_groupe'));
    $query->condition('cond2', 'AbsenceEleveSaisie.IdGroupe = ?', getFiltreRechercheParam('filter_groupe'));
    $query->where(array('cond1', 'cond2'), 'or');
}
if (isFiltreRechercheParam('filter_aid')) {
    $query->useJTraitementSaisieEleveQuery()->endUse();
    $query->leftJoin('JTraitementSaisieEleve.AbsenceEleveSaisie');
    $query->leftJoin('AbsenceEleveSaisie.Eleve');
    $query->leftJoin('Eleve.JAidEleves');
    $query->condition('cond1', 'JAidEleves.IdAid = ?', getFiltreRechercheParam('filter_aid'));
    $query->condition('cond2', 'AbsenceEleveSaisie.IdAid = ?', getFiltreRechercheParam('filter_aid'));
    $query->where(array('cond1', 'cond2'), 'or');
}
if (isFiltreRechercheParam('filter_type')) {
    $query->filterByATypeId(getFiltreRechercheParam('filter_type'));
}
if (isFiltreRechercheParam('filter_justification')) {
    $query->filterByAJustificationId(getFiltreRechercheParam('filter_justification'));
}
if (isFiltreRechercheParam('filter_date_creation_traitement_debut_plage')) {
    $date_creation_traitement_debut_plage = new DateTime(str_replace("/",".",getFiltreRechercheParam('filter_date_creation_traitement_debut_plage')));
    $query->filterByCreatedAt($date_creation_traitement_debut_plage, Criteria::GREATER_EQUAL);
}
if (isFiltreRechercheParam('filter_date_creation_traitement_fin_plage')) {
    $date_creation_traitement_fin_plage = new DateTime(str_replace("/",".",getFiltreRechercheParam('filter_date_creation_traitement_fin_plage')));
    $query->filterByCreatedAt($date_creation_traitement_fin_plage, Criteria::LESS_EQUAL);
}
if (isFiltreRechercheParam('filter_date_modification')) {
    $query->where('AbsenceEleveTraitement.CreatedAt != AbsenceEleveTraitement.UpdatedAt');
}

if (getFiltreRechercheParam('order') == "asc_id") {
    $query->orderBy('Id', Criteria::ASC);
} else if (getFiltreRechercheParam('order') == "des_id") {
    $query->orderBy('Id', Criteria::DESC);
} else if (getFiltreRechercheParam('order') == "asc_utilisateur") {
    $query->useUtilisateurProfessionnelQuery()->orderBy('Nom', Criteria::ASC)->endUse();
} else if (getFiltreRechercheParam('order') == "des_utilisateur") {
    $query->useUtilisateurProfessionnelQuery()->orderBy('Nom', Criteria::DESC)->endUse();
} else if (getFiltreRechercheParam('order') == "asc_eleve") {
    $query->useJTraitementSaisieEleveQuery()->useAbsenceEleveSaisieQuery()->useEleveQuery()->orderBy('Nom', Criteria::ASC)->endUse()->endUse()->endUse();
} else if (getFiltreRechercheParam('order') == "des_eleve") {
    $query->useJTraitementSaisieEleveQuery()->useAbsenceEleveSaisieQuery()->useEleveQuery()->orderBy('Nom', Criteria::DESC)->endUse()->endUse()->endUse();
} else if (getFiltreRechercheParam('order') == "asc_classe") {
    $query->useJTraitementSaisieEleveQuery()->useAbsenceEleveSaisieQuery()->useClasseQuery()->orderBy('NomComplet', Criteria::ASC)->endUse()->endUse()->endUse();
} else if (getFiltreRechercheParam('order') == "des_classe") {
    $query->useJTraitementSaisieEleveQuery()->useAbsenceEleveSaisieQuery()->useClasseQuery()->orderBy('NomComplet', Criteria::DESC)->endUse()->endUse()->endUse();
} else if (getFiltreRechercheParam('order') == "asc_groupe") {
    $query->useJTraitementSaisieEleveQuery()->useAbsenceEleveSaisieQuery()->useGroupeQuery()->orderBy('Name', Criteria::ASC)->endUse()->endUse()->endUse();
} else if (getFiltreRechercheParam('order') == "des_groupe") {
    $query->useJTraitementSaisieEleveQuery()->useAbsenceEleveSaisieQuery()->useGroupeQuery()->orderBy('Name', Criteria::DESC)->endUse()->endUse()->endUse();
} else if (getFiltreRechercheParam('order') == "asc_aid") {
    $query->useAidDetailsQuery()->orderBy('Nom', Criteria::ASC)->endUse();
} else if (getFiltreRechercheParam('order') == "des_aid") {
    $query->useAidDetailsQuery()->orderBy('Nom', Criteria::DESC)->endUse();
} else if (getFiltreRechercheParam('order') == "asc_type") {
    $query->orderBy('ATypeId', Criteria::ASC);
} else if (getFiltreRechercheParam('order') == "des_type") {
    $query->orderBy('ATypeId', Criteria::DESC);
} else if (getFiltreRechercheParam('order') == "asc_justification") {
    $query->orderBy('AJustificationId', Criteria::ASC);
} else if (getFiltreRechercheParam('order') == "des_justification") {
    $query->orderBy('AJustificationId', Criteria::DESC);
} else if (getFiltreRechercheParam('order') == "asc_date_debut") {
    $query->orderBy('DebutAbs', Criteria::ASC);
} else if (getFiltreRechercheParam('order') == "des_date_debut") {
    $query->orderBy('DebutAbs', Criteria::DESC);
} else if (getFiltreRechercheParam('order') == "asc_date_fin") {
    $query->orderBy('FinAbs', Criteria::ASC);
} else if (getFiltreRechercheParam('order') == "des_date_fin") {
    $query->orderBy('FinAbs', Criteria::DESC);
} else if (getFiltreRechercheParam('order') == "asc_creneau") {
    $query->useEdtCreneauQuery()->orderBy('HeuredebutDefiniePeriode', Criteria::ASC)->endUse();
} else if (getFiltreRechercheParam('order') == "des_creneau") {
    $query->useEdtCreneauQuery()->orderBy('HeuredebutDefiniePeriode', Criteria::DESC)->endUse();
} else if (getFiltreRechercheParam('order') == "asc_date_creation") {
    $query->orderBy('CreatedAt', Criteria::ASC);
} else if (getFiltreRechercheParam('order') == "des_date_creation") {
    $query->orderBy('CreatedAt', Criteria::DESC);
} else if (getFiltreRechercheParam('order') == "asc_date_modification") {
    $query->orderBy('UpdatedAt', Criteria::ASC);
} else if (getFiltreRechercheParam('order') == "des_date_modification") {
    $query->orderBy('UpdatedAt', Criteria::DESC);
} else if (getFiltreRechercheParam('order') == "des_date_modification") {
    $query->orderBy('UpdatedAt', Criteria::DESC);
}

$query->distinct();
$traitements_col = $query->paginate($page_number, $item_per_page);

$nb_pages = (floor($traitements_col->getNbResults() / $item_per_page) + 1);
if ($page_number > $nb_pages) {
    $page_number = $nb_pages;
}

echo '<form method="post" action="liste_traitements.php" id="liste_traitements">';

  echo "<p>";
  
if ($traitements_col->haveToPaginate()) {
    echo "Page ";
    echo '<input type="submit" name="page_deplacement" value="-"/>';
    echo '<input type="text" name="page_number" size="1" value="'.$page_number.'"/>';
    echo '<input type="submit" name="page_deplacement" value="+"/> ';
    echo "sur ".$nb_pages." page(s) ";
    echo "| ";
}
echo "Voir ";
echo '<input type="text" name="item_per_page" size="1" value="'.$item_per_page.'"/>';
echo "par page|  Nombre d'enregistrements : ";
echo $traitements_col->count();

echo "&nbsp;&nbsp;&nbsp;";
echo '<button type="submit">Rechercher</button>';
echo '<button type="submit" name="reinit_filtre" value="y" >Reinitialiser les filtres</button> ';

echo "</p>";

echo '<table id="table_liste_absents" class="tb_absences" style="border-spacing:0;">';

echo '<thead>';
echo '<tr>';

$order = getFiltreRechercheParam('order');
//en tete filtre id
echo '<th>';
//echo '<nobr>';
echo '<span style="white-space: nowrap;"> ';
echo 'N�';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_id") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_id"/>';
echo '<input type="image" src="../images/down.png" title="descendre" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_id") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_id"/>';
//echo '</nobr> ';
echo '</span>';
echo '<input type="text" name="filter_traitement_id" value="'.getFiltreRechercheParam('filter_traitement_id').'" size="3"/>';
echo '</th>';

//en tete filtre utilisateur
echo '<th>';
//echo '<nobr>';
echo '<span style="white-space: nowrap;"> ';
echo 'Utilisateur';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_utilisateur") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_utilisateur"/>';
echo '<input type="image" src="../images/down.png" title="descendre" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_utilisateur") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_utilisateur"/>';
//echo '</nobr>';
echo '</span>';
echo '<br /><input type="text" name="filter_utilisateur" value="'.getFiltreRechercheParam('filter_utilisateur').'" size="12"/>';
echo '</th>';

//en tete filtre eleve
echo '<th>';
//echo '<nobr>';
echo '<span style="white-space: nowrap;"> ';
echo 'Eleve';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_eleve") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_eleve"/>';
echo '<input type="image" src="../images/down.png" title="descendre" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_eleve") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_eleve"/>';
//echo '</nobr>';
echo '</span>';
echo '<br /><input type="text" name="filter_eleve" value="'.getFiltreRechercheParam('filter_eleve').'" size="8"/>';
echo '</th>';

//en tete filtre saisies
echo '<th>';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo 'Saisies';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_eleve") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_eleve"/>';
echo '<input type="image" src="../images/down.png" title="descendre" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_eleve") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_eleve"/>';
echo '</span>';
//echo '</nobr>';
echo '</th>';

//en tete filtre classe
echo '<th>';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo 'Classe';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_classe") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_classe"/>';
echo '<input type="image" src="../images/down.png" title="descendre" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_classe") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_classe"/>';
echo '</span>';
//echo '</nobr>';
echo '<br />';
echo ("<select name=\"filter_classe\" onchange='submit()'>");
echo "<option value=''></option>\n";
foreach (ClasseQuery::create()->distinct()->find() as $classe) {
	echo "<option value='".$classe->getId()."'";
	if (getFiltreRechercheParam('filter_classe') === $classe->getId()) echo " selected='selected' ";
	echo ">";
	echo $classe->getNomComplet();
	echo "</option>\n";
}
echo "</select>";
echo '</th>';

//en tete type d'absence
echo '<th>';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo 'type';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_type") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_type"/>';
echo '<input type="image" src="../images/down.png" title="descendre" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_type") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_type"/>';
echo '</span>';
//echo '</nobr>';
echo '<br />';
echo ("<select name=\"filter_type\" onchange='submit()'>");
echo "<option value=''></option>\n";
foreach (AbsenceEleveTypeQuery::create()->find() as $type) {
	echo "<option value='".$type->getId()."'";
	if (getFiltreRechercheParam('filter_type') === $type->getId()) echo " selected='selected' ";
	echo ">";
	echo $type->getNom();
	echo "</option>\n";
}
echo "</select>";
echo '</th>';

//en tete justification d'absence
echo '<th>';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo 'justification';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_justification") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_justification"/>';
echo '<input type="image" src="../images/down.png" title="descendre" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_justification") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_justification"/>';
echo '</span>';
//echo '</nobr>';
echo '<br />';
echo ("<select name=\"filter_justification\" onchange='submit()'>");
echo "<option value=''></option>\n";
foreach (AbsenceEleveJustificationQuery::create()->find() as $justification) {
	echo "<option value='".$justification->getId()."'";
	if (getFiltreRechercheParam('filter_justification') === $justification->getId()) echo " selected='selected' ";
	echo ">";
	echo $justification->getNom();
	echo "</option>\n";
}
echo "</select>";
echo '</th>';

//en tete notification d'absence
echo '<th>';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo '&nbsp;notification&nbsp;';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_notification") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_notification"/>';
echo '<input type="image" src="../images/down.png" title="descendre" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_notification") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_notification"/>';
echo '</span>';
//echo '</nobr>';
echo '<br />';
echo '</th>';

//en tete filtre date creation
echo '<th>';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo 'Date creation';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_date_creation") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_date_creation"/>';
echo '<input type="image" src="../images/down.png" title="descendre" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_date_creation") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_date_creation"/>';
echo '</span>';
//echo '</nobr>';
echo '<br />';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo 'Entre : <input size="13" id="filter_date_creation_traitement_debut_plage" name="filter_date_creation_traitement_debut_plage" value="';
if (isFiltreRechercheParam('filter_date_creation_traitement_debut_plage')) {echo getFiltreRechercheParam('filter_date_creation_traitement_debut_plage');}
echo '" />&nbsp;';
echo '<img id="trigger_filter_date_creation_traitement_debut_plage" src="../images/icons/calendrier.gif" alt="" />';
echo '</span>';
//echo '</nobr>';
echo '
<script type="text/javascript">
    Calendar.setup({
	inputField     :    "filter_date_creation_traitement_debut_plage",     // id of the input field
	ifFormat       :    "%d/%m/%Y %H:%M",      // format of the input field
	button         :    "trigger_filter_date_creation_traitement_debut_plage",  // trigger for the calendar (button ID)
	align          :    "Tl",           // alignment (defaults to "Bl")
	singleClick    :    true,
	showsTime	:   true
    });
</script>';
echo '<br />';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo 'Et : <input size="13" id="filter_date_creation_traitement_fin_plage" name="filter_date_creation_traitement_fin_plage" value="';
if (isFiltreRechercheParam('filter_date_creation_traitement_fin_plage') != null) {echo getFiltreRechercheParam('filter_date_creation_traitement_fin_plage');}
echo '" />&nbsp;';
echo '<img id="trigger_filter_date_creation_traitement_fin_plage" src="../images/icons/calendrier.gif" alt="" />';
echo '</span>';
//echo '</nobr>';
echo '
<script type="text/javascript">
    Calendar.setup({
	inputField     :    "filter_date_creation_traitement_fin_plage",     // id of the input field
	ifFormat       :    "%d/%m/%Y %H:%M",      // format of the input field
	button         :    "trigger_filter_date_creation_traitement_fin_plage",  // trigger for the calendar (button ID)
	align          :    "Tl",           // alignment (defaults to "Bl")
	singleClick    :    true,
	showsTime	:   true
    });
</script>';
echo '</th>';

//en tete filtre date modification
echo '<th>';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo '';
echo '<input type="image" src="../images/up.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "asc_date_modification") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="asc_date_modification"/>';
echo '<input type="image" src="../images/down.png" title="monter" style="vertical-align: middle;width:15px; height:15px; ';
if ($order == "des_date_modification") {echo "border-style: solid; border-color: red;";} else {echo "border-style: solid; border-color: silver;";}
echo 'border-width:1px;" alt="" name="order" value="des_date_modification"/>';
echo '</span>';
//echo '</nobr> ';
echo '<span style="white-space: nowrap;"> ';
//echo '<nobr>';
echo '<input type="hidden" value="y" name="filter_checkbox_posted"/>';
echo '<input type="checkbox" value="y" name="filter_date_modification" onchange="submit()"';
if (isFiltreRechercheParam('filter_date_modification') != null && getFiltreRechercheParam('filter_date_modification') == 'y') {echo "checked";}
echo '/> modifi�';
echo '</span>';
//echo '</nobr>';
echo '</th>';

//en tete commentaire
echo '<th>';
echo 'commentaire';
echo '</th>';

echo '</tr>';
echo '</thead>';

echo '<tbody>';
$results = $traitements_col->getResults();
foreach ($results as $traitement) {
    //$traitement = new AbsenceEleveTraitement();
    if ($results->getPosition() %2 == '1') {
	    $background_couleur="rgb(220, 220, 220);";
    } else {
	    $background_couleur="rgb(210, 220, 230);";
    }

    echo "<tr style='background-color :$background_couleur'>\n";

    //donnees id
    echo '<td>';
    echo "<a href='visu_traitement.php?id_traitement=".$traitement->getPrimaryKey()."' style='display: block; height: 100%;'> ";
    echo $traitement->getId();
    echo "</a>";
    echo '</td>';

    //donnees utilisateur
    echo '<td>';
    echo "<a href='visu_traitement.php?id_traitement=".$traitement->getPrimaryKey()."' style='display: block; height: 100%; color: #330033'> ";
    if ($traitement->getUtilisateurProfessionnel() != null) {
	echo $traitement->getUtilisateurProfessionnel()->getCivilite().' '.$traitement->getUtilisateurProfessionnel()->getNom();
    }
    echo "</a>";
    echo '</td>';

    //donnees eleve
    echo '<td>';
    $eleve_col = new PropelObjectCollection();
    foreach ($traitement->getAbsenceEleveSaisies() as $saisie) {
	if ($saisie->getEleve() != null) {
	    $eleve_col->add($saisie->getEleve());
	}
    }
    foreach ($eleve_col as $eleve) {
	echo "<table style='border-spacing:0px; border-style : none; margin : 0px; padding : 0px; font-size:100%;'>";
	echo "<tr style='border-spacing:0px; border-style : none; margin : 0px; padding : 0px; font-size:100%;'>";
	echo "<td style='border-spacing:0px; border-style : none; margin : 0px; padding : 0px; font-size:100%;'>";
	echo "<a href='liste_traitements.php?filter_eleve=".$saisie->getEleve()->getNom()."' style='display: block; height: 100%;'> ";
	echo ($eleve->getCivilite().' '.$eleve->getNom().' '.$eleve->getPrenom());
	echo "</a>";
	if ($utilisateur->getAccesFicheEleve($saisie->getEleve())) {
	    //echo "<a href='../eleves/visu_eleve.php?ele_login=".$eleve->getLogin()."' target='_blank'>";
	    echo "<a href='../eleves/visu_eleve.php?ele_login=".$eleve->getLogin()."' >";
	    echo ' (voir fiche)';
	    echo "</a>";
	}
	echo "</td>";
	echo "<td style='border-spacing:0px; border-style : none; margin : 0px; padding : 0px; font-size:100%;'>";
	echo "<a href='liste_traitements.php?filter_eleve=".$saisie->getEleve()->getNom()."' style='display: block; height: 100%;'> ";
 	if ((getSettingValue("active_module_trombinoscopes")=='y')) {
	    $nom_photo = $eleve->getNomPhoto(1);
	    $photos = "../photos/eleves/".$nom_photo;
	    if (($nom_photo != "") && (file_exists($photos))) {
		$valeur = redimensionne_image_petit($photos);
		echo ' <img src="'.$photos.'" style="align:right; width:'.$valeur[0].'px; height:'.$valeur[1].'px;" alt="" title="" /> ';
	    }
	}
	echo "</a>";
	echo "</td></tr></table>";
    }
    echo '</td>';

    //donnees saisies
    echo '<td>';
    if (!$traitement->getAbsenceEleveSaisies()->isEmpty()) {
	echo "<table style='border-spacing:0px; border-style : none; margin : 0px; padding : 0px; font-size:100%; width: 220px;'>";
    }
    foreach ($traitement->getAbsenceEleveSaisies() as $saisie) {
	echo "<tr style='border-spacing:0px; border-style : solid; border-size : 1px; margin : 0px; padding : 0px; font-size:100%;'>";
	echo "<td style='border-spacing:0px; border-style : solid; border-size : 1px; �argin : 0px; padding-top : 3px; font-size:88%;'>";
	echo "<a href='visu_saisie.php?id_saisie=".$saisie->getPrimaryKey()."' style='display: block; height: 100%;'>\n";
	echo $saisie->getDescription();
	echo "</a>";
	echo "</td>";
	echo "</tr>";
    }
    if (!$traitement->getAbsenceEleveSaisies()->isEmpty()) {
	echo "</table>";
    }
    echo '</td>';

    //donnees classe
    echo '<td>';
    echo "<a href='visu_traitement.php?id_traitement=".$traitement->getPrimaryKey()."' style='display: block; height: 100%; color: #330033'> ";
    $classe_col = new PropelObjectCollection();
    foreach ($traitement->getAbsenceEleveSaisies() as $saisie) {
	if ($saisie->getClasse() != null) {
	    $classe_col->add($saisie->getClasse());
	}
    }
    foreach ($classe_col as $classe) {
	echo $classe->getNomComplet();
    }
    if ($classe_col->isEmpty() != null) {
	echo "&nbsp;";
    }
    echo "</a>";
    echo '</td>';

    //donnees type
    //echo '<td><nobr>';
    echo '<td>';
    echo "<a href='visu_traitement.php?id_traitement=".$traitement->getPrimaryKey()."' style='display: block; height: 100%; color: #330033'>\n";
    if ($traitement->getAbsenceEleveType() != null) {
	echo $traitement->getAbsenceEleveType()->getNom();
    } else {
	echo "&nbsp;";
    }
    echo "</a>";
    //echo '</nobr></td>';
    echo '</td>';

    //donnees justification
    //echo '<td><nobr>';
    echo '<td>';
    echo "<a href='visu_traitement.php?id_traitement=".$traitement->getPrimaryKey()."' style='display: block; height: 100%; color: #330033'>\n";
    if ($traitement->getAbsenceEleveJustification() != null) {
	echo $traitement->getAbsenceEleveJustification()->getNom();
    } else {
	echo "&nbsp;";
    }
    echo "</a>";
    //echo '</nobr></td>';
    echo '</td>';

    //donnees notification
    echo '<td>';
    echo "<a href='visu_traitement.php?id_traitement=".$traitement->getPrimaryKey()."' style='display: block; height: 100%; color: #330033'> ";
    echo "</a>";
	if (count($traitement->getAbsenceEleveNotifications())){
    echo "<table style='border-spacing:0px; border-style : none; margin : 0px; padding : 0px; font-size:100%; width: 200px;'>";
    foreach ($traitement->getAbsenceEleveNotifications() as $notification) {
	echo "<tr style='border-spacing:0px; border-style : solid; border-size : 1px; margin : 0px; padding : 0px; font-size:100%;'>";
	echo "<td style='border-spacing:0px; border-style : solid; border-size : 1px; �argin : 0px; padding-top : 3px; font-size:88%;'>";
	echo "<a href='visu_notification.php?id_notification=".$notification->getPrimaryKey()."' style='display: block; height: 100%;'>\n";
	echo $notification->getDescription();
	echo "</a>";
	echo "</td>";
	echo "</tr>";
    }
    echo "</table>";
	}
 //   echo "</a>";
    echo '</td>';

    //echo '<td><nobr>';
    echo '<td>';
    echo "<a href='visu_traitement.php?id_traitement=".$traitement->getPrimaryKey()."' style='display: block; height: 100%; color: #330033'>\n";
    echo (strftime("%a %d %b %Y %H:%M", $traitement->getCreatedAt('U')));
    echo "</a>";
    //echo '</nobr></td>';
    echo '</td>';

    echo '<td>';
    echo "<a href='visu_traitement.php?id_traitement=".$traitement->getPrimaryKey()."' style='display: block; height: 100%; color: #330033'>\n";
    echo (strftime("%a %d %b %Y %H:%M", $traitement->getUpdatedAt('U')));
    echo "</a>";
    echo '</td>';

    echo '<td>';
    echo "<a href='visu_traitement.php?id_traitement=".$traitement->getPrimaryKey()."' style='display: block; height: 100%; color: #330033'>\n";
    echo ($traitement->getCommentaire());
    echo "&nbsp;";
    echo "</a>";
    echo '</td>';

    echo '</tr>';
}

echo '</tbody>';
//echo '</tbody>';

echo '</table>';

echo '</form>';

echo "</div>\n";

require_once("../lib/footer.inc.php");


//fonction redimensionne les photos petit format
function redimensionne_image_petit($photo)
 {
    // prendre les informations sur l'image
    $info_image = getimagesize($photo);
    // largeur et hauteur de l'image d'origine
    $largeur = $info_image[0];
    $hauteur = $info_image[1];
    // largeur et/ou hauteur maximum � afficher
             $taille_max_largeur = 35;
             $taille_max_hauteur = 35;

    // calcule le ratio de redimensionnement
     $ratio_l = $largeur / $taille_max_largeur;
     $ratio_h = $hauteur / $taille_max_hauteur;
     $ratio = ($ratio_l > $ratio_h)?$ratio_l:$ratio_h;

    // d�finit largeur et hauteur pour la nouvelle image
     $nouvelle_largeur = $largeur / $ratio;
     $nouvelle_hauteur = $hauteur / $ratio;

   // on renvoit la largeur et la hauteur
    return array($nouvelle_largeur, $nouvelle_hauteur);
 }
?>