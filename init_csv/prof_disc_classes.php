<?php
@set_time_limit(0);
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

if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}

include("../lib/initialisation_annee.inc.php");
$liste_tables_del = $liste_tables_del_etape_matieres;

//**************** EN-TETE *****************
$titre_page = "Outil d'initialisation de l'ann�e : Importation des mati�res";
require_once("../lib/header.inc");
//************** FIN EN-TETE ***************

$en_tete=isset($_POST['en_tete']) ? $_POST['en_tete'] : "no";

?>
<p class="bold"><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour accueil initialisation</a></p>
<?php

echo "<center><h3 class='gepi'>Sixi�me phase d'initialisation<br />Importation des associations profs-mati�res-classes (enseignements)</h3></center>\n";


if (!isset($_POST["action"])) {
	//
	// On s�lectionne le fichier � importer
	//

	echo "<p>Vous allez effectuer la sixi�me �tape : elle consiste � importer le fichier <b>g_prof_disc_classes.csv</b> contenant les donn�es relatives aux enseignements.</p>\n";
	echo "<p>ATTENTION ! Avec cette op�ration, vous effacez tous les groupes d'enseignement qui avaient �t� d�finis l'ann�e derni�re. Ils seront �cras�s par ceux que vous allez importer avec la proc�dure courante.</p>\n";
	echo "<p>Les champs suivants doivent �tre pr�sents, dans l'ordre, et <b>s�par�s par un point-virgule</b> : </p>\n";
	echo "<ul><li>Login du professeur</li>\n" .
			"<li>Nom court de la mati�re</li>\n" .
			"<li>Le ou les identifiant(s) de classe (s�par�s par un point d'exclamation ; ex : 1S1!1S2)</li>\n" .
			"<li>Type d'enseignement (CG pour enseignement g�n�ral suivi par toute la classe, OPT pour un enseignement optionnel)</li>\n" .
			"</ul>\n";
	echo "<p>Exemple de ligne pour un enseignement g�n�ral :<br />\n" .
			"DUPONT.JEAN;MATHS;1S1;CG<br />\n" .
			"Exemple de ligne pour un enseignement optionnel avec des �l�ves de plusieurs classes :<br />\n" .
			"DURANT.PATRICE;ANGL2;1S1!1S2!1S3;OPT</p>\n";
	echo "<p>Veuillez pr�ciser le nom complet du fichier <b>g_prof_disc_classes.csv</b>.</p>\n";
	echo "<form enctype='multipart/form-data' action='prof_disc_classes.php' method='post'>\n";
	echo add_token_field();
	echo "<input type='hidden' name='action' value='upload_file' />\n";
	echo "<p><input type=\"file\" size=\"80\" name=\"csv_file\" />\n";

	echo "<p><label for='en_tete' style='cursor:pointer;'>Si le fichier � importer comporte une premi�re ligne d'en-t�te (non vide) � ignorer, <br />cocher la case ci-contre</label>&nbsp;<input type='checkbox' name='en_tete' id='en_tete' value='yes' checked /></p>\n";

	echo "<p><input type='submit' value='Valider' />\n";
	echo "</form>\n";

} else {
	//
	// Quelque chose a �t� post�
	//
	if ($_POST['action'] == "save_data") {
		check_token(false);
		//
		// On enregistre les donn�es dans la base.
		// Le fichier a d�j� �t� affich�, et l'utilisateur est s�r de vouloir enregistrer
		//

		$j=0;
		while ($j < count($liste_tables_del)) {
			if (mysql_result(mysql_query("SELECT count(*) FROM $liste_tables_del[$j]"),0)!=0) {
				$del = @mysql_query("DELETE FROM $liste_tables_del[$j]");
			}
			$j++;
		}

		$sql="SELECT * FROM tempo4;";
		$res_tempo4=mysql_query($sql);
		if(mysql_num_rows($res_tempo4)==0) {
			echo "<p style='color:red'>ERREUR&nbsp;: Aucune association professeur/mati�re/classe/type n'a �t� trouv�e&nbsp;???</p>\n";
			echo "<p><br /></p>\n";
			require("../lib/footer.inc.php");
			die();
		}

		//$go = true;
		$i = 0;
		// Compteur d'erreurs
		$error = 0;
		// Compteur d'enregistrement
		$total = 0;
		//while ($go) {
		while ($lig=mysql_fetch_object($res_tempo4)) {
			/*
			$reg_prof = $_POST["ligne".$i."_prof"];
			$reg_matiere = $_POST["ligne".$i."_matiere"];
			$reg_classes = $_POST["ligne".$i."_classes"];
			$reg_type = $_POST["ligne".$i."_type"];
			*/
			$reg_prof = $lig->col1;
			$reg_matiere = $lig->col2;
			$reg_classes = $lig->col3;
			$reg_type = $lig->col4;

			// On nettoie et on v�rifie :
			$reg_prof = preg_replace("/[^A-Za-z0-9._]/","",trim(strtoupper($reg_prof)));
			if (strlen($reg_prof) > 50) $reg_prof = substr($reg_prof, 0, 50);

			$reg_matiere = preg_replace("/[^A-Za-z0-9.\-]/","",trim(strtoupper($reg_matiere)));
			if (strlen($reg_matiere) > 50) $reg_matiere = substr($reg_matiere, 0, 50);

			$reg_classes = preg_replace("/[^A-Za-z0-9.\-!]/","",trim($reg_classes));
			if (strlen($reg_classes) > 2000) $reg_classes = substr($reg_classes, 0, 2000); // C'est juste pour �viter une tentative d'overflow...

			$reg_type = preg_replace("/[^A-Za-z]/","",trim(strtoupper($reg_type)));
			if ($reg_type != "CG" AND $reg_type != "OPT") $reg_type = "";


			// Premi�re �tape : on s'assure que le prof existe. S'il n'existe pas, on laisse tomber.
			$test = mysql_result(mysql_query("SELECT count(login) FROM utilisateurs WHERE login = '" . $reg_prof . "'"),0);
			if ($test == 1) {

				// Le prof existe. cool. Maintenant on r�cup�re la mati�re.
				$test = mysql_query("SELECT nom_complet FROM matieres WHERE matiere = '" . $reg_matiere . "'");

				if (mysql_num_rows($test) == 1) {
					// La mati�re existe
					// On r�cup�re le nom complet de la mati�re
					$reg_matiere_complet = mysql_result($test, 0, "nom_complet");

					// Maintenant on en arrive aux classes
					// On r�cup�re un tableau :
					$reg_classes = explode("!", $reg_classes);

					// On d�termine le type de groupe
					if (count($reg_classes) > 1) {
						// On force le type "OPT" s'il y a plusieurs classes
						$reg_type = "OPT";
					} else {
						if ($reg_type == "") {
							// Si on n'a qu'une seule classe et que rien n'est sp�cifi�, on a par d�faut
							// un cours g�n�ral
							$reg_type = "CG";
						}
					}

					// Si on arrive ici, c'est que normalement tout est bon.
					// On va quand m�me s'assurer qu'on a des classes valides.

					$valid_classes = array();
					foreach ($reg_classes as $classe) {
						$test = mysql_query("SELECT id FROM classes WHERE classe = '" . $classe . "'");
						if (mysql_num_rows($test) == 1) $valid_classes[] = mysql_result($test, 0, "id");
					}

					if (count($valid_classes) > 0) {
						// C'est bon, on a au moins une classe valide. On peut cr�er le groupe !

						$new_group = mysql_query("INSERT INTO groupes SET name = '" . $reg_matiere . "', description = '" . html_entity_decode($reg_matiere_complet) . "'");
						$group_id = mysql_insert_id();
						if (!$new_group) echo mysql_error();
						// Le groupe est cr��. On associe la mati�re.
						$res = mysql_query("INSERT INTO j_groupes_matieres SET id_groupe = '".$group_id."', id_matiere = '" . $reg_matiere . "'");
						if (!$res) echo mysql_error();
						// On associe le prof
						$res = mysql_query("INSERT INTO j_groupes_professeurs SET id_groupe = '" . $group_id . "', login = '" . $reg_prof . "'");
						if (!$res) echo mysql_error();
						// On associe la mati�re au prof
						$res = mysql_query("INSERT INTO j_professeurs_matieres SET id_professeur = '" . $reg_prof . "', id_matiere = '" . $reg_matiere . "'");
						// On associe le groupe aux classes (ou � la classe)
						foreach ($valid_classes as $classe_id) {
							$res = mysql_query("INSERT INTO j_groupes_classes SET id_groupe = '" . $group_id . "', id_classe = '" . $classe_id ."'");
							if (!$res) echo mysql_error();
						}

						// Si le type est � "CG", on associe les �l�ves de la classe au groupe
						if ($reg_type == "CG") {

							// On r�cup�re le nombre de p�riodes pour la classe
							$periods = mysql_result(mysql_query("SELECT count(num_periode) FROM periodes WHERE id_classe = '" . $valid_classes[0] . "'"), 0);
							$get_eleves = mysql_query("SELECT DISTINCT(login) FROM j_eleves_classes WHERE id_classe = '" . $valid_classes[0] . "'");
							$nb = mysql_num_rows($get_eleves);
							for ($e=0;$e<$nb;$e++) {
								$current_eleve = mysql_result($get_eleves, $e, "login");
								for ($p=1;$p<=$periods;$p++) {
									$res = mysql_query("INSERT INTO j_eleves_groupes SET login = '" . $current_eleve . "', id_groupe = '" . $group_id . "', periode = '" . $p . "'");
									if (!$res) echo mysql_error();
								}
							}
						}

						if (!$new_group) {
							$error++;
						} else {
							$total++;
						}
					} // -> Fin du test si on a au moins une classe valide
				} // -> Fin du test o� la mati�re existe

			} // -> Fin du test o� le prof existe

			$i++;
			//if (!isset($_POST['ligne'.$i.'_prof'])) {$go = false;}
		}

		echo "<p>Op�ration termin�e.</p>\n";
		if ($error > 0) echo "<p><font color='red'>Il y a eu " . $error . " erreurs.</font></p>\n";
		if ($total > 0) echo "<p>" . $total . " groupes ont �t� enregistr�s.</p>\n";

		echo "<p><a href='index.php'>Revenir � la page pr�c�dente</a></p>\n";


	} else if ($_POST['action'] == "upload_file") {
		check_token(false);
		//
		// Le fichier vient d'�tre envoy� et doit �tre trait�
		// On va donc afficher le contenu du fichier tel qu'il va �tre enregistr� dans Gepi
		// en proposant des champs de saisie pour modifier les donn�es si on le souhaite
		//

		$csv_file = isset($_FILES["csv_file"]) ? $_FILES["csv_file"] : NULL;

		// On v�rifie le nom du fichier... Ce n'est pas fondamentalement indispensable, mais
		// autant forcer l'utilisateur � �tre rigoureux
		if(strtolower($csv_file['name']) == "g_prof_disc_classes.csv") {

			// Le nom est ok. On ouvre le fichier
			$fp=fopen($csv_file['tmp_name'],"r");

			if(!$fp) {
				// Aie : on n'arrive pas � ouvrir le fichier... Pas bon.
				echo "<p>Impossible d'ouvrir le fichier CSV !</p>\n";
				echo "<p><a href='prof_disc_classes.php'>Cliquer ici </a> pour recommencer !</p>\n";
			} else {

				// Fichier ouvert ! On attaque le traitement

				// On va stocker toutes les infos dans un tableau
				// Une ligne du CSV pour une entr�e du tableau
				$data_tab = array();

				//=========================
				// On lit une ligne pour passer la ligne d'ent�te:
				if($en_tete=="yes") {
					$ligne = fgets($fp, 4096);
				}
				//=========================

				$k = 0;
				while (!feof($fp)) {
					$ligne = fgets($fp, 4096);
					if(trim($ligne)!="") {

						$tabligne=explode(";",$ligne);

						// 0 : Login du prof
						// 1 : nom court de la mati�re
						// 2 : identifiant(s) de l� (des) classe(s) (Format : 1S1!1S2!1S3)
						// 3 : type de groupe (CG || OPT)


						// On nettoie et on v�rifie :
						$tabligne[0] = preg_replace("/[^A-Za-z0-9._]/","",trim(strtoupper($tabligne[0])));
						if (strlen($tabligne[0]) > 50) $tabligne[0] = substr($tabligne[0], 0, 50);
			
						$tabligne[1] = preg_replace("/[^A-Za-z0-9.\-]/","",trim(strtoupper($tabligne[1])));
						if (strlen($tabligne[1]) > 50) $tabligne[1] = substr($tabligne[1], 0, 50);
			
						$tabligne[2] = preg_replace("/[^A-Za-z0-9.\-!]/","",trim($tabligne[2]));
						if (strlen($tabligne[2]) > 2000) $tabligne[2] = substr($tabligne[2], 0, 2000);
			
						$tabligne[3] = preg_replace("/[^A-Za-z]/","",trim(strtoupper($tabligne[3])));
						if ($tabligne[3] != "CG" AND $tabligne[3] != "OPT") $tabligne[3] = "";



						$data_tab[$k] = array();

						$data_tab[$k]["prof"] = $tabligne[0];
						$data_tab[$k]["matiere"] = $tabligne[1];
						$data_tab[$k]["classes"] = $tabligne[2];
						$data_tab[$k]["type"] = $tabligne[3];
					}
					$k++;
				}

				fclose($fp);

				// Fin de l'analyse du fichier.
				// Maintenant on va afficher tout �a.

				$nb_error=0;

				$sql="CREATE TABLE IF NOT EXISTS tempo4 ( col1 varchar(100) NOT NULL default '', col2 varchar(100) NOT NULL default '', col3 varchar(100) NOT NULL default '', col4 varchar(100) NOT NULL default '');";
				$res_tempo4=mysql_query($sql);

				$sql="TRUNCATE tempo4;";
				$res_tempo4=mysql_query($sql);

				echo "<form enctype='multipart/form-data' action='prof_disc_classes.php' method='post'>\n";
				echo add_token_field();
				echo "<input type='hidden' name='action' value='save_data' />\n";
				echo "<table border='1' class='boireaus' summary='Prof/mati�re/classe/type'>\n";
				echo "<tr><th>Login prof</th><th>Mati�re</th><th>Classe(s)</th><th>Type</th></tr>\n";

				$alt=1;
				for ($i=0;$i<$k-1;$i++) {
					$alt=$alt*(-1);
					echo "<tr class='lig$alt'>\n";
					echo "<td>\n";
					$sql="INSERT INTO tempo4 SET col1='".addslashes($data_tab[$i]["prof"])."',
					col2='".addslashes($data_tab[$i]["matiere"])."',
					col3='".addslashes($data_tab[$i]["classes"])."',
					col4='".addslashes($data_tab[$i]["type"])."';";
					$insert=mysql_query($sql);
					if(!$insert) {
						echo "<span style='color:red'>";
						echo $data_tab[$i]["prof"];
 						echo "</span>";
						$nb_error++;
					}
					else {
						echo $data_tab[$i]["prof"];
					}
					//echo "<input type='hidden' name='ligne".$i."_prof' value='" . $data_tab[$i]["prof"] . "' />\n";
					echo "</td>\n";
					echo "<td>\n";
					echo $data_tab[$i]["matiere"];
					//echo "<input type='hidden' name='ligne".$i."_matiere' value='" . $data_tab[$i]["matiere"] . "' />\n";
					echo "</td>\n";
					echo "<td>\n";
					echo $data_tab[$i]["classes"];
					//echo "<input type='hidden' name='ligne".$i."_classes' value='" . $data_tab[$i]["classes"] . "' />\n";
					echo "</td>\n";
					echo "<td>\n";
					echo $data_tab[$i]["type"];
					//echo "<input type='hidden' name='ligne".$i."_type' value='" . $data_tab[$i]["type"] . "' />\n";
					echo "</td>\n";
					echo "</tr>\n";
				}

				echo "</table>\n";

				if($nb_error>0) {
					echo "<span style='color:red'>$nb_error erreur(s) d�tect�e(s) lors de la pr�paration.</style><br />\n";
				}

				echo "<input type='submit' value='Enregistrer' />\n";

				echo "</form>\n";
			}

		} else if (trim($csv_file['name'])=='') {

			echo "<p>Aucun fichier n'a �t� s�lectionn� !<br />\n";
			echo "<a href='prof_disc_classes.php'>Cliquer ici </a> pour recommencer !</p>\n";

		} else {
			echo "<p>Le fichier s�lectionn� n'est pas valide !<br />\n";
			echo "<a href='prof_disc_classes.php'>Cliquer ici </a> pour recommencer !</p>\n";
		}
	}
}
echo "<p><br /></p>\n";
require("../lib/footer.inc.php");
?>