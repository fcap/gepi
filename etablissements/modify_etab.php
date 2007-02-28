<?php
/*
 * Last modification  : 04/01/2006
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

$champs_vides = "non";
if (isset($is_posted) and ($is_posted == '1')) {
    if (($id != '') and ($nom_etab != '') and ($niveau_etab != '') and ($type_etab != '') and ($cp_etab != '') ) {
        $call_test = mysql_query("SELECT * FROM etablissements WHERE id = '$id'");
        $count = mysql_num_rows($call_test);


        if ($count == "0") {
            $register_etab = mysql_query("INSERT INTO etablissements SET id = '".$id."', nom='".$nom_etab."', niveau='".$niveau_etab."', type='".$type_etab."', cp= '".$cp_etab."', ville= '".$ville_etab."'");
            if (!$register_etab) {
                $msg = "Une erreur s'est produite lors de l'enregistrement du nouvel établissement.";
            } else {
                $msg = "Le nouvel établissement a bien été enregistré.";
            }
        } else {
            if ($nouvel_etab == 'no') {
                $register_etab = mysql_query("UPDATE etablissements SET nom='".$nom_etab."', niveau='".$niveau_etab."', type='".$type_etab."', cp= '".$cp_etab."', ville= '".$ville_etab."' WHERE id = '".$id."'");
                if (!$register_etab) {
                    $msg = "Une erreur s'est produite lors de la modification de l'établissement.";
                    } else {
                    $msg = "La fiche établissement a bien été modifiée.";
                }
            } else {
                $msg = "Un établissement ayant le même identifiant RNE existe déjà dans la base. Enregistrement impossible !";
                $id = '';
            }
        }
    } else {
        $msg = "Un ou plusieurs champs sont vides !";
        $champs_vides = "oui";
    }
}

//**************** EN-TETE *******************************
$titre_page = "Gestion des établissements | Ajouter, modifier un établissement";
require_once("../lib/header.inc");
//**************** FIN EN-TETE ***************************
?>
<p class=bold><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>
</p>

<?php
if ((isset($id)) and ($champs_vides == "non")) {
    $call_data = mysql_query("SELECT * FROM etablissements WHERE id = '$id'");
    $nom_etab = @mysql_result($call_data, 0, "nom");
    $niveau_etab = @mysql_result($call_data, 0, "niveau");
    $type_etab = @mysql_result($call_data, 0, "type");
    $cp_etab = @mysql_result($call_data, 0, "cp");
    $ville_etab = @mysql_result($call_data, 0, "ville");
}

if (!isset($nom_etab)) $nom_etab='';
if (!isset($niveau_etab)) $niveau_etab='aucun';
if (!isset($cp_etab)) $cp_etab='';
if (!isset($ville_etab)) $ville_etab='';
if (!isset($type_etab)) $type_etab='aucun';

?>
<form enctype="multipart/form-data" action="modify_etab.php" method="post">
<div class='norme'>
<table>
<?php
if (!(isset($id)) or ($id == '')) {
    echo "<tr><td>Identifiant de l'établissement : </td><td><input type=text size=30 name=id value = \"\" ></input>";
    echo "<input type='hidden' name='nouvel_etab' value='yes' /></td></tr>";
} else {
    echo "<tr><td>Identifiant RNE de l'établissement : $id</td>";
    echo "<td><input type='hidden' name='id' value='$id' />";
    echo "<input type='hidden' name='nouvel_etab' value='no' /></td></tr>";
}
?>
<!--div class='norme'-->
<tr><td>Nom de l'établissement : </td><td><input type='text' size='30' name='nom_etab' value='"<?php echo $nom_etab; ?>"'></input></td></tr>
<tr><td>Niveau : </td><td><select name='niveau_etab' size='1'>
<?php
foreach ($type_etablissement as $type => $nom_etab) {
    echo "<option value=\"".$type."\" ";
    if ($niveau_etab == $type) { echo " SELECTED ";}
    echo ">";
    if ($nom_etab != '') echo $nom_etab; else echo "(vide)";
    echo "</option>";
}
?>
</select></td></tr>
<tr><td>Type : </td><td><SELECT name=type_etab size=1>
<option value='public' <?php if ($type_etab == "public") { echo "SELECTED";}?>>Public</option>
<option value='prive' <?php if ($type_etab == "prive") { echo "SELECTED";}?>>Privé</option>
<option value='aucun' <?php if ($type_etab == "aucun") { echo "SELECTED";}?>>(vide)</option>
</select>
</td></tr>

<tr><td>Code postal : </td><td><input type='text' size='6' name='cp_etab' value='"<?php echo $cp_etab; ?>"'></input></td></tr>
<tr><td>Ville : </td><td><input type='text' size='20' name='ville_etab' value='"<?php echo $ville_etab; ?>"'></input></td></tr>
</table>
</div>
<input type='hidden' name='is_posted' value='1' />
<input type='submit' value='Enregistrer'></input>
</form>
<?php require("../lib/footer.inc.php");?>