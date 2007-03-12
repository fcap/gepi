<?php
/*
 * $Id$
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
// Check access

if (!checkAccess()) {
    header("Location: ../logout.php?auto=1");
die();
}

$msg = '';


if (isset($_POST['OK'])) {
    if (isset($_POST['GepiRubConseilProf'])) {
        $temp = 'yes';
    } else {
        $temp = 'no';
    }
    if (!saveSetting("GepiRubConseilProf", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiRubConseilProf !";
    }
    if (isset($_POST['GepiRubConseilScol'])) {
        $temp = 'yes';
    } else {
        $temp = 'no';
    }
    if (!saveSetting("GepiRubConseilScol", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiRubConseilScol !";
    }

    if (isset($_POST['GepiProfImprBul'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiProfImprBul", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiProfImprBul !";
    }

    if (isset($_POST['GepiProfImprBulSettings'])) {
        $temp = "yes";
    } else {
        $temp ="no";
    }
    if (!saveSetting("GepiProfImprBulSettings", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiProfImprBulSettings !";
    }

    if (isset($_POST['GepiAdminImprBulSettings'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAdminImprBulSettings", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAdminImprBulSettings !";
    }

    if (isset($_POST['GepiScolImprBulSettings'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiScolImprBulSettings", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiScolImprBulSettings !";
    }

    if (isset($_POST['GepiAccesReleveScol'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesReleveScol", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesReleveScol !";
    }

    if (isset($_POST['GepiAccesReleveCpe'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesReleveCpe", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesReleveCpe !";
    }

    if (isset($_POST['GepiAccesReleveProfP'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesReleveProfP", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesReleveProfP !";
    }
    if (isset($_POST['GepiAccesReleveProf'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesReleveProf", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesReleveProf !";
    }
    if (isset($_POST['GepiAccesReleveProfTousEleves'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesReleveProfTousEleves", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesReleveProf !";
    }
    if (isset($_POST['GepiAccesReleveEleve'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesReleveEleve", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesReleveEleve !";
    }
    
    if (isset($_POST['GepiAccesCahierTexteEleve'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesCahierTexteEleve", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesCahierTexteEleve !";
    }
    
    if (isset($_POST['GepiAccesReleveParent'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesReleveParent", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesReleveParent !";
    }

    if (isset($_POST['GepiAccesCahierTexteParent'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesCahierTexteParent", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesCahierTexteParent !";
    }
    
    if (isset($_POST['GepiPasswordReinitProf'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiPasswordReinitProf", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiPasswordReinitProf !";
    }
    
    if (isset($_POST['GepiPasswordReinitScolarite'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiPasswordReinitScolarite", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiPasswordReinitScolarite !";
    }
    
    if (isset($_POST['GepiPasswordReinitCpe'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiPasswordReinitCpe", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiPasswordReinitCpe !";
    }
    
    if (isset($_POST['GepiPasswordReinitAdmin'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiPasswordReinitAdmin", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiPasswordReinitAdmin !";
    }

    if (isset($_POST['GepiPasswordReinitEleve'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiPasswordReinitEleve", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiPasswordReinitEleve !";
    }

    if (isset($_POST['GepiPasswordReinitParent'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiPasswordReinitParent", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiPasswordReinitParent !";
    }	

    if (isset($_POST['GepiAccesEquipePedaEleve'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesEquipePedaEleve", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesEquipePedaEleve !";
    }	
    
    if (isset($_POST['GepiAccesEquipePedaParent'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesEquipePedaParent", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesEquipePedaParent !";
    }	

    if (isset($_POST['GepiAccesEquipePedaEmailEleve'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesEquipePedaEmailEleve", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesEquipePedaEmailEleve !";
    }	
    
    if (isset($_POST['GepiAccesEquipePedaEmailParent'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesEquipePedaEmailParent", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesEquipePedaEmailParent !";
    }
    
    if (isset($_POST['GepiAccesBulletinSimpleParent'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesBulletinSimpleParent", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesBulletinSimpleParent !";
    }

    if (isset($_POST['GepiAccesBulletinSimpleEleve'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesBulletinSimpleEleve", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesBulletinSimpleEleve !";
    }

    if (isset($_POST['GepiAccesGraphEleve'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesGraphEleve", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesGraphEleve !";
    }

    if (isset($_POST['GepiAccesGraphParent'])) {
        $temp = "yes";
    } else {
        $temp = "no";
    }
    if (!saveSetting("GepiAccesGraphParent", $temp)) {
        $msg .= "Erreur lors de l'enregistrement de GepiAccesGraphParent !";
    }
}

// Load settings
if (!loadSettings()) {
    die("Erreur chargement settings");
}
if (isset($_POST['is_posted']) and ($msg=='')) $msg = "Les modifications ont �t� enregistr�es !";

// End standart header
$titre_page = "Droits d'acc�s";
require_once("../lib/header.inc");
?>
<p class=bold><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>
<form action="droits_acces.php" method="post" name="form1" style="width: 100%;">
<table class='menu' style='width: 90%; margin-left: auto; margin-right: auto;' cellpadding="10">
	<tr>
		<th colspan="2">
		Param�trage des droits d'acc�s
		</th>
	</tr>
    <tr>
        <td style="font-variant: small-caps;">
        Le professeur
        </td>
       	<td>
        <input type="checkbox" name="GepiAccesReleveProf" value="yes" <?php if (getSettingValue("GepiAccesReleveProf")=='yes') echo "checked"; ?> /> a acc�s aux relev�s de notes des �l�ves des classes dans lesquelles il enseigne<br />
        <input type="checkbox" name="GepiAccesReleveProfTousEleves" value="yes" <?php if (getSettingValue("GepiAccesReleveProfTousEleves")=='yes') echo "checked"; ?> /> a acc�s aux relev�s de notes de tous les �l�ves des classes dans lesquelles il enseigne (si case non coch�e, le professeur ne voit que les �l�ves de ses groupes d'enseignement et pas les autres �l�ves des classes concern�es)<br/>
        <input type="checkbox" name="GepiPasswordReinitProf" value="yes" <?php if (getSettingValue("GepiPasswordReinitProf")=='yes') echo "checked"; ?> /> peut r�initialiser lui-m�me son mot de passe perdu (si fonction activ�e)
       	</td>
    </tr>    
    <tr>
        <td style="font-variant: small-caps;">
        Le <?php echo getSettingValue("gepi_prof_suivi"); ?>
        </td>
       	<td>
       	<input type="checkbox" name="GepiRubConseilProf" value="yes" <?php if (getSettingValue("GepiRubConseilProf")=='yes') echo "checked"; ?> /> peut saisir les avis du conseil de classe pour sa classe<br/>
       	<input type="checkbox" name="GepiProfImprBul" value="yes" <?php if (getSettingValue("GepiProfImprBul")=='yes') echo "checked"; ?> /> �dite/imprime les bulletins p�riodiques des classes dont il a la charge.<br />
        <span class='small'>(Par d�faut, seul un utilisateur ayant le statut scolarit� peut �diter les bulletins)</span><br/>
        <input type="checkbox" name="GepiProfImprBulSettings" value="yes" <?php if (getSettingValue("GepiProfImprBulSettings")=='yes') echo "checked"; ?> /> a acc�s au param�trage de l'impression des bulletins (lorsqu'il est autoris� � �diter/imprimer les bulletins)<br />
        <input type="checkbox" name="GepiAccesReleveProfP" value="yes" <?php if (getSettingValue("GepiAccesReleveProfP")=='yes') echo "checked"; ?> /> a acc�s aux relev�s des classes dont il est <?php echo getSettingValue("gepi_prof_suivi"); ?><br />
       	</td>
    </tr> 
    <tr>
        <td style="font-variant: small-caps;">
        La scolarit�
        </td>
       	<td>
            <input type="checkbox" name="GepiRubConseilScol" value="yes" <?php if (getSettingValue("GepiRubConseilScol")=='yes') echo "checked"; ?> /> peut saisir les avis du conseil de classe<br/>
            <input type="checkbox" name="GepiScolImprBulSettings" value="yes" <?php if (getSettingValue("GepiScolImprBulSettings")=='yes') echo "checked"; ?> /> a acc�s au param�trage de l'impression des bulletins<br />
        	<input type="checkbox" name="GepiAccesReleveScol" value="yes" <?php if (getSettingValue("GepiAccesReleveScol")=='yes') echo "checked"; ?> /> a acc�s � tous les relev�s de notes de toutes les classes<br/>
        	<input type="checkbox" name="GepiPasswordReinitScolarite" value="yes" <?php if (getSettingValue("GepiPasswordReinitScolarite")=='yes') echo "checked"; ?> /> peut r�initialiser elle-m�me son mot de passe perdu (si fonction activ�e)
       	</td>
    </tr> 
    <tr>
        <td style="font-variant: small-caps;">
        Le CPE
        </td>
       	<td>
        <input type="checkbox" name="GepiAccesReleveCpe" value="yes" <?php if (getSettingValue("GepiAccesReleveCpe")=='yes') echo "checked"; ?> /> a acc�s � tous les relev�s de notes de toutes les classes<br />
        <input type="checkbox" name="GepiPasswordReinitCpe" value="yes" <?php if (getSettingValue("GepiPasswordReinitCpe")=='yes') echo "checked"; ?> /> peut r�initialiser lui-m�me son mot de passe perdu (si fonction activ�e)
       	</td>
    </tr> 
    <tr>
        <td style="font-variant: small-caps;">
        L'administrateur
        </td>
       	<td>
			<input type="checkbox" name="GepiAdminImprBulSettings" value="yes" <?php if (getSettingValue("GepiAdminImprBulSettings")=='yes') echo "checked"; ?> /> a acc�s au param�trage de l'impression des bulletins<br/>
			<input type="checkbox" name="GepiPasswordReinitAdmin" value="yes" <?php if (getSettingValue("GepiPasswordReinitAdmin")=='yes') echo "checked"; ?> /> peut r�initialiser lui-m�me son mot de passe perdu (si fonction activ�e)
       	</td>
    </tr> 
    <tr>
        <td style="font-variant: small-caps;">
        L'�l�ve
        </td>
       	<td>
        	<input type="checkbox" name="GepiAccesReleveEleve" value="yes" <?php if (getSettingValue("GepiAccesReleveEleve")=='yes') echo "checked"; ?> /> a acc�s � ses relev�s de notes<br/>
        	<input type="checkbox" name="GepiAccesCahierTexteEleve" value="yes" <?php if (getSettingValue("GepiAccesCahierTexteEleve")=='yes') echo "checked"; ?> /> a acc�s � son cahier de texte<br/>
        	<input type="checkbox" name="GepiPasswordReinitEleve" value="yes" <?php if (getSettingValue("GepiPasswordReinitEleve")=='yes') echo "checked"; ?> /> peut r�initialiser lui-m�me son mot de passe perdu (si fonction activ�e)<br/>
        	<input type="checkbox" name="GepiAccesEquipePedaEleve" value="yes" <?php if (getSettingValue("GepiAccesEquipePedaEleve")=='yes') echo "checked"; ?> /> a acc�s � l'�quipe p�dagogique le concernant<br/>
        	<input type="checkbox" name="GepiAccesEquipePedaEmailEleve" value="yes" <?php if (getSettingValue("GepiAccesEquipePedaEmailEleve")=='yes') echo "checked"; ?> /> a acc�s � aux adresses email de l'�quipe p�dagogique le concernant<br/>
        	<input type="checkbox" name="GepiAccesBulletinSimpleEleve" value="yes" <?php if (getSettingValue("GepiAccesBulletinSimpleEleve")=='yes') echo "checked"; ?> /> a acc�s � ses bulletins simplifi�s<br/>
        	<input type="checkbox" name="GepiAccesGraphEleve" value="yes" <?php if (getSettingValue("GepiAccesGraphEleve")=='yes') echo "checked"; ?> /> a acc�s � la visualisation graphique de ses r�sultats
       	</td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Le responsable d'�l�ve
        </td>
       	<td>
        	<input type="checkbox" name="GepiAccesReleveParent" value="yes" <?php if (getSettingValue("GepiAccesReleveParent")=='yes') echo "checked"; ?> /> a acc�s aux relev�s de notes des �l�ves dont il est responsable<br/>
        	<input type="checkbox" name="GepiAccesCahierTexteParent" value="yes" <?php if (getSettingValue("GepiAccesCahierTexteParent")=='yes') echo "checked"; ?> /> a acc�s au cahier de texte des �l�ves dont il est responsable<br/>
        	<input type="checkbox" name="GepiPasswordReinitParent" value="yes" <?php if (getSettingValue("GepiPasswordReinitParent")=='yes') echo "checked"; ?> /> peut r�initialiser lui-m�me son mot de passe perdu (si fonction activ�e)<br/>
        	<input type="checkbox" name="GepiAccesEquipePedaParent" value="yes" <?php if (getSettingValue("GepiAccesEquipePedaParent")=='yes') echo "checked"; ?> /> a acc�s � l'�quipe p�dagogique concernant les �l�ves dont il est responsable<br/>
        	<input type="checkbox" name="GepiAccesEquipePedaEmailParent" value="yes" <?php if (getSettingValue("GepiAccesEquipePedaEmailParent")=='yes') echo "checked"; ?> /> a acc�s aux adresses email de l'�quipe p�dagogique concernant les �l�ves dont il est responsable<br/>
        	<input type="checkbox" name="GepiAccesBulletinSimpleParent" value="yes" <?php if (getSettingValue("GepiAccesBulletinSimpleParent")=='yes') echo "checked"; ?> /> a acc�s aux bulletins simplifi�s des �l�ves dont il est responsable<br/>
        	<input type="checkbox" name="GepiAccesGraphParent" value="yes" <?php if (getSettingValue("GepiAccesGraphParent")=='yes') echo "checked"; ?> /> a acc�s � la visualisation graphique des r�sultats des �l�ves dont il est responsable
       	</td>
    </tr> 
</table>
<input type="hidden" name="is_posted" value="1" />
<center><input type="submit" name = "OK" value="Enregistrer" style="font-variant: small-caps;" /></center>
</form>
<?php require("../lib/footer.inc.php");?>