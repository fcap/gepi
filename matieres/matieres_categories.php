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

$orderby = isset($_GET['orderby']) ? $_GET['orderby'] : (isset($_POST['orderby']) ? $_POST["orderby"] : 'priority,nom_complet');
if ($orderby != "nom_court" AND $orderby != "nom_complet" AND $orderby != "priority, nom_court") {
    $orderby = "priority,nom_complet";
}

$msg = null;

if (isset($_POST['action'])) {
	check_token();
    $error = false;
    if ($_POST['action'] == "add") {
        // On enregistre une nouvelle cat�gorie
        // On filtre un peu
        if (!is_numeric($_POST['priority'])) $_POST['priority'] = "0";
        // Le reste passera sans soucis, mais on coupe quand m�me si jamais c'est trop long
        if (strlen($_POST['nom_court']) > 250) $_POST['nom_court'] = substr($_POST['nom_court'], 0, 250);
        if (strlen($_POST['nom_complet']) > 250) $_POST['nom_complet'] = substr($_POST['nom_complet'], 0, 250);
        // On enregistre
        if ($_POST['nom_court'] == '') {
            $msg .= "Le nom court ne peut pas �tre vide.<br/>";
            $error = true;
            $res = false;
        }
        if (strtolower($_POST['nom_court']) == 'aucune') {
            $msg .= "Le nom court ne peut pas �tre 'Aucune'.<br/>";
            $error = true;
            $res = false;
        }
        if ($_POST['nom_complet'] == '') {
            $msg .= "L'intitul� ne peut pas �tre vide.<br/>";
            $error = true;
            $res = false;
        }

        if (!$error) {
            $res = mysql_query("INSERT INTO matieres_categories SET nom_court = '" . htmlentities($_POST['nom_court']) . "', nom_complet = '" . htmlentities($_POST['nom_complet']) . "', priority = '" . $_POST["priority"] . "'");
        }
        if (!$res) {
            $msg .= "Erreur lors de l'enregistrement de la nouvelle cat�gorie.</br>";
            echo mysql_error();
        }
    } elseif ($_POST['action'] == "edit") {
        // On met � jour une cat�gorie
        // On filtre un peu
        if (!is_numeric($_POST['priority'])) $_POST['priority'] = "0";
        if (!is_numeric($_POST['categorie_id'])) $_POST['categorie_id'] = "0";
        // Le reste passera sans soucis, mais on coupe quand m�me si jamais c'est trop long
        if (strlen($_POST['nom_court']) > 250) $_POST['nom_court'] = substr($_POST['nom_court'], 0, 250);
        if (strlen($_POST['nom_complet']) > 250) $_POST['nom_complet'] = substr($_POST['nom_complet'], 0, 250);

        if ($_POST['nom_court'] == '') {
            $msg .= "Le nom court ne peut pas �tre vide.<br/>";
            $error = true;
            $res = false;
        }
        if (strtolower($_POST['nom_court']) == 'aucune') {
            $msg .= "Le nom court ne peut pas �tre 'Aucune'.<br/>";
            $error = true;
            $res = false;
        }
        if ($_POST['nom_complet'] == '') {
            $msg .= "L'intitul� ne peut pas �tre vide.<br/>";
            $error = true;
            $res = false;
        }

        if (!$error) {
            // On enregistre
            $res = mysql_query("UPDATE matieres_categories SET nom_court = '" . htmlentities($_POST['nom_court']) . "', nom_complet = '" . htmlentities($_POST['nom_complet']) . "', priority = '" . $_POST["priority"] . "' WHERE id = '".$_POST['categorie_id']."'");
        }

        if (!$res) $msg .= "Erreur lors de la mise � jour de la cat�gorie.";
    } elseif ($_POST['action'] == "delete") {
        // On teste d'abord l'ID
        if (!is_numeric($_POST['categorie_id'])) {
            // Inutile d'en dire plus...
            $msg .= "Erreur.";
        } else {
            // On a un ID valide.
            // Si c'est l'ID 1, on ne supprime pas. C'est la cat�gorie par d�faut
            if ($_POST['categorie_id'] == 1) {
                $msg .= "Vous ne pouvez pas supprimer la cat�gorie par d�faut !";
            } else {

                // On teste l'utilisation de cette cat�gorie
                $res = mysql_query("SELECT matiere FROM matieres WHERE categorie_id = '" . $_POST['categorie_id'] ."'");
                $test = mysql_num_rows($res);

                $res2 = mysql_query("SELECT DISTINCT id_groupe, c.id, c.classe FROM j_groupes_classes jgc, classes c WHERE c.id=jgc.id_classe AND categorie_id='".$_POST['categorie_id']."'");
                $test2 = mysql_num_rows($res2);

                if ($test>0) {
                    // On a des entr�es... la cat�gorie a d�j� �t� associ�e � des mati�res, donc on ne la supprime pas.
					$liste_matieres_associees="";
					while($lig=mysql_fetch_object($res)) {
						if($liste_matieres_associees!='') {$liste_matieres_associees.=", ";}
						$liste_matieres_associees.="<a href='index.php' target='_blank'>".$lig->matiere."</a>";
					}
                    $msg .= "La cat�gorie n'a pas pu �tre supprim�e, car elle a d�j� �t� associ�e � des mati�res (<i>$liste_matieres_associees</i>).<br/>";
				}
                elseif ($test2>0) {
					$liste_classes_associees="";
					while($lig=mysql_fetch_object($res2)) {
						if($liste_classes_associees!='') {$liste_classes_associees.=", ";}
						$liste_classes_associees.="<a href='../groupes/edit_class.php?id_classe=$lig->id_classe' target='_blank'>".get_class_from_id($lig->id_classe)."</a>";
					}
                    $msg .= "La cat�gorie n'a pas pu �tre supprim�e, car elle a d�j� �t� associ�e � des enseignements pour des classes (<i>$liste_classes_associees</i>).<br/>";
                }
				else {
                    $res = mysql_query("DELETE FROM matieres_categories WHERE id = '" . $_POST['categorie_id']."'");
                    if (!$res) {
                        $msg .= "Erreur lors de la suppression de la cat�gorie.<br/>";
                    } else {
                        $msg .= "La cat�gorie a bien �t� supprim�e.<br/>";
                    }
                }
            }
        }
    }
}

//**************** EN-TETE **************************************
$titre_page = "Gestion des cat�gories de mati�res";
require_once("../lib/header.inc");
//**************** FIN EN-TETE **********************************

if (isset($_GET['action'])) {
    // On a une action : soit on ajoute soit on �dite soit on delete
    ?>
    <p class=bold><a href="matieres_categories.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>
    <?php
    if ($_GET['action'] == "add") {
        // On ajoute une cat�gorie
        // On affiche le formulaire d'ajout
        echo "<form enctype='multipart/form-data' action='matieres_categories.php' name='formulaire' method=post>";
		echo add_token_field();
        echo "<input type='hidden' name='action' value='add'>";
        echo "<p>Nom court (utilis� dans les outils de configuration) : <input type='text' name='nom_court'></p>";
        echo "<p>Intitul� complet (utilis� sur les documents officiels) : <input type='text' name='nom_complet'></p>";
        echo "<p>Priorit� d'affichage par d�faut : ";
        echo "<select name='priority' size='1'>";
        for ($i=0;$i<11;$i++) {
            echo "<option value='$i'>$i</option>";
        }
        echo "</select>";

        echo "<p>";
        echo "<input type='submit' value='Enregistrer'>";
        echo "</p>";
        echo "</form>";
    } elseif ($_GET['action'] == "edit") {
        // On �dite la cat�gorie existante
        if (!is_numeric($_GET['categorie_id'])) $_GET['categorie_id'] == 0;

        $res = mysql_query("SELECT id, nom_court, nom_complet, priority FROM matieres_categories WHERE id = '" . $_GET['categorie_id'] . "'");
        $current_cat = mysql_fetch_array($res, MYSQL_ASSOC);

        if ($current_cat) {
			if($current_cat["nom_court"]=='Aucune') {
				echo "<p style='color:red'>ANOMALIE&nbsp;: Il ne devrait pas exister de cat�gorie intitul�e 'Aucune'.<br />Voir <a href='http://www.sylogix.org/wiki/gepi/Enseignement_invisible'>http://www.sylogix.org/wiki/gepi/Enseignement_invisible</a> et <a href='http://www.sylogix.org/wiki/gepi/Suppr_Cat_Aucune'>http://www.sylogix.org/wiki/gepi/Suppr_Cat_Aucune</a> pour des explications</p>\n";
			}
            echo "<form enctype='multipart/form-data' action='matieres_categories.php' name='formulaire' method=post>";
			echo add_token_field();
            echo "<input type='hidden' name='action' value='edit'>";
            echo "<input type='hidden' name='categorie_id' value='".$current_cat["id"] . "'>";
            echo "<p>Nom court (utilis� dans les outils de configuration) : <input type='text' name='nom_court' value='".html_entity_decode_all_version($current_cat["nom_court"]) ."' /></p>";
            echo "<p>Intitul� complet (utilis� sur les documents officiels) : <input type='text' name='nom_complet' value='".html_entity_decode_all_version($current_cat["nom_complet"]) ."' /></p>";
            echo "<p>Priorit� d'affichage par d�faut : ";
            echo "<select name='priority' size='1'>";
            for ($i=0;$i<11;$i++) {
                echo "<option value='$i'";
                if ($current_cat["priority"] == $i) echo " SELECTED";
                echo ">$i</option>";
            }
            echo "</select>";

            echo "<p>";
            echo "<input type='submit' value='Enregistrer'>";
            echo "</p>";
            echo "</form>";
        }

    }



} else {
    // Pas d'action. On affiche la liste des rubriques
    ?>
    <p class=bold><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a> | <a href="matieres_categories.php?action=add">Ajouter une cat�gorie</a></p>
    <p>Remarque : la cat�gorie par d�faut ne peut pas �tre supprim�e. Elle est automatiquement associ�e aux mati�res existantes et aux nouvelles mati�res, et pour tous les groupes. Vous pouvez la renommer (Autres, Hors cat�gories, etc.), mais laissez toujours un nom g�n�rique.</p>

    <table class='boireaus' width='100%' border='1' cellpadding='5' summary='Tableau des cat�gories'>
<tr>
    <th><p class='bold'><a href='./matieres_categories.php?orderby=nom_court'>Nom court</a></p></th>
    <th><p class='bold'><a href='./matieres_categories.php?orderby=m.nom_complet'>Intitul� complet</a></p></th>
    <th><p class='bold'><a href='./matieres_categories.php?orderby=m.priority,m.nom_complet'>Ordre d'affichage<br />par d�faut</a></p></th>
    <th><p class='bold'>Supprimer</p></th>
</tr>
    <?php
	$temoin_anomalie_categ_Aucune='n';
	$alt=1;
    $res = mysql_query("SELECT id, nom_court, nom_complet, priority FROM matieres_categories ORDER BY $orderby");
    while ($current_cat = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$alt=$alt*(-1);
        echo "<tr class='lig$alt white_hover'>\n";
        echo "<td><a href='matieres_categories.php?action=edit&categorie_id=".$current_cat["id"]."'>".html_entity_decode_all_version($current_cat["nom_court"])."</a></td>\n";
        echo "<td>".html_entity_decode_all_version($current_cat["nom_complet"])."</td>\n";
        echo "<td>".$current_cat["priority"]."</td>\n";
        echo "<td>";
        if ($current_cat["id"] != "1") {
            echo "<form enctype='multipart/form-data' action='matieres_categories.php' name='formulaire' method=post>\n";
			echo add_token_field();
            echo "<input type='hidden' name='action' value='delete' />\n";
            echo "<input type='hidden' name='categorie_id' value='".$current_cat["id"]."' />\n";
            echo "<input type='submit' value='Supprimer' />\n</form>\n";
        } else {
            echo "Cat�gorie par d�faut (suppression impossible)";
        }
		echo "</td>\n";
        echo "</tr>\n";
		if($current_cat["nom_court"]=='Aucune') {$temoin_anomalie_categ_Aucune='y';}
    }
    echo "</table>\n";
	if($temoin_anomalie_categ_Aucune=='y') {
		echo "<p style='color:red'>ANOMALIE&nbsp;: Il ne devrait pas exister de cat�gorie intitul�e 'Aucune'.<br />Voir <a href='http://www.sylogix.org/wiki/gepi/Enseignement_invisible'>http://www.sylogix.org/wiki/gepi/Enseignement_invisible</a> et <a href='http://www.sylogix.org/wiki/gepi/Suppr_Cat_Aucune'>http://www.sylogix.org/wiki/gepi/Suppr_Cat_Aucune</a> pour des explications</p>\n";
	}
}
require("../lib/footer.inc.php");
?>