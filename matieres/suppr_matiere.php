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

//INSERT INTO `droits` VALUES ('/matieres/suppr_matiere.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'F', 'Suppression d une matiere', '');
if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}

$matiere=isset($_POST['matiere']) ? $_POST['matiere'] : (isset($_GET['matiere']) ? $_GET['matiere'] : NULL);
$confirmation_suppr=isset($_POST['confirmation_suppr']) ? $_POST['confirmation_suppr'] : (isset($_GET['confirmation_suppr']) ? $_GET['confirmation_suppr'] : NULL);

//**************** EN-TETE *****************
$titre_page = "Suppression d'une mati�re";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

echo "<p class=bold><a href='../accueil.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour accueil</a> | <a href='index.php'>Retour � la gestion des mati�res</a></p>\n";

echo "<h2>Suppression d'une mati�re</h2>\n";

if(!isset($matiere)) {
	echo "<p>Aucune mati�re n'a �t� choisie.</p>\n";

	echo "<p><br /></p>\n";
	require("../lib/footer.inc.php");
}

$sql="SELECT * FROM matieres WHERE matiere='$matiere';";
$res_mat=mysql_query($sql);
if(mysql_num_rows($res_mat)==0) {
	echo "<p>La mati�re '$matiere' n'existe pas dans la table 'matieres'.</p>\n";

	echo "<p><br /></p>\n";
	require("../lib/footer.inc.php");
}

if(!isset($confirmation_suppr)) {
	echo "<p>Vous souhaitez supprimer la mati�re '$matiere'.<br />\n";

	$sql="SELECT id_groupe FROM j_groupes_matieres WHERE id_matiere='$matiere';";
	$res_grp=mysql_query($sql);

	$nb_grp=mysql_num_rows($res_grp);
	if($nb_grp==0) {
		echo "Elle n'est associ�e � aucun groupe.</p>\n";
	}
	elseif($nb_grp==1) {
		echo "Elle est associ�e � un groupe.<br />\n";
	}
	else {
		echo "Elle est associ�e � $nb_grp groupes.<br />\n";
	}

	if($nb_grp>0) {
		$nb_notes_app=0;
		while($lig_grp=mysql_fetch_object($res_grp)) {
			// Rechercher les groupes associ�s � des notes...
			if(test_before_group_deletion($lig_grp->id_groupe)) {
				$nb_notes_app++;
			}
		}
		if ($nb_notes_app==0) {
			echo "Le ou les groupe(s) ne sont associ�(s) � aucune note/appr�ciation sur un bulletin.</p>\n";
		}
		elseif ($nb_notes_app==1) {
			echo "Le groupe ou l'un des groupes est associ� � aucune note/appr�ciation sur un bulletin.<br />Vous ne devriez pas supprimer la mati�re.</p>\n";
		}
		else {
			echo "Le groupe ou les groupes sont associ�(s) � des notes/appr�ciations sur des bulletins.<br />Vous ne devriez pas supprimer la mati�re.</p>\n";
		}
	}


	// Formulaire de confirmation de suppression
	echo "<form action='".$_SERVER['PHP_SELF']."' name='form1' method='post'>\n";
	echo add_token_field();
	echo "<input type='hidden' name='matiere' value=\"$matiere\" />\n";
	echo "<p><input type='submit' name='confirmation_suppr' value='Supprimer la mati�re' /></p>\n";
	echo "</form>\n";


}
else {
	check_token();

	// Suppression proprement dite... avec une boucle sur les groupes pour ne pas risquer un timeout
	// Et finir par la suppression de la mati�re

	/*
	$sql="CREATE TABLE IF NOT EXISTS temp_suppr_matiere (
	id int(11) NOT NULL auto_increment,
	col1 VARCHAR(255) NOT NULL,
	col2 TEXT,
	PRIMARY KEY  (id)
	);";
	$create_table=mysql_query($sql);

	$sql="TRUNCATE temp_suppr_matiere;";
	$nettoyage=mysql_query($sql);
	*/

	$sql="SELECT id_groupe FROM j_groupes_matieres WHERE id_matiere='$matiere' LIMIT 1;";
	$res_grp=mysql_query($sql);

	$nb_grp=mysql_num_rows($res_grp);
	if($nb_grp==0) {
		echo "<p>Tous les groupes (*) associ�s � la mati�re $matiere sont supprim�s.<br />(*) et enregistrements associ�s.</p>\n";

		echo "<p>\n";

		// Il reste � nettoyer:
		// - j_professeurs_matieres

		$sql="SELECT * FROM j_professeurs_matieres WHERE id_matiere='$matiere';";
		$res_jpm=mysql_query($sql);
		$nb_jpm=mysql_num_rows($res_jpm);
		if($nb_jpm>0) {
			echo "Suppression de $nb_jpm association(s) professeur/mati�re: ";
			$sql="DELETE FROM j_professeurs_matieres WHERE id_matiere='$matiere';";
			$res_jpm=mysql_query($sql);
			if($res_jpm) {
				echo "<span style='color:green;'>OK</span><br />\n";
			}
			else {
				echo "<span style='color:red;'>Erreur</span><br />\n";
			}
		}

		// - aid
		$sql="SELECT * FROM aid WHERE matiere1='$matiere';";
		$res_aid=mysql_query($sql);
		$nb_aid=mysql_num_rows($res_aid);
		if($nb_aid>0) {
			echo "Suppression de $nb_aid association(s) aid/matiere1: ";
			$sql="UPDATE aid SET matiere1='' WHERE matiere1='$matiere';";
			$res_aid=mysql_query($sql);
			if($res_aid) {
				echo "<span style='color:green;'>OK</span><br />\n";
			}
			else {
				echo "<span style='color:red;'>Erreur</span><br />\n";
			}
		}

		$sql="SELECT * FROM aid WHERE matiere2='$matiere';";
		$res_aid=mysql_query($sql);
		$nb_aid=mysql_num_rows($res_aid);
		if($nb_aid>0) {
			echo "Suppression de $nb_aid association(s) aid/matiere2: ";
			$sql="UPDATE aid SET matiere2='' WHERE matiere2='$matiere';";
			$res_aid=mysql_query($sql);
			if($res_aid) {
				echo "<span style='color:green;'>OK</span><br />\n";
			}
			else {
				echo "<span style='color:red;'>Erreur</span><br />\n";
			}
		}

		// - observatoire
		$test_existence=mysql_query("SHOW TABLES LIKE 'observatoire';");
		if(mysql_num_rows($test_existence)>0){
			$sql="SELECT * FROM observatoire WHERE matiere='$matiere';";
			$res_obs=mysql_query($sql);
			$nb_obs=mysql_num_rows($res_obs);
			if($nb_obs>0) {
				echo "Suppression de $nb_obs association(s) observatoire/matiere: ";
				$sql="DELETE FROM observatoire WHERE matiere='$matiere';";
				$res_obs=mysql_query($sql);
				if($res_obs) {
					echo "<span style='color:green;'>OK</span><br />\n";
				}
				else {
					echo "<span style='color:red;'>Erreur</span><br />\n";
				}
			}
		}

		// - observatoire_comment
		$test_existence=mysql_query("SHOW TABLES LIKE 'observatoire_comment';");
		if(mysql_num_rows($test_existence)>0){
			$sql="SELECT * FROM observatoire_comment WHERE matiere='$matiere';";
			$res_obs=mysql_query($sql);
			$nb_obs=mysql_num_rows($res_obs);
			if($nb_obs>0) {
				echo "Suppression de $nb_obs association(s) observatoire/matiere: ";
				$sql="DELETE FROM observatoire_comment WHERE matiere='$matiere';";
				$res_obs=mysql_query($sql);
				if($res_obs) {
					echo "<span style='color:green;'>OK</span><br />\n";
				}
				else {
					echo "<span style='color:red;'>Erreur</span><br />\n";
				}
			}
		}

		// - matieres
		echo "Suppression de la mati�re $matiere de la table 'matieres': ";
		$sql="DELETE FROM matieres WHERE matiere='$matiere';";
		$res_obs=mysql_query($sql);
		if($res_obs) {
			echo "<span style='color:green;'>OK</span><br />\n";
		}
		else {
			echo "<span style='color:red;'>Erreur</span><br />\n";
		}

		echo "</p>\n";

		echo "<p>Fin de la suppression.</p>\n";

	}
	else {
		$lig_grp=mysql_fetch_object($res_grp);
		$current_group=get_group($lig_grp->id_groupe);

		echo "<p>Suppression du groupe n�".$current_group['id']." associ� � la mati�re '$matiere': \n";

		if(delete_group($current_group['id'])==true) {
			echo "<span style='color:green;'>OK</span></p>\n";

			echo "<form action=\"".$_SERVER['PHP_SELF']."#suite\" name='suite' method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"matiere\" value=\"$matiere\" />\n";
			echo "<input type=\"hidden\" name=\"confirmation_suppr\" value=\"y\" />\n";

			echo "<script type='text/javascript'>
	setTimeout(\"document.forms['suite'].submit();\", 1000);
</script>\n";

			echo "<noscript>\n";
			echo "<div id='fixe'><input type=\"submit\" name=\"ok\" value=\"Suite du nettoyage\" /></div>\n";
			echo "</noscript>\n";

			echo "</form>\n";
		}
		else {
			echo "<span style='color:red;'>Erreur</span><br />Il faudra peut-�tre effectuer un Nettoyage des tables/V�rification des groupes.</p>\n";

			echo "<form action=\"".$_SERVER['PHP_SELF']."#suite\" name='suite' method=\"post\">\n";
			echo "<input type=\"hidden\" name=\"matiere\" value=\"$matiere\" />\n";
			echo "<input type=\"hidden\" name=\"confirmation_suppr\" value=\"y\" />\n";

			echo "<div id='fixe'><input type=\"submit\" name=\"ok\" value=\"Suite du nettoyage\" /></div>\n";

			echo "</form>\n";
		}

	}
}

echo "<p><br /></p>\n";
require("../lib/footer.inc.php");
die();
//==================================================================================


//if(!isset($_GET['verif'])){
if(!isset($verif)) {
	echo "<h2>V�rification des groupes</h2>\n";
	echo "<p>Cette page est destin�e � rep�rer la cause d'�ventuelles erreurs du type:</p>\n";
	echo "<pre style='color:green;'>Warning: mysql_result(): Unable to jump to row 0
on MySQL result index 468 in /var/wwws/gepi/lib/groupes.inc.php on line 143</pre>\n";
	echo "<p>Pour proc�der � la v�rification, cliquez sur ce lien: <a href='".$_SERVER['PHP_SELF']."?verif=oui'>V�rification</a><br />(<i>l'op�ration peut �tre tr�s longue</i>)</p>\n";
}
else{
	$ini=isset($_POST['ini']) ? $_POST['ini'] : NULL;


	echo "<h2>Recherche des inscriptions erron�es d'�l�ves</h2>\n";
	flush();
	$err_no=0;

	// Liste des num�ros de p�riodes
	$sql="SELECT DISTINCT num_periode FROM periodes ORDER BY num_periode;";
	$res_per=mysql_query($sql);
	if(mysql_num_rows($res_per)==0) {
		echo "<p>Aucune p�riode n'est encore d�finie.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}
	else {
		unset($tab_per);
		while($lig=mysql_fetch_object($res_per)) {
			$tab_per[]=$lig->num_periode;
		}
	}

	if(!isset($_POST['c_est_parti'])) {
		$sql="TRUNCATE tempo2;";
		$nettoyage=mysql_query($sql);

		$sql="SELECT DISTINCT login FROM j_eleves_groupes ORDER BY login;";
		$res_ele=mysql_query($sql);

		if(mysql_num_rows($res_ele)==0) {
			echo "<p>Aucun �l�ve n'est encore inscrit dans un groupe.</p>\n";
			require("../lib/footer.inc.php");
			die();
		}

		while($lig=mysql_fetch_object($res_ele)) {
			$sql="INSERT INTO tempo2 SET col1='$lig->login', col2='verif_grp';";
			$res_tempo2=mysql_query($sql);
		}


		$sql="CREATE TABLE IF NOT EXISTS tempo3 (
		id int(11) NOT NULL auto_increment,
		col1 VARCHAR(255) NOT NULL,
		col2 TEXT,
		PRIMARY KEY  (id)
		);";
		$create_table=mysql_query($sql);

		$sql="TRUNCATE tempo3;";
		$nettoyage=mysql_query($sql);

		$ini="";
	}

	/*
	// On commence par ne r�cup�rer que les login/periode pour ne pas risquer d'oublier d'�l�ves
	// (il peut y avoir des incoh�rences non d�tect�es si on essaye de r�cup�rer davantage d'infos dans un premier temps)
	$sql="SELECT DISTINCT login,periode FROM j_eleves_groupes ORDER BY login,periode";
	$res_ele=mysql_query($sql);
	*/

	$sql="SELECT * FROM tempo3 WHERE col1='rapport_verif_grp' ORDER BY id;";
	$res_rapport=mysql_query($sql);
	if(mysql_num_rows($res_rapport)>0) {
		while($lig_rapp=mysql_fetch_object($res_rapport)){
			echo $lig_rapp->col2;
		}
	}

	$nb=20;
	$sql="SELECT col1 AS login FROM tempo2 WHERE col2='verif_grp' ORDER BY col1 LIMIT $nb";
	//echo "$sql<br />";
	$res_ele=mysql_query($sql);

	//$ini="A";
	//$ini="";
	//echo "<i>Parcours des login commen�ant par la lettre $ini</i>";

	if(mysql_num_rows($res_ele)>0) {
		$chaine_rapport="";
		while($lig_ele=mysql_fetch_object($res_ele)){
			$temoin_erreur="n";

			if(strtoupper(substr($lig_ele->login,0,1))!=$ini){
				$ini=strtoupper(substr($lig_ele->login,0,1));
				//echo " - <i>$ini</i>";
				echo "<a name='suite'></a>\n";
				$info="<p>\n<i>Parcours des login commen�ant par la lettre $ini</i></p>\n";
				echo $info;
				$chaine_rapport.=$info;
			}

			for($loop=0;$loop<count($tab_per);$loop++) {
				$num_periode=$tab_per[$loop];

				// R�cup�ration de la liste des groupes auxquels l'�l�ve est inscrit sur la p�riode en cours d'analyse:
				$sql="SELECT id_groupe FROM j_eleves_groupes WHERE login='$lig_ele->login' AND periode='$num_periode'";
				//echo "$sql<br />\n";
				affiche_debug($sql,$lig_ele->login);
				$res_jeg=mysql_query($sql);

				//while($lig_jeg=mysql_fetch_object($res_jeg)){
				if(mysql_num_rows($res_jeg)>0){
					// On v�rifie si l'�l�ve est dans une classe pour cette p�riode:
					//$sql="SELECT 1=1 FROM j_eleves_classes WHERE login='$lig_ele->login' AND periode='$num_periode'";
					$sql="SELECT id_classe FROM j_eleves_classes WHERE login='$lig_ele->login' AND periode='$num_periode'";
					affiche_debug($sql,$lig_ele->login);
					$res_jec=mysql_query($sql);

					if(mysql_num_rows($res_jec)==0){
						$temoin_erreur="y";
						// L'�l�ve n'est dans aucune classe sur la p�riode choisie.
						$sql="SELECT c.* FROM classes c, j_eleves_classes jec WHERE jec.login='$lig_ele->login' AND periode='$num_periode' AND jec.id_classe=c.id";
						affiche_debug($sql,$lig_ele->login);
						$res_class_test=mysql_query($sql);

						// Le test ci-dessous est forc�ment vrai si on est arriv� l�!
						if(mysql_num_rows($res_class_test)==0){
							$sql="SELECT DISTINCT c.id,c.classe FROM classes c, j_eleves_classes jec WHERE jec.login='$lig_ele->login' AND jec.id_classe=c.id";
							affiche_debug($sql,$lig_ele->login);
							$res_class=mysql_query($sql);

							$chaine_msg="";
							$chaine_classes="";
							if(mysql_num_rows($res_class)!=0){
								while($lig_class=mysql_fetch_object($res_class)){
									$chaine_classes.=", $lig_class->classe";
									$chaine_msg.=",<br /><a href='../classes/eleve_options.php?login_eleve=".$lig_ele->login."&amp;id_classe=".$lig_class->id."' target='_blank'>Contr�ler en $lig_class->classe</a>\n";
								}
								$chaine_msg=substr($chaine_msg,7);
								$chaine_classes=substr($chaine_classes,2);

								//echo "<br />\n";
								$info="<p>\n";
								$info.="<b>$lig_ele->login</b> de <b>$chaine_classes</b> est inscrit � des groupes pour la p�riode <b>$num_periode</b>, mais n'est pas dans la classe pour cette p�riode.<br />\n";
								echo $info;
								$chaine_rapport.=$info;

								echo $chaine_msg;
								$chaine_rapport.=$chaine_msg;


								// Contr�ler � quelles classes les groupes sont li�s.
								unset($tab_tmp_grp);
								$tab_tmp_grp=array();
								if(isset($tab_tmp_clas)){unset($tab_tmp_clas);}
								$tab_tmp_clas=array();
								while($lig_grp=mysql_fetch_object($res_jeg)){
									$tab_tmp_grp[]=$lig_grp->id_groupe;
									$sql="SELECT DISTINCT c.id,c.classe FROM classes c,j_groupes_classes jgc WHERE jgc.id_classe=c.id AND jgc.id_groupe='$lig_grp->id_groupe'";
									$res_grp2=mysql_query($sql);
									while($lig_tmp_clas=mysql_fetch_object($res_grp2)){
										if(!in_array($lig_tmp_clas->classe,$tab_tmp_clas)){
											$tab_tmp_clas[]=$lig_tmp_clas->classe;
										}
									}
								}

								$info="<br />\n";
								$info.="Les groupes dont <b>$lig_ele->login</b> est membre sont li�s ";
								echo $info;
								$chaine_rapport.=$info;

								if(count($tab_tmp_clas)>1){
									$info="aux classes suivantes: ";
								}
								else{
									$info="� la classe suivante: ";
								}
								echo $info;
								$chaine_rapport.=$info;

								$info=$tab_tmp_clas[0];
								echo $info;
								$chaine_rapport.=$info;

								for($i=1;$i<count($tab_tmp_clas);$i++){
									$info=", ".$tab_tmp_clas[$i];
									echo $info;
									$chaine_rapport.=$info;
								}
								$info="<br />\n";
								$info.="Si <b>$lig_ele->login</b> n'est pas dans une de ces classes, il faudrait l'affecter dans la classe sur une p�riode au moins pour pouvoir supprimer son appartenance � ces groupes, ou proc�der � un nettoyage des tables de la base GEPI.";
								$info.="</p>\n";
								echo $info;
								$chaine_rapport.=$info;
							}
							else{
								$info="<p>\n";
								$info.="<b>$lig_ele->login</b> est inscrit � des groupes pour la p�riode <b>$num_periode</b>, mais n'est dans aucune classe.<br />\n";
								// ... dans aucune classe sur aucune p�riode.
								$info.="Il va falloir l'affecter dans une classe pour pouvoir supprimer ses inscriptions � des groupes.<br />\n";
								$info.="</p>\n";
								echo $info;
								$chaine_rapport.=$info;
							}
						}
						$err_no++;


						// Est-ce qu'en plus l'�l�ve aurait des notes ou moyennes saisies sur la p�riode?
						//$sql="SELECT * FROM matieres_notes WHERE id_groupe='$tab_tmp_grp[$i]' AND periode='$num_periode' AND login='$lig_ele->login'"
						$sql="SELECT * FROM matieres_notes WHERE periode='$num_periode' AND login='$lig_ele->login'";
						$res_mat_not=mysql_query($sql);
						if(mysql_num_rows($res_mat_not)>0){
							$info="<b>$lig_ele->login</b> a de plus des moyennes saisies pour le bulletin sur la p�riode <b>$num_periode</b>";
							echo $info;
							$chaine_rapport.=$info;
							/*
							echo " en "
							$lig_tmp=mysql_fetch_object($res_mat_not);
							$sql="SELECT description FROM groupes WHERE id='$lig_tmp->id_groupe'"
							*/
						}

					}
					else{
						if(mysql_num_rows($res_jec)==1){
							$lig_clas=mysql_fetch_object($res_jec);
							//$lig_grp=mysql_fetch_object($res_jeg);
							while($lig_grp=mysql_fetch_object($res_jeg)){
								// On cherche si l'association groupe/classe existe:
								$sql="SELECT 1=1 FROM j_groupes_classes WHERE id_groupe='$lig_grp->id_groupe' AND id_classe='$lig_clas->id_classe'";
								affiche_debug($sql,$lig_ele->login);
								$res_test_grp_clas=mysql_query($sql);

								if(mysql_num_rows($res_test_grp_clas)==0){
									$temoin_erreur="y";
									$sql="SELECT classe FROM classes WHERE id='$lig_clas->id_classe'";
									$res_tmp=mysql_query($sql);
									$lig_tmp=mysql_fetch_object($res_tmp);
									$clas_tmp=$lig_tmp->classe;

									$sql="SELECT description FROM groupes WHERE id='$lig_grp->id_groupe'";
									$res_tmp=mysql_query($sql);
									$lig_tmp=mysql_fetch_object($res_tmp);
									$grp_tmp=$lig_tmp->description;

									$info="<p>\n";
									//echo "Il semble que $lig_ele->login de la classe $lig_clas->id_classe soit inscrit dans le groupe $lig_grp->id_groupe alors que ce groupe n'est pas associ� � la classe dans 'j_groupes_classes'.<br />\n";
									$info.="<b>$lig_ele->login</b> est inscrit en p�riode $num_periode dans le groupe <b>$grp_tmp</b> (<i>groupe n�$lig_grp->id_groupe</i>) alors que ce groupe n'est pas associ� � la classe <b>$clas_tmp</b> dans 'j_groupes_classes'.<br />\n";
									echo $info;
									$chaine_rapport.=$info;

									// /groupes/edit_eleves.php?id_groupe=285&id_classe=8
									//$sql="SELECT id_classe FROM j_groupes_classes WHERE id_groupe='$lig_grp->id_groupe';";
									$sql="SELECT jgc.id_classe, c.classe FROM j_groupes_classes jgc, classes c WHERE jgc.id_groupe='$lig_grp->id_groupe' AND jgc.id_classe=c.id;";
									$res_tmp_clas=mysql_query($sql);
									if(mysql_num_rows($res_tmp_clas)>0){
										//$lig_tmp_clas=mysql_fetch_object($res_tmp_clas);
										//echo "Vous pouvez tenter de d�cocher l'�l�ve de <b>$clas_tmp</b> du groupe <b>$grp_tmp</b> dans cette <a href='../groupes/edit_eleves.php?id_groupe=".$lig_grp->id_groupe."&id_classe=".$lig_tmp_clas->id_classe."' target='_blank'>page</a> si il s'y trouve.<br />\n";
										$info="Vous pouvez tenter de d�cocher l'�l�ve de <b>$clas_tmp</b> du groupe <b>$grp_tmp</b> dans l'une des pages suivantes ";
										echo $info;
										$chaine_rapport.=$info;

										$tab_tmp_class=array();
										$tab_tmp_classe=array();
										while($lig_tmp_clas=mysql_fetch_object($res_tmp_clas)){
											$tab_tmp_class[]=$lig_tmp_clas->id_classe;
											$tab_tmp_classe[]=$lig_tmp_clas->classe;
											$info="<a href='../groupes/edit_eleves.php?id_groupe=".$lig_grp->id_groupe."&amp;id_classe=".$lig_tmp_clas->id_classe."' target='_blank'>$lig_tmp_clas->classe</a>, ";
											echo $info;
											$chaine_rapport.=$info;
										}
										$info="si il s'y trouve.<br />\n";
										echo $info;
										$chaine_rapport.=$info;
									}

									$info="Si aucune erreur n'est relev�e non plus dans la(es) classe(s) de ";
									$info.="<a href='../classes/eleve_options.php?login_eleve=".$lig_ele->login."&amp;id_classe=".$lig_clas->id_classe."' target='_blank'>$clas_tmp</a>, \n";
									echo $info;
									$chaine_rapport.=$info;

									for($i=0;$i<count($tab_tmp_class);$i++){
										$info="<a href='../classes/eleve_options.php?login_eleve=".$lig_ele->login."&amp;id_classe=".$tab_tmp_class[$i]."' target='_blank'>".$tab_tmp_classe[$i]."</a>, \n";
										echo $info;
										$chaine_rapport.=$info;
									}
									$info="il faudra effectuer un <a href='clean_tables.php?maj=9'>nettoyage des tables de la base de donn�es GEPI</a> (<i>apr�s une <a href='../gestion/accueil_sauve.php?action=dump' target='blank'>sauvegarde de la base</a></i>).<br />\n";
									$info.="</p>\n";
									echo $info;
									$chaine_rapport.=$info;

									$err_no++;
								}
							}
						}
						else{
							$temoin_erreur="y";
							$info="<p>\n";
							$info.="<b>$lig_ele->login</b> est inscrit dans plusieurs classes sur la p�riode $num_periode:<br />\n";
							echo $info;
							$chaine_rapport.=$info;

							while($lig_clas=mysql_fetch_object($res_jec)){
								$sql="SELECT classe FROM classes WHERE id='$lig_clas->id_classe'";
								$res_tmp=mysql_query($sql);
								$lig_tmp=mysql_fetch_object($res_tmp);
								$clas_tmp=$lig_tmp->classe;
								$info="Classe de <a href='../classes/classes_const.php?id_classe=$lig_clas->id_classe'>$clas_tmp</a> (<i>n�$lig_clas->id_classe</i>)<br />\n";
								echo $info;
								$chaine_rapport.=$info;
							}
							$info="Cela ne devrait pas �tre possible.<br />\n";
							$info.="Faites le m�nage dans les effectifs des classes ci-dessus.\n";
							$info.="</p>\n";
							echo $info;
							$chaine_rapport.=$info;
							$err_no++;
						}
					}
				}
				// Pour envoyer ce qui a �t� �crit vers l'�cran sans attendre la fin de la page...
				flush();
			}

			$sql="UPDATE tempo2 SET col2='$temoin_erreur' WHERE col1='$lig_ele->login';";
			$update=mysql_query($sql);
		}


		// INSERER $chaine_rapport DANS UNE TABLE
		$sql="INSERT INTO tempo3 SET col1='rapport_verif_grp', col2='".addslashes($chaine_rapport)."';";
		$insert=mysql_query($sql);

		echo "<form action=\"".$_SERVER['PHP_SELF']."#suite\" name='suite' method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"verif\" value=\"y\" />\n";
		echo "<input type=\"hidden\" name=\"ini\" value=\"$ini\" />\n";
		echo "<input type=\"hidden\" name=\"c_est_parti\" value=\"y\" />\n";

		echo "<script type='text/javascript'>
	setTimeout(\"document.forms['suite'].submit();\", 2000);
</script>\n";

		echo "<NOSCRIPT>\n";
		echo "<div id='fixe'><input type=\"submit\" name=\"ok\" value=\"Suite de la v�rification\" /></div>\n";
		echo "</NOSCRIPT>\n";


		echo "</form>\n";


	}
	else {

		$sql="SELECT 1=1 FROM tempo2 WHERE col2='y';";
		$test_err=mysql_query($sql);
		$err_no=mysql_num_rows($test_err);

		if($err_no==0){
			echo "<p>Aucune erreur d'affectation dans des groupes/classes n'a �t� d�tect�e.</p>\n";
		}
		else{
			echo "<p>Une ou des erreurs ont �t� relev�es.<br />\n";
			echo "Pour corriger, il faut passer par 'Gestion des bases/Gestion des classes/G�rer les �l�ves' et contr�ler pour quelles p�riodes l'�l�ve est dans la classe.<br />\n";
			echo "Puis, cliquer sur le lien 'Mati�res suivies' pour cet �l�ve et d�cocher l'�l�ve des p�riodes souhait�es appropri�es.<br />\n";
			echo "</p>\n";
			echo "<p>Il se peut �galement qu'un <a href='clean_tables.php?maj=9'>nettoyage de la base (<i>�tape des Groupes</i>)</a> soit n�cessaire.<br />\n";
			echo "Prenez soin de faire une <a href='../gestion/accueil_sauve.php?action=dump' target='blank'>sauvegarde de la base</a> auparavant par pr�caution.<br />\n";
		}

		echo "<hr />\n";

		echo "<h2>Recherche des r�f�rences � des identifiants de groupes inexistants</h2>\n";

		$err_no=0;
		$table=array('j_groupes_classes','j_groupes_matieres','j_groupes_professeurs','j_eleves_groupes');
		$id_grp_suppr=array();

		for($i=0;$i<count($table);$i++){
			$sql="SELECT DISTINCT id_groupe FROM ".$table[$i]." ORDER BY id_groupe";
			$res_grp1=mysql_query($sql);

			if(mysql_num_rows($res_grp1)>0){
				echo "<p>On parcourt la table '".$table[$i]."'.</p>\n";
				while($ligne=mysql_fetch_array($res_grp1)){
					$sql="SELECT 1=1 FROM groupes WHERE id='".$ligne[0]."'";
					$res_test=mysql_query($sql);

					if(mysql_num_rows($res_test)==0){
						echo "<b>Erreur:</b> Le groupe d'identifiant $ligne[0] est utilis� dans $table[$i] alors que le groupe n'existe pas dans la table 'groupes'.<br />\n";
						$id_grp_suppr[]=$ligne[0];
						// FAIRE UNE SAUVEGARDE DE LA BASE AVANT DE DECOMMENTER LES 3 LIGNES CI-DESSOUS:
						/*
						$sql="DELETE FROM $table[$i] WHERE id_groupe='$ligne[0]'";
						echo "$sql<br />";
						$res_suppr=mysql_query($sql);
						*/
						$err_no++;
					}
					flush();
				}
			}
		}
		if($err_no==0){
			echo "<p>Aucune erreur d'identifiant de groupe n'a �t� relev�e dans les tables 'j_groupes_classes', 'j_groupes_matieres', 'j_groupes_professeurs' et 'j_eleves_groupes'.</p>\n";
		}
		else{
			echo "<p>Une ou des erreurs ont �t� relev�es.<br />\n";
			echo "Pour corriger, vous devriez proc�der � un <a href='clean_tables.php?maj=9'>nettoyage de la base (<i>�tape des Groupes</i>)</a>.<br />\n";
			echo "Prenez soin de faire une <a href='../gestion/accueil_sauve.php?action=dump' target='blank'>sauvegarde de la base</a> auparavant par pr�caution.<br />\n";
			echo "</p>\n";
		}
	}
}
echo "<p><br /></p>\n";
require("../lib/footer.inc.php");
?>