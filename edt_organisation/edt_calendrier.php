<?php

/* Fichier destin� � param�trer le calendrier de Gepi pour l'Emploi du temps */
$titre_page = "Emploi du temps - Calendrier";
$affiche_connexion = 'yes';
$niveau_arbo = 1;

// Initialisations files
require_once("../lib/initialisations.inc.php");

// fonctions edt
require_once("./fonctions_edt.php");

// Resume session
$resultat_session = resumeSession();
if ($resultat_session == 'c') {
   header("Location:utilisateurs/mon_compte.php?change_mdp=yes&retour=accueil#changemdp");
   die();
} else if ($resultat_session == '0') {
    header("Location: ../logout.php?auto=1");
    die();
}

// S�curit�
if (!checkAccess()) {
    header("Location: ../logout.php?auto=2");
    die();
}
// S�curit� suppl�mentaire par rapport aux param�tres du module EdT / Calendrier
if (param_edt($_SESSION["statut"]) != "yes") {
	Die('Vous devez demander � votre administrateur l\'autorisation de voir cette page.');
}
// CSS et js particulier � l'EdT
$javascript_specifique = "edt_organisation/script/fonctions_edt";
$style_specifique = "edt_organisation/style_edt";
$utilisation_jsdivdrag = "";
//==============PROTOTYPE===============
$utilisation_prototype = "ok";
//============fin PROTOTYPE=============
// On ins�re l'ent�te de Gepi
require_once("../lib/header.inc");

// On ajoute le menu EdT
require_once("./menu.inc.php"); ?>


<br />
<!-- la page du corps de l'EdT -->

	<div id="lecorps">

<?php
	// Initialisation des variables
$calendrier = isset($_GET["calendrier"]) ? $_GET["calendrier"] : (isset($_POST["calendrier"]) ? $_POST["calendrier"] : NULL);
$new_periode = isset($_GET['new_periode']) ? $_GET['new_periode'] : (isset($_POST['new_periode']) ? $_POST['new_periode'] : NULL);
$nom_periode = isset($_POST["nom_periode"]) ? $_POST["nom_periode"] : NULL;
$classes_concernees = isset($_POST["classes_concernees"]) ? $_POST["classes_concernees"] : NULL;
$jour_debut = isset($_POST["jour_debut"]) ? $_POST["jour_debut"] : NULL;
$jour_fin = isset($_POST["jour_fin"]) ? $_POST["jour_fin"] : NULL;
$jour_dperiode = isset($_POST["jour_dperiode"]) ? $_POST["jour_dperiode"] : NULL;
$mois_dperiode = isset($_POST["mois_dperiode"]) ? $_POST["mois_dperiode"] : NULL;
$annee_dperiode = isset($_POST["annee_dperiode"]) ? $_POST["annee_dperiode"] : NULL;
$heure_debut = isset($_POST["heure_deb"]) ? $_POST["heure_deb"] : NULL;
$jour_fperiode = isset($_POST["jour_fperiode"]) ? $_POST["jour_fperiode"] : NULL;
$mois_fperiode = isset($_POST["mois_fperiode"]) ? $_POST["mois_fperiode"] : NULL;
$annee_fperiode = isset($_POST["annee_fperiode"]) ? $_POST["annee_fperiode"] : NULL;
$heure_fin = isset($_POST["heure_fin"]) ? $_POST["heure_fin"] : NULL;
$choix_periode = isset($_POST["choix_periode"]) ? $_POST["choix_periode"] : NULL;
$etabferme = isset($_POST["etabferme"]) ? $_POST["etabferme"] : NULL;
$vacances = isset($_POST["vacances"]) ? $_POST["vacances"] : NULL;
$supprimer = isset($_GET["supprimer"]) ? $_GET["supprimer"] : NULL;
$modifier = isset($_GET["modifier"]) ? $_GET["modifier"] : (isset($_POST["modifier"]) ? $_POST["modifier"] : NULL);
$modif_ok = isset($_POST["modif_ok"]) ? $_POST["modif_ok"] : NULL;
$message_new = NULL;

	// Quelques variables utiles
$annee_actu = date("Y"); // ann�e
$mois_actu = date("m"); // mois sous la forme 01 � 12
$jour_actu = date("d"); // jour sous la forme 01 � 31
$date_jour = date("d/m/Y"); //jour/mois/ann�e

		/*/ Recherche des infos d�j� entr�es dans Gepi
	$req_heures = mysql_fetch_array(mysql_query("SELECT ouverture_horaire_etablissement, fermeture_horaire_etablissement FROM horaires_etablissement"));
$heure_etab_deb = $req_heures["ouverture_horaire_etablissement"];
$heure_etab_fin = $req_heures["fermeture_horaire_etablissement"];
*/
/* On efface quand c'est demand� */
if (isset($calendrier) AND isset($supprimer)) {
	$req_supp = mysql_query("DELETE FROM edt_calendrier WHERE id_calendrier = '".$supprimer."'") or Die ('Suppression impossible !');
}

//+++++++++++ AIDE pour le calendrier ++++++++
?>
<a href="#" onMouseOver="javascript:changerDisplayDiv('aide_calendar');" onMouseOut="javascript:changerDisplayDiv('aide_calendar');">
	<img src="../images/info.png" alt="Plus d'infos..." Title="Plus d'infos..." />
</a>
	<div style="display: none;" id="aide_calendar">
	<hr />
	<p><span class="red">Attention</span>, ces p&eacute;riodes ne sont pas les m&ecirc;mes que celles d&eacute;finies pour les notes. Si vous voulez faire une
	 lien entre les p&eacute;riodes de notes et celles du calendrier, vous devez pr&eacute;ciser lors de la cr&eacute;ation de ces derni&egrave;res
	 &agrave; quelle p&eacute;riode de notes elles sont rattach&eacute;es en choisissant celle-ci dans le menu <i>P&eacute;riode de notes ?</i></p>
	 <hr />
	</div>
<?php
//+++++++++++ fin de l'aide ++++++++++++++++++

/* On modifie quand c'est demand� */
if (isset($calendrier) AND isset($modifier)) {
	// On affiche la p�riode demand�e dans un formulaire
	$rep_modif = mysql_fetch_array(mysql_query("SELECT * FROM edt_calendrier WHERE id_calendrier = '".$modifier."'"));
	echo '

<fieldset id="modif_periode">
	<legend>Modifier la p�riode pour le calendrier</legend>
		<form name="modifier_periode" action="edt_calendrier.php" method="post">
			<input type="hidden" name="calendrier" value="ok" />
			<input type="hidden" name="modif_ok" value="'.$rep_modif["id_calendrier"].'" />
		<p>
			<input type="text" name="nom_periode" maxlenght="100" size="30" value="'.$rep_modif["nom_calendrier"].'" />
			<span class="legende">Nom de la p�riode</span>
		</p>
	<div id="div_classes_concernees">
		';

	// On affiche la liste des classes
	$tab_select = renvoie_liste("classe");
	// On r�cup�re les classes de la p�riode ("zone de temps") � afficher
	$toutes_classes = explode(";", $rep_modif["classe_concerne_calendrier"]);
		// Fonction checked_calendar
		function checked_calendar($tester_classe, $classes_cochees){
			$cl_coch = explode(";", $classes_cochees);
			$return = "";
			for($t=0; $t<count($cl_coch); $t++) {
				if ($tester_classe == $cl_coch[$t]) {
					$return = " checked='checked'";
				}
			}
			return $return;
		}

	echo '
	<table>
		<tr valign="top" align="right"><td>
			';
	// Choix des classes sur 3 (ou 4) colonnes
		$modulo = count($tab_select) % 3;
			// Calcul du nombre d'entr�e par colonne ($ligne)
		if ($modulo !== 0) {
			$calcul = count($tab_select) / 3;
			$expl = explode(".", $calcul);
			$ligne = $expl[0];
		}else {
			$ligne = count($tab_select) / 3;
		}
$aff_checked = ""; // par d�faut, le checkbox n'est pas coch�
	// On affiche la premi�re colonne
for($i=0; $i<$ligne; $i++) {
	$aff_checked = checked_calendar($tab_select[$i]["id"], $rep_modif["classe_concerne_calendrier"]);
	echo
		$tab_select[$i]["classe"].'
			<label>
				<input name="classes_concernees[]" value="'.$tab_select[$i]["id"].'" id="case_1_'.$tab_select[$i]["id"].'"'.$aff_checked.' type="checkbox" />
			</label><br />
		';
}

echo '
		</td><td>
	';

for($i=$ligne; $i<($ligne*2); $i++) {
	$aff_checked = checked_calendar($tab_select[$i]["id"], $rep_modif["classe_concerne_calendrier"]);
	// On affiche la deuxi�me colonne
	echo
		$tab_select[$i]["classe"].'
			<label>
				<input name="classes_concernees[]" value="'.$tab_select[$i]["id"].'" id="case_1_'.$tab_select[$i]["id"].'"'.$aff_checked.' type="checkbox" />
			</label><br />
		';
}

echo '
		</td><td>
	';
for($i=($ligne*2); $i<($ligne*3); $i++) {
	$aff_checked = checked_calendar($tab_select[$i]["id"], $rep_modif["classe_concerne_calendrier"]);
	// On affiche la troisi�me colonne
	echo
		$tab_select[$i]["classe"].'
			<label>
				<input name="classes_concernees[]" value="'.$tab_select[$i]["id"].'" id="case_1_'.$tab_select[$i]["id"].'"'.$aff_checked.' type="checkbox" />
			</label><br />
		';
}
echo '
		</td>
	';
// s'il y a une quatri�me colonne, on l'affiche
if ($modulo !== 0) {
	echo '
		<td>
		';
	for($i=($ligne*3); $i<count($tab_select); $i++) {
	$aff_checked = checked_calendar($tab_select[$i]["id"], $rep_modif["classe_concerne_calendrier"]);
		echo
		$tab_select[$i]["classe"].'
			<label>
				<input name="classes_concernees[]" value="'.$tab_select[$i]["id"].'" id="case_1_'.$tab_select[$i]["id"].'"'.$aff_checked.' type="checkbox" />
			</label><br />
		';
	}
	echo '</td>';
	}


	echo '
		</tr>
	</table>
	</div>
		';
// Fin du div pour le choix des classes

	echo '
		<p>
			<input type="text" name="jour_dperiode" maxlenght="10" size="10" value="'.$rep_modif["jourdebut_calendrier"].'" />
			<span class="legende">Premier jour</span>

			<input type="text" name="heure_deb" maxlenght="8" size="8" value="'.$rep_modif["heuredebut_calendrier"].'" />
			<span class="legende">Heure de d�but</span>
		</p>
		<p>
			<input type="text" name="jour_fperiode" maxlenght="10" size="10" value="'.$rep_modif["jourfin_calendrier"].'" />
			<span class="legende">Dernier jour</span>

			<input type="text" name="heure_fin" maxlenght="8" size="8" value="'.$rep_modif["heurefin_calendrier"].'" />
			<span class="legende">Heure de fin</span>
		</p>
		<p>
			<select name="choix_periode">
				<option value="rien">Non</option>'."\n";
	// Proposition de d�finition des p�riodes d�j� existantes de la table periodes
	$req_periodes = mysql_query("SELECT nom_periode, num_periode FROM periodes WHERE id_classe = '1'");
	$nbre_periodes = mysql_num_rows($req_periodes);
		$rep_periodes[] = array();
		for ($i=0; $i<$nbre_periodes; $i++) {
			$rep_periodes[$i]["num_periode"] = mysql_result($req_periodes, $i, "num_periode");
			$rep_periodes[$i]["nom_periode"] = mysql_result($req_periodes, $i, "nom_periode");
				if ($rep_modif["numero_periode"] == $rep_periodes[$i]["num_periode"]) {
					$selected = " selected='true'";
				}
				else $selected = "";
			echo '<option value="'.$rep_periodes[$i]["num_periode"].'"'.$selected.'>'.$rep_periodes[$i]["nom_periode"].'</option>'."\n";
		}
	echo '
			</select>
			<span class="legende">P�riodes de notes ?</span>
		</p>
		<p>
			<select name="etabferme" />
		';
		// On v�rifie le ouvert - ferm�
		if ($rep_modif["etabferme_calendrier"] == "1") {
			$selected1 = " selected='selected'";
		} else $selected1 = "";
		if ($rep_modif["etabferme_calendrier"] == "2") {
			$selected2 = " selected='selected'";
		} else $selected2 = "";
	echo '
				<option value="1"'.$selected1.'>Ouvert</option>
				<option value="2"'.$selected2.'>Ferm�</option>
			</select>
			<span class="legende">Etablissement</span>
		</p>
		<p>
			<select name="vacances">
		';
		// On v�rifie le vacances - cours
		if ($rep_modif["etabvacances_calendrier"] == "0") {
			$selected1v = " selected='selected'";
		} else $selected1v = "";
		if ($rep_modif["etabvacances_calendrier"] == "1") {
			$selected2v = " selected='selected'";
		}else $selected2v = "";
	echo '
				<option value="0"'.$selected1v.'>Cours</option>
				<option value="1"'.$selected2v.'>Vacances</option>
			</select>
			<span class="legende">Vacances / Cours</span>
		</p>
			<input type="submit" name="valider" value="enregistrer" />
		</form>
</fieldset>
	';
}
	// On construit les classes consern�es
	if ($classes_concernees[0] == "0") {
			$classes_concernees_insert = "0";
		}
		else {
				$classes_concernees_insert = "";
			for ($c=0; $c<count($classes_concernees); $c++) {
				$classes_concernees_insert .= $classes_concernees[$c].";";
			}
		} // else
	// Puis on modifie la p�riode
if (isset($modif_ok) AND isset($nom_periode)) {
	$jourdebut = $jour_dperiode;
	$jourfin = $jour_fperiode;
	$modif_periode = mysql_query("UPDATE edt_calendrier SET nom_calendrier = '".$nom_periode."', classe_concerne_calendrier = '".$classes_concernees_insert."', jourdebut_calendrier = '".$jourdebut."', heuredebut_calendrier = '".$heure_debut."', jourfin_calendrier = '".$jourfin."', heurefin_calendrier = '".$heure_fin."', numero_periode = '".$choix_periode."', etabferme_calendrier = '".$etabferme."', etabvacances_calendrier = '".$vacances."' WHERE id_calendrier = '".$modif_ok."'") OR DIE ('Erreur dans la modification');
}

/* On traite les nouvelles entr�es dans la table */
if (isset($new_periode) AND isset($nom_periode)) {
$detail_jourdeb = explode("/", $jour_debut);
$detail_jourfin = explode("/", $jour_debut);

	$jourdebut = $detail_jourdeb[2]."-".$detail_jourdeb[1]."-".$detail_jourdeb[0];
	$jourfin = $detail_jourfin[2]."-".$detail_jourfin[1]."-".$detail_jourfin[0];
		// On ins�re les classes qui sont concern�es (0 = toutes)
		if ($classes_concernees[0] == "0") {
			$classes_concernees_insert = "0";
		}
		else {
				$classes_concernees_insert = "";
			for ($c=0; $c<count($classes_concernees); $c++) {
				$classes_concernees_insert .= $classes_concernees[$c].";";
			}
		} // else
	// On v�rifie que ce nom de p�riode n'existe pas encore
	$req_verif_periode = mysql_fetch_array(mysql_query("SELECT nom_calendrier FROM edt_calendrier WHERE nom_calendrier = '".$nom_periode."'"));
	if ($req_verif_periode[0] == NULL) {
	$heure_debut = $heure_debut.":00";
	$heure_fin = $heure_fin.":00";
		$req_insert = mysql_query("INSERT INTO edt_calendrier (`nom_calendrier`, `classe_concerne_calendrier`, `jourdebut_calendrier`, `heuredebut_calendrier`, `jourfin_calendrier`, `heurefin_calendrier`, `numero_periode`, `etabferme_calendrier`, `etabvacances_calendrier`) VALUES ('$nom_periode', '$classes_concernees_insert', '$jourdebut', '$heure_debut', '$jourfin', '$heure_fin', '$choix_periode', '$etabferme', '$vacances')") OR DIE ('Echec dans la requ�te de cr�ation d\'une nouvelle entr�e !');
	}
	else echo '<h3 class="red">Ce nom de p�riode existe d�j�</h3>';
}

/* On affiche alors toutes les p�riodes de la table */

	// Lien qui permet de saisir de nouvelles p�riodes
if ($modifier == NULL) {
	echo '
	<p>
	<a href="edt_calendrier.php?calendrier=ok&amp;new_periode=ok"><img src="../images/icons/add.png" alt="" class="back_link" /> AJOUTER</a>
	</p>
	';

}

/*+++++++++++++++++++++AFFICHAGE DES PERIODES DEJA DEFINIES +++++++++++++++++++++*/
//================================================================================
	// Toutes les p�riodes sont visibles par d�faut
echo '
<fieldset id="aff_calendar">
	<legend>Liste des p�riodes</legend>
<table id="edt_calendar" cellspacing="1" cellpadding="1" border="1">
	<tr class="premiere_ligne">
		<td>Nom du calendrier</td>
		<td>Classes</td>
		<td class="bonnelargeur">Premier jour</td>
		<td class="bonnelargeur">�</td>
		<td class="bonnelargeur">Dernier jour</td>
		<td class="bonnelargeur">�</td>
		<!--<td>Trimestre</td>-->
		<td class="bonnelargeur">Etablissement</td>
		<td></td>
		<td></td>
	</tr>
';
	// On affiche toutes les lignes d�j� entr�es
$req_affcalendar = mysql_query("SELECT * FROM edt_calendrier ORDER BY jourdebut_calendrier") OR die ('Impossible d\'afficher le calendrier.');
$nbre_affcalendar = mysql_num_rows($req_affcalendar);
	// Variable pour le $class_tr
	$a = 1;

	for ($i=0; $i<$nbre_affcalendar; $i++) {
		$rep_affcalendar[$i]["id_calendrier"] = mysql_result($req_affcalendar, $i, "id_calendrier");
		$rep_affcalendar[$i]["classe_concerne_calendrier"] = mysql_result($req_affcalendar, $i, "classe_concerne_calendrier");
		$rep_affcalendar[$i]["nom_calendrier"] = mysql_result($req_affcalendar, $i, "nom_calendrier");
		$rep_affcalendar[$i]["jourdebut_calendrier"] = mysql_result($req_affcalendar, $i, "jourdebut_calendrier");
		$rep_affcalendar[$i]["heuredebut_calendrier"] = mysql_result($req_affcalendar, $i, "heuredebut_calendrier");
		$rep_affcalendar[$i]["jourfin_calendrier"] = mysql_result($req_affcalendar, $i, "jourfin_calendrier");
		$rep_affcalendar[$i]["heurefin_calendrier"] = mysql_result($req_affcalendar, $i, "heurefin_calendrier");
		$rep_affcalendar[$i]["numero_periode"] = mysql_result($req_affcalendar, $i, "numero_periode");
		$rep_affcalendar[$i]["etabferme_calendrier"] = mysql_result($req_affcalendar, $i, "etabferme_calendrier");
		$rep_affcalendar[$i]["etabvacances_calendrier"] = mysql_result($req_affcalendar, $i, "etabvacances_calendrier");
			// �tablissement ouvert ou ferm� ?
			if ($rep_affcalendar[$i]["etabferme_calendrier"] == "1") {
				$ouvert_ferme = "ouvert";
			}
			else $ouvert_ferme = "ferm�";
			// Quelles classes sont concern�es
			$expl_aff = explode(";", ($rep_affcalendar[$i]["classe_concerne_calendrier"]));
			// Attention, si on compte l'explode, on a une ligne de trop
			if ($expl_aff == "0" OR $rep_affcalendar[$i]["classe_concerne_calendrier"] == "0") {
				$aff_classe_concerne = "<span class=\"legende\">Toutes</span>";
			}
			else {
				$contenu_infobulle = "<font style=\"color: brown;\">".(count($expl_aff) - 1)." classe(s).</font><br />";
				for ($t=0; $t<(count($expl_aff) - 1); $t++) {
					$req_nomclasse = mysql_fetch_array(mysql_query("SELECT nom_complet FROM classes WHERE id = '".$expl_aff[$t]."'"));
					$contenu_infobulle .= $req_nomclasse["nom_complet"].'<br />';
				}
				//$aff_classe_concerne = aff_popup("Voir", "edt", "Classes concern�es", $contenu_infobulle);
				$id_div = "periode".$rep_affcalendar[$i]["id_calendrier"];
				$aff_classe_concerne = "<a href=\"#\" onmouseover=\"afficher_div('".$id_div."','Y',10,10);return false;\" onmouseout=\"cacher_div('".$id_div."');\">Liste</a>\n".creer_div_infobulle($id_div, "Liste des classes", "#330033", $contenu_infobulle, "#FFFFFF", 15,0,"n","n","y","n");
			} // else

			// On enl�ve les secondes � l'affichage
			$explode_deb = explode(":", $rep_affcalendar[$i]["heuredebut_calendrier"]);
			$rep_affcalendar[$i]["heuredebut_calendrier"] = $explode_deb[0].":".$explode_deb[1];
			$explode_fin = explode(":", $rep_affcalendar[$i]["heurefin_calendrier"]);
			$rep_affcalendar[$i]["heurefin_calendrier"] = $explode_fin[0].":".$explode_fin[1];

		// Afficher de deux couleurs diff�rentes

		if ($a == 1) {
			$class_tr = "ligneimpaire";
			$a ++;
		}
		elseif ($a == 2) {
			$class_tr = "lignepaire";
			$a = 1;
		}
		echo '
	<tr class="'.$class_tr.'">
		<td>'.$rep_affcalendar[$i]["nom_calendrier"].'</td>
		<td>'.$aff_classe_concerne.'</td>
		<td>'.$rep_affcalendar[$i]["jourdebut_calendrier"].'</td>
		<td>'.$rep_affcalendar[$i]["heuredebut_calendrier"].'</td>
		<td>'.$rep_affcalendar[$i]["jourfin_calendrier"].'</td>
		<td>'.$rep_affcalendar[$i]["heurefin_calendrier"].'</td>
		<!--<td>'.$rep_affcalendar[$i]["numero_periode"].'</td>-->
		<td>'.$ouvert_ferme.'</td>
		<td class="modif_supr"><a href="edt_calendrier.php?calendrier=ok&amp;modifier='.$rep_affcalendar[$i]["id_calendrier"].'"><img src="../images/icons/configure.png" title="Modifier" alt="Modifier" /></a></td>
		<td class="modif_supr"><a href="edt_calendrier.php?calendrier=ok&amp;supprimer='.$rep_affcalendar[$i]["id_calendrier"].'" onClick="return confirm(\'Confirmez-vous cette suppression ?\')"><img src="../images/icons/delete.png" title="Supprimer" alt="Supprimer" /></a></td>
	</tr>
		';
	}
echo '
</table>
</fieldset>
<br />
';
/* fin de l'affichage des p�riodes d�j� pr�sentes dans Gepi
  D�but de l'affichage pour enregistrer de nouvelles p�riodes */
if ($new_periode == "ok") {
	// On affiche le formulaire pour entrer les "new_periode"
	echo '
<fieldset id="saisie_new_periode">
	<legend>Saisir une nouvelle p�riode pour le calendrier</legend>

		<form name="nouvelle_periode" action="edt_calendrier.php" method="post">
			<input type="hidden" name="calendrier" value="ok" />
			<input type="hidden" name="new_periode" value="ok" />

	<div id="div_classes_concernees">

		';
	// On affiche la liste des classes
	$tab_select = renvoie_liste("classe");

	echo '
	<table>
		<tr valign="top" align="right"><td>
			';
	// Choix des classes sur 3 (ou 4) colonnes
		$modulo = count($tab_select) % 3;
			// Calcul du nombre d'entr�e par colonne ($ligne)
		if ($modulo !== 0) {
			$calcul = count($tab_select) / 3;
			$expl = explode(".", $calcul);
			$ligne = $expl[0];
		}else {
			$ligne = count($tab_select) / 3;
		}

	// Par d�faut, tous les checkbox sont coch�s
	$aff_checked = " checked='checked'";

	// On affiche la premi�re colonne
for($i=0; $i<$ligne; $i++) {

	echo
		$tab_select[$i]["classe"].'
			<label>
				<input name="classes_concernees[]" value="'.$tab_select[$i]["id"].'" id="case_1_'.$tab_select[$i]["id"].'"'.$aff_checked.' type="checkbox" />
			</label><br />
		';
}

echo '
		</td><td>
	';

for($i=$ligne; $i<($ligne*2); $i++) {
	// On affiche la deuxi�me colonne
	echo
		$tab_select[$i]["classe"].'
			<label>
				<input name="classes_concernees[]" value="'.$tab_select[$i]["id"].'" id="case_1_'.$tab_select[$i]["id"].'"'.$aff_checked.' type="checkbox" />
			</label><br />
		';
}

echo '
		</td><td>
	';
for($i=($ligne*2); $i<($ligne*3); $i++) {
	// On affiche la troisi�me colonne
	echo
		$tab_select[$i]["classe"].'
			<label>
				<input name="classes_concernees[]" value="'.$tab_select[$i]["id"].'" id="case_1_'.$tab_select[$i]["id"].'"'.$aff_checked.' type="checkbox" />
			</label><br />
		';
}
echo '
		</td>
	';
// s'il y a une quatri�me colonne, on l'affiche
if ($modulo !== 0) {
	echo '
		<td>
		';
	for($i=($ligne*3); $i<count($tab_select); $i++) {
		echo
		$tab_select[$i]["classe"].'
			<label>
				<input name="classes_concernees[]" value="'.$tab_select[$i]["id"].'" id="case_1_'.$tab_select[$i]["id"].'"'.$aff_checked.' type="checkbox" />
			</label><br />
		';
	}
	echo '</td>';
	}


	echo '
		</tr>
	</table>
	</div>
		<p>
			<input type="text" name="nom_periode" maxlenght="100" size="30" value="Nouvelle p�riode" />
			<span class="legende">Nom de la p�riode</span>
		</p>
		<p>

		<input type="text" name="jour_debut" maxlenght="10" size="10" value="'.$date_jour.'" />
		<a href="#calend" onclick="window.open(\'../lib/calendrier/pop.calendrier.php?frm=nouvelle_periode&amp;ch=jour_debut\',\'calendrier\',\'width=350,height=170,scrollbars=0\').focus();">
		<img src="../lib/calendrier/petit_calendrier.gif" alt="" border="0" /></a>
			<span class="legende">Premier jour</span>

			<input type="text" name="heure_deb" maxlenght="5" size="5" value="00:00" />
			<span class="legende">Heure de d�but</span>
		</p>
		<p>

		<input type="text" name="jour_fin" maxlenght="10" size="10" value="jj/mm/YYYY" />
		<a href="#calend" onclick="window.open(\'../lib/calendrier/pop.calendrier.php?frm=nouvelle_periode&amp;ch=jour_fin\',\'calendrier\',\'width=350,height=170,scrollbars=0\').focus();">
		<img src="../lib/calendrier/petit_calendrier.gif" alt="" border="0" /></a>
			<span class="legende">Dernier jour</span>

			<input type="text" name="heure_fin" maxlenght="5" size="5" value="23:59" />
			<span class="legende">Heure de fin</span>
		</p>
		<p>
			<select name="choix_periode">
				<option value="rien">Non</option>';
	// Proposition de d�finition des p�riodes d�j� existantes de la table periodes
	$req_periodes = mysql_query("SELECT nom_periode, num_periode FROM periodes WHERE id_classe = '1'");
	$nbre_periodes = mysql_num_rows($req_periodes);
		$rep_periodes[] = array();
		for ($i=0; $i<$nbre_periodes; $i++) {
			$rep_periodes[$i]["num_periode"] = mysql_result($req_periodes, $i, "num_periode");
			$rep_periodes[$i]["nom_periode"] = mysql_result($req_periodes, $i, "nom_periode");
			echo '
				<option value="'.$rep_periodes[$i]["num_periode"].'">'.$rep_periodes[$i]["nom_periode"].'</option>
				';
		}
	echo '
			</select>
			<span class="legende">P�riode de notes ?</span>
		</p>
		<p>
			<select name="etabferme" />
				<option value="1">Ouvert</option>
				<option value="2">Ferm�</option>
			</select>
			<span class="legende">Etablissement</span>
		</p>
		<p>
			<select name="vacances">
				<option value="0">Cours</option>
				<option value="1">Vacances</option>
			</select>
			<span class="legende">Vacances / Cours</span>
		</p>
			<input type="submit" name="valider" value="enregistrer" />
		</form>
</fieldset>

	';
} // if ($new_periode == "ok")

if (isset($message_new)) {
	echo $message_new;
}

?>

	</div>
<br />
<br />
<?php
// inclusion du footer
require("../lib/footer.inc.php");
?>
