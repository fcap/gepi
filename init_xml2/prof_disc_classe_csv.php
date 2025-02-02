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
//extract($_POST, EXTR_OVERWRITE);

// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
	header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
	die();
} else if ($resultat_session == '0') {
	header("Location: ../logout.php?auto=1");
	die();
}

//===========================================

if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}
/*
if($_SESSION['statut']!='administrateur') {
	header("Location: ../logout.php?auto=1");
	die();
}
*/

check_token();

//===========================================

$step1=isset($_POST['step1']) ? $_POST['step1'] : (isset($_GET['step1']) ? $_GET['step1'] : NULL);
$suite=isset($_GET['suite']) ? $_GET['suite'] : NULL;

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
		echo "<font color='green'>$texte</font>\n";
		flush();
	}
}

//$debug=1;
//=====================================


//**************** EN-TETE *****************
$titre_page = "Outil d'initialisation de l'ann�e : Importation des relations professeurs/classes/mati�res";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

require_once("init_xml_lib.php");
?>
<p class="bold"><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour accueil initialisation</a></p>
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

		echo "<p>Les tables vid�es seront&nbsp;: 'j_groupes_professeurs' et 'j_professeurs_matieres'</p>\n";

		echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post'>\n";
		echo add_token_field();
		echo "<input type='hidden' name='step1' value='y' />\n";
		echo "<input type='hidden' name='temoin_nettoyage_a_faire' value='y' />\n";
		echo "<input type='submit' name='confirm' value='Poursuivre la proc�dure' />\n";
		echo "</form>\n";
		require("../lib/footer.inc.php");
		die();
	}
}


$tempdir=get_user_temp_directory();
if(!$tempdir){
	echo "<p style='color:red'>Il semble que le dossier temporaire de l'utilisateur ".$_SESSION['login']." ne soit pas d�fini!?</p>\n";
	// Il ne faut pas aller plus loin...
	// SITUATION A GERER
}

//if (!isset($is_posted)) {
if (!isset($suite)) {

	//if(isset($_POST['temoin_nettoyage_a_faire'])) {
	//	check_token(false);
		$del = @mysql_query("DELETE FROM j_groupes_professeurs");
		$del = @mysql_query("DELETE FROM j_professeurs_matieres");
	//}

	/*
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
	*/

	$dest_file="../temp/".$tempdir."/sts.xml";

	$sts_xml=simplexml_load_file($dest_file);
	if(!$sts_xml) {
		echo "<p style='color:red;'>ECHEC du chargement du fichier avec simpleXML.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}

	$nom_racine=$sts_xml->getName();
	if(strtoupper($nom_racine)!='STS_EDT') {
		echo "<p style='color:red;'>ERREUR: Le fichier XML fourni n'a pas l'air d'�tre un fichier XML STS_EMP_&lt;RNE&gt;_&lt;ANNEE&gt;.<br />Sa racine devrait �tre 'STS_EDT'.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}

	echo "Analyse du fichier pour extraire les informations de la section DIVISIONS...<br />\n";

	$divisions=array();
	$i=0;
	foreach($sts_xml->DONNEES->STRUCTURE->DIVISIONS->children() as $objet_division) {
		$divisions[$i]=array();

		foreach($objet_division->attributes() as $key => $value) {
			if(strtoupper($key)=='CODE') {
				$divisions[$i]['code']=preg_replace('/"/','',trim(traite_utf8($value)));
				//echo "<p>\$divisions[$i]['code']=".$divisions[$i]['code']."<br />";
				break;
			}
		}

		// Champs de la division
		$j=0;

		foreach($objet_division->SERVICES->children() as $service) {
			foreach($service->attributes() as $key => $value) {
				$divisions[$i]["services"][$j][strtolower($key)]=trim(traite_utf8($value));
				//echo "\$divisions[$i][\"services\"][$j][".strtolower($key)."]=trim(traite_utf8($value))<br />";
			}

			$k=0;
			foreach($service->ENSEIGNANTS->children() as $enseignant) {

				foreach($enseignant->attributes() as $key => $value) {
					//<ENSEIGNANT ID="8949" TYPE="epp">
					//$divisions[$i]["services"][$j]["enseignants"][$k][strtolower($key)]=trim(traite_utf8($value));
					if(strtoupper($key)=="ID") {
						$divisions[$i]["services"][$j]["enseignants"][$k]["id"]=trim(traite_utf8($value));
						break;
					}
				}
				$k++;
			}
			$j++;
		}
		$i++;
	}

	$groupes=array();
	$i=0;

	$tab_champs_groupe=array("LIBELLE_LONG");

	foreach($sts_xml->DONNEES->STRUCTURE->GROUPES->children() as $objet_groupe) {
		$groupes[$i]=array();

		foreach($objet_groupe->attributes() as $key => $value) {
			if(strtoupper($key)=='CODE') {
				$groupes[$i]['code']=preg_replace('/"/',"",trim(traite_utf8($value)));
				//echo("<p>\$groupes[$i]['code']=".$groupes[$i]['code']."<br />");
				break;
			}
		}

		// Champs enfants du groupe
		foreach($objet_groupe->children() as $key => $value) {
			if(in_array(strtoupper($key),$tab_champs_groupe)) {
				$groupes[$i][strtolower($key)]=preg_replace('/"/',"",trim(traite_utf8($value)));
			}
		}

		if((!isset($groupes[$i]['libelle_long']))||($groupes[$i]['libelle_long']=='')) {
			$groupes[$i]['libelle_long']=$groupes[$i]['code'];
		}

		$j=0;
		foreach($objet_groupe->DIVISIONS_APPARTENANCE->children() as $objet_division_apartenance) {
			foreach($objet_division_apartenance->attributes() as $key => $value) {
				$groupes[$i]["divisions"][$j][strtolower($key)]=preg_replace('/"/',"",trim(traite_utf8($value)));
			}
			$j++;
		}

		$j=0;
		foreach($objet_groupe->SERVICES->children() as $service) {
			foreach($service->attributes() as $key => $value) {
				//$groupes[$i]["service"][$j][strtolower($key)]=trim(traite_utf8($value));
				$groupes[$i]["grp"][$j][strtolower($key)]=trim(traite_utf8($value));
				// Remarque: Pour les divisions, c'est ["services"] au lieu de ["service"]
				//           $divisions[$i]["services"][$j][strtolower($key)]=trim(traite_utf8($value));
			}

			$k=0;
			foreach($service->ENSEIGNANTS->children() as $enseignant) {

				foreach($enseignant->attributes() as $key => $value) {
					//<ENSEIGNANT ID="8949" TYPE="epp">
					//$divisions[$i]["services"][$j]["enseignants"][$k][strtolower($key)]=trim(traite_utf8($value));
					if(strtoupper($key)=="ID") {
						//$groupes[$i]["service"][$j]["enseignant"][$k]["id"]=trim(traite_utf8($value));
						$groupes[$i]["grp"][$j]["enseignant"][$k]["id"]=trim(traite_utf8($value));
						break;
					}
				}
				$k++;
			}
			$j++;
		}
		$i++;
	}


	// On r�cup�re les correspondances code/code_gestion sur les mati�res.
	$sql="SELECT code,code_gestion FROM temp_matieres_import";
	$res_mat=mysql_query($sql);
	if(mysql_num_rows($res_mat)==0){
		echo "<p>La table 'temp_matieres_import' est vide.<br />Auriez-vous saut� des �tapes???</p>\n";
		require("../lib/footer.inc.php");
		die();
	}
	$cpt=0;
	while($lig_mat=mysql_fetch_object($res_mat)){
		$matiere[$cpt]=array();
		$matiere[$cpt]["code"]=$lig_mat->code;
		$matiere[$cpt]["code_gestion"]=$lig_mat->code_gestion;
		//echo "$lig_mat->code;$lig_mat->code_gestion<br />";
		$cpt++;
	}

	function get_code_gestion_from_code($id_mat) {
		global $matiere;
		for($m=0;$m<count($matiere);$m++){
			if($matiere[$m]["code"]==$id_mat){
				return $matiere[$m]["code_gestion"];
				break;
			}
		}
	}

	function get_nom_complet_from_matiere($mat) {
		$sql="SELECT nom_complet FROM matieres WHERE matiere='$mat';";
		$res_mat=mysql_query($sql);
		if(mysql_num_rows($res_mat)>0) {
			$lig_mat=mysql_fetch_object($res_mat);
			return $lig_mat->nom_complet;
		}
	}

	function creation_matiere($matiere_name, $matiere_nom_complet, $matiere_priorite=0, $matiere_categorie=0) {
		$sql="SELECT * from matieres WHERE matiere='$matiere_name';";
		$verif=mysql_query($sql);;
		if(mysql_num_rows($verif)==0) {
			$sql="INSERT INTO matieres SET matiere='".addslashes($matiere_name)."', nom_complet='".addslashes($matiere_nom_complet)."', priority='".$matiere_priorite."', categorie_id = '" . $matiere_categorie . "',matiere_aid='n',matiere_atelier='n';";
			$insert=mysql_query($sql);
		}
	}

	creation_matiere('X_X_X','Matiere inconnue');

	echo "<hr />\n";

	echo "<p>A cette �tape, les �l�ves vont �tre affect�s dans tous les groupes.<br />Ce n'est qu'� l'�tape suivante que les options vont �tre prises en compte pour �laguer les groupes.</p>\n";
	// A REVOIR... � moins que cette page soit d�j� longue en traitement...

	$temoin_div_sans_services=0;
	$temoin_service_sans_enseignant=0;
	echo "<h3>Cr�ation des groupes classe enti�re</h3>\n";
	for ($i=0;$i<count($divisions);$i++) {
		$classe=$divisions[$i]['code'];

		echo "<p class='bold'>Classe de $classe</p>\n";
		echo "<blockquote>\n";

		$sql="SELECT id FROM classes WHERE classe='$classe';";
		$res_clas=mysql_query($sql);
		$nb_clas=mysql_num_rows($res_clas);
		if($nb_clas>1) {
			echo "<p style='color:red;'>ANOMALIE: ".$nb_clas." classes ont le m�me nom: ".$classe."<br />Les groupes ne peuvent pas �tre import�s pour ces classes.</p>\n";
		}
		elseif($nb_clas==1) {

			$lig_clas=mysql_fetch_object($res_clas);
			$id_classe=$lig_clas->id;

			// R�cup�ration des �l�ves de la classe
			$sql="SELECT DISTINCT login FROM j_eleves_classes WHERE id_classe='$id_classe' ORDER BY login;";
			$res_ele=mysql_query($sql);
			$tab_ele=array();
			if(mysql_num_rows($res_ele)>0) {
				while($lig_ele=mysql_fetch_object($res_ele)) {
					$tab_ele[]=$lig_ele->login;
				}
			}

			// R�cup�ration des p�riodes de la classe
			$tab_per=array();
			$periode_query=mysql_query("SELECT * FROM periodes WHERE id_classe='$id_classe' ORDER BY num_periode");
			$nb_periode=mysql_num_rows($periode_query)+1;
			$j="1";
			while ($j<$nb_periode) {
				$tab_per[]=$j;
				//echo "\$tab_per[]=$j<br />";
				$j++;
			}

			$tab_clas=array($id_classe);

			if(!isset($divisions[$i]['services'])) {
				echo "<p style='color:red;'>Aucun service n'est d�fini pour cette classe.<br />L'emploi du temps a-t-il �t� remont� vers STS?</p>\n";
				$temoin_div_sans_services++;
			}
			else {
				for($j=0;$j<count($divisions[$i]['services']);$j++) {
					$id_mat=$divisions[$i]['services'][$j]['code_matiere'];
					$mat=get_code_gestion_from_code($id_mat);
	
					$nom_grp=$mat;
					$descr_grp=get_nom_complet_from_matiere($mat);
	
					// Cr�er le groupe:                   groupes
					// L'associer � la classe:            j_groupes_classes
					// L'associer � la mati�re:           j_groupes_matieres
					echo "<p>Cr�ation du groupe $descr_grp (<i>$nom_grp</i>) en $classe: ";
					if($id_groupe=create_group($nom_grp, $descr_grp, $mat, $tab_clas)) {
						echo "<span style='color:green;'>$id_groupe</span>";
						//echo "<br />\n";
						echo "<blockquote>\n";
	
						echo "Professeur(s): ";
						if(!isset($divisions[$i]['services'][$j]['enseignants'])) {
							echo "<p style='color:red;'>Aucun enseignant n'est associ� � ce service.<br />L'emploi du temps a-t-il �t� correctement renseign� lors de la remont�e vers STS?</p>\n";
							$temoin_service_sans_enseignant++;
						}
						else {
	
							for($k=0;$k<count($divisions[$i]['services'][$j]['enseignants']);$k++) {
		
								$sql="select col1 from tempo2 where col2='P".$divisions[$i]['services'][$j]['enseignants'][$k]['id']."';";
								$res_prof=mysql_query($sql);
								$login_prof=@mysql_result($res_prof, 0, 'col1');
		
								if ($login_prof!='') {
									// Associer le groupe au prof:    j_groupes_professeurs
									$sql="SELECT 1=1 FROM j_groupes_professeurs WHERE id_groupe='$id_groupe' AND login='$login_prof';";
									$res_grp_prof=mysql_query($sql);
									if(mysql_num_rows($res_grp_prof)==0) {
										$sql="INSERT INTO j_groupes_professeurs SET id_groupe='$id_groupe', login='$login_prof';";
										if($insert=mysql_query($sql)) {
											echo "<span style='color:green;'>";
										}
										else {
											echo "<span style='color:red;'>";
										}
										echo "$login_prof</span>";
									}
		
									// Associer le prof � la mati�re: j_professeurs_matieres
									$sql="SELECT 1=1 FROM j_professeurs_matieres WHERE id_matiere='$mat' AND id_professeur='$login_prof';";
									$res_prof_mat=mysql_query($sql);
									echo " (";
									if(mysql_num_rows($res_prof_mat)==0) {
										$sql="INSERT INTO j_professeurs_matieres SET id_matiere='$mat', id_professeur='$login_prof';";
										if($insert=mysql_query($sql)) {
											echo "<span style='color:green;'>";
										}
										else {
											echo "<span style='color:red;'>";
										}
									}
									else {
										echo "<span style='color:black;'>";
									}
									echo "$mat</span>)";
		
								}
								//else {echo "prof inconnu";}
							}
						}
						echo "<br />\n";
	
						// Mettre tous les �l�ves dans le groupe pour toutes les p�riodes: j_eleves_groupes
						echo "Association des �l�ves:<br />";
						echo "<blockquote>\n";
						for($k=0;$k<count($tab_ele);$k++) {
							if($k>0) {echo " - ";}
							echo $tab_ele[$k]." (";
							for($l=0;$l<count($tab_per);$l++) {
								if($l>0) {echo "-";}
								$sql="SELECT 1=1 FROM j_eleves_groupes WHERE id_groupe='$id_groupe' AND login='".$tab_ele[$k]."' AND periode='".$tab_per[$l]."';";
								//echo "$sql<br />";
								$res_ele_grp=mysql_query($sql);
								if(mysql_num_rows($res_ele_grp)==0) {
									$sql="INSERT INTO j_eleves_groupes SET id_groupe='$id_groupe', login='".$tab_ele[$k]."', periode='".$tab_per[$l]."';";
									if($insert=mysql_query($sql)) {
										echo "<span style='color:green;'>";
									}
									else {
										//echo "$sql<br />\n";
										echo "<span style='color:red;'>";
									}
								}
								else {
									echo "<span style='color:black;'>";
								}
								echo $tab_per[$l]."</span>";
							}
							echo ")";
						}
						echo "</blockquote>\n";
	
						echo "</blockquote>\n";
					}
					else {
						echo "<span style='color:red;'>ERREUR</span>";
					}
					echo "</p>\n";
				}
			}
		}
		echo "</blockquote>\n";
	}

	$nb_groupes_sans_matieres=0;
	echo "<h3>Cr�ation des groupes</h3>\n";
	// Traiter les groupes ensuite
	if(!isset($groupes)) {
		echo "<p>Aucun groupe n'est d�fini.</p>\n";		
	}
	else {
		for ($i=0;$i<count($groupes);$i++) {
			$code_groupe=$groupes[$i]['code'];
			$libelle_groupe=$groupes[$i]['libelle_long'];

			//echo "<p>\$code_groupe=$code_groupe<br />";
			//echo "\$libelle_groupe=$libelle_groupe<br />";

			$tab_clas=array();
			$tab_ele=array();
			$tab_per_clas=array();

			$max_per=0;

			$list_classe="";
	
			$cpt_clas=0;
	
			for ($ii=0;$ii<count($groupes[$i]['divisions']);$ii++) {
				$classe=$groupes[$i]['divisions'][$ii]['code'];
				$sql="SELECT id FROM classes WHERE classe='$classe';";
				$res_clas=mysql_query($sql);
				$nb_clas=mysql_num_rows($res_clas);
				if($nb_clas==1) {
					$lig_clas=mysql_fetch_object($res_clas);
					$id_classe=$lig_clas->id;
	
					//echo "\$id_classe=$id_classe<br />";

					// R�cup�ration des p�riodes de la classe
					//$tab_per=array();
					$periode_query=mysql_query("SELECT MAX(num_periode) FROM periodes WHERE id_classe='$id_classe';");
					if(mysql_num_rows($periode_query)>0) {
						$max_per=mysql_result($periode_query,0);
						$tab_per_clas[]=$max_per;

						$tab_test=array_unique($tab_per_clas);
						if(count($tab_test)==1) {
							// R�cup�ration des �l�ves de la classe
							$sql="SELECT DISTINCT login FROM j_eleves_classes WHERE id_classe='$id_classe' ORDER BY login;";
							$res_ele=mysql_query($sql);
							if(mysql_num_rows($res_ele)>0) {
								while($lig_ele=mysql_fetch_object($res_ele)) {
									$tab_ele[]=$lig_ele->login;
								}
							}
	
							//$tab_clas[]=$classe;
							//$tab_id_clas[]=$id_classe;
							$tab_clas[]=$id_classe;
	
							if($list_classe!="") {$list_classe.=", ";}
							$list_classe.=$classe;
						}
						else {
							echo "<p style='color:red'>PROBLEME&nbsp;: Des classes n'ayant pas le m�me nombre de p�riodes sont associ�es dans un m�me groupe.</p>\n";
						}

						//for($loop=0;$loop<count($tab_test);$loop++) {
						//	echo "P�riodes \$tab_test[$loop]=$tab_test[$loop]<br />";
						//}
					}
				}
			}
	
	
			if(!isset($groupes[$i]['code'])) {
				echo "<p style='color:red'>Le groupe n�$i n'a pas la forme standard dans le XML de STS.<br />Il ne peut pas �tre cr�� dans GEPI.</p>\n";
			}
			elseif(!isset($groupes[$i]['grp'])) {
				echo "<p style='color:red'>Le groupe n�$i nomm� dans STS <b>".$groupes[$i]['code']."</b> n'est associ� � aucune mati�re dans STS???<br />\n";

				$nb_groupes_sans_matieres++;

				$code_groupe=$groupes[$i]['code'];
				$nom_grp=$groupes[$i]['code'];
				$descr_grp=$groupes[$i]['code'];
				$mat="X_X_X";

				echo "<p><span style='color:red'>On cr�e n�anmoins le groupe.<br />Vous devrez revoir l'association avec une mati�re.</span><br />Cr�ation du groupe $descr_grp (<i>$nom_grp</i>) en $list_classe";
				echo " (<i style='font-size:x-small;'>nom sts: ".$code_groupe."</i>)";
				echo ": ";
				if($id_groupe=create_group($nom_grp, $descr_grp, $mat, $tab_clas)) {
					echo "<span style='color:green;'>$id_groupe</span>";
					//echo "<br />\n";
					echo "<blockquote>\n";

					// Mettre tous les �l�ves dans le groupe pour toutes les p�riodes: j_eleves_groupes
					echo "Association des �l�ves:<br />";
					echo "<blockquote>\n";

					$sql="SELECT login FROM eleves e, temp_grp t WHERE e.ele_id=t.ELE_ID AND t.NOM_GRP='".addslashes($code_groupe)."';";
					$get_login_ele=mysql_query($sql);
					if(mysql_num_rows($get_login_ele)==0) {
						// On va mettre tous les �l�ves dans le groupe
						$tab_ele_courant=$tab_ele;
					}
					else {
						$tab_ele_courant=array();
						while($lig_ele=mysql_fetch_object($get_login_ele)) {
							// Normalement on n'a qu'un enregistrement par ele/grp, mais au cas o�
							if(!in_array($lig_ele->login,$tab_ele_courant)) {
								$tab_ele_courant[]=$lig_ele->login;
							}
						}
					}

					for($k=0;$k<count($tab_ele_courant);$k++) {
						if($k>0) {echo " - ";}
						echo $tab_ele_courant[$k]." (";
						//for($l=0;$l<count($tab_per);$l++) {
						for($l=1;$l<=$max_per;$l++) {
							if($l>0) {echo "-";}
							//$sql="SELECT 1=1 FROM j_eleves_groupes WHERE id_groupe='$id_groupe' AND login='".$tab_ele_courant[$k]."' AND periode='".$tab_per[$l]."';";
							$sql="SELECT 1=1 FROM j_eleves_groupes WHERE id_groupe='$id_groupe' AND login='".$tab_ele_courant[$k]."' AND periode='".$l."';";
							//echo "$sql<br />";
							$res_ele_grp=mysql_query($sql);
							if(mysql_num_rows($res_ele_grp)==0) {
								//$sql="INSERT INTO j_eleves_groupes SET id_groupe='$id_groupe', login='".$tab_ele_courant[$k]."', periode='".$tab_per[$l]."';";
								$sql="INSERT INTO j_eleves_groupes SET id_groupe='$id_groupe', login='".$tab_ele_courant[$k]."', periode='".$l."';";
								if($insert=mysql_query($sql)) {
									echo "<span style='color:green;'>";
								}
								else {
									//echo "$sql<br />\n";
									echo "<span style='color:red;'>";
								}
							}
							else {
								echo "<span style='color:black;'>";
							}
							//echo $tab_per[$l]."</span>";
							echo $l."</span>";
						}
						echo ")";
					}
					echo "</blockquote>\n";
	
					echo "</blockquote>\n";

				}
			}
			else {
				for($i_grp=0;$i_grp<count($groupes[$i]['grp']);$i_grp++) {
					$id_mat=$groupes[$i]['grp'][$i_grp]['code_matiere'];
					$mat=get_code_gestion_from_code($id_mat);
		
					//$nom_grp=$mat;
					$nom_grp=$mat."_".remplace_accents($code_groupe,'all');
					$descr_grp=get_nom_complet_from_matiere($mat)." (".$code_groupe.")";

					echo "<p>Cr�ation du groupe $descr_grp (<i>$nom_grp</i>) en $list_classe";
					echo " (<i style='font-size:x-small;'>nom sts: ".$code_groupe."</i>)";
					echo ": ";
					if($id_groupe=create_group($nom_grp, $descr_grp, $mat, $tab_clas)) {
						echo "<span style='color:green;'>$id_groupe</span>";
						//echo "<br />\n";
						echo "<blockquote>\n";
		
						echo "Professeur(s): ";
						if((!isset($groupes[$i]['grp'][$i_grp]['enseignant']))||(count($groupes[$i]['grp'][$i_grp]['enseignant'])==0)) {
							echo "<span style='color:red'>Aucun professeur n'est associ� � ce groupe</span>";
						}
						else {
							for($k=0;$k<count($groupes[$i]['grp'][$i_grp]['enseignant']);$k++) {
								if($k>0) {echo ", ";}
			
								$sql="select col1 from tempo2 where col2='P".$groupes[$i]['grp'][$i_grp]['enseignant'][$k]['id']."';";
								$res_prof=mysql_query($sql);
								$login_prof=@mysql_result($res_prof, 0, 'col1');
			
								if ($login_prof!='') {
									// Associer le groupe au prof:    j_groupes_professeurs
									$sql="SELECT 1=1 FROM j_groupes_professeurs WHERE id_groupe='$id_groupe' AND login='$login_prof';";
									$res_grp_prof=mysql_query($sql);
									if(mysql_num_rows($res_grp_prof)==0) {
										$sql="INSERT INTO j_groupes_professeurs SET id_groupe='$id_groupe', login='$login_prof';";
										if($insert=mysql_query($sql)) {
											echo "<span style='color:green;'>";
										}
										else {
											echo "<span style='color:red;'>";
										}
										echo "$login_prof</span>";
									}
			
									// Associer le prof � la mati�re: j_professeurs_matieres
									$sql="SELECT 1=1 FROM j_professeurs_matieres WHERE id_matiere='$mat' AND id_professeur='$login_prof';";
									$res_prof_mat=mysql_query($sql);
									echo " (";
									if(mysql_num_rows($res_prof_mat)==0) {
										$sql="INSERT INTO j_professeurs_matieres SET id_matiere='$mat', id_professeur='$login_prof';";
										if($insert=mysql_query($sql)) {
											echo "<span style='color:green;'>";
										}
										else {
											echo "<span style='color:red;'>";
										}
									}
									else {
										echo "<span style='color:black;'>";
									}
									echo "$mat</span>)";
			
								}
								//else {echo "prof inconnu";}
							}
						}
						echo "<br />\n";
		

						// Mettre tous les �l�ves dans le groupe pour toutes les p�riodes: j_eleves_groupes
						echo "Association des �l�ves:<br />";
						echo "<blockquote>\n";

						$sql="SELECT login FROM eleves e, temp_grp t WHERE e.ele_id=t.ELE_ID AND t.NOM_GRP='".addslashes($code_groupe)."';";
						$get_login_ele=mysql_query($sql);
						if(mysql_num_rows($get_login_ele)==0) {
							// On va mettre tous les �l�ves dans le groupe
							$tab_ele_courant=$tab_ele;
						}
						else {
							$tab_ele_courant=array();
							while($lig_ele=mysql_fetch_object($get_login_ele)) {
								// Normalement on n'a qu'un enregistrement par ele/grp, mais au cas o�
								if(!in_array($lig_ele->login,$tab_ele_courant)) {
									$tab_ele_courant[]=$lig_ele->login;
								}
							}
						}
	
						for($k=0;$k<count($tab_ele_courant);$k++) {
							if($k>0) {echo " - ";}
							echo $tab_ele_courant[$k]." (";
							//for($l=0;$l<count($tab_per);$l++) {
							for($l=1;$l<=$max_per;$l++) {
								if($l>0) {echo "-";}
								//$sql="SELECT 1=1 FROM j_eleves_groupes WHERE id_groupe='$id_groupe' AND login='".$tab_ele_courant[$k]."' AND periode='".$tab_per[$l]."';";
								$sql="SELECT 1=1 FROM j_eleves_groupes WHERE id_groupe='$id_groupe' AND login='".$tab_ele_courant[$k]."' AND periode='".$l."';";
								//echo "$sql<br />";
								$res_ele_grp=mysql_query($sql);
								if(mysql_num_rows($res_ele_grp)==0) {
									//$sql="INSERT INTO j_eleves_groupes SET id_groupe='$id_groupe', login='".$tab_ele_courant[$k]."', periode='".$tab_per[$l]."';";
									$sql="INSERT INTO j_eleves_groupes SET id_groupe='$id_groupe', login='".$tab_ele_courant[$k]."', periode='".$l."';";
									if($insert=mysql_query($sql)) {
										echo "<span style='color:green;'>";
									}
									else {
										//echo "$sql<br />\n";
										echo "<span style='color:red;'>";
									}
								}
								else {
									echo "<span style='color:black;'>";
								}
								//echo $tab_per[$l]."</span>";
								echo $l."</span>";
							}
							echo ")";
						}
						echo "</blockquote>\n";
		
						echo "</blockquote>\n";
		
		
					}
		
				}
			}
		}
	}



	/*
	if ($nb_reg_no != 0) {
		echo "<p>Lors de l'enregistrement des donn�es il n'y a eu $nb_reg_no erreurs. Essayez de trouvez la cause de l'erreur et recommencez la proc�dure avant de passer � l'�tape suivante.";
	} else {
	*/

	if($temoin_div_sans_services==0) {
		echo "<p>L'importation des relations professeurs/mati�res et professeurs/classes dans la base GEPI a �t� effectu�e avec succ�s !<br />Vous pouvez proc�der � l'�tape suivante d'importation des options suivies par les �l�ves.</p>";
	}
	elseif($temoin_div_sans_services==1) {
		echo "<p style='color:red;'>$temoin_div_sans_services division n'a pas de services d�clar�s.<br />Le fichier STS fourni n'est peut-�tre pas complet.<br />Cela arrive notamment quand l'emploi du temps n'a pas �t� remont� vers STS.<br />Vous devriez contr�ler cela avant de proc�der � l'�tape d'importation des options suivies par les �l�ves.</p>\n";
	}
	else {
		echo "<p style='color:red;'>$temoin_div_sans_services divisions n'ont pas de services d�clar�s.<br />Le fichier STS fourni n'est peut-�tre pas complet.<br />Cela arrive notamment quand l'emploi du temps n'a pas �t� remont� vers STS.<br />Vous devriez contr�ler cela avant de proc�der � l'�tape d'importation des options suivies par les �l�ves.</p>\n";
	}

	if($temoin_service_sans_enseignant==1) {
		echo "<p style='color:red;'>$temoin_service_sans_enseignant enseignement (<i>service</i>) a �t� d�clar� sans enseignant associ�.<br />Les �l�ves pratiquent-ils l'auto-formation en autonomie ou le STS est-il mal renseign�?</p>\n";
	}
	elseif($temoin_service_sans_enseignant>1) {
		echo "<p style='color:red;'>$temoin_service_sans_enseignant enseignements (<i>services</i>) ont �t� d�clar�s sans enseignant associ�.<br />Les �l�ves pratiquent-ils l'auto-formation en autonomie ou le STS est-il mal renseign�?</p>\n";
	}

	if($nb_groupes_sans_matieres!=0) {
		echo "<p style='color:red;'>$nb_groupes_sans_matieres enseignements ont �t� d�clar�s sans mati�re associ�e.<br />Vous devrez corriger la mati�re associ�e ainsi que le professeur associ�.</p>\n";
	}

	//}
	echo "<p align='center'><a href='init_options.php?a=a".add_token_in_url()."'>Importer les options suivies par les �l�ves</a></p>\n";
	echo "<p><br /></p>\n";



	require("../lib/footer.inc.php");
	die();
	//=================================================================================================
}
else {

	$fp = fopen("../temp/$tempdir/f_men.csv","r");
	$fp2 = fopen("../temp/$tempdir/f_gpd.csv","r");

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
		$cpt_tmp=0;
		for ($k = 0; $k < count($tabchamps2); $k++) {
			for ($i = 0; $i < count($en_tete); $i++) {
				//if ($en_tete[$i] == $tabchamps2[$k]) {
				if (trim($en_tete[$i]) == $tabchamps2[$k]) {
					//$tabindice2[] = $i;
					//affiche_debug("\$tabindice2[]=$i<br />\n");
					$tabindice2[$cpt_tmp] = $i;
					affiche_debug("\$tabindice2[$cpt_tmp]=$i<br />\n");
					$cpt_tmp++;
				}
			}
		}
		affiche_debug("==========================<br />\n");
		//=========================
		//$fp2=fopen($dbf_file2['tmp_name'],"r");
		$fp2 = fopen("../temp/$tempdir/f_gpd.csv","r");
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
		/*
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
		*/
		$cpt_tmp=0;
		for ($k = 0; $k < count($tabchamps); $k++) {
			for ($i = 0; $i < count($en_tete2); $i++) {
				if (trim($en_tete2[$i]) == $tabchamps[$k]) {
					$tabindice[$cpt_tmp]=$i;
					affiche_debug("\$tabindice[$cpt_tmp]=$i<br />\n");
					$cpt_tmp++;
				}
			}
		}
		affiche_debug("==========================<br />\n");
		affiche_debug("==========================<br />\n");

		//=========================
		//$fp=fopen($dbf_file['tmp_name'],"r");
		$fp = fopen("../temp/$tempdir/f_men.csv","r");
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
							//echo "Ajout de la correspondance prof/mati�re suivante: $login_prof/$affiche[0]<br />\n";
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

								//echo "<p>\n";

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

											echo "<p>\n";
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
										//echo "\$nb_per=$nb_per<br />";

										// DEBUG :: echo "<br/>Classe : " . $id_classe . "<br/>Nb el. : " . $nb_eleves . "<br/>Nb per.: " . $nb_per . "<br/><br/>";
										if($nb_eleves>0){
											echo "Ajout � ce groupe des �l�ves suivants: ";
											for ($m=0;$m<$nb_eleves;$m++) {
												$e_login = mysql_result($get_eleves, $m, "login");
												for ($n=1;$n<=$nb_per;$n++) {
													$insert_e = mysql_query("INSERT into j_eleves_groupes SET id_groupe = '" . $group_id . "', login = '" . $e_login . "', periode = '" . $n . "'");
													//affiche_debug("INSERT into j_eleves_groupes SET id_groupe = '" . $group_id . "', login = '" . $e_login . "', periode = '" . $n . "'<br />\n");
													affiche_debug("<br />\nINSERT into j_eleves_groupes SET id_groupe = '" . $group_id . "', login = '" . $e_login . "', periode = '" . $n . "'\n");
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
										//echo "Ajout de $login_prof � un groupe existant.<br />\n";
									}
								}
								//echo "</p>\n";
							}
						}
					}
				}
				affiche_debug("===================================================<br />\n");
			}
		}
		//dbase_close($fp);
		fclose($fp);


		/*
		if ($nb_reg_no != 0) {
			echo "<p>Lors de l'enregistrement des donn�es il n'y a eu $nb_reg_no erreurs. Essayez de trouvez la cause de l'erreur et recommencez la proc�dure avant de passer � l'�tape suivante.";
		} else {
			echo "<p>L'importation des relations professeurs/mati�res et professeurs/classes dans la base GEPI a �t� effectu�e avec succ�s !<br />Vous pouvez proc�der � l'�tape suivante d'importation des options suivies par les �l�ves.</p>";

		}
		*/

		echo "<p>Contr�lez dans la page si vous n'avez pas d'erreur (<i>signal�e en rouge le cas �ch�ant</i>), puis vous pouvez proc�der � l'�tape suivante d'importation des options suivies par les �l�ves.</p>";

		echo "<center><p><a href='init_options.php?a=a".add_token_in_url()."'>Importer les options suivies par les �l�ves</a></p></center>";
		echo "<p><br /></p>\n";
	}
}
require("../lib/footer.inc.php");
?>