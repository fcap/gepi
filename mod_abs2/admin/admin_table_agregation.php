<?php
/*
 *
 *  @version $Id$
 *
 * Copyright 2010-2011 Josselin Jacquard
 *
 * This file and the mod_abs2 module is distributed under GPL version 3, or
 * (at your option) any later version.
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
include("../../lib/initialisationsPropel.inc.php");
require_once("../../lib/initialisations.inc.php");

// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
    header("Location: ../../utilisateurs/mon_compte.php?change_mdp=yes");
    die();
} else if ($resultat_session == '0') {
    header("Location: ../../logout.php?auto=1");
    die();
}

// Check access
if (!checkAccess()) {
    header("Location: ../../logout.php?auto=1");
    die();
}

//initialisation des variables 
$action= isset($_POST['action'])?$_POST['action']:Null;
$page= isset($_POST['page'])?$_POST['page']:1;
$maxPerPage=10;

check_token();    

// header
$titre_page = "Gestion de la table d'agr�gation des demi-journ�es d'absence";
$javascript_specifique[] = "mod_abs2/lib/include";
require_once("../../lib/header.inc");

echo "<p class=bold>";
echo "<a href=\"index.php\">";
echo "<img src='../../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>";
echo "</p>";
?>

<div style="text-align:center">
   <h2>Maintenance de la table d'agr�gation des demi-journ�es d'absence</h2>    
    
    <div style="text-align:center">
        <?php if ($action == "vidage_regeneration") : ?>
            <h2>Vidage et reg�n�ration de la table d'agr�gation</h2>
                <?php
                if ($action == "vidage_regeneration") {
                    if ($page == 1) {
                        $del=AbsenceAgregationDecompteQuery::create()->deleteAll();
                    }
                    $eleve_col = EleveQuery::create()->paginate($page, $maxPerPage);
                    echo'<div id="contain_div" class="css-panes">
                        <p> Traitement de la tranche d\'�l�ve ' . $page . '/' . $eleve_col->getLastPage() . ' en cours... <br />
                            Attention cette op�ration peut �tre longue.</p>
                         </div>';
                    ob_flush();
                    flush();
                    foreach ($eleve_col as $eleve) {
                        $eleve->checkAndUpdateSynchroAbsenceAgregationTable();
                    }
                }
                if ($page != $eleve_col->getLastPage()) {
                    echo"<p> Traitement de la tranche d'�l�ve " . $page . "/" . $eleve_col->getLastPage() . " termin� <br /></p>";
                    $page++;
                } else {
                    echo"<p>Traitement termin�</p>";
                    die();
                }
                ?>
        <?php else : ?>
            <h2>ATTENTION : En cas de modification d'un des types d'absence vous devez vider la table et la reremplir.</h2>
            <p>En cliquant sur le bouton ci-dessous vous lancerez le vidage et le reremplissage de la table</p>
        <?php endif; ?>
        
        <form action="admin_table_agregation.php" method="post" name="form_table" id="form_table">
            <?php echo add_token_field();?>
            <input type="hidden" name="action" value="vidage_regeneration" /> 
            <input type="hidden" name="page" value="<?php echo $page; ?>" />
            <?php if ($action !== "vidage_regeneration") : ?> 
                <input type="submit" name="Submit" value="Vider et reremplir" onclick="return(confirm('Etes-vous s�r de vouloir lancer le processus de vidage remplissage ?'));" /> 
            <?php else : ?> 
                <script type="text/javascript">
                    postform(document.getElementById('form_table'));
                </script>  
                <noscript>
                <input type="submit" name="Submit" value="Continuer" />
                </noscript>
            <?php endif; ?>  
        </form>
    </div>
<?php /* fin du div de centrage du tableau pour ie5 */ ?>
<?php require("../../lib/footer.inc.php");?>