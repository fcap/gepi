<?php
/*
 * Last modification  : 07/08/2006
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


unset($retour_cn);
$retour_cn = isset($_POST["retour_cn"]) ? $_POST["retour_cn"] : (isset($_GET["retour_cn"]) ? $_GET["retour_cn"] : NULL);

$id_groupe = isset($_POST['id_groupe']) ? $_POST['id_groupe'] : (isset($_GET['id_groupe']) ? $_GET['id_groupe'] : NULL);
if (is_numeric($id_groupe) && $id_groupe > 0) {
    $current_group = get_group($id_groupe);
} else {
    $current_group = false;
}
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : (isset($_POST['order_by']) ? $_POST["order_by"] : "classe");
if (count($current_group["classes"]["list"]) > 1) {
    $multiclasses = true;
} else {
    $multiclasses = false;
    $order_by = "nom";
}


include "../lib/periodes.inc.php";

if ($_SESSION['statut'] != "secours") {
    if (!(check_prof_groupe($_SESSION['login'],$current_group["id"]))) {
        $mess=rawurlencode("Vous n'�tes pas professeur de cet enseignement !");
        header("Location: index.php?msg=$mess");
        die();
    }
}



if (isset($is_posted) and ($is_posted == 'yes')) {

    foreach ($current_group["eleves"]["all"]["list"] as $reg_eleve_login) {
        $k=1;
        while ($k < $nb_periode) {
            if (in_array($reg_eleve_login, $current_group["eleves"][$k]["list"])) {
                $eleve_id_classe = $current_group["classes"]["classes"][$current_group["eleves"][$k]["users"][$reg_eleve_login]["classe"]]["id"];
                if ($current_group["classe"]["ver_periode"][$eleve_id_classe][$k] == "N"){
                    $nom_log = $reg_eleve_login."_t".$k;
                    $note = $$nom_log;
                    $elev_statut = '';
                    if (($note == 'disp')) { $note = '0'; $elev_statut = 'disp';
                    } else if (($note == 'abs')) { $note = '0'; $elev_statut = 'abs';
                    } else if (($note == '-')) { $note = '0'; $elev_statut = '-';
                    } else if (ereg ("^[0-9\.\,]{1,}$", $note)) {
                        $note = str_replace(",", ".", "$note");
                        if (($note < 0) or ($note > 20)) { $note = ''; $elev_statut = '';}
                    } else {
                        $note = ''; $elev_statut = '';
                    }
                    if (($note != '') or ($elev_statut != '')) {
                        $test_eleve_note_query = mysql_query("SELECT * FROM matieres_notes WHERE (login='$reg_eleve_login' AND id_groupe='" . $current_group["id"] . "' AND periode='$k')");
                        $test = mysql_num_rows($test_eleve_note_query);
                        if ($test != "0") {
                            $register = mysql_query("UPDATE matieres_notes SET note='$note',statut='$elev_statut', rang='0' WHERE (login='$reg_eleve_login' AND id_groupe='" . $current_group["id"] . "' AND periode='$k')");
                            $modif[$k] = 'yes';
                        } else {
                            $register = mysql_query("INSERT INTO matieres_notes SET login='$reg_eleve_login', id_groupe='" . $current_group["id"] . "',periode='$k',note='$note',statut='$elev_statut', rang='0'");
                            $modif[$k] = 'yes';
                        }
                    } else {
                        $register = mysql_query("DELETE FROM matieres_notes WHERE (login='$reg_eleve_login' and id_groupe='" . $current_group["id"] . "' and periode='$k')");
                        $modif[$k] = 'yes';
                    }
                }
            }

            $k++;
        }
    }
    // on indique qu'il faut le cas �ch�ant proc�der � un recalcul du rang des �l�ves
    $k=1;
    while ($k < $nb_periode) {
        if (isset($modif[$k]) and ($modif[$k] == 'yes')) {
            $recalcul_rang = sql_query1("select recalcul_rang from groupes
            where id='".$current_group["id"]."' limit 1");
            $long = strlen($recalcul_rang);
            if ($long >= $k) {
                $recalcul_rang = substr_replace ( $recalcul_rang, "y", $k-1, $k);
            } else {
                for ($l = $long; $l<$k; $l++) {
                    $recalcul_rang = $recalcul_rang.'y';
                }
            }
            $req = mysql_query("update groupes set recalcul_rang = '".$recalcul_rang."'
            where id='".$current_group["id"]."'");
        }
        $k++;
    }


    $affiche_message = 'yes';
}
if (!isset($is_posted)) $is_posted = '';
$themessage  = 'Des notes ont �t� modifi�es. Voulez-vous vraiment quitter sans enregistrer ?';
$message_enregistrement = "Les modifications ont �t� enregistr�es !";
//**************** EN-TETE *****************
$titre_page = "Saisie des moyennes";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

// Couleurs utilis�es
$couleur_devoirs = '#AAE6AA';
$couleur_fond = '#AAE6AA';
$couleur_moy_cn = '#96C8F0';

if (!isset($periode_cn)) $periode_cn = 0;

// appel du carnet de notes
if ($periode_cn != 0) {
    $login_prof = $_SESSION['login'];
    $appel_cahier_notes = mysql_query("SELECT id_cahier_notes FROM cn_cahier_notes WHERE (id_groupe = '" . $current_group["id"] . "' and periode='$periode_cn')");
    $id_racine = @mysql_result($appel_cahier_notes, 0, 'id_cahier_notes');

}

$matiere_nom = $current_group["matiere"]["nom_complet"];

$affiche_bascule = 'no';
$i = 1;

while ($i < $nb_periode) {
    if (($current_group["classe"]["ver_periode"]["all"][$i] >= 2) and ($periode_cn == $i)) $affiche_bascule = 'yes';
    $i++;
}
echo "<p class=bold>";
if (isset($retour_cn)) {
    echo "<a href=\"../cahier_notes/index.php?id_groupe=" . $current_group["id"] . "&amp;periode_num=$periode_cn\" onclick=\"return confirm_abandon (this, change, '$themessage')\"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour vers mes �valuations</a>";
} else {
    echo "<a href=\"index.php\" onclick=\"return confirm_abandon (this, change, '$themessage')\"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour accueil saisie</a>";
}
echo " | <a href='saisie_appreciations.php?id_groupe=" . $current_group["id"] . "&amp;periode_cn=$periode_cn' onclick=\"return confirm_abandon (this, change, '$themessage')\">Saisir les appr�ciations</a>";
// enregistrement du chemin de retour pour la fonction imprimer
$_SESSION['chemin_retour'] = $_SERVER['PHP_SELF']."?". $_SERVER['QUERY_STRING'];
echo " | <a href='../prepa_conseil/index1.php?id_groupe=$id_groupe'>Imprimer</a>";
echo "</p>";
echo "<h2 class='gepi'>Bulletin scolaire - Saisie des moyennes</H2>";
echo "<script type=\"text/javascript\" language=\"javascript\">\n";
if (($affiche_bascule == 'yes') and ($is_posted == 'bascule')) echo "change = 'yes';"; else echo "change = 'no';";

echo "</script>\n";

//echo "<table  border=\"0\">\n";
if ($affiche_bascule == 'yes') {
//if ($id_racine == '') echo "<tr><td></td><td><font color=\"#FF0000\">Actuellement, vous n'utilisez pas le cahier de notes. Il n'y a donc aucune note � importer.</font></td></tr>\n";
if ($id_racine == '') echo "<font color=\"#FF0000\">Actuellement, vous n'utilisez pas le cahier de notes. Il n'y a donc aucune note � importer.</font>\n";
    echo "<form enctype=\"multipart/form-data\" action=\"saisie_notes.php\" method=\"post\">\n";
    if ($is_posted != 'bascule') {
        //echo "<tr><td><input type=\"submit\" value=\"Recopier\"></td><td> : Recopier la colonne \"carnet de notes\" dans la colonne \"bulletin\"</td></tr>\n";
        echo "<input type=\"submit\" value=\"Recopier\" /> : Recopier la colonne \"carnet de notes\" dans la colonne \"bulletin\"\n";
        echo "<input type=\"hidden\" name=\"is_posted\" value=\"bascule\" />\n";
    } else {
        //echo "<tr><td><input type=\"submit\" value=\"Annuler recopie\"></td><td> : Afficher dans la colonne \"bulletin\" les moyennes actuellement enregistr�es</td></tr>\n";
        echo "<input type=\"submit\" value=\"Annuler recopie\" /> : Afficher dans la colonne \"bulletin\" les moyennes actuellement enregistr�es\n";
    }
    echo "<input type=\"hidden\" name=\"id_groupe\" value= \"".$id_groupe."\" />\n";
    echo "<input type=\"hidden\" name=\"periode_cn\" value=\"".$periode_cn."\" />\n";
    if (isset($retour_cn)) echo "<input type=\"hidden\" name=\"retour_cn\" value=\"".$retour_cn."\" />\n";
    echo "</form>\n";
}




//=============================================================
// MODIF: boireaus
echo "
<script type='text/javascript' language='JavaScript'>

function verifcol(num_id){
    document.getElementById('n'+num_id).value=document.getElementById('n'+num_id).value.toLowerCase();
    if(document.getElementById('n'+num_id).value=='a'){
        document.getElementById('n'+num_id).value='abs';
    }
    if(document.getElementById('n'+num_id).value=='d'){
        document.getElementById('n'+num_id).value='disp';
    }
    if(document.getElementById('n'+num_id).value=='n'){
        document.getElementById('n'+num_id).value='-';
    }
    note=document.getElementById('n'+num_id).value;

    if((note!='-')&&(note!='disp')&&(note!='abs')&&(note!='')){
        //if((note.search(/^[0-9.]+$/)!=-1)&&(note.lastIndexOf('.')==note.indexOf('.',0))){
        if(((note.search(/^[0-9.]+$/)!=-1)&&(note.lastIndexOf('.')==note.indexOf('.',0)))||
	((note.search(/^[0-9,]+$/)!=-1)&&(note.lastIndexOf(',')==note.indexOf(',',0)))){
            if((note>20)||(note<0)){
                couleur='red';
            }
            else{
                couleur='$couleur_devoirs';
            }
        }
        else{
            couleur='red';
        }
    }
    else{
        couleur='$couleur_devoirs';
    }
    eval('document.getElementById(\'td_'+num_id+'\').style.background=couleur');
}
</script>
";
//=============================================================




echo "<form enctype=\"multipart/form-data\" action=\"saisie_notes.php\" method=\"post\" name=\"saisie\">\n";
?>

<!--tr><td><input type=submit value=Enregistrer></td><td> : Enregistrer les moyennes dans le bulletin</td></tr></table-->
<p><input type="submit" value="Enregistrer" /> : Enregistrer les moyennes dans le bulletin
</p>
<p><i>Taper une note de 0 � 20 pour chaque �l�ve, ou � d�faut le code 'a' pour 'absent', le code 'd' pour 'dispens�', le code 'n' ou '-' pour absence de note.</i></p>
<?php
echo "<p><b>Moyennes (sur 20) de : ".htmlentities($current_group["description"])." (" . $current_group["classlist_string"] . ")</b></p>\n";
?>
<table border=1 cellspacing=2 cellpadding=1>
<tr>
    <td><b><a href="saisie_notes.php?id_groupe=<?php echo $id_groupe;?>&amp;periode_cn=<?php echo $periode_cn;?>&amp;order_by=nom">Nom Pr�nom</a></b></td>
    <?php
    if ($multiclasses) {
        echo "<td><b><a href='saisie_notes.php?id_groupe=$id_groupe&amp;periode_cn=$periode_cn&amp;order_by=classe'>Classe</a></b></td>";
    }
    $i = 1;
    while ($i < $nb_periode) {
        if (($periode_cn == $i) and ($current_group["classe"]["ver_periode"]["all"][$i] >= 2)) {
            echo "<th bgcolor=\"$couleur_fond\" colspan=\"2\"><b>".ucfirst($nom_periode[$i])."</b></th>\n";
        } else {
            echo "<td><b>".ucfirst($nom_periode[$i])."</b></td>\n";
        }
        $i++;
    }
echo "</tr>";
echo "<tr><td>&nbsp;</td>";
if ($multiclasses) echo "<td>&nbsp;</td>";

    $i = 1;
    while ($i < $nb_periode) {
        if ($current_group["classe"]["ver_periode"]["all"][$i] >= 2) {
            if ($periode_cn == $i) {
                echo "<td bgcolor=\"$couleur_moy_cn\">Carnet de notes</td><td bgcolor=\"$couleur_fond\">Bulletin</td>\n";
            } else {
                echo "<td>&nbsp;</td>\n";
            }
        } else {
            echo "<td><b>".ucfirst($gepiClosedPeriodLabel)."</b></td>\n";
        }
        //echo "</td>\n";
        $i++;
    }
    ?>
</tr>

<?php
// On commence par mettre la liste dans l'ordre souhait�
if ($order_by != "classe") {
    $liste_eleves = $current_group["eleves"]["all"]["list"];
} else {
    // Ici, on tri par classe
    // On va juste cr�er une liste des �l�ves pour chaque classe
    $tab_classes = array();
    foreach($current_group["classes"]["list"] as $classe_id) {
        $tab_classes[$classe_id] = array();
    }
    // On passe maintenant �l�ve par �l�ve et on les met dans la bonne liste selon leur classe
    foreach($current_group["eleves"]["all"]["list"] as $eleve_login) {
        $classe = $current_group["eleves"]["all"]["users"][$eleve_login]["classe"];
        $tab_classes[$classe][] = $eleve_login;
    }
    // On met tout �a � la suite
    $liste_eleves = array();
    foreach($current_group["classes"]["list"] as $classe_id) {
        $liste_eleves = array_merge($liste_eleves, $tab_classes[$classe_id]);
    }
}

$eleve_login = null;
$num_id = 10;
$prev_classe = null;
foreach ($liste_eleves as $eleve_login) {

    $k=1;
    while ($k < $nb_periode) {

        if (in_array($eleve_login, $current_group["eleves"][$k]["list"])) {
            //
            // si l'�l�ve appartient au groupe pour cette p�riode
            //
            $eleve_nom = $current_group["eleves"][$k]["users"][$eleve_login]["nom"];
            $eleve_prenom = $current_group["eleves"][$k]["users"][$eleve_login]["prenom"];
            $eleve_classe = $current_group["classes"]["classes"][$current_group["eleves"][$k]["users"][$eleve_login]["classe"]]["classe"];
            $eleve_id_classe = $current_group["classes"]["classes"][$current_group["eleves"][$k]["users"][$eleve_login]["classe"]]["id"];
            $suit_option[$k] = 'yes';
            //
            // si l'�l�ve suit la mati�re
            //
            $note_query = mysql_query("SELECT * FROM matieres_notes WHERE (login='$eleve_login' AND id_groupe = '" . $current_group["id"] . "' AND periode='$k')");
            $eleve_statut = @mysql_result($note_query, 0, "statut");
            $eleve_note = @mysql_result($note_query, 0, "note");
            $eleve_login_t[$k] = $eleve_login."_t".$k;
            if ($current_group["classe"]["ver_periode"][$eleve_id_classe][$k] != "N") {
                //
                // si la p�riode est verrouill�e
                //

                if ($periode_cn == $k) {
                    // Affichage de la colonne du carnet de notes
                    $moyenne_query = mysql_query("SELECT * FROM cn_notes_conteneurs WHERE (login='$eleve_login' AND id_conteneur='$id_racine')");
                    $statut_moy = @mysql_result($moyenne_query, 0, "statut");
                    if ($statut_moy == 'y') {
                        $moy = @mysql_result($moyenne_query, 0, "note");
                    } else {
                        $moy = '&nbsp;';
                    }
                    $mess[$k] = "<td bgcolor=\"$couleur_moy_cn\"><center>$moy</center></td>\n";
                    $temp = "bgcolor=$couleur_fond";
                } else {
                    $mess[$k] = '';
                    $temp = "";
                }
                // Affichage de la colonne 'note'
                $mess[$k] =$mess[$k]."<td><center><b>";
                if ($eleve_statut != '') {
                    $mess[$k] = $mess[$k].$eleve_statut;
                } else {
                    if ($eleve_note != '') {
                        $mess[$k] =$mess[$k]."$eleve_note";
                    } else {
                        $mess[$k] =$mess[$k]."&nbsp;";
                    }
                }
                //$mess[$k] =$mess[$k]."</center></b></td>\n";
                $mess[$k] =$mess[$k]."</b></center></td>\n";
            } else {
                //
                // si la p�riode n'est pas verrouill�e
                //

                if ($periode_cn == $k) {
                    // Affichage de la colonne du carnet de notes
                    $moyenne_query = mysql_query("SELECT * FROM cn_notes_conteneurs WHERE (login='$eleve_login' AND id_conteneur='$id_racine')");
                    $statut_moy = @mysql_result($moyenne_query, 0, "statut");
                    if ($statut_moy == 'y') {
                        $moy = @mysql_result($moyenne_query, 0, "note");
                    } else {
                        $moy = '&nbsp;';
                    }
                    $mess[$k] = "<td bgcolor=\"$couleur_moy_cn\"><center>$moy</center></td>\n";
                    $temp = "bgcolor=$couleur_fond";
                } else {
                    $mess[$k] = '';
                    $temp = "";
                }
                // Affichage de la colonne 'note'
                if (($periode_cn == $k) and ($is_posted=='bascule')) {
                    $mess[$k] = $mess[$k]."<td id=\"td_".$k.$num_id."\" ".$temp."><center><input id=\"n".$k.$num_id."\" onKeyDown=\"clavier(this.id,event);\" type=\"text\" size=\"4\" name=\"".$eleve_login_t[$k]."\" value=";
                    if ($statut_moy == 'y') {
                        $mess[$k] = $mess[$k]."\"".@mysql_result($moyenne_query, 0, "note")."\"";
                    } else {
                        $mess[$k] = $mess[$k]."\"\"";
                    }
                    $mess[$k] = $mess[$k]." onfocus=\"javascript:this.select()\" onchange=\"verifcol(".$k.$num_id.");changement()\" /></center></td>\n";
                } else {
                    $mess[$k] = $mess[$k]."<td id=\"td_".$k.$num_id."\" ".$temp."><center><input id=\"n".$k.$num_id."\" onKeyDown=\"clavier(this.id,event);\" type=\"text\" size=\"4\" name=\"".$eleve_login_t[$k]."\" value=";
                    if ($eleve_statut != '') {
                        $mess[$k] = $mess[$k]."\"".$eleve_statut."\"";
                    } else {
                        $mess[$k] = $mess[$k]."\"".$eleve_note."\"";
                    }
                    $mess[$k] = $mess[$k]." onfocus=\"javascript:this.select()\" onchange=\"verifcol(".$k.$num_id.");changement()\" /></center></td>\n";
                }
            }

            } else {
            //
            // si l'�l�ve n'est pas dans le groupe pour la p�riode
            //
            $suit_option[$k] = 'no';
            if (($periode_cn == $k) and ($current_group["classe"]["ver_periode"]["all"][$k] >= 2)) {
                $mess[$k] = "<td bgcolor=\"$couleur_moy_cn\"><center>-</center></td><td bgcolor=\"$couleur_fond\"><center>-</center></td>\n";
            } else {
               $mess[$k] = "<td><center>-</center></td>\n";
            }
        }

        $k++;
    }

    //
    //Affichage de la ligne
    //
    $display_eleve='no';
    $k=1;
    while ($k < $nb_periode) {
        if ($suit_option[$k] != 'no') {$display_eleve='yes';}
        $k++;
    }
    if ($display_eleve=='yes') {
        $num_id++;
        if ($order_by == "nom" OR $prev_classe == $eleve_classe OR $prev_classe == null) {
            echo "<tr><td>$eleve_nom $eleve_prenom</td>";
            if ($multiclasses) echo "<td>$eleve_classe</td>";
            echo "\n";
            $prev_classe = $eleve_classe;
        } else {
            echo "<tr><td style='border-top: 2px solid blue;'>$eleve_nom $eleve_prenom</td>";
            if ($multiclasses) echo "<td style='border-top: 2px solid blue;'>$eleve_classe</td>";
            echo "\n";
            $prev_classe = $eleve_classe;
        }
        $k=1;
        while ($k < $nb_periode) {
            echo $mess[$k];
            $k++;
        }
        echo "</tr>";
    }

    $i++;
}

echo "<tr>";
if ($multiclasses) {
    echo "<td colspan=2>";
} else {
    echo "<td>";
}
echo "Moyennes :</td>";

$k='1';
$temp = '';
while ($k < $nb_periode) {
    if (($periode_cn == $k) and ($current_group["classe"]["ver_periode"]["all"][$k] >= 2)) {
        $call_moy_moy = mysql_query("SELECT round(avg(n.note),1) moyenne FROM cn_notes_conteneurs n, j_eleves_groupes j WHERE
        (
        j.id_groupe='" . $current_group["id"] ."' AND
        j.periode = '$periode_cn' AND
        n.login = j.login AND
        n.statut='y' AND
        n.id_conteneur='$id_racine'
        )");

        $moy_moy = mysql_result($call_moy_moy, 0, "moyenne");
        if ($moy_moy != '') {
            $affiche_moy = $moy_moy;
        } else {
           $affiche_moy = "&nbsp;";
        }
        echo "<td bgcolor=\"$couleur_moy_cn\"><center><b>$affiche_moy</b></center></td>\n";
        $temp = "bgcolor=\"$couleur_fond\"";
    } else {
        $temp = '';
    }
    if (($is_posted=='bascule') and (($periode_cn == $k) and ($current_group["classe"]["ver_periode"]["all"][$k] >= 2))) {
        echo "<td><center><b>$affiche_moy</b></center></td>\n";
    } else {
        $call_moyenne_t[$k] = mysql_query("SELECT round(avg(n.note),1) moyenne FROM matieres_notes n, j_eleves_groupes j " .
                                    "WHERE (" .
                                    "n.id_groupe='" . $current_group["id"] ."' AND " .
                                    "n.login = j.login AND " .
                                    "n.statut='' AND " .
                                    "j.id_groupe = n.id_groupe AND " .
                                    "n.periode='$k' AND j.periode='$k'" .
                                    ")");
        $moyenne_t[$k] = mysql_result($call_moyenne_t[$k], 0, "moyenne");
        if ($moyenne_t[$k] != '') {
            echo "<td ".$temp."><center>$moyenne_t[$k]</center></td>\n";
        } else {
            echo "<td ".$temp.">&nbsp;</td>\n";
        }
    }
$k++;
}
?>
</tr>
</table>
<?php
if ($is_posted == 'bascule') {
?>
    <script type="text/javascript" language="javascript">
    <!--
    alert("Attention, les notes import�es ne sont pas encore enregistr�es dans la base GEPI. Vous devez confirmer l'importation (bouton \"enregistrer\") !");
    //-->
    </script>
<?php
}
?>
<input type="hidden" name="is_posted" value="yes" />
<input type="hidden" name="id_groupe" value="<?php echo "$id_groupe";?>" />
<input type="hidden" name="periode_cn" value="<?php echo "$periode_cn";?>" />
<?php
if (isset($retour_cn)) echo "<input type=\"hidden\" name=\"retour_cn\" value=\"".$retour_cn."\" />\n";
?>
<center><div id="fixe">
<input type="submit" value="Enregistrer" />
</div></center>

</form>

<script language='javascript' type='text/javascript'>
	// On donne le focus � la premi�re cellule lors du chargement de la page:
	if(document.getElementById('n110')){
		document.getElementById('n110').focus();
	}
	if(document.getElementById('n210')){
		document.getElementById('n210').focus();
	}
	if(document.getElementById('n310')){
		document.getElementById('n310').focus();
	}
</script>
<?php require("../lib/footer.inc.php");?>