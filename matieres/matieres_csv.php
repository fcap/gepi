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

//INSERT INTO `droits` ( `id` , `administrateur` , `professeur` , `cpe` , `scolarite` , `eleve` , `secours` , `description` , `statut` ) VALUES ('/matieres/matieres_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'Importation des mati�res depuis un fichier CSV', '');
if (!checkAccess()) {
    header("Location: ../logout.php?auto=1");
    die();
}


if (isset($is_posted)) {
	check_token();
}

//**************** EN-TETE *****************
$titre_page = "Mati�res : Importation des mati�res depuis un CSV";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
?>
<p class=bold><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour accueil mati�res</a></p>

<?php

echo "<center><h3 class='gepi'>Importation des mati�res</h3></center>\n";

if (!isset($is_posted)) {

    echo "<p>Importation d'un fichier CSV o� chaque ligne est de la forme: <code>nom_court;nom_long</code><br /><i>Par exemple:</i><br />\n";
    echo "<pre>MATHS;MATHEMATIQUES
FRANC;FRANCAIS
...</pre>\n";
    //echo "</p>\n";
    echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post'>\n";
	echo add_token_field();
    echo "<input type='hidden' name='is_posted' value='yes' />\n";
    //echo "<input type='hidden' name='step1' value='y'>";
    echo "<p><input type='file' size='80' name='csv_file' /><br />\n";
    echo "<input type='submit' value='Valider' /></p>\n";
    echo "</form>\n";

}
else {
    $csv_file = isset($_FILES["csv_file"]) ? $_FILES["csv_file"] : NULL;
    //if(strtoupper($csv_file['name']) == "F_TMT.DBF") {
        //$fp = dbase_open($csv_file['tmp_name'], 0);
        $fp=fopen($csv_file['tmp_name'],"r");
        if(!$fp) {
            echo "<p>Impossible d'ouvrir le fichier CSV</p>\n";
            echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."'>Cliquer ici </a> pour recommencer !</p>\n";
        }
        else{
            $msg="";

            echo "<p>Dans le tableau ci-dessous, les identifiants en rouge correspondent � des nouvelles mati�res dans la base GEPI. les identifiants en vert correspondent � des identifiants de mati�res d�tect�s dans le fichier CSV mais d�j� pr�sents dans la base GEPI.</p>\n";
            echo "<table class='boireaus' border='1' cellpadding='2' cellspacing='2' summary='Tableau des mati�res du CSV'>\n";
            echo "<tr><th><p class=\"small\">Identifiant de la mati�re</p></th><th><p class=\"small\">Nom complet</p></th></tr>\n";

			$alt=1;

            $nb_reg_no = 0;
            while(!feof($fp)){
                $temoin_erreur="non";
                $tmp_lig=fgets($fp,4096);
				if(trim($tmp_lig)!=""){
					$ligne=explode(";",$tmp_lig);

					$affiche[0]=traitement_magic_quotes(corriger_caracteres(dbase_filter(trim($ligne[0]))));
					if((strlen(preg_replace("/[A-Za-z0-9_ &]/","",strtr($affiche[0],"-","_")))!=0)&&($affiche[0]!="")){
					//if((strlen(my_ereg_replace("[A-Za-z������������������������������0-9_ &]","",strtr($affiche[$i],"-","_")))!=0)&&($affiche[$i]!="")){
						$temoin_erreur="oui";
						//echo "<!--  -->\n";
						$msg.="Le nom <font color='red'>$affiche[0]</font> ne convient pas.<br />\n";
						$nb_reg_no++;
					}

					$affiche[1]=traitement_magic_quotes(corriger_caracteres(dbase_filter(trim($ligne[1]))));
					//if((strlen(my_ereg_replace("[A-Za-z0-9_ &]","",strtr($affiche[$i],"-","_")))!=0)&&($affiche[$i]!="")){
					//echo "\$affiche[1]=$affiche[1]<br />";
					//echo "my_ereg_replace(\"[A-Za-z������������������������������0-9_ &]\",\"\",$affiche[1])=".my_ereg_replace("[A-Za-z������������������������������0-9_ &]","",$affiche[1])."<br />";
					if((strlen(preg_replace("/[A-Za-z������������������������������0-9_ &]/","",strtr($affiche[1],"-","_")))!=0)&&($affiche[1]!="")){
						$temoin_erreur="oui";
						//echo "<!--  -->\n";
						$msg.="Le nom <font color='red'>$affiche[1]</font> ne convient pas.<br />\n";
						$nb_reg_no++;
					}

					if(($affiche[0]!="")&&($affiche[1]!="")&&($temoin_erreur!="oui")){
						$alt=$alt*(-1);

						$verif = mysql_query("select matiere, nom_complet from matieres where matiere='$affiche[0]'");
						$resverif = mysql_num_rows($verif);
						if($resverif == 0) {
							$req = mysql_query("insert into matieres set matiere='$affiche[0]', nom_complet='$affiche[1]', priority='0',matiere_aid='n',matiere_atelier='n'");
							if(!$req) {
								$nb_reg_no++;
								//echo mysql_error();
								echo "<tr class='lig$alt white_hover'><td colspan='2'><font color='red'>".mysql_error()."</font></td></tr>\n";
							} else {
								echo "<tr class='lig$alt white_hover'><td><p><font color='red'>".htmlentities($affiche[0])."</font></p></td><td><p>".htmlentities($affiche[1])."</p></td></tr>\n";
							}
						} else {
							$nom_complet = mysql_result($verif,0,'nom_complet');
							echo "<tr class='lig$alt white_hover'><td><p><font color='green'>".htmlentities($affiche[0])."</font></p></td><td><p>".htmlentities($nom_complet)."</p></td></tr>\n";
						}
					}
				}
            }
            echo "</table>\n";
            //dbase_close($fp);
            fclose($fp);
            if ($nb_reg_no != 0) {
                echo "<p>Lors de l'enregistrement des donn�es il y a eu <b>$nb_reg_no erreur(s)</b>.<br />Essayez de trouver la cause de l'erreur et recommencez la proc�dure.</p>\n";
                if($msg!=""){
                    echo "<p>Voici la liste des chaines non accept�es:</p>\n";
                    echo "<blockquote>\n";
                    echo "$msg";
                    echo "</blockquote>\n";
                }
            } else {
                echo "<p>L'importation des mati�res dans la base GEPI a �t� effectu�e avec succ�s !<br />";
            }
        }
}
require("../lib/footer.inc.php");
?>