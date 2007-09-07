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
include "../lib/periodes.inc.php";


$call_classe = mysql_query("SELECT classe FROM classes WHERE id = '$id_classe'");
$classe = mysql_result($call_classe, "0", "classe");

if (isset($is_posted) and ($is_posted == 1)) {
    $call_eleves = mysql_query("SELECT login FROM eleves ORDER BY nom, prenom");
    $nombreligne = mysql_num_rows($call_eleves);
    $k = '0';
    while ($k < $nombreligne) {
        $pb = 'no';
        $login_eleve = mysql_result($call_eleves, $k, 'login');

        $temp = $login_eleve."_item";

        $item_login = isset($_POST[$temp])?$_POST[$temp]:NULL;

        $i="1";

        while ($i < $nb_periode) {

            $temp = "ajout_".$login_eleve."_".$i;

            $reg_login[$i] = isset($_POST[$temp])?$_POST[$temp]:NULL;

            $i++;

        }

        if ($item_login == 'yes') {

            $reg_data = 'yes';

            $regime_login = "regime_".$login_eleve;

            $doublant_login = "doublant_".$login_eleve;

            $reg_regime = isset($_POST[$regime_login])?$_POST[$regime_login]:NULL;

            $reg_doublant = isset($_POST[$doublant_login])?$_POST[$doublant_login]:NULL;

            $call_regime = mysql_query("SELECT * FROM j_eleves_regime WHERE login='$login_eleve'");

            $nb_test_regime = mysql_num_rows($call_regime);

            if ($nb_test_regime == 0) {

                $reg_data = mysql_query("INSERT INTO j_eleves_regime SET login='$login_eleve', doublant='$reg_doublant', regime='$reg_regime'");

                if (!($reg_data)) $reg_ok = 'no';

            } else {

                $reg_data = mysql_query("UPDATE j_eleves_regime SET doublant = '$reg_doublant', regime = '$reg_regime'  WHERE login='$login_eleve'");

                if (!($reg_data)) $reg_ok = 'no';

            }

        }

        $i="1";

        while ($i < $nb_periode) {

            if ($reg_login[$i] == 'yes') {

                if (mysql_num_rows(mysql_query("SELECT login FROM j_eleves_classes WHERE

                (login = '$login_eleve' and

                id_classe = '$id_classe' and

                periode = '$i')")) == 0) {

                    $call_data = mysql_query("INSERT INTO j_eleves_classes VALUES('$login_eleve', '$id_classe', $i, '0')");

                    if (!($reg_data)) $reg_ok = 'no';

                }

            }

            $i++;

        }

        $k++;

    }

    if (($reg_data) == 'yes') {

      $msg = "L'enregistrement des donn�es a �t� correctement effectu� !";

    } else {

      $msg = "Il y a eu un probl�me lors de l'enregistrement !";

    }

}

// =================================
// AJOUT: boireaus
$sql="SELECT id, classe FROM classes ORDER BY classe";
$res_class_tmp=mysql_query($sql);
if(mysql_num_rows($res_class_tmp)>0){
    $id_class_prec=0;
    $id_class_suiv=0;
    $temoin_tmp=0;
    while($lig_class_tmp=mysql_fetch_object($res_class_tmp)){
        if($lig_class_tmp->id==$id_classe){
            $temoin_tmp=1;
            if($lig_class_tmp=mysql_fetch_object($res_class_tmp)){
                $id_class_suiv=$lig_class_tmp->id;
            }
            else{
                $id_class_suiv=0;
            }
        }
        if($temoin_tmp==0){
            $id_class_prec=$lig_class_tmp->id;
        }
    }
}
// =================================


//**************** EN-TETE **************************************
$titre_page = "Gestion des classes | Ajout d'�l�ves � une classe";
require_once("../lib/header.inc");
//**************** FIN EN-TETE **********************************

?>
<script type='text/javascript' language='javascript'>
function CochePeriode() {
    nbParams = CochePeriode.arguments.length;
    for (var i=0;i<nbParams;i++) {
        theElement = CochePeriode.arguments[i];
        if (document.formulaire.elements[theElement])
            document.formulaire.elements[theElement].checked = true;
    }
}

function DecochePeriode() {
    nbParams = DecochePeriode.arguments.length;
    for (var i=0;i<nbParams;i++) {
        theElement = DecochePeriode.arguments[i];
        if (document.formulaire.elements[theElement])
            document.formulaire.elements[theElement].checked = false;
    }
}
</script>

<form enctype="multipart/form-data" action="classes_ajout.php" name="formulaire" method=post>

<p class=bold>
<a href="classes_const.php?id_classe=<?php echo $id_classe;?>"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour � la page de gestion des �l�ves</a>

<?php
if($id_class_prec!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_prec'>Classe pr�c�dente</a>";}
if($id_class_suiv!=0){echo " | <a href='".$_SERVER['PHP_SELF']."?id_classe=$id_class_suiv'>Classe suivante</a>";}
?>
</p>
<p><b>Ajout d'�l�ves � la classe de <?php echo $classe; ?></b><br />Liste des �l�ves non affect�s � une classe :</p>

<?php

$call_eleves = mysql_query("SELECT * FROM eleves ORDER BY nom, prenom");

$nombreligne = mysql_num_rows($call_eleves);

if ($nombreligne == '0') {

    echo "<p>Il n'y a pas d'�l�ves actuellement dans la base.</p>\n";

} else {

    $eleves_non_affectes = 'no';

    echo "<table border='1' cellpadding='5'>\n<tr>\n<td><p><b>Nom Pr�nom </b></p></td>\n<td><p><b>R�gime</b></p></td>\n<td><p><b>Redoublant</b></p></td>\n";

    $i="1";

        while ($i < $nb_periode) {

        echo "<td><p><b>Ajouter per. $i</b></p></td>\n";

        $i++;

    }

	//echo "<td><b><center>cocher / d�cocher <br/>toutes p�riodes</center></b></p></td>\n";
	echo "<td><p style='font-weight:bold; text-align:center;'>cocher / d�cocher <br />toutes p�riodes</p></td>\n";

    echo "</tr>";

    $k = '0';

    While ($k < $nombreligne) {

        $login_eleve = mysql_result($call_eleves, $k, 'login');

        $nom_eleve = mysql_result($call_eleves, $k, 'nom');

        $prenom_eleve = mysql_result($call_eleves, $k, 'prenom');

        $call_regime = mysql_query("SELECT * FROM j_eleves_regime WHERE login='$login_eleve'");

        $doublant = @mysql_result($call_regime, 0, 'doublant');

        if ($doublant == '') {$doublant = '-';}

        $regime = @mysql_result($call_regime, 0, 'regime');

        if ($regime == '') {$regime = 'd/p';}

        $i="1";

        while ($i < $nb_periode) {

            $ajout_login[$i] = "ajout_".$login_eleve."_".$i;

            $i++;

        }

        $item_login = $login_eleve."_item";

        $regime_login = "regime_".$login_eleve;

        $doublant_login = "doublant_".$login_eleve;

        $inserer_ligne = 'no';

        $call_data = mysql_query("SELECT id_classe FROM j_eleves_classes WHERE login = '$login_eleve'");

        $test = mysql_num_rows($call_data);

        if ($test == 0) {

            $inserer_ligne = 'yes';

            $eleves_non_affectes = 'yes';

            $i="1";

            while ($i < $nb_periode) {



                $nom_classe[$i] = 'vide';

                $i++;

            }

        } else {

            $id_classe_eleve = mysql_result($call_data, 0, "id_classe");

            $query_periode_max = mysql_query("SELECT * FROM periodes WHERE id_classe = '$id_classe_eleve'");

            $periode_max = mysql_num_rows($query_periode_max) + 1 ;

            // si l'�l�ve est d�j� dans une classe dont le nombre de p�riodes est diff�rent du nombre de p�riodes de la classe selctionn�e, on ne fait rien. Dans la cas contraire :

            if ($periode_max == $nb_periode) {

                $i = '1';

                while ($i < $nb_periode) {

                    $call_data2 = mysql_query("SELECT id_classe FROM j_eleves_classes WHERE (login = '$login_eleve' and periode = '$i')");

                    $test2 = mysql_num_rows($call_data2);

                    if ($test2 == 0) {

                        // l'�l�ve n'est affect� � aucune classe pour cette p�riode

                        $inserer_ligne = 'yes';

                        $eleves_non_affectes = 'yes';

                        $nom_classe[$i] = 'vide';

                    } else {

                        $idd_classe = mysql_result($call_data2, 0, "id_classe");

                        $call_classe = mysql_query("SELECT classe FROM classes WHERE (id = '$idd_classe')");

                        $nom_classe[$i] = mysql_result($call_classe, 0, "classe");

                    }

                    $i++;

                }

            }


        }

        if ($inserer_ligne == 'yes') {

            echo "<tr><td>\n";
            echo "<input type='hidden' name=$item_login value='yes' />\n";

            //echo "<tr><td><p>$nom_eleve $prenom_eleve</p></td>\n";
            echo "<p>$nom_eleve $prenom_eleve</p></td>\n";

            echo "<td><p>Ext.|Int.|D/P|I-ext.<br /><input type='radio' name='$regime_login' value='ext.'";

            if ($regime == 'ext.') { echo " checked ";}

            echo " />\n";

            echo "&nbsp;&nbsp;&nbsp;<input type=radio name='$regime_login' value='int.'";

            if ($regime == 'int.') { echo " checked ";}

            echo " />\n";

            echo "&nbsp;&nbsp;&nbsp;<input type=radio name='$regime_login' value='d/p' ";

            if ($regime == 'd/p') { echo " checked ";}

            echo " />\n";

            echo "&nbsp;&nbsp;&nbsp;<input type=radio name='$regime_login' value='i-e'";

            if ($regime == 'i-e') { echo " checked ";}

            echo " />\n";



            //echo "</p></td><td><p><center><INPUT TYPE=CHECKBOX NAME='$doublant_login' VALUE='R'";
            echo "</p></td>\n<td><p align='center'><input type='checkbox' name='$doublant_login' value='R'";

            if ($doublant == 'R') { echo " checked ";}

            echo " />";



            //echo "</center></p></td>";
            echo "</p></td>\n";

            $i="1";

            while ($i < $nb_periode) {

                echo "<td><p align='center'>";

                if ($nom_classe[$i] == 'vide') {

                    echo "<input type='checkbox' name='$ajout_login[$i]' value='yes' />";

                } else {



                    echo "$nom_classe[$i]";

                }

                echo "</p></td>\n";

                $i++;

            }

			$elementlist = null;
	        for ($i=1;$i<=sizeof($ajout_login);$i++) {
			  //echo $ajout_login[$i]."<br>";
			  $elementlist .= "'".$ajout_login[$i]."',";
			}
	        $elementlist = substr($elementlist, 0, -1);

			echo "<td><center><a href=\"javascript:CochePeriode($elementlist)\"><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a> / <a href=\"javascript:DecochePeriode($elementlist)\"><img src='../images/disabled.png' width='15' height='15' alt='Tout d�cocher' /></a></center></td>\n";

			echo "</tr>\n";

        }

        $k++;

    }

    echo "</table>\n";

    if ($eleves_non_affectes == 'no') {

        echo "<p>Il n'y a aucun �l�ve de disponible � ajouter !";

    } else {

        echo "<input type=submit value=Enregistrer /><br />\n";

    }

}

?>

<input type=hidden name=id_classe value=<?php echo $id_classe;?> />

<input type=hidden name=is_posted value=1 />

</form>
<?php require("../lib/footer.inc.php");?>