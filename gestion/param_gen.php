<?php
/*
 * Last modification  : 07/08/2006
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
if (isset($_POST['sup_logo'])) {
    $dest = '../images/';
     $ok = false;
     if ($f = @fopen("$dest/.test", "w")) {
        @fputs($f, '<'.'?php $ok = true; ?'.'>');
        @fclose($f);
        include("$dest/.test");
     }
     if (!$ok) {
         $msg = "Probl�me d'�criture sur le r�pertoire. Veuillez signaler ce probl�me � l'administrateur du site";
     } else {
        $old = getSettingValue("logo_etab");
        if (($old != '') and (file_exists($dest.$old))) unlink($dest.$old);
        $msg = "Le logo a �t� supprim�.";
        if (!saveSetting("logo_etab", '')) $msg .= "Erreur lors de l'enregistrement dans la table setting !";

     }

}
if (isset($_POST['valid_logo'])) {
   $doc_file = isset($_FILES["doc_file"]) ? $_FILES["doc_file"] : NULL;
   if (ereg("\.([^.]+)$", $doc_file['name'], $match)) {
       $ext = strtolower($match[1]);
       if ($ext!='jpg' and $ext!='png'and $ext!='gif') {
          $msg = "les seules extentions autoris�es sont gif, png et jpg";
       } else {
          $dest = '../images/';
          $ok = false;
          if ($f = @fopen("$dest/.test", "w")) {
             @fputs($f, '<'.'?php $ok = true; ?'.'>');
             @fclose($f);
             include("$dest/.test");
          }
          if (!$ok) {
              $msg = "Probl�me d'�criture sur le r�pertoire IMAGES. Veuillez signaler ce probl�me � l'administrateur du site";
          } else {
             $old = getSettingValue("logo_etab");
             if (file_exists($dest.$old)) @unlink($dest.$old);
             if (file_exists($dest.$doc_file)) @unlink($dest.$doc_file);
             $ok = @copy($doc_file['tmp_name'], $dest.$doc_file['name']);
             if (!$ok) $ok = @move_uploaded_file($doc_file['tmp_name'], $dest.$doc_file['name']);
             if (!$ok) {
                $msg = "Probl�me de transfert : le fichier n'a pas pu �tre transf�r� sur le r�pertoire IMAGES. Veuillez signaler ce probl�me � l'administrateur du site";
             } else {
                $msg = "Le fichier a �t� transf�r�.";
             }
             if (!saveSetting("logo_etab", $doc_file['name'])) {
             $msg .= "Erreur lors de l'enregistrement dans la table setting !";
             }

          }
       }
   } else {
       $msg = "Le fichier s�lectionn� n'est pas valide !";
   }
}
// Max session length
if (isset($_POST['sessionMaxLength'])) {
    if (!(ereg ("^[0-9]{1,}$", $_POST['sessionMaxLength'])) || $_POST['sessionMaxLength'] < 1) {
        $_POST['sessionMaxLength'] = 30;
    }
    if (!saveSetting("sessionMaxLength", $_POST['sessionMaxLength'])) {
        $msg .= "Erreur lors de l'enregistrement da dur�e max d'inactivit� !";
    }
}
if (isset($_POST['gepiYear'])) {
    if (!saveSetting("gepiYear", $_POST['gepiYear'])) {
        $msg .= "Erreur lors de l'enregistrement de l'ann�e scolaire !";
    }
}
if (isset($_POST['gepiSchoolName'])) {
    if (!saveSetting("gepiSchoolName", $_POST['gepiSchoolName'])) {
        $msg .= "Erreur lors de l'enregistrement du nom de l'�tablissement !";
    }
}
if (isset($_POST['gepiSchoolAdress1'])) {
    if (!saveSetting("gepiSchoolAdress1", $_POST['gepiSchoolAdress1'])) {
        $msg .= "Erreur lors de l'enregistrement de l'adresse !";
    }
}
if (isset($_POST['gepiSchoolAdress2'])) {
    if (!saveSetting("gepiSchoolAdress2", $_POST['gepiSchoolAdress2'])) {
        $msg .= "Erreur lors de l'enregistrement de l'adresse !";
    }
}
if (isset($_POST['gepiSchoolZipCode'])) {
    if (!saveSetting("gepiSchoolZipCode", $_POST['gepiSchoolZipCode'])) {
        $msg .= "Erreur lors de l'enregistrement du code postal !";
    }
}
if (isset($_POST['gepiSchoolCity'])) {
    if (!saveSetting("gepiSchoolCity", $_POST['gepiSchoolCity'])) {
        $msg .= "Erreur lors de l'enregistrement de la ville !";
    }
}
if (isset($_POST['gepiSchoolTel'])) {
    if (!saveSetting("gepiSchoolTel", $_POST['gepiSchoolTel'])) {
        $msg .= "Erreur lors de l'enregistrement du num�ro de t�l�phone !";
    }
}
if (isset($_POST['gepiSchoolFax'])) {
    if (!saveSetting("gepiSchoolFax", $_POST['gepiSchoolFax'])) {
        $msg .= "Erreur lors de l'enregistrement du num�ro de fax !";
    }
}
if (isset($_POST['gepiSchoolEmail'])) {
    if (!saveSetting("gepiSchoolEmail", $_POST['gepiSchoolEmail'])) {
        $msg .= "Erreur lors de l'adresse �lectronique !";
    }
}
if (isset($_POST['gepiAdminNom'])) {
    if (!saveSetting("gepiAdminNom", $_POST['gepiAdminNom'])) {
        $msg .= "Erreur lors de l'enregistrement du nom de l'administrateur !";
    }
}
if (isset($_POST['gepiAdminPrenom'])) {
    if (!saveSetting("gepiAdminPrenom", $_POST['gepiAdminPrenom'])) {
        $msg .= "Erreur lors de l'enregistrement du pr�nom de l'administrateur !";
    }
}
if (isset($_POST['gepiAdminFonction'])) {
    if (!saveSetting("gepiAdminFonction", $_POST['gepiAdminFonction'])) {
        $msg .= "Erreur lors de l'enregistrement de la fonction de l'administrateur !";
    }
}

if (isset($_POST['gepiAdminAdress'])) {
    if (!saveSetting("gepiAdminAdress", $_POST['gepiAdminAdress'])) {
        $msg .= "Erreur lors de l'enregistrement de l'adresse email !";
    }
}
if (isset($_POST['longmin_pwd'])) {
    if (!saveSetting("longmin_pwd", $_POST['longmin_pwd'])) {
        $msg .= "Erreur lors de l'enregistrement de la longueur minimale du mot de passe !";
    }
}
if (isset($_POST['gepi_prof_suivi'])) {
    if (!saveSetting("gepi_prof_suivi", $_POST['gepi_prof_suivi'])) {
        $msg .= "Erreur lors de l'enregistrement de gepi_prof_suivi !";
    }
}


// Initialiser � 'Boite'
if (isset($_POST['gepi_denom_boite'])) {
    if (!saveSetting("gepi_denom_boite", $_POST['gepi_denom_boite'])) {
        $msg .= "Erreur lors de l'enregistrement de gepi_denom_boite !";
    }
}
if (isset($_POST['gepi_denom_boite_genre'])) {
    if (!saveSetting("gepi_denom_boite_genre", $_POST['gepi_denom_boite_genre'])) {
        $msg .= "Erreur lors de l'enregistrement de gepi_denom_boite_genre !";
    }
}

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
}

if (isset($_POST['num_enregistrement_cnil'])) {
    if (!saveSetting("num_enregistrement_cnil", $_POST['num_enregistrement_cnil'])) {
        $msg .= "Erreur lors de l'enregistrement du num�ro d'enregistrement � la CNIL !";
    }
}

if (isset($_POST['mode_generation_login'])) {
    if (!saveSetting("mode_generation_login", $_POST['mode_generation_login'])) {
        $msg .= "Erreur lors de l'enregistrement du mode de g�n�ration des logins !";
    }
}

// Load settings
if (!loadSettings()) {
    die("Erreur chargement settings");
}
if (isset($_POST['is_posted']) and ($msg=='')) $msg = "Les modifications ont �t� enregistr�es !";

// End standart header
$titre_page = "Param�tres g�n�raux";
require_once("../lib/header.inc");
?>
<p class=bold>|<a href="index.php">Retour</a>|</p>
<form action="param_gen.php" method="post" name="form1" style="width: 100%;">
<table style="width: 100%; border: 0;" cellpadding="5" cellspacing="5">
    <tr>
        <td style="width: 60%;font-variant: small-caps;">
        Ann�e scolaire :
        </td>
        <td><input type="text" name="gepiYear" size="20" value="<?php echo(getSettingValue("gepiYear")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Nom de l'�tablissement :
        </td>
        <td><input type="text" name="gepiSchoolName" size="20" value="<?php echo(getSettingValue("gepiSchoolName")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Adresse de l'�tablissement :
        </td>
        <td><input type="text" name="gepiSchoolAdress1" size="40" value="<?php echo(getSettingValue("gepiSchoolAdress1")); ?>" /><br />
        <input type="text" name="gepiSchoolAdress2" size="40" value="<?php echo(getSettingValue("gepiSchoolAdress2")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Code postal :
        </td>
        <td><input type="text" name="gepiSchoolZipCode" size="20" value="<?php echo(getSettingValue("gepiSchoolZipCode")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Ville :
        </td>
        <td><input type="text" name="gepiSchoolCity" size="20" value="<?php echo(getSettingValue("gepiSchoolCity")); ?>" />
        </td>
    </tr>
        <tr>
        <td style="font-variant: small-caps;">
        T�l�phone �tablissement :
        </td>
        <td><input type="text" name="gepiSchoolTel" size="20" value="<?php echo(getSettingValue("gepiSchoolTel")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Fax �tablissement :
        </td>
        <td><input type="text" name="gepiSchoolFax" size="20" value="<?php echo(getSettingValue("gepiSchoolFax")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        E-mail �tablissement :
        </td>
        <td><input type="text" name="gepiSchoolEmail" size="20" value="<?php echo(getSettingValue("gepiSchoolEmail")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Nom de l'administrateur du site :
        </td>
        <td><input type="text" name="gepiAdminNom" size="20" value="<?php echo(getSettingValue("gepiAdminNom")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Pr�nom de l'administrateur du site :
        </td>
        <td><input type="text" name="gepiAdminPrenom" size="20" value="<?php echo(getSettingValue("gepiAdminPrenom")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Fonction de l'administrateur du site :
        </td>
        <td><input type="text" name="gepiAdminFonction" size="20" value="<?php echo(getSettingValue("gepiAdminFonction")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Email de l'administrateur du site :
        </td>
        <td><input type="text" name="gepiAdminAdress" size="20" value="<?php echo(getSettingValue("gepiAdminAdress")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Dur�e maximum d'inactivit� : <br />
        <span class='small'>(Dur�e d'inactivit�, en minutes, au bout de laquelle un utilisateur est automatiquement d�connect� de Gepi.)</span>
        </td>
        <td><input type="text" name="sessionMaxLength" size="20" value="<?php echo(getSettingValue("sessionMaxLength")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Longueur minimale du mot de passse :</td>
        <td><input type="text" name="longmin_pwd" size="20" value="<?php echo(getSettingValue("longmin_pwd")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        D�nomination du professeur charg� du suivi des �l�ves :</td>
        <td><input type="text" name="gepi_prof_suivi" size="20" value="<?php echo(getSettingValue("gepi_prof_suivi")); ?>" />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;" valign='top'>
        D�signation des boites/conteneurs/emplacements/sous-mati�res :</td>
        <td>
        <input type="text" name="gepi_denom_boite" size="20" value="<?php echo(getSettingValue("gepi_denom_boite")); ?>" /><br />
        <table><tr valign='top'><td>Genre:</td><td>
        <input type="radio" name="gepi_denom_boite_genre" value="m" <?php if(getSettingValue("gepi_denom_boite_genre")=="m"){echo 'checked';} ?> /> M<br />
        <input type="radio" name="gepi_denom_boite_genre" value="f" <?php if(getSettingValue("gepi_denom_boite_genre")=="f"){echo 'checked';} ?> /> F<br />
        </td></tr></table>
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Les avis des conseils de classe peuvent �tre saisis par : </td>
        <td><input type="checkbox" name="GepiRubConseilProf" value="yes" <?php if (getSettingValue("GepiRubConseilProf")=='yes') echo "checked"; ?> /> Le <?php echo getSettingValue("gepi_prof_suivi"); ?> (lorsqu'il est d�fini)<br />
            <input type="checkbox" name="GepiRubConseilScol" value="yes" <?php if (getSettingValue("GepiRubConseilScol")=='yes') echo "checked"; ?> /> Le service scolarit�
        </td>
    </tr>
        <tr>
        <td style="font-variant: small-caps;">
        Le <?php echo getSettingValue("gepi_prof_suivi");?> �dite/imprime les bulletins p�riodiques des classes dont il a la charge.<br />
        <span class='small'>(Par d�faut, seul un utilisateur ayant le statut scolarit� peut �diter les bulletins)</span></td>
        <td><input type="checkbox" name="GepiProfImprBul" value="yes" <?php if (getSettingValue("GepiProfImprBul")=='yes') echo "checked"; ?> />
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Les utilisateurs suivants ont acc�s au param�trage de l'impression des bulletins :</td>
        <td>
        <input type="checkbox" name="GepiAdminImprBulSettings" value="yes" <?php if (getSettingValue("GepiAdminImprBulSettings")=='yes') echo "checked"; ?> /> L'administrateur de Gepi<br />
        <input type="checkbox" name="GepiProfImprBulSettings" value="yes" <?php if (getSettingValue("GepiProfImprBulSettings")=='yes') echo "checked"; ?> /> Le <?php echo getSettingValue("gepi_prof_suivi"); ?> (lorsqu'il est autoris� � �diter/imprimer les bulletins)<br />
        <input type="checkbox" name="GepiScolImprBulSettings" value="yes" <?php if (getSettingValue("GepiScolImprBulSettings")=='yes') echo "checked"; ?> /> Le service scolarit�
        </td>
    </tr>
    <tr>
        <td style="font-variant: small-caps;">
        Conditions d'acc�s aux relev�s de notes :</td>
        <td>
        <input type="checkbox" name="GepiAccesReleveScol" value="yes" <?php if (getSettingValue("GepiAccesReleveScol")=='yes') echo "checked"; ?> /> Le service scolarit� acc�de � tous les relev�s de toutes les classes<br />
        <input type="checkbox" name="GepiAccesReleveCpe" value="yes" <?php if (getSettingValue("GepiAccesReleveCpe")=='yes') echo "checked"; ?> /> Le/les CPE acc�de(nt) � tous les relev�s de toutes les classes<br />
        <input type="checkbox" name="GepiAccesReleveProfP" value="yes" <?php if (getSettingValue("GepiAccesReleveProfP")=='yes') echo "checked"; ?> /> Le <?php echo getSettingValue("gepi_prof_suivi"); ?> acc�de aux relev�s des classes dont il est <?php echo getSettingValue("gepi_prof_suivi"); ?><br />
        <input type="checkbox" name="GepiAccesReleveProf" value="yes" <?php if (getSettingValue("GepiAccesReleveProf")=='yes') echo "checked"; ?> /> Le professeur acc�de aux relev�s des �l�ves des classes dans lesquelles il enseigne<br />
        <input type="checkbox" name="GepiAccesReleveProfTousEleves" value="yes" <?php if (getSettingValue("GepiAccesReleveProfTousEleves")=='yes') echo "checked"; ?> /> Le professeur acc�de aux relev�s de tous les �l�ves des classes dans lesquelles il enseigne (si case non coch�e, le professeur ne voit que les �l�ves de ses groupes d'enseignement et pas les autres �l�ves des classes concern�es)
        </td>
    </tr>

    <tr>
        <td style="font-variant: small-caps;">
        Mode de g�n�ration automatique des logins :</td>
       <td>
       <select name='mode_generation_login'>
    		<option value='name8'<?php if (getSettingValue("mode_generation_login")=='name8') echo " SELECTED"; ?>> nom (tronqu� � 8 caract�res)</option>
    		<option value='fname8'<?php if (getSettingValue("mode_generation_login")=='fname8') echo " SELECTED"; ?>> pnom (tronqu� � 8 caract�res)</option>
    		<option value='fname19'<?php if (getSettingValue("mode_generation_login")=='fname19') echo " SELECTED"; ?>> pnom (tronqu� � 19 caract�res)</option>
    		<option value='firstdotname'<?php if (getSettingValue("mode_generation_login")=='firstdotname') echo " SELECTED"; ?>> prenom.nom</option>
    		<option value='firstdotname19'<?php if (getSettingValue("mode_generation_login")=='firstdotname19') echo " SELECTED"; ?>> prenom.nom (tronqu� � 19 caract�res)</option>
    		<option value='namef8'<?php if (getSettingValue("mode_generation_login")=='namef8') echo " SELECTED"; ?>> nomp (tronqu� � 8 caract�res)</option>
       </select>
       </td>
    </tr>

    <tr>
        <td style="font-variant: small-caps;">
        N� d'enregistrement � la CNIL : <br />
        <span class='small'>Conform�ment � l'article 16 de la loi 78-17 du 6 janvier 1978, dite loi informatique et libert�,
        cette installation de GEPI doit faire l'objet d'une d�claration de traitement automatis� d'informations nominatives aupr�s
        de la CNIL. Si ce n'est pas encore le cas, laissez libre le champ ci-contre</span>
        </td>
        <td><input type="text" name="num_enregistrement_cnil" size="20" value="<?php echo(getSettingValue("num_enregistrement_cnil")); ?>" />
        </td>
    </tr>




</table>
<input type="hidden" name="is_posted" value="1" />
<center><input type="submit" name = "OK" value="Enregistrer" style="font-variant: small-caps;" /></center>
</form>
<hr />
<form enctype="multipart/form-data" action="param_gen.php" method="post" name="form2" style="width: 100%;">
<table border=0 cellpadding="5" cellspacing="5">
<?php
echo "<tr><td colspan=2 style=\"font-variant: small-caps;\"><b>Logo de l'�tablissement : </b></td></tr>\n";
echo "<tr><td colspan=2>Le logo est visible sur les bulletins officiels, ainsi que sur la page d'accueil publique des cahiers de texte</td></tr>\n";
echo "<tr><td>Modifier le Logo (png, jpg et gif uniquement) : ";
echo "<INPUT TYPE=FILE NAME=\"doc_file\" />\n";
echo "<INPUT TYPE=SUBMIT name = \"valid_logo\" value = \"Enregistrer\" /><br />\n";
echo "Supprimer le logo : <INPUT TYPE=SUBMIT name = \"sup_logo\" value = \"Supprimer le logo\" /></td>\n";


$nom_fic_logo = getSettingValue("logo_etab");

$nom_fic_logo_c = "../images/".$nom_fic_logo;
if (($nom_fic_logo != '') and (file_exists($nom_fic_logo_c))) {
   echo "<td><b>Logo actuel : </b><br /><IMG SRC=\"".$nom_fic_logo_c."\" BORDER=0 ALT=\"logo\" /></td>\n";
} else {
   echo "<td><b><i>Pas de logo actuellement</i></b></td>\n";
}
echo "</tr></table></form>\n";
?>

</body>
</html>