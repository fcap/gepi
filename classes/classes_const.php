<?php
/*
* $Id: classes_const.php 1271 2007-12-18 18:16:27Z crob $
*
* Copyright 2001, 2005 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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
$resultat_session = resumeSession();
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
include "../lib/periodes.inc.php";

$_SESSION['chemin_retour'] = $gepiPath."/classes/classes_const.php?id_classe=".$id_classe;


if (isset($is_posted)) {
	$reg_ok = 'yes';
	$call_eleves = mysql_query("SELECT DISTINCT e.* FROM eleves e, j_eleves_classes c WHERE (c.id_classe = '$id_classe' AND e.login = c.login)");
	$nombreligne = mysql_num_rows($call_eleves);

	//=========================
	// AJOUT: boireaus 20071010
	$log_eleve=$_POST['log_eleve'];
	$regime_eleve=isset($_POST['regime_eleve']) ? $_POST['regime_eleve'] : NULL;
	$doublant_eleve=isset($_POST['doublant_eleve']) ? $_POST['doublant_eleve'] : NULL;
	$prof_principal=isset($_POST['prof_principal']) ? $_POST['prof_principal'] : NULL;
	$cpe_resp=isset($_POST['cpe_resp']) ? $_POST['cpe_resp'] : NULL;
	//=========================

	$k = 0;
	While ($k < $nombreligne) {
		$login_eleve = mysql_result($call_eleves, $k, 'login');

		//echo "<p>\$login_eleve=$login_eleve<br />\n";
		//=========================
		// AJOUT: boireaus 20071003
		// R�cup�ration du num�ro de l'�l�ve dans les saisies:
		$num_eleve=-1;
		for($i=0;$i<count($log_eleve);$i++){
			if($login_eleve==$log_eleve[$i]){
				$num_eleve=$i;
				break;
			}
		}
		if($num_eleve!=-1){
			//echo "El�ve n�$num_eleve<br />\n";

			//=========================
			// MODIF: boireaus 20071010
			//$regime_login = 'regime_'.$login_eleve;
			//$reg_regime = isset($_POST[$regime_login])?$_POST[$regime_login]:NULL;
			$reg_regime="";
			if(isset($regime_eleve[$num_eleve])){$reg_regime=$regime_eleve[$num_eleve];}

			//$doublant_login = "doublant_".$login_eleve;
			//$reg_doublant = isset($_POST[$doublant_login])?$_POST[$doublant_login]:NULL;
			$reg_doublant="";
			if(isset($doublant_eleve[$num_eleve])){$reg_doublant=$doublant_eleve[$num_eleve];}
			//========================
			if ($reg_doublant == 'yes') {$reg_doublant = 'R';} else {$reg_doublant = '-';}

			$call_regime = mysql_query("SELECT * FROM j_eleves_regime WHERE login='$login_eleve'");
			$nb_test_regime = mysql_num_rows($call_regime);
			if ($nb_test_regime == 0) {
				$reg_data = mysql_query("INSERT INTO j_eleves_regime SET login='$login_eleve',     doublant='$reg_doublant', regime='$reg_regime'");
				if (!($reg_data)) $reg_ok = 'no';
			} else {
				$reg_data = mysql_query("UPDATE j_eleves_regime SET doublant = '$reg_doublant', regime = '$reg_regime'  WHERE login='$login_eleve'");
				if (!($reg_data)) $reg_ok = 'no';
			}

			//=========================
			// MODIF: boireaus 20071010
			//$prof_login = "prof_".$login_eleve;
			//$reg_prof = isset($_POST[$prof_login])?$_POST[$prof_login]:NULL;
			$reg_prof="(vide)";
			if(isset($prof_principal[$num_eleve])){$reg_prof=$prof_principal[$num_eleve];}
			//echo "\$reg_prof=$reg_prof<br />\n";

			//echo "$login_eleve - \$reg_prof=\$prof_principal[$num_eleve]=".$prof_principal[$num_eleve]."<br />";
			//=========================

			$call_profsuivi_eleve = mysql_query("SELECT professeur FROM j_eleves_professeurs WHERE (login = '$login_eleve' AND id_classe='$id_classe')");
			$eleve_profsuivi = @mysql_result($call_profsuivi_eleve, '0', 'professeur');
			//echo "\$eleve_profsuivi=$eleve_profsuivi<br />\n";
			if (($reg_prof == '(vide)') and ($eleve_profsuivi != '')) {
				$sql="DELETE FROM j_eleves_professeurs WHERE (login='$login_eleve' AND id_classe='$id_classe')";
				//echo "$sql<br />";
				$reg_data = mysql_query($sql);
				if (!($reg_data)){
					$reg_ok = 'no';
					//echo "<span style='color:red;'>PB</span>";
				}
			}
			if  (($reg_prof != '(vide)') and ($eleve_profsuivi != '') and ($reg_prof != $eleve_profsuivi)) {
				$sql="UPDATE j_eleves_professeurs SET professeur ='$reg_prof' WHERE (login='$login_eleve' AND id_classe='$id_classe')";
				//echo "$sql<br />";
				$reg_data = mysql_query($sql);
				if (!($reg_data)){
					$reg_ok = 'no';
					//echo "<span style='color:red;'>PB</span>";
				}
			}
			if  (($reg_prof != '(vide)') and ($eleve_profsuivi == '')) {
				$sql="INSERT INTO j_eleves_professeurs VALUES ('$login_eleve', '$reg_prof', '$id_classe')";
				//echo "$sql<br />";
				$reg_data = mysql_query($sql);
				if (!($reg_data)){
					$reg_ok = 'no';
					//echo "<span style='color:red;'>PB</span>";
				}
			}

			//=========================
			// MODIF: boireaus 20071010
			//$cpe_login = "cpe_".$login_eleve;
			//$reg_cperesp = isset($_POST[$cpe_login])?$_POST[$cpe_login]:NULL;
			//$reg_cperesp="(vide)";
			$reg_cperesp="(vide)";
			if(isset($cpe_resp[$num_eleve])){$reg_cperesp=$cpe_resp[$num_eleve];}
			//echo "\$reg_cperesp=$reg_cperesp<br />\n";
			//=========================

			$call_cperesp_eleve = mysql_query("SELECT cpe_login FROM j_eleves_cpe WHERE e_login = '$login_eleve'");
			$eleve_cperesp = @mysql_result($call_cperesp_eleve, '0', 'cpe_login');
			if (($reg_cperesp == '(vide)') and ($eleve_cperesp != '')) {
				$sql="DELETE FROM j_eleves_cpe WHERE e_login='$login_eleve'";
				//echo "$sql<br />";
				$reg_data = mysql_query($sql);
				if (!($reg_data)){
					$reg_ok = 'no';
					//echo "<span style='color:red;'>PB</span>";
				}
			}
			if  (($reg_cperesp != '(vide)') and ($eleve_cperesp != '') and ($reg_cperesp != $eleve_cperesp)) {
				$sql="UPDATE j_eleves_cpe SET cpe_login ='$reg_cperesp' WHERE e_login='$login_eleve'";
				//echo "$sql<br />";
				$reg_data = mysql_query($sql);
				if (!($reg_data)){
					$reg_ok = 'no';
					//echo "<span style='color:red;'>PB</span>";
				}
			}
			if  (($reg_cperesp != '(vide)') and ($eleve_cperesp == '')) {
				$sql="INSERT INTO j_eleves_cpe VALUES ('$login_eleve', '$reg_cperesp')";
				//echo "$sql<br />";
				$reg_data = mysql_query($sql);
				if (!($reg_data)){
					$reg_ok = 'no';
					//echo "<span style='color:red;'>PB</span>";
				}
			}
		}
		$k++;
	}
	$k = '0';
	$liste_cible = '';
	$liste_cible2 = '';
	$liste_cible3 = '';
	$autorisation_sup = 'yes';
	while ($k < $nombreligne){
		$eleve_login = mysql_result($call_eleves, $k, "login");

		//=========================
		// AJOUT: boireaus 20071003
		// R�cup�ration du num�ro de l'�l�ve dans les saisies:
		$num_eleve=-1;
		for($i=0;$i<count($log_eleve);$i++){
			if($eleve_login==$log_eleve[$i]){
				$num_eleve=$i;
				break;
			}
		}
		if($num_eleve!=-1){
			$delete=isset($_POST['delete_'.$num_eleve]) ? $_POST['delete_'.$num_eleve] : NULL;

			$i="1";
			while ($i < $nb_periode) {

				//=========================
				// MODIF: boireaus 20071010
				//$temp = 'delete_'.$eleve_login."_".$i;
				//$del_eleve[$i] = isset($_POST[$temp])?$_POST[$temp]:NULL;
				$del_eleve[$i]=NULL;
				if(isset($delete[$i])){$del_eleve[$i]=$delete[$i];}
				//=========================


				if ($del_eleve[$i] == 'yes') {
					$test = mysql_query("SELECT * FROM matieres_notes WHERE (login='$eleve_login' and periode = '$i')");
					$nb_test = mysql_num_rows($test);
					$test_app = mysql_query("SELECT * FROM matieres_appreciations WHERE (login='$eleve_login' and periode='$i')");
					$nb_test_app = mysql_num_rows($test_app);
					$test_app_conseil = mysql_query("SELECT * FROM avis_conseil_classe WHERE (login='$eleve_login' and periode='$i' and avis!='')");
					$nb_test_app_conseil = mysql_num_rows($test_app_conseil);

					if (($nb_test != 0) or ($nb_test_app != 0) or ($nb_test_app_conseil != 0)) {
						$autorisation_sup = 'no';
						$msg = "<font color = 'red'>--> Impossible de retirer l'�l�ve $eleve_login de la classe pour la p�riode $i !<br />Celui-ci a des moyennes ou appr�ciations pour cette p�riode. Commencez par supprimer les donn�es de l'�l�ve pour cette p�riode !</font><br />\n";
						$reg_ok = "impossible";
					} else {
						$liste_cible .= $eleve_login.";";
						$liste_cible2 .= $i.";";
						$liste_cible3 .= $id_classe.";";
					}
				}
				$i++;
			}
		}
		$k++;
	}

	if (($liste_cible != '') and ($autorisation_sup != 'no')) {
		header("Location: ../lib/confirm_query.php?liste_cible=$liste_cible&liste_cible2=$liste_cible2&liste_cible3=$liste_cible3&action=retire_eleve");
	}

	if ($reg_ok == 'yes') {
	//$message_enregistrement = "Les modifications ont �t� enregistr�es !";
		if(!isset($msg)){$msg="";}
	$msg.="Les modifications ont �t� enregistr�es !";
	} else if ($reg_ok == "impossible") {
		$message_enregistrement = "Op�ration Impossible (voir message d'avertissement en rouge).";
		$affiche_message = 'yes';
	} else {
	//$message_enregistrement = "Il y a eu un probl�me lors de l'enregistrement";
		$message_enregistrement="Il y a eu un probl�me lors de l'enregistrement";
		$affiche_message = 'yes';
	}
	//$affiche_message = 'yes';
}



// =================================
// AJOUT: boireaus
$sql="SELECT id, classe FROM classes ORDER BY classe";
$res_class_tmp=mysql_query($sql);
if(mysql_num_rows($res_class_tmp)>0){
	$id_class_prec=0;
	$id_class_suiv=0;
	$temoin_tmp=0;
	while($lig_class_tmp=mysql_fetch_object($res_class_tmp)){
		if($lig_class_tmp->id==$id_classe){
			$temoin_tmp=1;
			if($lig_class_tmp=mysql_fetch_object($res_class_tmp)){
				$id_class_suiv=$lig_class_tmp->id;
			}
			else{
				$id_class_suiv=0;
			}
		}
		if($temoin_tmp==0){
			$id_class_prec=$lig_class_tmp->id;
		}
	}
}
// =================================



//**************** EN-TETE **************************************
$titre_page = "Gestion des classes | Gestion des �l�ves";
require_once("../lib/header.inc");
//**************** FIN EN-TETE **********************************
$call_classe = mysql_query("SELECT classe FROM classes WHERE id = '$id_classe'");
$classe = mysql_result($call_classe, "0", "classe");

$themessage  = 'Des informations ont �t� modifi�es. Voulez-vous vraiment quitter sans enregistrer ?';


?>
<form enctype="multipart/form-data" action="classes_const.php" method=post>

<?php

//=============================
// MODIF: boireaus

if(!isset($quitter_la_page)){
	echo "<p class='bold'>";
	echo "<a href='index.php' onclick=\"return confirm_abandon (this, change, '$themessage')\"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour </a> | <a href='prof_suivi.php?id_classe=$id_classe' onclick=\"return confirm_abandon (this, change, '$themessage')\">".ucfirst(getSettingValue("gepi_prof_suivi"))." : saisie rapide</a>\n";
	if($id_class_prec!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_prec' onclick=\"return confirm_abandon (this, change, '$themessage')\">Classe pr�c�dente</a>";}
	if($id_class_suiv!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_suiv' onclick=\"return confirm_abandon (this, change, '$themessage')\">Classe suivante</a>";}
	echo "</p>\n";
}
else{
	// Cette page a �t� ouverte en target='blank' depuis une autre page (par exemple /eleves/modify_eleve.php)
	// Apr�s modification �ventuelle, il faut quitter cette page.
	echo "<p class='bold'>";
	echo "<a href='index.php' onClick='self.close();return false;'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Refermer la page </a> | <a href='prof_suivi.php?id_classe=$id_classe' onclick=\"return confirm_abandon (this, change, '$themessage')\">".ucfirst(getSettingValue("gepi_prof_suivi"))." : saisie rapide</a>\n";
	if($id_class_prec!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_prec&amp;quitter_la_page=y' onclick=\"return confirm_abandon (this, change, '$themessage')\">Classe pr�c�dente</a>";}
	if($id_class_suiv!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_suiv&amp;quitter_la_page=y' onclick=\"return confirm_abandon (this, change, '$themessage')\">Classe suivante</a>";}
	echo "</p>\n";

	echo "<input type='hidden' name='quitter_la_page' value='y' />\n";
	// Il va falloir faire en sorte que la page destination tienne compte de la variable...
}

//debug_var();

//=============================
?>



<p class='bold'>Classe : <?php echo $classe; ?></p>
<center><input type="submit" value="Enregistrer" /></center>
<p>

<?php
echo "<img src='../images/icons/add_user.png' alt='' /> <a href='classes_ajout.php?id_classe=$id_classe' onclick=\"return confirm_abandon (this, change, '$themessage')\">Ajouter des �l�ves � la classe</a>";
?>
</p>
<p class='small'><b>Remarque :</b> lors du retrait d'un �l�ve de la classe pour une p�riode donn�e, celui-ci sera retir� de tous les enseignements auxquels il �tait inscrit pour la p�riode en question.</p>
<?php


echo "<script type='text/javascript'>

function CocheLigne(ki) {
	for (var i=1;i<$nb_periode;i++) {
		if(document.getElementById('case_'+i+'_'+ki)){
			document.getElementById('case_'+i+'_'+ki).checked = true;
		}
	}
}

function DecocheLigne(ki) {
	for (var i=1;i<$nb_periode;i++) {
		if(document.getElementById('case_'+i+'_'+ki)){
			document.getElementById('case_'+i+'_'+ki).checked = false;
		}
	}
}

</script>
";

$call_eleves = mysql_query("SELECT DISTINCT j.login FROM j_eleves_classes j, eleves e WHERE (j.id_classe = '$id_classe' and e.login = j.login) ORDER BY e.nom, e.prenom");
$nombreligne = mysql_num_rows($call_eleves);
if ($nombreligne == '0') {
	echo "<p>Il n'y a pas d'�l�ves actuellement dans cette classe.</p>\n";
} else {
	$k = '0';
	echo "<table border='1' cellpadding='5' class='boireaus'>\n";
	echo "<tr>\n";
	echo "<th>Nom Pr�nom </th>\n";
	echo "<th>R�gime</th>\n";
	echo "<th>Redoublant</th>\n";
	echo "<th>".ucfirst(getSettingValue("gepi_prof_suivi"))."</th><th>CPE responsable</th>\n";
	$i="1";
	while ($i < $nb_periode) {
		//echo "<th><p class=\"small\">Retirer de la classe<br />$nom_periode[$i]</p></th>\n";
		echo "<th><p class=\"small\">Retirer de la classe<br />$nom_periode[$i]<br />\n";

		echo "<a href=\"javascript:CocheColonne(".$i.");changement();\"><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a> / <a href=\"javascript:DecocheColonne(".$i.");changement();\"><img src='../images/disabled.png' width='15' height='15' alt='Tout d�cocher' /></a>";

		echo "</p></th>\n";
		$i++;
	}
	echo "<th>&nbsp;</th>\n";
	echo "</tr>\n";
	$alt=1;
	While ($k < $nombreligne) {
		$login_eleve = mysql_result($call_eleves, $k, 'login');
		$call_regime = mysql_query("SELECT * FROM j_eleves_regime WHERE login='$login_eleve'");
		$doublant = @mysql_result($call_regime, 0, 'doublant');
		$regime = @mysql_result($call_regime, 0, 'regime');
		if ($regime =='') {$regime = "d/p";}
		if ($doublant == '') {$doublant = '-';}

		$regime_login = "regime_".$login_eleve;
		$doublant_login = "doublant_".$login_eleve;
		$i="1";
			while ($i < $nb_periode) {
			$delete_login[$i] = "delete_".$login_eleve."_".$i;
		$i++;
		}
		$call_data_eleves = mysql_query("SELECT * FROM eleves WHERE (login = '$login_eleve')");
		$nom_eleve = @mysql_result($call_data_eleves, '0', 'nom');
		$prenom_eleve = @mysql_result($call_data_eleves, '0', 'prenom');

		$call_profsuivi_eleve = mysql_query("SELECT * FROM j_eleves_professeurs WHERE (login = '$login_eleve' and id_classe='$id_classe')");
		$eleve_profsuivi = @mysql_result($call_profsuivi_eleve, '0', 'professeur');
		$prof_login = "prof_".$login_eleve;

		$call_cperesp = mysql_query("SELECT u.nom nom, u.prenom prenom, j.cpe_login cpe_login FROM j_eleves_cpe j, utilisateurs u WHERE (u.login = j.cpe_login AND j.e_login = '$login_eleve')");
		$eleve_cperesp = @mysql_result($call_cperesp, '0', "cpe_login");
		$cpe_login = "cpe_".$login_eleve;

		$alt=$alt*(-1);
		echo "<tr class='lig$alt'>\n";
		echo "<td><p>".strtoupper($nom_eleve)." ".$prenom_eleve."\n";
		//=========================
		// AJOUT: boireaus 20071010
		echo "<input type='hidden' name='log_eleve[$k]' value=\"$login_eleve\" />\n";
		//=========================
		echo "<br /><b><a href='eleve_options.php?login_eleve=".$login_eleve."&amp;id_classe=".$id_classe."' onclick=\"return confirm_abandon (this, change, '$themessage')\">Mati�res suivies</a></b>";
		echo "</p></td>\n";
		echo "<td style='padding: 0;'>\n";

		echo "";

		echo "<table style='border-collaspe: collapse;'>\n";
		echo "<tr>\n";
		//=========================
		// MODIF: boireaus 20071010
		/*
		echo "<td style='text-align: center; border: 0px;'>I-ext<br /><input type='radio' name='$regime_login' value='i-e'";
		if ($regime == 'i-e') {echo " checked";}
		echo " /></td>\n";
		echo "<td style='text-align: center; border: 0px; border-left: 1px solid #AAAAAA;'>Int<br/><input type='radio' name='$regime_login' value='int.'";
		if ($regime == 'int.') {echo " checked";}
		echo " /></td>\n";
		echo "<td style='text-align: center; border: 0px; border-left: 1px solid #AAAAAA;'>D/P<br/><input type='radio' name='$regime_login' value='d/p'";
		if ($regime == 'd/p') {echo " checked";}
		echo " /></td>\n";
		echo "<td style='text-align: center; border: 0px; border-left: 1px solid #AAAAAA;'>Ext<br/><input type='radio' name='$regime_login' value='ext.'";
		if ($regime == 'ext.') {echo " checked";}
		//echo " /></p></td><td><p><center><input type='checkbox' name='$doublant_login' value='yes'";
		*/

		echo "<td style='text-align: center; border: 0px;'>I-ext<br /><input type='radio' name='regime_eleve[$k]' value='i-e' ";
		if ($regime == 'i-e') {echo " checked";}
		echo " onchange='changement()'";
		echo " /></td>\n";
		echo "<td style='text-align: center; border: 0px; border-left: 1px solid #AAAAAA;'>Int<br/><input type='radio' name='regime_eleve[$k]' value='int.' ";
		if ($regime == 'int.') {echo " checked";}
		echo " onchange='changement()'";
		echo " /></td>\n";
		echo "<td style='text-align: center; border: 0px; border-left: 1px solid #AAAAAA;'>D/P<br/><input type='radio' name='regime_eleve[$k]' value='d/p' ";
		if ($regime == 'd/p') {echo " checked";}
		echo " onchange='changement()'";
		echo " /></td>\n";
		echo "<td style='text-align: center; border: 0px; border-left: 1px solid #AAAAAA;'>Ext<br/><input type='radio' name='regime_eleve[$k]' value='ext.' ";
		if ($regime == 'ext.') {echo " checked";}
		//=========================
		echo " onchange='changement()'";
		echo " /></td></tr></table>\n";

		echo "";

		echo "</td>\n";

		//=========================
		// MODIF: boireaus 20071010
		//echo "<td><p align='center'><input type='checkbox' name='$doublant_login' value='yes' ";
		echo "<td><p align='center'><input type='checkbox' name='doublant_eleve[$k]' value='yes' ";
		//=========================
		if ($doublant == 'R') {echo " checked";}
		//echo " /></center></p></td><td><p><select size='1' name='$prof_login'>";
		echo " onchange='changement()'";
		echo " /></p></td>\n";

		echo "<td>\n";
		//=========================
		// MODIF: boireaus 20071010
		//echo "<p><select size='1' name='$prof_login'>\n";
		echo "<p><select size='1' name='prof_principal[$k]'";
		echo " onchange='changement()'";
		echo ">\n";
		//=========================
		$profsuivi = '(vide)';
		echo "<option value='$profsuivi'>(vide)</option>\n";
		$call_prof = mysql_query("SELECT DISTINCT u.login, u.nom, u.prenom " .
				"FROM utilisateurs u, j_groupes_professeurs jgp, j_groupes_classes jgc WHERE (" .
				"u.statut = 'professeur' and " .
				"u.login = jgp.login and " .
				"jgp.id_groupe = jgc.id_groupe and " .
				"jgc.id_classe = '".$id_classe."'" .
				") ORDER BY u.login");
		$nb = mysql_num_rows($call_prof);
		$i='0';
		while ($i < $nb) {
			$profsuivi = mysql_result($call_prof, $i, "login");
			$prof_nom = mysql_result($call_prof, $i, "nom");
			$prof_prenom = mysql_result($call_prof, $i, "prenom");
			echo "<option value='$profsuivi'";
			if ($profsuivi==$eleve_profsuivi) { echo " selected";}
			echo ">$prof_prenom $prof_nom</option>\n";
		$i++;
		}
		echo "</select></p>\n";
		echo "</td>\n";

		echo "<td>\n";
		//=========================
		// MODIF: boireaus 20071010
		//echo "<p><select size='1' name='$cpe_login'>\n";
		echo "<p><select size='1' name='cpe_resp[$k]'";
		echo " onchange='changement()'";
		echo ">\n";
		//=========================
			$cperesp = "(vide)";
			echo "<option value='$cperesp'>(vide)</option>\n";
			$call_cpe = mysql_query("SELECT login,nom,prenom FROM utilisateurs WHERE (statut='cpe' AND etat='actif')");
			$nb = mysql_num_rows($call_cpe);
			for ($i="0";$i<$nb;$i++) {
				$cperesp = mysql_result($call_cpe, $i, "login");
				$cperesp_nom = mysql_result($call_cpe, $i, "nom");
				$cperesp_prenom = mysql_result($call_cpe, $i, "prenom");
				echo "<option value='$cperesp'";
					if ($cperesp == $eleve_cperesp) echo " selected";
				echo ">" . $cperesp_prenom . " " . $cperesp_nom ;
				echo "</option>\n";
			}
		echo "</select></p>\n";
		echo "</td>\n";

		$i="1";
		while ($i < $nb_periode) {
			$call_trim = mysql_query("SELECT periode FROM j_eleves_classes WHERE (id_classe = '$id_classe' and periode = '$i' and login = '$login_eleve')");
			$nb_ligne = mysql_num_rows($call_trim);
			if ($nb_ligne != 0) {
				//echo "<td><p><center><input type='checkbox' name='$delete_login[$i]' value='yes' /></center></p></td>";
				//=========================
				// MODIF: boireaus 20071010
				//echo "<td><p align='center'><input type='checkbox' name='$delete_login[$i]' id='case_".$i."_".$k."' value='yes' /></p></td>\n";
				echo "<td><p align='center'><input type='checkbox' name='delete_".$k."[$i]' id='case_".$i."_".$k."' value='yes'";
				echo " onchange='changement()'";
				echo " /></p></td>\n";
				//=========================
			} else {
				$call_classe = mysql_query("SELECT c.classe FROM classes c, j_eleves_classes j WHERE (c.id = j.id_classe and j.periode = '$i' and j.login = '$login_eleve')");
				$nom_classe = @mysql_result($call_classe, 0, "classe");
				//echo "<td><p><center>$nom_classe&nbsp;</center></p></td>";
				echo "<td><p align='center'>$nom_classe&nbsp;</p></td>\n";
			}
			$i++;
		}

		echo "<td><a href=\"javascript:CocheLigne(".$k.");changement()\"><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a> <a href=\"javascript:DecocheLigne(".$k.");changement()\"><img src='../images/disabled.png' width='15' height='15' alt='Tout d�cocher' /></a></td>";

		echo "</tr>\n";
		$k++;
	}
	echo "</table>\n";
	echo "<p align='center'><input type='submit' value='Enregistrer' style='margin: 30px 0 60px 0;'/></p>\n";
	echo "<br />\n";

	echo "<script type='text/javascript'>

function CocheColonne(i) {
	for (var ki=0;ki<$k;ki++) {
		if(document.getElementById('case_'+i+'_'+ki)){
			document.getElementById('case_'+i+'_'+ki).checked = true;
		}
	}
}

function DecocheColonne(i) {
	for (var ki=0;ki<$k;ki++) {
		if(document.getElementById('case_'+i+'_'+ki)){
			document.getElementById('case_'+i+'_'+ki).checked = false;
		}
	}
}

</script>
";

}

?>
<input type='hidden' name='id_classe' value='<?php echo $id_classe;?>' />
<input type='hidden' name='is_posted' value='1' />
</form>
<?php require("../lib/footer.inc.php");?>
