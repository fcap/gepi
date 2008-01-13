<?php
/*
 * @version: $Id$
 *
 * Copyright 2001-2004 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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

//pour g�rer le verrouillage de la p�riode depuis le fichier V�rif_bulletins.php
$classe=isset($_GET['classe']) ? $_GET['classe'] : 0;
$periode=isset($_GET['periode']) ? $_GET['periode'] : 0;
// quelle action apr�s le verrouillage ?
$action_apres=isset($_GET['action']) ? $_GET['action'] : NULL;


if (isset($_POST['deverouillage_auto_periode_suivante'])) {
    if (!saveSetting("deverouillage_auto_periode_suivante", $_POST['deverouillage_auto_periode_suivante'])) {
        $msg .= "Erreur lors de l'enregistrement de deverouillage_auto_periode_suivante !";
        $reg_ok = 'no';
    }
}

if (isset($_POST['ok'])) {

   $pb_reg_ver = 'no';
   //$calldata = sql_query("SELECT DISTINCT c.id, c.classe FROM classes c, periodes p WHERE p.id_classe = c.id  ORDER BY classe");
   $calldata = sql_query("SELECT DISTINCT c.id, c.classe FROM classes c, periodes p, j_scol_classes jsc WHERE p.id_classe = c.id  AND jsc.id_classe=c.id AND jsc.login='".$_SESSION['login']."' ORDER BY classe");
   if ($calldata) for ($k = 0; ($row = sql_row($calldata, $k)); $k++) {
      $id_classe = $row[0];
      $periode_query = sql_query("SELECT verouiller FROM periodes WHERE id_classe = '$id_classe' ORDER BY num_periode");
      $nb_periode = sql_count($periode_query) + 1 ;
      if ($periode_query) for ($i = 0; ($row_per = sql_row($periode_query, $i)); $i++) {
         $nom_classe = "cl_".$id_classe."_".$i;
         $t = $i+1;
         if (isset($_POST[$nom_classe]))  {
            $register = sql_query("UPDATE periodes SET verouiller='".$_POST[$nom_classe]."' WHERE (num_periode='".$t."' and id_classe='".$id_classe."')");
            if (!$register) {$pb_reg_ver = 'yes';}
         }
      }
   }

   // D�verrouillage de la p�riode suivante si le bouton radio est � Oui.
   if ((($action_apres == 'retour') OR ($action_apres == 'imprime_html') OR ($action_apres == 'imprime_pdf') OR ($action_apres == 'rien')) AND isset($_POST['deverouillage_auto_periode_suivante'])) {
		if (($_POST['deverouillage_auto_periode_suivante'])=='y') {
		  //recherche du nombre de p�riode pour la classe
		  $sql_periode = "SELECT * FROM periodes WHERE id_classe=$classe";
		  $result_periode = mysql_query($sql_periode);
		  $nb_periodes_classe = mysql_num_rows($result_periode);
          //echo $nb_periodes_classe;
          $periode_en_cours = $periode;
		  $periode_suivante = $periode+1;
		  //Pour la p�riode modifi�e on r�cup�re son �tat
		  $etat_periode=mysql_result($result_periode, $periode-1, "verouiller");
		  //echo "<br/>".$etat_periode;
		  //echo "<br/>".$periode_en_cours;
		  //echo "<br/>".$nb_periodes_classe;
		  //si l'�tat est P ou O on d�v�rouille totalement la p�riode +1 (di elle existe !)
		  if (($etat_periode=='P') OR $etat_periode=='O') {
		    if ($periode_en_cours  < $nb_periodes_classe) {
			  //echo "<br/>On d�verrouille $periode_suivante";
			  $sql_maj_periode_suivante = "UPDATE periodes SET verouiller='N' WHERE (num_periode='".$periode_suivante."' and id_classe='".$classe."')";
			  //echo "<br/>".$sql_maj_periode_suivante;
			  $result_maj_periode_suivante = mysql_query($sql_maj_periode_suivante);
			  if (!$result_maj_periode_suivante) {$pb_reg_ver = 'yes';}
		    }
		  }
      	}
   }

   if ($pb_reg_ver == 'no') {
      $msg = "Les modifications ont �t� enregistr�es.";
   } else {
      $msg = "Il y a eu un probl�me lors de l'enregistrement des donn�es.";
   }

   if ($action_apres == 'retour') {
     header("Location: ./verif_bulletins.php");
   }

   if ($action_apres == 'imprime_html') {

     header("Location: ./index.php?id_classe=$classe");
   }

   if ($action_apres == 'imprime_pdf') {

     header("Location: ./index.php?format=pdf");
   }
}


//**************** EN-TETE **************************************
$titre_page = "Verrouillage et d�verrouillage des p�riodes";
require_once("../lib/header.inc");
//**************** FIN EN-TETE **********************************
?>
<script type='text/javascript' language='javascript'>
function CocheCase(rang,per) {
 nbelements = document.formulaire.elements.length;
 for (i=0;i<nbelements;i++) {
   if (document.formulaire.elements[i].type =='hidden') {
     if (document.formulaire.elements[i].value ==per) {
       document.formulaire.elements[i+1].checked = false ;
       document.formulaire.elements[i+2].checked = false ;
       document.formulaire.elements[i+3].checked = false ;
       document.formulaire.elements[i+rang].checked = true ;
      }
   }
 }
}
</script>
<p class=bold><a href="../accueil.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>
</p>

<?php
$texte_deverrouiller = urlencode("D�verrouiller");
$texte_verrouiller_part = urlencode("Verrouiller part.");
$texte_verrouiller_tot = urlencode("Verrouiller tot.");

// si la classe et la p�riode sont d�finies (on vient de verif_bulletiin.php)
if (!(($classe != 0) AND ($periode !=0))) {
// On va chercher les classes d�j� existantes, et on les affiche.
    $max_per = sql_query1("SELECT num_periode FROM periodes ORDER BY num_periode DESC LIMIT 1");
   //$calldata = sql_query("SELECT DISTINCT c.id, c.classe FROM classes c, periodes p WHERE p.id_classe = c.id  ORDER BY classe");
   $calldata = mysql_query("SELECT DISTINCT c.id, c.classe FROM classes c, periodes p, j_scol_classes jsc WHERE p.id_classe = c.id  AND jsc.id_classe=c.id AND jsc.login='".$_SESSION['login']."' ORDER BY classe");
   $nombreligne = sql_count($calldata);
   echo "Total : $nombreligne classes\n";
}

   echo "<ul>
   <li>Lorsqu'une p�riode est <b>d�verrouill�e</b>, le remplissage de toutes les rubriques (notes, appr�ciations, avis) est autoris�, la visualisation des
   bulletins simples est autoris�e mais la visualisation et l'impression des bulletins officiels sont impossibles.<br /><br /></li>
   <li>Lorsqu'une p�riode est <b>verrouill�e partiellement</b>, seuls le remplissage et/ou la modification
   de l'avis du conseil de classe sont possibles. La visualisation et l'impression des bulletins officiels sont autoris�es.<br /><br /></li>
   <li>Lorsqu'une p�riode est <b>verrouill�e totalement</b>, le remplissage et la modification du bulletin pour la p�riode concern�e
   sont impossibles. la visualisation et l'impression sont autoris�es.</li>\n";
   echo "</ul>\n";
   echo "<br /><br />\n";


// si la classe et la p�riode sont d�finies (on vient de verif_bulletiin.php)
if (($classe != 0) AND ($periode !=0)) {

	  echo "<form action=\"verrouillage.php?classe=$classe&periode=$periode&action=$action_apres\" name=\"formulaire\" method=\"post\">";

      echo "<table cellpadding='3' cellspacing='0' border='1' align='center'>";
      echo "<tr class='fond_sombre'><td>&nbsp;</td>";
      echo "<td>";
      echo"</td>
        <td><IMG SRC=\"../lib/create_im_mat.php?texte=".$texte_deverrouiller."&amp;width=22\" WIDTH=\"22\" BORDER=0 alt=\"D�verrouiller\" /></td>
        <td><IMG SRC=\"../lib/create_im_mat.php?texte=".$texte_verrouiller_part."&amp;width=22\" WIDTH=\"22\" BORDER=0 alt=\"Verrouiller partiellement\" /></td>
        <td><IMG SRC=\"../lib/create_im_mat.php?texte=".$texte_verrouiller_tot."&amp;width=22\" WIDTH=\"22\" BORDER=0 alt=\"Verrouiller totalement\" /></td>";
      echo "</tr>\n";
	  // Affichage de la classe (nom court)
	  $sql_classe = "SELECT classe FROM classes WHERE id = '$classe'";
      $requete_classe = sql_query($sql_classe);
	  $donner_modele = mysql_fetch_array($requete_classe);
	  $nom_court_classe = $donner_modele['classe'];
	  echo "<td><b>$nom_court_classe</b> ";
	  echo "</td>";

	  $sql_periode = "SELECT nom_periode, verouiller FROM periodes WHERE (id_classe = '$classe' AND num_periode='$periode')";
	  $periode_query = sql_query($sql_periode);
	  $nb_periode = sql_count($periode_query) + 1 ;
	  $j = 0;

	  //ajustement de l'indice periode 1, 2 , 3 dans la base en r�alit� : 0, 1, 2
	  $indice_periode = $periode-1;
	  if ($periode_query) for ($i = 0; ($row_per = sql_row($periode_query, $i)); $i++) {
		 $nom_classe = "cl_".$classe."_".$indice_periode;
		 echo "<td>".ucfirst($row_per[0])."</td>\n";
		 //echo "<input type=\"hidden\" name=\"numperiode\" value=\"$i\" />";
		 echo "<td><input type=\"hidden\" name=\"numperiode\" value=\"$indice_periode\" />";
		 //echo "<td><input type=\"radio\" name=\"".$nom_classe."\" value=\"N\" ";
		 echo "<input type=\"radio\" name=\"".$nom_classe."\" value=\"N\" ";
		 if ($row_per[1] == "N") echo "checked";
		 echo " /></td>\n";
		 echo "<td><input type=\"radio\" name=\"".$nom_classe."\" value=\"P\" ";
		 if ($row_per[1] == "P") echo "checked";
		 echo " /></td>\n";
		 echo "<td><input type=\"radio\" name=\"".$nom_classe."\" value=\"O\" ";
		 if ($row_per[1] == "O") echo "checked";
		 echo " /></td>\n";
		 $j++;
	  }

	  echo "</table><br />\n";

	  // Option de d�verrouillage automatique
	  echo "<br />\n<table align='center'>\n";
	  echo "<tr>\n";
	  echo "<td>\nProc�der �galement au d�verrouillage automatique de la p�riode suivante <br />lors du verrouillage partiel ou total de la p�riode ci-dessus : ";
      echo "\n</td>\n<td>\n";

        echo "<input type=\"radio\" name=\"deverouillage_auto_periode_suivante\" value=\"y\" ";
        if (getSettingValue("deverouillage_auto_periode_suivante") == 'y') echo " checked";
        echo " />&nbsp;Oui";
        echo "<input type=\"radio\" name=\"deverouillage_auto_periode_suivante\" value=\"n\" ";
        if (getSettingValue("deverouillage_auto_periode_suivante") != 'y') echo " checked";
        echo " />&nbsp;Non";

	  echo "\n</td>\n</tr>\n</table>\n<br />\n";

	 if ($action_apres == 'rien') {

	   echo "<center><input type=\"submit\" name=\"ok\" value=\"Enregistrer\" /></center>\n";

    } elseif ($action_apres == 'imprime_html') {

	  echo "<center><input type=\"submit\" name=\"ok\" value=\"Enregistrer puis aller � la page impression HTML\" /></center>\n";

    } elseif ($action_apres == 'imprime_pdf') {

	  echo "<center><input type=\"submit\" name=\"ok\" value=\"Enregistrer puis aller � la page impression PDF\" /></center>\n";

    } elseif ($action_apres == 'retour') {

      echo "<center><input type=\"submit\" name=\"ok\" value=\"Enregistrer puis retour � la page v�rification\" /></center>\n";

    }

      echo "</form>\n";

} else {
   if ($nombreligne != 0) {
      echo "<form action=\"verrouillage.php\" name=\"formulaire\" method=\"post\">";

      echo "<p align='center'><input type=\"submit\" name=\"ok\" value=\"Enregistrer\" /></p>\n";
      //echo "<table cellpadding='3' cellspacing='0' border='1' align='center'>";
      echo "<table class='boireaus' cellpadding='3' cellspacing='0' align='center'>";
      echo "<tr class='fond_sombre'><th>&nbsp;</th>";
      for ($i = 0; $i < $max_per; $i++) echo "<th>
        <a href=\"javascript:CocheCase(1,".$i.")\">Tout d�verrouiller</a><br />
        <a href=\"javascript:CocheCase(2,".$i.")\">Tout verrouiller partiellement</a><br />
        <a href=\"javascript:CocheCase(3,".$i.")\">Tout verrouiller  totalement</a>
        </th>
        <th><IMG SRC=\"../lib/create_im_mat.php?texte=".$texte_deverrouiller."&amp;width=22\" WIDTH=\"22\" BORDER=0 alt=\"D�verrouiller\" /></th>
        <th><IMG SRC=\"../lib/create_im_mat.php?texte=".$texte_verrouiller_part."&amp;width=22\" WIDTH=\"22\" BORDER=0 alt=\"Verrouiller partiellement\" /></th>
        <th><IMG SRC=\"../lib/create_im_mat.php?texte=".$texte_verrouiller_tot."&amp;width=22\" WIDTH=\"22\" BORDER=0 alt=\"Verrouiller totalement\" /></th>\n";
      echo "</tr>\n";
      //$flag = 0;
		$alt=1;
      if ($calldata) for ($k = 0; ($row = sql_row($calldata, $k)); $k++) {
          $id_classe = $row[0];
          $classe = $row[1];
          $alt=$alt*(-1);
			echo "<tr class='lig$alt'";
          //if ($flag==1) { echo " class='fond_sombre'"; $flag = 0;} else {$flag=1;};
          echo "><td><b>$classe</b> ";
          echo "</td>";

          $periode_query = sql_query("SELECT nom_periode, verouiller FROM periodes WHERE id_classe = '$id_classe' ORDER BY num_periode");
          $nb_periode = sql_count($periode_query) + 1 ;
          $j = 0;
          if ($periode_query) for ($i = 0; ($row_per = sql_row($periode_query, $i)); $i++) {
             $nom_classe = "cl_".$id_classe."_".$i;
             echo "<td>".ucfirst($row_per[0])."</td>\n";
             //echo "<input type=\"hidden\" name=\"numperiode\" value=\"$i\" />";
             echo "<td><input type=\"hidden\" name=\"numperiode\" value=\"$i\" />";
             //echo "<td><input type=\"radio\" name=\"".$nom_classe."\" value=\"N\" ";
             echo "<input type=\"radio\" name=\"".$nom_classe."\" value=\"N\" ";
             if ($row_per[1] == "N") echo "checked";
             echo " /></td>\n";
             echo "<td><input type=\"radio\" name=\"".$nom_classe."\" value=\"P\" ";
             if ($row_per[1] == "P") echo "checked";
             echo " /></td>\n";
             echo "<td><input type=\"radio\" name=\"".$nom_classe."\" value=\"O\" ";
             if ($row_per[1] == "O") echo "checked";
             echo " /></td>\n";
             $j++;
          }
          for ($i = $j; $i < $max_per; $i++) echo "<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>\n";

          echo "</tr>\n";
      }
      echo "</table><br />\n";
      echo "<center><input type=\"submit\" name=\"ok\" value=\"Enregistrer\" /></center>\n";
      echo "</form>\n";
   } else {
      echo "<p class='grand'>Attention : aucune classe n'a �t� d�finie dans la base GEPI !</p>\n";
   }
} //else
echo "<p><br /></p>\n";
require("../lib/footer.inc.php");
?>