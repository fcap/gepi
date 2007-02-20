<?php
/*
 * Copyright 2001, 2002 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Christian Chapel
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

$niveau_arbo = 2;
// Initialisations files
require_once("../../lib/initialisations.inc.php");
//mes fonctions
include("../lib/functions.php");

// Resume session
$resultat_session = resumeSession();
if ($resultat_session == 'c') {
header("Location: ../../utilisateurs/mon_compte.php?change_mdp=yes");
die();
} else if ($resultat_session == '0') {
    header("Location: ../../logout.php?auto=1");
die();
};
// Check access
if (!checkAccess()) {
    header("Location: ../../logout.php?auto=1");
die();
}
$msg = '';

function securite_texte($str)
 {
     $str = str_replace(array('<script','</script>','<?','?>','<?php'), array('','','','',''), $str);
     return $str;
 }

if (empty($_GET['action_sql']) AND empty($_POST['action_sql'])) {$action_sql="";}
    else { if (isset($_GET['action_sql'])) {$action_sql=$_GET['action_sql'];} if (isset($_POST['action_sql'])) {$action_sql=$_POST['action_sql'];} }
if (empty($_GET['action']) AND empty($_POST['action'])) {exit();}
    else { if (isset($_GET['action'])) {$action=$_GET['action'];} if (isset($_POST['action'])) {$action=$_POST['action'];} }
if (empty($_GET['id_motif']) AND empty($_POST['id_motif'])) { $id_motif="";}
    else { if (isset($_GET['id_motif'])) {$id_motif=$_GET['id_motif'];} if (isset($_POST['id_motif'])) {$id_motif=$_POST['id_motif'];} }
if (empty($_GET['nb_ajout']) AND empty($_POST['nb_ajout'])) { $nb_ajout="1";}
    else { if (isset($_GET['nb_ajout'])) {$nb_ajout=$_GET['nb_ajout'];} if (isset($_POST['nb_ajout'])) {$nb_ajout=$_POST['nb_ajout'];} }
if (empty($_GET['init_absence_action']) AND empty($_POST['init_absence_action'])) { $init_absence_action=""; }
    else { if (isset($_GET['init_absence_action'])) {$init_absence_action=$_GET['init_absence_action'];} if (isset($_POST['init_absence_action'])) {$init_absence_action=$_POST['init_absence_action'];} }
if (empty($_GET['def_absence_action']) AND empty($_POST['def_absence_action'])) { $def_absence_action="";}
    else { if (isset($_GET['def_absence_action'])) {$def_absence_action=$_GET['def_absence_action'];} if (isset($_POST['def_absence_action'])) {$def_absence_action=$_POST['def_absence_action'];} }

$total = 0;
$verification[0] = 1;
$erreur = 0;
$remarque = 0;

if ($action_sql == "ajouter" OR $action_sql == "modifier")
{
   while ($total < $nb_ajout)
      {
            // V�rifcation des variable
              $init_absence_action_ins = $_POST['init_absence_action'][$total];
              $def_absence_action_ins = securite_texte($_POST['def_absence_action'][$total]);

              if ($action_sql == "modifier") { $id_absence_action_ins = $_POST['id_motif'][$total]; }

            // V�rification des champs nom et prenom (si il ne sont pas vides ?)
            if($init_absence_action_ins != "" && $def_absence_action_ins != "")
            {
                 if($action_sql == "ajouter") { $test = mysql_result(mysql_query("SELECT count(*) FROM absences_actions WHERE init_absence_action = '".$init_absence_action_ins."'"),0); }
                 if($action_sql == "modifier") { $test = mysql_result(mysql_query("SELECT count(*) FROM absences_actions WHERE id_absence_action != '".$id_absence_action_ins."' AND init_absence_action = '".$init_absence_action_ins."'"),0); }
                 if ($test == "0")
                  {
                     if($action_sql == "ajouter")
                      {
                            // Requete d'insertion MYSQL
                             $requete = "INSERT INTO absences_actions (init_absence_action,def_absence_action) VALUES ('$init_absence_action_ins','$def_absence_action_ins')";
                      }
                     if($action_sql == "modifier")
                      {
                            // Requete de mise � jour MYSQL
                              $requete = "UPDATE absences_actions SET
                                                  init_absence_action = '$init_absence_action_ins',
                                                  def_absence_action = '$def_absence_action_ins'
                                                  WHERE id_absence_action = '".$id_absence_action_ins."' ";
                      }
                            // Execution de cette requete dans la base cartouche
                             mysql_query($requete) or die('Erreur SQL !'.$sql.'<br />'.mysql_error());
                             $verification[$total] = 1;
                    } else {
                               // v�rification = 2 - C'est initiale pour les motif existe d�jas
                                 $verification[$total] = 2;
                                 $erreur = 1;
                            }
            } else {
                     // v�rification = 3 - Tous les champs ne sont pas remplie
                     $verification[$total] = 3;
                     $erreur = 1;
                   }
      $total = $total + 1;
      }

      if($erreur == 0)
       {
          $action = "visualiser";
       } else {
                 $o = 0;
                 $n = 0;
                 while ($o < $nb_ajout)
                  {
                    if($verification[$o] != 1)
                     {
                        $init_absence_action_erreur[$n] = $init_absence_action[$o];
                        $def_absence_action_erreur[$n] = $def_absence_action[$o];
                        $verification_erreur[$n] = $verification[$o];
                        if ($action_sql == "modifier") { $id_definie_motif_erreur[$n] = $id_motif[$o]; }
                        $n = $n + 1;
                     }
                     $o = $o + 1;
                  }
                  $nb_ajout = $n;
                  if ($action_sql == "ajouter") { $action = "ajouter"; }
                if ($action_sql == "modifier") { $action = "modifier"; }
              }
}

if ($action_sql == "supprimer") {
      $test = mysql_result(mysql_query("SELECT count(*) FROM absences_actions, suivi_eleve_cpe
      WHERE  suivi_eleve_cpe.action_suivi_eleve_cpe = absences_actions.init_absence_action
      and id_absence_action ='".$id_motif."'"),0);
      if ($test == "0")
      {
         //Requete de suppresion MYSQL
            $requete = "DELETE FROM absences_actions WHERE id_absence_action ='$id_motif'";
         // Execution de cette requete
            mysql_query($requete) or die('Erreur SQL !'.$requete.'<br />'.mysql_error());
            $msg = "La suppresion a �t� effectu�e avec succ�s.";
      } else {
          $msg = "Suppression impossible car une ou plusieurs suivi ont �t� enregistr�es avec ce type d'action. Commencez par supprimer les suivis concern�es";
      }

}

if ($action == "modifier")
 {
      $requete_modif_motif = 'SELECT * FROM absences_actions WHERE id_absence_action="'.$id_motif.'"';
      $resultat_modif_motif = mysql_query($requete_modif_motif) or die('Erreur SQL !'.$requete_modif_motif.'<br />'.mysql_error());
      $data_modif_motif = mysql_fetch_array($resultat_modif_motif);
 }

// header
$titre_page = "Gestion des motifs d'absence";
require_once("../../lib/header.inc");
?>

<p class=bold>|
<a href="../../accueil.php">Accueil</a>|
<a href="../../accueil_modules.php">Retour administration des modules</a>|
<a href='index.php'>Retour module absence</a>|
<?php if ($action=="modifier" OR $action=="ajouter") echo "<a href=\"admin_actions_absences.php?action=visualiser\">Retour � la d�finition des motifs</a>"; ?>
<?php if ($action=="visualiser") echo "<a href=\"admin_actions_absences.php?action=ajouter\">Ajouter un ou des motif(s)</a>"; ?>
|


</p>
<?php if ($action == "visualiser") { ?>
<? /* div de centrage du tableau pour ie5 */ ?>
<div style="text-align:center">
    <table border="0" cellpadding="0" cellspacing="1" class="tableau_moyen_centre">
      <tr class="fond_bleu_2">
        <td colspan="5"><div class="norme_absence_gris_bleu"><strong>D&eacute;finition des motifs d'absence</strong></div></td>
      </tr>
      <tr>
        <td class="tableau_moyen_centre_th">Initial</td>
        <td class="tableau_moyen_centre_th">D�finition</td>
        <td class="tableau_moyen_centre_th_25"></td>
        <td class="tableau_moyen_centre_th_25"></td>
      </tr>
    <?php
    $requete_motif = 'SELECT * FROM absences_actions WHERE init_absence_action !="DI" AND init_absence_action !="IN" ORDER BY init_absence_action ASC';
    $execution_motif = mysql_query($requete_motif) or die('Erreur SQL !'.$requete_motif.'<br>'.mysql_error());
    $i=2;
    while ( $data_motif = mysql_fetch_array( $execution_motif ) ) {
       if ($i==1) {
                    $i=2;
                    $couleur_cellule="fond_bleu_3";
                  } else {
                           $couleur_cellule="fond_bleu_4";
                           $i=1;
                         } ?>
        <tr class="<?php echo $couleur_cellule; ?>">
          <td class="centre"><?php echo $data_motif['init_absence_action']; ?></td>
          <td class="centre"><?php echo $data_motif['def_absence_action']; ?></td>
          <td class="centre"><a href="admin_actions_absences.php?action=modifier&amp;id_motif=<?php echo $data_motif['id_absence_action']; ?>"><img src="../images/modification.png" width="18" height="22" title="Modifier" border="0" alt="" /></a></td>
          <td class="centre"><a href="admin_actions_absences.php?action=visualiser&amp;action_sql=supprimer&amp;id_motif=<?php echo $data_motif['id_absence_action']; ?>" onClick="return confirm('Etes-vous sur de vouloire le supprimer...')"><img src="../images/x2.png" width="22" height="22" title="Supprimer" border="0" alt="" /></a></td>
        </tr>
     <?php } ?>
    </table>
<? /* fin du div de centrage du tableau pour ie5 */ ?>
</div>
<?php } ?>

<?php if ($action == "ajouter" OR $action == "modifier") { ?>
  <?php if ($action == "ajouter") { ?>
<? /* div de centrage du tableau pour ie5 */ ?>
<div style="text-align:center">
    <form method="post" action="admin_actions_absences.php?action=ajouter" name="form1" id="form1">
     <fieldset class="fieldset_efface">
      <table border="0" cellpadding="2" cellspacing="2" class="tableau_moyen_centre">
        <tr class="fond_bleu_2">
          <td class="norme_absence_gris_bleu"><b>Nombre de motifs � ajouter</b></td>
        </tr>
        <tr class="adroite">
          <td><input name="nb_ajout" type="text" size="5" maxlength="5" value="<?php if(isset($nb_ajout)) { echo $nb_ajout; } else { ?>1<?php } ?>" />&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit2" value="Cr&eacute;er" /></td>
        </tr>
      </table>
     </fieldset>
    </form>
  <?php } ?>
    <form action="admin_actions_absences.php?action=visualiser&amp;action_sql=<?php if($action=="ajouter") { ?>ajouter<?php } if($action=="modifier") { ?>modifier<?php } ?>" method="post" name="form2" id="form2">
     <fieldset class="fieldset_efface">
      <table border="0" cellpadding="2" cellspacing="2" class="tableau_moyen_centre">
        <tr class="fond_bleu_2">
          <td colspan="3" class="norme_absence_gris_bleu"><b><?php if($action=="ajouter") { ?>Ajout d'un ou plusieurs motif(s)<?php } if($action=="modifier") { ?>Modifier un motif<?php } ?></b></td>
        </tr>
        <tr class="fond_bleu_2">
          <td class="norme_absence_gris_bleu_centre">Initital</td>
          <td colspan="2" class="norme_absence_gris_bleu">D�finition</td>
        </tr>
        <?php
        $i = 2;
        $nb = 0;
        while($nb < $nb_ajout) {
        if ($i==1) {
                    $i=2;
                    $couleur_cellule="fond_bleu_3";
                  } else {
                           $couleur_cellule="fond_bleu_4";
                           $i=1;
                         } ?>
        <?php if (isset($verification_erreur[$nb]) and $verification_erreur[$nb] != 1) { ?>
         <tr>
        <td class="centre"><img src="../images/attention.png" width="28" height="28" alt="" /></td>
          <td colspan="2" class="erreur_rouge_jaune"><b>- Erreur -<br />
          <?php if ($verification_erreur[$nb] === '2') { ?>Cette initiale pour le motif existe d�j�<?php } ?>
          <?php if ($verification_erreur[$nb] === '3') { ?>Tous les champs ne sont pas remplis<?php } ?>
          </b><br /></td>
         </tr>
        <?php } ?>
        <tr class="<?php echo $couleur_cellule; ?>">
          <td class="centre">
           <?php
           if($action==="modifier") {
               $test = mysql_result(mysql_query("SELECT count(*) FROM suivi_eleve_cpe WHERE suivi_eleve_cpe.action_suivi_eleve_cpe = '".$data_modif_motif['init_absence_action']."'"),0);
               if ($test != "0") {
                   ?><input name="init_absence_action[<?php echo $nb; ?>]" type="hidden" id="init_absence_action" size="2" maxlength="2" value="<?php if($action=="modifier") { echo $data_modif_motif['init_absence_action']; } elseif (isset($init_absence_action_erreur[$nb])) { echo $init_absence_action_erreur[$nb]; } ?>" /><?php if($action=="modifier") { echo $data_modif_motif['init_absence_action']; } elseif (isset($init_absence_action_erreur[$nb])) { echo $init_absence_action_erreur[$nb]; } ?><?php
               } else {
                   ?><input name="init_absence_action[<?php echo $nb; ?>]" type="text" id="init_absence_action" size="2" maxlength="2" value="<?php if($action=="modifier") { echo $data_modif_motif['init_absence_action']; } elseif (isset($init_absence_action_erreur[$nb])) { echo $init_absence_action_erreur[$nb]; } ?>" /><?php
               }
           } else {
               ?><input name="init_absence_action[<?php echo $nb; ?>]" type="text" id="init_absence_action" size="2" maxlength="2" value="<?php if($action=="modifier") { echo $data_modif_motif['init_absence_action']; } elseif (isset($init_absence_action_erreur[$nb])) { echo $init_absence_action_erreur[$nb]; } ?>" /><?php
           }

            ?>
           </td>
           <td colspan="2" class="centre">
              <input name="def_absence_action[<?php echo $nb; ?>]" type="text" id="def_absence_action" size="40" maxlength="200" value="<?php if($action=="modifier") { echo $data_modif_motif['def_absence_action']; } elseif (isset($def_absence_action_erreur[$nb])) { echo $def_absence_action_erreur[$nb]; } else { ?><?php } ?>" />
           </td>
        </tr>
            <?php if($action==='modifier') { ?>
              <input type="hidden" name="id_motif[<?php echo $nb; ?>]" value="<?php if (isset($id_definie_motif_erreur[$nb])) { echo $id_definie_motif_erreur[$nb]; } else { echo $id_motif; } ?>" />
            <?php } ?>
        <?php $nb = $nb + 1; } ?>
        <tr>
          <td colspan="3" class="adroite">
              <input type="hidden" name="nb_ajout" value="<?php echo $nb_ajout; ?>" />
              <input type="submit" name="Submit" value="<?php if($action=="ajouter") { ?>Cr�er motif(s)<?php } if($action=="modifier") { ?>Modifier le motif<?php } ?>" />
          </td>
        </tr>
      </table>
     </fieldset>
    </form>
<? /* fin du div de centrage du tableau pour ie5 */ ?>
</div>
<?php mysql_close(); } ?>
