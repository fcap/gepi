<?php
/*
 * Last modification  : 04/04/2005
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
$msg = '';
if (isset($_POST['activer'])) {
    if (!saveSetting("active_carnets_notes", $_POST['activer'])) $msg = "Erreur lors de l'enregistrement du param�tre activation/d�sactivation !";
}

if (isset($_POST['is_posted']) and ($msg=='')) $msg = "Les modifications ont �t� enregistr�es !";
// header
$titre_page = "Gestion des carnets de notes";
require_once("../lib/header.inc");
?>
<p class=bold><a href="../accueil_modules.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>
<h2>Configuration g�n�rale</h2>
<i>La d�sactivation des carnets de notes n'entra�ne aucune suppression des donn�es. Lorsque le module est d�sactiv�, les professeurs n'ont pas acc�s au module.</i>
<br />
<form action="index.php" name="form1" method="post">
<input type="radio" name="activer" value="y" <?php if (getSettingValue("active_carnets_notes")=='y') echo " checked"; ?> />&nbsp;Activer les carnets de notes<br />
<input type="radio" name="activer" value="n" <?php if (getSettingValue("active_carnets_notes")=='n') echo " checked"; ?> />&nbsp;D�sactiver les carnets de notes
<input type="hidden" name="is_posted" value="1" />
<center><input type="submit" value="Enregistrer" style="font-variant: small-caps;"/></center>
</form>
<?php require("../lib/footer.inc.php");?>