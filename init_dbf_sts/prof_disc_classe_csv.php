<?php
@set_time_limit(0);
/*
* $Id$
* MODIF: boireaus AFFICHAGE DE COMMENTAIRES...
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
extract($_POST, EXTR_OVERWRITE);

// Resume session
$resultat_session = $session_gepi->security_check();
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

// Page bourrin�e... la gestion du token n'est pas faite... et ne sera faite que si quelqu'un utilise encore ce mode d'initialisation et le manifeste sur la liste de diffusion gepi-users
check_token();

//=====================================
// AJOUT: boireaus
//$debug=1;
$debug=0;

if(isset($_GET['debug'])){
	if($_GET['debug']=="1"){
		$debug=1;
	}
	else{
		$debug=0;
	}
}

function affiche_debug($texte){
	global $debug;
	if($debug==1){
		echo "$texte\n";
	}
}

//$debug=1;
//=====================================


//**************** EN-TETE *****************
$titre_page = "Outil d'initialisation de l'ann�e : Importation des relations professeurs/classes/mati�res";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
?>
<p class=bold><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour accueil initialisation</a></p>
<?php

// On v�rifie si l'extension d_base est active
//verif_active_dbase();

echo "<center><h3 class='gepi'>Cinqui�me phase d'initialisation" .
		"<br />Affectation des mati�res � chaque professeur," .
		"<br />Affectation des professeurs dans chaque classe," .
		"<br />Importation des options suivies par les �l�ves" .
		"</h3></center>";

echo "<h3 class='gepi'>Premi�re �tape : affectation des mati�res � chaque professeur et affectation des professeurs dans chaque classe.</h3>";

if (!isset($step1)) {
	$test = mysql_result(mysql_query("SELECT count(*) FROM j_groupes_professeurs"),0);
	if ($test != 0) {
		echo "<p><b>ATTENTION ...</b><br />";
		echo "Des donn�es concernant l'affectation de professeurs dans des classes sont actuellement pr�sentes dans la base GEPI<br /></p>";
		echo "<p>Si vous poursuivez la proc�dure ces donn�es seront effac�es.</p>";
		echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post'>";
		echo "<input type=hidden name='step1' value='y' />";
		echo "<input type='submit' name='confirm' value='Poursuivre la proc�dure' />";
		echo "</form>";
		die();
	}
}

if (!isset($is_posted)) {
	$del = @mysql_query("DELETE FROM j_groupes_professeurs");
	$del = @mysql_query("DELETE FROM j_professeurs_matieres");


	echo "<p>Importation des fichiers <b>F_men.csv</b> et <b>F_gpd.csv</b> contenant les donn�es de relations entre professeurs, mati�re et classes.";
	echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method=post>";
	echo "<p>Veuillez pr�ciser le nom complet du fichier <b>F_men.csv</b>.";
	echo "<p><input type='file' size='80' name='dbf_file' />";
	echo "<p>Veuillez pr�ciser le nom complet du fichier <b>F_gpd.csv</b>.";
	echo "<p><input type='file' size='80' name='dbf_file2' />";
	echo "<input type='hidden' name='is_posted' value='yes' />";
	echo "<input type='hidden' name='step1' value='y' />";
	echo "<p><input type='submit' value='Valider' />";
	echo "</form>";

} else {
	$dbf_file = isset($_FILES["dbf_file"]) ? $_FILES["dbf_file"] : NULL;
	$dbf_file2 = isset($_FILES["dbf_file2"]) ? $_FILES["dbf_file2"] : NULL;
	//if ((strtoupper($dbf_file['name']) == "F_MEN.DBF") or (strtoupper($dbf_file2['name']) == "F_GPD.DBF")) {
	if ((strtoupper($dbf_file['name']) == "F_MEN.CSV") or (strtoupper($dbf_file2['name']) == "F_GPD.CSV")) {

		//$fp = @dbase_open($dbf_file['tmp_name'], 0);
		//$fp2 = @dbase_open($dbf_file2['tmp_name'], 0);
		$fp = fopen($dbf_file['tmp_name'],"r");
		$fp2 = fopen($dbf_file2['tmp_name'],"r");
		if (!$fp) {
			//echo "<p>Impossible d'ouvrir le fichier F_MEN.DBF !</p>";
			//@dbase_close($fp2);
			echo "<p>Impossible d'ouvrir le fichier F_MEN.CSV !</p>";
			fclose($fp2);
			echo "<a href='".$_SERVER['PHP_SELF']."'>Cliquer ici </a> pour recommencer !</center></p>";
		} else if (!$fp2) {
			//echo "<p>Impossible d'ouvrir le fichier F_GPD.DBF !</p>";
			//@dbase_close($fp);
			echo "<p>Impossible d'ouvrir le fichier F_GPD.CSV !</p>";
			fclose($fp);
			echo "<a href='".$_SERVER['PHP_SELF']."'>Cliquer ici </a> pour recommencer !</center></p>";
		} else {
			// on constitue le tableau des champs � extraire dans $fp2
			$tabchamps2 = array("GROCOD","DIVCOD");
			//$nblignes2 = dbase_numrecords($fp2); //number of rows

			unset($en_tete);
			$nblignes2=0;
			while (!feof($fp2)) {
				$ligne = fgets($fp2, 4096);
				if($nblignes2==0){
					// Quand on enregistre en CSV des fichiers DBF de GEP avec OpenOffice, les champs sont renomm�s avec l'ajout de ',...' en fin de nom de champ.
					// On ne retient pas ces ajouts pour $en_tete
					$temp=explode(";",$ligne);
					for($i=0;$i<sizeof($temp);$i++){
						$temp2=explode(",",$temp[$i]);
						$en_tete[$i]=$temp2[0];
					}

					//$en_tete=explode(";",$ligne);
					$nbchamps=sizeof($en_tete);
				}
				$nblignes2++;
			}
			fclose($fp2);
/*
			if (@dbase_get_record_with_names($fp2,1)) {
				$temp = @dbase_get_record_with_names($fp2,1);
			} else {
				echo "<p>Le fichier F_GPD.DBF s�lectionn� n'est pas valide !<br />";
				echo "<a href='".$_SERVER['PHP_SELF']."'>Cliquer ici </a> pour recommencer !</center></p>";
				die();
			}
			$nb = 0;
			foreach($temp as $key => $val){
				$en_tete[$nb] = "$key";
				affiche_debug("\$en_tete[$nb]=$en_tete[$nb]<br />\n");
				$nb++;
			}
			affiche_debug("==========================<br />\n");
*/
			// On range dans tabindice les indices des champs retenus
			// On rep�re l'indice des colonnes GROCOD et DIVCOD
			for ($k = 0; $k < count($tabchamps2); $k++) {
				for ($i = 0; $i < count($en_tete); $i++) {
					//if ($en_tete[$i] == $tabchamps2[$k]) {
					if (trim($en_tete[$i]) == $tabchamps2[$k]) {
						$tabindice2[] = $i;
						affiche_debug("\$tabindice2[]=$i<br />\n");
					}
				}
			}
			affiche_debug("==========================<br />\n");
			//=========================
			$fp2=fopen($dbf_file2['tmp_name'],"r");
			// On lit une ligne pour passer la ligne d'ent�te:
			$ligne = fgets($fp2, 4096);
			//=========================
			for($k = 1; ($k < $nblignes2+1); $k++){
				// Pour chaque ligne du fichier F_GPD, on r�cup�re dans $affiche[0] le GROCOD et dans $affiche[1] le DIVCOD
				//$ligne = dbase_get_record($fp2,$k);
				if(!feof($fp2)){
					$ligne = fgets($fp2, 4096);
					if(trim($ligne)!=""){
						$tabligne=explode(";",$ligne);
						for($i = 0; $i < count($tabchamps2); $i++) {
							//$affiche[$i] = dbase_filter(trim($ligne[$tabindice2[$i]]));
							$affiche[$i] = dbase_filter(trim($tabligne[$tabindice2[$i]]));
							affiche_debug("\$affiche[$i]=$affiche[$i]<br />\n");
						}
						$tab_groupe[$affiche[0]] = $affiche[1];
						affiche_debug("\$tab_groupe[\$affiche[0]]=\$tab_groupe[$affiche[0]]=".$tab_groupe[$affiche[0]]."<br />\n");
						//=======================================================
						// AJOUT: boireaus
						$tab_groupe2[$affiche[0]][] = $affiche[1];
						affiche_debug("\$tab_groupe2[\$affiche[0]][]=\$tab_groupe2[$affiche[0]][]=".$affiche[1]."<br />\n");
						//=======================================================
					}
				}
			}
			//dbase_close($fp2);
			fclose($fp2);
			// Jusque l�, on s'est arrang� pour renseigner un tableau du type:
			// $tab_groupe[GROCOD] = DIVCOD;
			// Du coup, on ne r�cup�re qu'une seule des classes... la derni�re de la liste des classes/membres du groupe.
			// Corrig� avec le tab_groupe2
			affiche_debug("=======================================================<br />\n");
			affiche_debug("On a fini l'�pluchage du fichier F_GPD<br />\n");
			affiche_debug("=======================================================<br />\n");
			unset($en_tete2);

			// on range les classes existantes dans un tableau:
			$req = mysql_query("select id, classe from classes");
			$nb_classes = mysql_num_rows($req);
			$n = 0;

			// on constitue le tableau des champs � extraire
			$tabchamps = array("MATIMN","NUMIND","ELSTCO");
			//$nblignes = dbase_numrecords($fp); //number of rows
			$nblignes=0;
			while (!feof($fp)) {
				$ligne = fgets($fp, 4096);
				if($nblignes==0){
					// Quand on enregistre en CSV des fichiers DBF de GEP avec OpenOffice, les champs sont renomm�s avec l'ajout de ',...' en fin de nom de champ.
					// On ne retient pas ces ajouts pour $en_tete
					$temp=explode(";",$ligne);
					//echo "\$ligne=".$ligne."<br />\n";
					//echo "sizeof(\$temp)=".sizeof($temp)."<br />\n";
					for($i=0;$i<sizeof($temp);$i++){
						$temp2=explode(",",$temp[$i]);
						//$en_tete[$i]=$temp2[0];
						//affiche_debug("\$en_tete[$i]=".$en_tete[$i]."<br />\n");
						$en_tete2[$i]=$temp2[0];
						affiche_debug("\$en_tete2[$i]=".$en_tete2[$i]."<br />\n");
					}
					$nbchamps=sizeof($en_tete2);
					affiche_debug("\$nbchamps=".$nbchamps."<br />\n");
					for($i=0;$i<sizeof($en_tete2);$i++){
						affiche_debug("\$en_tete2[$i]=".$en_tete2[$i]."<br />\n");
					}
				}
				$nblignes++;
			}
			fclose ($fp);

/*
			if (@dbase_get_record_with_names($fp,1)) {
				$temp = @dbase_get_record_with_names($fp,1);
			} else {
				echo "<p>Le fichier s�lectionn� n'est pas valide !<br />";
				echo "<a href='".$_SERVER['PHP_SELF']."'>Cliquer ici </a> pour recommencer !</center></p>";
				die();
			}

			$nb = 0;
			foreach($temp as $key => $val){
				$en_tete[$nb] = "$key";
				affiche_debug("\$en_tete[$nb]=$en_tete[$nb]<br />\n");
				$nb++;
			}
			affiche_debug("==========================<br />\n");
*/
			// On range dans tabindice les indices des champs retenus
			affiche_debug("count(\$tabchamps)=".count($tabchamps)."<br />\n");
			//affiche_debug("count(\$en_tete)=".count($en_tete)."<br />\n");
			affiche_debug("count(\$en_tete2)=".count($en_tete2)."<br />\n");
			for ($k = 0; $k < count($tabchamps); $k++) {
				//for ($i = 0; $i < count($en_tete); $i++) {
				for ($i = 0; $i < count($en_tete2); $i++) {
					//echo "\$en_tete2[$i]=".$en_tete2[$i]." et \$tabchamps[$k]=".$tabchamps[$k]."<br />\n";
					//if ($en_tete2[$i] == $tabchamps[$k]) {
					if (trim($en_tete2[$i]) == $tabchamps[$k]) {
						$tabindice[] = $i;
						affiche_debug("\$tabindice[]=$i<br />\n");
					}
				}
			}
			affiche_debug("==========================<br />\n");
			affiche_debug("==========================<br />\n");

			//=========================
			$fp=fopen($dbf_file['tmp_name'],"r");
			// On lit une ligne pour passer la ligne d'ent�te:
			$ligne = fgets($fp, 4096);
			//=========================
			$nb_reg_no = 0;
			for($k = 1; ($k < $nblignes+1); $k++){
				//$ligne = dbase_get_record($fp,$k);
				if(!feof($fp)){
					$ligne = fgets($fp, 4096);
					if(trim($ligne)!=""){
						$tabligne=explode(";",$ligne);
						for($i = 0; $i < count($tabchamps); $i++) {
							//$affiche[$i] = dbase_filter(trim($ligne[$tabindice[$i]]));
							//affiche_debug("\$affiche[$i]=dbase_filter(trim(\$ligne[$tabindice[$i]]))=$affiche[$i]<br />\n");
							$affiche[$i] = dbase_filter(trim($tabligne[$tabindice[$i]]));
							affiche_debug("\$affiche[$i]=dbase_filter(trim(\$tabligne[".$tabindice[$i]."]))=".$affiche[$i]."<br />\n");
						}
						affiche_debug("==========================<br />\n");
						$req = mysql_query("select col1 from tempo2 where col2 = '$affiche[1]'");
						affiche_debug("On recherche si un prof assure le cours correspondant au groupe: select col1 from tempo2 where col2 = '$affiche[1]'<br />\n");
						$login_prof = @mysql_result($req, 0, 'col1');

						// A REVOIR... IL FAUDRAIT PEUT-ETRE CREER QUAND MEME LE GROUPE POUR L'ASSOCIATION groupe/matiere/classe m�me si il n'y a pas encore de prof (dans le F_MEN)
						if ($login_prof != '') {
							// On relie les profs aux mati�res
							affiche_debug("Un (au moins) prof trouv�: $login_prof<br />\n");
							$verif = mysql_query("select id_professeur from j_professeurs_matieres where (id_matiere='$affiche[0]' and id_professeur='$login_prof')");
							affiche_debug("select id_professeur from j_professeurs_matieres where (id_matiere='$affiche[0]' and id_professeur='$login_prof')<br />\n");
							$resverif = mysql_num_rows($verif);
							if($resverif == 0) {
								// On arrive jusque l�.
								$req = mysql_query("insert into j_professeurs_matieres set id_matiere='$affiche[0]', id_professeur='$login_prof', ordre_matieres=''");
								affiche_debug("insert into j_professeurs_matieres set id_matiere='$affiche[0]', id_professeur='$login_prof', ordre_matieres=''<br />\n");
								echo "<p>Ajout de la correspondance prof/mati�re suivante: $login_prof/$affiche[0]<br />\n";
								if(!$req) $nb_reg_no++;
							}

							// On relie prof, mati�res et classes dans un nouveau groupe de Gepi

							// On vide le tableau de la liste des classes associ�es au groupe:
							unset($tabtmp);

							$test = mysql_query("select id from classes where classe='$affiche[2]'");
							// On initialise le tableau pour que par d�faut il contienne $affiche[2] au cas o� ce serait une classe...
							$tabtmp[0]=$affiche[2];
							affiche_debug("select id from classes where classe='$affiche[2]'<br />\n");
							$nb_test = mysql_num_rows($test) ;
							if ($nb_test == 0) {
								// dans ce cas, $affiche[2] d�signe un groupe
								// on convertit le groupe en classe
					/*
								$affiche[2] = $tab_groupe[$affiche[2]];
								echo "\$affiche[2] = \$tab_groupe[\$affiche[2]] = \$tab_groupe[$affiche[2]] = $affiche[2];<br />\n";
								$test = mysql_query("select id from classes where classe='$affiche[2]'");
								echo "select id from classes where classe='$affiche[2]'<br />\n";
					*/
								// MODIF: boireaus
								// On modifie/remplit le tableau $tabtmp avec la liste des classes associ�es au groupe.
								for($i=0;$i<count($tab_groupe2[$affiche[2]]);$i++){
									$tabtmp[$i]=$tab_groupe2[$affiche[2]][$i];
									affiche_debug("\$tabtmp[$i]=$tabtmp[$i]<br />\n");
								}
							}
							// On boucle sur la liste des classes:
							// On initialise un t�moin pour ne pas recr�er le groupe pour la deuxi�me, troisi�me,... classe:
							$temoin_groupe_deja_cree="non";
							for($i=0;$i<count($tabtmp);$i++){
								$test = mysql_query("select id from classes where classe='$tabtmp[$i]'");

								$id_classe = @mysql_result($test,0,'id');
								affiche_debug("select id from classes where classe='$tabtmp[$i]' donne \$id_classe=$id_classe<br />\n");

								if ($id_classe != '') {
									$sql="SELECT classe FROM classes WHERE id='$id_classe'";
									$res_classe_tmp=mysql_query($sql);
									$lig_classe_tmp=mysql_fetch_object($res_classe_tmp);
									$classe=$lig_classe_tmp->classe;

									echo "<p>\n";

									$verif = mysql_query("select g.id from " .
											"groupes g, j_groupes_matieres jgm, j_groupes_professeurs jgp, j_groupes_classes jgc " .
											"where (" .
											"g.id = jgm.id_groupe and " .
											"jgm.id_matiere='$affiche[0]' and " .
											"jgm.id_groupe = jgp.id_groupe and " .
											"jgp.login = '$login_prof' and " .
											"jgp.id_groupe = jgc.id_groupe and " .
											"jgc.id_classe='$id_classe')");
									affiche_debug("select g.id from " .
											"groupes g, j_groupes_matieres jgm, j_groupes_professeurs jgp, j_groupes_classes jgc " .
											"where (" .
											"g.id = jgm.id_groupe and " .
											"jgm.id_matiere='$affiche[0]' and " .
											"jgm.id_groupe = jgp.id_groupe and " .
											"jgp.login = '$login_prof' and " .
											"jgp.id_groupe = jgc.id_groupe and " .
											"jgc.id_classe='$id_classe')<br />\n");
									$resverif = mysql_num_rows($verif);
									if($resverif == 0) {

										// Avant d'enregistrer, il faut quand m�me v�rifier si le groupe existe d�j� ou pas
										// ... pour cette classe...
										$verif2 = mysql_query("select g.id from " .
											"groupes g, j_groupes_matieres jgm, j_groupes_classes jgc " .
											"where (" .
											"g.id = jgm.id_groupe and " .
											"jgm.id_matiere='$affiche[0]' and " .
											"jgm.id_groupe = jgc.id_groupe and " .
											"jgc.id_classe='$id_classe')");
										affiche_debug("select g.id from " .
											"groupes g, j_groupes_matieres jgm, j_groupes_classes jgc " .
											"where (" .
											"g.id = jgm.id_groupe and " .
											"jgm.id_matiere='$affiche[0]' and " .
											"jgm.id_groupe = jgc.id_groupe and " .
											"jgc.id_classe='$id_classe')<br />\n");
										$resverif2 = mysql_num_rows($verif2);

										if ($resverif2 == 0) {
											affiche_debug("Le groupe n'existe pas encore pour la classe \$id_classe=$id_classe<br />\n");

											// ordre d'affichage par d�faut :
											$priority = sql_query("select priority from matieres where matiere='".$affiche[0]."'");
											if ($priority == "-1") $priority = "0";

											$matiere_nom = mysql_result(mysql_query("SELECT nom_complet FROM matieres WHERE matiere = '" . $affiche[0] . "'"), 0);
											if($temoin_groupe_deja_cree=="non"){
												$res = mysql_query("insert into groupes set name = '" . $affiche[0] . "', description = '" . $matiere_nom . "', recalcul_rang = 'y'");
												affiche_debug("insert into groupes set name = '" . $affiche[0] . "', description = '" . $matiere_nom . "', recalcul_rang = 'y'<br />\n");
												$group_id = mysql_insert_id();
												$temoin_groupe_deja_cree=$group_id;

												//echo "Cr�ation d'un groupe pour la mati�re $affiche[0], \n";
												echo "Cr�ation d'un groupe (n�$group_id) pour la mati�re $affiche[0], \n";


												$res2 = mysql_query("insert into j_groupes_matieres set id_groupe = '" . $group_id . "', id_matiere = '" . $affiche[0] . "'");
												affiche_debug("insert into j_groupes_matieres set id_groupe = '" . $group_id . "', id_matiere = '" . $affiche[0] . "'<br />\n");

												$res4 = mysql_query("insert into j_groupes_professeurs set id_groupe = '" . $group_id . "', login ='" . $login_prof . "'");
												affiche_debug("insert into j_groupes_professeurs set id_groupe = '" . $group_id . "', login ='" . $login_prof . "'<br />\n");
												echo "le professeur $login_prof\n";
											}
											else{
												$group_id=$temoin_groupe_deja_cree;
												affiche_debug("Groupe d�j� cr�� avec \$group_id=$group_id<br />");
											}


											$res3 = mysql_query("insert into j_groupes_classes set id_groupe = '" . $group_id . "', id_classe = '" . $id_classe . "', priorite = '" . $priority . "', coef = '0'");
											affiche_debug("insert into j_groupes_classes set id_groupe = '" . $group_id . "', id_classe = '" . $id_classe . "', priorite = '" . $priority . "', coef = '0'<br />\n");

/*
											$sql="SELECT classe FROM classes WHERE id='$id_classe'";
											$res_classe_tmp=mysql_query($sql);
											$lig_classe_tmp=mysql_fetch_object($res_classe_tmp);
											echo " et la classe $lig_classe_tmp->classe.<br />\n";
*/
											echo " et la classe $classe.<br />\n";

											//$res4 = mysql_query("insert into j_groupes_professeurs set id_groupe = '" . $group_id . "', login ='" . $login_prof . "'");
											//echo "insert into j_groupes_professeurs set id_groupe = '" . $group_id . "', login ='" . $login_prof . "'<br />\n";

											// On ajoute tous les �l�ves de la classe consid�r�e aux groupes. On enl�vera ceux qui ne suivent pas les enseignements
											// � la prochaine �tape

											$get_eleves = mysql_query("SELECT distinct(login) FROM j_eleves_classes WHERE id_classe = '" . $id_classe . "'");
											$nb_eleves = mysql_num_rows($get_eleves);
											affiche_debug("\$nb_eleves=$nb_eleves<br />\n");
											$nb_per = mysql_result(mysql_query("SELECT count(*) FROM periodes WHERE id_classe = '" . $id_classe . "'"), 0);
											affiche_debug("\$nb_per=$nb_per<br />\n");

											// DEBUG :: echo "<br/>Classe : " . $id_classe . "<br/>Nb el. : " . $nb_eleves . "<br/>Nb per.: " . $nb_per . "<br/><br/>";
											if($nb_eleves>0){
												echo "Ajout � ce groupe des �l�ves suivants: ";
												for ($m=0;$m<$nb_eleves;$m++) {
													$e_login = mysql_result($get_eleves, $m, "login");
													for ($n=1;$n<=$nb_per;$n++) {
														$insert_e = mysql_query("INSERT into j_eleves_groupes SET id_groupe = '" . $group_id . "', login = '" . $e_login . "', periode = '" . $n . "'");
														affiche_debug("INSERT into j_eleves_groupes SET id_groupe = '" . $group_id . "', login = '" . $e_login . "', periode = '" . $n . "'<br />\n");
													}
													if($m==0){
														echo "$e_login";
													}
													else{
														echo ", $e_login";
													}
												}
												echo "<br />\n";
											}
											else{
												echo "Aucun �l�ve dans ce groupe???<br />\n";
											}

										} else {
											// Si on est l�, c'est que le groupe existe d�j�, mais que le professeur que l'on
											// est en train de traiter n'est pas encore associ� au groupe
											// C'est le cas de deux professeurs pour un m�me groupe/classe dans une mati�re.
											affiche_debug("Le groupe existe d�j� pour la classe \$id_classe=$id_classe, on ajoute le professeur $login_prof au groupe:<br />\n");
											$group_id = mysql_result($verif2, 0);
											$res = mysql_query("insert into j_groupes_professeurs set id_groupe = '" . $group_id . "', login ='" . $login_prof . "'");
											affiche_debug("insert into j_groupes_professeurs set id_groupe = '" . $group_id . "', login ='" . $login_prof . "'<br />\n");
											echo "Ajout de $login_prof � un groupe existant (<i>plus d'un professeur pour ce groupe</i>).<br />\n";
										}
									}
									echo "</p>\n";
								}
							}
						}
					}
					affiche_debug("===================================================<br />\n");
				}
			}
			//dbase_close($fp);
			fclose($fp);

			if ($nb_reg_no != 0) {
				echo "<p>Lors de l'enregistrement des donn�es il n'y a eu $nb_reg_no erreurs. Essayez de trouvez la cause de l'erreur et recommencez la proc�dure avant de passer � l'�tape suivante.";
			} else {
				echo "<p>L'importation des relations professeurs/mati�res et professeurs/classes dans la base GEPI a �t� effectu�e avec succ�s !<br />Vous pouvez proc�der � l'�tape suivante d'importation des options suivies par les �l�ves.</p>";

			}
			echo "<center><p><a href='init_options.php'>Importer les options suivies par les �l�ves</a></p></center>";
		}
	} else if ((trim($dbf_file['name'])=='') or (trim($dbf_file2['name'])=='')) {
		echo "<p>Veuillez pr�ciser les fichiers !<br />";
		echo "<a href='".$_SERVER['PHP_SELF']."'>Cliquer ici </a> pour recommencer !</center></p>";

	} else {
		echo "<p>Fichier(s) s�lectionn�(s) non valide(s) !<br />";
		echo "<a href='".$_SERVER['PHP_SELF']."'>Cliquer ici </a> pour recommencer !</center></p>";
	}
}
require("../lib/footer.inc.php");
?>