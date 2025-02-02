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

$variables_non_protegees = 'yes';

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

// SQL : INSERT INTO droits VALUES ( '/mod_discipline/saisie_sanction.php', 'V', 'F', 'V', 'V', 'F', 'F', 'F', 'F', 'Discipline: Saisie sanction', '');
// maj : $tab_req[] = "INSERT INTO droits VALUES ( '/mod_discipline/saisie_sanction.php', 'V', 'F', 'V', 'V', 'F', 'F', 'F', 'F', 'Discipline: Saisie sanction', '');;";
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

$msg="";

$id_incident=isset($_POST['id_incident']) ? $_POST['id_incident'] : (isset($_GET['id_incident']) ? $_GET['id_incident'] : NULL);
$ele_login=isset($_POST['ele_login']) ? $_POST['ele_login'] : (isset($_GET['ele_login']) ? $_GET['ele_login'] : NULL);
$mode=isset($_POST['mode']) ? $_POST['mode'] : (isset($_GET['mode']) ? $_GET['mode'] : NULL);
$id_sanction=isset($_POST['id_sanction']) ? $_POST['id_sanction'] : (isset($_GET['id_sanction']) ? $_GET['id_sanction'] : NULL);
$id_report=isset($_POST['id_report']) ? $_POST['id_report'] : (isset($_GET['id_report']) ? $_GET['id_report'] : NULL);

$odt = isset($_POST["odt"]) ? $_POST["odt"] : (isset($_GET["odt"]) ? $_GET["odt"] : Null);

if(isset($_POST['enregistrer_sanction'])) {
	check_token();

	$autre_protagoniste_meme_sanction=isset($_POST['autre_protagoniste_meme_sanction']) ? $_POST['autre_protagoniste_meme_sanction'] : array();

	if($_POST['traitement']=='retenue') {

		$date_retenue=isset($_POST['date_retenue']) ? $_POST['date_retenue'] : NULL;
		$heure_debut=isset($_POST['heure_debut']) ? $_POST['heure_debut'] : NULL;
		$heure_debut_main=isset($_POST['heure_debut_main']) ? $_POST['heure_debut_main'] : '00:00';
		$duree_retenue=isset($_POST['duree_retenue']) ? $_POST['duree_retenue'] : 1;
		$lieu_retenue=isset($_POST['lieu_retenue']) ? $_POST['lieu_retenue'] : NULL;
		
		$report_demande=isset($_POST['report_demande']) ? $_POST['report_demande'] : NULL;
		$choix_motif_report=isset($_POST['choix_motif_report']) ? $_POST['choix_motif_report'] : NULL;
		

		$duree_retenue=preg_replace("/[^0-9.]/","",preg_replace("/,/",".",$duree_retenue));
		if($duree_retenue=="") {
			$duree_retenue=1;
			$msg.="La dur�e de retenue saisie n'�tait pas correcte. Elle a �t� remplac�e par '1'.<r />";
		}

		if(!isset($date_retenue)) {
			$annee = strftime("%Y");
			$mois = strftime("%m");
			$jour = strftime("%d");
			//$display_date = $jour."/".$mois."/".$annee;
		}
		else {
			$jour =  substr($date_retenue,0,2);
			$mois =  substr($date_retenue,3,2);
			$annee = substr($date_retenue,6,4);
		}

		if(!checkdate($mois,$jour,$annee)) {
			$annee = strftime("%Y");
			$mois = strftime("%m");
			$jour = strftime("%d");

			$msg.="La date propos�e n'�tait pas valide. Elle a �t� remplac�e par la date du jour courant.";
		}
		$date_retenue="$annee-$mois-$jour";

		if (isset($NON_PROTECT["travail"])){
			$travail=traitement_magic_quotes(corriger_caracteres($NON_PROTECT["travail"]));
			// Contr�le des saisies pour supprimer les sauts de lignes surnum�raires.
			$travail=preg_replace('/(\\\r\\\n)+/',"\r\n",$travail);
			$travail=preg_replace('/(\\\r)+/',"\r",$travail);
			$travail=preg_replace('/(\\\n)+/',"\n",$travail);
		}
		else {
			$travail="";
		}

		if(isset($id_sanction)) {
		
		    // traitement du report de la retenue (seulement si elle existe d�j� !)
			if ($report_demande=="OK") { // c'est un report
				// on r�cup�re les informations pr�c�dente dans la table s_retenues pour les inscrire dans s_reports
				$sql="SELECT * FROM s_retenues WHERE id_sanction='$id_sanction';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)==0) {
					$msg.="La retenue n�$id_sanction n'existe pas dans 's_retenues'.<br />Elle ne peut pas �tre report�e.<br />";
				}
				else {
				    $lig=mysql_fetch_object($res);
					$id_retenue=$lig->id_retenue;
					$ancienne_date=$lig->date;
					$ancienne_duree=$lig->duree;
				}
				// enregistrement des donn�es du report dans la table s_report
				$choix_motif_report = str_replace("_", " ", $choix_motif_report);

				$sql="INSERT INTO s_reports SET id_sanction='$id_sanction', id_type_sanction='$id_retenue', nature_sanction='retenue', date='$ancienne_date', informations='Dur�e : ".$ancienne_duree."H', motif_report='$choix_motif_report';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
				if(!$res) {
					$msg.="Erreur lors de l'insertion des informations de report dans 's_reports'.<br />";
				}
			}
		
			// Modification???
			$sql="SELECT 1=1 FROM s_sanctions WHERE id_sanction='$id_sanction';";
			//echo "$sql<br />\n";
			$res=mysql_query($sql);
			if(mysql_num_rows($res)==0) {
				$msg.="La sanction n�$id_sanction n'existe pas dans 's_sanctions'.<br />Elle ne peut pas �tre mise � jour.<br />";
			}
			else {
				$sql="SELECT 1=1 FROM s_retenues WHERE id_sanction='$id_sanction';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)==0) {
					$msg.="La retenue n�$id_sanction n'existe pas dans 's_retenues'.<br />Elle ne peut pas �tre mise � jour.<br />";
				}
				else {
					//Eric
					//choix de l'heure de retenue � conserver (champs sasie manuellement ou par la liste d�roulante
					//par defaut la liste d�roulante
					if ($heure_debut_main !='00:00') {
					   $heure_debut=$heure_debut_main;
					}
					//$sql="UPDATE s_retenues SET date='$date_retenue', heure_debut='$heure_debut', duree='$duree_retenue', travail='$travail', lieu='$lieu_retenue', effectuee='N' WHERE id_sanction='$id_sanction';";
					$sql="UPDATE s_retenues SET date='$date_retenue', heure_debut='$heure_debut', duree='$duree_retenue', travail='$travail', lieu='$lieu_retenue' WHERE id_sanction='$id_sanction';";
					//echo "$sql<br />\n";
					$update=mysql_query($sql);
					if(!$update) {
						$msg.="Erreur lors de la mise � jour de l'exclusion n�$id_sanction.<br />";
					}
				}
			}
		}
		else {
			//$sql="INSERT INTO s_sanctions SET login='$ele_login', nature='retenue', id_incident='$id_incident';";
			$sql="INSERT INTO s_sanctions SET login='$ele_login', nature='retenue', id_incident='$id_incident';";
			//echo "$sql<br />\n";
			$res=mysql_query($sql);
			if(!$res) {
				$msg.="Erreur lors de l'insertion de la sanction dans 's_sanctions'.<br />";
			}
			else {
				$id_sanction=mysql_insert_id();
				//Eric
				//choix de l'heure de retenue � conserver (champs sasie manuellement ou par la liste d�roulante
				//par defaut la liste d�roulante
				if ($heure_debut_main !='00:00') {
					   $heure_debut=$heure_debut_main;
				}
				//$sql="INSERT INTO s_retenues SET id_sanction='$id_sanction', date='$date_retenue', heure_debut='$heure_debut', duree='$duree_retenue', travail='$travail', lieu='$lieu_retenue', effectuee='N';";
				$sql="INSERT INTO s_retenues SET id_sanction='$id_sanction', date='$date_retenue', heure_debut='$heure_debut', duree='$duree_retenue', travail='$travail', lieu='$lieu_retenue';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
			}

			if(count($autre_protagoniste_meme_sanction)>0) {
				for($loop=0;$loop<count($autre_protagoniste_meme_sanction);$loop++) {
					$sql="INSERT INTO s_sanctions SET login='$autre_protagoniste_meme_sanction[$loop]', nature='retenue', id_incident='$id_incident';";
					//echo "$sql<br />\n";
					$res=mysql_query($sql);
					if(!$res) {
						$msg.="Erreur lors de l'insertion de la sanction dans 's_sanctions' pour $autre_protagoniste_meme_sanction[$loop].<br />";
					}
					else {
						$tmp_id_sanction=mysql_insert_id();
						$tab_tmp_id_sanction[]=$tmp_id_sanction;
						//Eric
						//choix de l'heure de retenue � conserver (champs sasie manuellement ou par la liste d�roulante
						//par defaut la liste d�roulante
						if ($heure_debut_main !='00:00') {
							$heure_debut=$heure_debut_main;
						}
						//$sql="INSERT INTO s_retenues SET id_sanction='$id_sanction', date='$date_retenue', heure_debut='$heure_debut', duree='$duree_retenue', travail='$travail', lieu='$lieu_retenue', effectuee='N';";
						$sql="INSERT INTO s_retenues SET id_sanction='$tmp_id_sanction', date='$date_retenue', heure_debut='$heure_debut', duree='$duree_retenue', travail='$travail', lieu='$lieu_retenue';";
						//echo "$sql<br />\n";
						$res=mysql_query($sql);
					}
				}
			}
		}

	}
	elseif($_POST['traitement']=='exclusion') {

		$date_debut=isset($_POST['date_debut']) ? $_POST['date_debut'] : NULL;
		$heure_debut=isset($_POST['heure_debut']) ? $_POST['heure_debut'] : NULL;
		$date_fin=isset($_POST['date_fin']) ? $_POST['date_fin'] : NULL;
		$heure_fin=isset($_POST['heure_fin']) ? $_POST['heure_fin'] : NULL;
		$lieu_exclusion=isset($_POST['lieu_exclusion']) ? $_POST['lieu_exclusion'] : NULL;
		$nombre_jours=isset($_POST['nombre_jours']) ? $_POST['nombre_jours'] : NULL;
		$qualification_faits=isset($_POST['qualification_faits']) ? $_POST['qualification_faits'] : NULL;
		$numero_courrier=isset($_POST['numero_courrier']) ? $_POST['numero_courrier'] : NULL;
		$type_exclusion=isset($_POST['type_exclusion']) ? $_POST['type_exclusion'] : NULL;
		$signataire=isset($_POST['signataire']) ? $_POST['signataire'] : NULL;
		

		if(!isset($date_debut)) {
			$annee = strftime("%Y");
			$mois = strftime("%m");
			$jour = strftime("%d");
			//$display_date = $jour."/".$mois."/".$annee;
		}
		else {
			$jour =  substr($date_debut,0,2);
			$mois =  substr($date_debut,3,2);
			$annee = substr($date_debut,6,4);
		}

		if(!checkdate($mois,$jour,$annee)) {
			$annee = strftime("%Y");
			$mois = strftime("%m");
			$jour = strftime("%d");

			$msg.="La date propos�e n'�tait pas valide. Elle a �t� remplac�e par la date du jour courant.";
		}
		$date_debut="$annee-$mois-$jour";
		$tmp_timestamp_debut=mktime(0, 0, 0, $mois, $jour, $annee);

		if(!isset($date_fin)) {
			if(!isset($date_debut)) {
				$annee = strftime("%Y");
				$mois = strftime("%m");
				$jour = strftime("%d");
			}
			else {
				$jour =  substr($date_debut,0,2);
				$mois =  substr($date_debut,3,2);
				$annee = substr($date_debut,6,4);
			}
		}
		else {
			$jour =  substr($date_fin,0,2);
			$mois =  substr($date_fin,3,2);
			$annee = substr($date_fin,6,4);
		}

		if(!checkdate($mois,$jour,$annee)) {
			$annee = strftime("%Y");
			$mois = strftime("%m");
			$jour = strftime("%d");

			$msg.="La date propos�e n'�tait pas valide. Elle a �t� remplac�e par la date du jour courant.";
		}
		$date_fin="$annee-$mois-$jour";
		$tmp_timestamp_fin=mktime(0, 0, 0, $mois, $jour, $annee);

		if($tmp_timestamp_debut>$tmp_timestamp_fin) {
			//echo "\$date_debut=$date_debut<br />";
			//echo "\$date_fin=$date_fin<br />";
			//echo "\$tmp_timestamp_debut=$tmp_timestamp_debut<br />";
			//echo "\$tmp_timestamp_fin=$tmp_timestamp_fin<br />";

			//echo "MODIF:<br />";
			$tmp_date_debut=$date_fin;
			//echo "\$tmp_date_debut=$date_fin<br />";
			$date_fin=$date_debut;
			//echo "\$date_fin=$date_debut<br />";
			$date_debut=$tmp_date_debut;
			//echo "\$date_debut=$tmp_date_debut<br />";

			$msg.="La date de fin �tait ant�rieure � la date de d�but de l'exclusion.<br />Les dates ont �t� interverties.<br />";
		}

		if (isset($NON_PROTECT["travail"])){
			$travail=traitement_magic_quotes(corriger_caracteres($NON_PROTECT["travail"]));
			// Contr�le des saisies pour supprimer les sauts de lignes surnum�raires.
			$travail=preg_replace('/(\\\r\\\n)+/',"\r\n",$travail);
			$travail=preg_replace('/(\\\r)+/',"\r",$travail);
			$travail=preg_replace('/(\\\n)+/',"\n",$travail);
		}
		else {
			$travail="";
		}
		
		if (isset($NON_PROTECT["qualification_faits"])){
			$qualification_faits=traitement_magic_quotes(corriger_caracteres($NON_PROTECT["qualification_faits"]));
			// Contr�le des saisies pour supprimer les sauts de lignes surnum�raires.
			$qualification_faits=preg_replace('/(\\\r\\\n)+/',"\r\n",$qualification_faits);
			$qualification_faits=preg_replace('/(\\\r)+/',"\r",$qualification_faits);
			$qualification_faits=preg_replace('/(\\\n)+/',"\n",$qualification_faits);
		}
		else {
			$qualification_faits="";
		}
		
		if(isset($id_sanction)) {
			// Modification???
			$sql="SELECT 1=1 FROM s_sanctions WHERE id_sanction='$id_sanction';";
			//echo "$sql<br />\n";
			$res=mysql_query($sql);
			if(mysql_num_rows($res)==0) {
				$msg.="La sanction n�$id_sanction n'existe pas dans 's_sanctions'.<br />Elle ne peut pas �tre mise � jour.<br />";
			}
			else {
				$sql="SELECT 1=1 FROM s_exclusions WHERE id_sanction='$id_sanction';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)==0) {
					$msg.="La sanction n�$id_sanction n'existe pas dans 's_exclusions'.<br />Elle ne peut pas �tre mise � jour.<br />";
				}
				else {
					$sql="UPDATE s_exclusions SET date_debut='$date_debut', heure_debut='$heure_debut', date_fin='$date_fin', heure_fin='$heure_fin', travail='$travail', lieu='$lieu_exclusion', nombre_jours='$nombre_jours', qualification_faits='$qualification_faits', num_courrier='$numero_courrier', type_exclusion='$type_exclusion', id_signataire='$signataire' WHERE id_sanction='$id_sanction';";
					//echo "$sql<br />\n";
					$update=mysql_query($sql);
					if(!$update) {
						$msg.="Erreur lors de la mise � jour de l'exclusion n�$id_sanction.<br />";
					}
				}
			}
		}
		else {
			$sql="INSERT INTO s_sanctions SET login='$ele_login', nature='exclusion', id_incident='$id_incident';";
			//echo "$sql<br />\n";
			$res=mysql_query($sql);
			if(!$res) {
				$msg.="Erreur lors de l'insertion de la sanction dans 's_sanctions'.<br />";
			}
			else {
				$id_sanction=mysql_insert_id();

				$sql="INSERT INTO s_exclusions SET id_sanction='$id_sanction', date_debut='$date_debut', heure_debut='$heure_debut', date_fin='$date_fin', heure_fin='$heure_fin', travail='$travail', lieu='$lieu_exclusion', nombre_jours='$nombre_jours', qualification_faits='$qualification_faits', num_courrier='$numero_courrier', type_exclusion='$type_exclusion', id_signataire='$signataire';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
			}

			if(count($autre_protagoniste_meme_sanction)>0) {
				for($loop=0;$loop<count($autre_protagoniste_meme_sanction);$loop++) {
					$sql="INSERT INTO s_sanctions SET login='$autre_protagoniste_meme_sanction[$loop]', nature='exclusion', id_incident='$id_incident';";
					//echo "$sql<br />\n";
					$res=mysql_query($sql);
					if(!$res) {
						$msg.="Erreur lors de l'insertion de la sanction dans 's_sanctions' pour $autre_protagoniste_meme_sanction[$loop].<br />";
					}
					else {
						$tmp_id_sanction=mysql_insert_id();
						$tab_tmp_id_sanction[]=$tmp_id_sanction;

						$sql="INSERT INTO s_exclusions SET id_sanction='$tmp_id_sanction', date_debut='$date_debut', heure_debut='$heure_debut', date_fin='$date_fin', heure_fin='$heure_fin', travail='$travail', lieu='$lieu_exclusion', nombre_jours='$nombre_jours', qualification_faits='$qualification_faits', num_courrier='$numero_courrier', type_exclusion='$type_exclusion', id_signataire='$signataire';";
						//echo "$sql<br />\n";
						$res=mysql_query($sql);
					}
				}
			}
		}

	}
	elseif($_POST['traitement']=='travail') {

		$date_retour=isset($_POST['date_retour']) ? $_POST['date_retour'] : NULL;
		$heure_retour=isset($_POST['heure_retour']) ? $_POST['heure_retour'] : NULL;

		if(!isset($date_retour)) {
			$annee = strftime("%Y");
			$mois = strftime("%m");
			$jour = strftime("%d");
			//$display_date = $jour."/".$mois."/".$annee;
		}
		else {
			$jour =  substr($date_retour,0,2);
			$mois =  substr($date_retour,3,2);
			$annee = substr($date_retour,6,4);
		}

		if(!checkdate($mois,$jour,$annee)) {
			$annee = strftime("%Y");
			$mois = strftime("%m");
			$jour = strftime("%d");

			$msg.="La date propos�e n'�tait pas valide. Elle a �t� remplac�e par la date du jour courant.";
		}
		$date_retour="$annee-$mois-$jour";

		if (isset($NON_PROTECT["travail"])){
			$travail=traitement_magic_quotes(corriger_caracteres($NON_PROTECT["travail"]));
			// Contr�le des saisies pour supprimer les sauts de lignes surnum�raires.
			$travail=preg_replace('/(\\\r\\\n)+/',"\r\n",$travail);
			$travail=preg_replace('/(\\\r)+/',"\r",$travail);
			$travail=preg_replace('/(\\\n)+/',"\n",$travail);
		}
		else {
			$travail="";
		}

		if(isset($id_sanction)) {
			// Modification???
			$sql="SELECT 1=1 FROM s_sanctions WHERE id_sanction='$id_sanction';";
			//echo "$sql<br />\n";
			$res=mysql_query($sql);
			if(mysql_num_rows($res)==0) {
				$msg.="La sanction n�$id_sanction n'existe pas dans 's_sanctions'.<br />Elle ne peut pas �tre mise � jour.<br />";
			}
			else {
				$sql="SELECT 1=1 FROM s_travail WHERE id_sanction='$id_sanction';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)==0) {
					$msg.="Le travail n�$id_sanction n'existe pas dans 's_travail'.<br />Il ne peut pas �tre mis � jour.<br />";
				}
				else {
					//$sql="UPDATE s_travail SET date_retour='$date_retour', heure_retour='$heure_retour', travail='$travail', effectuee='N' WHERE id_sanction='$id_sanction';";
					$sql="UPDATE s_travail SET date_retour='$date_retour', heure_retour='$heure_retour', travail='$travail' WHERE id_sanction='$id_sanction';";
					//echo "$sql<br />\n";
					$update=mysql_query($sql);
					if(!$update) {
						$msg.="Erreur lors de la mise � jour de l'exclusion n�$id_sanction.<br />";
					}
				}
			}
		}
		else {
			$sql="INSERT INTO s_sanctions SET login='$ele_login', nature='travail', id_incident='$id_incident';";
			//echo "$sql<br />\n";
			$res=mysql_query($sql);
			if(!$res) {
				$msg.="Erreur lors de l'insertion de la sanction dans 's_sanctions'.<br />";
			}
			else {
				$id_sanction=mysql_insert_id();

				//$sql="INSERT INTO s_travail SET id_sanction='$id_sanction', date_retour='$date_retour', heure_retour='$heure_retour', travail='$travail', effectuee='N';";
				$sql="INSERT INTO s_travail SET id_sanction='$id_sanction', date_retour='$date_retour', heure_retour='$heure_retour', travail='$travail';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
			}

			if(count($autre_protagoniste_meme_sanction)>0) {
				for($loop=0;$loop<count($autre_protagoniste_meme_sanction);$loop++) {
					$sql="INSERT INTO s_sanctions SET login='$autre_protagoniste_meme_sanction[$loop]', nature='travail', id_incident='$id_incident';";
					//echo "$sql<br />\n";
					$res=mysql_query($sql);
					if(!$res) {
						$msg.="Erreur lors de l'insertion de la sanction dans 's_sanctions' pour $autre_protagoniste_meme_sanction[$loop]<br />";
					}
					else {
						$tmp_id_sanction=mysql_insert_id();
						$tab_tmp_id_sanction[]=$tmp_id_sanction;

						//$sql="INSERT INTO s_travail SET id_sanction='$id_sanction', date_retour='$date_retour', heure_retour='$heure_retour', travail='$travail', effectuee='N';";
						$sql="INSERT INTO s_travail SET id_sanction='$tmp_id_sanction', date_retour='$date_retour', heure_retour='$heure_retour', travail='$travail';";
						//echo "$sql<br />\n";
						$res=mysql_query($sql);
					}
				}
			}
		}
	}
	else {
		$id_nature=$_POST['traitement'];
		$sql="SELECT * FROM s_types_sanctions WHERE id_nature='".$id_nature."';";
		//echo "$sql<br />\n";
		$res=mysql_query($sql);
		if(mysql_num_rows($res)>0) {
			$lig=mysql_fetch_object($res);
			$type_sanction=$lig->nature;

			if (isset($NON_PROTECT["description"])){
				$description=traitement_magic_quotes(corriger_caracteres($NON_PROTECT["description"]));
				// Contr�le des saisies pour supprimer les sauts de lignes surnum�raires.
				$description=preg_replace('/(\\\r\\\n)+/',"\r\n",$description);
				$description=preg_replace('/(\\\r)+/',"\r",$description);
				$description=preg_replace('/(\\\n)+/',"\n",$description);
			}
			else {
				$description="";
			}

			if(isset($id_sanction)) {
				// Modification???
				$sql="SELECT 1=1 FROM s_sanctions WHERE id_sanction='$id_sanction';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
				if(mysql_num_rows($res)==0) {
					$msg.="La sanction n�$id_sanction n'existe pas dans 's_sanctions'.<br />Elle ne peut pas �tre mise � jour.<br />";
				}
				else {
					$sql="SELECT 1=1 FROM s_autres_sanctions WHERE id_sanction='$id_sanction';";
					//echo "$sql<br />\n";
					$res=mysql_query($sql);
					if(mysql_num_rows($res)==0) {
						$msg.="La sanction n�$id_sanction n'existe pas dans 's_autres_sanctions'.<br />Elle ne peut pas �tre mis � jour.<br />";
					}
					else {
						$sql="UPDATE s_autres_sanctions SET description='$description', id_nature='$id_nature' WHERE id_sanction='$id_sanction';";
						//echo "$sql<br />\n";
						$update=mysql_query($sql);
						if(!$update) {
							$msg.="Erreur lors de la mise � jour de la sanction '$type_sanction' n�$id_sanction.<br />";
						}
					}
				}
			}
			else {
				$sql="INSERT INTO s_sanctions SET login='$ele_login', nature='autre', id_incident='$id_incident';";
				//echo "$sql<br />\n";
				$res=mysql_query($sql);
				if(!$res) {
					$msg.="Erreur lors de l'insertion de la sanction dans 's_sanctions'.<br />";
				}
				else {
					$id_sanction=mysql_insert_id();

					$sql="INSERT INTO s_autres_sanctions SET id_sanction='$id_sanction', id_nature='$id_nature', description='$description';";
					//echo "$sql<br />\n";
					$res=mysql_query($sql);
					if(!$res) {
						$msg.="Erreur lors de l'enregistrement de la sanction '$type_sanction' n�$id_sanction.<br />";
					}
				}

				if(count($autre_protagoniste_meme_sanction)>0) {
					for($loop=0;$loop<count($autre_protagoniste_meme_sanction);$loop++) {
						$sql="INSERT INTO s_sanctions SET login='$autre_protagoniste_meme_sanction[$loop]', nature='autre', id_incident='$id_incident';";
						//echo "$sql<br />\n";
						$res=mysql_query($sql);
						if(!$res) {
							$msg.="Erreur lors de l'insertion de la sanction dans 's_sanctions' pour $autre_protagoniste_meme_sanction[$loop]<br />";
						}
						else {
							$tmp_id_sanction=mysql_insert_id();
							$tab_tmp_id_sanction[]=$tmp_id_sanction;

							$sql="INSERT INTO s_autres_sanctions SET id_sanction='$tmp_id_sanction', id_nature='$id_nature', description='$description';";
							//echo "$sql<br />\n";
							$res=mysql_query($sql);
							if(!$res) {
								$msg.="Erreur lors de l'enregistrement de la sanction '$type_sanction' n�$tmp_id_sanction.<br />";
							}
						}
					}
				}
			}
		}
	}




	if(isset($id_sanction)) {
		$temoin_modif_fichier=0;

		unset($suppr_doc_joint);
		$suppr_doc_joint=isset($_POST['suppr_doc_joint']) ? $_POST['suppr_doc_joint'] : array();
		for($loop=0;$loop<count($suppr_doc_joint);$loop++) {
			if((preg_match("/\.\./",$suppr_doc_joint[$loop]))||(preg_match("#/#",$suppr_doc_joint[$loop]))) {
				$msg.="Nom de fichier ".$suppr_doc_joint[$loop]." invalide<br />";
			}
			else {
				$fichier_courant="../$dossier_documents_discipline/incident_".$id_incident."/sanction_".$id_sanction."/".$suppr_doc_joint[$loop];
				if(!unlink($fichier_courant)) {
					$msg.="Erreur lors de la suppression de $fichier_courant<br />";
				}
			}
		}
		$temoin_modif_fichier+=count($suppr_doc_joint);

		$ajouter_doc_joint=isset($_POST['ajouter_doc_joint']) ? $_POST['ajouter_doc_joint'] : array();
		for($loop=0;$loop<count($ajouter_doc_joint);$loop++) {
			if((preg_match("/\.\./",$ajouter_doc_joint[$loop]))||(preg_match("#/#",$ajouter_doc_joint[$loop]))) {
				$msg.="Nom de fichier ".$ajouter_doc_joint[$loop]." invalide<br />";
			}
			else {
				$chemin_src="../$dossier_documents_discipline/incident_".$id_incident."/mesures/".$ele_login;
				$chemin_dest="../$dossier_documents_discipline/incident_".$id_incident."/sanction_".$id_sanction;

				$fichier_src=$chemin_src."/".$ajouter_doc_joint[$loop];
				$fichier_dest=$chemin_dest."/".$ajouter_doc_joint[$loop];
				if(file_exists($fichier_src)) {
					@mkdir($chemin_dest,0770,true);
					copy($fichier_src, $fichier_dest);

					/*
					if(!unlink($fichier_src)) {
						$msg.="Erreur lors de la suppression de $fichier_src<br />";
					}
					*/
				}

				if((isset($tab_tmp_id_sanction))&&(count($tab_tmp_id_sanction)>0)) {
					for($loop2=0;$loop2<count($tab_tmp_id_sanction);$loop2++) {
						$chemin_dest="../$dossier_documents_discipline/incident_".$id_incident."/sanction_".$tab_tmp_id_sanction[$loop2];
						$fichier_dest=$chemin_dest."/".$ajouter_doc_joint[$loop];
						if(file_exists($fichier_src)) {
							@mkdir($chemin_dest,0770,true);
							copy($fichier_src, $fichier_dest);
						}
					}
				}
			}
		}
		$temoin_modif_fichier+=count($ajouter_doc_joint);

		unset($document_joint);
		$document_joint=isset($_FILES["document_joint"]) ? $_FILES["document_joint"] : NULL;
		if((isset($document_joint['tmp_name']))&&($document_joint['tmp_name']!="")) {
			//$msg.="\$document_joint['tmp_name']=".$document_joint['tmp_name']."<br />";
			if(!is_uploaded_file($document_joint['tmp_name'])) {
				$msg.="L'upload du fichier a �chou�.<br />\n";
			}
			else{
				if(!file_exists($document_joint['tmp_name'])){
					$msg.="Le fichier aurait �t� upload�... mais ne serait pas pr�sent/conserv�.<br />\n";
				}
				else {
					//echo "<p>Le fichier a �t� upload�.</p>\n";

					$source_file=$document_joint['tmp_name'];
					$dossier_courant="../$dossier_documents_discipline/incident_".$id_incident."/sanction_".$id_sanction;
					if(!file_exists($dossier_courant)) {
						mkdir($dossier_courant, 0770, true);
					}
					$dest_file=$dossier_courant."/".remplace_accents($document_joint['name'], "all");
					$res_copy=copy("$source_file" , "$dest_file");
					if(!$res_copy) {$msg.="Echec de la mise en place du fichier ".$document_joint['name']."<br />";}
				}
			}
			$temoin_modif_fichier++;
		}

		if($temoin_modif_fichier>0) {
			$mode="modif";
			$valeur=$_POST['traitement'];
		}
	}



}

if(($mode=="suppr_sanction")&&(isset($id_sanction))) {
	check_token();

	$msg.=suppr_doc_joints_sanction($id_sanction);

	$sql="DELETE FROM s_travail WHERE id_sanction='$id_sanction';";
	$res=mysql_query($sql);
	$sql="DELETE FROM s_exclusions WHERE id_sanction='$id_sanction';";
	$res=mysql_query($sql);
	$sql="DELETE FROM s_retenues WHERE id_sanction='$id_sanction';";
	$res=mysql_query($sql);
	$sql="DELETE FROM s_autres_sanctions WHERE id_sanction='$id_sanction';";
	$res=mysql_query($sql);
	$sql="DELETE FROM s_sanctions WHERE id_sanction='$id_sanction';";
	$res=mysql_query($sql);
	$sql="DELETE FROM s_reports WHERE id_sanction='$id_sanction';";
	$res=mysql_query($sql);
}

if(($mode=="suppr_report")&&(isset($id_report))) {
	check_token();

	$sql="DELETE FROM s_reports WHERE id_report='$id_report';";
	$res=mysql_query($sql);
}

if(isset($odt)&&($odt=="exclusion")) { //impression de l'exclusion en Ooo
//recup des informations � exporter dans l'ODT
//Nom et prenom eleve;
if ($ele_login != null && $ele_login != '') {
    $eleve_current=  EleveQuery::create()->filterByLogin($ele_login)->findOne();
    $nom_ele = $eleve_current->getNom();
	$prenom_ele= $eleve_current->getPrenom();
	$id_classe_ele= $eleve_current->getClasse()->getId();
}
//classe de l'�l�ve
if ($id_classe_ele != null && $id_classe_ele != '') {
    $classe = ClasseQuery::create()->findOneById($id_classe_ele);
    if ($classe != null) {
        $classe_ele = $classe->getNom();
    }
}

require_once("./lib_tbs_courrier.php"); //fonction pour le traitement de l'adresse

$tab_adresse=adresse_responsables($ele_login); 

// Pour le moment on ne traite que pour le R1
$ad_nom_resp=$tab_adresse[0]['civilite'];
$adr1_resp=$tab_adresse[0]['adresse1'];
$adr2_resp=$tab_adresse[0]['adresse2'];
$adr3_resp=$tab_adresse[0]['adresse3'];
$cp_ville_resp=$tab_adresse[0]['cp_ville'];
$civilite_courrier=$tab_adresse[0]['civilite_courrier'];

//Contenu du courrier
if ($id_sanction != null && $id_sanction != '') {
$sql="SELECT * FROM s_exclusions WHERE id_sanction='$id_sanction';";
$res_sanction=mysql_query($sql);
	if(mysql_num_rows($res_sanction)==0) {
		$num_courrier="";
		$type_exclusion="";
		$qualidication_faits="";
		$duree_exclusion="";
		$date_debut="";
		$date_fin="";
		$signataire="";
	}
	else {
		$lig_sanction=mysql_fetch_object($res_sanction);
		$num_courrier=$lig_sanction->num_courrier;
		$type_exclusion=$lig_sanction->type_exclusion;
		$qualification_faits=$lig_sanction->qualification_faits;
		$duree_exclusion=$lig_sanction->nombre_jours;
		$date_debut=$lig_sanction->date_debut;
		$date_fin=$lig_sanction->date_fin;
		$signataire=$lig_sanction->id_signataire;
	}

$sql="SELECT * FROM s_delegation WHERE id_delegation='$signataire';";
$res_delegation=mysql_query($sql);
	if(mysql_num_rows($res_delegation)==0) {
		$fct_delegation="";
		$fct_autorite="";
		$nom_autorite="";
	}
	else {
		$lig_delegation=mysql_fetch_object($res_delegation);
		$fct_delegation=$lig_delegation->fct_delegation;
		$fct_autorite=$lig_delegation->fct_autorite;
		$nom_autorite=$lig_delegation->nom_autorite;
	}
}
//conversion des dates
//Voici les deux tableaux des jours et des mois traduits en fran�ais
$nom_jour_fr = array("dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi");
$mois_fr = Array("", "janvier", "f�vrier", "mars", "avril", "mai", "juin", "juillet", "ao�t", 
        "septembre", "octobre", "novembre", "d�cembre");
// on extrait la date du jour pour la date de debut
list($annee, $mois, $jour) = explode('-', $date_debut);
$mois=intval($mois);
$timestamp = mktime (0, 0, 0, $mois, $jour, $annee);
// affichage du jour de la semaine
$date_debut = $nom_jour_fr[date("w",$timestamp)].' '.$jour.' '.$mois_fr[$mois].' '.$annee; 
// on extrait la date du jour pour la date de fin
list($annee, $mois, $jour) = explode('-', $date_fin); 
$mois=intval($mois);
$timestamp = mktime (0, 0, 0, $mois, $jour, $annee);
// affichage du jour de la semaine
$date_fin = $nom_jour_fr[date("w",$timestamp)].' '.$jour.' '.$mois_fr[$mois].' '.$annee; 

if ($date_debut==$date_fin) {
$chaine_date = "du $date_debut";
$journee = "la journ�e";
} else {
$chaine_date = "du $date_debut au $date_fin inclus";
$journee = "les journ�es";
}

$export = array();
$export[] = Array('nom' => $nom_ele, 'prenom' => $prenom_ele, 'classe' => $classe_ele,
				  'ad_nom_resp' => $ad_nom_resp, 
				  'adr1_resp' => $adr1_resp, 'adr2_resp' => $adr2_resp, 'adr3_resp' => $adr3_resp,
				  'cp_ville_resp' => $cp_ville_resp,
				  'civilite_courrier' => $civilite_courrier,
				  'num_courrier' => $num_courrier,
				  'type_exclusion' => $type_exclusion,
				  'qualif_faits' => $qualification_faits,
				  'duree_exclusion' => $duree_exclusion,
				  'date_debut' => $date_debut,
				  'date_fin' => $date_fin,
				  'chaine_date' => $chaine_date,
				  'journee' => $journee,
				  'fonction_delegation' => $fct_delegation,
				  'fonction_autorite' => $fct_autorite,
				  'nom_autorite' => $nom_autorite
				  );
/*
echo "<pre>";
echo print_r($mois);
echo "</pre>";
*/
// g�n�ration Ooo
include_once '../mod_abs2/lib/function.php'; //pour la fonction repertoire_modeles
include_once '../orm/helpers/AbsencesNotificationHelper.php'; // pour la fonction tbs_str et MergeInfosEtab
$extraction_bilans = repertoire_modeles('discipline_exclusion.odt');
//Coordonn�es etab
$TBS = AbsencesNotificationHelper::MergeInfosEtab($extraction_bilans);

$TBS->MergeBlock('export', $export);

$nom_fichier = 'exclusion_'. $nom_ele.'_'.$prenom_ele.'_'.$id_sanction. '.odt';
$TBS->Show(OPENTBS_DOWNLOAD + TBS_EXIT, $nom_fichier);
} //fin Ooo

$utilisation_prototype="ok";
$themessage  = 'Des informations ont �t� modifi�es. Voulez-vous vraiment quitter sans enregistrer ?';
//**************** EN-TETE *****************
$titre_page = "Discipline: Traitement/sanction";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

//debug_var();

$page="saisie_sanction.php";

echo "<script type='text/javascript'>
	// La fonction est ramen�e de saisie_sanction.inc.php dans saisie_sanction.php
	// parce que les javascript d�finis dans la page saisie_sanction.inc.php appel�e via ajax ne sont pas pris en compte.
	function occupation_lieu_heure(id_sanction) {
		lieu=document.getElementById('lieu_retenue').value;
		date_retenue=document.getElementById('date_retenue').value;
		heure_debut=document.getElementById('heure_debut').options[document.getElementById('heure_debut').selectedIndex].value;
		duree_retenue=document.getElementById('duree_retenue').value;

		centrerpopup('occupation_lieu_heure.php?id_sanction='+id_sanction+'&lieu='+lieu+'&date='+date_retenue+'&heure='+heure_debut+'&duree='+duree_retenue,600,480,'scrollbars=yes,statusbar=no,resizable=yes');
	}

	function maj_div_liste_retenues_jour() {
		if($('date_retenue')) {
			date=$('date_retenue').value;
			//alert('date='+date);
			new Ajax.Updater($('div_liste_retenues_jour'),'liste_retenues_jour.php?date='+date,{method: 'get'});
		}
	}
</script>\n";

//=====================================================
// MENU
echo "<div id='s_menu' style='float:right; border: 1px solid black; background-color: white; width: 17em;'>\n";
echo "<ul style='margin:0px;'>\n";
echo "<li>\n";
echo "<a href='traiter_incident.php'";
echo " onclick='return confirm_abandon (this, change, \"$themessage\")'";
echo ">Liste des incidents</a>";
echo "</li>\n";

echo "<li>\n";
echo "<a href='liste_sanctions_jour.php'";
echo " onclick='return confirm_abandon (this, change, \"$themessage\")'";
echo ">Liste des sanctions du jour</a>";
echo "</li>\n";
echo "</ul>\n";
echo "</div>\n";
//=====================================================

echo "<p class='bold'><a href='index.php'";
echo " onclick='return confirm_abandon (this, change, \"$themessage\")'";
echo "><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour index</a>\n";

if(!isset($id_incident)) {
	echo "</p>\n";

	echo "<p><strong>Erreur&nbsp;:</strong> Il faut commencer par s�lectionner l'incident.</p>\n";
	require("../lib/footer.inc.php");
	die();
}

echo " | <a href='saisie_incident.php?id_incident=$id_incident&amp;step=2'";
echo " onclick='return confirm_abandon (this, change, \"$themessage\")'";
echo ">Retour incident</a>\n";

//if(!isset($mode)) {
if((!isset($mode))||($mode=="suppr_sanction")||($mode=="suppr_report")) {
	//echo " | <a href='traiter_incident.php'>Liste des incidents</a>\n";
	echo "</p>\n";

	// Affichage des protagonistes:
	$sql="SELECT * FROM s_protagonistes WHERE id_incident='$id_incident' ORDER BY statut,qualite,login;";
	//echo "$sql<br />";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		//echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post' name='formulaire'>\n";
		//echo "<input type='hidden' name='step' value='$step' />\n";

		echo "<p class='bold'>Protagonistes de l'incident n�$id_incident&nbsp;:</p>\n";

		echo "<blockquote>\n";

		echo "<table class='boireaus' border='1' summary='Protagonistes'>\n";
		echo "<tr>\n";
		echo "<th>Individu</th>\n";
		echo "<th>Statut</th>\n";
		//echo "<th>Qualit� dans l'incident</th>\n";
		echo "<th>R�le dans l'incident</th>\n";
		echo "<th>Traitement/sanction</th>\n";
		echo "</tr>\n";
		$alt=1;
		$cpt=0;
		while($lig=mysql_fetch_object($res)) {
			$alt=$alt*(-1);
			echo "<tr class='lig$alt'>\n";

			//Individu
			if($lig->statut=='eleve') {
				echo "<td>";
				$sql="SELECT nom,prenom FROM eleves WHERE login='$lig->login';";
				//echo "$sql<br />\n";
				$res2=mysql_query($sql);
				if(mysql_num_rows($res2)>0) {
					$lig2=mysql_fetch_object($res2);
					echo ucfirst(strtolower($lig2->prenom))." ".strtoupper($lig2->nom);
					echo infobulle_photo($lig->login);
				}
				else {
					echo "ERREUR: Login inconnu";
				}

				echo "</td>\n";
				echo "<td>";
				echo "�l�ve (<i>";
				$tmp_tab=get_class_from_ele_login($lig->login);
				if(isset($tmp_tab['liste_nbsp'])) {echo $tmp_tab['liste_nbsp'];}
				echo "</i>)";
				echo "</td>\n";
			}
			else {
				echo "<td>";
				$sql="SELECT nom,prenom,civilite FROM utilisateurs WHERE login='$lig->login';";
				//echo "$sql<br />\n";
				$res2=mysql_query($sql);
				if(mysql_num_rows($res2)>0) {
					$lig2=mysql_fetch_object($res2);
					echo ucfirst(strtolower($lig2->prenom))." ".strtoupper($lig2->nom);
				}
				else {
					echo "ERREUR: Login inconnu";
				}

				echo "</td>\n";

				//echo "<td>$lig->statut</td>\n";
				if($lig->statut=='autre') {
					//echo "<td>".$_SESSION['statut_special']."</td>\n";

					$sql = "SELECT ds.id, ds.nom_statut FROM droits_statut ds, droits_utilisateurs du
													WHERE du.login_user = '".$lig->login."'
													AND du.id_statut = ds.id;";
					$query = mysql_query($sql);
					$result = mysql_fetch_array($query);

					echo "<td>".$result['nom_statut']."</td>\n";
				}
				else {
					echo "<td>$lig->statut</td>\n";
				}
			}

			echo "<td>\n";
			//echo "<input type='hidden' name='ele_login[$cpt]' value=\"$lig->login\" />\n";
			echo $lig->qualite;
			echo "</td>\n";

			//echo "<td style='padding:3px;'>\n";
			echo "<td>\n";
			if($lig->statut=='eleve') {

				// Retenues
				$passage_report=false; //traiter les cas ou une sanction correspond � plusieurs retenues
				$sql="SELECT * FROM s_sanctions s, s_retenues sr WHERE s.id_incident=$id_incident AND s.login='".$lig->login."' AND sr.id_sanction=s.id_sanction ORDER BY sr.date, sr.heure_debut;";
				//echo "$sql<br />\n";
				$res_sanction=mysql_query($sql);
				$res_sanction_tmp=mysql_query($sql);
				if(mysql_num_rows($res_sanction)>0) {
					echo "<table class='boireaus' border='1' summary='Retenues' style='margin:2px;'>\n";
					echo "<tr>\n";
					echo "<th>Nature</th>\n";
					echo "<th>Date</th>\n";
					echo "<th>Heure</th>\n";
					echo "<th>Dur�e</th>\n";
					echo "<th>Lieu</th>\n";
					echo "<th>Travail</th>\n";
					
					$lig_sanction_tmp=mysql_fetch_object($res_sanction_tmp);
					$nombre_de_report=nombre_reports($lig_sanction_tmp->id_sanction,0);
					if ($nombre_de_report <> 0) {
					   echo "<th>Nbre report</th>\n";
					   $passage_report = true;
					}
//Eric
					echo "<th>Imprimer</th>\n";
//
					echo "<th>Suppr</th>\n";
					echo "</tr>\n";
					$alt_b=1;
					while($lig_sanction=mysql_fetch_object($res_sanction)) {
						$alt_b=$alt_b*(-1);
						echo "<tr class='lig$alt_b'>\n";
						//echo "<td>Retenue</td>\n";
						echo "<td><a href='".$_SERVER['PHP_SELF']."?mode=modif&amp;valeur=retenue&amp;id_sanction=$lig_sanction->id_sanction&amp;id_incident=$id_incident&amp;ele_login=$lig->login'>Retenue</a></td>\n";
						echo "<td>".formate_date($lig_sanction->date)."</td>\n";
						echo "<td>$lig_sanction->heure_debut</td>\n";
						echo "<td>$lig_sanction->duree</td>\n";
						echo "<td>$lig_sanction->lieu</td>\n";
						//echo "<td>".nl2br($lig_sanction->travail)."</td>\n";
						echo "<td>";

						$texte=nl2br($lig_sanction->travail);
						$tmp_doc_joints=liste_doc_joints_sanction($lig_sanction->id_sanction);
						if($tmp_doc_joints!="") {
							if($texte!="") {$texte.="<br />";}
							$texte.="<b>Documents joints</b>&nbsp;:<br />";
							$texte.=$tmp_doc_joints;
						}

						$tabdiv_infobulle[]=creer_div_infobulle("div_travail_sanction_$lig_sanction->id_sanction","Travail (sanction n�$lig_sanction->id_sanction)","",$texte,"",20,0,'y','y','n','n');

						echo " <a href='#' onmouseover=\"delais_afficher_div('div_travail_sanction_$lig_sanction->id_sanction','y',10,-40,$delais_affichage_infobulle,$largeur_survol_infobulle,$hauteur_survol_infobulle);\" onclick=\"return false;\">D�tails</a>";
						echo "</td>\n";
//Eric
						If ($passage_report) {
							$nombre_de_report=nombre_reports($lig_sanction->id_sanction,0);
							if ($nombre_de_report <> 0) {
								echo "<td>\n";
								echo $nombre_de_report;
								echo "</td>";
							} else {
							    echo "<td>\n";
								echo "";
								echo "</td>";
							}
						}
						
						echo "<td>";
						if ($gepiSettings['active_mod_ooo'] == 'y') { //impression avec mod_ooo
							echo "<a href='../mod_ooo/retenue.php?mode=module_retenue&amp;id_incident=$id_incident&amp;id_sanction=$lig_sanction->id_sanction&amp;ele_login=$lig->login".add_token_in_url()."' title='Imprimer la retenue'><img src='../images/icons/print.png' width='16' height='16' alt='Imprimer Retenue' /></a>\n";
						}
						else {
							echo "-";
						}
						echo "</td>\n";
//
						echo "<td><a href='".$_SERVER['PHP_SELF']."?mode=suppr_sanction&amp;id_sanction=$lig_sanction->id_sanction&amp;id_incident=$id_incident".add_token_in_url()."' title='Supprimer la sanction n�$lig_sanction->id_sanction'><img src='../images/icons/delete.png' width='16' height='16' alt='Supprimer la sanction n�$lig_sanction->id_sanction' /></a></td>\n";
						echo "</tr>\n";
					}
					echo "</table>\n";
				}

				// Exclusions
				$sql="SELECT * FROM s_sanctions s, s_exclusions se WHERE s.id_incident=$id_incident AND s.login='".$lig->login."' AND se.id_sanction=s.id_sanction ORDER BY se.date_debut, se.heure_debut;";
				//echo "$sql<br />\n";
				$res_sanction=mysql_query($sql);
				if(mysql_num_rows($res_sanction)>0) {
					echo "<table class='boireaus' border='1' summary='Exclusions' style='margin:2px;'>\n";
					echo "<tr>\n";
					echo "<th>Nature</th>\n";
					echo "<th>Date d�but</th>\n";
					echo "<th>Heure d�but</th>\n";
					echo "<th>Date fin</th>\n";
					echo "<th>Heure fin</th>\n";
					echo "<th>Lieu</th>\n";
					echo "<th>Travail</th>\n";
					echo "<th>Impr.</th>\n";
					echo "<th>Suppr</th>\n";
					echo "</tr>\n";
					$alt_b=1;
					while($lig_sanction=mysql_fetch_object($res_sanction)) {
						$alt_b=$alt_b*(-1);
						echo "<tr class='lig$alt_b'>\n";
						//echo "<td>Exclusion</td>\n";
						echo "<td><a href='".$_SERVER['PHP_SELF']."?mode=modif&amp;valeur=exclusion&amp;id_sanction=$lig_sanction->id_sanction&amp;id_incident=$id_incident&amp;ele_login=$lig->login'>Exclusion</a></td>\n";
						echo "<td>".formate_date($lig_sanction->date_debut)."</td>\n";
						echo "<td>$lig_sanction->heure_debut</td>\n";
						echo "<td>".formate_date($lig_sanction->date_fin)."</td>\n";
						echo "<td>$lig_sanction->heure_fin</td>\n";
						echo "<td>$lig_sanction->lieu</td>\n";
						//echo "<td>".nl2br($lig_sanction->travail)."</td>\n";
						echo "<td>";

						$texte=nl2br($lig_sanction->travail);
						$tmp_doc_joints=liste_doc_joints_sanction($lig_sanction->id_sanction);
						if($tmp_doc_joints!="") {
							if($texte!="") {$texte.="<br />";}
							$texte.="<b>Documents joints</b>&nbsp;:<br />";
							$texte.=$tmp_doc_joints;
						}
						$tabdiv_infobulle[]=creer_div_infobulle("div_travail_sanction_$lig_sanction->id_sanction","Travail (sanction n�$lig_sanction->id_sanction)","",$texte,"",20,0,'y','y','n','n');

						echo " <a href='#' onmouseover=\"delais_afficher_div('div_travail_sanction_$lig_sanction->id_sanction','y',10,-40,$delais_affichage_infobulle,$largeur_survol_infobulle,$hauteur_survol_infobulle);\" onclick=\"return false;\">D�tails</a>";
						echo "</td>\n";
//Eric						
						echo "<td>";
						if ($gepiSettings['active_mod_ooo'] == 'y') { //impression avec mod_ooo
							echo "<a href='".$_SERVER['PHP_SELF']."?odt=exclusion&amp;id_sanction=$lig_sanction->id_sanction&amp;id_incident=$id_incident&amp;ele_login=$lig->login".add_token_in_url()."' title='Imprimer la sanction n�$lig_sanction->id_sanction'><img src='../images/icons/print.png' width='16' height='16' alt=\"Imprimer le document\" /></a>\n";
						}
						else {
							echo "-";
						}
						echo "</td>\n";

						echo "<td><a href='".$_SERVER['PHP_SELF']."?mode=suppr_sanction&amp;id_sanction=$lig_sanction->id_sanction&amp;id_incident=$id_incident".add_token_in_url()."' title='Supprimer la sanction n�$lig_sanction->id_sanction'><img src='../images/icons/delete.png' width='16' height='16' alt='Supprimer la sanction n�$lig_sanction->id_sanction' /></a></td>\n";
						echo "</tr>\n";
					}
					echo "</table>\n";
				}

				// Simple travail
				$sql="SELECT * FROM s_sanctions s, s_travail st WHERE s.id_incident=$id_incident AND s.login='".$lig->login."' AND st.id_sanction=s.id_sanction ORDER BY st.date_retour;";
				//echo "$sql<br />\n";
				$res_sanction=mysql_query($sql);
				if(mysql_num_rows($res_sanction)>0) {
					echo "<table class='boireaus' border='1' summary='Travail' style='margin:2px;'>\n";
					echo "<tr>\n";
					echo "<th>Nature</th>\n";
					echo "<th>Date retour</th>\n";
					echo "<th>Travail</th>\n";
					echo "<th>Suppr</th>\n";
					echo "</tr>\n";
					$alt_b=1;
					while($lig_sanction=mysql_fetch_object($res_sanction)) {
						$alt_b=$alt_b*(-1);
						echo "<tr class='lig$alt_b'>\n";
						echo "<td><a href='".$_SERVER['PHP_SELF']."?mode=modif&amp;valeur=travail&amp;id_sanction=$lig_sanction->id_sanction&amp;id_incident=$id_incident&amp;ele_login=$lig->login'>Travail</a></td>\n";
						echo "<td>".formate_date($lig_sanction->date_retour)."</td>\n";
						//echo "<td>".nl2br($lig_sanction->travail)."</td>\n";
						echo "<td>";

						$texte=nl2br($lig_sanction->travail);
						$tmp_doc_joints=liste_doc_joints_sanction($lig_sanction->id_sanction);
						if($tmp_doc_joints!="") {
							if($texte!="") {$texte.="<br />";}
							$texte.="<b>Documents joints</b>&nbsp;:<br />";
							$texte.=$tmp_doc_joints;
						}
						$tabdiv_infobulle[]=creer_div_infobulle("div_travail_sanction_$lig_sanction->id_sanction","Travail (sanction n�$lig_sanction->id_sanction)","",$texte,"",20,0,'y','y','n','n');

						echo " <a href='#' onmouseover=\"delais_afficher_div('div_travail_sanction_$lig_sanction->id_sanction','y',10,-40,$delais_affichage_infobulle,$largeur_survol_infobulle,$hauteur_survol_infobulle);\" onclick=\"return false;\">D�tails</a>";
						echo "</td>\n";

						echo "<td><a href='".$_SERVER['PHP_SELF']."?mode=suppr_sanction&amp;id_sanction=$lig_sanction->id_sanction&amp;id_incident=$id_incident".add_token_in_url()."' title='Supprimer la sanction n�$lig_sanction->id_sanction'><img src='../images/icons/delete.png' width='16' height='16' alt='Supprimer la sanction n�$lig_sanction->id_sanction' /></a></td>\n";
						echo "</tr>\n";
					}
					echo "</table>\n";
				}



				// Autres sanctions
				$sql="SELECT * FROM s_sanctions s, s_autres_sanctions sa, s_types_sanctions sts WHERE s.id_incident='$id_incident' AND s.login='".$lig->login."' AND sa.id_sanction=s.id_sanction AND sa.id_nature=sts.id_nature ORDER BY sts.nature;";
				//echo "$sql<br />\n";
				$res_sanction=mysql_query($sql);
				if(mysql_num_rows($res_sanction)>0) {
					echo "<table class='boireaus' border='1' summary='Autres sanctions' style='margin:2px;'>\n";
					echo "<tr>\n";
					echo "<th>Nature</th>\n";
					echo "<th>Description</th>\n";
					echo "<th>Suppr</th>\n";
					echo "</tr>\n";
					$alt_b=1;
					while($lig_sanction=mysql_fetch_object($res_sanction)) {
						$alt_b=$alt_b*(-1);
						echo "<tr class='lig$alt_b'>\n";
						echo "<td><a href='".$_SERVER['PHP_SELF']."?mode=modif&amp;valeur=".$lig_sanction->id_nature."&amp;id_sanction=$lig_sanction->id_sanction&amp;id_incident=$id_incident&amp;ele_login=$lig->login'>$lig_sanction->nature</a></td>\n";

						echo "<td>\n";
						$texte=nl2br($lig_sanction->description);
						$tabdiv_infobulle[]=creer_div_infobulle("div_autre_sanction_$lig_sanction->id_sanction","$lig_sanction->nature (sanction n�$lig_sanction->id_sanction)","",$texte,"",20,0,'y','y','n','n');

						echo " <a href='#' onmouseover=\"delais_afficher_div('div_autre_sanction_$lig_sanction->id_sanction','y',10,-40,$delais_affichage_infobulle,$largeur_survol_infobulle,$hauteur_survol_infobulle);\" onclick=\"return false;\">D�tails</a>";
						echo "</td>\n";

						echo "<td><a href='".$_SERVER['PHP_SELF']."?mode=suppr_sanction&amp;id_sanction=$lig_sanction->id_sanction&amp;id_incident=$id_incident".add_token_in_url()."' title='Supprimer la sanction n�$lig_sanction->id_sanction'><img src='../images/icons/delete.png' width='16' height='16' alt='Supprimer la sanction n�$lig_sanction->id_sanction' /></a></td>\n";
						echo "</tr>\n";
					}
					echo "</table>\n";
				}


				echo "<a href='".$_SERVER['PHP_SELF']."?id_incident=$id_incident&amp;ele_login=$lig->login&amp;mode=ajout' title='Ajouter une sanction'><img src='../images/icons/add.png' width='16' height='16' alt='Ajouter une sanction' /></a>";
			}
			else {
				// Pas de sanction pour un personnel, non mais sans blagues;o)
				echo "&nbsp;";
			}
			echo "</td>\n";

			echo "</tr>\n";
			$cpt++;
		}
		echo "</table>\n";
		echo "</blockquote>\n";

		rappel_incident($id_incident);
	}
	else {
		echo "<p>Aucun protagoniste n'a (<i>encore</i>) �t� sp�cifi� pour cet incident.</p>\n";
	}

	require("../lib/footer.inc.php");
	die();
}
elseif($mode=='ajout') {
	echo " | <a href='saisie_sanction.php?id_incident=$id_incident&amp;step=2'";
	echo " onclick='return confirm_abandon (this, change, \"$themessage\")'";
	echo ">Retour sanction</a>\n";
	//echo " | <a href='traiter_incident.php'>Liste des incidents</a>\n";
	echo "</p>\n";

	echo "<script type='text/javascript'>
	//function edt_eleve(id_sanction) {
	function edt_eleve() {
		// Avec l'appel Ajax lors d'un Ajout de sanction, on ne parvient pas � r�cup�rer la valeur de ele_login
		//ele_login=document.getElementById('ele_login').value;
		ele_login='$ele_login';
		centrerpopup('edt_eleve.php?ele_login='+ele_login,800,600,'scrollbars=yes,statusbar=no,resizable=yes');
	}
</script>\n";

	echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post' name='formulaire'>\n";
	//echo "<input type='hidden' name='step' value='$step' />\n";

	echo add_token_field(true);

	echo "<p class='bold'>Ajout d'une sanction pour ".p_nom($ele_login);
	echo infobulle_photo($ele_login);
	echo " (<em>incident n�$id_incident</em>)&nbsp;:</p>\n";

	echo "<blockquote>\n";

	$largeur_champ_select=11;
	$tab_autres_sanctions=array();
	$sql="SELECT * FROM s_types_sanctions ORDER BY nature;";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		while($lig=mysql_fetch_object($res)) {
			$tab_autres_sanctions[$lig->id_nature]=$lig->nature;
			//if(strlen($lig->nature)>$largeur_champ_select) {$largeur_champ_select=strlen($lig->nature);}
		}
		$largeur_champ_select=20;
		// On peut quand m�me se retrouver avec une superposition.
	}

	echo "<div style='float:left; width:".$largeur_champ_select."em;'>\n";

	echo "<p class='bold'>Nature de la sanction&nbsp;:<br />\n";
	echo "<select name='traitement' id='traitement' onchange=\"maj_traitement()\">\n";
	echo "<option value=''";
	echo ">---</option>\n";

	echo "<option value='travail'";
	echo ">Travail</option>\n";

	echo "<option value='retenue'";
	echo ">Retenue</option>\n";

	echo "<option value='exclusion'";
	echo ">Exclusion</option>\n";

	foreach($tab_autres_sanctions AS $key => $value) {
		echo "<option value='$key'";
		echo ">$value</option>\n";
	}

	/*
	$sql="SELECT * FROM s_types_sanctions ORDER BY nature;";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		while($lig=mysql_fetch_object($res)) {
			echo "<option value='$lig->id_nature'";
			echo ">$lig->nature</option>\n";
		}
	}
	*/

	echo "</select>\n";
	echo "</p>\n";
	echo "</div>\n";

	echo "<div style='clear:both;'></div>\n";

	// Pour afficher les autres champs de formulaire des d�tails de la sanction:
	echo "<div id='div_details_sanction'>\n";
	echo "</div>\n";

	//echo "<input type='submit' name='enregistrer_sanction' value='Enregistrer' />\n";
	echo "</blockquote>\n";

	echo "<input type='hidden' name='id_incident' value='$id_incident' />\n";
	echo "<input type='hidden' name='ele_login' value='$ele_login' />\n";
	echo "</form>\n";


	echo "<script type='text/javascript'>
	// Avec cette fonction, on ne fait qu'ajouter des champs dans le formulaire (aucun enregistrement avant validation du formulaire)
	function maj_traitement() {
		valeur=$('traitement').value;
		//new Ajax.Updater($('div_details_sanction'),'ajout_sanction.php?cpt=0&valeur='+valeur,{method: 'get'});
		new Ajax.Updater($('div_details_sanction'),'ajout_sanction.php?cpt=0&valeur='+valeur+'&ele_login=$ele_login&id_incident=$id_incident',{method: 'get'});
	}
</script>\n";


echo "<script type='text/javascript'>
	function maj_lieu(id_lieu,champ_select) {
		if(document.getElementById(id_lieu)) {
			//document.getElementById(id_lieu).value=document.getElementById(champ_select).selectedIndex;
			document.getElementById(id_lieu).value=document.getElementById(champ_select).options[document.getElementById(champ_select).selectedIndex].value;
		}
	}
</script>\n";

	//echo "<div style='clear:both;'></div>\n";

	echo envoi_mail_rappel_js();

	rappel_incident($id_incident);

}
elseif($mode=='modif') {
	echo " | <a href='saisie_sanction.php?id_incident=$id_incident&amp;step=2'";
	echo " onclick='return confirm_abandon (this, change, \"$themessage\")'";
	echo ">Retour sanction</a>\n";
	//echo " | <a href='traiter_incident.php'>Liste des incidents</a>\n";
	echo "</p>\n";

	if(!isset($temoin_modif_fichier)) {
		$valeur=isset($_GET['valeur']) ? $_GET['valeur'] : NULL;
	}
	//$valeur=isset($_POST['traitement']) ? $_POST['traitement'] : (isset($_GET['valeur']) ? $_GET['valeur'] : NULL);
	$traitement=$valeur;

	echo "<script type='text/javascript'>
	//function edt_eleve(id_sanction) {
	function edt_eleve() {
		// Avec l'appel Ajax lors d'un Ajout de sanction, on ne parvient pas � r�cup�rer la valeur de ele_login
		//ele_login=document.getElementById('ele_login').value;
		ele_login='$ele_login';
		centrerpopup('edt_eleve.php?ele_login='+ele_login,800,600,'scrollbars=yes,statusbar=no,resizable=yes');
	}
</script>\n";

	echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post' name='formulaire'>\n";

	echo add_token_field(true);

	echo "<p class='bold'>Sanction (<em>$traitement</em>) n�$id_sanction concernant ".p_nom($ele_login);
	echo infobulle_photo($ele_login);
	echo "&nbsp;:</p>\n";
	echo "<blockquote>\n";

	echo "<input type='hidden' name='ele_login' id='ele_login' value='$ele_login' />\n";
	include('saisie_sanction.inc.php');
	echo "</blockquote>\n";

	echo "<input type='hidden' name='traitement' value='$traitement' />\n";
	echo "<input type='hidden' name='id_sanction' value='$id_sanction' />\n";
	echo "<input type='hidden' name='id_incident' value='$id_incident' />\n";
	echo "</form>\n";

	echo envoi_mail_rappel_js();

	rappel_incident($id_incident);

	echo "<script type='text/javascript'>
	function maj_lieu(id_lieu,champ_select) {
		if(document.getElementById(id_lieu)) {
			//document.getElementById(id_lieu).value=document.getElementById(champ_select).selectedIndex;
			document.getElementById(id_lieu).value=document.getElementById(champ_select).options[document.getElementById(champ_select).selectedIndex].value;
		}
	}
</script>\n";

}
else {
	echo "<p>Euh... c'est pas pr�vu qu'on arrive l� (pour le moment;o).</p>\n";
}


echo "<p><br /></p>\n";

require("../lib/footer.inc.php");
?>