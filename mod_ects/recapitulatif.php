<?php
/*
 * $Id: saisie_avis.php 2147 2008-07-23 09:01:04Z tbelliard $
 *
 * Copyright 2001, 2009 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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
include("../lib/initialisationsPropel.inc.php");
require_once("../lib/initialisations.inc.php");

// Resume session
$resultat_session = $session_gepi->security_check();
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

$id_classe = isset($_POST["id_classe"]) ? $_POST["id_classe"] :(isset($_GET["id_classe"]) ? $_GET["id_classe"] : false);

// On teste si un professeur peut effectuer l'�dition
if (($_SESSION['statut'] == 'professeur') and $gepiSettings["GepiAccesRecapitulatifEctsProf"] !='yes') {
   die("Droits insuffisants pour effectuer cette op�ration");
}

// On teste si le service scolarit� peut effectuer la saisie
if (($_SESSION['statut'] == 'scolarite') and $gepiSettings["GepiAccesRecapitulatifEctsScolarite"] !='yes') {
   die("Droits insuffisants pour effectuer cette op�ration");
}



// Si aucune classe n'a �t� choisie, on affiche la liste des classes accessibles
if (!$id_classe) {

// On n'affiche le header que dans la partie de s�lection de la classe,
// car le tableau va s'ouvrir dans une nouvelle fen�tre pour pouvoir �tre imprim�

//**************** EN-TETE *****************
$titre_page = "R�capitulatif ECTS";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

    echo "<p class=bold><a href=\"../accueil.php\"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>";

    if (($_SESSION['statut'] == 'scolarite') or ($_SESSION['statut'] == 'secours')) {

        // On ne s�lectionne que les classes qui ont au moins un enseignement ouvrant � cr�dits ECTS
        if($_SESSION['statut']=='scolarite'){
            $call_classe = mysql_query("SELECT DISTINCT c.*
                                        FROM classes c, periodes p, j_scol_classes jsc, j_groupes_classes jgc
                                        WHERE p.id_classe = c.id  AND jsc.id_classe=c.id AND jsc.login='".$_SESSION['login']."' AND c.id=jgc.id_classe AND jgc.saisie_ects = TRUE ORDER BY classe");
        } else {
            $call_classe = mysql_query("SELECT DISTINCT c.* FROM classes c, periodes p, j_groupes_classes jgc WHERE p.id_classe = c.id AND c.id = jgc.id_classe AND jgc.saisie_ects = TRUE ORDER BY classe");
        }

        $nombre_classe = mysql_num_rows($call_classe);
        if($nombre_classe==0){
            echo "<p>Aucune classe avec param�trage ECTS ne vous est attribu�e.<br />Contactez l'administrateur pour qu'il effectue le param�trage appropri� dans la Gestion des classes.</p>\n";
        }
    } else {
        $call_classe = mysql_query("SELECT DISTINCT c.* FROM classes c, j_eleves_professeurs s, j_eleves_classes cc, j_groupes_classes jgc WHERE (s.professeur='" . $_SESSION['login'] . "' AND s.login = cc.login AND cc.id_classe = c.id AND c.id = jgc.id_classe AND jgc.saisie_ects = TRUE)");
        $nombre_classe = mysql_num_rows($call_classe);
        if ($nombre_classe == "0") {
            echo "Vous n'�tes pas ".$gepiSettings['gepi_prof_suivi']." dans des classes ayant des enseignements ouvrant droits � des ECTS.";
        }
    }

    echo "<p>Cliquez sur la classe pour laquelle vous souhaitez �diter les documents ECTS :</p>\n";
    //echo "<br/><p><a href='recapitulatif.php?id_classe=all' target='_new'>Toutes les classes</a></p>";

    $i = 0;
    unset($tab_lien);
    unset($tab_txt);
    $nombreligne = mysql_num_rows($call_classe);
    while ($i < $nombreligne){
        $tab_lien[$i] = "recapitulatif.php?id_classe=".mysql_result($call_classe, $i, "id");
        $tab_txt[$i] = mysql_result($call_classe, $i, "classe");
        $i++;

    }
    tab_liste($tab_txt,$tab_lien,3,"target='_new'");
    echo "<p><br /></p>\n";

} else {
  // Ici, on affiche le tableau

  // On load les les p�riodes de l'ann�e en cours, notamment pour conna�tre
  // la p�riode actuelle
  include "../lib/periodes.inc.php";


  // Initialisation des tableaux
  
  $annees = array(); // Contient ann�es->p�riodes->matieres.
                     // C'est le tableau global de r�f�rence pour les colonnes.
                     
  $resultats = array(); // Contient les r�sultats, organis�s par
                        // �l�ve->ann�e->p�riode->matiere. C'est le tableau de stockage des donnn�es
                        
  $derniere_annee_archivee = false;
  $ignore_annees = array(); // Contient, pour chaque �l�ve, les ann�es
                              // qui doivent �tre gris�es car redoubl�es
  
  $gepiYear = $gepiSettings['gepiYear']; // L'ann�e courante

  // On passe �l�ve par �l�ve. Pour chaque �l�ve, on va extraire les ECTS
  // archiv�s, puis les ECTS courant, et au fur et � mesure on stocke
  // tout �a dans le tableau r�capitulatif g�n�ral, en mettant bien � jour
  
  // Appel des �l�ves
  $Classe = ClassePeer::retrieveByPK($id_classe);
  $Eleves = $Classe->getEleves('1');
  

  // Boucle de remplissage des donn�es
  foreach ($Eleves as $Eleve) {
    if (!array_key_exists($Eleve->getIdEleve(), $ignore_annees)){
      $ignore_annees[$Eleve->getIdEleve()] = array();
    }
    // On commence par les archives
    $annees_precedentes = $Eleve->getEctsAnneesPrecedentes();
    if (!array_key_exists($Eleve->getLogin(), $resultats)) {
      $resultats[$Eleve->getLogin()] = array();
    }
    
    
    // On alimente le tableau de r�f�rence, si n�cessaire
    foreach($annees_precedentes as $a) {
      // L'ann�e
      if (!array_key_exists($a['annee'], $annees)) {
        $annees[$a['annee']] = array();
        // Les p�riodes
        foreach($a['periodes'] as $num => $periode) {
          if (!array_key_exists($num, $annees[$a['annee']])) {
            $annees[$a['annee']][$num] = array('nom_periode' => $periode, 'matieres' => array());
          }
        }
      }
    }
    
    // On va chercher les r�sultats
    foreach($annees_precedentes as $a) {
      // On initialise le tableau de l'ann�e, si besoin
      if (!array_key_exists($a['annee'], $resultats[$Eleve->getLogin()])) {
        $resultats[$Eleve->getLogin()][$a['annee']] = array();
      }
      
      // On passe chaque p�riode et on r�cup�re le cr�dit
      foreach($a['periodes'] as $p_num => $p) {
        // On initialise le tableau de la p�riode, si besoin
        if (!array_key_exists($p_num, $resultats[$Eleve->getLogin()][$a['annee']])) {
          $resultats[$Eleve->getLogin()][$a['annee']][$p_num] = array(); // C'est le tableau qui va ensuite contenir les cr�dits par mati�re
        }
        $credits = $Eleve->getArchivedEctsCredits($a['annee'], $p_num);
        foreach($credits as $credit) {
          if (!array_key_exists($credit->getMatiere(), $annees[$a['annee']][$p_num]['matieres'])) {
            $annees[$a['annee']][$p_num]['matieres'][$credit->getMatiere()] = $credit->getMatiere();
          }
          $resultats[$Eleve->getLogin()][$a['annee']][$p_num][$credit->getMatiere()] = $credit;
        }
      }
      // On regarde si l'�l�ve est redoublant
      $redoublant = sql_count(sql_query("SELECT * FROM archivage_eleves2 WHERE ine = '".$Eleve->getNoGep()."' AND doublant = 'R'")) != "0" ? true : false;
      if ($redoublant && $derniere_annee_archivee) {
        $ignore_annees[$Eleve->getIdEleve()][$derniere_annee_archivee] = true;
      }
      $derniere_annee_archivee = $a['annee'];      
    }
        
    // On continue avec l'ann�e courante, m�me principe
    $redoublant = sql_count(sql_query("SELECT * FROM j_eleves_regime WHERE login = '".$Eleve->getLogin()."' AND doublant = 'R'")) != "0" ? true : false;
    if ($redoublant) {
      $ignore_annees[$Eleve->getIdEleve()][$derniere_annee_archivee] = true;
    }
    if (!array_key_exists($gepiYear, $annees)) {
      $annees[$gepiYear] = array();
    }
    if (!array_key_exists($gepiYear, $resultats[$Eleve->getLogin()])) {
      $resultats[$Eleve->getLogin()][$gepiYear] = array();
    }
    
    // On regarde quelle est la p�riode maxi pour laquelle l'�l�ve a des notes
    $periode_num = mysql_result(mysql_query("SELECT MAX(num_periode) FROM ects_credits WHERE id_eleve = '".$Eleve->getIdEleve()."'"),0);
    
    for($i=1;$i<=$periode_num;$i++) {
      if (!array_key_exists($i, $annees[$gepiYear])) {
        $annees[$gepiYear][$i] = array('nom_periode' => $nom_periode[$i], 'matieres' => array());
      }
      if (!array_key_exists($i, $resultats[$Eleve->getLogin()][$gepiYear])) {
        $resultats[$Eleve->getLogin()][$gepiYear][$i] = array();
      }
      // Maintenant on r�cup�re pour chaque p�riode les cr�dits pour chaque mati�re
      $categories = $Eleve->getEctsGroupesByCategories($i);
      foreach ($categories as $categorie) {
        foreach($categorie[1] as $group) {
          $CreditEcts = $Eleve->getEctsCredit($i,$group->getId());
          $matiere = mysql_result(mysql_query("SELECT m.nom_complet FROM matieres m, j_groupes_matieres jgm, groupes g
            WHERE
              m.matiere = jgm.id_matiere AND
              jgm.id_groupe = '".$group->getId()."'"), 0);
          
          // On enregistre quoi qu'il arrive la mati�re dans le tableau de r�f�rence,
          // car il s'agit de l'ann�e en cours. Donc on doit pouvoir utiliser le tableau
          // comme document de travail.
          if (!array_key_exists($matiere, $annees[$gepiYear][$i]['matieres'])) {
              $annees[$gepiYear][$i]['matieres'][$matiere] = $matiere;
          }
          if ($CreditEcts) {
            $resultats[$Eleve->getLogin()][$gepiYear][$i][$matiere] = $CreditEcts;
          }
        }
      }
    }
  }
  
  // Affichage des en-t�tes du tableau
  
  
  require('../lib/header.inc');
  ?>
  <style>
        .rotate90 
        {
            -webkit-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
        }
        .cell, .central_cell, .first_cell, .last_cell, .lone_cell
        {
          border-top: 1px solid black;
          border-bottom: 1px solid black;
          padding: 5 5 5 5;
          text-align: center;
        }
        
        .lone_cell
        {
          border-left: 1px solid black;
          border-right: 1px solid black;
        }
        
        .central_cell
        {
          border-left: 1px solid grey;
          border-right: 1px solid grey;
        }
        
        .first_cell
        {
          border-left: 1px solid black;
          border-right: 1px solid grey;
        }
        
        .last_cell
        {
          border-left: 1px solid grey;
          border-right: 1px solid black;
        }
        
        .result, .nom
        {
          font-size: 0.7em;
        }
        
        .result_ignore
        {
          color: grey;
        }
        
        .nom
        {
          text-align: left;
          padding-left: 5px;
          padding-right: 5px;
        }
  </style>
  <?php
  
  
  
  echo "<table style='border: 1px solid black;border-collapse: collapse; margin: 20px;'>";
  echo "<tr>";
  echo "<td style='padding-left: 150px;'>&nbsp;</td>\n"; // Nom et pr�nom
  foreach($annees as $annee => $periodes) {
    $colspan_annee = 0;
    foreach($periodes as $periode) { 
      $colspan_annee = $colspan_annee + count($periode['matieres']);
    }
    echo "<td class='lone_cell' colspan='$colspan_annee'>";
    echo $annee;
    echo "</td>\n";
  }
  // La colonne pour le cr�dit global :
  echo "<td></td>";
  // Et la colonne pour le rappel nom/pr�nom :
  echo "<td></td>";
  echo "</tr>";
  
  // Maintenant on affiche les p�riodes
  echo "<tr>\n";
  echo "<td></td>\n";
  foreach($annees as $annee => $periodes) {
    foreach($periodes as $periode) {
      $colspan_periode = count($periode['matieres']);
      echo "<td class='lone_cell' colspan='$colspan_periode'>";
      echo $periode['nom_periode'];
      echo "</td>\n";
    }
  }
  // La colonne pour le cr�dit global
  echo "<td></td>";
  // Et la colonne pour le rappel nom/pr�nom :
  echo "<td style='padding-left:150px;'></td>";
  echo "</tr>\n";
  
  // Et enfin on affiche les mati�res
  echo "<tr>\n";
  echo "<td class='lone_cell'>";
  echo "Classe :<br/><br/>";
  echo "<span style='font-weight: bold;'>";
  echo $Classe->getClasse();
  echo "</span>";
  echo "</td>\n";
  foreach($annees as $annee => $periodes) {
    foreach($periodes as $periode) {
      $m = 1;
      $nb = count($periode['matieres']);
      foreach($periode['matieres'] as $matiere) {
        if ($m == 1) {
          $cellstyle = 'first_cell';
        } else if ($m == $nb) {
          $cellstyle = 'last_cell';
        } else {
          $cellstyle = 'central_cell';
        }
        echo "<td class='$cellstyle' style='vertical-align: bottom;'>\n";
        $nom_complet_coupe = (strlen($matiere) > 20)? urlencode(substr($matiere,0,20)."...") : urlencode($matiere);
        echo "<img src=\"../lib/create_im_mat.php?texte=".rawurlencode("$nom_complet_coupe")."&amp;width=22\" WIDTH=\"22\" BORDER=\"0\" alt=\"$nom_complet_coupe\" />";
        echo "</td>\n";
        $m++;
      }
    }
  }
  echo "<td class='lone_cell' style='vertical-align: bottom;'>";
  echo "<img src=\"../lib/create_im_mat.php?texte=".rawurlencode("Mention globale")."&amp;width=22\" WIDTH=\"22\" BORDER=\"0\" alt=\"Mention globale\" />";
  echo "</td>";
  echo "<td></td>"; // Rappel nom/pr�nom
  echo "</tr>\n";
  
  
  // Boucle d'affichage du tableau
  foreach ($Eleves as $Eleve) {
    echo "<tr>";
    
    // Nom Pr�nom
    echo "<td class='lone_cell nom'>";
    echo $Eleve->getNom().' '.$Eleve->getPrenom();
    echo "</td>";
    
    // Les r�sultats
    foreach($annees as $annee => $periodes) {
      foreach($periodes as $num => $periode) {
        
        $m = 1;
        $nb = count($periode['matieres']);
        foreach($periode['matieres'] as $matiere) {
          if ($m == 1) {
            $cellstyle = 'first_cell';
          } else if ($m == $nb) {
            $cellstyle = 'last_cell';
          } else {
            $cellstyle = 'central_cell';
          }
          
          if (array_key_exists($annee, $ignore_annees[$Eleve->getIdEleve()])) {
            $cellstyle = $cellstyle.' result_ignore';
          }
          
          echo "<td class='$cellstyle result'>";
          if (array_key_exists($annee, $resultats[$Eleve->getLogin()])
            and array_key_exists($num, $resultats[$Eleve->getLogin()][$annee])
            and array_key_exists($matiere, $resultats[$Eleve->getLogin()][$annee][$num])) {
          
            
            $valeur = $resultats[$Eleve->getLogin()][$annee][$num][$matiere]->getValeur();
            $mention = $resultats[$Eleve->getLogin()][$annee][$num][$matiere]->getMention();
            if ($annee == $gepiYear) {
              $mention_prof = $resultats[$Eleve->getLogin()][$annee][$num][$matiere]->getMentionProf();
            } else {
              $mention_prof = '';
            }
            
            echo $valeur;
            if (($mention == null or $mention == '') and ($mention_prof != null or $mention_prof != '')) {
              echo '('.$mention_prof.')';
            } else {
              echo $mention;
            }
          } else {
            echo "&nbsp;";
          }
          echo "</td>";
          $m++;
        }
      }
    }
    // Le cr�dit global
    echo "<td class='lone_cell result'>";
    $credit_global = $Eleve->getCreditEctsGlobal();
    if ($credit_global) {
      echo $credit_global->getMention();
    } else {
      echo "&nbsp;";
    }
    echo "</td>";
    
    // Rappel Nom Pr�nom, pour la lisibilit�
    echo "<td class='cell nom'>";
    echo $Eleve->getNom().' '.$Eleve->getPrenom();
    echo "</td>";
    
    echo "</tr>";
    
    
  }
  
  echo "</table>";
  
}
require("../lib/footer.inc.php");
?>
