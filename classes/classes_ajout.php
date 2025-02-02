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
extract($_GET, EXTR_OVERWRITE);
extract($_POST, EXTR_OVERWRITE);
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

$msg_complement="";

if(getSettingValue('classes_ajout_debug_var')=='y') {
	debug_var();
}

if(getSettingValue('classes_ajout_sans_regime')=='y') {
	$classes_ajout_sans_regime="y";
}
else {
	$classes_ajout_sans_regime="n";
}

// On ne propose pas de d�finir le r�gime quand suhosin est actif... on a alors trop de variables post�es s'il y a beaucoup d'�l�ves
$suhosin_post_max_totalname_length=ini_get('suhosin.post.max_totalname_length');
if($suhosin_post_max_totalname_length!='') {
	$classes_ajout_sans_regime="y";
}

$sql="SELECT classe FROM classes WHERE id = '$id_classe';";
$call_classe = mysql_query($sql);
if(mysql_num_rows($call_classe)==0) {
	echo "La classe n�$id_classe (id_classe) n'existe pas.<br />\n";
	die();
}
$classe = mysql_result($call_classe, "0", "classe");

include "../lib/periodes.inc.php";

if (isset($is_posted) and ($is_posted == 1)) {
	check_token();

	$gepiProfSuivi=getSettingValue("gepi_prof_suivi");

	$call_eleves = mysql_query("SELECT login, id_eleve FROM eleves ORDER BY nom, prenom;");
	$nombreligne = mysql_num_rows($call_eleves);

	/*
	$_POST['regime_1442']=	d/p
	$_POST['ajout_eleve_1442']=	Array (*)
	$_POST[ajout_eleve_1442]['1']=	yes
	$_POST['regime_1441']=	d/p
	$_POST['doublant_eleve_1441']=	R
	*/

	$reg_data = 'yes';

	$k = '0';
	while ($k < $nombreligne) {
		$pb = 'no';
		$login_eleve = mysql_result($call_eleves, $k, 'login');
		$id_eleve = mysql_result($call_eleves, $k, 'id_eleve');

		if(isset($_POST['ajout_eleve_'.$id_eleve])) {
			$i=1;
			while ($i < $nb_periode) {
				$tab_per_ajout_eleve=$_POST['ajout_eleve_'.$id_eleve];
				if(isset($tab_per_ajout_eleve[$i])) {
					// Contr�ler que l'�l�ve n'est pas d�j� dans une autre classe
					$sql="SELECT id_classe FROM j_eleves_classes WHERE
					(login = '$login_eleve' and
					id_classe!='$id_classe' and
					periode = '$i')";
					$test_clas_per=mysql_query($sql);
					if(mysql_num_rows($test_clas_per)>0) {
						$lig_clas_per=mysql_fetch_object($test_clas_per);
						$msg_complement.=get_nom_prenom_eleve($login_eleve)." est d�j� dans une autre classe&nbsp;: ".get_class_from_id($lig_clas_per->id_classe)."<br />\n";
						$reg_ok = 'no';
					}
					else {
						$sql="SELECT login FROM j_eleves_classes WHERE
						(login = '$login_eleve' and
						id_classe = '$id_classe' and
						periode = '$i')";
						$res_clas_per=mysql_query($sql);
						if (mysql_num_rows($res_clas_per)==0) {
							$sql="INSERT INTO j_eleves_classes VALUES('$login_eleve', '$id_classe', $i, '0');";
							$reg_data = mysql_query($sql);
							if (!($reg_data))  {$reg_ok = 'no';}
						}
		
						// UPDATE: Ajouter l'�l�ve � tous les groupes pour la p�riode:
						$sql="SELECT id_groupe FROM j_groupes_classes WHERE id_classe='$id_classe'";
						$res_liste_grp_classe=mysql_query($sql);
						if(mysql_num_rows($res_liste_grp_classe)>0){
							while($lig_tmp=mysql_fetch_object($res_liste_grp_classe)){
								$sql="SELECT 1=1 FROM j_eleves_groupes WHERE login='$login_eleve' AND id_groupe='$lig_tmp->id_groupe' AND periode='$i'";
								$test=mysql_query($sql);
								if(mysql_num_rows($test)==0){
									$sql="INSERT INTO j_eleves_groupes SET login='$login_eleve',id_groupe='$lig_tmp->id_groupe',periode='$i'";
									$insert_grp=mysql_query($sql);
									if (!($insert_grp))  {$reg_ok = 'no';}
								}
							}
						}
			
						$sql="SELECT DISTINCT cpe_login FROM j_eleves_cpe jecpe, j_eleves_classes jec
									WHERE (
										jec.id_classe='$id_classe' AND
										jecpe.e_login=jec.login AND
										jec.periode='$i'
									)";
						//echo "$sql<br />";
						$res_cpe=mysql_query($sql);
						if(mysql_num_rows($res_cpe)==1) {
							$sql="DELETE FROM j_eleves_cpe WHERE e_login='$login_eleve';";
							//echo "$sql<br />";
							$nettoyage=mysql_query($sql);
			
							$lig_tmp=mysql_fetch_object($res_cpe);
							$sql="INSERT INTO j_eleves_cpe SET cpe_login='$lig_tmp->cpe_login', e_login='$login_eleve';";
							//echo "$sql<br />";
							$insert_cpe=mysql_query($sql);
						}
						else {
							$msg_complement.="<br />L'�l�ve $login_eleve n'a pas �t� associ� � un CPE.";
						}
			
						$sql="SELECT DISTINCT professeur FROM j_eleves_professeurs jep
									WHERE (
										jep.id_classe='$id_classe'
									)";
						//echo "$sql<br />";
						$res_pp=mysql_query($sql);
						if(mysql_num_rows($res_pp)==1) {
							$sql="DELETE FROM j_eleves_professeurs WHERE login='$login_eleve';";
							//echo "$sql<br />";
							$nettoyage=mysql_query($sql);
			
							$lig_tmp=mysql_fetch_object($res_pp);
							$sql="INSERT INTO j_eleves_professeurs SET professeur='$lig_tmp->professeur', login='$login_eleve', id_classe='$id_classe';";
							//echo "$sql<br />";
							$insert_pp=mysql_query($sql);
						}
						else {
							$msg_complement.="<br />L'�l�ve $login_eleve n'a pas �t� associ� � un ".$gepiProfSuivi.".";
						}
					}
				}
				$i++;
			}
		}
		
		if(isset($_POST['regime_'.$id_eleve])) {
			$sql="SELECT * FROM j_eleves_regime WHERE login='$login_eleve';";
			$call_regime = mysql_query($sql);
			$nb_test_regime = mysql_num_rows($call_regime);
			if ($nb_test_regime == 0) {
				$sql="INSERT INTO j_eleves_regime SET login='$login_eleve', regime='".$_POST['regime_'.$id_eleve]."', doublant='-';";
				$reg_data = mysql_query($sql);
				if (!($reg_data)) $reg_ok = 'no';
			} else {
				$sql="UPDATE j_eleves_regime SET regime='".$_POST['regime_'.$id_eleve]."' WHERE login='$login_eleve';";
				$reg_data = mysql_query($sql);
				if (!($reg_data)) $reg_ok = 'no';
			}
		}
	
		if(isset($_POST['doublant_eleve_'.$id_eleve])) {
			$sql="SELECT * FROM j_eleves_regime WHERE login='$login_eleve';";
			$call_regime = mysql_query($sql);
			$nb_test_regime = mysql_num_rows($call_regime);
			if ($nb_test_regime == 0) {
				$sql="INSERT INTO j_eleves_regime SET login='$login_eleve', doublant='".$_POST['doublant_eleve_'.$id_eleve]."', regime='d/p';";
				$reg_data = mysql_query($sql);
				if (!($reg_data)) $reg_ok = 'no';
			} else {
				$sql="UPDATE j_eleves_regime SET doublant='".$_POST['doublant_eleve_'.$id_eleve]."' WHERE login='$login_eleve';";
				$reg_data = mysql_query($sql);
				if (!($reg_data)) $reg_ok = 'no';
			}
		}

		$k++;
	}

	if (($reg_data) == 'yes') {
	$msg = "L'enregistrement des donn�es a �t� correctement effectu� !";
	} else {
	$msg = "Il y a eu un probl�me lors de l'enregistrement !";
	}
	$msg.=$msg_complement;
}

// AJOUT: boireaus
$chaine_options_classes="";
$sql="SELECT id, classe FROM classes ORDER BY classe";
$res_class_tmp=mysql_query($sql);
if(mysql_num_rows($res_class_tmp)>0){
	$id_class_prec=0;
	$id_class_suiv=0;
	$temoin_tmp=0;

	$cpt_classe=0;
	$num_classe=-1;

	while($lig_class_tmp=mysql_fetch_object($res_class_tmp)){
		if($lig_class_tmp->id==$id_classe){
			// Index de la classe dans les <option>
			$num_classe=$cpt_classe;

			$chaine_options_classes.="<option value='$lig_class_tmp->id' selected='true'>$lig_class_tmp->classe</option>\n";
			$temoin_tmp=1;
			if($lig_class_tmp=mysql_fetch_object($res_class_tmp)){
				$chaine_options_classes.="<option value='$lig_class_tmp->id'>$lig_class_tmp->classe</option>\n";
				$id_class_suiv=$lig_class_tmp->id;
			}
			else{
				$id_class_suiv=0;
			}
		}
		else {
			$chaine_options_classes.="<option value='$lig_class_tmp->id'>$lig_class_tmp->classe</option>\n";
		}

		if($temoin_tmp==0){
			$id_class_prec=$lig_class_tmp->id;
		}

		$cpt_classe++;
	}
}
// =================================


$themessage  = 'Des informations ont �t� modifi�es. Voulez-vous vraiment quitter sans enregistrer ?';
//**************** EN-TETE **************************************
$titre_page = "Gestion des classes | Ajout d'�l�ves � une classe";
require_once("../lib/header.inc");
//**************** FIN EN-TETE **********************************

//debug_var();

echo "<script type='text/javascript' language='javascript'>
function CocheLigne(ki) {
	for (var i=1;i<$nb_periode;i++) {
		if(document.getElementById('case_'+ki+'_'+i)){
			document.getElementById('case_'+ki+'_'+i).checked = true;
		}
	}
}

function DecocheLigne(ki) {
	for (var i=1;i<$nb_periode;i++) {
		if(document.getElementById('case_'+ki+'_'+i)){
			document.getElementById('case_'+ki+'_'+i).checked = false;
		}
	}
}
</script>\n";
?>

<form enctype="multipart/form-data" action="classes_ajout.php" name="form1" method=post>

<p class="bold">
<a href="classes_const.php?id_classe=<?php echo $id_classe;?>"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour � la page de gestion des �l�ves</a>

<?php
if($id_class_prec!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_prec'>Classe pr�c�dente</a>";}
if($chaine_options_classes!="") {

	echo "<script type='text/javascript'>
	// Initialisation
	change='no';

	function confirm_changement_classe(thechange, themessage)
	{
		if (!(thechange)) thechange='no';
		if (thechange != 'yes') {
			document.form1.submit();
		}
		else{
			var is_confirmed = confirm(themessage);
			if(is_confirmed){
				document.form1.submit();
			}
			else{
				document.getElementById('id_classe').selectedIndex=$num_classe;
			}
		}
	}
</script>\n";


	echo " | <select name='id_classe' id='id_classe' onchange=\"confirm_changement_classe(change, '$themessage');\">\n";
	echo $chaine_options_classes;
	echo "</select>\n";
}
if($id_class_suiv!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_suiv'>Classe suivante</a>";}
?>
</p>
</form>

<form enctype="multipart/form-data" action="classes_ajout.php" name="formulaire" method=post>
<p><b>Ajout d'�l�ves � la classe de <?php echo $classe; ?></b><br />Liste des �l�ves non affect�s � une classe :</p>

<?php

echo add_token_field();

$call_eleves = mysql_query("SELECT * FROM eleves ORDER BY nom, prenom");
$nombreligne = mysql_num_rows($call_eleves);
if ($nombreligne == '0') {
	echo "<p>Il n'y a pas d'�l�ves actuellement dans la base.</p>\n";
} else {
	$eleves_non_affectes = 'no';
	echo "<table class='boireaus' cellpadding='5'>\n";
	echo "<tr>\n";
	echo "<th><p><b>Nom Pr�nom </b></p></th>\n";
	if($classes_ajout_sans_regime!="y") {
		echo "<th><p><b>R�gime</b></p></th>\n";
	}
	echo "<th><p><b>Redoublant</b></p></th>\n";
	$i="1";
	while ($i < $nb_periode) {
		echo "<th><p><b>Ajouter per. $i</b></p></th>\n";
		$i++;
	}

	echo "<th><p style='font-weight:bold; text-align:center;'>cocher / d�cocher <br />toutes p�riodes</p></th>\n";
	echo "</tr>";
	$k = '0';
	//=========================
	// AJOUT: boireaus 20071010
	// Compteur des �l�ves effectivement non affect�s:
	//$ki=0;
	//=========================
	$alt=1;
	While ($k < $nombreligne) {
		$id_eleve = mysql_result($call_eleves, $k, 'id_eleve');
		$login_eleve = mysql_result($call_eleves, $k, 'login');
		$nom_eleve = mysql_result($call_eleves, $k, 'nom');
		$prenom_eleve = mysql_result($call_eleves, $k, 'prenom');
		$call_regime = mysql_query("SELECT * FROM j_eleves_regime WHERE login='$login_eleve'");
		$doublant = @mysql_result($call_regime, 0, 'doublant');
		if ($doublant == '') {$doublant = '-';}
		$regime = @mysql_result($call_regime, 0, 'regime');
		if ($regime == '') {$regime = 'd/p';}
		$i="1";
		while ($i < $nb_periode) {
			$ajout_login[$i] = "ajout_".$login_eleve."_".$i;
			$i++;
		}

		$inserer_ligne = 'no';
		$call_data = mysql_query("SELECT id_classe FROM j_eleves_classes WHERE login = '$login_eleve'");
		$test = mysql_num_rows($call_data);
		if ($test == 0) {
			$inserer_ligne = 'yes';
			$eleves_non_affectes = 'yes';
			$i="1";
			while ($i < $nb_periode) {
				$nom_classe[$i] = 'vide';
				$i++;
			}
		} else {
			$id_classe_eleve = mysql_result($call_data, 0, "id_classe");
			$query_periode_max = mysql_query("SELECT * FROM periodes WHERE id_classe = '$id_classe_eleve'");
			$periode_max = mysql_num_rows($query_periode_max) + 1 ;
			// si l'�l�ve est d�j� dans une classe dont le nombre de p�riodes est diff�rent du nombre de p�riodes de la classe selctionn�e, on ne fait rien. Dans la cas contraire :
			if ($periode_max == $nb_periode) {
				$i = '1';
				while ($i < $nb_periode) {
					$call_data2 = mysql_query("SELECT id_classe FROM j_eleves_classes WHERE (login = '$login_eleve' and periode = '$i')");
					$test2 = mysql_num_rows($call_data2);
					if ($test2 == 0) {
						// l'�l�ve n'est affect� � aucune classe pour cette p�riode
						$inserer_ligne = 'yes';
						$eleves_non_affectes = 'yes';
						$nom_classe[$i] = 'vide';
					} else {
						$idd_classe = mysql_result($call_data2, 0, "id_classe");
						$call_classe = mysql_query("SELECT classe FROM classes WHERE (id = '$idd_classe')");
						$nom_classe[$i] = mysql_result($call_classe, 0, "classe");
					}
					$i++;
				}
			}
		}
		if ($inserer_ligne == 'yes') {
			$alt=$alt*(-1);
			echo "<tr class='lig$alt'><td>\n";

			//echo "<input type='hidden' name='log_eleve[$ki]' value=\"$login_eleve\" />\n";
			echo "<p>".strtoupper($nom_eleve)." $prenom_eleve</p></td>\n";

			if($classes_ajout_sans_regime!="y") {
				echo "<td><p>Ext.|Int.|D/P|I-ext.<br /><input type='radio' name='regime_$id_eleve' value='ext.'";
				if ($regime == 'ext.') { echo " checked ";}
				echo " onchange='changement()' />\n";
				echo "&nbsp;&nbsp;&nbsp;<input type=radio name='regime_$id_eleve' value='int.'";
				if ($regime == 'int.') { echo " checked ";}
				echo " onchange='changement()' />\n";
				echo "&nbsp;&nbsp;&nbsp;<input type=radio name='regime_$id_eleve' value='d/p' ";
				if ($regime == 'd/p') { echo " checked ";}
				echo " onchange='changement()' />\n";
				echo "&nbsp;&nbsp;&nbsp;<input type=radio name='regime_$id_eleve' value='i-e'";
				if ($regime == 'i-e') { echo " checked ";}
				echo " onchange='changement()' />\n";
				echo "</p></td>\n";
			}
			//echo "<td><p align='center'><input type='checkbox' name='doublant_eleve[$ki]' value='R'";
			echo "<td><p align='center'><input type='checkbox' name='doublant_eleve_$id_eleve' value='R'";
			//=========================
			if ($doublant == 'R') { echo " checked ";}
			echo " onchange='changement()' />";

			echo "</p></td>\n";

			$i="1";
			while ($i < $nb_periode) {
				echo "<td><p align='center'>";
				if ($nom_classe[$i] == 'vide') {
					//echo "<input type='checkbox' name='ajout_eleve_".$ki."[$i]' id='case_".$ki."_".$i."' value='yes' onchange='changement()' />";
					echo "<input type='checkbox' name='ajout_eleve_".$id_eleve."[$i]' id='case_".$id_eleve."_".$i."' value='yes' onchange='changement()' />";
				} else {
					echo "$nom_classe[$i]";
				}
				echo "</p></td>\n";
				$i++;
			}
			$elementlist = null;
			for ($i=1;$i<=sizeof($ajout_login);$i++) {
				//echo $ajout_login[$i]."<br>";
				$elementlist .= "'".$ajout_login[$i]."',";
			}
			$elementlist = substr($elementlist, 0, -1);
			echo "<td><center><a href=\"javascript:CocheLigne($id_eleve);changement();\"><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a> / <a href=\"javascript:DecocheLigne($id_eleve);changement();\"><img src='../images/disabled.png' width='15' height='15' alt='Tout d�cocher' /></a></center></td>\n";
			echo "</tr>\n";

			//$ki++;
		}
		$k++;
	}
	echo "</table>\n";

	if ($eleves_non_affectes == 'no') {
		echo "<p>Il n'y a aucun �l�ve de disponible � ajouter !";
	} else {
		echo "<p align='center'><input type='submit' value='Enregistrer' /></p>\n";
	}
}
?>
<input type='hidden' name='id_classe' value='<?php echo $id_classe;?>' />
<input type='hidden' name='is_posted' value='1' />
</form>
<p><br /></p>
<?php require("../lib/footer.inc.php");?>
