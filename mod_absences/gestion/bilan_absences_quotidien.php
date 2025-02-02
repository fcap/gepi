<?php

/**
 * @version $Id$
 *
 * Fichier destin� � visionner le bilan de la journ�e des absences heure par heure et cours par cours
 * en ordonnant le classement des �l�ves par classe et par ordre alphab�tique.
 *
 * @copyright 2007
 */

$niveau_arbo = 2;
// Initialisations files
require_once("../../lib/initialisations.inc.php");
// Les fonctions utiles
include("../lib/functions.php");
require_once("../../edt_organisation/fonctions_edt.php");
require_once("../../edt_organisation/fonctions_calendrier.php");

// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
	header("Location: ../../utilisateurs/mon_compte.php?change_mdp=yes");
	die();
} else if ($resultat_session == '0') {
    header("Location: ../../logout.php?auto=1");
die();
};

// S�curit�
if (!checkAccess()) {
    header("Location: ../../logout.php?auto=1");
die();
}

// Insertion du style sp�cifique
$style_specifique = "mod_absences/gestion/style_absences";

//+++++++++++++++++++++++++++++++++
//++++++++++HEADER+++++++++++++++++
$titre_page = "Bilan quotidien des absences.";
require_once("../../lib/header.inc");
//+++++++++FIN HEADER++++++++++++++

// Initialisation des variables
$date_choisie = isset($_POST["date_choisie"]) ? $_POST["date_choisie"] : (date("d/m/Y"));
$choix_date = explode("/", $date_choisie);
$date_choisie_ts = mktime(0,0,0, $choix_date[1], $choix_date[0], $choix_date[2]);

// On r�cup�re le nom des cr�neaux

$creneaux = retourne_creneaux();
// On r�cup�re le nombre de cr�neaux
$nb_creneaux = count($creneaux);

// Fonctions des absences
	function suivi_absence($creneau_id, $eleve_id, $date_choisie){
		// On r�cup�re les horaires de d�but du cr�neau en question et on les transforme en timestamp UNIX
			$choix_date = explode("/", $date_choisie);
			$date_choisie_ts = mktime(0,0,0, $choix_date[1], $choix_date[0], $choix_date[2]);
		if (date("w", $date_choisie_ts) == getSettingValue("creneau_different")) {
			$req_sql = mysql_query("SELECT heuredebut_definie_periode, heurefin_definie_periode FROM edt_creneaux_bis WHERE id_definie_periode = '".$creneau_id."'");
		}
		else {
			$req_sql = mysql_query("SELECT heuredebut_definie_periode, heurefin_definie_periode FROM edt_creneaux WHERE id_definie_periode = '".$creneau_id."'");
		}
		$rep_sql = mysql_fetch_array($req_sql);
		$heuredeb = explode(":", $rep_sql["heuredebut_definie_periode"]);
		$heurefin = explode(":", $rep_sql["heurefin_definie_periode"]);
		//$d_date = explode("/", $d_date_absence_eleve);
		$ts_heuredeb = mktime($heuredeb[0], $heuredeb[1], 0, $choix_date[1], $choix_date[0], $choix_date[2]);
		$ts_heurefin = mktime($heurefin[0], $heurefin[1], 0, $choix_date[1], $choix_date[0], $choix_date[2]);
		// On teste si l'�l�ve �tait absent ou en retard le cours du cr�neau (on ne teste que le d�but du cr�neau)
		$req = mysql_query("SELECT id, retard_absence FROM absences_rb
								WHERE eleve_id = '".$eleve_id."' AND
								(
									(
										debut_ts BETWEEN '" . $ts_heuredeb . "' AND '" . $ts_heurefin . "'
										AND fin_ts BETWEEN '" . $ts_heuredeb . "' AND '" . $ts_heurefin . "'
							        )
									OR
									(
					    	     		'" . $ts_heuredeb . "' BETWEEN debut_ts AND fin_ts
					        	 		OR '" . $ts_heurefin . "' BETWEEN debut_ts AND fin_ts
									)
									AND debut_ts != '" . $ts_heurefin . "'
									AND fin_ts != '" . $ts_heuredeb . "'
								)");


		$rep = mysql_fetch_array($req);
			// S'il est marqu� absent A -> fond rouge
		if ($rep["retard_absence"] == "A") {
			return "<td style=\"border: 1px solid black; background-color: #ffd4d4; color: red;\"><b>A</b></td>";
		} elseif ($rep["retard_absence"] == "R") {
			// S'il est marqu� en retard R -> fond vert
			return "<td style=\"border: 1px solid black; background-color: #d7ffd4; color: green;\"><b>R</b></td>";
		} else {
			return "<td style=\"border: 1px solid black;\"></td>";
		}
	}

?>
<form name="autre_date" method="post" action="bilan_absences_quotidien.php">

	<a href="./voir_absences_viescolaire.php">
		<img src="../../images/icons/back.png" alt="Retour" title="Retour" class="back_link" />&nbsp;Retour
	</a> -

	<label for="dateChoisie" style="cursor: pointer;">Voir le bilan du&nbsp;</label>
	<input type="text" name="date_choisie" id="dateChoisie" style="maxlenght: 10;" size="10" value="<?php echo $date_choisie; ?>" />
	<a href="#calend" onclick="window.open('../../lib/calendrier/pop.calendrier.php?frm=autre_date&amp;ch=date_choisie','calendrier','width=350,height=170,scrollbars=0').focus();">
		<img src="../../lib/calendrier/petit_calendrier.gif" alt="" border="0" />
	</a>
	<input type="submit" name="valider" title="valider" />

	&nbsp;-&nbsp;
	<a href="bilan_absences_quotidien_pdf.php?date_choisie=<?php echo $date_choisie; ?>" title="exporter au format PDF">Version PDF</a>


</form>

	<h3 class="gepi" style="margin-bottom: 2px; margin-left: 4px;">Bilan des absences du <?php echo date_frl(date_sql($date_choisie)); ?>.</h3>
<table style="border: 1px solid black;" cellpadding="5" cellspacing="5">

	<tr>
	<td colspan="<?php echo($nb_creneaux + 2); ?>">
		<table border="0" cellspacing="0" cellpadding="1">
		<tr>
		<td style="background-color: #d7ffd4; width: 15px; color: green;"><b>R</b></td><td> Retard</td><td></td>
		<td style="background-color: #ffd4d4; width: 15px; color: red;"><b>A</b></td><td> Absence</td>
		</tr>
		</table>
	</td>
	</tr>

	<tr>
		<th style="border: 1px solid black; background-color: grey;">Classe</th>
		<th style="border: 1px solid black; background-color: grey; width: 300px;">Nom Pr&eacute;nom</th>
<?php
		//afficher les cr�neaux
			$i = 0;
		while($i < $nb_creneaux){
			echo "<th style=\"border: 1px solid black; background-color: grey;\">".$creneaux[$i]."</th>\n";
			$i++;
		}
?>
	</tr>

<?php
// ===================== Quelques variables utiles ===============
	// On d�termine le jour en Fran�ais actuel
	$jour_choisi = retourneJour(date("w", $date_choisie_ts));
	$query = mysql_query("SELECT ouverture_horaire_etablissement, fermeture_horaire_etablissement FROM horaires_etablissement WHERE jour_horaire_etablissement = '".$jour_choisi."'");
	$attention = ''; // message de pr�vention au cas o� $query ne retourne rien

	$nbre_rep = mysql_num_rows($query);
	if ($nbre_rep >= 1) {
		// Avec le r�sultat, on calcule les timestamps UNIX
		$req = mysql_fetch_array($query);
		$rep_deb = explode(":", $req["ouverture_horaire_etablissement"]);
		$rep_fin = explode(":", $req["fermeture_horaire_etablissement"]);
		$time_actu_deb = mktime($rep_deb[0], $rep_deb[1], 0, $choix_date[1], $choix_date[0], $choix_date[2]);
		$time_actu_fin = mktime($rep_fin[0], $rep_fin[1], 0, $choix_date[1], $choix_date[0], $choix_date[2]);
	}else{
		// Si on ne r�cup�re rien, on donne par d�faut les ts du jour actuel
		$time_actu_deb = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		$time_actu_fin = mktime(23, 59, 0, date("m"), date("d"), date("Y"));
		// et on affiche un petit message
		$attention = "L'�tablissement est cens� �tre ferm� aujourd'hui.";
	}

// Affichage des noms r�partis par classe
$req_classe = mysql_query("SELECT id, classe FROM classes ORDER BY classe");
$nbre = mysql_num_rows($req_classe);

for($i = 0; $i < $nbre; $i++) {
	// On r�cup�re le nom de toutes les classes
	$rep_classe[$i]["classe"] = mysql_result($req_classe, $i, "classe");
	$rep_classe[$i]["id"] = mysql_result($req_classe, $i, "id");
	echo '
		<tr>
			<td><a href="bilan_absences_classe.php?id_classe='.$rep_classe[$i]["id"].'">'.$rep_classe[$i]["classe"].'</a></td>
			<td colspan="'.($nb_creneaux + 1).'"></td>
		</tr>
		';
	// On traite alors l'affichage de tous les �l�ves de chaque classe
	$req_absences = mysql_query("SELECT DISTINCT eleve_id FROM absences_rb
									WHERE eleve_id != 'appel' AND
									(
										(
										debut_ts BETWEEN '" . $time_actu_deb . "' AND '" . $time_actu_fin . "'
										AND fin_ts BETWEEN '".$time_actu_deb . "' AND '" . $time_actu_fin . "'
       		  							)
										OR
       		  							(
										'" . $time_actu_deb . "' BETWEEN debut_ts AND fin_ts
										OR '" . $time_actu_fin . "' BETWEEN debut_ts AND fin_ts
       		  							)
										AND debut_ts != '" . $time_actu_fin . "'
         	  							AND fin_ts != '" . $time_actu_deb . "'
									) ORDER BY eleve_id");

	$nbre_a = mysql_num_rows($req_absences);

	for($b = 0; $b < $nbre_a; $b++){
		$rep_absences[$b]["eleve_id"] = mysql_result($req_absences, $b, "eleve_id");
		$req_id_classe = mysql_fetch_array(mysql_query("SELECT id_classe FROM j_eleves_classes WHERE login = '".$rep_absences[$b]["eleve_id"]."'"));

		// On affiche l'�l�ve en fonction de la classe � laquelle il appartient
		if ($rep_classe[$i]["id"] == $req_id_classe["id_classe"]) {
			// On r�cup�re nom et pr�nom de l'�l�ve
			$rep_nom = mysql_fetch_array(mysql_query("SELECT nom, prenom FROM eleves WHERE login = '".$rep_absences[$b]["eleve_id"]."'"));
			echo '<tr>
			<td></td>
			<td>'.$rep_nom["nom"].' '.$rep_nom["prenom"].'</td>
			';
			// On traite alors pour chaque cr�neau
			if (getSettingValue("creneau_different") != 'n') {
				if (date("w") == getSettingValue("creneau_different")) {
					$req_creneaux = mysql_query("SELECT id_definie_periode FROM edt_creneaux_bis WHERE type_creneaux != 'pause'");
				}else {
					$req_creneaux = mysql_query("SELECT id_definie_periode FROM edt_creneaux WHERE type_creneaux != 'pause' ORDER BY heuredebut_definie_periode");
				}
			}else {
				$req_creneaux = mysql_query("SELECT id_definie_periode FROM edt_creneaux WHERE type_creneaux != 'pause' ORDER BY heuredebut_definie_periode");
			}
			$nbre_creneaux = mysql_num_rows($req_creneaux);
			for($a=0; $a<$nbre_creneaux; $a++){
				$id_creneaux[$a]["id"] = mysql_result($req_creneaux, $a, "id_definie_periode");
				echo '
				'.suivi_absence($id_creneaux[$a]["id"], $rep_absences[$b]["eleve_id"], $date_choisie);
			} // for $a

			echo '
			</tr>';
		} else {
			echo "";
		}
	} // for $b
} // for $i
?>
</table>

<h5>Impression faite le <?php echo date("d/m/Y - h:i"); ?>.</h5>
<?php
echo '<p class="red">'.$attention.'</p>'; // message d'information si le jour demand� est un jour ferm� normalement
require("../../lib/footer.inc.php");
?>