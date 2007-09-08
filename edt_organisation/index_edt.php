<?php

/**
 * Fichier de gestion de l'emploi du temps dans Gepi version 1.5.x
 *
 * index_edt.php
 * @copyright 2007
 */

	// D�finir le sous-titre
$calendrier = isset($_GET['calendrier']) ? $_GET['calendrier'] : (isset($_POST['calendrier']) ? $_POST['calendrier'] : NULL);
	if ($calendrier == "ok") {
		$sous_titre = " - <span class='legende'>Calendrier</span>";
	}
	else
	$sous_titre = "";

$titre_page = "Emploi du temps".$sous_titre;
$affiche_connexion = 'yes';
$niveau_arbo = 1;

// Initialisations files
require_once("../lib/initialisations.inc.php");

// fonctions edt
require_once("./fonctions_edt.php");

// Resume session
$resultat_session = resumeSession();
if ($resultat_session == 'c') {
   header("Location:utilisateurs/mon_compte.php?change_mdp=yes&retour=accueil#changemdp");
   die();
} else if ($resultat_session == '0') {
    header("Location: ../logout.php?auto=1");
    die();
}

// S�curit�
if (!checkAccess()) {
    header("Location: ../logout.php?auto=2");
    die();
}

// On ins�re l'ent�te de Gepi
require_once("../lib/header.inc");

// On ajoute le menu EdT
require_once("./menu.inc.php"); ?>


<br />
<!-- la page du corps de l'EdT -->

	<div id="lecorps">

<?php include($page_inc_edt); ?>

	</div>
<br />
<br />
<?php
// inclusion du footer
require("../lib/footer.inc.php");
?>
