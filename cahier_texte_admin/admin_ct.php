<?php
/*
 * @version: $Id$
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
// Check access
if (!checkAccess()) {
    header("Location: ../logout.php?auto=1");
    die();
}
// Suppresion d'un ou plusieurs cahiers de texte
if (isset($_POST['sup_ct'])) {
  $query = sql_query("SELECT DISTINCT id_groupe, id_login FROM ct_entry ORDER BY id_groupe");
  $msg = '';
  for ($i=0; ($row=sql_row($query,$i)); $i++) {
      $id_groupe = $row[0];
      $id_prop = $row[1];
      $temp = "sup".$id_groupe."_".$id_prop;
      if (isset($_POST[$temp])) {
         $error = 'no';
         $appel_ct = sql_query("SELECT id_ct  FROM ct_entry WHERE (id_groupe='".$id_groupe."' and id_login = '".$id_prop."')");
         if (($appel_ct) and (sql_count($appel_ct)!=0)) {
           for ($k=0; ($row2 = sql_row($appel_ct,$k)); $k++) {
             $id_ctexte = $row2[0];
             $appel_doc = sql_query("select emplacement from ct_documents where id_ct='".$id_ctexte."'");
             for ($j=0; ($row3 = sql_row($appel_doc,$j)); $j++) {
                $empl = $row3[0];
                if ($empl != -1) $del = @unlink($empl);
             }
             $del_doc = sql_query("delete from ct_documents where id_ct='".$id_ctexte."'");
             if (!($del_doc)) $error = 'yes';
             $del_ct = sql_query("delete from ct_entry where id_ct = '".$id_ctexte."'");
             if (!($del_ct)) $error = 'yes';
           }
           if ($error == 'no') {
              $msg = "Suppression des notices dans ct_entry r�ussie";
           } else {
              $msg = "Il y a eu un probl�me lors de la suppression des notices dans ct_entry.";
           }
         } else {
           $msg = "Pas de notice � supprimer dans ct_entry.";
         }
      }

   }
  $query = sql_query("SELECT DISTINCT id_groupe, id_login FROM ct_devoirs_entry ORDER BY id_groupe");
  for ($i=0; ($row=sql_row($query,$i)); $i++) {
      $id_groupe = $row[0];
      $id_prop = $row[1];
      $temp = "sup".$id_groupe."_".$id_prop;
      if (isset($_POST[$temp])) {
         $error = 'no';
         $del_ct_devoirs = sql_query("delete  FROM ct_devoirs_entry WHERE (id_groupe='".$id_groupe."' and id_login = '".$id_prop."')");
         if (!($del_ct_devoirs)) $error = 'yes';
         if ($error == 'no') {
             $msg .= "<br>Suppression des notices dans ct_devoirs_entry r�ussie";
         } else {
             $msg .= "<br>Il y a eu un probl�me lors de la suppression des notices dans ct_devoirs_entry.";
         }
      } else {
           $msg .= "<br>Pas de notice � supprimer dans ct_devoirs_entry.";
      }

   }

}

// Modification d'un cahier de texte - Etape 2
if (isset($_POST['action'])) {
  $id_groupe = $_POST['id_groupe'];
  $id_prop = $_POST['id_prop'];

  if ($_POST['action'] == 'change_groupe') {
  	 $id_former_group = $_POST['id_former_group'];
     $sql1 = sql_query("UPDATE ct_entry SET id_groupe='".$id_groupe."' WHERE (id_groupe='".$id_former_group."' and id_login='".$id_prop."')");
     $sql2 = sql_query("UPDATE ct_devoirs_entry SET id_groupe='".$id_groupe."' WHERE (id_groupe='".$id_former_group."' and id_login='".$id_prop."')");
     if (($sql1) and ($sql2)) {
        $msg = "Le changement de groupe a �t� effectu�.";
     } else {
        $msg = "Il y a eu un probl�me lors du changement de groupe.";
     }
  }

  if ($_POST['action'] == 'change_prop') {
     $sql1 = sql_query("UPDATE ct_entry SET id_login='".$id_prop."' WHERE (id_groupe='".$id_groupe."')");
     $sql2 = sql_query("UPDATE ct_entry SET id_login='".$id_prop."' WHERE (id_groupe='".$id_groupe."')");
     if (($sql1) and ($sql2)) {
        $msg = "Le changement de propri�taire a �t� effectu�.";
     } else {
        $msg = "Il y a eu un probl�me lors du changement de propri�taire.";
     }
  }


}

// header
$titre_page = "Administration des cahiers de texte";
require_once("../lib/header.inc");


// Modification d'un cahier de texte - Etape 1
if (isset($_GET['action'])) {
  echo "<p class='bold'><a href=\"admin_ct.php\"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>";
  $id_groupe = $_GET['id_groupe'];
  $id_prop = $_GET['id_prop'];
  $classes = null;
  $nom_groupe = sql_query1("select name from groupes where id = '".$id_groupe."'");
  if ($nom_groupe == "-1") {
     $nom_groupe = "<font color='red'>".$id_groupe." : groupe inexistant</font>";
  } else {
  	  $get_classes = mysql_query("SELECT c.classe FROM classes c, j_groupes_classes jc WHERE (c.id = jc.id_classe and jc.id_groupe = '" . $id_groupe . "')");
      $nb_classes = mysql_num_rows($get_classes);
      for ($c=0;$c<$nb_classes;$c++) {
      	$current_classe = mysql_result($get_classes, $c, "classe");
      	$classes .= $current_classe;
      	if ($c+1<$nb_classes) $classes .= ", ";
      }
  }
  $sql_prof = sql_query("select nom, prenom from utilisateurs where login = '".$id_prop."'");
  if (!($sql_prof)) {
     $nom_prof = "<font color='red'>".$id_prop." : utilisateur inexistant</font>";
  } else {
     $row_prof=sql_row($sql_prof,0);
     $nom_prof = $row_prof[1]." ".$row_prof[0];
         $test_groupe_prof = sql_query("select login from j_groupes_professeurs WHERE (id_groupe='".$id_groupe."' and login = '".$id_prop."')");
         if (sql_count($test_groupe_prof) == 0) $nom_prof = "<font color='red'>".$nom_prof." : <br />Ce professeur n'enseigne pas dans ce groupe</font>";
  }

  if ($_GET['action'] == 'modif_groupe') {
     echo "<form action=\"admin_ct.php\" name=\"formulaire2\" method=\"post\">";
     echo "<H2>Cahier de texte - Modification du groupe</h2>";
     echo "<p>Groupe actuel : <b>".$nom_groupe."</b><br />";
     echo "Dans la (les) classe(s) de : <b>".$classes."</b><br />";
     echo "Propri�taire actuel : <b>".$nom_prof."</b></p>";
     echo "<p>Vous pouvez attribuer � ce cahier de texte un nouveau groupe.</p>";
     echo "<p>Choisissez la nouvelle classe : </p>";

     $sql_groupe = sql_query("select g.id, g.name from groupes g, classes c, j_groupes_classes jc " .
     		"WHERE (".
     		"c.id = jc.id_classe and ".
			"jc.id_groupe = g.id) " .
			"order by c.classe");


     echo "<select name=\"id_groupe\" size=\"1\">";
     for ($i=0; ($row=sql_row($sql_groupe,$i)); $i++) {
        $new_id_groupe = $row[0];
        $nom_groupe = $row[1];
        $classes = null;
        $get_classes = mysql_query("SELECT c.classe FROM classes c, j_groupes_classes jc WHERE (c.id = jc.id_classe and jc.id_groupe = '" . $new_id_groupe . "')");
	    $nb_classes = mysql_num_rows($get_classes);
	      for ($c=0;$c<$nb_classes;$c++) {
	      	$current_classe = mysql_result($get_classes, $c, "classe");
	      	$classes .= $current_classe;
	      	if ($c+1<$nb_classes) $classes .= ", ";
	      }
        echo "<option value=\"".$new_id_groupe."\">".$classes." | " . $nom_groupe ."</option>";
     }
     echo "</select>";
     echo "<input type=\"hidden\" name=\"id_prop\" value=\"".$id_prop."\" />";
     echo "<input type=\"hidden\" name=\"id_former_group\" value=\"".$id_groupe."\" />";
     echo "<input type=\"hidden\" name=\"action\" value=\"change_groupe\" />";
     echo "<br /><input type=\"submit\" value=\"Enregistrer\" />";
     echo "</form>";

  }

  if ($_GET['action'] == 'modif_prop') {

     echo "<form action=\"admin_ct.php\" name=\"formulaire2\" method=\"post\">";
     echo "<H2>Cahier de texte - Modification du propri�taire</h2>";
     echo "<p>Groupe actuel : <b>".$nom_groupe."</b><br />";
     echo "Classe(s) de : <b>".$classes."</b><br />";
     echo "Propri�taire actuel : <b>".$nom_prof."</b></p>";
     echo "<p>Vous pouvez attribuer � ce cahier de texte un nouveau propri�taire.</p>";
     echo "<p>Choisissez le nouveau propri�taire : </p>";
     $sql_matiere = sql_query("select DISTINCT u.login, u.nom, u.prenom from utilisateurs u, matieres m, j_groupes_professeurs j where " .
     		"(u.login=j.login and " .
     		"j.id_groupe='".$id_groupe."'" .
			") order by 'u.nom, u.prenom'");
     echo "<select name=\"id_prop\" size=\"1\">";
     for ($i=0; ($row=sql_row($sql_matiere,$i)); $i++) {
        $id_prop = $row[0];
        $nom_prop = $row[1];
        $prenom_prop = $row[2];
        echo "<option value=\"".$id_prop."\">".$nom_prop." ".$prenom_prop."</option>";
     }
     echo "</select>";
     echo "<input type=\"hidden\" name=\"id_groupe\" value=\"".$id_groupe."\" />";
     echo "<input type=\"hidden\" name=\"action\" value=\"change_prop\" />";
     echo "<br /><input type=\"submit\" value=\"Enregistrer\" />";
     echo "</form>";

  }
}

if (!(isset($_GET['action']))) {
  // Affichage du tableau complet
  ?>
  <p class='bold'><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>
  <H2>Administration des cahiers de texte</h2>
  <p>Le tableau ci-dessous pr�sentent l'ensemble des cahiers de texte actuellement en ligne. Les probl�mes sont signal�s en rouge.
  <br />Vous pouvez modifier le groupe ou le propri�taire d'un cahier de texte en cliquant sur le lien correspondant.
  <br />Vous pouvez �galement supprimer d�finitivement un cahier de texte (notices et documents joints).</p>


  <form action="admin_ct.php" name="formulaire1" method="post">
  <table border="1"><tr valign='center' align='center'>
  <td><b><a href='admin_ct.php?order_by=jc.id_classe,jm.id_matiere'>Classe(s)</a></b></td>
  <td><b><a href='admin_ct.php?order_by=jm.id_matiere,jc.id_classe'>Groupe</a></b></td>
  <td><b><a href='admin_ct.php?order_by=ct.id_login,jc.id_classe,jm.id_matiere'>Propri�taire</a></b></td>
  <td><b>Nombre<br />de notices</b></td>
  <td><b>Nombre<br />de notices<br />"devoirs"</b></td>
  <td><b>Action</b></td><td><b><input type="submit" name="sup_ct" value="Suppression" onclick="return confirmlink(this, 'La suppression d\'un cahier de texte est d�finitive. Les notices ainsi que les documents joints seront supprim�s. Etes-vous s�r de vouloir continuer ?', 'Confirmation de la suppression')" /></b></td></tr>

  <?php
  if (!isset($_GET['order_by'])) {
     $order_by = "jc.id_classe,jm.id_matiere";
  } else {
     $order_by = $_GET['order_by'];
  }

  $query = sql_query("SELECT DISTINCT ct.id_groupe, ct.id_login FROM ct_entry ct, j_groupes_classes jc, j_groupes_matieres jm WHERE (jc.id_groupe = ct.id_groupe AND jm.id_groupe = ct.id_groupe) ORDER BY ".$order_by);
  for ($i=0; ($row=sql_row($query,$i)); $i++) {
      $id_groupe = $row[0];
      $id_prop = $row[1];
      $nom_groupe = sql_query1("select name from groupes where id = '".$id_groupe."'");
      $nom_matiere = sql_query1("select m.nom_complet from matieres m, j_groupes_matieres jm where (jm.id_groupe = '".$id_groupe."' AND m.matiere = jm.id_matiere)");
      $get_classes = mysql_query("SELECT c.classe FROM classes c, j_groupes_classes jc WHERE (c.id = jc.id_classe and jc.id_groupe = '" . $id_groupe . "')");
      $nb_classes = mysql_num_rows($get_classes);
      $classes = null;
      for ($c=0;$c<$nb_classes;$c++) {
      	$current_classe = mysql_result($get_classes, $c, "classe");
      	$classes .= $current_classe;
      	if ($c+1<$nb_classes) $classes .= ", ";
      }

      if ($nom_groupe == "-1") $nom_groupe = "<font color='red'>Groupe inexistant</font>";
      $sql_prof = sql_query("select nom, prenom from utilisateurs where login = '".$id_prop."'");
      if (!($sql_prof)) {
         $nom_prof = "<font color='red'>".$id_prop." : utilisateur inexistant</font>";
      } else {
         $row_prof=sql_row($sql_prof,0);
         $nom_prof = $row_prof[1]." ".$row_prof[0];
         $test_groupe_prof = sql_query("select login from j_groupes_professeurs WHERE (id_groupe='".$id_groupe."' and login = '".$id_prop."')");
         if (sql_count($test_groupe_prof) == 0) $nom_prof = "<font color='red'>".$nom_prof." : <br />Ce professeur n'enseigne pas dans ce groupe</font>";
      }
      // Nombre de notices de chaque utilisateurs
      $nb_ct = sql_count(sql_query("select 1=1 FROM ct_entry WHERE (id_groupe='".$id_groupe."' and id_login='".$id_prop."') "));

      // Nombre de notices devoirs de haque utilisateurs
      $nb_ct_devoirs = sql_count(sql_query("select 1=1 FROM ct_devoirs_entry WHERE (id_groupe='".$id_groupe."' and id_login='".$id_prop."') "));

      // Affichage des lignes
      echo "<tr><td>".$classes."</td>";
      echo "<td><a href='admin_ct.php?id_groupe=".$id_groupe."&id_prop=".$id_prop."&action=modif_groupe' title='modifier la mati�re'>".$nom_groupe."</a></td>";
      echo "<td><a href='admin_ct.php?id_groupe=".$id_groupe."&id_prop=".$id_prop."&action=modif_prop' title='modifier le propri�taire'>".$nom_prof."</a></td>";
      echo "<td>".$nb_ct."</td>";
      echo "<td>".$nb_ct_devoirs."</td>";
      echo "<td><a href='../public/index.php?id_groupe=".$id_groupe."' target='_blank'>Voir</a></td>";
      echo "<td><center><input type=\"checkbox\" name=\"sup".$id_groupe."_".$id_prop."\" /></center></td>";
      echo "</tr>";

  }
  echo "</table></form>";
}
require ("../lib/footer.inc.php");
?>