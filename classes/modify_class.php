<?php
/*
 * THIS FILE IS DEPRECATED - IT HAS BEEN REPLACED WITH A NEW GROUP MANAGEMENT TOOL
 */

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

include "../lib/periodes.inc.php";

$message_enregistrement = "Les modifications ont �t� enregistr�es !";
if (isset($is_posted)) {
	check_token();

    $affiche_message = 'yes';

    $mess_avertissement = 'no';
    $mess_avertissement2 = 'no';
    $msg = '';
    // J'appelle les diff�rentes mati�res existantes :
    $callinfo = mysql_query("SELECT * FROM matieres");
    $nombre_lignes = mysql_num_rows($callinfo);

    $i = 0;
    while ($i < $nombre_lignes) {
        $reg_matiere = mysql_result($callinfo, $i, "matiere");
        $reg_matiere_max_profs = $reg_matiere."_max";
        $max = $_POST[$reg_matiere_max_profs];
        $m = 0;
        While ($m < $max) {
            $reg_matiere_prof[$m] = $reg_matiere."_prof".$m;
            if (isset($_POST[$reg_matiere_prof[$m]])) $prof[$m] = $_POST[$reg_matiere_prof[$m]];
            $m++;
        }
        $reg_matiere_priority = $reg_matiere."_priority";
        if (isset($_POST[$reg_matiere_priority])) {
            $priority = $_POST[$reg_matiere_priority];
        }
        $reg_matiere_coef = $reg_matiere."_coef";
        if (isset($_POST[$reg_matiere_coef])) $coef = $_POST[$reg_matiere_coef];

        // Si la mati�re est coch�e
        if (isset($_POST[$reg_matiere]) and ($_POST[$reg_matiere] == "yes")) {
            // On regarde
            $test_prof = '';
            $m = 0;
            While ($m < $max) {
                if ((isset($prof[$m])) and ($prof[$m] != '')) $test_prof = 'yes';
                $m++;
            }
            $suppression = '';
            if ($test_prof == '') {
                // Il n'y a aucun prof associ� � la mati�re
                // Il faut donc tester s'il y a des notes ou des appr�ciations associ�es
                $test1 = mysql_query("SELECT * FROM matieres_notes mn, j_eleves_classes j  WHERE (
                mn.matiere='".$reg_matiere."' and
                mn.login=j.login and
                j.id_classe='".$id_classe."'
                )");
                $nb_test1 = mysql_num_rows($test1);
                $test2 = mysql_query("SELECT * FROM matieres_appreciations ma, j_eleves_classes j  WHERE (
                ma.matiere='".$reg_matiere."' and
                ma.login=j.login and
                j.id_classe='".$id_classe."'
                )");
                $nb_test2 = mysql_num_rows($test2);
                if (($nb_test1 != 0) or ($nb_test2 != 0)) $suppression = 'impossible';
            }
            if ($suppression != 'impossible') {
            // On efface la ligne relatives � la classe et � la mati�re

            // AMELIORATION DU SCRIPT
            // Remarque : il faudrait normalement faire des test sur le carnet de notes et interdire la suppression d'un prof
            // si celui-ci a commenc� � rentrer des notes. Ou bien, il faudrait syst�matiquement supprimer le carnet de note correspondant
            $del = mysql_query("DELETE FROM j_classes_matieres_professeurs
            WHERE
            (
            id_classe='$id_classe' and
            id_matiere='$reg_matiere'
            )
            ");
            $m = 0;
            $compteur = 1;
            While ($m < $max) {
                if ((isset($prof[$m])) and ($prof[$m] != '')) {
                    $test = mysql_query("SELECT id_professeur FROM j_classes_matieres_professeurs
                        WHERE (
                        id_classe='$id_classe' and
                        id_matiere='$reg_matiere' and
                        id_professeur='$prof[$m]'
                        )");
                    $nb = mysql_num_rows($test);
                    if ($nb == 0) {
                        $reg_data = mysql_query("INSERT INTO j_classes_matieres_professeurs SET id_professeur='$prof[$m]', priorite='$priority', id_classe='$id_classe', id_matiere='$reg_matiere', ordre_prof='$compteur', coef='$coef', recalcul_rang='y'");
                        $compteur++;
                    }
                    $prof[$m] = '';
                }
                $m++;
            }
            $test = mysql_query("SELECT * FROM j_classes_matieres_professeurs
                WHERE (
                id_classe='$id_classe' and
                id_matiere='$reg_matiere'
                )");
            $nb = mysql_num_rows($test);
            if ($nb == 0) {
                $mess_avertissement = 'yes';
            }
            } else {
                $mess_avertissement2 = 'yes';
            }
        } else {
            // Si la mati�re n'est pas coch�e : on v�rifie s'il existe  une ligne correspondante dans  j_classes_matieres_professeurs
            // Si oui, on teste s'il y a des notes et appr�ciations associ�es
            // s'il y a des notes et appr�ciations associ�es, on ne peut pas supprimer la mati�re, sinon, on peut
                        // On efface la ligne relatives � la classe et � la mati�re
            $test = mysql_query("SELECT * FROM j_classes_matieres_professeurs
                WHERE (
                id_classe='$id_classe' and
                id_matiere='$reg_matiere'
                )");
            $nb = mysql_num_rows($test);
            if ($nb != 0) {
                $query_eleves = mysql_query("select distinct login from j_eleves_classes where id_classe='".$id_classe."'");
                $nb_eleves = mysql_num_rows($query_eleves);
                // on va tester s'il y a des notes et appr�ciations associ�es
                $k = 0;
                $suppression = '';
                while ($k < $nb_eleves) {
                    $login_eleve[$k] = mysql_result($query_eleves, $k, 'login');
                    $test1 = mysql_query("SELECT * FROM matieres_notes WHERE (matiere='".$reg_matiere."' and login='".$login_eleve[$k]."')");
                    $nb_test1 = mysql_num_rows($test1);
                    $test2 = mysql_query("SELECT * FROM matieres_appreciations WHERE (matiere='".$reg_matiere."' and login='".$login_eleve[$k]."')");
                    $nb_test2 = mysql_num_rows($test2);
                    if (($nb_test1 != 0) or ($nb_test2 != 0)) $suppression = 'impossible';
                    $k++;
                }
                if ($suppression == 'impossible') {
                    $msg = $msg."--> Impossible de supprimer la mati�re ".$reg_matiere." car des moyennes ou appr�ciations ont d�j� �t� rentr�es !<br />";
                    $message_enregistrement = "Impossible de supprimer une ou plusieurs mati�res : lisez le message en rouge en haut de la page.";

                } else {
                    // Si le test est concluant
                    // On supprime la ligne dans j_classes_matieres_professeurs
                    $del1 = mysql_query("DELETE FROM j_classes_matieres_professeurs
                    WHERE
                    (
                    id_classe='$id_classe' and
                    id_matiere='$reg_matiere'
                    )
                    ");
                    // On supprime les �l�ves non inscrits
                    $k = 0;
                    while ($k < $nb_eleves) {
                        $del2 = mysql_query("DELETE FROM j_eleves_matieres
                        WHERE
                        (
                        login = '".$login_eleve[$k]."' and
                        matiere='$reg_matiere'
                        )
                        ");

                        $k++;
                    }
                    // AMELIORATION DU SCRIPT
                    // Remarque : il faudrait normalement faire des test sur le carnet de notes et supprimer les donn�es dans
                    // celui-ci le cas �ch�ant.

                    // reste � �galement faire le m�nage dans  j_eleves_professeurs
                    $test3 = mysql_query("select distinct j.professeur
                    from j_eleves_professeurs j, j_professeurs_matieres m
                    where (
                    j.id_classe='".$id_classe."' and
                    j.professeur = m.id_professeur and
                    m.id_matiere = '".$reg_matiere."'
                    )"
                    );
                    $nb_prof = mysql_num_rows($test3);
                    $m = 0;
                    while ($m < $nb_prof) {
                        $login_prof = mysql_result($test3, $m, 'professeur');
                        $test4 = mysql_query("select distinct id_professeur from  j_classes_matieres_professeurs
                        where (
                        id_professeur='".$login_prof."' and
                        id_classe='".$id_classe."'
                        )");
                        if (mysql_num_rows($test4) == 0)
                            $del = mysql_query("delete from j_eleves_professeurs
                            where (
                            id_classe='".$id_classe."' and
                            professeur = '".$login_prof."'
                            )");
                        $m++;
                    }
                }
             }
        }
        // le cas �ch�ant, pour toutes les mati�res, on force la valeur de la priorit� d'affichage aux valeurs par d�faut.

        if (isset($_POST['force_defaut'])) {
            $priority_defaut = sql_query1("select priority from matieres where matiere='".$reg_matiere."'");
            $req = mysql_query("UPDATE j_classes_matieres_professeurs SET priorite='".$priority_defaut."'
            WHERE (
            id_classe='".$id_classe."' AND
            id_matiere='".$reg_matiere."'
            )
            ");
        }


    $i++;
    }

    if ($mess_avertissement == 'yes') {$msg .="ATTENTION : Certaines mati�res sont coch�es et n'ont pas de professeurs affect�s<br />L'enregistrement d'une mati�re n'a lieu que lorqu'au moins un professeur a �t� affect�.<br>";}
    if ($mess_avertissement2 == 'yes') {$msg .="ATTENTION : vous avez tent� de supprimer une mati�re en supprimant tous les professeurs associ�s alors que des notes ou des appr�ciations attach�es � cette mati�re dans cette classe existent d�j�.<br>";}
}

//**************** EN-TETE **************************************
$titre_page = "Gestion des classes | Gestion des mati�res";
require_once("../lib/header.inc");
//**************** FIN EN-TETE **********************************

?>
<script language=javascript>
chargement = false;
change = 'no';
</script>
<?php
$themessage = "Des changements ont eu lieu sur cette page et n\'ont pas �t� enregistr�s. Si vous cliquez sur OK les changements seront perdus.";

// Calcul du nombre d'�l�ves dans la classe � la premi�re periode:
$appel_donnees_eleves = mysql_query("SELECT DISTINCT e.* FROM eleves e, j_eleves_classes c WHERE (c.id_classe='$id_classe' and c.login = e.login and c.periode='1')");
$nombre_eleves = mysql_num_rows($appel_donnees_eleves);
?>
<form action="modify_class.php" name='form2' method=post>
<?php
echo add_token_field();
echo "<p class=bold>|<a href=\"index.php\" onclick=\"return confirm_abandon(this, change, '".$themessage."')\">Retour</a>";
echo "|<a href='javascript:centrerpopup(\"help_modify_class.html\",600,480,\"scrollbars=yes,statusbar=no,resizable=yes\")'>Aide</a>";

$test_prof_mat = mysql_result(mysql_query("SELECT count(*) FROM j_professeurs_matieres"),0);
if ($test_prof_mat==0) {
    echo "<p class='grand'>Aucune affectation professeur<->mati�re n'est disponible !</p>";
    echo "<p>Vous devez d'abord d�finir des professeurs et leur affecter des mati�res !</p>";
    die();
}


$test1 = mysql_result(mysql_query("SELECT count(*) FROM temp_gep_import"),0);
$test2 = mysql_result(mysql_query("SELECT count(*) FROM j_classes_matieres_professeurs WHERE id_classe='$id_classe'"),0);
if (($test1 != 0) and ($test2 != 0)) {
    ?>
    |<a href="init_options.php?id_classe=<?echo $id_classe?>">Initialisation des Options � partir de Gep</a>
    <?php
}
echo "|<input type=\"submit\" onclick=\"return VerifChargement()\" name=\"Envoyer\" value=\"Enregistrer les modifications\">";
echo "|</p>";

$call_nom_class = mysql_query("SELECT classe FROM classes WHERE id = '$id_classe'");
$classe = mysql_result($call_nom_class, 0, 'classe');
echo "<H3>Classe : $classe</H3>";

echo "<b>Pour toutes les mati�res, forcer les valeurs de priorit� d'affichage aux valeurs par d�faut :</b>
<input type=\"checkbox\" name=\"force_defaut\" onchange=\"changement()\"/><br /><br />";


// J'appelle les diff�rentes mati�res existantes :
$cas = 0;

while ($cas < 2) {
// Tout d'abord les mati�res ayant un ou plusieurs profs enregistr�s
if ($cas==0)
$call_class_info = mysql_query("SELECT DISTINCT m.*, j.priorite, j.coef FROM matieres m, j_classes_matieres_professeurs j
Where (
m.matiere=j.id_matiere and
j.id_classe='$id_classe'
) ORDER BY j.priorite , m.matiere");

// Ensuite les mati�res n'ayant aucun profs enregistr�s
if ($cas==1)
$call_class_info = mysql_query("SELECT DISTINCT m.*, j.priorite, j.coef FROM matieres m LEFT JOIN
j_classes_matieres_professeurs j ON (m.matiere=j.id_matiere and
 j.id_classe='$id_classe')
 where (j.id_matiere IS NULL)
  ORDER BY m.matiere  ");

// Dans chacun des cas pr�c�dent, on applique le traitement suivant :
$nombre_ligne = mysql_num_rows($call_class_info);

$i=0;
while ($i < $nombre_ligne) {
    $priority = mysql_result($call_class_info, $i, "priorite");
    $reg_matiere = mysql_result($call_class_info, $i, "matiere");
    $reg_matiere_complet = mysql_result($call_class_info, $i, "nom_complet");
    $reg_priority = $reg_matiere."_priority";
    $reg_matiere_max_profs = $reg_matiere."_max";
    $call_profs = mysql_query("SELECT * FROM j_classes_matieres_professeurs WHERE ( id_classe='$id_classe' and id_matiere = '$reg_matiere') ORDER BY ordre_prof");
    $nombre_profs = mysql_num_rows($call_profs);
    $coef = mysql_result($call_class_info, $i, "coef");
    $reg_coef = $reg_matiere."_coef";

    // priorit� par d�faut
    $priority_defaut = sql_query1("select priority from matieres where matiere='".$reg_matiere."'");

    // S'il s'agit d'une nouvelle mati�re, on propose l'ordre d'affichage par d�faut
    if ($priority == '') $priority = $priority_defaut;

    if (($nombre_profs != 0) or (isset($_POST[$reg_matiere]) and ($_POST[$reg_matiere] == "yes"))) {
        echo "<fieldset style=\"padding-top: 8px; padding-bottom: 8px;  margin-left: auto; margin-right: auto;\">";
        echo "<table border = '0' width='100%'><tr><td width='30%'>";
        echo "<input type=checkbox value=yes name=$reg_matiere onchange=\"changement()\" CHECKED>";
        if (($nombre_profs != 0) and (mysql_num_rows(mysql_query("select id_classe from periodes where id_classe = '$id_classe'")) != 0)) {
            echo "<span class=\"norme\"><b>$reg_matiere ($reg_matiere_complet)</b></span>";
        } else {
            echo "<span class=\"norme\"><b><font color='red'>$reg_matiere ($reg_matiere_complet)</font></b></span>";
        }
        echo "</td>";

        // Calcul du nombre d'inscrits pour chaque p�riode
        $tmpInscrits = null;
        for ($m=1;$m<$nb_periode;$m++) {
            $testquery = mysql_query("SELECT j.login FROM j_eleves_matieres j, j_eleves_classes c WHERE (".
                    "j.login = c.login AND " .
                    "c.id_classe = '" . $id_classe . "' AND " .
                    "j.matiere = '" . $reg_matiere . "' AND " .
                    "j.periode = '" . $m . "' AND " .
                    "c.periode = '" . $m . "'" .
                    ")");

            $test = mysql_num_rows($testquery);

            $total_eleves_periode = mysql_num_rows(mysql_query("SELECT DISTINCT e.* FROM eleves e, j_eleves_classes c WHERE (c.id_classe='$id_classe' AND c.login = e.login AND c.periode = '" . $m . "')"));

            $inscrits = $total_eleves_periode-$test;
            $tmpInscrits .= $inscrits . "-";
        }

        $nombre_inscrits = substr($tmpInscrits, 0, -1);



        echo "<td><b><a href='modify_options.php?id_classe=$id_classe&current_matiere=$reg_matiere' onclick=\"return confirm_abandon(this, change, '".$themessage."')\">El�ves inscrits (".$nombre_inscrits.")</a></b></td>";
        echo "<td width='30%'>Priorit� d'affichage";
        echo "<select size=1 name=$reg_priority onchange=\"changement()\">";
        echo "<option value=0";
        if  ($priority == '0') echo " SELECTED";
        echo ">0";
        if ($priority_defaut == 0) echo " (valeur par d�faut)";
        echo "</option>";
        $k = '0';

        $k='11';
        $j = '1';
        while ($k < '51'){
            echo "<option value=$k"; if ($priority == $k) {echo " SELECTED";} echo ">".$j;
            if ($priority_defaut == $k) echo " (valeur par d�faut)";
            echo "</option>";
            $k++;
            $j = $k - 10;
        }
        echo "</select>";

        echo "</td><td>Coefficient : <input type=\"text\" name=\"".$reg_coef."\" value=\"$coef\" size=\"5\" onchange=\"changement()\" /></td></tr></table>";
        $k = 0;
        while ($k < $nombre_profs+1) {
            $prof[$k] = @mysql_result($call_profs, $k, "id_professeur");
            $reg_matiere_prof[$k] = $reg_matiere."_prof".$k;
            $num_prof = $k+1;
            echo "<span class=\"norme\">Professeur $num_prof : <select size=1 name=$reg_matiere_prof[$k] onchange=\"changement()\">";
            $calldata = mysql_query("SELECT u.* FROM utilisateurs u, j_professeurs_matieres j WHERE (j.id_matiere = '$reg_matiere' and j.id_professeur = u.login and u.etat!='inactif') ORDER BY u.login");
            $nombreligneutilisateur = mysql_num_rows($calldata);
            $login_list = '';
            echo "<option value=$login_list>(vide)</option>";
            $m = '0';
            while ($m < $nombreligneutilisateur){
                $login_list = mysql_result($calldata, $m, "login");
                $nom_list = mysql_result($calldata, $m, "nom");
                $prenom_list = mysql_result($calldata, $m, "prenom");
                echo "<option value=$login_list"; if ($login_list == $prof[$k]) {echo " SELECTED";} echo ">$prenom_list $nom_list</option>";
                $m++;
            }
            ?>
            </select></span>&nbsp;&nbsp;
        <?php
        $k++;
        }
    } else {
        echo "<span class= \"norme\"><input type=checkbox value=yes name=$reg_matiere onchange=\"changement()\"> $reg_matiere ($reg_matiere_complet)</span>";
    }
    echo "</fieldset><br />";

    $nombre_profs++;
    echo "<input type=hidden name=$reg_matiere_max_profs value=$nombre_profs>";
    $i++;
}
// Fin du traitement
$cas++;
}

?>
<center><input type=submit value="Enregistrer les modifications"></center></p>
<input type=hidden name=id_classe value=<?php echo $id_classe;?>>
<input type=hidden name=is_posted value=1>
</form>
<script language=javascript>
chargement = true;
</script>

</body>
</html>