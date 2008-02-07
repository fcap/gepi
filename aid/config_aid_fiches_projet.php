<?php
/*
 * @version: $Id: config_aid.php 1441 2008-02-01 18:21:17Z delineau $
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
// ========== Iniialisation des variables ==========
$is_posted = isset($_POST["is_posted"]) ? $_POST["is_posted"] : NULL;
// ========== fin initialisation ===================

$requete = "select * from droits_aid where (id!='cpe_peut_modifier' and id!='prof_peut_modifier' and id!='eleve_peut_modifier' and id != 'fiche_publique' and id!='affiche_adresse1' and id!='en_construction' and id!='nom' and id!='numero') order by id";
if (isset($is_posted) and ($is_posted == "1")) {
    $res = mysql_query($requete);
    $nb_lignes = mysql_num_rows($res);
    $i = 0;
    while ($i < $nb_lignes) {
        $id = mysql_result($res,$i,"id");
        if ($_POST["description_".$id]=="") $_POST["description_".$id] = "A pr�ciser";
        if (!(isset($_POST["public_".$id]))) $_POST["public_".$id] = 'F';
        if (!(isset($_POST["professeur_".$id]))) $_POST["professeur_".$id] = 'F';
        if (!(isset($_POST["cpe_".$id]))) $_POST["cpe_".$id] = 'F';
        if (!(isset($_POST["eleve_".$id]))) $_POST["eleve_".$id] = 'F';
        if (!(isset($_POST["statut_".$id]))) $_POST["statut_".$id]= '0';
        $sql = mysql_query("update droits_aid set
        public = '".$_POST["public_".$id]."',
        professeur = '".$_POST["professeur_".$id]."',
        cpe = '".$_POST["cpe_".$id]."',
        eleve = '".$_POST["eleve_".$id]."',
        description = '".$_POST["description_".$id]."',
        statut = '".$_POST["statut_".$id]."'
        where id = '".$id."'");
        $i++;
        }
}


//**************** EN-TETE *********************
$titre_page = "Configuration des fiches projet";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
?>
<form enctype="multipart/form-data" name="formulaire" action="config_aid_fiches_projet.php" method="post">
<p class="bold"><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>
<input type="submit" value="Enregistrer" /><br />
<?php

echo "<p>le tableau suivant permet de fixer les droits sur les diff�rents champs des fiches projet.";
echo "<br><br />Remarques :
<ul>
<li>Quand un champ est <b>actif</b>, il est visible dans l'interface priv�e de GEPI (utilisateur connect�) par les administrateurs, les professeurs, les CPE, les �l�ves et les responsables.</li>
<li>Quand un champ n'est pas <b>actif</b>, cela signifie qu'il n'est pas utilis� dans GEPI.</li>
<li>Pour qu'un champ soit visible dans l'interface publique, il est n�cessaire de cocher �galement la case correspondante dans le tableau.</li>
</ul>";
echo "<table border='1' cellpadding='5' class='boireaus'>";
echo "<tr><th><b>Champ de la fiche projet</b></th>
<th><span class='small'>Libell� du champ</span></th>
<th><span class='small'>Le champ est visible dans l'interface publique</span></th>
<th><span class='small'>Les professeurs peuvent modifier ce champ</span></th>
<th><span class='small'>Les C.P.E peuvent modifier ce champ</span></th>
<th><span class='small'>Les �l�ves peuvent modifier ce champ</span></th>
<th><span class='small'>Le champ est actif</span></th>
</tr>";
$res = mysql_query($requete);
$nb_lignes = mysql_num_rows($res);
$i = 0;
$alt=1;
while ($i < $nb_lignes) {
    $id = mysql_result($res,$i,"id");
    $public = mysql_result($res,$i,"public");
    $professeur = mysql_result($res,$i,"professeur");
    $cpe = mysql_result($res,$i,"cpe");
    $eleve = mysql_result($res,$i,"eleve");
    $responsable = mysql_result($res,$i,"responsable");
    $description = mysql_result($res,$i,"description");
    $_statut = mysql_result($res,$i,"statut");
    $alt=$alt*(-1);
    echo "<tr class='lig$alt'>";
    if (($id!="perso1") and ($id!="perso2") and ($id!="perso3"))
        echo "<td>".$description."</td>\n";
    else
        echo "<td>".$id."</td>\n";
    if (($id=="perso1") or ($id=="perso2") or ($id=="perso3")) {
        echo "<td><input type=\"text\" name=\"description_".$id."\" value=\"".htmlentities($description)."\" size=\"20\" /></td>\n";
    } else {
        echo "<td><input type=\"hidden\" name=\"description_".$id."\" value=\"".htmlentities($description)."\" /> - </td>\n";

    }

    echo "<td><input type=\"checkbox\" name=\"public_".$id."\" value=\"V\" ";
    if ($public=='V') echo " checked ";
    echo "/></td>\n";

    echo "<td><input type=\"checkbox\" name=\"professeur_".$id."\" value=\"V\" ";
    if ($professeur=='V') echo " checked ";
    echo "/></td>\n";

    echo "<td><input type=\"checkbox\" name=\"cpe_".$id."\" value=\"V\" ";
    if ($cpe=='V') echo " checked ";
    echo "/></td>\n";

    echo "<td><input type=\"checkbox\" name=\"eleve_".$id."\" value=\"V\" ";
    if ($eleve=='V') echo " checked ";
    echo "/></td>\n";

    echo "<td><input type=\"checkbox\" name=\"statut_".$id."\" value=\"1\" ";
    if ($_statut=='1') echo " checked ";
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