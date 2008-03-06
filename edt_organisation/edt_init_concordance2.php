<?php

/**
 * Fichier qui enregistre les concordances et les cours du fichier edt_init_csv2.php
 *
 * @version $Id$
 *
 * Copyright 2001, 2008 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, St�phane Boireau, Julien Jocal
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

// fonctions edt
require_once("./fonctions_edt.php");
require_once("./edt_init_fonctions.php");

// Resume session
$resultat_session = resumeSession();
if ($resultat_session == 'c') {
   header("Location:utilisateurs/mon_compte.php?change_mdp=yes&retour=accueil#changemdp");
   die();
} else if ($resultat_session == '0') {
    header("Location: ../logout.php?auto=1");
    die();
}

// Initialisation des variables
$etape = isset($_POST["etape"]) ? $_POST["etape"] : NULL;
$concord_csv2 = isset($_POST["concord_csv2"]) ? $_POST["concord_csv2"] : NULL;
$nbre_lignes = isset($_POST["nbre_lignes"]) ? $_POST["nbre_lignes"] : NULL;
//$ = isset($_POST[""]) ? $_POST[""] : NULL;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr">
<head>
	<title>Enregistrer les concordances(2) pour l'import de l'EdT</title>
	<LINK REL="SHORTCUT ICON" href="/gepi_trunk/favicon.ico" />
</head>
<body>
<?php
// traitement des donn�es qui arrivent

if ($etape != NULL) {
	// Alors on peut commencer le traitement

	echo '<p>Etape num�ro : '.$etape.'</p>';
	echo '<p>Nbre lignes : '.$nbre_lignes.'</p>';

	if ($etape != 12 AND $etape != 5) {
			$values = NULL;
		// C'est le cas g�n�ral pour enregistrer les concordances entre le fichier csv et Gepi
		// On r�ceptionne les donn�es et on les rentre dans la base
		for($a = 0; $a < $nbre_lignes; $a++){

			$nom_gepi[$a] = isset($_POST["nom_gepi_".$a]) ? $_POST["nom_gepi_".$a] : NULL;
			$nom_export[$a] = isset($_POST["nom_export_".$a]) ? $_POST["nom_export_".$a] : NULL;

			// On pr�pare la requ�te en v�rifiant qu'elle doit bien �tre construite
			if ($nom_gepi[$a] != '' AND $nom_gepi[$a] != 'none') {
				$values .= "('', '".$etape."', '".$nom_export[$a]."', '".$nom_gepi[$a]."'), ";

			}
		}
		// On envoie toutes les requ�tes d'un coup
		echo $values;
		$envoie = mysql_query("INSERT INTO edt_init (id_init, ident_export, nom_export, nom_gepi)
					VALUE ".$values." ('', ".$etape.", 'fin', 'fin')")
					OR error_reporting('Erreur dans la requ�te $envoie de l\'�tape '.$etape.' : '.mysql_error().'<br />'.$envoie);
		// On r�cup�re le nombre de valeurs enregistr�es et on affiche

		echo '<p>'.$nbre_lignes.' lignes ont �t� enregistr�es dans la base.</p>';

	}elseif($etape == 5){
			$enre = $deja = 0;
		// Ce sont les salles. On va enregistrer celles qui ne sont pas encore dans Gepi
		for($a = 0; $a < $nbre_lignes; $a++){
			$nom_export[$a] = isset($_POST["nom_export_".$a]) ? $_POST["nom_export_".$a] : NULL;
			$test = testerSalleCsv2($nom_export[$a]);
			if ($test == "enregistree") {
				$enre++;
			}elseif($test == "ok"){
				$deja++;
			}
		}
		echo '
		<p>'.$enre.' nouvelles salles ont �t� enregistr�es et '.$deja.' existaient d�j�.</p>';

	}elseif($etape == 12){

		// Ce sont les cours qui arrivent, car on a termin� les concordances
		for($i = 0; $i < $nbre_lignes; $i++){
			// On initialisise toutes les variables et on affiche la valeur de chaque cours
			$ligne = isset($_POST["ligne_".$i]) ? $_POST["ligne_".$i] : NULL;
			echo $ligne.'<br />';
		}
	}
	// On affiche un lien pour revenir � la page de d�part
	echo '
	<a href="edt_init_csv2.php">Retour</a>';

	// On incr�mente le num�ro de l'�tape
	if ($etape != 12) {
		$prochaine_etape = $etape + 1;
		$vers_etape2 = mysql_query("UPDATE edt_init SET nom_export = '".$prochaine_etape."' WHERE ident_export = 'fichierTexte2'");
	}
}

?>
</body>
</html>