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

if (!isset($_SESSION['order_by'])) {$_SESSION['order_by'] = "date";}
$_SESSION['order_by'] = isset($_POST['order_by']) ? $_POST['order_by'] : (isset($_GET['order_by']) ? $_GET['order_by'] : $_SESSION['order_by']);
$order_by = $_SESSION['order_by'];

$call_data = mysql_query("SELECT * FROM inscription_items ORDER BY $order_by");
$nombre_lignes = mysql_num_rows($call_data);


if (isset($_POST['is_posted'])) {
	check_token();

    $del = mysql_query("delete from inscription_j_login_items where login='".$_SESSION['login']."'");
    $i = 0;
    while ($i < $nombre_lignes){
        $id = mysql_result($call_data, $i, "id");
        if (isset($_POST[$id])) {
            $req = mysql_query("insert into inscription_j_login_items
            set login='".$_SESSION['login']."',
            id = '".$id."'
            ");

        }
        $i++;
    }
    $msg = "Les modifications ont �t� enregistr�es.";
}


//**************** EN-TETE *****************
$titre_page = getSettingValue("mod_inscription_titre")." - Inscription";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
?>

<p class=bold>
<a href="../accueil.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>
</p>
<?php
echo getSettingValue("mod_inscription_explication");

echo "<form name=\"formulaire\" method=\"post\" action=\"inscription_index.php\">";
echo add_token_field();
echo "<table width=\"100%\" border=\"1\" cellspacing=\"1\" cellpadding=\"5\">\n";

echo "<tr>\n";
echo "<td><p class='bold'><a href='inscription_index.php?order_by=id'>N�</a></p></td>\n";
echo "<td><p class='bold'>Date</p></td>\n";
echo "<td><p class='bold'>Heure</p></td>\n";
//echo "<td><p class='bold'><a href='inscription_index.php?order_by=date'>Date</a></p></td>\n";
//echo "<td><p class='bold'><a href='inscription_index.php?order_by=heure'>Heure</a></p></td>\n";
echo "<td><p class='bold'><a href='inscription_index.php?order_by=description'>Intitul�</a></p></td>\n";
echo "<td><p class='bold'>Personnes actuellement inscrites</p></td>\n";
echo "<td><p class='bold'>S'inscrire</p></td>\n";
echo "</tr>\n";

$_SESSION['chemin_retour'] = $_SERVER['REQUEST_URI'];
$i = 0;
while ($i < $nombre_lignes){
    $id = mysql_result($call_data, $i, "id");
    $date = mysql_result($call_data, $i, "date");
    $day = substr($date, 8, 2);
    $month = substr($date, 5, 2);
    $year = substr($date, 0, 4);
    $date = mktime(0,0,0,$month,$day,$year);
    $date = strftime("%A %d %B %Y", $date);

    $heure = mysql_result($call_data, $i, "heure");
    $description = mysql_result($call_data, $i, "description");

    $inscrit = sql_query1("select id from inscription_j_login_items
    where login='".$_SESSION['login']."' and id='".$id."' ");


    $inscrits = mysql_query("select login from inscription_j_login_items
    where id='".$id."' ");
    $nb_inscrits = mysql_num_rows($inscrits);
    if ($nb_inscrits == 0) $noms_inscrits = "<center>-</center>"; else $noms_inscrits = "";
    $k = 0;
    $nom_prenom = "";
    while ($k < $nb_inscrits) {
        $login_inscrit = mysql_result($inscrits, $k, "login");
        $nom_inscrit = sql_query1("select nom from utilisateurs where login='".$login_inscrit."'");
        $prenom_inscrit = sql_query1("select prenom from utilisateurs where login='".$login_inscrit."'");
        if ($nom_inscrit == -1) $nom_inscrit = "<font color='red'>(Nom absent => login : ".$login_inscrit.")</font>";
        if ($prenom_inscrit == -1) $prenom_inscrit = "";
        $nom_prenom .=$prenom_inscrit." ".$nom_inscrit;
        if ($k < ($nb_inscrits-1)) $nom_prenom .= "<br />";
        $k++;

    }


    echo "<tr>\n";
    echo "<td>$id</td>\n";
    echo "<td>$date</td>\n";
    echo "<td>$heure</td>\n";
    echo "<td>$description</td>\n";
    echo "<td>".$nom_prenom."&nbsp;</td>\n";
    echo "<td><input type=\"checkbox\" name=\"".$id."\" value=\"y\" ";
    if ($inscrit == $id) echo " checked";
    echo " /></td>\n";
    echo "</tr>\n";
    $i++;
}
echo "</table>";
echo "<input type=\"hidden\" name=\"is_posted\" value=\"yes\" />";
echo "<div id=\"fixe\"><center>";
echo "<input type=\"submit\" name=\"ok\" value=\"Enregistrer\" />";
echo "</center></div></form><br />&nbsp;<br />&nbsp;<br />&nbsp;";

require("../lib/footer.inc.php");?>