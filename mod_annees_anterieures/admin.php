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

$accessibilite="y";
$titre_page = "Gestion du module Ann�es ant�rieures";
$gepiPathJava="./..";
$post_reussi=FALSE;
$msg = '';
$affiche_connexion = 'no';
$niveau_arbo = 1;

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

// Check access
if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}

//$msg = '';
if (isset($_POST['activer'])) {
	check_token();
    if (!saveSetting("active_annees_anterieures", $_POST['activer']))
			$msg = "Erreur lors de l'enregistrement du param�tre activation/d�sactivation !";
}



if (isset($_POST['is_posted']) and ($msg=='')){
  $msg = "Les modifications ont �t� enregistr�es !";
  $post_reussi=TRUE;
}
//**************** EN-TETE *******************************
// header
//$titre_page = "Gestion du module Ann�es ant�rieures";
//require_once("../lib/header.inc");
//**************** EN-TETE *******************************

// ====== Inclusion des balises head et du bandeau =====
include_once("../lib/header_template.inc");

if (!suivi_ariane($_SERVER['PHP_SELF'],"Gestion Ann�es ant�rieures"))
		echo "erreur lors de la cr�ation du fil d'ariane";
/****************************************************************
			FIN HAUT DE PAGE
****************************************************************/



/****************************************************************
			BAS DE PAGE
****************************************************************/
$tbs_microtime	="";
$tbs_pmv="";
require_once ("../lib/footer_template.inc.php");

/****************************************************************
			On s'assure que le nom du gabarit est bien renseign�
****************************************************************/
if ((!isset($_SESSION['rep_gabarits'])) || (empty($_SESSION['rep_gabarits']))) {
	$_SESSION['rep_gabarits']="origine";
}

//==================================
// D�commenter la ligne ci-dessous pour afficher les variables $_GET, $_POST, $_SESSION et $_SERVER pour DEBUG:
// $affiche_debug=debug_var();


$nom_gabarit = '../templates/'.$_SESSION['rep_gabarits'].'/mod_annees_anterieures/admin_template.php';

$tbs_last_connection=""; // On n'affiche pas les derni�res connexions
include($nom_gabarit);





/*
$quitter_la_page=isset($_GET['quitter_la_page']) ? $_GET['quitter_la_page'] : NULL;
if(!isset($quitter_la_page)){
	echo "<p class='bold'><a href='../accueil_modules.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></p>\n";
}
else {
	echo "<p class=bold><a href=\"#\"";
	echo " onclick=\"self.close();\"";
	echo ">Refermer la page</a></p>\n";
}

?>
<h2>Configuration g�n�rale</h2>
<i>La d�sactivation du module Ann�es ant�rieures n'entra�ne aucune suppression des donn�es. Lorsque le module est d�sactiv�, personne n'a acc�s au module.</i>
<br />
<form action="<?php
	echo $_SERVER['PHP_SELF'];
?>" name="form1" method="post">

<p>
<input type="radio" name="activer" id='activer_y' value="y" <?php if (getSettingValue("active_annees_anterieures")=='y') echo " checked"; ?> />&nbsp;<label for='activer_y' style='cursor: pointer;'>Activer le module Ann�es ant�rieures</label><br />
<input type="radio" name="activer" id='activer_n' value="n" <?php if (getSettingValue("active_annees_anterieures")=='n') echo " checked"; ?> />&nbsp;<label for='activer_n' style='cursor: pointer;'>D�sactiver le module Ann�es ant�rieures</label>
</p>

<input type="hidden" name="is_posted" value="1" />
<center><input type="submit" value="Enregistrer" style="font-variant: small-caps;"/></center>
</form>
<?php require("../lib/footer.inc.php"); */ ?>
