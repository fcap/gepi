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

$liste_tables_del = array(
//"absences",
//"aid",
//"aid_appreciations",
//"aid_config",
//"avis_conseil_classe",
//"classes",
//"droits",
//"eleves",
//"responsables",
//"etablissements",
//"j_aid_eleves",
//"j_aid_utilisateurs",
//"j_eleves_classes",
//"j_eleves_etablissements",
//"j_eleves_professeurs",
//"j_eleves_regime",
//"j_professeurs_matieres",
//"log",
//"matieres",
"matieres_appreciations",
"matieres_notes",
"matieres_appreciations_grp",
"matieres_appreciations_tempo",
//"periodes",
//"tempo2",
//"temp_gep_import",
//"tempo",
//"utilisateurs",
"cn_cahier_notes",
"cn_conteneurs",
"cn_devoirs",
"cn_notes_conteneurs",
"cn_notes_devoirs",
"groupes",
"j_eleves_groupes",
"j_groupes_classes",
"j_groupes_matieres",
"j_groupes_professeurs",
"eleves_groupes_settings"
//"setting"
);

// Initialisation
$lcs_ldap_people_dn = 'ou=people,'.$lcs_ldap_base_dn;
$lcs_ldap_groups_dn = 'ou=groups,'.$lcs_ldap_base_dn;

//**************** EN-TETE *****************
$titre_page = "Outil d'initialisation de l'ann�e : Importation des mati�res";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

echo "<p class=bold><a href='../init_lcs/index.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>";

if (isset($_POST['is_posted'])) {
    // L'admin a valid� la proc�dure, on proc�de donc...

    // On se connecte au LDAP
    $ds = connect_ldap($lcs_ldap_host,$lcs_ldap_port,"","");

    // On commence par r�cup�rer tous les profs depuis le LDAP
    $sr = ldap_search($ds,$ldap_base,"(cn=Matiere_*)");
    $info = ldap_get_entries($ds,$sr);

    if ($_POST['record'] == "yes") {
        // Suppression des donn�es pr�sentes dans les tables en lien avec les mati�res

        $j=0;
        while ($j < count($liste_tables_del)) {
            if (mysql_result(mysql_query("SELECT count(*) FROM $liste_tables_del[$j]"),0)!=0) {
                $del = @mysql_query("DELETE FROM $liste_tables_del[$j]");
            }
            $j++;
        }

        $new_matieres = array();
        echo "<table border=\"1\" cellpadding=\"3\" cellspacing=\"3\">\n";
        echo "<tr><td>Identifiant mati�re</td><td>Nom complet mati�re</td><td>identifiants prof.</td></tr>\n";
        for ($i=0;$i<$info["count"];$i++) {
            $matiere=preg_replace("/Matiere_/","",$info[$i]["cn"][0]);
            $get_matieres = mysql_query("SELECT matiere FROM matieres");
            $nbmat = mysql_num_rows($get_matieres);
            $matieres = array();
            for($j=0;$j<$nbmat;$j++) {
                $matieres[] = mysql_result($get_matieres, $j, "matiere");
            }

            if (!in_array($matiere, $matieres)) {
                $reg_matiere = mysql_query("INSERT INTO matieres SET matiere='".$matiere."',nom_complet='".html_entity_decode_all_version(stripslashes($_POST['reg_nom_complet'][$matiere]))."', priority='0'");
            } else {
                $reg_matiere = mysql_query("UPDATE matieres SET nom_complet='".html_entity_decode_all_version(stripslashes($_POST['reg_nom_complet'][$matiere]))."' WHERE matiere = '" . $matiere . "'");
            }
            if (!$reg_matiere) echo "<p>Erreur lors de l'enregistrement de la mati�re $matiere.";
            $new_matieres[] = $matiere;

            // On regarde maintenant les affectations professeur/mati�re
            $list_member = "";
            for ( $u = 0; $u < $info[$i]["member"]["count"] ; $u++ ) {
                $member = preg_replace ("/^uid=([^,]+),ou=.*/" , "\\1", $info[$i]["member"][$u] );
                if (trim($member) !="") {
                    if ($list_member != "") $list_member .=", ";
                    $list_member .=$member;
                    $test = mysql_result(mysql_query("SELECT count(*) FROM j_professeurs_matieres WHERE (id_professeur = '" . $member . "' and id_matiere = '" . $matiere . "')"), 0);
                    if ($test == 0) {
                        $res = mysql_query("INSERT into j_professeurs_matieres SET id_professeur = '" . $member . "', id_matiere = '" . $matiere . "'");
                    }
                }
            }
            echo "<tr><td>".$matiere."</td><td>".stripslashes($_POST['reg_nom_complet'][$matiere])."</td><td>".$list_member."</td></tr>\n";
        }
        // On efface les mati�res qui ne sont plus utilis�es
        echo "</table>";
        $to_remove = array_diff($matieres, $new_matieres);

        foreach($to_remove as $delete) {
            $res = mysql_query("DELETE from matieres WHERE matiere = '" . $delete . "'");
            $res2 = mysql_query("DELETE from j_professeurs_matieres WHERE id_matiere = '" . $delete . "'");
        }

        echo "<p>Op�ration effectu�e.</p>";
        echo "<p>Vous pouvez v�rifier l'importation en allant sur la page de <a href='../matieres/index.php'>gestion des mati�res</a>.</p>";

    } elseif ($_POST['record'] == "no") {

            echo "<form action='disciplines.php' method='post' name='formulaire'>";
            echo "<input type=hidden name='record' value='yes'>";
            echo "<input type=hidden name='is_posted' value='yes'>";

            echo "<p>Les mati�res en vert indiquent des mati�res d�j� existantes dans la base GEPI.<br />Les mati�res en rouge indiquent des mati�res nouvelles et qui vont �tre ajout�es � la base GEPI.<br /></p>";
            echo "<p>Attention !!! Il n'y a pas de tests sur les champs entr�s. Soyez vigilant � ne pas mettre des caract�res sp�ciaux dans les champs ...</p>";
            echo "<p>Essayez de remplir tous les champs, cela �vitera d'avoir � le faire ult�rieurement.</p>";
            echo "<p>N'oubliez pas <b>d'enregistrer les donn�es</b> en cliquant sur le bouton en bas de la page<br /><br />";
            echo "<br/>";
            echo "<center>";
            echo "<table border=1 cellpadding=2 cellspacing=2>";
            echo "<tr><td><p class=\"small\">Identifiant de la mati�re</p></td><td><p class=\"small\">Nom complet</p></td></tr>";
            for ($i=0;$i<$info["count"];$i++) {
                $matiere=preg_replace("/Matiere_/","",$info[$i]["cn"][0]);
                $description = $info[$i]["description"][0];
                $test_exist = mysql_query("SELECT * FROM matieres WHERE matiere='$matiere'");
                $nb_test_matiere_exist = mysql_num_rows($test_exist);

                if ($nb_test_matiere_exist==0) {
                    $nom_complet = $description;
                    $nom_court = "<font color=red>".$matiere."</font>";
                } else {
                    $id_matiere = mysql_result($test_exist, 0, 'matiere');
                    $nom_court = "<font color=green>".$matiere."</font>";
                    $nom_complet = mysql_result($test_exist, 0, 'nom_complet');
                }
                echo "<tr>";
                echo "<td>";
                echo "<p><b><center>$nom_court</center></b></p>";
                echo "";
                echo "</td>";
                echo "<td>";
                echo "<input type=\"text\" size=\"40\" name='reg_nom_complet[$matiere]' value=\"".$nom_complet."\">\n";
                echo "</td></tr>";
            }
            echo "</table>\n";
            echo "</center>";
            echo "<center><input type='submit' value='Enregistrer les donn�es'></center>\n";
            echo "</form>\n";
    }

} else {

    echo "<p><b>ATTENTION ...</b><br />";
    echo "<p>Si vous poursuivez la proc�dure les donn�es telles que notes, appr�ciations, ... seront effac�es.</p>";
    echo "<p>Seules la table contenant les mati�res et la table mettant en relation les mati�res et les professeurs seront conserv�es.</p>";
    echo "<p>L'op�ration d'importation des mati�res depuis le LDAP de LCS va effectuer les op�rations suivantes :</p>";
    echo "<ul>";
    echo "<li>Ajout ou mise � jour de chaque mati�res pr�sente dans le LDAP</li>";
    echo "<li>Association professeurs <-> mati�res</li>";
    echo "</ul>";
    echo "<form enctype='multipart/form-data' action='disciplines.php' method=post>";
    echo "<input type=hidden name='is_posted' value='yes'>";
    echo "<input type=hidden name='record' value='no'>";

    echo "<p>Etes-vous s�r de vouloir importer toutes les mati�res depuis l'annuaire du serveur LCS vers Gepi ?</p>";
    echo "<br/>";
    echo "<input type='submit' value='Je suis s�r'>";
    echo "</form>";
}
require("../lib/footer.inc.php");
?>