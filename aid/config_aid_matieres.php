<?php
/*
 * @version: $Id$
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
// ========== Initialisation des variables ==========
$is_posted = isset($_POST["is_posted"]) ? $_POST["is_posted"] : NULL;
// ========== fin initialisation ===================

$requete = "select * from matieres order by nom_complet";
if (isset($is_posted) and ($is_posted == "1")) {
    check_token();

    $msg = "";
    $pb = "no";
    $res = mysql_query($requete);
    $nb_lignes = mysql_num_rows($res);
    $i = 0;
    while ($i < $nb_lignes) {
        $matiere = mysql_result($res,$i,"matiere");
        if (isset($_POST["id_".$matiere])) {
            $req = mysql_query("update matieres set matiere_aid='y' where matiere='".$matiere."'");
            if (!$req) {
                $msg .= "Probl�me lors de l'enregistrement de la mati�re ".$matiere.".<br />";
                $pb="yes";
            }
        } else {
            $test = sql_query1("select count(id) from aid where matiere1='".$matiere."' or matiere2='".$matiere."'");
            if ($test > 0) {
                $msg .= "La mati�re ".$matiere." ne peut �tre supprim�e car elle est d�j� utilis�e dans au moins une fiche projet.<br />";
                $pb = "yes";
            } else {
                $req = mysql_query("update matieres set matiere_aid='n' where matiere='".$matiere."'");
                if (!$req) {
                    $msg .= "Probl�me lors de la suppression de la mati�re ".$matiere.".<br />";
                    $pb="yes";
                }

            }
        }
        $i++;
    }
    if ($pb!="yes") $msg = "Les modifications ont �t� enregistr�es.";
}


//**************** EN-TETE *********************
$titre_page = "Configuration des disciplines pour les fiches projet";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
?>
<form enctype="multipart/form-data" name="formulaire" action="config_aid_matieres.php" method="post">
<p class="bold"><a href="config_aid_fiches_projet.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>
|<a href="javascript:centrerpopup('help.php',600,480,'scrollbars=yes,statusbar=no,resizable=yes')">Aide</a>|
<input type="submit" value="Enregistrer" /><br />
<?php

echo add_token_field();

echo "<p>Parmi les champs des fiches projet figurent les deux champs \"discipline principale\" et \"discipline secondaire\".
<br />Parmi toutes les mati�res actuellement pr�sentes dans la base de GEPI, indiquez dans le tableau ci-dessous
les mati�res qui appara�tront dans la liste d�roulante des diciplines propos�es pour le remplissage de ces champs.";

echo "<table border='1' cellpadding='5' class='boireaus'>";
echo "<tr><th><b>Identifiant de la mati�re</b></th>
<th><span class='small'>Nom complet de la mati�re</span></th>
<th><span class='small'>La mati�re fait partie de la liste des disciplines propos�es dans les fiches projet</span></th>
</tr>";
$res = mysql_query($requete);
$nb_lignes = mysql_num_rows($res);
$i = 0;
$alt=1;
while ($i < $nb_lignes) {
    $matiere = mysql_result($res,$i,"matiere");
    $nom_complet = mysql_result($res,$i,"nom_complet");
    $matiere_aid  = mysql_result($res,$i,"matiere_aid");
    $alt=$alt*(-1);
    echo "<tr class='lig$alt'>";
    echo "<td>".$matiere."</td>\n";
    echo "<td>".$nom_complet."</td>\n";
    echo "<td><input type=\"checkbox\" name=\"id_".$matiere."\" value=\"y\" ";
    if ($matiere_aid=='y') echo " checked ";
    echo "/></td>\n";
    echo "</tr>\n";
    $i++;
}
echo "</table><br /><br />";
?>
<input type="hidden" name="is_posted" value="1" />
<div id='fixe'>
<input type="submit" value="Enregistrer" />
</div>
</form>
<?php require("../lib/footer.inc.php"); ?>