<?php
/* $Id$
*
* Copyright 2001, 2008 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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




/* ---------Variables envoy�es au gabarit
*	$tbs_CdT_public_titre						cahier de textes public activ�
*	$tbs_multisite									rne si on est en multisite
*	$tbs_gepiSchoolName							nom de l'�tablissement
*	$tbs_gepiYear										ann�e scolaire en cours
*	$tbs_password_recovery					adresse page de r�cup�ration de mot de passe oubli�
* $tbs_SSO_lien										adresse page de login SSO
*	$tbs_admin_java									nom du script pour contacter l'administrateur
*	$tbsStyleScreenAjout						chemin du fichier Style_Screen_Ajout.css
*	
*	----- tableaux -----
*	$tbs_Site_ferme									message de fermeture									tbs_blk1
* $tbs_message										message sous l'ent�te									tbs_message
*				-> classe									classe CSS ("" ou "txt_rouge")
*				-> texte									le texte � afficher
*	$tbs_admin_adr									adresse courriel administrateur				tbs_blk2
*				-> nom
*				-> fai
* $tbs_dossier_gabarit						liste des gabarits disponibles				tbs_blk3
*				-> texte									texte � afficher dans la liste de choix
*				-> value									nom du dossier
*				-> selection							`y` si gabarit par d�faut, `n` ou rien sinon
*/


/*
table � ajouter pour pouvoir utiliser plusieurs gabarits et donn�es du gabarit d'origine 

CREATE TABLE `gabarits` (
`index` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`texte` VARCHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`repertoire` VARCHAR( 16 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
`pardefaut` CHAR( 1 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'n'
) ENGINE = MYISAM ;

INSERT INTO `gabarits` (
`index` ,
`texte` ,
`repertoire` ,
`pardefaut`
)
VALUES (
NULL , 'Interface de GEPI', 'origine', 'n'
);


*/

//test version de php
if (version_compare(PHP_VERSION, '5') < 0) {
    die('GEPI n�cessite PHP5 pour fonctionner');
}




// Pour le tbs_multisite
if (isset($_GET["rne"])) {
	setcookie('RNE', $_GET["rne"], null, '/');
}

// V�rification de la bonne installation de GEPI
require_once("./utilitaires/verif_install.php");

$niveau_arbo = 0;

// On indique qu'il faut cr�er des variables non prot�g�es (voir fonction cree_variables_non_protegees())
$variables_non_protegees = 'yes';

// Initialisations files
require_once("./lib/initialisations.inc.php");

// Si on est sur LCS, on r�cup�re l'identit� de connexion:
//if ($is_lcs_plugin=='yes') {list ($idpers,$login) = isauth();}
// Inutile, c'est d�j� fait dans lib/initialisations.inc.php

# On redirige vers le login SSO si le login local ou ldap n'est pas activ�.
//if ($session_gepi->auth_sso && !$session_gepi->auth_locale && !$session_gepi->auth_ldap) {
if (($session_gepi->auth_sso && !$session_gepi->auth_locale && ! $session_gepi->auth_ldap) ||
(($is_lcs_plugin=='yes')&&($login!=""))) {
	header("Location:login_sso.php");
	exit();
}

if ($session_gepi->auth_simpleSAML == 'yes') {
	//l'authentification est faite pour chaque page par simpleSAML, pas besoin de page d'authentification
	header("Location: ./accueil.php");
	die();
}

// Test de mise � jour : si on d�tecte que la base n'est � jour avec les nouveaux
// param�tres utilis�s pour l'authentification, on redirige vers maj.php pour
// une mise � jour, normale ou forc�e.
if (!isset($gepiSettings['auth_sso'])) {
	header("Location:utilitaires/maj.php");
	exit();
}

// Authentification Classique et Ldap
//-----------------------------------


if ($session_gepi->auth_locale && isset($_POST['login']) && isset($_POST['no_anti_inject_password'])) {

	$auth = $session_gepi->authenticate($_POST['login'], $NON_PROTECT['password']);

	if ($auth == "1") {
		// On renvoie � la page d'accueil
		session_write_close();
		header("Location: ./accueil.php");
		die();

	} else {
		header("Location: ./login_failure.php?error=".$auth);
		die();
	}
}
?>



<?php

$test = 'templates/accueil_externe.php' ;


//==================================
//Site en maintenance
	$tbs_Site_ferme = array();
	if ((getSettingValue("disable_login"))!='no'){
		// Fermeture du site � afficher en rouge et plus grand
		$tbs_Site_ferme[0] = "Le site est en cours de maintenance et temporairement inaccessible.";
		$tbs_Site_ferme[1] = "Veuillez nous excuser de ce d�rangement et r�essayer de vous connecter ult�rieurement.";
	}


//==================================
//On v�rifie si le module cahiers de textes public est activ�
	$tbs_CdT_public_titre =  "" ;
	if (getSettingValue("active_cahiers_texte")=='y' and getSettingValue("cahier_texte_acces_public") == "yes" and getSettingValue("disable_login")!='yes') {
		$tbs_CdT_public_titre = "Consulter les cahiers de textes (acc�s public)";
	}
//==================================
//Utilisation tbs_multisite
	$tbs_multisite = "";
	if ($multisite == "y" AND isset($_GET["rne"]) AND $_GET["rne"] != '') {
		$tbs_multisite = $_GET["rne"];
	}

//==================================
//      Cadre identification
//==================================

//==================================
//Nom ann�e
	$tbs_gepiSchoolName = getSettingValue("gepiSchoolName");
	$tbs_gepiYear = getSettingValue("gepiYear");
	
//==================================
//Message
	if (isset($message)) {
		$tbs_message[] =array("classe"=>"txt_rouge","texte" => $message);
	} else {
		//$tbs_message_class = "message";
		$tbs_message[] =array("classe"=>"","texte" => "Afin d'utiliser Gepi, vous devez vous identifier.");
	}
	
//==================================
//	Mot de passe oubli�
	$tbs_password_recovery = "";
	if (getSettingValue("enable_password_recovery") == "yes") {
		$tbs_password_recovery = "recover_password.php";
	}	
	
//==================================
//	authentification unique
	$tbs_SSO_lien = "";
	if ($session_gepi->auth_sso) {
		$tbs_SSO_lien = 'login_sso.php';
	// ajouter un test sur plugin_sso_table
		if (mb_strlen(getSettingValue('login_sso_url'))>0) {
			$tbs_SSO_lien = getSettingValue('login_sso_url');
		}
	}

	
//==================================
//	Feuille de style style_screen_ajout.css
if (isset($style_screen_ajout))  {

	// Styles param�trables depuis l'interface:
	if($style_screen_ajout=='y') {
		if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y') {
			if (@file_exists('./style_screen_ajout_'.getSettingValue("gepiSchoolRne").'.css')) {
				$tbsStyleScreenAjout=$gepiPath."/style_screen_ajout_".getSettingValue("gepiSchoolRne").".css";	
			}else {
				$tbsStyleScreenAjout="n";	
			}
		} else {
			if (@file_exists('./style_screen_ajout.css')) {
				$tbsStyleScreenAjout=$gepiPath."/style_screen_ajout.css";	
			}else {
				$tbsStyleScreenAjout="n";	
			}
		}
	} else {
		$tbsStyleScreenAjout="n";	
	}
} else {
	$tbsStyleScreenAjout="n";	
}
	


//==================================
//	administrateurs
	$tbs_admin_adr=array();
	$tbs_admin_titre="";
	if(getSettingValue("gepiAdminAdressPageLogin")!='n'){
		$gepiAdminAdress=getSettingValue("gepiAdminAdress");
		//$tmp_adr=explode("@",$gepiAdminAdress);
		//echo("<a href=\"javascript:pigeon('$tmp_adr[0]','$tmp_adr[1]');\">[Contacter l'administrateur]</a> \n");
		//echo "$gepiAdminAdress<br />";
		//$compteur=0;
		$tab_adr=array();
		$tmp_adr1=explode(",",$gepiAdminAdress);
		for($i=0;$i<count($tmp_adr1);$i++){
			//echo "\$tmp_adr1[$i]=$tmp_adr1[$i]<br />";
			$tmp_adr2=explode("@",$tmp_adr1[$i]);
			//echo "\$tmp_adr2[0]=$tmp_adr2[0]<br />";
			//echo "\$tmp_adr2[1]=$tmp_adr2[1]<br />";
			if((isset($tmp_adr2[0]))&&(isset($tmp_adr2[1]))) {
				$tbs_admin_adr[]=array("nom"=>$tmp_adr2[0] , "fai"=>$tmp_adr2[1]);
				/*
				$tab_adr[$compteur]=$tmp_adr2[0];
				$compteur++;
				$tab_adr[$compteur]=$tmp_adr2[1];
				$compteur++;
				*/
			}
		}

		//echo "<script type='text/javascript'>\n";
		//echo "adm_adr=new Array();\n";
		/*
			for($i=0;$i<count($tab_adr);$i++){
				echo "adm_adr[$i]='$tab_adr[$i]';\n";
			}
		//echo "</script>\n";
		if(count($tab_adr)>0){
			//echo("<a href=\"javascript:pigeon2(adm_adr);\">[Contacter l'administrateur]</a> \n");
			//echo("<p><a href=\"javascript:pigeon2();\">[Contacter l'administrateur]</a></p>\n");
		}
		*/
	}
	

//==================================

$msg_page_login="";
$test = mysql_query("SHOW TABLES LIKE 'message_login'");
if(mysql_num_rows($test)>0) {
	$sql="SELECT ml.texte FROM message_login ml, setting s WHERE s.value=ml.id AND s.name='message_login';";
	//echo "$sql <br />";
	$res=mysql_query($sql);

	if(mysql_num_rows($res)>0) {
		$lig_page_login=mysql_fetch_object($res);
		$msg_page_login=$lig_page_login->texte;
	}
}

//==================================
//	gabarits dynamiques




//==================================
//	switcher de gabarits

	$tbs_dossier_gabarit=array();


$test = mysql_query("SHOW TABLES LIKE 'gabarits'");

		$sql="SELECT texte, repertoire, pardefaut FROM gabarits ;";
		$res_gab=mysql_query($sql);
	if($res_gab){
	
		if(mysql_num_rows($res_gab)>0) {
			while($lig_gab=mysql_fetch_object($res_gab)) {
				$texte_gab=$lig_gab->texte;
				$repertoire_gab=$lig_gab->repertoire;
				$defaut_gab=$lig_gab->pardefaut;
				if($defaut_gab=="y"){
					$value_gab="selected='selected'";
					$gabarit=$lig_gab->repertoire;
				}else{
					$value_gab="";
				}
			$tbs_dossier_gabarit[]=array("texte"=>$texte_gab, "selection"=>$value_gab, "value"=>$repertoire_gab);	
			}
		}
		
	}else{
		$gabarit="origine";
	}

	if ((isset($_GET['template'])) or (isset($_POST['template'])) or (isset($gabarit))) {
		$gabarit = isset($_POST['template']) ? unslashes($_POST['template']) : (isset($_GET['template']) ? unslashes($_GET['template']) : $gabarit);
	}
	else{
		$gabarit="origine";
	}
	

	
//==================================
// D�commenter la ligne ci-dessous pour afficher les variables $_GET, $_POST, $_SESSION et $_SERVER pour DEBUG:
//debug_var();

// appel des biblioth�ques tinyButStrong

		
$_SESSION['tbs_class'] = 'tbs/tbs_class.php';
include_once($_SESSION['tbs_class']);
			
		

	$_SESSION['rep_gabarits'] = $gabarit;

//==================================
// Appel de script externe
	
	$entete_externe = "templates/".$_SESSION['rep_gabarits']."/login_entete_externe.php" ;
	$corps_externe = "templates/".$_SESSION['rep_gabarits']."/login_corps_externe.php" ;
	$pied_externe = "templates/".$_SESSION['rep_gabarits']."/login_pied_externe.php" ;

	$fichier_gabarits='templates/'.$_SESSION['rep_gabarits'].'/login_template.html' ;
		
	$TBS = new clsTinyButStrong ;
	$TBS->LoadTemplate($fichier_gabarits) ;
	$TBS->MergeBlock('tbs_blk1',$tbs_Site_ferme);
	$TBS->MergeBlock('tbs_blk2',$tbs_admin_adr);
	$TBS->MergeBlock('tbs_blk3',$tbs_dossier_gabarit);
	$TBS->MergeBlock("tbs_message",$tbs_message);
	/*
	if(isset($lig_page_login)) {
		$TBS->MergeBlock("msg_page_login",$lig_page_login);
	}
	if(isset($msg_page_login)) {
		$TBS->MergeBlock("msg_page_login",$msg_page_login);
	}
	*/

	$TBS->Show() ;

// ------ on vide les tableaux -----
	unset($tbs_Site_ferme,$tbs_admin_adr,$tbs_dossier_gabarit,$tbs_message);

?> 
