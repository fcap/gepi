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


//**************** EN-TETE *****************
$titre_page = "Outil de gestion | Effacement des photos �l�ves";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
?><p class=bold><a href='index.php#efface_photos'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>
<h2>Effacement des photos d'�l�ves</h2>
<?php
// En multisite, on ajoute le r�pertoire RNE
if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y') {
	// On r�cup�re le RNE de l'�tablissement
	$rep_photos='../photos/'.$_COOKIE['RNE'].'/eleves';
}
else {
	$rep_photos='../photos/eleves';		
}

if((isset($_POST['is_posted']))&&(isset($_POST['supprimer']))) {
	check_token(false);

	$handle=opendir($rep_photos);
	//$tab_file = array();
	$n=0;
	$nbsuppr=0;
	$nberreur=0;
	$chaine="";
	while ($file = readdir($handle)) {
		if((my_eregi(".jpg$",$file))||(my_eregi(".jpeg$",$file))){

			$prefixe=substr($file,0,strrpos($file,"."));
			$sql="SELECT 1=1 FROM eleves WHERE elenoet='$prefixe'";
			//echo "<br />$sql<br />\n";
			$test=mysql_query($sql);

			if(mysql_num_rows($test)==0){
				//$tab_file[] = $file;
				if($n>0){
					//echo ", \n";
					$chaine.=", \n";
				}
				if(unlink($rep_photos."/".$file)){
					$chaine.="$file";
					$nbsuppr++;
				}
				else{
					$chaine.="<font color='red'>$file</font>";
					$nberreur++;
				}
				$n++;
			}
		}
	}
	closedir($handle);
	if($chaine!=""){
		echo "<p>R�sultat du nettoyage: $nbsuppr suppression(s) r�ussie(s)";
		if($nberreur>0){echo " et $nberreur �checs.<br />Contr�lez les droits sur ces fichiers et r�essayez";}
		echo ":<br />\n";
		echo "$chaine\n";
		echo "</p>\n";
	}
}
else {
    echo "<p><b>ATTENTION:</b> Cette proc�dure efface toutes les photos non associ�es � des �l�ves.</p>\n";

	$handle=opendir($rep_photos);
	//$tab_file = array();
	$n=0;
	$nbjpg=0;
	$chaine="";
	while ($file = readdir($handle)) {
		if((my_eregi(".jpg$",$file))||(my_eregi(".jpeg$",$file))){
			$nbjpg++;

			$prefixe=substr($file,0,strrpos($file,"."));
			$sql="SELECT 1=1 FROM eleves WHERE elenoet='$prefixe'";
			//echo "<br />$sql<br />\n";
			$test=mysql_query($sql);

			if(mysql_num_rows($test)==0){
				//$tab_file[] = $file;
				if($n>0){
					//echo ", \n";
					$chaine.=", \n";
				}
				//echo "<a href='../photos/eleves/$file'>$file</a>";
				$chaine.="<a href='".$rep_photos."/$file' target='blank'>$file</a>";
				$n++;
			}
		}
	}
	closedir($handle);
	if($chaine!=""){
		echo "<p>Les photos suivantes seraient supprim�es:\n";
		echo "$chaine\n";
		echo "<br />Soit un total de $n photo(s).</p>\n";

		echo "<p><b>Etes-vous s�r de vouloir continuer ?</b></p>\n";
		echo "<form action='".$_SERVER['PHP_SELF']."' method=\"post\" name=\"formulaire\">\n";
		echo add_token_field();
		echo "<input type='hidden' name=is_posted value = '1' />\n";
		echo "<input type='submit' name='supprimer' value='Supprimer ces photos' />\n";
		echo "</form>\n";
	}
	else{
		if($nbjpg>0){
			echo "<p>Aucune photo ne r�pond � ce crit�re.</p>\n";
		}
		else{
			echo "<p>Aucune photo JPEG n'a �t� trouv�e.</p>\n";
		}
	}
}

?>

</body>
</html>
