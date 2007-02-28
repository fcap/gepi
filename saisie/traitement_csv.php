<?php
/*
 * Last modification  : 15/03/2005
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


$id_groupe = isset($_POST['id_groupe']) ? $_POST['id_groupe'] : (isset($_GET['id_groupe']) ? $_GET['id_groupe'] : NULL);
if (is_numeric($id_groupe) && $id_groupe > 0) {
	$current_group = get_group($id_groupe);
} else {
	$current_group = false;
}

include "../lib/periodes.inc.php";

if ($_SESSION['statut'] != "secours") {
    if (!(check_prof_groupe($_SESSION['login'],$current_group["id"]))) {
        $mess=rawurlencode("Vous n'�tes pas professeur de cet enseignement !");
        header("Location: index.php?msg=$mess");
        die();
    }
}

//**************** EN-TETE *****************
$titre_page = "Saisie des moyennes et appr�ciations | Importation";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
echo "<p class='bold'><a href='index.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour accueil saisie</a></p>";

echo "<p class = 'grand'>Importation de moyennes et appr�ciations - $nom_periode[$periode_num]</p>";
echo "<p class = 'bold'>Groupe : " . $current_group["description"] . " " . $current_group["classlist_string"] . " | Mati�re : " . $current_group["matiere"]["nom_complet"];
echo "<p>";
$modif = 'no';
$nb_row++;
for ($row=1; $row<$nb_row; $row++) {
    $enregistrement_note = 'yes';
    $temp = "reg_".$row."_login";
    if (isset($$temp)) {
        $reg_login = $$temp;
        $reg_login = urldecode($reg_login);
    } else {
        $reg_login = '';
    }
    $temp = "reg_".$row."_note";
    $reg_note = $$temp;
    if (isset($$temp)) {
        $reg_note = urldecode($reg_note);
    } else {
        $reg_note = '';
    }
    $temp = "reg_".$row."_app";
    if (isset($$temp)) {
        $reg_app = $$temp;
        $reg_app = urldecode($reg_app);
        $reg_app = traitement_magic_quotes(corriger_caracteres($reg_app));
    } else {
        $reg_app = '';
    }

    $call_login = mysql_query("SELECT * FROM eleves WHERE login='$reg_login'");
    $test = mysql_num_rows($call_login);
    if ($test != 0) {
        //
        // Si l'�l�ve ne suit pas l'enseignement, �chec
        //
        if (in_array($reg_login, $current_group["eleves"][$periode_num]["list"]))  {
            $reg_note_min = strtolower($reg_note);
            if (ereg ("^[0-9\.\,]{1,}$", $reg_note)) {
                $reg_note = str_replace(",", ".", "$reg_note");
                //$test_num = settype($reg_note,"double");
                if (($reg_note >= 0) and ($reg_note <= 20)) {
                    $elev_statut = '';
                } else {
                    $reg_note = '0';
                    $elev_statut = '-';
                }
            } elseif ($reg_note_min == '-') {
                $reg_note = '0';
                $elev_statut = '-';
            } elseif ($reg_note_min == "disp") {
                $reg_note = '0';
                $elev_statut = 'disp';
            } elseif ($reg_note_min == "abs") {
                $reg_note = '0';
                $elev_statut = 'abs';
            } elseif ($reg_note == "") {
                $enregistrement_note = 'no';
            } else {
                $reg_note = '0';
                $elev_statut = '-';
            }

            if ($enregistrement_note != "no") {
                $test_eleve_note_query = mysql_query("SELECT * FROM matieres_notes WHERE (login='$reg_login' AND id_groupe='" . $id_groupe . "' AND periode='$periode_num')");
                $test = mysql_num_rows($test_eleve_note_query);
                if ($test != "0") {
                    $reg_data1 = mysql_query("UPDATE matieres_notes SET note='$reg_note',statut='$elev_statut', rang='0' WHERE (login='$reg_login' AND id_groupe='" . $id_groupe . "' AND periode='$periode_num')");
                    $modif = 'yes';
                } else {
                    $reg_data1 = mysql_query("INSERT INTO matieres_notes SET login='$reg_login', id_groupe='" . $id_groupe . "',periode='$periode_num',note='$reg_note',statut='$elev_statut', rang='0'");
                    $modif = 'yes';
                }
            } else {
                $reg_data1 ='ok';
            }

            if ($reg_app != "") {
                $test_eleve_app_query = mysql_query("SELECT * FROM matieres_appreciations WHERE (login='$reg_login' AND id_groupe='" . $id_groupe . "' AND periode='$periode_num')");
                $test = mysql_num_rows($test_eleve_app_query);
                if ($test != 0) {
                    $reg_data2 = mysql_query("UPDATE matieres_appreciations SET appreciation='" . $reg_app . "' WHERE (login='$reg_login' AND id_groupe='" . $current_group["id"] . "' AND periode='$periode_num')");
                } else {          
                    $reg_data2 = mysql_query("INSERT INTO matieres_appreciations set login = '" . $reg_login . "', id_groupe = '" . $id_groupe . "', periode = '" . $periode_num . "', appreciation = '" . $reg_app . "'");
                    echo mysql_error();
                }
            } else {
                $reg_data2 = 'ok';
            }

        }
    }
    if ((!$reg_data1) or (!$reg_data2)) {
        echo "<font color=red>Erreur lors de la modification de donn�es de l'utilisateur $reg_login !</font><br />";
    } else {
        echo "Les donn�es de l'utilisateur $reg_login ont �t� modifi�es avec succ�s !<br />";
    }
}

// on indique que qu'il faut le cas �ch�ant proc�der � un recalcul du rang des �l�ves
if ($modif == 'yes') {
    $recalcul_rang = sql_query1("select recalcul_rang from groupes
    where id='".$id_groupe."' limit 1 ");
    $long = strlen($recalcul_rang);
    if ($long >= $periode_num) {
        $recalcul_rang = substr_replace ( $recalcul_rang, "y", $periode_num-1, $periode_num);
    } else {
       for ($l = $long; $l<$periode_num; $l++) {
           $recalcul_rang = $recalcul_rang.'y';
       }
    }
    $req = mysql_query("update groupes set recalcul_rang = '".$recalcul_rang."'
    where id='".$id_groupe."'");
}

echo "</p>";
echo "<p><a href='saisie_notes.php?id_groupe=$id_groupe&order_by=nom'>Acc�der � la page de saisie des moyennes pour v�rification</a>";
echo "<br /><a href='saisie_appreciations.php?id_groupe=$id_groupe&order_by=nom'>Acc�der � la page de saisie des appr�ciations pour v�rification</a></p>";
require("../lib/footer.inc.php");
?>