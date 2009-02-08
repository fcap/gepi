<?php
/*
 * $Id$
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


$msg="";

//GepiAccesRestrAccesAppProfP
if($_SESSION['statut']=="professeur") {
	if(getSettingValue('GepiAccesRestrAccesAppProfP')!="yes") {
		$msg="Acc�s interdit au param�trage des acc�s aux appr�ciatons/avis pour les parents et �l�ves.";
		header("Location: ../accueil.php?msg=".rawurlencode($msg));
	    die();
	}

	$sql="SELECT 1=1 FROM j_eleves_professeurs WHERE professeur='".$_SESSION['login']."';";
	$test=mysql_query($sql);
	if(mysql_num_rows($test)==0){
		$gepi_prof_suivi=getSettingValue('gepi_prof_suivi');
		$msg="Vous n'�tes pas ".$gepi_prof_suivi.".<br />Vous ne devriez donc pas acc�der � cette page.";
		header("Location: ../accueil.php?msg=".rawurlencode($msg));
	    die();
	}
}


$sql="CREATE TABLE IF NOT EXISTS `matieres_appreciations_acces` (
`id_classe` INT( 11 ) NOT NULL ,
`statut` VARCHAR( 255 ) NOT NULL ,
`periode` INT( 11 ) NOT NULL ,
`date` DATE NOT NULL ,
`acces` ENUM( 'y', 'n', 'date', 'd' ) NOT NULL
);";
$creation_table=mysql_query($sql);
/*
if(isset($_POST['submit'])) {
	$max_per=isset($_POST['max_per']) ? $_POST['max_per'] : 0;
	$id_classe=isset($_POST['id_classe']) ? $_POST['id_classe'] : NULL;
	$nb_classe=isset($_POST['nb_classe']) ? $_POST['nb_classe'] : 0;

	unset($tab);
	$tab=array();
	$tab['ele']='eleve';
	$tab['resp']='responsable';

	$cpt=0;

	foreach($tab as $pref => $statut) {
		for($j=0;$j<$nb_classe;$j++){
			if(isset($id_classe[$j])) {
				for($i=1;$i<=$max_per;$i++){
					if(isset($_POST[$pref.'_mode_'.$j.'_'.$i])) {
						$mode=$_POST[$pref.'_mode_'.$j.'_'.$i];
						if($mode=="manuel") {
							if(isset($_POST[$pref.'_acces_'.$j.'_'.$i])) {
								$accessible="y";
							}
							else {
								$accessible="n";
							}
							$sql="DELETE FROM matieres_appreciations_acces
									WHERE id_classe='$id_classe[$j]' AND
											statut='$statut' AND
											periode='$i';";
							$suppr=mysql_query($sql);

							$sql="INSERT INTO matieres_appreciations_acces
									SET id_classe='$id_classe[$j]',
											statut='$statut',
											periode='$i',
											acces='$accessible';";
							$insert=mysql_query($sql);
							if(!$insert) {$msg.="Erreur sur l'acc�s aux appr�ciations de la classe ".get_class_from_id($id_classe[$j])." en $statut pour la p�riode $i.<br />\n";}else{$cpt++;}
						}
						else {
							if(isset($_POST[$pref.'_display_date_'.$j.'_'.$i])) {
								$tmp_date=$_POST[$pref.'_display_date_'.$j.'_'.$i];
								// Contr�ler le format de la date et sa validit�.

								$tabdate=explode("/",$tmp_date);

								if(checkdate($tabdate[1],$tabdate[0],$tabdate[2])) {
									$date=sprintf("%04d",$tabdate[2])."-".$tabdate[1]."-".$tabdate[0];

									$sql="DELETE FROM matieres_appreciations_acces
											WHERE id_classe='$id_classe[$j]' AND
													statut='$statut' AND
													periode='$i';";
									$suppr=mysql_query($sql);

									$sql="INSERT INTO matieres_appreciations_acces
											SET id_classe='$id_classe[$j]',
													statut='$statut',
													periode='$i',
													date='$date',
													acces='date';";
									$insert=mysql_query($sql);
									if(!$insert) {$msg.="Erreur sur l'acc�s aux appr�ciations de la classe ".get_class_from_id($id_classe[$j])." en $statut pour la p�riode $i.<br />\n";}else{$cpt++;}
								}
								else {
									$msg.="La date $tmp_date n'est pas valide pour la classe ".get_class_from_id($id_classe[$j])." en $statut pour la p�riode $i.<br />\n";
								}
							}
						}
					}
				}
			}
		}
	}
	if(($msg=="")&&($cpt>0)) {
		if($cpt==1) {
			$msg="Enregistrement effectu�.<br />\n";
		}
		else{
			$msg="Enregistrements effectu�s ($cpt).<br />\n";
		}
	}
}
*/

$javascript_specifique="classes/acces_appreciations";

//include "../lib/periodes.inc.php";
$themessage  = 'Des informations ont �t� modifi�es. Voulez-vous vraiment quitter sans enregistrer ?';
//**************** EN-TETE *****************
$titre_page = "Acc�s aux appr�ciations";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

echo "<p class=bold><a href='../accueil.php' onclick=\"return confirm_abandon (this, change, '$themessage')\"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Accueil</a></p>\n";

if($_SESSION['statut']=="professeur") {
	$gepi_prof_suivi=getSettingValue('gepi_prof_suivi');

	$sql="SELECT 1=1 FROM j_eleves_professeurs WHERE professeur='".$_SESSION['login']."';";
	$test=mysql_query($sql);
	if(mysql_num_rows($test)==0){
		echo "<p>Vous n'�tes pas ".$gepi_prof_suivi.".<br />Vous ne devriez donc pas acc�der � cette page.</p>\n";
		echo "<p><br /></p>\n";
		require("../lib/footer.inc.php");
		exit();
	}

	$sql="SELECT DISTINCT c.* FROM j_eleves_professeurs jep, j_eleves_classes jec, classes c
					WHERE jep.professeur='".$_SESSION['login']."' AND
						jep.login=jec.login AND
						jec.id_classe=c.id
					ORDER BY c.classe;";
}
elseif($_SESSION['statut']=="scolarite") {
	$sql="SELECT DISTINCT c.* FROM j_scol_classes jsc, classes c
					WHERE jsc.login='".$_SESSION['login']."' AND
						jsc.id_classe=c.id
					ORDER BY c.classe;";
}
elseif($_SESSION['statut']=="administrateur") {
	$sql="SELECT DISTINCT c.* FROM classes c ORDER BY c.classe;";
}
$res_classe=mysql_query($sql);

if(mysql_num_rows($res_classe)==0) {
	echo "<p>Vous n'avez acc�s � aucune classe.</p>\n";
	echo "<p><br /></p>\n";
	require("../lib/footer.inc.php");
	exit();
}

$tab_classe=array();
$cpt=0;
$max_per=0;
while($lig=mysql_fetch_object($res_classe)){

	$sql="SELECT MAX(num_periode) AS max_per FROM periodes WHERE id_classe='$lig->id';";
	$res_per=mysql_query($sql);

	if(mysql_num_rows($res_per)!=0) {
		$tab_classe[$cpt]=array();
		$tab_classe[$cpt]['id']=$lig->id;
		$tab_classe[$cpt]['classe']=$lig->classe;

		$lig_per=mysql_fetch_object($res_per);
		if($lig_per->max_per>$max_per) {$max_per=$lig_per->max_per;}

		$cpt++;
	}
}

echo "<p>Vous pouvez d�finir ici quand les comptes utilisateurs pour des responsables et des �l�ves peuvent acc�der aux appr�ciations des professeurs et avis du conseil de classe.<br />
Il est souvent appr�ci� de pouvoir interdire l'acc�s aux �l�ves et responsables avant que le conseil de classe se soit d�roul�.<br />
Cet acc�s est conditionn� par l'existence des comptes responsables et �l�ves.</p>\n";


echo "<p>L'ouverture/fermeture de l'acc�s aux appr�ciations peut se faire selon trois crit�res:</p>\n";
echo "<ul>\n";
echo "<li><img src='../images/icons/configure.png' width='16' height='16' alt=\"Manuel\" /> Bascule manuelle de l'acc�s ou de l'interdiction d'acc�s.</li>\n";
echo "<li><img src='../images/icons/date.png' width='16' height='16' alt=\"Choix d'une date de d�verrouillage\" /> Ouverture automatique de l'acc�s � la date choisie.</li>\n";
echo "<li><img src='../images/icons/securite.png' width='16' height='16' alt=\"P�riode close\" /> Ouverture automatique de l'acc�s une fois la p�riode compl�tement close.<br />\n";
echo "Il est cependant possible d'ajouter un d�lais apr�s cloture de la p�riode avant que l'ouverture soit effective pour les �l�ves/responsables.<br />\n";
echo "Ce d�lais (<i>en nombre de jours</i>) se param�tre en administrateur dans ";
if($_SESSION['statut']=='administrateur') {echo "<a href='../gestion/param_gen.php#delais_apres_cloture'>";}
echo "Gestion g�n�rale/Configuration g�n�rale";
if($_SESSION['statut']=='administrateur') {echo "</a>";}
echo ".</li>\n";
echo "</ul>\n";


//echo "<form method='post' action='".$_SERVER['PHP_SELF']."' name='form2'>\n";
//echo "<p align='center'><input type='submit' name='submit' value='Valider' /></p>\n";

$delais_apres_cloture=getSettingValue('delais_apres_cloture');

include("../lib/calendrier/calendrier.class.php");
$cal = new Calendrier("form", "choix_date");

$titre="Choix de la date";
//$texte="<input type='text' name='choix_date' id='choix_date' size='10' value='$display_date'";
$texte="<form name='form' action='".$_SERVER['PHP_SELF']."' method='get'>\n";
$texte.="<p align='center'>\n";
$texte.="<input type='hidden' name='id_div' id='choix_date_id_div' value='' />\n";
$texte.="<input type='hidden' name='statut' id='choix_date_statut' value='' />\n";
$texte.="<input type='hidden' name='id_classe' id='choix_date_id_classe' value='' />\n";
$texte.="<input type='hidden' name='periode' id='choix_date_periode' value='' />\n";
$texte.="<input type='text' name='choix_date' id='choix_date' size='10' value='' />\n";
$texte.="<a href='#calend' onClick=\"".$cal->get_strPopup('../lib/calendrier/pop.calendrier.php', 350, 170).";document.getElementById('choix_date').checked='true';\"><img src='../lib/calendrier/petit_calendrier.gif' alt='Calendrier' border='0' /></a>\n";
$texte.="<br />\n";
$texte.="<input type='button' name='choix_date_valider' value='Valider' onclick=\"g_date()\" />\n";
$texte.="</p>\n";
$texte.="</form>\n";

$tabdiv_infobulle[]=creer_div_infobulle('infobulle_choix_date',$titre,"",$texte,"",14,0,'y','y','n','n');

echo "<table class='boireaus' width='100%'>\n";
echo "<tr>\n";
echo "<th rowspan='2'>Classe</th>\n";
echo "<th rowspan='2'>Statut</th>\n";
echo "<th colspan='$max_per'>P�riodes</th>\n";
echo "</tr>\n";

echo "<tr>\n";
for($i=1;$i<=$max_per;$i++) {
	$sql="SELECT DISTINCT nom_periode FROM periodes WHERE num_periode='$i';";
	$test=mysql_query($sql);
	if(mysql_num_rows($test)==1) {
		$lig_per=mysql_fetch_object($test);
		echo "<th>$lig_per->nom_periode</th>\n";
	}
	else{
		echo "<th>P�riode $i</th>\n";
	}
}
echo "</tr>\n";

$annee = strftime("%Y");
$mois = strftime("%m");
$jour = strftime("%d");

$display_date=$jour."/".$mois."/".$annee;

//include("../lib/calendrier/calendrier.class.php");

$tab_statut=array('eleve', 'responsable');
$tab_statut2=array('El�ve', 'Responsable');

$alt=1;
for($j=0;$j<count($tab_classe);$j++) {
	$alt=$alt*(-1);
	$id_classe=$tab_classe[$j]['id'];
	unset($nom_periode);
	unset($ver_periode);
	include "../lib/periodes.inc.php";
	if(isset($nom_periode)) {
		if(count($nom_periode)>0){
			for($k=0;$k<count($tab_statut);$k++) {
				if($k==0) {
					echo "<tr class='lig$alt'>\n";
					echo "<td rowspan='2'>".$tab_classe[$j]['classe'];
					echo "<input type='hidden' name='id_classe[$j]' value='$id_classe' />\n";
					echo "</td>\n";
				}
				else {
					echo "<tr class='lig$alt'>\n";
				}

				echo "<td>$tab_statut2[$k]</td>\n";

				for($i=1;$i<=count($nom_periode);$i++) {
					$sql="SELECT * FROM matieres_appreciations_acces WHERE id_classe='$id_classe' AND periode='$i' AND statut='$tab_statut[$k]';";
					$res=mysql_query($sql);
					if(mysql_num_rows($res)==0) {
						$mode="manuel";
						$display_date=$jour."/".$mois."/".$annee;
						$accessible="n";
					}
					else {
						$lig=mysql_fetch_object($res);
						//if($lig->date=="0000-00-00") {
						if($lig->acces=="date") {
							$mode="date";
							$tabdate=explode("-",$lig->date);
							$display_date=$tabdate[2]."/".$tabdate[1]."/".$tabdate[0];

							$timestamp_limite=mktime(0,0,0,$tabdate[1],$tabdate[2],$tabdate[0]);
							$timestamp_courant=time();
							if($timestamp_courant>$timestamp_limite) {
								$accessible="y";
							}
							else {
								$accessible="n";
							}
						}
						elseif($lig->acces=="d") {
							$mode="d";
							/*
							$sql="SELECT verouiller,date_verrouillage FROM periodes WHERE id_classe='$id_classe' AND num_periode='$i';";
							$res_ver_per=mysql_query($sql);
							*/
							//$display_date=$jour."/".$mois."/".$annee;

							if($ver_periode[$i]!='O') {
								$accessible="n";
								if($ver_periode[$i]='P') {$etat_periode="P�riode partiellement close";} else {$etat_periode="P�riode ouverte";}
							}
							else {
								$tmp_tabdate=explode(" ",$date_ver_periode[$i]);
								$tabdate=explode("-",$tmp_tabdate[0]);
								$display_date=$tabdate[2]."/".$tabdate[1]."/".$tabdate[0];

								$timestamp_limite=mktime(0,0,0,$tabdate[1],$tabdate[2],$tabdate[0])+$delais_apres_cloture*24*3600;
								$timestamp_courant=time();
								if($timestamp_courant>=$timestamp_limite) {
									$accessible="y";
									$etat_periode="Accessible depuis<br />le $jour/$mois/$annee";
								}
								else {
									$accessible="n";
									$tmp_date=getdate($timestamp_limite);
									$jour=sprintf("%02d",$tmp_date['mday']);
									$mois=sprintf("%02d",$tmp_date['mon']);
									$annee=$tmp_date['year'];
									$etat_periode="Acces possible<br />le $jour/$mois/$annee";
								}
							}
						}
						else {
							$mode="manuel";
							$display_date=$jour."/".$mois."/".$annee;
							$accessible=$lig->acces;
						}
						//$accessible=$lig->acces;
					}

					//echo "<td id='td_ele_".$j."_".$i."'";
					echo "<td";
					/*
					if($accessible=="y") {
						echo " style='background-color:green;'\n";
					}
					else {
						echo " style='background-color:red;'\n";
					}
					*/
					echo ">\n";

						$id_div=$tab_statut[$k]."_".$j."_".$i;
						//$statut="eleve";
						/*
						echo "<div id='$id_div' style='width:100%; height:100%;";
						if($accessible=="y") {
							echo " background-color:lightgreen;\n";
						}
						else {
							echo " background-color:orangered;\n";
						}
						echo "'>\n";
						*/

						// Modifier le lien pour soumettre effectivement si javascript est d�sactiv�
						echo "<a href='#' onclick=\"g_manuel('$id_div', $id_classe, $i,'$accessible','$tab_statut[$k]');return false;\"><img src='../images/icons/configure.png' width='16' height='16' alt=\"Manuel\" /></a>\n";
						echo " | ";
						echo "<a href='#' onclick=\"$('choix_date_id_div').value='$id_div';$('choix_date_id_classe').value=$id_classe;$('choix_date_statut').value='$tab_statut[$k]';$('choix_date_periode').value=$i;afficher_div('infobulle_choix_date','y',-100,20);return false;\"><img src='../images/icons/date.png' width='16' height='16' alt=\"Choix d'une date de d�verrouillage\" /></a>\n";
						echo " | ";
						echo "<a href='#' onclick=\"g_periode_close('$id_div', $id_classe, $i,'$tab_statut[$k]');return false;\"><img src='../images/icons/securite.png' width='16' height='16' alt=\"P�riode close\" /></a>\n";
						echo "<br />\n";

						echo "<div id='$id_div' style='width:100%; height:100%;";
						if($accessible=="y") {
							echo " background-color:lightgreen;\n";
						}
						else {
							echo " background-color:orangered;\n";
						}
						echo "'>\n";

						//echo "$mode: $display_date";
						//echo "$mode";
						if($mode=='manuel') {
							echo "Manuel";
						}
						elseif($mode=='date') {
							echo "Date: $display_date";
						}
						elseif($mode=='d') {
							echo "$etat_periode";
						}
						echo "</div>\n";

					echo "</td>\n";
				}
				echo "</tr>\n";
			}

		}
	}
}

echo "</table>\n";
/*
echo "<input type='hidden' name='max_per' value='$max_per' />\n";
echo "<input type='hidden' name='nb_classe' value='$j' />\n";
echo "<p align='center'><input type='submit' name='submit' value='Valider' /></p>\n";
echo "</form>\n";
*/

echo "<p><br /></p>\n";
require("../lib/footer.inc.php");
?>