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


// Initialisations files
$niveau_arbo = 0;
require_once("./lib/initialisations.inc.php");
global $gepiPath;

// On r�cup�re le dossier temporaire pour l'effacer
if (isset($_SESSION['login'])){
  $temp_perso="temp/".get_user_temp_directory();
}else{
  $temp_perso=NULL;
}

$rne_courant="";
if(($multisite=='y')&&(isset($_COOKIE['RNE']))) {
	$rne_courant=$_COOKIE['RNE'];
}

if ($session_gepi->current_auth_mode == "sso" and $session_gepi->auth_sso == "cas") {
  $session_gepi->close(0);
  $session_gepi->logout_cas();
  // On efface le dossier temporaire
  if ($temp_perso){
	foreach (glob($temp_perso."/*") as $filename) {
	  if (is_file($filename) && (!strstr($filename, 'index.html'))){
		@unlink ($filename);
	  }
	}
	unset ($filename);
  }
  die();
}

if (getSettingValue('gepiEnableIdpSaml20') == 'yes' && (!isset($_REQUEST['idploggedout']))) {
		include_once(dirname(__FILE__).'/lib/simplesaml/lib/_autoload.php');
		$auth = new SimpleSAML_Auth_GepiSimple();
		if ($auth->isAuthenticated()) {
			//on fait le logout de session avec simplesaml en tant que fournisseur d'identit�. �a va d�connecter uniqement les services associ�s.
			//Si gepi n'est pas connect� en local, il faut revenir � la page de logout et passer � la d�connexion de gepi 
			$logout_return_url = $_SERVER['REQUEST_URI'];
			if (strpos($logout_return_url, '?')) {
				$logout_return_url .= '&';
			} else {
				$logout_return_url .= '?';
			}
			$logout_return_url .= 'idploggedout=done';
			header("Location:./lib/simplesaml/www/saml2/idp/SingleLogoutService.php?ReturnTo=".urlencode($logout_return_url));
			exit();
		}
}
//print_r($session_gepi);die;

//$message = "<h1 class='gepi'>D�connexion</h1>";
    $titre= "D�connexion";
    $message = "";
    	
    if (!isset($_GET['auto']) || !$_GET['auto']) {
    	$session_gepi->close(0);
        $message .= "Vous avez ferm� votre session GEPI.";
        //$message .= "<a href=\"$gepiPath/login.php\">Ouvrir une nouvelle session</a>.";
    } else if ($_GET['auto']==2) {
        $session_gepi->close($_GET['auto']);
        $message .= "Vous avez �t� d�connect�. Il peut s'agir d'une mauvaise configuration de la variable \$GepiPath dans le fichier \"connect.inc.php\"<br />
        <a href='aide_gepipath.php'><b>Aide � la configuration de \$GepiPath</b></a>";
        //$message .= "<a href=\"$gepiPath/login.php\">Ouvrir une nouvelle session</a>.";
    } else if ($_GET['auto']==3) {
        $date_fermeture = date("d\/m\/Y\ \�\ H\ \h\ i");
        $debut_session = urldecode($_GET['debut_session']);
        $sql = "select now() > END TIMEOUT from log where SESSION_ID = '" . $_GET['session_id'] . "' and START = '" . $debut_session . "'";
        if (sql_query1($sql)) {
           // Le temps d'inactivit� est d�pass�
           $session_gepi->close($_GET['auto']);
           $message .= "Votre session GEPI a expir� car le temps maximum (".getSettingValue("sessionMaxLength")." minutes) sans �change avec le serveur a �t� atteint.<br /><br />Date et heure de la d�connexion : ".$date_fermeture."";
           //$message .= "<a href=\"$gepiPath/login.php\">Ouvrir une nouvelle session</a>.";
        } else {
           $message .= "<h1 class='gepi'>Fermeture d'une fen�tre GEPI</h1>";
           $titre= "Fermeture d'une fen�tre GEPI";
           /*
			$message .= "A l'heure ci-dessous, une fen�tre GEPI s'est automatiquement ferm�e par mesure de s�curit� car
           le temps maximum d'inactivit� (".getSettingValue("sessionMaxLength")." minutes) avait �t� atteint.<br /><br />
           Heure et date de fermeture de la fen�tre : ".$date_fermeture;
           */
			$message .= "A l'heure ci-dessous, une fen�tre GEPI s'est automatiquement ferm�e par mesure de s�curit�. Le temps maximum de ".getSettingValue("sessionMaxLength")." minutes sans �change avec le serveur a sans doute �t� atteint.<br /><br />
           Heure et date de fermeture de la fen�tre : ".$date_fermeture;
           //$message .= "<a href=\"$gepiPath/login.php\">Ouvrir une nouvelle session</a>.";
        }
    } else {
        $session_gepi->close($_GET['auto']);
        $message .= "Votre session GEPI a expir�, ou bien vous avez �t� d�connect�.<br />";
        if ((getSettingValue("disable_login"))=='yes')  {
        	$message .=  "<br /><span class=\"rouge gras\">Le site est momentan�ment inaccessible. Veuillez nous excuser de ce d�rangement !<span>";
        }
        //$message .= "<a href=\"$gepiPath/login.php\">Ouvrir une nouvelle session</a>.";
    }

if(getSettingValue('temporary_dir_no_cleaning')!='yes') {
	// On efface le dossier temporaire
	if ($temp_perso) {
		foreach (glob($temp_perso."/*") as $filename) {
			if (is_file($filename) && (!strstr($filename, 'index.html'))){
				@unlink ($filename);
			}
		}
	unset ($filename);
	}
}

// Ajout pour le multisite
unset($_COOKIE['RNE']);
setcookie('RNE', 'unset', null, '/'); // permet d'effacer le contenu du cookie.
include('./templates/origine/logout_template.php');


?>
