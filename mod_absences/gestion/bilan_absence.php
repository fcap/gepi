<?php
/*
*
*$Id$
*
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

extract($_GET, EXTR_OVERWRITE);
extract($_POST, EXTR_OVERWRITE);

// Global configuration file
// Quand on est en SSL, IE n'arrive pas � ouvrir le PDF.
//Le probl�me peut �tre r�solu en ajoutant la ligne suivante :
Header('Pragma: public');

// Lorsque qu'on utilise une session PHP, parfois, IE n'affiche pas le PDF
// C'est un probl�me qui affecte certaines versions d'IE.
// Pour le contourner, on ajoutez la ligne suivante avant session_start() :
session_cache_limiter('private');


// Resume session
$resultat_session = resumeSession();
if ($resultat_session == 'c') {
header("Location: ../../utilisateurs/mon_compte.php?change_mdp=yes");
die();
} else if ($resultat_session == '0') {
    header("Location: ../../logout.php?auto=1");
die();
};

if (!checkAccess()) {
    header("Location: ../../logout.php?auto=1");
die();
}


define('FPDF_FONTPATH','../../fpdf/font/');
require('../../fpdf/fpdf.php');

// fonction de redimensionnement d'image
function redimensionne_logo($photo, $L_max, $H_max)
 {
	// prendre les informations sur l'image
	$info_image = getimagesize($photo);
	// largeur et hauteur de l'image d'origine
	$largeur = $info_image[0];
	$hauteur = $info_image[1];
	// largeur et/ou hauteur maximum � afficher en pixel
	 $taille_max_largeur = $L_max;
	 $taille_max_hauteur = $H_max;

	// calcule le ratio de redimensionnement
	 $ratio_l = $largeur / $taille_max_largeur;
	 $ratio_h = $hauteur / $taille_max_hauteur;
	 $ratio = ($ratio_l > $ratio_h)?$ratio_l:$ratio_h;

	// d�finit largeur et hauteur pour la nouvelle image
	 $nouvelle_largeur = $largeur / $ratio;
	 $nouvelle_hauteur = $hauteur / $ratio;

	// des Pixels vers Millimetres
	 $nouvelle_largeur = $nouvelle_largeur / 2.8346;
	 $nouvelle_hauteur = $nouvelle_hauteur / 2.8346;

	return array($nouvelle_largeur, $nouvelle_hauteur);
 }

   $date_ce_jour = date('d/m/Y');
    if (empty($_GET['classe']) and empty($_POST['classe'])) {$classe="";}
    else { if (isset($_GET['classe'])) {$classe=$_GET['classe'];} if (isset($_POST['classe'])) {$classe=$_POST['classe'];} }
    if (empty($_GET['eleve']) and empty($_POST['eleve'])) {$eleve="tous";}
    else { if (isset($_GET['eleve'])) {$eleve=$_GET['eleve'];} if (isset($_POST['eleve'])) {$eleve=$_POST['eleve'];} }
    if (empty($_GET['du']) and empty($_POST['du'])) {$du="$date_ce_jour";}
    else { if (isset($_GET['du'])) {$du=$_GET['du'];} if (isset($_POST['du'])) {$du=$_POST['du'];} }
    if (empty($_GET['au']) and empty($_POST['au'])) {$au="$du";}
    else { if (isset($_GET['au'])) {$au=$_GET['au'];} if (isset($_POST['au'])) {$au=$_POST['au'];} }

    if ($au == "" or $au == "JJ/MM/AAAA") { $au = $du; }

class bilan_PDF extends FPDF
{

    //En-t�te du document
    function Header()
    {
	    global $prefix_base;
			$X_etab = '10'; $Y_etab = '10';
		        $caractere_utilse = 'Arial';
			$affiche_logo_etab='1';
			$entente_mel='0'; // afficher l'adresse mel dans l'ent�te
			$entente_tel='0'; // afficher le num�ro de t�l�phone dans l'ent�te
			$entente_fax='0'; // afficher le num�ro de fax dans l'ent�te
			$L_max_logo=75; $H_max_logo=75; //dimension du logo

    //Affiche le filigrame

	//bloc identification etablissement
	$logo = '../../images/'.getSettingValue('logo_etab');
	$format_du_logo = str_replace('.','',strstr(getSettingValue('logo_etab'), '.')); 
	if($affiche_logo_etab==='1' and file_exists($logo) and getSettingValue('logo_etab') != '' and ($format_du_logo==='jpg' or $format_du_logo==='png'))
	{
	 $valeur=redimensionne_logo($logo, $L_max_logo, $H_max_logo);
	 //$X_logo et $Y_logo; placement du bloc identite de l'�tablissement
	 $X_logo=5; $Y_logo=5; $L_logo=$valeur[0]; $H_logo=$valeur[1];
	 $X_etab=$X_logo+$L_logo; $Y_etab=$Y_logo;
	 //logo
         $this->Image($logo, $X_logo, $Y_logo, $L_logo, $H_logo);
	}

	//adresse
 	 $this->SetXY($X_etab,$Y_etab);
 	 $this->SetFont($caractere_utilse,'',14);
	  $gepiSchoolName = getSettingValue('gepiSchoolName');
	 $this->Cell(90,7, $gepiSchoolName,0,2,''); 
	 $this->SetFont($caractere_utilse,'',10);
	  $gepiSchoolAdress1 = getSettingValue('gepiSchoolAdress1');
	 $this->Cell(90,5, $gepiSchoolAdress1,0,2,'');
	  $gepiSchoolAdress2 = getSettingValue('gepiSchoolAdress2');
	 $this->Cell(90,5, $gepiSchoolAdress2,0,2,''); 
	  $gepiSchoolZipCode = getSettingValue('gepiSchoolZipCode');
	  $gepiSchoolCity = getSettingValue('gepiSchoolCity');
	 $this->Cell(90,5, $gepiSchoolZipCode." ".$gepiSchoolCity,0,2,''); 
	  $gepiSchoolTel = getSettingValue('gepiSchoolTel');
	  $gepiSchoolFax = getSettingValue('gepiSchoolFax');
	if($entente_tel==='1' and $entente_fax==='1') { $entete_communic = 'T�l: '.$gepiSchoolTel.' / Fax: '.$gepiSchoolFax; }
	if($entente_tel==='1' and empty($entete_communic)) { $entete_communic = 'T�l: '.$gepiSchoolTel; }
	if($entente_fax==='1' and empty($entete_communic)) { $entete_communic = 'Fax: '.$gepiSchoolFax; }
	if( isset($entete_communic) and $entete_communic != '' ) {
	 $this->Cell(90,5, $entete_communic,0,2,''); 
	}
	if($entente_mel==='1') {
	  $gepiSchoolEmail = getSettingValue('gepiSchoolEmail');
	 $this->Cell(90,5, $gepiSchoolEmail,0,2,''); 
	}
    }

    //Pied de page du document
    function Footer()
    {

                 $niveau_etab = "";
                 $nom_etab = getSettingValue("gepiSchoolName");
                 $adresse1_etab = getSettingValue("gepiSchoolAdress1");
                 $adresse2_etab = getSettingValue("gepiSchoolAdress2");
                 $cp_etab = getSettingValue("gepiSchoolZipCode");
                 $ville_etab = getSettingValue("gepiSchoolCity");
                 $cedex_etab = "";
                 $telephone_etab = getSettingValue("gepiSchoolTel");
                 $fax_etab = getSettingValue("gepiSchoolFax");
                 $mel_etab = getSettingValue("gepiSchoolEmail");

        //Positionnement � 1 cm du bas et 0,5cm + 0,5cm du cot� gauche
   	$this->SetXY(5,-10);
        //Police Arial Gras 6
        $this->SetFont('Arial','B',8);
	$this->SetLineWidth(0,2);
	$this->SetDrawColor(0, 0, 0);
	$this->Line(10, 280, 200, 280);
	$this->SetFont('Arial','',10);
	$this->SetY(280);
	$adresse = $nom_etab." - ".$adresse1_etab." - ".$cp_etab." ".$ville_etab." ".$cedex_etab;
	if($adresse2_etab!="")
	{
	  $nom_etab." - ".$adresse1_etab." ".$adresse2_etab." - ".$cp_etab." ".$ville_etab." ".$cedex_etab;
	}
	if($telephone_etab!="" and $fax_etab!="" and $mel_etab!="")
	{
	  $adresse2 = "Tel : ".$telephone_etab." - Fax : ".$fax_etab." - M�l : ".$mel_etab;
	}
	if($telephone_etab=="" and $fax_etab!="" and $mel_etab!="")
	{
	  $adresse2 = "Fax : ".$fax_etab." - M�l : ".$mel_etab;
	}
	if($telephone_etab!="" and $fax_etab=="" and $mel_etab!="")
	{
	  $adresse2 = "Tel : ".$telephone_etab." - M�l : ".$mel_etab;
	}
	if($telephone_etab!="" and $fax_etab!="" and $mel_etab=="")
	{
	  $adresse2 = "Tel : ".$telephone_etab." - Fax : ".$fax_etab;
	}

	$this->Cell(0, 4.5, $adresse, 0, 1, 'C', '');
	$this->Cell(0, 4.5, $adresse2, 0, 1, 'C', '');
    } 
}


//requete dans la base de donn�e
  //etablissement
          $niveau_etab = "";
          $nom_etab = getSettingValue("gepiSchoolName");
          $adresse1_etab = getSettingValue("gepiSchoolAdress1");
          $adresse2_etab = getSettingValue("gepiSchoolAdress2");
          $cp_etab = getSettingValue("gepiSchoolZipCode");
          $ville_etab = getSettingValue("gepiSchoolCity");
          $cedex_etab = "";
          $telephone_etab = getSettingValue("gepiSchoolTel");
          $fax_etab = getSettingValue("gepiSchoolFax");
          $mel_etab = getSettingValue("gepiSchoolEmail");
  //contage des pages
      if ($classe != "tous" and $eleve == "tous")
        {
          $cpt_requete_1 =mysql_result(mysql_query("SELECT DISTINCT count(*) FROM ".$prefix_base."absences_eleves, ".$prefix_base."eleves, ".$prefix_base."j_eleves_classes WHERE ((d_date_absence_eleve >= '".date_sql($du)."' AND d_date_absence_eleve <= '".date_sql($au)."') OR (a_date_absence_eleve >= '".date_sql($du)."' AND a_date_absence_eleve <= '".date_sql($au)."')) AND eleve_absence_eleve=".$prefix_base."eleves.login AND ".$prefix_base."j_eleves_classes.login=".$prefix_base."eleves.login AND id_classe='".$classe."' GROUP BY id_absence_eleve ORDER BY nom, prenom, d_date_absence_eleve ASC"),0);
        }
      if ($classe == "tous" and $eleve == "tous")
        {
          $cpt_requete_1 =mysql_result(mysql_query("SELECT count(*) FROM ".$prefix_base."absences_eleves, ".$prefix_base."eleves WHERE ((d_date_absence_eleve >= '".date_sql($du)."' AND d_date_absence_eleve <= '".date_sql($au)."') OR (a_date_absence_eleve >= '".date_sql($du)."' AND a_date_absence_eleve <= '".date_sql($au)."')) AND eleve_absence_eleve=login ORDER BY nom, prenom, d_date_absence_eleve ASC"),0);
        }
      if (($classe != "tous" or $classe == "tous") and $eleve != "tous")
        {
          $cpt_requete_1 =mysql_result(mysql_query("SELECT count(*) FROM ".$prefix_base."absences_eleves, ".$prefix_base."eleves WHERE ((d_date_absence_eleve >= '".date_sql($du)."' AND d_date_absence_eleve <= '".date_sql($au)."') OR (a_date_absence_eleve >= '".date_sql($du)."' AND a_date_absence_eleve <= '".date_sql($au)."')) AND eleve_absence_eleve=login AND login='".$eleve."' GROUP BY id_absence_eleve ORDER BY nom, prenom, d_date_absence_eleve ASC"),0);
        }

        //je compte le nombre de page
        $nb_par_page = 35;
        $nb_page = $cpt_requete_1 / $nb_par_page;
 
        if(number_format($cpt_requete_1, 0, ',' ,'') == number_format($nb_page, 0, ',' ,'')) { $nb_page = number_format($nb_page, 0, ',' ,''); } else { $nb_page = number_format($nb_page, 0, ',' ,'') + 1; }

// mode paysage, a4, etc.
$pdf=new bilan_PDF('P','mm','A4');
$pdf->Open();
$pdf->SetAutoPageBreak(true);

// champs facultatifs
$pdf->SetAuthor('');
$pdf->SetCreator('cr�er avec Fpdf');
$pdf->SetTitle('Titre');
$pdf->SetSubject('Sujet');

$pdf->SetMargins(10,10);
$page = 0;
$nb_debut = 0;
$nb_fin = 0;
while ($page<$nb_page) {
$pdf->AddPage();

$pdf->SetFont('Arial','',12);
$pdf->SetY(20);
$pdf->SetX(65);
$pdf->SetFont('Arial','B',18);
$pdf->Cell(0, 6, 'RELEVE DES ABSENCES', 0, 1, 'C', '');
$pdf->SetFont('Arial','',14);
$duu = "du ".date_frl(date_sql($du));
$auu = "au ".date_frl(date_sql($au));
$pdf->SetX(65);
$pdf->Cell(0, 8, $duu, 0, 1, 'C', '');
if ($du != $au)
{
  $pdf->SetX(65);
  $pdf->Cell(0, 6, $auu, 0, 1, 'C', '');
}
//tableau
$pdf->SetX(30);
$pdf->SetY(60);
            $pdf->SetFont('Arial','',9.5);
            $pdf->Cell(55, 5, 'Nom et Pr�nom', 1, 0, 'C', '');
            $pdf->Cell(15, 5, 'Classe', 1, 0, 'C', '');
            $pdf->Cell(40, 5, 'Motif', 1, 0, 'C', '');
            $pdf->Cell(40, 5, 'Du', 1, 0, 'C', '');
            $pdf->Cell(40, 5, 'Au', 1, 1, 'C', '');

if ($classe != "tous" AND $eleve == "tous")
    {
      $requete_1 ="SELECT DISTINCT * FROM ".$prefix_base."absences_eleves, ".$prefix_base."eleves, ".$prefix_base."j_eleves_classes WHERE ((d_date_absence_eleve >= '".date_sql($du)."' AND d_date_absence_eleve <= '".date_sql($au)."') OR (a_date_absence_eleve >= '".date_sql($du)."' AND a_date_absence_eleve <= '".date_sql($au)."')) AND eleve_absence_eleve=".$prefix_base."eleves.login AND ".$prefix_base."j_eleves_classes.login=".$prefix_base."eleves.login AND id_classe='".$classe."' GROUP BY id_absence_eleve ORDER BY nom, prenom, d_date_absence_eleve ASC";
    }
if ($classe == "tous" AND $eleve == "tous")
    {
      $requete_1 ="SELECT * FROM ".$prefix_base."absences_eleves, ".$prefix_base."eleves WHERE ((d_date_absence_eleve >= '".date_sql($du)."' AND d_date_absence_eleve <= '".date_sql($au)."') OR (a_date_absence_eleve >= '".date_sql($du)."' AND a_date_absence_eleve <= '".date_sql($au)."')) AND eleve_absence_eleve=login GROUP BY id_absence_eleve ORDER BY nom, prenom, d_date_absence_eleve ASC LIMIT $nb_debut, $nb_par_page";
    }
if (($classe != "tous" OR $classe == "tous") AND $eleve != "tous")
    {
      $requete_1 ="SELECT * FROM ".$prefix_base."absences_eleves, ".$prefix_base."eleves WHERE ((d_date_absence_eleve >= '".date_sql($du)."' AND d_date_absence_eleve <= '".date_sql($au)."') OR (a_date_absence_eleve >= '".date_sql($du)."' AND a_date_absence_eleve <= '".date_sql($au)."')) AND eleve_absence_eleve=login AND login='".$eleve."' GROUP BY id_absence_eleve ORDER BY nom, prenom, d_date_absence_eleve ASC";
    }

$execution_1 = mysql_query($requete_1) or die('Erreur SQL !'.$requete_1.'<br />'.mysql_error());
while ( $data_1 = mysql_fetch_array($execution_1))
      {
      //tableau des absences
            $pdf->SetFont('Arial','',9);
            $pdf->SetFont('Arial','',9);
            $ident_eleve = strtoupper($data_1['nom'])." ".ucfirst($data_1['prenom']);
            $pdf->Cell(55, 5, $ident_eleve, 1, 0, '', '');
            $classe_eleve = classe_de($data_1['login']);
            $pdf->Cell(15, 5, $classe_eleve, 1, 0, '', '');
            $motif = motab_c($data_1['motif_absence_eleve'])." (".$data_1['type_absence_eleve'].")";
            $pdf->Cell(40, 5, $motif, 1, 0, '', '');
            $debut = date_fr($data_1['d_date_absence_eleve'])." � ".heure($data_1['d_heure_absence_eleve']);
            $pdf->Cell(40, 5, $debut, 1, 0, '', '');
            if($data_1['a_heure_absence_eleve'] == "" OR $data_1['a_heure_absence_eleve'] == "00:00:00" OR $data_1['a_heure_absence_eleve'] == $data_1['d_heure_absence_eleve'])
            {
            $fin = "";
            } else {
                     $fin = date_fr($data_1['a_date_absence_eleve'])." � ".heure($data_1['a_heure_absence_eleve']);
                   }

            $pdf->Cell(40, 5, $fin, 1, 1, '', '');
      }
    $pdf->Cell(0, 5, '(A): absence     (R): retard     (I): infirmerie     (D): dispense', 0, 1, '', '');

if($nb_page>1)
{
    $nb_affiche_page = $page + 1;
    $nb_affiche_sur_page = $nb_page;
    $info_page = "page : ".$nb_affiche_page."/".$nb_affiche_sur_page;
    $pdf->Cell(0, 5, $info_page, 0, 1, 'C', '');
}

//}
$nb_debut = $nb_debut + $nb_par_page;
$page = $page + 1;
}
// Et on affiche le pdf g�n�r�... (ou on le sauvegarde en local)
// $pdf->Output(); pour afficher sur votre browser
$nom_lettre=date("Ymd_Hi");
$nom_lettre='Bilan_absence_'.$nom_lettre.'.pdf';
$pdf->Output($nom_lettre,'I');


?>
