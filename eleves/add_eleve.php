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

unset($reg_login);
$reg_login = isset($_POST["reg_login"]) ? $_POST["reg_login"] : NULL;
unset($reg_nom);
$reg_nom = isset($_POST["reg_nom"]) ? $_POST["reg_nom"] : NULL;
unset($reg_prenom);
$reg_prenom = isset($_POST["reg_prenom"]) ? $_POST["reg_prenom"] : NULL;
unset($reg_email);
$reg_email = isset($_POST["reg_email"]) ? $_POST["reg_email"] : NULL;
unset($reg_sexe);
$reg_sexe = isset($_POST["reg_sexe"]) ? $_POST["reg_sexe"] : NULL;
unset($reg_no_nat);
$reg_no_nat = isset($_POST["reg_no_nat"]) ? $_POST["reg_no_nat"] : NULL;
unset($reg_no_gep);
$reg_no_gep = isset($_POST["reg_no_gep"]) ? $_POST["reg_no_gep"] : NULL;
unset($birth_year);
$birth_year = isset($_POST["birth_year"]) ? $_POST["birth_year"] : NULL;
unset($birth_month);
$birth_month = isset($_POST["birth_month"]) ? $_POST["birth_month"] : NULL;
unset($birth_day);
$birth_day = isset($_POST["birth_day"]) ? $_POST["birth_day"] : NULL;

unset($reg_resp1);
$reg_resp1 = isset($_POST["reg_resp1"]) ? $_POST["reg_resp1"] : NULL;
unset($reg_resp2);
$reg_resp2 = isset($_POST["reg_resp2"]) ? $_POST["reg_resp2"] : NULL;

unset($reg_etab);
$reg_etab = isset($_POST["reg_etab"]) ? $_POST["reg_etab"] : NULL;

unset($mode);
$mode = isset($_POST["mode"]) ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : NULL);
unset($order_type);
$order_type = isset($_POST["order_type"]) ? $_POST["order_type"] : (isset($_GET["order_type"]) ? $_GET["order_type"] : NULL);
unset($quelles_classes);
$quelles_classes = isset($_POST["quelles_classes"]) ? $_POST["quelles_classes"] : (isset($_GET["quelles_classes"]) ? $_GET["quelles_classes"] : NULL);
unset($eleve_login);
$eleve_login = isset($_POST["eleve_login"]) ? $_POST["eleve_login"] : (isset($_GET["eleve_login"]) ? $_GET["eleve_login"] : NULL);


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

//================================================
if (isset($_POST['is_posted']) and ($_POST['is_posted'] == "1")) {
	check_token();

	// Détermination du format de la date de naissance
	$call_eleve_test = mysql_query("SELECT naissance FROM eleves WHERE 1");
	$test_eleve_naissance = @mysql_result($call_eleve_test, "0", "naissance");
	$format = strlen($test_eleve_naissance);


	// Cas de la création d'un élève
	$reg_nom = trim($reg_nom);
	$reg_prenom = trim($reg_prenom);
	$reg_email = trim($reg_email);
	if ($reg_resp1 == '(vide)') {$reg_resp1 = '';}
	if (!my_ereg("^[0-9]{4}$", $birth_year)) {$birth_year = "1900";}
	if (!my_ereg("^[0-9]{2}$", $birth_month)) {$birth_month = "01";}
	if (!my_ereg("^[0-9]{2}$", $birth_day)) {$birth_day = "01";}
	if ($format == '10') {
		// YYYY-MM-DD
		$reg_naissance = $birth_year."-".$birth_month."-".$birth_day." 00:00:00";
	}
	else {
		if ($format == '8') {
			// YYYYMMDD
			$reg_naissance = $birth_year.$birth_month.$birth_day;
			settype($reg_naissance,"integer");
		} else {
			// Format inconnu
			$reg_naissance = $birth_year.$birth_month.$birth_day;
		}
	}

	//===========================
	//AJOUT:
	if(!isset($msg)){$msg="";}
	//===========================

	$continue = 'yes';
	if (($reg_nom == '') or ($reg_prenom == '')) {
		$msg = "Les champs nom et prénom sont obligatoires.";
		$continue = 'no';
	}

	//$msg.="\$reg_login=$reg_login<br />";
	//if(isset($eleve_login)){$msg.="\$eleve_login=$eleve_login<br />";}

	// $reg_login non vide correspond à un nouvel élève.
	// On a saisi un login avant de valider
	if (($continue == 'yes') and (isset($reg_login))) {
		$msg = '';
		$ok = 'yes';
		//if (ereg ("^[a-zA-Z_]{1}[a-zA-Z0-9_]{0,11}$", $reg_login)) {
		//if (my_ereg ("^[a-zA-Z_]{1}[a-zA-Z0-9_.]{0,11}$", $reg_login)) {
		if (my_ereg ("^[a-zA-Z_]{1}[a-zA-Z0-9_.]{0,".($longmax_login-1)."}$", $reg_login)) {
			if ($reg_no_gep != '') {
				$test1 = mysql_query("SELECT login FROM eleves WHERE elenoet='$reg_no_gep'");
				$count1 = mysql_num_rows($test1);
				if ($count1 != "0") {
					//$msg .= "Erreur : un élève ayant le même numéro GEP existe déjà.<br />";
					$msg .= "Erreur : un élève ayant le même numéro interne Sconet (elenoet) existe déjà.<br />";
					$ok = 'no';
				}
			}

			if ($reg_no_nat != '') {
				$test2 = mysql_query("SELECT login FROM eleves WHERE no_gep='$reg_no_nat'");
				$count2 = mysql_num_rows($test2);
				if ($count2 != "0") {
					$msg .= "Erreur : un élève ayant le même numéro national existe déjà.";
					$ok = 'no';
				}
			}

			if ($ok == 'yes') {
				$test = mysql_query("SELECT login FROM eleves WHERE login='$reg_login'");
				$count = mysql_num_rows($test);
				if ($count == "0") {

					if(!isset($ele_id)){
						// GENERER UN ele_id...
						/*
						$sql="SELECT MAX(ele_id) max_ele_id FROM eleves";
						$res_ele_id_eleve=mysql_query($sql);
						$max_ele_id = mysql_result($call_resp , 0, "max_ele_id");

						$sql="SELECT MAX(ele_id) max_ele_id FROM responsables2";
						$res_ele_id_responsables2=mysql_query($sql);
						$max_ele_id2 = mysql_result($call_resp , 0, "max_ele_id");

						if($max_ele_id2>$max_ele_id){$max_ele_id=$max_ele_id2;}
						$ele_id=$max_ele_id+1;
						*/
						// PB si on fait ensuite un import sconet le pers_id risque de ne pas correspondre... de provoquer des collisions.
						// QUAND ON LES METS A LA MAIN, METTRE UN ele_id, pers_id,... négatifs?

						// PREFIXER D'UN e...

						$sql="SELECT ele_id FROM eleves WHERE ele_id LIKE 'e%' ORDER BY ele_id DESC";
						$res_ele_id_eleve=mysql_query($sql);
						if(mysql_num_rows($res_ele_id_eleve)>0){
							$tmp=0;
							$lig_ele_id_eleve=mysql_fetch_object($res_ele_id_eleve);
							$tmp=substr($lig_ele_id_eleve->ele_id,1);
							$tmp++;
							$max_ele_id=$tmp;
						}
						else{
							$max_ele_id=1;
						}

						$sql="SELECT ele_id FROM responsables2 WHERE ele_id LIKE 'e%' ORDER BY ele_id DESC";
						$res_ele_id_responsables2=mysql_query($sql);
						if(mysql_num_rows($res_ele_id_responsables2)>0){
							$tmp=0;
							$lig_ele_id_responsables2=mysql_fetch_object($res_ele_id_responsables2);
							$tmp=substr($lig_ele_id_responsables2->ele_id,1);
							$tmp++;
							$max_ele_id2=$tmp;
						}
						else{
							$max_ele_id2=1;
						}

						$tmp=max($max_ele_id,$max_ele_id2);
						$ele_id="e".sprintf("%09d",max($max_ele_id,$max_ele_id2));
					}

					/*
					$reg_data1 = mysql_query("INSERT INTO eleves SET
						no_gep = '".$reg_no_nat."',
						nom='".$reg_nom."',
						prenom='".$reg_prenom."',
						login='".$reg_login."',
						sexe='".$reg_sexe."',
						naissance='".$reg_naissance."',
						elenoet = '".$reg_no_gep."',
						ereno = '".$reg_resp1."',
						ele_id = '".$ele_id."'
						");
					*/
					$reg_data1 = mysql_query("INSERT INTO eleves SET
						no_gep = '".$reg_no_nat."',
						nom='".$reg_nom."',
						prenom='".$reg_prenom."',
						email='".$reg_email ."',
						login='".$reg_login."',
						sexe='".$reg_sexe."',
						naissance='".$reg_naissance."',
						elenoet = '".$reg_no_gep."',
						ele_id = '".$ele_id."',
						date_sortie = NULL
						");

					if($reg_resp1!=""){
						// Quand on laisse '(vide)' pour le choix du responsable, la variable est créée puisque le champ est posté, mais la variable est une chaine vide qui ne doit pas correspondre à une insertion dans responsables2
						$sql="SELECT 1=1 FROM responsables2 WHERE ele_id='$ele_id' AND pers_id='$reg_resp1'";
						$test_resp1=mysql_query($sql);
						if(mysql_num_rows($test_resp1)>0){
							// Il y a déjà une association élève/responsable (c'est bizarre pour un élève que l'on inscrit maintenant???)
							$sql="SELECT 1=1 FROM responsables2 WHERE ele_id='$ele_id' AND pers_id='$reg_resp1' AND resp_legal='2'";
							$test_resp1b=mysql_query($sql);
							if(mysql_num_rows($test_resp1b)==1){
								// Le responsable 2 devient responsable 1.
								$temoin_maj_resp="";
								$sql="SELECT pers_id FROM responsables2 WHERE ele_id='$ele_id' AND pers_id!='$reg_resp1' AND resp_legal='1'";
								$test_resp1c=mysql_query($sql);
								if(mysql_num_rows($test_resp1c)==1){
									$lig_autre_resp=mysql_fetch_object($test_resp1c);
									$sql="UPDATE responsables2 SET resp_legal='2' WHERE ele_id='$ele_id' AND pers_id='$lig_autre_resp->pers_id'";
									$res_update=mysql_query($sql);
									if(!$res_update){
										$msg.="Erreur lors de la mise à jour du responsable $lig_autre_resp->pers_id en responsable légal n°2.<br />\n";
										$temoin_maj_resp="PB";
									}
								}

								if($temoin_maj_resp==""){
									$sql="UPDATE responsables2 SET resp_legal='1' WHERE ele_id='$ele_id' AND pers_id='$reg_resp1'";
									$res_update=mysql_query($sql);
									if(!$res_update){
										$msg.="Erreur lors de la mise à jour du responsable $reg_resp1 en responsable légal n°1.<br />\n";
									}
								}
							}
							// Sinon, l'association est déjà la bonne... pas de changement.
						}
						else{
							// Il n'y a pas encore d'association entre cet élève et ce responsable
							$temoin_maj_resp="";
							$sql="SELECT pers_id FROM responsables2 WHERE ele_id='$ele_id' AND pers_id!='$reg_resp1' AND resp_legal='1'";
							$test_resp1c=mysql_query($sql);
							//if(mysql_num_rows($test_resp1c)==1){
							if(mysql_num_rows($test_resp1c)>0){
								$lig_autre_resp=mysql_fetch_object($test_resp1c);

								// Y avait-il un autre responsable légal n°2?
								$sql="DELETE FROM responsables2 WHERE ele_id='$ele_id' AND resp_legal='2'";
								$res_menage=mysql_query($sql);
								if(!$res_menage){
									$msg.="Erreur lors de la suppression de l'association avec le précédent responsable légal n°2.<br />";
									$temoin_maj_resp="PB";
								}
								else{
									// L'ancien resp_legal 1 devient resp_legal 2
									$sql="UPDATE responsables2 SET resp_legal='2' WHERE ele_id='$ele_id' AND pers_id='$lig_autre_resp->pers_id'";
									$res_update=mysql_query($sql);
									if(!$res_update){
										$msg.="Erreur lors de la mise à jour du responsable $lig_autre_resp->pers_id en responsable légal n°2.<br />\n";
										$temoin_maj_resp="PB";
									}
								}
							}

							if($temoin_maj_resp==""){
								$sql="INSERT INTO responsables2 SET ele_id='$ele_id', pers_id='$reg_resp1', resp_legal='1', pers_contact='1'";
								$reg_data2b=mysql_query($sql);
								if(!$reg_data2b){
									$msg.="Erreur lors de la mise à jour du responsable $reg_resp1 en responsable légal n°1.<br />\n";
								}
							}
						}
					}

					// Régime et établissement d'origine:
					$reg_data3 = mysql_query("INSERT INTO j_eleves_regime SET login='$reg_login', doublant='-', regime='d/p';");
					if ($reg_no_gep != '') {
						//$call_test = mysql_query("SELECT * FROM j_eleves_etablissements WHERE id_eleve = '$reg_login'");
						$call_test = mysql_query("SELECT * FROM j_eleves_etablissements WHERE id_eleve = '$reg_no_gep';");
						$count2 = mysql_num_rows($call_test);
						if ($count2 == "0") {
							if ($reg_etab != "(vide)") {
								//$reg_data2 = mysql_query("INSERT INTO j_eleves_etablissements VALUES ('$reg_login','$reg_etab')");
								$reg_data2 = mysql_query("INSERT INTO j_eleves_etablissements VALUES ('$reg_no_gep','$reg_etab');");
							}
						} else {
							if ($reg_etab != "(vide)") {
								//$reg_data2 = mysql_query("UPDATE j_eleves_etablissements SET id_etablissement = '$reg_etab' WHERE id_eleve='$reg_login'");
								$reg_data2 = mysql_query("UPDATE j_eleves_etablissements SET id_etablissement = '$reg_etab' WHERE id_eleve='$reg_no_gep';");
							} else {
								//$reg_data2 = mysql_query("DELETE FROM j_eleves_etablissements WHERE id_eleve='$reg_login'");
								$reg_data2 = mysql_query("DELETE FROM j_eleves_etablissements WHERE id_eleve='$reg_no_gep';");
							}
						}
					}
					if ((!$reg_data1) or (!$reg_data3)) {
						$msg = "Erreur lors de l'enregistrement des données";
					} elseif ($mode == "unique") {
						$mess=rawurlencode("Elève enregistré !");
						header("Location: index.php?msg=$mess");
						die();
					} elseif ($mode == "multiple") {
						$mess=rawurlencode("Elève enregistré. Vous pouvez saisir l'élève suivant.");
						header("Location: add_eleve.php?mode=multiple&msg=$mess");
						die();
					}
				} else {
					$msg="Un élève portant le même identifiant existe déja !";
				}
			}
		} else {
			$msg="L'identifiant choisi est constitué au maximum de 12 caractères : lettres, chiffres, \"_\" ou \".\" et ne doit pas commencer par un chiffre !";
		}
	} else if ($continue == 'yes') {
		// C'est une mise à jour pour un élève qui existait déjà dans la table 'eleves'.

		// On nettoie les windozeries
		$reg_data = mysql_query("UPDATE eleves SET no_gep = '$reg_no_nat', nom='$reg_nom',prenom='$reg_prenom',email='$reg_email',sexe='$reg_sexe',naissance='".$reg_naissance."', ereno='".$reg_resp1."', elenoet = '".$reg_no_gep."' WHERE login='".$eleve_login."'");
		if (!$reg_data) {
			$msg = "Erreur lors de l'enregistrement des données";
		} else {
			// On met à jour la table utilisateurs si un compte existe pour cet élève
			$test_login = mysql_result(mysql_query("SELECT count(login) FROM utilisateurs WHERE login = '".$eleve_login ."'"), 0);
			if ($test_login > 0) {
				$res = mysql_query("UPDATE utilisateurs SET nom='".$reg_nom."', prenom='".$reg_prenom."', email='".$reg_email."' WHERE login = '".$eleve_login."'");
				//$msg.="TEMOIN test_login puis update<br />";
			}
		}

		if ($reg_no_gep != '') {
			//$call_test = mysql_query("SELECT * FROM j_eleves_etablissements WHERE id_eleve = '$eleve_login'");
			$call_test = mysql_query("SELECT * FROM j_eleves_etablissements WHERE id_eleve = '$reg_no_gep';");
			$count = mysql_num_rows($call_test);
			if ($count == "0") {
				if ($reg_etab != "(vide)") {
					//$reg_data = mysql_query("INSERT INTO j_eleves_etablissements VALUES ('$eleve_login','$reg_etab')");
					$reg_data = mysql_query("INSERT INTO j_eleves_etablissements VALUES ('$reg_no_gep','$reg_etab');");
				}
			} else {
				if ($reg_etab != "(vide)") {
					//$reg_data = mysql_query("UPDATE j_eleves_etablissements SET id_etablissement = '$reg_etab' WHERE id_eleve='$eleve_login'");
					$reg_data = mysql_query("UPDATE j_eleves_etablissements SET id_etablissement = '$reg_etab' WHERE id_eleve='$reg_no_gep';");
				} else {
					//$reg_data = mysql_query("DELETE FROM j_eleves_etablissements WHERE id_eleve='$eleve_login'");
					$reg_data = mysql_query("DELETE FROM j_eleves_etablissements WHERE id_eleve='$reg_no_gep';");
				}
			}
		}

		if (!$reg_data) {
			$msg = "Erreur lors de l'enregistrement des données !";
		} else {
			//$msg = "Les modifications ont bien été enregistrées !";
			// MODIF POUR AFFICHER MES TEMOINS...
			$msg .= "Les modifications ont bien été enregistrées !";
		}

		$temoin_ele_id="";
		$sql="SELECT ele_id FROM eleves WHERE login='$eleve_login'";
		$res_ele_id_eleve=mysql_query($sql);
		if(mysql_num_rows($res_ele_id_eleve)==0){
			$msg.="Erreur: Le champ ele_id n'est pas présent. Votre table 'eleves' n'a pas l'air à jour.<br />";
			$temoin_ele_id="PB";
		}
		else{
			$lig_tmp=mysql_fetch_object($res_ele_id_eleve);
			$ele_id=$lig_tmp->ele_id;
		}


		if($temoin_ele_id==""){
			$sql="SELECT 1=1 FROM responsables2 WHERE ele_id='$ele_id' AND pers_id='$reg_resp1'";
			$test_resp1=mysql_query($sql);
			if(mysql_num_rows($test_resp1)>0){
				// Il y a déjà une association élève/responsable (c'est bizarre pour un élève que l'on inscrit maintenant???)
				$sql="SELECT 1=1 FROM responsables2 WHERE ele_id='$ele_id' AND pers_id='$reg_resp1' AND resp_legal='2'";
				$test_resp1b=mysql_query($sql);
				if(mysql_num_rows($test_resp1b)==1){
					// Le responsable 2 devient responsable 1.
					$temoin_maj_resp="";
					$sql="SELECT pers_id FROM responsables2 WHERE ele_id='$ele_id' AND pers_id!='$reg_resp1' AND resp_legal='1'";
					$test_resp1c=mysql_query($sql);
					if(mysql_num_rows($test_resp1c)==1){
						$lig_autre_resp=mysql_fetch_object($test_resp1c);
						$sql="UPDATE responsables2 SET resp_legal='2' WHERE ele_id='$ele_id' AND pers_id='$lig_autre_resp->pers_id'";
						$res_update=mysql_query($sql);
						if(!$res_update){
							$msg.="Erreur lors de la mise à jour du responsable $lig_autre_resp->pers_id en responsable légal n°2.<br />\n";
							$temoin_maj_resp="PB";
						}
					}

					if($temoin_maj_resp==""){
						$sql="UPDATE responsables2 SET resp_legal='1' WHERE ele_id='$ele_id' AND pers_id='$reg_resp1'";
						$res_update=mysql_query($sql);
						if(!$res_update){
							$msg.="Erreur lors de la mise à jour du responsable $reg_resp1 en responsable légal n°1.<br />\n";
						}
					}
				}
				// Sinon, l'association est déjà la bonne... pas de changement.
			}
			else{
				// Il n'y a pas encore d'association entre cet élève et ce responsable
				$temoin_maj_resp="";
				$sql="SELECT pers_id FROM responsables2 WHERE ele_id='$ele_id' AND pers_id!='$reg_resp1' AND resp_legal='1'";
				$test_resp1c=mysql_query($sql);
				//if(mysql_num_rows($test_resp1c)==1){
				if(mysql_num_rows($test_resp1c)>0){
					$lig_autre_resp=mysql_fetch_object($test_resp1c);

					// Y avait-il un autre responsable légal n°2?
					$sql="DELETE FROM responsables2 WHERE ele_id='$ele_id' AND resp_legal='2'";
					$res_menage=mysql_query($sql);
					if(!$res_menage){
						$msg.="Erreur lors de la suppression de l'association avec le précédent responsable légal n°2.<br />";
						$temoin_maj_resp="PB";
					}
					else{
						// L'ancien resp_legal 1 devient resp_legal 2
						$sql="UPDATE responsables2 SET resp_legal='2' WHERE ele_id='$ele_id' AND pers_id='$lig_autre_resp->pers_id'";
						$res_update=mysql_query($sql);
						if(!$res_update){
							$msg.="Erreur lors de la mise à jour du responsable $lig_autre_resp->pers_id en responsable légal n°2.<br />\n";
							$temoin_maj_resp="PB";
						}
					}
				}

				if($temoin_maj_resp==""){
					$sql="INSERT INTO responsables2 SET ele_id='$ele_id', pers_id='$reg_resp1', resp_legal='1', pers_contact='1'";
					$reg_data2b=mysql_query($sql);
					if(!$reg_data2b){
						$msg.="Erreur lors de la mise à jour du responsable $reg_resp1 en responsable légal n°1.<br />\n";
					}
				}
			}
		}

/*
		$sql="SELECT 1=1 FROM responsables2 WHERE ele_id='$ele_id' AND pers_id='$reg_resp1'";
		$test_resp1=mysql_query($sql);
		if(mysql_num_rows($test_resp1)){
			$sql="SELECT 1=1 FROM responsables2 WHERE ele_id='$ele_id' AND pers_id='$reg_resp1' AND resp_legal='2'";
			$test_resp1b=mysql_query($sql);
			if(mysql_num_rows($test_resp1b)==1){
				$sql="SELECT pers_id FROM responsables2 WHERE ele_id='$ele_id' AND pers_id!='$reg_resp1' AND resp_legal='1'";
				$test_resp1c=mysql_query($sql);
				if(mysql_num_rows($test_resp1c)==1){
					$lig_autre_resp=mysql_fetch_object($test_resp1c);
					$sql="UPDATE responsables2 SET resp_legal='2' WHERE ele_id='$ele_id' AND pers_id='$lig_autre_resp->pers_id'";
					$res_update=mysql_query($sql);
				}

				$sql="UPDATE responsables2 SET resp_legal='1' WHERE ele_id='$ele_id' AND pers_id='$reg_resp1'";
				$res_update=mysql_query($sql);
			}
		}
		else{
			$sql="SELECT pers_id FROM responsables2 WHERE ele_id='$ele_id' AND pers_id!='$reg_resp1' AND resp_legal='1'";
			$test_resp1c=mysql_query($sql);
			if(mysql_num_rows($test_resp1c)==1){
				$lig_autre_resp=mysql_fetch_object($test_resp1c);
				$sql="UPDATE responsables2 SET resp_legal='2' WHERE ele_id='$ele_id' AND pers_id='$lig_autre_resp->pers_id'";
				$res_update=mysql_query($sql);
			}

			$sql="INSERT INTO responsables2 SET ele_id='$ele_id', pers_id='$reg_resp1', resp_legal='1', pers_contact='1'";
			$reg_data2b=mysql_query($sql);
		}

		// AJOUTER DES TESTS DE SUCCES DE LA MàJ.
*/

	}
}

//================================================

// On appelle les informations de l'utilisateur pour les afficher :
if (isset($eleve_login)) {
    $call_eleve_info = mysql_query("SELECT * FROM eleves WHERE login='$eleve_login'");
    $eleve_nom = mysql_result($call_eleve_info, "0", "nom");
    $eleve_prenom = mysql_result($call_eleve_info, "0", "prenom");
    $eleve_email = mysql_result($call_eleve_info, "0", "email");
    $eleve_sexe = mysql_result($call_eleve_info, "0", "sexe");
    $eleve_naissance = mysql_result($call_eleve_info, "0", "naissance");
    if (strlen($eleve_naissance) == 10) {
        // YYYY-MM-DD
        $eleve_naissance_annee = substr($eleve_naissance, 0, 4);
        $eleve_naissance_mois = substr($eleve_naissance, 5, 2);
        $eleve_naissance_jour = substr($eleve_naissance, 8, 2);
    } elseif (strlen($eleve_naissance) == 8 ) {
        // YYYYMMDD
        $eleve_naissance_annee = substr($eleve_naissance, 0, 4);
        $eleve_naissance_mois = substr($eleve_naissance, 4, 2);
        $eleve_naissance_jour = substr($eleve_naissance, 6, 2);
    } elseif (strlen($eleve_naissance) == 19 ) {
        // YYYY-MM-DD xx:xx:xx
        $eleve_naissance_annee = substr($eleve_naissance, 0, 4);
        $eleve_naissance_mois = substr($eleve_naissance, 5, 2);
        $eleve_naissance_jour = substr($eleve_naissance, 8, 2);
    } else {
        // Format inconnu
        $eleve_naissance_annee = "??";
        $eleve_naissance_mois = "??";
        $eleve_naissance_jour = "????";
    }
    //$eleve_no_resp = mysql_result($call_eleve_info, "0", "ereno");
    $reg_no_nat = mysql_result($call_eleve_info, "0", "no_gep");
    $reg_no_gep = mysql_result($call_eleve_info, "0", "elenoet");
    $call_etab = mysql_query("SELECT e.* FROM etablissements e, j_eleves_etablissements j WHERE (j.id_eleve='$eleve_login' and e.id = j.id_etablissement)");
    $id_etab = @mysql_result($call_etab, "0", "id");


	if(!isset($ele_id)){
		$ele_id=mysql_result($call_eleve_info, "0", "ele_id");
	}

	$sql="SELECT pers_id FROM responsables2 WHERE ele_id='$ele_id' AND resp_legal='1'";
	$res_resp1=mysql_query($sql);
	if(mysql_num_rows($res_resp1)>0){
		$lig_no_resp1=mysql_fetch_object($res_resp1);
		$eleve_no_resp1=$lig_no_resp1->pers_id;
	}
	else{
		$eleve_no_resp1=0;
	}

	$sql="SELECT pers_id FROM responsables2 WHERE ele_id='$ele_id' AND resp_legal='2'";
	$res_resp2=mysql_query($sql);
	if(mysql_num_rows($res_resp2)>0){
		$lig_no_resp2=mysql_fetch_object($res_resp2);
		$eleve_no_resp2=$lig_no_resp2->pers_id;
	}
	else{
		$eleve_no_resp2=0;
	}


} else {
	// On passe par là au premier accès à la page et si après validation le champ login n'a pas été saisi.
	//echo "\$reg_nom=$reg_nom<br />\n";
    if (isset($reg_nom)) $eleve_nom = $reg_nom;
    if (isset($reg_prenom)) $eleve_prenom = $reg_prenom;
    if (isset($reg_email)) $eleve_email = $reg_email;
    if (isset($reg_sexe)) $eleve_sexe = $reg_sexe;
    if (isset($reg_no_nat)) $reg_no_nat = $reg_no_nat;
    if (isset($reg_no_gep)) $reg_no_gep = $reg_no_gep;
    if (isset($birth_year)) $eleve_naissance_annee = $birth_year;
    if (isset($birth_month)) $eleve_naissance_mois = $birth_month;
    if (isset($birth_day)) $eleve_naissance_jour = $birth_day;
    //$eleve_no_resp = 0;
    $eleve_no_resp1 = 0;
    $eleve_no_resp2 = 0;
    $id_etab = 0;
}


//**************** EN-TETE *****************
$titre_page = "Gestion des élèves | Ajouter/Modifier une fiche élève";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************


if ((isset($order_type)) and (isset($quelles_classes))) {
    echo "<p class=bold><a href=\"index.php?quelles_classes=$quelles_classes&amp;order_type=$order_type\"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>";
} else {
    echo "<p class=bold><a href=\"index.php\"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>";
}


/*
// Désactivé pour permettre de renseigner un ELENOET manquant pour une conversion avec sconet
// Cela a en revanche été conservé sur la page index.php
// On ne devrait donc arriver ici lorsqu'une conversion est réclamée qu'en venant de conversion.php pour remplir un ELENOET
if(!getSettingValue('conv_new_resp_table')){
	$sql="SELECT 1=1 FROM responsables";
	$test=mysql_query($sql);
	if(mysql_num_rows($test)>0){
		echo "<p>Une conversion des données élèves/responsables est requise.</p>\n";
		echo "<p>Suivez ce lien: <a href='../responsables/conversion.php'>CONVERTIR</a></p>\n";
		require("../lib/footer.inc.php");
		die();
	}

	$sql="SHOW COLUMNS FROM eleves LIKE 'ele_id'";
	$test=mysql_query($sql);
	if(mysql_num_rows($test)==0){
		echo "<p>Une conversion des données élèves/responsables est requise.</p>\n";
		echo "<p>Suivez ce lien: <a href='../responsables/conversion.php'>CONVERTIR</a></p>\n";
		require("../lib/footer.inc.php");
		die();
	}
	else{
		$sql="SELECT 1=1 FROM eleves WHERE ele_id=''";
		$test=mysql_query($sql);
		if(mysql_num_rows($test)>0){
			echo "<p>Une conversion des données élèves/responsables est requise.</p>\n";
			echo "<p>Suivez ce lien: <a href='../responsables/conversion.php'>CONVERTIR</a></p>\n";
			require("../lib/footer.inc.php");
			die();
		}
	}
}
*/


?>
<form enctype="multipart/form-data" action="add_eleve.php" method="post">
<?php
echo add_token_field();
echo "<table>\n";
echo "<tr>\n";
echo "<td>\n";

echo "<table cellpadding='5'>\n";
echo "<tr>\n";

	$photo_largeur_max=150;
	$photo_hauteur_max=150;

	function redimensionne_image($photo){
		global $photo_largeur_max, $photo_hauteur_max;

		// prendre les informations sur l'image
		$info_image=getimagesize($photo);
		// largeur et hauteur de l'image d'origine
		$largeur=$info_image[0];
		$hauteur=$info_image[1];

		// calcule le ratio de redimensionnement
		$ratio_l=$largeur/$photo_largeur_max;
		$ratio_h=$hauteur/$photo_hauteur_max;
		$ratio=($ratio_l>$ratio_h)?$ratio_l:$ratio_h;

		// définit largeur et hauteur pour la nouvelle image
		$nouvelle_largeur=round($largeur/$ratio);
		$nouvelle_hauteur=round($hauteur/$ratio);

		return array($nouvelle_largeur, $nouvelle_hauteur);
	}


    if (isset($eleve_login)) {
        echo "<td>Identifiant GEPI * : </td>
        <td>".$eleve_login."<input type=hidden name='eleve_login' size=20 ";
        if ($eleve_login) echo "value='$eleve_login'";
        echo " /></td>\n";
    } else {
        echo "<td>Identifiant GEPI * : </td>
        <td><input type=text name=reg_login size=20 value=\"\" maxlength='".$longmax_login."' /> (<i>max.$longmax_login caractères</i>)</td>\n";
    }
    ?>
</tr>
<tr>
    <td>Nom * : </td>
    <td><input type=text name='reg_nom' size=20 <?php if (isset($eleve_nom)) { echo "value=\"".$eleve_nom."\"";}?> /></td>
</tr>
<tr>
    <td>Prénom * : </td>
    <td><input type=text name='reg_prenom' size=20 <?php if (isset($eleve_prenom)) { echo "value=\"".$eleve_prenom."\"";}?> /></td>
</tr>
<tr>
    <td>Email : </td>
    <td><input type=text name='reg_email' size=20 <?php if (isset($eleve_email)) { echo "value=\"".$eleve_email."\"";}?> /></td>
</tr>
<tr>
    <td>Identifiant National : </td>
    <?php
    echo "<td><input type='text' name='reg_no_nat' size='20' ";
    if (isset($reg_no_nat)) echo "value=\"".$reg_no_nat."\"";
    echo " /></td>\n";
    ?>
</tr>
<?php
    //echo "<tr><td>Numéro GEP : </td><td><input type=text name='reg_no_gep' size=20 ";
    echo "<tr><td>Numéro interne Sconet (<i>elenoet</i>) : </td><td><input type='text' name='reg_no_gep' size='20' ";
    if (isset($reg_no_gep)) echo "value=\"".$reg_no_gep."\"";
    echo " /></td>\n";


    ?>

</table>
<?php

if(isset($reg_no_gep)){
	$nom_photo = nom_photo($reg_no_gep);
	//if ($nom_photo!="") {
	if ($nom_photo) {
	   // $photo="../photos/eleves/".$nom_photo;
	   // if(file_exists($photo)){
		      echo "<td>\n";
		      $dimphoto=redimensionne_image($photo);
		      echo '<img src="'.$photo.'" style="width: '.$dimphoto[0].'px; height: '.$dimphoto[1].'px; border: 0px; border-right: 3px solid #FFFFFF; float: left;" alt="" />';
		      echo "\n</td>\n";
	    //}
	}
}
echo "</tr>\n";
echo "</table>\n";

if (($reg_no_gep == '') and (isset($eleve_login))) {
   //echo "<font color=red>ATTENTION : Cet élève ne possède pas de numéro GEP. Vous ne pourrez pas importer les absences à partir des fichiers GEP pour cet élèves.</font>\n";
   echo "<font color=red>ATTENTION : Cet élève ne possède pas de numéro interne Sconet (elenoet). Vous ne pourrez pas importer les absences à partir des fichiers GEP/Sconet pour cet élèves.</font>\n";

	$sql="select value from setting where name='import_maj_xml_sconet'";
	$test_sconet=mysql_query($sql);
	if(mysql_num_rows($test_sconet)>0){
		$lig_tmp=mysql_fetch_object($test_sconet);
		if($lig_tmp->value=='1'){
			echo "<br />";
			echo "<font color=red>Vous ne pourrez pas non plus effectuer les mises à jour de ses informations depuis Sconet<br />(<i>l'ELENOET et l'ELE_ID ne correspondront pas aux données de Sconet</i>).</font>\n";
		}
	}
}

?>
<center><table border = '1' CELLPADDING = '5'>
<tr><td><div class='norme'>Sexe : <br />
<?php
if (!(isset($eleve_sexe))) $eleve_sexe="M";
/*
<input type=radio name=reg_sexe value=M <?php if ($eleve_sexe == "M") { echo "CHECKED" ;} ?> /> Masculin
<input type=radio name=reg_sexe value=F <?php if ($eleve_sexe == "F") { echo "CHECKED" ;} ?> /> Féminin
*/
?>
<label for='reg_sexeM' style='cursor: pointer;'><input type=radio name=reg_sexe id='reg_sexeM' value=M <?php if ($eleve_sexe == "M") { echo "CHECKED" ;} ?> /> Masculin</label>
<label for='reg_sexeF' style='cursor: pointer;'><input type=radio name=reg_sexe id='reg_sexeF' value=F <?php if ($eleve_sexe == "F") { echo "CHECKED" ;} ?> /> Féminin</label>
</div></td><td><div class='norme'>
Date de naissance (respecter format 00/00/0000) : <br />
Jour <input type=text name=birth_day size=2 value=<?php if (isset($eleve_naissance_jour)) echo $eleve_naissance_jour;?> />
Mois<input type=text name=birth_month size=2 value=<?php if (isset($eleve_naissance_mois)) echo $eleve_naissance_mois;?> />
Année<input type=text name=birth_year size=4 value=<?php if (isset($eleve_naissance_annee)) echo $eleve_naissance_annee;?> />
</div></td></tr>
</table></center>

<p><b>Remarques</b> :
<br />- la modification du régime de l'élève (demi-pensionnaire, interne, ...) s'effectue dans le module de gestion des classes !
<br />- Les champs * sont obligatoires.</p>
<?php
/*
echo "\$eleve_login=$eleve_login<br />\n";
echo "\$ele_id=$ele_id<br />\n";
echo "\$eleve_no_resp1=$eleve_no_resp1<br />\n";
*/

// PROBLEME: On ne récupère que les responsables déjà associés à un élève !

//$sql="SELECT rp.nom,rp.prenom,rp.pers_id,ra.* FROM responsables2 r, resp_adr ra, resp_pers rp WHERE r.resp_legal='1' AND r.pers_id=rp.pers_id AND rp.adr_id=ra.adr_id ORDER BY rp.nom, rp.prenom";
//$sql="SELECT DISTINCT rp.pers_id,rp.nom,rp.prenom,ra.* FROM responsables2 r, resp_adr ra, resp_pers rp WHERE r.pers_id=rp.pers_id AND rp.adr_id=ra.adr_id ORDER BY rp.nom, rp.prenom";
$sql="SELECT DISTINCT rp.pers_id,rp.nom,rp.prenom,ra.* FROM resp_adr ra, resp_pers rp WHERE rp.adr_id=ra.adr_id ORDER BY rp.nom, rp.prenom";

$call_resp=mysql_query($sql);
$nombreligne = mysql_num_rows($call_resp);
// si la table des responsables est non vide :
if ($nombreligne != 0) {
	$chaine_adr1 = '';
	$chaine_adr2 = '';
	$chaine_resp2 = '';

	echo "<br /><hr /><h3>Envoi des bulletins par voie postale</h3>\n";
	echo "<i>Si vous n'envoyez pas les bulletins scolaires par voie postale, vous pouvez ignorer cette rubrique.</i>";
	echo "<br /><br /><table><tr><td><b>Responsable légal principal : </b></td>\n";

	echo "<td><select size=1 name='reg_resp1'>\n";
	echo "<option value='(vide)' ";
	if(!(isset($eleve_no_resp1))){
		echo " selected";
	}
	elseif($eleve_no_resp1==0){
		echo " selected";
	}
	echo ">(vide)</option>\n";
	$i = 0;
	//while ($i < $nombreligne){
	while($lig_resp1=mysql_fetch_object($call_resp)){
		echo "<option value='".$lig_resp1->pers_id."'";
		//if ($lig_resp1->pers_id==$eleve_no_resp1) {
		// Cela donnait des trucs bizarres avec les valeurs non numériques p0000002 était assimilé à zéro.
		if ("$lig_resp1->pers_id"=="$eleve_no_resp1") {
			echo " selected";
		}
		echo ">";

		echo "$lig_resp1->nom $lig_resp1->prenom | ";
		/*
		if($lig_resp1->adr1!=''){echo "$lig_resp1->adr1 ";}
		if($lig_resp1->adr2!=''){echo "$lig_resp1->adr2 ";}
		if($lig_resp1->adr3!=''){echo "$lig_resp1->adr3 ";}
		if($lig_resp1->adr4!=''){echo "$lig_resp1->adr4 ";}
		echo "- ";
		if($lig_resp1->cp!=''){echo "$lig_resp1->cp, ";}
		if($lig_resp1->commune!=''){echo "$lig_resp1->commune ";}
		if($lig_resp1->pays!=''){echo "$lig_resp1->pays";}
		*/

		$chaine_adr1_tmp="";
		if($lig_resp1->adr1!=''){$chaine_adr1_tmp.="$lig_resp1->adr1 ";}
		if($lig_resp1->adr2!=''){$chaine_adr1_tmp.="$lig_resp1->adr2 ";}
		if($lig_resp1->adr3!=''){$chaine_adr1_tmp.="$lig_resp1->adr3 ";}
		if($lig_resp1->adr4!=''){$chaine_adr1_tmp.="$lig_resp1->adr4 ";}
		$chaine_adr1_tmp.="- ";
		if($lig_resp1->cp!=''){$chaine_adr1_tmp.="$lig_resp1->cp, ";}
		if($lig_resp1->commune!=''){$chaine_adr1_tmp.="$lig_resp1->commune ";}
		if($lig_resp1->pays!=''){$chaine_adr1_tmp.="$lig_resp1->pays";}

		echo $chaine_adr1_tmp;

		if ($lig_resp1->pers_id==$eleve_no_resp1) {
			$chaine_adr1=$chaine_adr1_tmp;
		}

		echo "</option>\n";
	}


/*
$call_resp = mysql_query("SELECT * FROM responsables ORDER BY nom1, prenom1");
$nombreligne = mysql_num_rows($call_resp);
// si la table des responsables est non vide :
if ($nombreligne != 0) {
    $chaine_adr1 = '';
    $chaine_adr2 = '';
    $chaine_resp2 = '';
    echo "<br /><hr /><H3>Envoi des bulletins par voie postale</H3>";
    echo "<i>Si vous n'envoyez pas les bulletins scolaires par voie postale, vous pouvez ignorer cette rubrique.</i>";
    echo "<br /><br /><table><tr><td><b>Responsable légal principal : </b></td>";

    echo "<td><select size = 1 name = 'reg_resp1'>";
    echo "<option value='(vide)' "; if (!(isset($eleve_no_resp))) {echo " SELECTED";} echo ">(vide)</option>";
    $i = 0;
    while ($i < $nombreligne){
        $ereno = mysql_result($call_resp , $i, "ereno");
        $nom1 = mysql_result($call_resp , $i, "nom1");
        $prenom1 = mysql_result($call_resp , $i, "prenom1");
        $adr1 = mysql_result($call_resp , $i, "adr1");
        $adr1_comp = mysql_result($call_resp , $i, "adr1_comp");
        $commune1 = mysql_result($call_resp , $i, "commune1");
        $cp1 = mysql_result($call_resp , $i, "cp1");
        $nom2 = mysql_result($call_resp , $i, "nom2");
        $prenom2 = mysql_result($call_resp , $i, "prenom2");
        $adr2 = mysql_result($call_resp , $i, "adr2");
        $commune2 = mysql_result($call_resp , $i, "commune2");
        $cp2 = mysql_result($call_resp , $i, "cp2");
        echo "<option value=".$ereno." ";
        if ($ereno == $eleve_no_resp) {
            echo " SELECTED";
            $chaine_adr1 = $adr1." - ".$cp1.", ".$commune1;
            if ($adr2 != '') {
                $chaine_adr2 = $adr2." - ".$cp2.", ".$commune2;
                $chaine_resp2 = $nom2." ".$prenom2;
            }
            if (substr($adr1, 0, strlen($adr1)-1) == substr($adr2, 0, strlen($adr1)-1) and ($cp1 == $cp2) and ($commune1 == $commune2)) {
                $message = "<b>Les adresses des deux responsables légaux sont identiques. Par conséquent, le bulletin ne sera envoyé qu'à la première adresse.</b>";
            } else {
                if ($chaine_adr2 != '') {
                    $message =  "<b>Les adresses des deux responsables légaux ne sont pas identiques. Par conséquent, le bulletin sera envoyé aux deux responsables légaux.</b>";
                } else {
                    $message =  "<b>Le bulletin sera envoyé au responsable légal ci-dessus.</b>";
                }
            }


        }
        echo ">".$nom1." ".$prenom1." | ".$adr1." ".$adr1_comp." - ".$cp1.", ".$commune1."</option>";

        $i++;
    }
*/

    echo "</select></td></tr>\n";


	if($eleve_no_resp2!=0){
		$sql="SELECT rp.nom,rp.prenom,rp.pers_id,ra.* FROM responsables2 r, resp_adr ra, resp_pers rp WHERE r.resp_legal='2' AND r.pers_id=rp.pers_id AND rp.adr_id=ra.adr_id AND r.ele_id='$ele_id' AND r.pers_id='$eleve_no_resp2'";
		$res_resp2=mysql_query($sql);
		if(mysql_num_rows($res_resp2)>0){
			$lig_resp2=mysql_fetch_object($res_resp2);
			echo "<tr><td><b>Deuxième responsable légal : </b></td>\n";
			echo "<td>".$lig_resp2->nom." ".$lig_resp2->prenom." | ";

			/*
			if($lig_resp2->adr1!=''){echo "$lig_resp2->adr1 ";}
			if($lig_resp2->adr2!=''){echo "$lig_resp2->adr2 ";}
			if($lig_resp2->adr3!=''){echo "$lig_resp2->adr3 ";}
			if($lig_resp2->adr4!=''){echo "$lig_resp2->adr4 ";}
			echo "- ";
			if($lig_resp2->cp!=''){echo "$lig_resp2->cp, ";}
			if($lig_resp2->commune!=''){echo "$lig_resp2->commune ";}
			if($lig_resp2->pays!=''){echo "$lig_resp2->pays";}
			*/

			if($lig_resp2->adr1!=''){$chaine_adr2.="$lig_resp2->adr1 ";}
			if($lig_resp2->adr2!=''){$chaine_adr2.="$lig_resp2->adr2 ";}
			if($lig_resp2->adr3!=''){$chaine_adr2.="$lig_resp2->adr3 ";}
			if($lig_resp2->adr4!=''){$chaine_adr2.="$lig_resp2->adr4 ";}
			$chaine_adr2.="- ";
			if($lig_resp2->cp!=''){$chaine_adr2.="$lig_resp2->cp, ";}
			if($lig_resp2->commune!=''){$chaine_adr2.="$lig_resp2->commune ";}
			if($lig_resp2->pays!=''){$chaine_adr2.="$lig_resp2->pays";}

			echo $chaine_adr2;

			echo "</td></tr>\n" ;


			if(substr($lig_resp1->adr1,0,strlen($lig_resp1->adr1)-1)==substr($lig_resp2->adr1, 0, strlen($lig_resp2->adr1)-1) and ($lig_resp1->cp==$lig_resp2->cp) and ($lig_resp1->commune==$lig_resp2->commune) and ($lig_resp1->pays==$lig_resp2->pays)) {
				$message = "<b>Les adresses des deux responsables légaux sont identiques. Par conséquent, le bulletin ne sera envoyé qu'à la première adresse.</b>";
			} else {
				if($chaine_adr2!='') {
					$message =  "<b>Les adresses des deux responsables légaux ne sont pas identiques. Par conséquent, le bulletin sera envoyé aux deux responsables légaux.</b>";
				} else {
					$message =  "<b>Le bulletin sera envoyé au responsable légal ci-dessus.</b>";
				}
			}

		}
		else{
			$message =  "<b>Le bulletin sera envoyé au responsable légal ci-dessus.</b>";
		}
	}
	elseif($eleve_no_resp1!=0){
		$message =  "<b>Le bulletin sera envoyé au responsable légal ci-dessus.</b>";
	}
	/*
	else{
		$message="";
	}
	*/
/*
    if ($chaine_adr2 != '') {
        echo "<tr><td><b>Deuxième responsable légal : </b></td>";
        echo "<td>".$chaine_resp2." | ".$chaine_adr2."</td></tr>" ;
    }
*/

    echo "</table>\n";
    echo "<br />Si le responsable légal ne figure pas dans la liste, vous pouvez l'ajouter à la base
    (après avoir, le cas échéant, sauvegardé cette fiche)
    <br />en vous rendant dans [Gestion des bases-><a href='../responsables/index.php'>Gestion des responsables élèves</a>]";

    if ($chaine_adr1 != '') {
		if(!isset($message)){$message="";}
        echo "<br />\n";
        echo $message;
        echo "<br />\n";
    }

}




?>
<br /><hr /><H3>Etablissement d'origine</h3>
<p>Etablissement d'origine :
<select size = 1 name = 'reg_etab'>
<?php
$calldata = mysql_query("SELECT * FROM etablissements ORDER BY id");
$nombreligne = mysql_num_rows($calldata);
echo "<option value='(vide)' "; if (!($id_etab)) {echo " SELECTED";} echo ">(vide)</option>\n";
$i = 0;
while ($i < $nombreligne){
    $list_etab_id = mysql_result($calldata, $i, "id");
    $list_etab_nom = mysql_result($calldata, $i, "nom");
    $list_etab_cp = mysql_result($calldata, $i, "cp");
    if ($list_etab_cp == 0) {$list_etab_cp = '';}
    $list_etab_ville = mysql_result($calldata, $i, "ville");
    $list_etab_niveau = mysql_result($calldata, $i, "niveau");
    foreach ($type_etablissement as $type_etab => $nom_etablissement) {
        if ($list_etab_niveau == $type_etab) {$list_etab_niveau = $nom_etablissement;}
    }
    echo "<option value=$list_etab_id "; if ($list_etab_id == $id_etab) {echo " SELECTED";} echo ">$list_etab_id | $list_etab_nom - $list_etab_niveau ($list_etab_cp";
    if ($list_etab_cp != '') {echo ", ";}
    echo "$list_etab_ville)</option>\n";
$i++;
}

echo "</select>\n";
echo "<input type=hidden name=is_posted value=\"1\" />\n";
if (isset($order_type)) echo "<input type=hidden name=order_type value=\"$order_type\" />\n";
if (isset($quelles_classes)) echo "<input type=hidden name=quelles_classes value=\"$quelles_classes\" />\n";
if (isset($eleve_login)) echo "<input type=hidden name=eleve_login value=\"$eleve_login\" />\n";
if (isset($mode)) echo "<input type=hidden name=mode value=\"$mode\" />\n";
echo "<center><input type=submit value=Enregistrer /></center>\n";
echo "</form>\n";
echo "<p><b>Attention</b>: L'enregistrement de l'établissement d'origine est conditionnée par la saisie d'un identifiant SCONET/GEP (<i>Elenoet</i>)</p>\n";

echo "<p><br /></p>\n";
require("../lib/footer.inc.php");
?>