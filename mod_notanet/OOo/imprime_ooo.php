<?php
/* $Id$ */
/*
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
$niveau_arbo = 2;
$mode_ooo="imprime";



include('init_secure.inc.php');



include('fiches_brevet.php');		// calcul du tableau de donn�es



// Remplacement des anciennes versions vers la nouvelle lib TinyDoc
// include_once('../../mod_ooo/lib/lib_mod_ooo.php');
// include_once('../../mod_ooo/lib/tbs_class.php');
// include_once('../../mod_ooo/lib/tbsooo_class.php');

$tempdir=get_user_temp_directory();

include_once('../../mod_ooo/lib/tinyButStrong.class.php');
include_once('../../mod_ooo/lib/tinyDoc.class.php');








//*****************************************************************************************************************************************
// else {

//=======================================
// AFFICHAGE DES DONN�ES
//=======================================

// Et maintenant on s'occupe du fichier proprement dit

//
//Les variables � modifier pour le traitement  du mod�le ooo
//
//Le chemin et le nom du fichier ooo � traiter (le mod�le de document)
switch($type_brevet){
	case '0':
		$nom_fichier_modele_ooo  ='fb_CLG_lv2.ods';
// Coll�ge LV2
	break;
	case '1':
		$nom_fichier_modele_ooo ='fb_CLG_dp6.ods';
// Coll�ge DP6
	break;
	case '2':
		$nom_fichier_modele_ooo ='fb_PRO.ods';
// Professionnel sans option
	break;
	case '3':
		$nom_fichier_modele_ooo ='fb_PRO_dp6.ods';
// Professionnel DP6
	break;
	case '4':
		$nom_fichier_modele_ooo ='fb_PRO_agri.ods';
// Professionnel agricole
	break;
	case '5':
		$nom_fichier_modele_ooo  ='fb_TECHNO.ods';
// Technologique sans option
	break;
	case '6':
		$nom_fichier_modele_ooo ='fb_TECHNO_dp6.ods';
// Technologique DP6
	break;
	case '7':
		$nom_fichier_modele_ooo ='fb_TECHNO_agri.ods';
// Technologique agricole
	break;
	default:
	die();
}

// Par defaut tmp
$tempdirOOo="../../temp/".$tempdir;
$nom_dossier_temporaire = $tempdirOOo;
//par defaut content.xml
$nom_fichier_xml_a_traiter ='content.xml';
//les chemins contenant les donn�es
$fb_gab_perso=getSettingValue("fb_gab_perso");
if($fb_gab_perso=="1"){
  $nom_dossier_modele_a_utiliser="../../mod_ooo/mes_modeles/";
}
else{
  $nom_dossier_modele_a_utiliser="../../mod_ooo/modeles_gepi/";
}

// Cr�ation d'une classe tinyDoc
$OOo = new tinyDoc();
$OOo->setZipMethod('ziparchive');
// $OOo->setZipMethod('shell');
// $OOo->setZipBinary('zip');
// $OOo->setUnzipBinary('unzip');

// setting the object
$OOo->SetProcessDir($nom_dossier_temporaire ); //dossier o� se fait le traitement (d�compression / traitement / compression)
// create a new openoffice document from the template with an unique id
$OOo->createFrom($nom_dossier_modele_a_utiliser.$nom_fichier_modele_ooo); // le chemin du fichier est indiqu� � partir de l'emplacement de ce fichier
// merge data with openoffice file named 'content.xml'
$OOo->loadXml($nom_fichier_xml_a_traiter); //Le fichier qui contient les variables et doit �tre pars� (il sera extrait)


// Traitement des tableaux
// On ins�re ici les lignes concernant la gestion des tableaux

// $OOo->mergeXmlBlock('eleves',$tab_eleves_OOo);

$OOo->mergeXml(
	array(
		'name'      => 'eleves',
		'type'      => 'block',
		'data_type' => 'array',
		'charset'   => 'ISO 8859-15'
	 ),$tab_eleves_OOo);

$OOo->SaveXml(); //traitement du fichier extrait

//G�n�ration du nom du fichier
$now = gmdate('d_M_Y_H:i:s');
$nom_fichier_modele = explode('.',$nom_fichier_modele_ooo);
$nom_fic = $nom_fichier_modele[0]."_g�n�r�_le_".$now.".".$nom_fichier_modele[1];
header('Expires: ' . $now);
if (my_ereg('MSIE', $_SERVER['HTTP_USER_AGENT'])) {
	header('Content-Disposition: inline; filename="' . $nom_fic . '"');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
}
else {
	header('Content-Disposition: attachment; filename="' . $nom_fic . '"');
	header('Pragma: no-cache');
}
// display
header('Content-type: '.$OOo->getMimetype());
header('Content-Length: '.filesize($OOo->GetPathname()));

$OOo->sendResponse(); //envoi du fichier trait�
$OOo->remove(); //suppression des fichiers de travail
// Fin de traitement des tableaux
$OOo->close();

//=======================================
// FIN AFFICHAGE DES DONN�ES
//=======================================



?>
