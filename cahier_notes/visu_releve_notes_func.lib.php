<?php

/*
function decompte_debug($motif,$texte) {
	global $tab_instant, $debug;
	if($debug=="y") {
		$instant=microtime();
		if(isset($tab_instant[$motif])) {
			$tmp_tab1=explode(" ",$instant);
			$tmp_tab2=explode(" ",$tab_instant[$motif]);
			if($tmp_tab1[1]!=$tmp_tab2[1]) {
				$diff=$tmp_tab1[1]-$tmp_tab2[1];
			}
			else {
				$diff=$tmp_tab1[0]-$tmp_tab2[0];
			}
			//if($debug=="y") {
				echo "<p style='color:green;'>$texte: ".$diff." s</p>\n";
			//}
		}
		else {
			//if($debug=="y") {
				echo "<p style='color:green;'>$texte</p>\n";
			//}
		}
		$tab_instant[$motif]=$instant;
	}
}

function regime($id_reg) {
	switch($id_reg) {
		case "d/p":
			$regime="demi-pensionnaire";
			break;
		case "ext.":
			$regime="externe";
			break;
		case "int.":
			$regime="interne";
			break;
		case "i-e":
			$regime="interne-extern�";
			break;
		default:
			$regime="R�gime inconnu???";
			break;
	}

	return $regime;
}
*/

function fich_debug($texte) {
	$fich=fopen("/tmp/visu_releve.txt","a+");
	fwrite($fich,$texte);
	fclose($fich);
}

function redimensionne_image_releve($photo){
	//global $bull_photo_largeur_max, $bull_photo_hauteur_max;
	global $releve_photo_largeur_max, $releve_photo_hauteur_max;

	// prendre les informations sur l'image
	$info_image=getimagesize($photo);
	// largeur et hauteur de l'image d'origine
	$largeur=$info_image[0];
	$hauteur=$info_image[1];

	// calcule le ratio de redimensionnement
	$ratio_l=$largeur/$releve_photo_largeur_max;
	$ratio_h=$hauteur/$releve_photo_hauteur_max;
	$ratio=($ratio_l>$ratio_h)?$ratio_l:$ratio_h;

	// d�finit largeur et hauteur pour la nouvelle image
	$nouvelle_largeur=round($largeur/$ratio);
	$nouvelle_hauteur=round($hauteur/$ratio);

	//fich_debug("photo=$photo\nlargeur=$largeur\nhauteur=$hauteur\nratio_l=$ratio_l\nratio_h=$ratio_h\nratio=$ratio\nnouvelle_largeur=$nouvelle_largeur\nnouvelle_hauteur=$nouvelle_hauteur\n===============\n");

	return array($nouvelle_largeur, $nouvelle_hauteur);
}

//echo "\$releve_photo_largeur_max=$releve_photo_largeur_max<br />";
//echo "\$releve_photo_hauteur_max=$releve_photo_hauteur_max<br />";

/*
function texte_html_ou_pas($texte){
	// Si le texte contient des < et >, on affiche tel quel
	if((strstr($texte,">"))||(strstr($texte,"<"))){
		$retour=$texte;
	}
	// Sinon, on transforme les retours � la ligne en <br />
	else{
		$retour=nl2br($texte);
	}
	return $retour;
}
*/

// $tab_reletin[$id_classe][$periode_num]
// $i indice �l�ve
//function releve_html($tab_rel,$i) {
function releve_html($tab_rel,$i,$num_releve_specifie) {
	global
		//============================================
		// Param�tres g�n�raux:
		// En admin, dans Gestion g�n�rale/Configuration g�n�rale
		$gepi_prof_suivi,

		$RneEtablissement,
		$gepiSchoolName,
		$gepiSchoolAdress1,
		$gepiSchoolAdress2,
		$gepiSchoolZipCode,
		$gepiSchoolCity,
		$gepiSchoolPays,
		$gepiSchoolTel,
		$gepiSchoolFax,
		$gepiYear,

		$logo_etab,
		//============================================
		$choix_periode,	// 'periode' ou 'intervalle'
		$chaine_coef,	// 'coef.:'
		//============================================

		// Param�tres d'impression des bulletins HTML:

		// Mise en page du bulletin scolaire
		$releve_body_marginleft,
		// $titlesize, $textsize, $p_bulletin_margin sont r�cup�r�s plus haut dans l'ent�te pour �crire les styles
		$releve_largeurtableau,

		$releve_col_matiere_largeur,
		//$col_note_largeur,
		//$col_boite_largeur,
		//$col_hauteur,		// La hauteur minimale de ligne n'est exploit�e que dans les boites/conteneurs
		$releve_cellpadding,
		$releve_cellspacing,
		$releve_ecart_entete,
		//$bull_espace_avis,
		// $bull_bordure_classique permet de renseigner $class_bordure
		//$class_bordure,
		$releve_class_bordure,

		$releve_categ_font_size,
		$releve_categ_bgcolor,
		//======================
		//$bull_categ_font_size_avis,
		//$bull_police_avis,
		//$bull_font_style_avis,
		// Ils sont utilis�s dans l'ent�te pour g�n�rer les styles
		//======================
		$genre_periode,
		$releve_affich_nom_etab,
		$releve_affich_adr_etab,

		// Informations devant figurer sur le bulletin scolaire
		$releve_mention_nom_court,
		$releve_mention_doublant,
		$releve_affiche_eleve_une_ligne,
		//$releve_affiche_appreciations,
		//$releve_affiche_absences,
		//$releve_affiche_avis,
		//$releve_affiche_aid,
		$releve_affiche_numero,		// affichage du num�ro du bulletin
		// L'affichage des graphes devrait provenir des Param�tres d'impression des bulletins HTML, mais le param�tre a �t� stock� dans $tab_rel
		//$releve_affiche_signature,	// affichage du nom du PP et du chef d'�tablissement
		$releve_affiche_etab,			// Etablissement d'origine


		$activer_photo_releve,
		// $releve_photo_largeur_max et $releve_photo_hauteur_max sont r�cup�r�es via global dans redimensionne_image()

		$releve_affiche_tel,
		$releve_affiche_fax,
		$releve_intitule_app,
		$releve_affiche_INE_eleve,
		$releve_affiche_formule,
		$releve_formule_bas,
		// Nom du fichier d�termin� d'apr�s le param�tre choix_bulletin
		$fichier_bulletin,
		$min_max_moyclas,

		// Bloc adresse responsable
		$releve_addressblock_padding_right,
		$releve_addressblock_padding_top,
		$releve_addressblock_padding_text,
		$releve_addressblock_length,
		$releve_addressblock_font_size,
		//addressblock_logo_etab_prop correspond au pourcentage $largeur1 et $largeur2 est le compl�ment � 100%
		$releve_addressblock_logo_etab_prop,
		$releve_addressblock_autre_prop,
		// Pourcentage calcul� par rapport au tableau contenant le bloc Classe, Ann�e,...
		$releve_addressblock_classe_annee2,
		// Nombre de sauts de ligne entre le bloc Logo+Etablissement et le bloc Nom, pr�nom,... de l'�l�ve
		$releve_ecart_bloc_nom,
		$releve_addressblock_debug,

		//============================================
		// Param�tre transmis depuis la page d'impression des bulletins
		$un_seul_bull_par_famille,

		//============================================
		// Tableaux provenant de /lib/global.inc
		$type_etablissement,
		$type_etablissement2,

		//============================================
		// Param�tre du module trombinoscope
		// En admin, dans Gestion des modules
		$active_module_trombinoscopes
;

	// R�cup�rer avant le nombre de bulletins � imprimer
	// - que le premier resp
	// - tous les resp si adr diff�rentes
	// et le passer via global
	//================================

    // Pour retourner � la ligne entre les devoirs dans le cas o� le nom ou l'appr�ciation du devoir est demand�e:
    $retour_a_la_ligne="y";
    // Passer � "n" pour d�sactiver le retour � la ligne.


/*
$affiche_categories

$avec_appreciation_devoir
$avec_nom_devoir
$avec_tous_coef_devoir

$avec_coef_devoir
$tab_releve[$id_classe]['rn_coefdev_si_diff']

$tab_ele['groupe'][$j]['differents_coef']
$affiche_coef

$avec_date_devoir
*/

	$id_classe=$tab_rel['id_classe'];

	// Pour n'imprimer qu'un relev� dans le cas o� on n'imprime pas les adresses des responsables
	$nb_releves=1;

	unset($tab_adr_ligne1);
	unset($tab_adr_ligne2);
	unset($tab_adr_ligne3);

	//if ($tab_rel['affiche_adresse'] == 'y') {
	// On fait le travail sur $tab_adr_ligne1 m�me si on ne souhaite pas afficher l'adresse des responsables parce que c'est aussi cette d�marche qui permet de d�terminer $nb_releves

		// Pr�paration des lignes adresse responsable
		if (!isset($tab_rel['eleve'][$i]['resp'][0])) {
			$tab_adr_ligne1[0]="<font color='red'><b>ADRESSE MANQUANTE</b></font>";
			$tab_adr_ligne2[0]="";
			$tab_adr_ligne3[0]="";
		}
		else {
			if (isset($tab_rel['eleve'][$i]['resp'][1])) {
				if((isset($tab_rel['eleve'][$i]['resp'][1]['adr1']))&&
					(isset($tab_rel['eleve'][$i]['resp'][1]['adr2']))&&
					(isset($tab_rel['eleve'][$i]['resp'][1]['adr3']))&&
					(isset($tab_rel['eleve'][$i]['resp'][1]['adr4']))&&
					(isset($tab_rel['eleve'][$i]['resp'][1]['cp']))&&
					(isset($tab_rel['eleve'][$i]['resp'][1]['commune']))
				) {
					// Le deuxi�me responsable existe et est renseign�
					if (($tab_rel['eleve'][$i]['resp'][0]['adr_id']==$tab_rel['eleve'][$i]['resp'][1]['adr_id']) OR
						(
							($tab_rel['eleve'][$i]['resp'][0]['adr1']==$tab_rel['eleve'][$i]['resp'][1]['adr1'])&&
							($tab_rel['eleve'][$i]['resp'][0]['adr2']==$tab_rel['eleve'][$i]['resp'][1]['adr2'])&&
							($tab_rel['eleve'][$i]['resp'][0]['adr3']==$tab_rel['eleve'][$i]['resp'][1]['adr3'])&&
							($tab_rel['eleve'][$i]['resp'][0]['adr4']==$tab_rel['eleve'][$i]['resp'][1]['adr4'])&&
							($tab_rel['eleve'][$i]['resp'][0]['cp']==$tab_rel['eleve'][$i]['resp'][1]['cp'])&&
							($tab_rel['eleve'][$i]['resp'][0]['commune']==$tab_rel['eleve'][$i]['resp'][1]['commune'])
						)
					) {
						// Les adresses sont identiques
						$nb_releves=1;

						if(($tab_rel['eleve'][$i]['resp'][0]['nom']!=$tab_rel['eleve'][$i]['resp'][1]['nom'])&&
							($tab_rel['eleve'][$i]['resp'][1]['nom']!="")) {
							// Les noms des responsables sont diff�rents
							$tab_adr_ligne1[0]=$tab_rel['eleve'][$i]['resp'][0]['civilite']." ".$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom']." et ".$tab_rel['eleve'][$i]['resp'][1]['civilite']." ".$tab_rel['eleve'][$i]['resp'][1]['nom']." ".$tab_rel['eleve'][$i]['resp'][1]['prenom'];
						}
						else{
							if(($tab_rel['eleve'][$i]['resp'][0]['civilite']!="")&&($tab_rel['eleve'][$i]['resp'][1]['civilite']!="")) {
								$tab_adr_ligne1[0]=$tab_rel['eleve'][$i]['resp'][0]['civilite']." et ".$tab_rel['eleve'][$i]['resp'][1]['civilite']." ".$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom'];
							}
							else {
								$tab_adr_ligne1[0]="M. et Mme ".$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom'];
							}
						}

						$tab_adr_ligne2[0]=$tab_rel['eleve'][$i]['resp'][0]['adr1'];
						if($tab_rel['eleve'][$i]['resp'][0]['adr2']!=""){
							$tab_adr_ligne2[0].="<br />\n".$tab_rel['eleve'][$i]['resp'][0]['adr2'];
						}
						if($tab_rel['eleve'][$i]['resp'][0]['adr3']!=""){
							$tab_adr_ligne2[0].="<br />\n".$tab_rel['eleve'][$i]['resp'][0]['adr3'];
						}
						if($tab_rel['eleve'][$i]['resp'][0]['adr4']!=""){
							$tab_adr_ligne2[0].="<br />\n".$tab_rel['eleve'][$i]['resp'][0]['adr4'];
						}
						$tab_adr_ligne3[0]=$tab_rel['eleve'][$i]['resp'][0]['cp']." ".$tab_rel['eleve'][$i]['resp'][0]['commune'];

						if(($tab_rel['eleve'][$i]['resp'][0]['pays']!="")&&(strtolower($tab_rel['eleve'][$i]['resp'][0]['pays'])!=strtolower($gepiSchoolPays))) {
							if($tab_adr_ligne3[0]!=" "){
								$tab_adr_ligne3[0].="<br />";
							}
							$tab_adr_ligne3[0].=$tab_rel['eleve'][$i]['resp'][0]['pays'];
						}
					}
					else {
						// Les adresses sont diff�rentes
						//if ($un_seul_bull_par_famille!="oui") {
						// On teste en plus si la deuxi�me adresse est valide
						if (($un_seul_bull_par_famille!="oui")&&
							($tab_rel['eleve'][$i]['resp'][1]['adr1']!="")&&
							($tab_rel['eleve'][$i]['resp'][1]['commune']!="")
						) {
							$nb_releves=2;
						}
						else {
							$nb_releves=1;
						}

						for($cpt=0;$cpt<$nb_releves;$cpt++) {
							if($tab_rel['eleve'][$i]['resp'][$cpt]['civilite']!="") {
								$tab_adr_ligne1[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['civilite']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['nom']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['prenom'];
							}
							else {
								$tab_adr_ligne1[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['nom']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['prenom'];
							}

							$tab_adr_ligne2[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['adr1'];
							if($tab_rel['eleve'][$i]['resp'][$cpt]['adr2']!=""){
								$tab_adr_ligne2[$cpt].="<br />\n".$tab_rel['eleve'][$i]['resp'][$cpt]['adr2'];
							}
							if($tab_rel['eleve'][$i]['resp'][$cpt]['adr3']!=""){
								$tab_adr_ligne2[$cpt].="<br />\n".$tab_rel['eleve'][$i]['resp'][$cpt]['adr3'];
							}
							if($tab_rel['eleve'][$i]['resp'][$cpt]['adr4']!=""){
								$tab_adr_ligne2[$cpt].="<br />\n".$tab_rel['eleve'][$i]['resp'][$cpt]['adr4'];
							}
							$tab_adr_ligne3[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['cp']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['commune'];

							if(($tab_rel['eleve'][$i]['resp'][$cpt]['pays']!="")&&(strtolower($tab_rel['eleve'][$i]['resp'][$cpt]['pays'])!=strtolower($gepiSchoolPays))) {
								if($tab_adr_ligne3[$cpt]!=" "){
									$tab_adr_ligne3[$cpt].="<br />";
								}
								$tab_adr_ligne3[$cpt].=$tab_rel['eleve'][$i]['resp'][$cpt]['pays'];
							}

						}

					}
				}
				else {
					// Il n'y a pas de deuxi�me adresse, mais il y aurait un deuxi�me responsable???
					// CA NE DEVRAIT PAS ARRIVER ETANT DONN� LA REQUETE EFFECTUEE QUI JOINT resp_pers ET resp_adr...
						if ($un_seul_bull_par_famille!="oui") {
							$nb_releves=2;
						}
						else {
							$nb_releves=1;
						}

						for($cpt=0;$cpt<$nb_releves;$cpt++) {
							if($tab_rel['eleve'][$i]['resp'][$cpt]['civilite']!="") {
								$tab_adr_ligne1[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['civilite']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['nom']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['prenom'];
							}
							else {
								$tab_adr_ligne1[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['nom']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['prenom'];
							}

							$tab_adr_ligne2[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['adr1'];
							if($tab_rel['eleve'][$i]['resp'][$cpt]['adr2']!=""){
								$tab_adr_ligne2[$cpt].="<br />\n".$tab_rel['eleve'][$i]['resp'][$cpt]['adr2'];
							}
							if($tab_rel['eleve'][$i]['resp'][$cpt]['adr3']!=""){
								$tab_adr_ligne2[$cpt].="<br />\n".$tab_rel['eleve'][$i]['resp'][$cpt]['adr3'];
							}
							if($tab_rel['eleve'][$i]['resp'][$cpt]['adr4']!=""){
								$tab_adr_ligne2[$cpt].="<br />\n".$tab_rel['eleve'][$i]['resp'][$cpt]['adr4'];
							}
							$tab_adr_ligne3[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['cp']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['commune'];

							if(($tab_rel['eleve'][$i]['resp'][$cpt]['pays']!="")&&(strtolower($tab_rel['eleve'][$i]['resp'][$cpt]['pays'])!=strtolower($gepiSchoolPays))) {
								if($tab_adr_ligne3[$cpt]!=" "){
									$tab_adr_ligne3[$cpt].="<br />";
								}
								$tab_adr_ligne3[$cpt].=$tab_rel['eleve'][$i]['resp'][$cpt]['pays'];
							}
						}
				}
			}
			else {
				// Il n'y a pas de deuxi�me responsable
				$nb_releves=1;

				if($tab_rel['eleve'][$i]['resp'][0]['civilite']!="") {
					$tab_adr_ligne1[0]=$tab_rel['eleve'][$i]['resp'][0]['civilite']." ".$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom'];
				}
				else {
					$tab_adr_ligne1[0]=$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom'];
				}

				$tab_adr_ligne2[0]=$tab_rel['eleve'][$i]['resp'][0]['adr1'];
				if($tab_rel['eleve'][$i]['resp'][0]['adr2']!=""){
					$tab_adr_ligne2[0].="<br />\n".$tab_rel['eleve'][$i]['resp'][0]['adr2'];
				}
				if($tab_rel['eleve'][$i]['resp'][0]['adr3']!=""){
					$tab_adr_ligne2[0].="<br />\n".$tab_rel['eleve'][$i]['resp'][0]['adr3'];
				}
				if($tab_rel['eleve'][$i]['resp'][0]['adr4']!=""){
					$tab_adr_ligne2[0].="<br />\n".$tab_rel['eleve'][$i]['resp'][0]['adr4'];
				}
				$tab_adr_ligne3[0]=$tab_rel['eleve'][$i]['resp'][0]['cp']." ".$tab_rel['eleve'][$i]['resp'][0]['commune'];

				if(($tab_rel['eleve'][$i]['resp'][0]['pays']!="")&&(strtolower($tab_rel['eleve'][$i]['resp'][0]['pays'])!=strtolower($gepiSchoolPays))) {
					if($tab_adr_ligne3[0]!=" "){
						$tab_adr_ligne3[0].="<br />";
					}
					$tab_adr_ligne3[0].=$tab_rel['eleve'][$i]['resp'][0]['pays'];
				}
			}
		}
	//}
	// Fin de la pr�paration des lignes adresse responsable

	//echo "\$nb_releves=$nb_releves<br />";
	//echo "\$num_releve_specifie=$num_releve_specifie<br />";

	$num_premier_releve=0;
	if($num_releve_specifie!=-1) {
		$num_premier_releve=$num_releve_specifie;
		$nb_releves=$num_releve_specifie+1;
	}

	//echo "\$num_premier_releve=$num_premier_releve<br />";
	//echo "\$nb_releves=$nb_releves<br />";

	// D�but des bulletins
	for ($num_releve=$num_premier_releve; $num_releve<$nb_releves; $num_releve++) {

		//echo "\$num_releve=$num_releve<br />";
		//echo "\$i=$i<br />";
		//echo "\$tab_rel['eleve'][$i]['nom']=".$tab_rel['eleve'][$i]['nom']."<br />";

		// Page de garde
		//if ( $affiche_page_garde == 'yes' OR $tab_rel['affiche_adresse'] == 'y') {
		if ($tab_rel['affiche_adresse'] == 'y') {

			// Affectation des lignes adresse responsable avec les lignes correspondant au bulletin en cours
			$ligne1=$tab_adr_ligne1[$num_releve];
			$ligne2=$tab_adr_ligne2[$num_releve];
			$ligne3=$tab_adr_ligne3[$num_releve];

			// Info affich�e en haut de la page de garde
			$info_eleve_page_garde="El�ve: ".$tab_rel['eleve'][$i]['nom']." ".$tab_rel['eleve'][$i]['prenom'].", ".$tab_rel['eleve'][$i]['classe'];

			//if ($affiche_page_garde == "yes") {
			//	include "./page_garde.php";
			//	// Saut de page
			//	echo "<p class='saut'>&nbsp;</p>\n";
			//}
		}


		if($tab_rel['affiche_adresse'] == 'y') {
			//-------------------------------
			// Maintenant, on affiche l'en-t�te : Les donn�es de l'�l�ve, le bloc adresse responsable et l'adresse du lyc�e.
			//-------------------------------

			echo "\n<!-- D�but du cadre ent�te -->\n";
			echo "<div";
			if($releve_addressblock_debug=="y"){echo " style='border:1px solid red;'";}
			echo ">\n";
			// Pour �viter que le bloc-adresse ne remonte au-del� du saut de page:
			echo "<div style='clear: both; font-size: xx-small;'>&nbsp;</div>\n";

			// Cadre adresse du responsable:
			echo "<div style='float:right;
width:".$releve_addressblock_length."mm;
padding-top:".$releve_addressblock_padding_top."mm;
padding-bottom:".$releve_addressblock_padding_text."mm;
padding-right:".$releve_addressblock_padding_right."mm;\n";
			if($releve_addressblock_debug=="y"){echo "border: 1px solid blue;\n";}
			echo "font-size: ".$releve_addressblock_font_size."pt;
'>
<div align='left'>
$ligne1<br />
$ligne2<br />
$ligne3
</div>
</div>\n";


			// Cadre contenant le tableau Logo+Ad_etab et le nom, pr�nom,... de l'�l�ve:
			echo "<div style='float:left;
left:0px;
top:0px;
width:".$releve_addressblock_logo_etab_prop."%;\n";
			if($releve_addressblock_debug=="y"){echo "border: 1px solid green;\n";}
			echo "'>\n";

			echo "<table";
			if($releve_addressblock_debug=="y"){echo " border='1'";}
			echo " summary='Tableau des informations �tablissement'";
			echo ">\n";
			echo "<tr>\n";

			$nom_fic_logo = $logo_etab;
			$nom_fic_logo_c = "../images/".$nom_fic_logo;

			if (($nom_fic_logo != '') and (file_exists($nom_fic_logo_c))) {
				echo "<td style=\"text-align: left;\"><img src=\"".$nom_fic_logo_c."\" border=\"0\" alt=\"Logo\" /></td>\n";
			}
			echo "<td style='text-align: center;'>";
			echo "<p class='bulletin'>";
			if($releve_affich_nom_etab=="y"){
				echo "<span class=\"releve_grand\">".$gepiSchoolName."</span>";
			}
			if($releve_affich_adr_etab=="y"){
				echo "<br />\n".$gepiSchoolAdress1."<br />\n".$gepiSchoolAdress2."<br />\n".$gepiSchoolZipCode." ".$gepiSchoolCity;
				if($releve_affiche_tel=="y"){echo "<br />\nTel: ".$gepiSchoolTel;}
				if($releve_affiche_fax=="y"){echo "<br />\nFax: ".$gepiSchoolFax;}
			}
			echo "</p>\n";

			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

			echo "<br />";


			// On rajoute des lignes vides
			$n = 0;
			while ($n < $releve_ecart_bloc_nom) {
				echo "<br />";
				$n++;
			}

			if ($activer_photo_releve=='y' and $active_module_trombinoscopes=='y') {
				$photo=nom_photo($tab_rel['eleve'][$i]['elenoet']);
				//echo "$photo";
				if("$photo"!=""){
					$photo="../photos/eleves/".$photo;
					if(file_exists($photo)){
						$dimphoto=redimensionne_image_releve($photo);
						echo '<img src="'.$photo.'" style="width: '.$dimphoto[0].'px; height: '.$dimphoto[1].'px; border: 0px; border-right: 3px solid #FFFFFF; float: left;" alt="" />'."\n";
					}
				}
			}


			//affichage des donn�es sur une seule ligne ou plusieurs
			if  ($releve_affiche_eleve_une_ligne == 'no') { // sur plusieurs lignes
				echo "<p class='bulletin'>\n";
				echo "<b><span class=\"releve_grand\">".$tab_rel['eleve'][$i]['nom']." ".$tab_rel['eleve'][$i]['prenom']."</span></b><br />";
				echo "N�";
				if (strtoupper($tab_rel['eleve'][$i]['sexe'])== "F") {echo "e";}
				echo "&nbsp;le&nbsp;".$tab_rel['eleve'][$i]['naissance'];
				//Eric Ajout
				echo "<br />";
				if ($tab_rel['eleve'][$i]['regime'] == "d/p") {echo "Demi-pensionnaire";}
				if ($tab_rel['eleve'][$i]['regime'] == "ext.") {echo "Externe";}
				if ($tab_rel['eleve'][$i]['regime'] == "int.") {echo "Interne";}
				if ($tab_rel['eleve'][$i]['regime'] == "i-e"){
					echo "Interne&nbsp;extern�";
					if (strtoupper($tab_rel['eleve'][$i]['sexe'])!= "F") {echo "e";}
				}
				//Eric Ajout
				if ($releve_mention_doublant == 'yes'){
					if ($tab_rel['eleve'][$i]['doublant'] == 'R'){
					echo "<br />";
					echo "Redoublant";
					if (strtoupper($tab_rel['eleve'][$i]['sexe'])!= "F") {echo "e";}
					}
				}

				if ($releve_mention_nom_court == 'no') {
					//Eric Ajout et supp
					//echo "<BR />";
					//echo ", $current_classe";
				} else {
					echo "<br />";
					echo $tab_rel['eleve'][$i]['classe'];
				}
			}
			else { //sur une ligne
				echo "<p class='bulletin'>\n";
				echo "<b><span class=\"releve_grand\">".$tab_rel['eleve'][$i]['nom']." ".$tab_rel['eleve'][$i]['prenom']."</span></b><br />";
				echo "N�";
				if (strtoupper($tab_rel['eleve'][$i]['sexe'])== "F") {echo "e";}
				echo "&nbsp;le&nbsp;".$tab_rel['eleve'][$i]['naissance'];
				if ($tab_rel['eleve'][$i]['regime'] == "d/p") {echo ", Demi-pensionnaire";}
				if ($tab_rel['eleve'][$i]['regime'] == "ext.") {echo ", Externe";}
				if ($tab_rel['eleve'][$i]['regime'] == "int.") {echo ", Interne";}
				if ($tab_rel['eleve'][$i]['regime'] == "i-e"){
					echo ", Interne&nbsp;extern�";
					if (strtoupper($tab_rel['eleve'][$i]['sexe'])!= "F") {echo "e";}
				}
				if ($releve_mention_doublant == 'yes'){
					if ($tab_rel['eleve'][$i]['doublant'] == 'R'){
						echo ", Redoublant";
						if (strtoupper($tab_rel['eleve'][$i]['sexe'])!= "F") {echo "e";}
					}
				}
				if ($releve_mention_nom_court == 'yes') {
					echo ", ".$tab_rel['eleve'][$i]['classe'];
				}
			}

			if($releve_affiche_INE_eleve=="y"){
				echo "<br />\n";
				echo "Num�ro INE: ".$tab_rel['eleve'][$i]['no_gep'];
			}

			if($releve_affiche_etab=="y"){
				if ((isset($tab_rel['eleve'][$i]['etab_nom']))&&($tab_rel['eleve'][$i]['etab_nom']!='')) {
					echo "<br />\n";
					if ($tab_rel['eleve'][$i]['etab_id'] != '990') {
						if ($RneEtablissement != $tab_rel['eleve'][$i]['etab_id']) {
							echo "Etablissement d'origine : ";
							echo $tab_rel['eleve'][$i]['etab_niveau_nom']." ".$tab_rel['eleve'][$i]['etab_type']." ".$tab_rel['eleve'][$i]['etab_nom']." (".$tab_rel['eleve'][$i]['etab_cp']." ".$tab_rel['eleve'][$i]['etab_ville'].")\n";
						}
					} else {
						echo "Etablissement d'origine : ";
						echo "hors de France\n";
					}
				}
			}

			echo "</p>\n";

			echo "</div>\n";

			//echo "<spacer type='vertical' size='10'>";


			// Tableau contenant le nom de la classe, l'ann�e et la p�riode.
			echo "<table width='".$releve_addressblock_autre_prop."%' ";
			if($releve_addressblock_debug=="y"){echo "border='1' ";}
			echo "summary=\"Tableau de l'ent�te\" ";
			echo "cellspacing='".$releve_cellspacing."' cellpadding='".$releve_cellpadding."'>\n";
			echo "<tr>\n";
			echo "<td class='releve_empty'>\n";
			echo "&nbsp;\n";
			echo "</td>\n";
			echo "<td style='width:".$releve_addressblock_classe_annee2."%;'>\n";
			echo "<p class='bulletin' align='center'><span class=\"releve_grand\">Classe de ".$tab_rel['eleve'][$i]['classe_nom_complet']."<br />Ann�e scolaire ".$gepiYear."</span><br />\n";

			if ($choix_periode=='intervalle') {
				echo "Relev� de notes du <b>".$tab_rel['intervalle']['debut']."</b> au <b>".$tab_rel['intervalle']['fin']."</b></span>";
			} else {
				echo "<b>".$tab_rel['nom_periode']."</b> : Relev� de notes";
			}

			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

			// Pour que le tableau des appr�ciations ne vienne pas s'encastrer dans les DIV float:
			echo "<div style='clear: both; font-size: xx-small;'>&nbsp;</div>\n";

			// Fin du cadre ent�te:
			echo "</div>\n";
			echo "<!-- Fin du cadre ent�te -->\n\n";

		}
		else{
			//-------------------------------
			// Maintenant, on affiche l'en-t�te : Les donn�es de l'�l�ve, et l'adresse du lyc�e.
			// sans bloc adresse responsable
			//-------------------------------

            echo "<div align='center'>\n";
			echo "<table width='$releve_largeurtableau' border='0' cellspacing='".$releve_cellspacing."' cellpadding='".$releve_cellpadding."'";
			echo " summary=\"Tableau de l'ent�te\"";
			echo ">\n";

			echo "<tr>\n";
			echo "<td style=\"width: 30%;\">\n";
			if ($activer_photo_releve=='y' and $active_module_trombinoscopes=='y') {
				$photo=nom_photo($tab_rel['eleve'][$i]['elenoet']);
				//echo "$photo";
				if("$photo"!=""){
					$photo="../photos/eleves/".$photo;
					if(file_exists($photo)){
						$dimphoto=redimensionne_image_releve($photo);

						echo '<img src="'.$photo.'" style="width: '.$dimphoto[0].'px; height: '.$dimphoto[1].'px; border: 0px; border-right: 3px solid #FFFFFF; float: left;" alt="" />'."\n";
					}
				}
			}

				//affichage des donn�es sur une seule ligne ou plusieurs
			if  ($releve_affiche_eleve_une_ligne == 'no') { // sur plusieurs lignes
				echo "<p class='bulletin'>\n";
				echo "<b><span class=\"releve_grand\">".$tab_rel['eleve'][$i]['nom']." ".$tab_rel['eleve'][$i]['prenom']."</span></b><br />";
				echo "N�";
				if (strtoupper($tab_rel['eleve'][$i]['sexe'])== "F") {echo "e";}
				echo "&nbsp;le&nbsp;".$tab_rel['eleve'][$i]['naissance'];
				//Eric Ajout
				echo "<br />";
				if ($tab_rel['eleve'][$i]['regime'] == "d/p") {echo "Demi-pensionnaire";}
				if ($tab_rel['eleve'][$i]['regime'] == "ext.") {echo "Externe";}
				if ($tab_rel['eleve'][$i]['regime'] == "int.") {echo "Interne";}
				if ($tab_rel['eleve'][$i]['regime'] == "i-e"){
					echo "Interne&nbsp;extern�";
					if (strtoupper($tab_rel['eleve'][$i]['sexe'])!= "F") {echo "e";}
				}
				//Eric Ajout
				if ($releve_mention_doublant == 'yes'){
					if ($tab_rel['eleve'][$i]['doublant'] == 'R'){
					echo "<br />";
					echo "Redoublant";
					if (strtoupper($tab_rel['eleve'][$i]['sexe'])!= "F") {echo "e";}
					}
				}


				if ($releve_mention_nom_court == 'no') {
					//Eric Ajout et supp
					//echo "<BR />";
					//echo ", $current_classe";
				} else {
					echo "<br />";
					echo $tab_rel['eleve'][$i]['classe'];
				}

			} else { //sur une ligne
				echo "<p class='bulletin'>\n";
				echo "<b><span class=\"releve_grand\">".$tab_rel['eleve'][$i]['nom']." ".$tab_rel['eleve'][$i]['prenom']."</span></b><br />";
				echo "N�";
				if (strtoupper($tab_rel['eleve'][$i]['sexe'])== "F") {echo "e";}
				echo "&nbsp;le&nbsp;".$tab_rel['eleve'][$i]['naissance'];

				if ($tab_rel['eleve'][$i]['regime'] == "d/p") {echo ", Demi-pensionnaire";}
				if ($tab_rel['eleve'][$i]['regime'] == "ext.") {echo ", Externe";}
				if ($tab_rel['eleve'][$i]['regime'] == "int.") {echo ", Interne";}
				if ($tab_rel['eleve'][$i]['regime'] == "i-e"){
					echo ", Interne&nbsp;extern�";
					if (strtoupper($tab_rel['eleve'][$i]['sexe'])!= "F") {echo "e";}
				}
				//Eric Ajout
				if ($releve_mention_doublant == 'yes'){
					if ($tab_rel['eleve'][$i]['doublant'] == 'R'){
					echo ", Redoublant";
					if (strtoupper($tab_rel['eleve'][$i]['sexe'])!= "F") {echo "e";}
					}
				}
				if ($releve_mention_nom_court == 'yes') {
					echo ", ".$tab_rel['eleve'][$i]['classe'];
				}
			}

			if($releve_affiche_INE_eleve=="y"){
				echo "<br />\n";
				echo "Num�ro INE: ".$tab_rel['eleve'][$i]['no_gep'];
			}

			if($releve_affiche_etab=="y"){
				if ((isset($tab_rel['eleve'][$i]['etab_nom']))&&($tab_rel['eleve'][$i]['etab_nom']!='')) {
					echo "<br />\n";
					if ($tab_rel['eleve'][$i]['etab_id'] != '990') {
						if ($RneEtablissement != $tab_rel['eleve'][$i]['etab_id']) {
							echo "Etablissement d'origine : ";
							echo $tab_rel['eleve'][$i]['etab_niveau_nom']." ".$tab_rel['eleve'][$i]['etab_type']." ".$tab_rel['eleve'][$i]['etab_nom']." (".$tab_rel['eleve'][$i]['etab_cp']." ".$tab_rel['eleve'][$i]['etab_ville'].")\n";
						}
					} else {
						echo "Etablissement d'origine : ";
						echo "hors de France\n";
					}
				}
			}

			echo "</p></td>\n<td style=\"width: 40%;text-align: center;\">\n";

			if ($tab_rel['affiche_adresse'] != "y") {
				echo "<p class='bulletin'><span class=\"releve_grand\">Classe de ".$tab_rel['eleve'][$i]['classe_nom_complet']."<br />Ann�e scolaire ".$gepiYear."</span><br />\n";

				if ($choix_periode=='intervalle') {
					echo "Relev� de notes du <b>".$tab_rel['intervalle']['debut']."</b> au <b>".$tab_rel['intervalle']['fin']."</b>";
				} else {
					echo "<b>".$tab_rel['nom_periode']."</b> : Relev� de notes";
				}
				echo "</p>\n";
			} else {
				echo "&nbsp;";
			}

			$nom_fic_logo = $logo_etab;
			$nom_fic_logo_c = "../images/".$nom_fic_logo;
			if (($nom_fic_logo != '') and (file_exists($nom_fic_logo_c))) {
				echo "</td>\n<td style=\"text-align: right;\"><img src=\"".$nom_fic_logo_c."\" border=\"0\" alt=\"Logo\" />";
			} else {
				echo "</td>\n<td>&nbsp;";
			}
			echo "</td>\n";
			echo "<td style=\"width: 20%;text-align: center;\">";
			echo "<p class='bulletin'>";
			if($releve_affich_nom_etab=="y"){
				echo "<span class=\"releve_grand\">".$gepiSchoolName."</span>";
			}
			if($releve_affich_adr_etab=="y"){
				//echo "<span class=\"releve_grand\">".$gepiSchoolName."</span>";
				if($releve_affich_nom_etab=="y"){echo "<br />\n";}
				echo $gepiSchoolAdress1."<br />\n";
				echo $gepiSchoolAdress2."<br />\n";
				echo $gepiSchoolZipCode." ".$gepiSchoolCity;

				if($releve_affiche_tel=="y"){echo "<br />\nTel: ".$gepiSchoolTel;}
				if($releve_affiche_fax=="y"){echo "<br />\nFax: ".$gepiSchoolFax;}
			}
			echo "</p>\n";

			echo "</td>\n</tr>\n</table>\n";
            echo "</div>\n";
			//-------------------------------
			// Fin de l'en-t�te
		}


		// On rajoute des lignes vides
		$n = 0;
		while ($n < $releve_ecart_entete) {
			echo "<br />";
			$n++;
		}



        //=============================================

		// Tableau des matieres/devoirs/notes/appr�ciations

		//include ($fichier_bulletin);

		// On initialise le tableau :

		$larg_tab = $releve_largeurtableau;
		$larg_col1 = $releve_col_matiere_largeur;
		$larg_col2 = $larg_tab - $larg_col1;
		//echo "<table width=\"$larg_tab\" class='boireaus' border=1 cellspacing=3 cellpadding=3>\n";
        echo "<div align='center'>\n";
		echo "<table width=\"$larg_tab\"$releve_class_bordure border='1' cellspacing='3' cellpadding='3'";
		echo "summary=\"Tableau des notes\" ";
		echo ">\n";
		echo "<tr>\n";
		echo "<td width=\"$larg_col1\" class='releve'><b>Mati�re</b><br /><i>Professeur</i></td>\n";
		echo "<td width=\"$larg_col2\" class='releve'>Notes sur 20</td>\n";
		echo "</tr>\n";

		// Boucle groupes
		$j = 0;
		$prev_cat_id = null;
		while ($j < count($tab_rel['eleve'][$i]['groupe'])) {

			if ($tab_rel['affiche_categories']) {
				// On regarde si on change de cat�gorie de mati�re
				if ($tab_rel['eleve'][$i]['groupe'][$j]['id_cat'] != $prev_cat_id) {
					$prev_cat_id = $tab_rel['eleve'][$i]['groupe'][$j]['id_cat'];

					echo "<tr>\n";
					echo "<td colspan='2'>\n\n";
					//echo "<p style='padding: 0; margin:0; font-size: 10px;'>".$tab_rel['categorie'][$prev_cat_id]."</p>\n";
					echo "<p style='padding: 0; margin:0; font-size: ".$releve_categ_font_size."px;";
					if($releve_categ_bgcolor!="") {echo "background-color:$releve_categ_bgcolor;";}
					echo "'>".$tab_rel['categorie'][$prev_cat_id]."</p>\n";


					echo "</td>\n";
					echo "</tr>\n";
				}
			}

			echo "<tr>\n";
			echo "<td class='releve'>\n";
			echo "<b>".htmlentities($tab_rel['eleve'][$i]['groupe'][$j]['matiere_nom_complet'])."</b>";
			$k = 0;
			While ($k < count($tab_rel['eleve'][$i]['groupe'][$j]['prof_login'])) {
				echo "<br /><i>".affiche_utilisateur(htmlentities($tab_rel['eleve'][$i]['groupe'][$j]['prof_login'][$k]),$id_classe)."</i>";
				$k++;
			}
			echo "</td>\n";

			echo "<td class='releve'>\n";

			// Boucle sur la liste des devoirs
			if(!isset($tab_rel['eleve'][$i]['groupe'][$j]['devoir'])) {
				echo "&nbsp;";
			}
			else {
				$m=0;
				$tiret = "no";
				while($m<count($tab_rel['eleve'][$i]['groupe'][$j]['devoir'])) {
					// Note de l'�l�ve sur le devoir:
					$eleve_note=$tab_rel['eleve'][$i]['groupe'][$j]['devoir'][$m]['note'];
					// Statut de l'�l�ve sur le devoir:
					$eleve_statut=$tab_rel['eleve'][$i]['groupe'][$j]['devoir'][$m]['statut'];
					// Appr�ciation de l'�l�ve sur le devoir:
					$eleve_app=$tab_rel['eleve'][$i]['groupe'][$j]['devoir'][$m]['app'];
					// Le professeur a-t-il autoris� l'acc�s � l'appr�ciation lors de la saisie du devoir
					$eleve_display_app=$tab_rel['eleve'][$i]['groupe'][$j]['devoir'][$m]['display_app'];
					// Nom court du devoir:
					$eleve_nom_court=$tab_rel['eleve'][$i]['groupe'][$j]['devoir'][$m]['nom_court'];
					// Date du devoir:
					$eleve_date=$tab_rel['eleve'][$i]['groupe'][$j]['devoir'][$m]['date'];
					// Coef du devoir:
					$eleve_coef=$tab_rel['eleve'][$i]['groupe'][$j]['devoir'][$m]['coef'];

					//==========================================
					// On teste s'il y aura une "Note" � afficher
					if (($eleve_statut != '') and ($eleve_statut != 'v')) {
						$affiche_note = $eleve_statut;
					}
					elseif ($eleve_statut == 'v') {
						$affiche_note = "";
					}
					elseif ($eleve_note != '') {
						$affiche_note = $eleve_note;
					}
					else {
						$affiche_note = "";
					}
					//==========================================

					// Nom du devoir ou pas
					if(($tab_rel['rn_app']=="y") and ($eleve_display_app=="1")) {
						if ($affiche_note=="") {
							if ($tab_rel['rn_nomdev']!="y") {
								$affiche_note = $eleve_nom_court;
							}
							else {
								$affiche_note = "&nbsp;";
							}
						}
					}

					// Si une "Note" doit �tre affich�e
					if ($affiche_note != '') {
						if ($tiret == "yes") {
							if ((($tab_rel['rn_app']=="y") or ($tab_rel['rn_nomdev']=="y"))&&($retour_a_la_ligne=='y')) {
								echo "<br />";
							}
							else {
								echo " - ";
							}
						}
						if($tab_rel['rn_nomdev']=="y"){
							echo "$eleve_nom_court: <b>".$affiche_note."</b>";
						}
						else{
							echo "<b>".$affiche_note."</b>";
						}

						// Coefficient (si on affiche tous les coef...
						// ou si on ne les affiche que s'il y a plusieurs coef diff�rents)
						if(($tab_rel['rn_toutcoefdev']=="y")||
							(($tab_rel['rn_coefdev_si_diff']=="y")&&($tab_rel['eleve'][$i]['groupe'][$j]['differents_coef']=="y"))) {
							echo " (<i><small>".$chaine_coef.$eleve_coef."</small></i>)";
						}

						// Si on a demand� � afficher les appr�ciations
						// et si le prof a coch� l'autorisation d'acc�s � l'appr�ciations
						if(($tab_rel['rn_app']=="y") and ($eleve_display_app=="1")) {
							echo " - Appr�ciation : ";
							if ($eleve_app!="") {
								echo $eleve_app;
							}
							else {
								echo "-";
							}
						}

						if($tab_rel['rn_datedev']=="y"){
							// Format: 2006-09-28 00:00:00
							$tmpdate=explode(" ",$eleve_date);
							$tmpdate=explode("-",$tmpdate[0]);
							echo " (<i><small>$tmpdate[2]/$tmpdate[1]/$tmpdate[0]</small></i>)";
						}
						//====================================================================
						// Apr�s un tour avec affichage dans la boucle:
						$tiret = "yes";
					}

					$m++;
				}
			}
			echo "</td>\n";
			echo "</tr>\n";
			$j++;
		}

		echo "</table>\n";
        //=============================================

		/*
		// Avis du conseil de classe � ramener par l�

		if (($releve_affiche_avis == 'y')||($releve_affiche_signature == 'y')) {
			// Tableau de l'avis des conseil de classe
			echo "<table $class_bordure width='$largeurtableau' border='1' cellspacing='".$releve_cellspacing."' cellpadding='".$releve_cellpadding."'>\n";
			echo "<tr>\n";
		}

        if ($releve_affiche_avis == 'y') {
			//
			// Case de gauche : avis des conseils de classe
			//
			echo "<td style='vertical-align: top; text-align: left;'>\n";
			// 1) l'avis
			echo "<span class='bulletin'><i>Avis du conseil de classe:</i></span><br />\n";

			if($tab_rel['avis'][$i]!="") {
				echo "<span class='avis_bulletin'>";
				echo texte_html_ou_pas($tab_rel['avis'][$i]);
				echo "</span>";
				if($releve_affiche_signature == 'y'){
					echo "<br />\n";
				}
			}
			else {
				echo "&nbsp;";
				// Si il n'y a pas d'avis, on rajoute des lignes vides selon les param�tres d'impression
				$n = 0;
				if ($releve_espace_avis >0){
					while ($n < $releve_espace_avis) {
						echo "<br />\n";
						$n++;
					}
				}
			}
		}
        elseif ($releve_affiche_signature == 'y') {
            echo "<td style=\"vertical-align: top;\">";
        }

        if ($releve_affiche_signature == 'y') {
            // 2) Le nom du professeur principal
			if(isset($tab_rel['eleve'][$i]['pp']['login'])) {
				echo "<b>".ucfirst($gepi_prof_suivi)."</b> ";
				echo "<i>".affiche_utilisateur($tab_rel['eleve'][$i]['pp']['login'],$tab_rel['eleve'][$i]['id_classe'])."</i>";
			}

			echo "</td>\n";
			//
			// Case de droite : paraphe du proviseur
			//
			echo "<td style='vertical-align: top; text-align: left;' width='30%'>\n";
			echo "<span class='bulletin'><b>".$tab_rel['formule']."</b>:</span><br />";
			echo "<span class='bulletin'><i>".$tab_rel['suivi_par']."</i></span>";
		}

        // Si une des deux variables 'releve_affiche_avis' ou 'releve_affiche_signature' est � 'y', il faut fermer le tableau
        if (($releve_affiche_avis == 'y')||($releve_affiche_signature == 'y')) {
            echo "</td>\n";
            // Fin du tableau
            echo "</tr>\n";
			echo "</table>\n";
        }
		*/
		//================================


		//================================
		if(($tab_rel['rn_sign_chefetab']=='y')||($tab_rel['rn_sign_pp']=='y')||($tab_rel['rn_sign_resp']=='y')){
			$nb_cases=0;
			if($tab_rel['rn_sign_chefetab']=='y'){
				$nb_cases++;
			}
			if($tab_rel['rn_sign_pp']=='y'){
				$nb_cases++;
			}
			if($tab_rel['rn_sign_resp']=='y'){
				$nb_cases++;
			}
			$largeur_case=round($releve_largeurtableau/$nb_cases);

			echo "<table$releve_class_bordure border='1' width='$releve_largeurtableau'";
			echo " summary=\"Tableau des signatures\"";
			echo ">\n";
			echo "<tr>\n";

			if($tab_rel['rn_sign_chefetab']=='y'){
				echo "<td width='$largeur_case'>\n";
				echo "<b>Signature du chef d'�tablissement:</b>";
				for($m=0;$m<$tab_rel['rn_sign_nblig'];$m++) {
					echo "<br />\n";
				}
				echo "</td>\n";
			}

			if($tab_rel['rn_sign_pp']=='y'){
				echo "<td width='$largeur_case'>\n";
				echo "<b>Signature du ".$gepi_prof_suivi.":</b>";
				for($m=0;$m<$tab_rel['rn_sign_nblig'];$m++) {
					echo "<br />\n";
				}
				echo "</td>\n";
			}

			if($tab_rel['rn_sign_resp']=='y'){
				echo "<td width='$largeur_case'>\n";
				echo "<b>Signature des responsables:</b>";
				for($m=0;$m<$tab_rel['rn_sign_nblig'];$m++) {
					echo "<br />\n";
				}
				echo "</td>\n";
			}

			echo "</tr>\n";
			echo "</table>\n";
		}

		if($tab_rel['rn_formule']!=""){
			//echo "<p>".htmlentities($tab_rel['rn_formule'])."</p>\n";
			//echo "<p>".$tab_rel['rn_formule']."</p>\n";

			echo "<table width='$releve_largeurtableau' style='margin-left:5px; margin-right:5px;' border='0' cellspacing='".$releve_cellspacing."' cellpadding='".$releve_cellpadding."' summary='Formule du bas de relev� de notes'>\n";
			echo "<tr>";
			echo "<td><p align='center' class='bulletin'>".$tab_rel['rn_formule']."</p></td>\n";
			echo "</tr></table>";

		}
		//================================
        echo "</div>\n";


		//================================
		/*
		// Affichage de la formule de bas de page
		//echo "\$releve_formule_bas=$releve_formule_bas<br />";
		//echo "\$releve_affiche_formule=$releve_affiche_formule<br />";
		if (($releve_formule_bas != '') and ($releve_affiche_formule == 'y')) {
			// Pas d'affichage dans le cas d'un bulletin d'une p�riode "examen blanc"
			echo "<table width='$releve_largeurtableau' style='margin-left:5px; margin-right:5px;' border='0' cellspacing='".$releve_cellspacing."' cellpadding='".$releve_cellpadding."'>\n";
			echo "<tr>";
			echo "<td><p align='center' class='bulletin'>".$releve_formule_bas."</p></td>\n";
			echo "</tr></table>";
		}
		*/
		//================================


		if(($num_releve==0)&&($nb_releves==2)){
			echo "<p class='saut'>&nbsp;</p>\n";
		}
	}
}



function releve_pdf($tab_rel,$i) {
	global $annee_scolaire,
		$gepi_prof_suivi,

		$RneEtablissement,
		$gepiSchoolName,
		$gepiSchoolAdress1,
		$gepiSchoolAdress2,
		$gepiSchoolZipCode,
		$gepiSchoolCity,
		$gepiSchoolPays,
		$gepiSchoolTel,
		$gepiSchoolFax,
		$gepiYear,

		$logo_etab,

		$un_seul_bull_par_famille,

		$X_cadre_eleve,
		$cadre_titre,

		$X_entete_etab,
		$caractere_utilse,
		$affiche_logo_etab,
		$entente_mel,
		$entente_tel,
		$entente_fax,
		$L_max_logo,
		$H_max_logo,

		$active_bloc_adresse_parent,
		$X_parent,
		$Y_parent,

		$annee_scolaire,
		$X_cadre_eleve,

		$titre_du_cadre,
		$largeur_cadre_matiere,
		$texte_observation,
		$cadre_titre,
		$largeur_cadre_note_global,
		$hauteur_dun_regroupement,

		$hauteur_du_titre,
		$largeur_cadre_note,
		$X_cadre_note,
		$hauteur_cachet,

		// Param�tres du mod�le PDF
		$tab_modele_pdf,

		// Objet PDF initi� hors de la pr�sente fonction donnant la page du bulletin pour un �l�ve
		$pdf;


	$id_classe=$tab_rel['id_classe'];
	$classe_id=$id_classe;

	// Pr�paration des lignes d'adresse

	// Initialisation:
	for($loop=0;$loop<=1;$loop++) {
		$tab_adr_ligne1[$loop]="";
		$tab_adr_ligne2[$loop]="";
		$tab_adr_ligne3[$loop]="";
		$tab_adr_ligne4[$loop]="";
		$tab_adr_ligne5[$loop]="";
		$tab_adr_ligne6[$loop]="";
	}

	// ON N'UTILISE PAS LE CHAMP adr4 DE L'ADRESSE DANS resp_adr
	// IL FAUDRA VOIR COMMENT LE RECUPERER

	if (!isset($tab_rel['eleve'][$i]['resp'][0])) {
		//$tab_adr_ligne1[0]="<font color='red'><b>ADRESSE MANQUANTE</b></font>";
		$tab_adr_ligne1[0]="ADRESSE MANQUANTE";
		$tab_adr_ligne2[0]="";
		$tab_adr_ligne3[0]="";
		$tab_adr_ligne4[0]="";
		$tab_adr_ligne5[0]="";
	}
	else {
		if (isset($tab_rel['eleve'][$i]['resp'][1])) {
			if((isset($tab_rel['eleve'][$i]['resp'][1]['adr1']))&&
				(isset($tab_rel['eleve'][$i]['resp'][1]['adr2']))&&
				(isset($tab_rel['eleve'][$i]['resp'][1]['adr3']))&&
				(isset($tab_rel['eleve'][$i]['resp'][1]['adr4']))&&
				(isset($tab_rel['eleve'][$i]['resp'][1]['cp']))&&
				(isset($tab_rel['eleve'][$i]['resp'][1]['commune']))
			) {
				// Le deuxi�me responsable existe et est renseign�
				if (($tab_rel['eleve'][$i]['resp'][0]['adr_id']==$tab_rel['eleve'][$i]['resp'][1]['adr_id']) OR
					(
						($tab_rel['eleve'][$i]['resp'][0]['adr1']==$tab_rel['eleve'][$i]['resp'][1]['adr1'])&&
						($tab_rel['eleve'][$i]['resp'][0]['adr2']==$tab_rel['eleve'][$i]['resp'][1]['adr2'])&&
						($tab_rel['eleve'][$i]['resp'][0]['adr3']==$tab_rel['eleve'][$i]['resp'][1]['adr3'])&&
						($tab_rel['eleve'][$i]['resp'][0]['adr4']==$tab_rel['eleve'][$i]['resp'][1]['adr4'])&&
						($tab_rel['eleve'][$i]['resp'][0]['cp']==$tab_rel['eleve'][$i]['resp'][1]['cp'])&&
						($tab_rel['eleve'][$i]['resp'][0]['commune']==$tab_rel['eleve'][$i]['resp'][1]['commune'])
					)
				) {
					// Les adresses sont identiques
					$nb_bulletins=1;

					if(($tab_rel['eleve'][$i]['resp'][0]['nom']!=$tab_rel['eleve'][$i]['resp'][1]['nom'])&&
						($tab_rel['eleve'][$i]['resp'][1]['nom']!="")) {
						// Les noms des responsables sont diff�rents
						$tab_adr_ligne1[0]=$tab_rel['eleve'][$i]['resp'][0]['civilite']." ".$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom']." et ".$tab_rel['eleve'][$i]['resp'][1]['civilite']." ".$tab_rel['eleve'][$i]['resp'][1]['nom']." ".$tab_rel['eleve'][$i]['resp'][1]['prenom'];

						/*
						$tab_adr_ligne1[0]=$tab_rel['eleve'][$i]['resp'][0]['civilite']." ".$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom'];
						//$tab_adr_ligne1[0].=" et ";
						$tab_adr_ligne1[0].="<br />\n";
						$tab_adr_ligne1[0].="et ";
						$tab_adr_ligne1[0].=$tab_rel['eleve'][$i]['resp'][1]['civilite']." ".$tab_rel['eleve'][$i]['resp'][1]['nom']." ".$tab_rel['eleve'][$i]['resp'][1]['prenom'];
						*/
					}
					else{
						if(($tab_rel['eleve'][$i]['resp'][0]['civilite']!="")&&($tab_rel['eleve'][$i]['resp'][1]['civilite']!="")) {
							$tab_adr_ligne1[0]=$tab_rel['eleve'][$i]['resp'][0]['civilite']." et ".$tab_rel['eleve'][$i]['resp'][1]['civilite']." ".$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom'];
						}
						else {
							$tab_adr_ligne1[0]="M. et Mme ".$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom'];
						}
					}

					$tab_adr_ligne2[0]=$tab_rel['eleve'][$i]['resp'][0]['adr1'];
					if($tab_rel['eleve'][$i]['resp'][0]['adr2']!=""){
						$tab_adr_ligne3[0]=$tab_rel['eleve'][$i]['resp'][0]['adr2'];
					}
					if($tab_rel['eleve'][$i]['resp'][0]['adr3']!=""){
						$tab_adr_ligne4[0]=$tab_rel['eleve'][$i]['resp'][0]['adr3'];
					}
					//if($tab_rel['eleve'][$i]['resp'][0]['adr4']!=""){
					//	$tab_adr_ligne2[0]=$tab_rel['eleve'][$i]['resp'][0]['adr4'];
					//}
					$tab_adr_ligne5[0]=$tab_rel['eleve'][$i]['resp'][0]['cp']." ".$tab_rel['eleve'][$i]['resp'][0]['commune'];


					if(($tab_rel['eleve'][$i]['resp'][0]['pays']!="")&&(strtolower($tab_rel['eleve'][$i]['resp'][0]['pays'])!=strtolower($gepiSchoolPays))) {
						$tab_adr_ligne6[0]=$tab_rel['eleve'][$i]['resp'][0]['pays'];
					}

				}
				else {
					// Les adresses sont diff�rentes
					//if ($un_seul_bull_par_famille!="oui") {
					// On teste en plus si la deuxi�me adresse est valide
					if (($un_seul_bull_par_famille!="oui")&&
						($tab_rel['eleve'][$i]['resp'][1]['adr1']!="")&&
						($tab_rel['eleve'][$i]['resp'][1]['commune']!="")
					) {
						$nb_bulletins=2;
					}
					else {
						$nb_bulletins=1;
					}

					for($cpt=0;$cpt<$nb_bulletins;$cpt++) {
						if($tab_rel['eleve'][$i]['resp'][$cpt]['civilite']!="") {
							$tab_adr_ligne1[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['civilite']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['nom']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['prenom'];
						}
						else {
							$tab_adr_ligne1[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['nom']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['prenom'];
						}

						$tab_adr_ligne2[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['adr1'];
						if($tab_rel['eleve'][$i]['resp'][$cpt]['adr2']!=""){
							$tab_adr_ligne3[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['adr2'];
						}
						if($tab_rel['eleve'][$i]['resp'][$cpt]['adr3']!=""){
							$tab_adr_ligne4[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['adr3'];
						}
						/*
						if($tab_rel['eleve'][$i]['resp'][$cpt]['adr4']!=""){
							$tab_adr_ligne2[$cpt].="<br />\n".$tab_rel['eleve'][$i]['resp'][$cpt]['adr4'];
						}
						*/
						$tab_adr_ligne5[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['cp']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['commune'];

						if(($tab_rel['eleve'][$i]['resp'][$cpt]['pays']!="")&&(strtolower($tab_rel['eleve'][$i]['resp'][$cpt]['pays'])!=strtolower($gepiSchoolPays))) {
							$tab_adr_ligne6[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['pays'];
						}
					}

				}
			}
			else {
				// Il n'y a pas de deuxi�me adresse, mais il y aurait un deuxi�me responsable???
				// CA NE DEVRAIT PAS ARRIVER ETANT DONN� LA REQUETE EFFECTUEE QUI JOINT resp_pers ET resp_adr...
				if ($un_seul_bull_par_famille!="oui") {
					$nb_bulletins=2;
				}
				else {
					$nb_bulletins=1;
				}

				for($cpt=0;$cpt<$nb_bulletins;$cpt++) {
					if($tab_rel['eleve'][$i]['resp'][$cpt]['civilite']!="") {
						$tab_adr_ligne1[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['civilite']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['nom']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['prenom'];
					}
					else {
						$tab_adr_ligne1[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['nom']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['prenom'];
					}

					$tab_adr_ligne2[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['adr1'];
					if($tab_rel['eleve'][$i]['resp'][$cpt]['adr2']!=""){
						$tab_adr_ligne3[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['adr2'];
					}
					if($tab_rel['eleve'][$i]['resp'][$cpt]['adr3']!=""){
						$tab_adr_ligne4[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['adr3'];
					}
					/*
					if($tab_rel['eleve'][$i]['resp'][$cpt]['adr4']!=""){
						$tab_adr_ligne2[$cpt].="<br />\n".$tab_rel['eleve'][$i]['resp'][$cpt]['adr4'];
					}
					*/
					$tab_adr_ligne5[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['cp']." ".$tab_rel['eleve'][$i]['resp'][$cpt]['commune'];

					if(($tab_rel['eleve'][$i]['resp'][$cpt]['pays']!="")&&(strtolower($tab_rel['eleve'][$i]['resp'][$cpt]['pays'])!=strtolower($gepiSchoolPays))) {
						$tab_adr_ligne6[$cpt]=$tab_rel['eleve'][$i]['resp'][$cpt]['pays'];
					}
				}
			}
		}
		else {
			// Il n'y a pas de deuxi�me responsable
			$nb_bulletins=1;

			if($tab_rel['eleve'][$i]['resp'][0]['civilite']!="") {
				$tab_adr_ligne1[0]=$tab_rel['eleve'][$i]['resp'][0]['civilite']." ".$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom'];
			}
			else {
				$tab_adr_ligne1[0]=$tab_rel['eleve'][$i]['resp'][0]['nom']." ".$tab_rel['eleve'][$i]['resp'][0]['prenom'];
			}

			$tab_adr_ligne2[0]=$tab_rel['eleve'][$i]['resp'][0]['adr1'];
			if($tab_rel['eleve'][$i]['resp'][0]['adr2']!=""){
				$tab_adr_ligne3[0]=$tab_rel['eleve'][$i]['resp'][0]['adr2'];
			}
			if($tab_rel['eleve'][$i]['resp'][0]['adr3']!=""){
				$tab_adr_ligne4[0]=$tab_rel['eleve'][$i]['resp'][0]['adr3'];
			}
			/*
			if($tab_rel['eleve'][$i]['resp'][0]['adr4']!=""){
				$tab_adr_ligne2[0].="<br />\n".$tab_rel['eleve'][$i]['resp'][0]['adr4'];
			}
			*/
			$tab_adr_ligne5[0]=$tab_rel['eleve'][$i]['resp'][0]['cp']." ".$tab_rel['eleve'][$i]['resp'][0]['commune'];

			if(($tab_rel['eleve'][$i]['resp'][0]['pays']!="")&&(strtolower($tab_rel['eleve'][$i]['resp'][0]['pays'])!=strtolower($gepiSchoolPays))) {
				$tab_adr_ligne6[0]=$tab_rel['eleve'][$i]['resp'][0]['pays'];
			}
		}
	}
	//=========================================


	$pdf->AddPage("P");
	$pdf->SetFontSize(10);

	/*
	if($nb_releve_par_page === '1' and $active_bloc_adresse_parent != '1') { $hauteur_cadre_note_global = 250; }
	if($nb_releve_par_page === '1' and $active_bloc_adresse_parent === '1') { $hauteur_cadre_note_global = 205; }
	if($nb_releve_par_page === '2') { $hauteur_cadre_note_global = 102; }
	*/

	// Pour un relev� en recto/verso avec le bulletin,
	// il ne faut qu'un relev� par page, mais si on devait utiliser cette fonction
	// pour remplacer un jour le dispositif relev� PDF, il faudrait revoir cela:
	$nb_releve_par_page=1;


	//$active_bloc_adresse_parent=0;
	$active_bloc_adresse_parent=($tab_rel['rn_adr_resp']=='y') ? 1 : 0;
	//$hauteur_cadre_note_global = 250;
	if($active_bloc_adresse_parent!=1) { $hauteur_cadre_note_global = 250; }
	if($active_bloc_adresse_parent==1) { $hauteur_cadre_note_global = 205; }

	// A FAIRE:
	// Pour la hauteur, prendre en compte la saisie d'une formule $tab_rel['rn_formule'] (non vide)
	// et le caract�re vide ou non de getSettingValue("bull_formule_bas")

	//$affiche_bloc_observation=1;
	$affiche_bloc_observation=($tab_rel['rn_bloc_obs']=='y') ? 1 : 0;

	//$affiche_cachet_pp=1;
	$affiche_cachet_pp=($tab_rel['rn_sign_pp']=='y') ? 1 : 0;
	//$affiche_signature_parent=1;
	$affiche_signature_parent=($tab_rel['rn_sign_resp']=='y') ? 1 : 0;

	if(($affiche_cachet_pp==1)||($affiche_signature_parent==1)) {$affiche_bloc_observation=1;}

	$texte_observation="Observations:";

	//$aff_classe_nom=1;
	$aff_classe_nom=$tab_rel['rn_aff_classe_nom'];

	// BIZARRE:
	$hauteur_cadre_matiere=20;
	$classe_aff="NOM_CLASSE";

	$passage_i=1;

	// login de l'�l�ve
	//$eleve_select=$login[$nb_eleves_i];
	//$eleve_select=$tab_rel['eleve'][$i]['login'];
	//if(isset($tab_rel['eleve'][$i]['login'])) {
		$eleve_select=$tab_rel['eleve'][$i]['login'];

		/*
		// diff�rente Y pour les pr�sentation sur 1 ou 2 par page avec ident parents
		if($nb_releve_par_page=='1' and $passage_i == '1' and $active_bloc_adresse_parent!='1') { $Y_cadre_note = '32'; $Y_cadre_eleve = '5'; $Y_entete_etab='5'; }
		if($nb_releve_par_page=='1' and $passage_i == '1' and $active_bloc_adresse_parent==='1') { $Y_cadre_note = '75'; $Y_cadre_eleve = '5'; $Y_entete_etab='5'; }
		if($nb_releve_par_page=='2' and $passage_i == '1') { $Y_cadre_note = '32'; $Y_cadre_eleve = '5'; $Y_entete_etab='5'; }
		if($nb_releve_par_page=='2' and $passage_i == '2') { $Y_cadre_note = $Y_cadre_note+145; $Y_cadre_eleve = $Y_cadre_eleve+145; $Y_entete_etab=$Y_entete_etab+145; }
		*/

		/*
		$Y_cadre_note = '32';
		$Y_cadre_eleve = '5';
		$Y_entete_etab='5';
		*/
		if($active_bloc_adresse_parent!='1') {
			$Y_cadre_note = '32';
			$Y_cadre_eleve = '5';
			$Y_entete_etab='5';
		}
		else {
			$Y_cadre_note = '75';
			$Y_cadre_eleve = '5';
			$Y_entete_etab='5';
		}


		//BLOC IDENTITE ELEVE
		$pdf->SetXY($X_cadre_eleve,$Y_cadre_eleve);
		$pdf->SetFont($caractere_utilse,'B',14);
		$pdf->Cell(90,7,strtoupper($tab_rel['eleve'][$i]['nom'])." ".ucfirst($tab_rel['eleve'][$i]['prenom']),0,2,'');
		$pdf->SetFont($caractere_utilse,'',10);
		//$pdf->Cell(90,5,'N� le '.affiche_date_naissance($naissance[$nb_eleves_i]).',�demi-pensionnaire',0,2,'');
		if($tab_rel['eleve'][$i]['sexe']=="M"){$e_au_feminin="";}else{$e_au_feminin="e";}

		//$pdf->Cell(90,5,'N�'.$e_au_feminin.' le '.affiche_date_naissance($tab_rel['eleve'][$i]['naissance']).',�'.regime($tab_rel['eleve'][$i]['regime']),0,2,'');
		$pdf->Cell(90,5,'N�'.$e_au_feminin.' le '.$tab_rel['eleve'][$i]['naissance'].',�'.regime($tab_rel['eleve'][$i]['regime']),0,2,'');

		$pdf->Cell(90,5,'',0,2,'');

		/*
		if ( $aff_classe_nom === '1' or $aff_classe_nom === '3' ) {
			$classe_aff = $pdf->WriteHTML('Classe de <B>'.unhtmlentities($tab_rel['classe_nom_complet']).'<B>');
		}
		if ( $aff_classe_nom === '2' ) {
			$classe_aff = $pdf->WriteHTML('Classe de <B>'.unhtmlentities($tab_rel['classe']).'<B>');
		}
		if ( $aff_classe_nom === '3' ) {
			$classe_aff = $pdf->WriteHTML(' ('.unhtmlentities($tab_rel['classe']).')');
		}
		*/
		//$classe_aff = $pdf->WriteHTML('Classe de <B>'.unhtmlentities($tab_rel['classe_nom_complet']).'<B>');
		//$classe_aff = $pdf->WriteHTML('Classe de <B>'.unhtmlentities($tab_rel['classe']).'<B>');
		//$classe_aff = $pdf->WriteHTML(' ('.unhtmlentities($tab_rel['classe']).')');
		// A REVOIR...
		//$classe_aff=$pdf->WriteHTML(' '.unhtmlentities($tab_rel['classe_nom_complet']).' ('.unhtmlentities($tab_rel['classe']).')');

		if($aff_classe_nom==1) {
			$classe_aff=$pdf->WriteHTML('Classe de '.unhtmlentities($tab_rel['classe_nom_complet']));
		}
		elseif($aff_classe_nom==2) {
			$classe_aff=$pdf->WriteHTML('Classe de '.unhtmlentities($tab_rel['classe']));
		}
		else {
			$classe_aff=$pdf->WriteHTML(' '.unhtmlentities($tab_rel['classe_nom_complet']).' ('.unhtmlentities($tab_rel['classe']).')');
		}

		$pdf->Cell(90,5,$classe_aff,0,2,'');
		$pdf->SetX($X_cadre_eleve);
		$pdf->SetFont($caractere_utilse,'',10);
		$pdf->Cell(90,5,'Ann�e scolaire '.$annee_scolaire,0,2,'');

		// BLOC IDENTITE DE L'ETABLISSEMENT
		$logo = '../images/'.getSettingValue('logo_etab');
		$format_du_logo = str_replace('.','',strstr(getSettingValue('logo_etab'), '.'));
		//if($affiche_logo_etab==='1' and file_exists($logo) and getSettingValue('logo_etab') != '' and ($format_du_logo==='jpg' or $format_du_logo==='png')) {
		//if($tab_modele_pdf["affiche_logo_etab"][$classe_id]==='1' and file_exists($logo) and getSettingValue('logo_etab') != '' and ($format_du_logo==='jpg' or $format_du_logo==='png')) {
		if($tab_modele_pdf["affiche_logo_etab"][$classe_id]==1 and file_exists($logo) and getSettingValue('logo_etab') != '' and ($format_du_logo=='jpg' or $format_du_logo=='png')) {
			$valeur=redimensionne_image($logo, $L_max_logo, $H_max_logo);
			//$X_logo et $Y_logo; placement du bloc identite de l'�tablissement
			$X_logo=$X_entete_etab;
			$Y_logo=$Y_entete_etab;
			$L_logo=$valeur[0];
			$H_logo=$valeur[1];
			$X_etab=$X_logo+$L_logo;
			$Y_etab=$Y_logo;
			//logo
			$pdf->Image($logo, $X_logo, $Y_logo, $L_logo, $H_logo);
		}
		else {
			$X_etab = $X_entete_etab; $Y_etab = $Y_entete_etab;
		}

		// BLOC ADRESSE ETABLISSEMENT
		$pdf->SetXY($X_etab,$Y_etab);
		$pdf->SetFont($caractere_utilse,'',14);
		//$gepiSchoolName = getSettingValue('gepiSchoolName');
		$pdf->Cell(90,7, $gepiSchoolName,0,2,'');
		$pdf->SetFont($caractere_utilse,'',10);
		//$gepiSchoolAdress1 = getSettingValue('gepiSchoolAdress1');
		$pdf->Cell(90,5, $gepiSchoolAdress1,0,2,'');
		//$gepiSchoolAdress2 = getSettingValue('gepiSchoolAdress2');
		$pdf->Cell(90,5, $gepiSchoolAdress2,0,2,'');
		//$gepiSchoolZipCode = getSettingValue('gepiSchoolZipCode');
		//$gepiSchoolCity = getSettingValue('gepiSchoolCity');
		$pdf->Cell(90,5, $gepiSchoolZipCode." ".$gepiSchoolCity,0,2,'');
		//$gepiSchoolTel = getSettingValue('gepiSchoolTel');
		//$gepiSchoolFax = getSettingValue('gepiSchoolFax');
		if($tab_modele_pdf["entente_tel"][$classe_id]==='1' and $tab_modele_pdf["entente_fax"][$classe_id]==='1') {
			$entete_communic = 'T�l: '.$gepiSchoolTel.' / Fax: '.$gepiSchoolFax;
		}
		if($tab_modele_pdf["entente_tel"][$classe_id]==='1' and empty($entete_communic)) {
			$entete_communic = 'T�l: '.$gepiSchoolTel;
		}
		if($tab_modele_pdf["entente_fax"][$classe_id]==='1' and empty($entete_communic)) {
			$entete_communic = 'Fax: '.$gepiSchoolFax;
		}
		if(isset($entete_communic) and $entete_communic!='') {
			$pdf->Cell(90,5, $entete_communic,0,2,'');
		}
		if($tab_modele_pdf["entente_mel"][$classe_id]==='1') {
			$gepiSchoolEmail = getSettingValue('gepiSchoolEmail');
			$pdf->Cell(90,5, $gepiSchoolEmail,0,2,'');
		}

		// BLOC ADRESSE DES PARENTS
		// Nom des variables � revoir
		//if($active_bloc_adresse_parent==='1' and $nb_releve_par_page==='1') {
		if($active_bloc_adresse_parent==1 and $nb_releve_par_page==1) {

			//+++++++++++++++
			// A REVOIR
			$num_resp=0;
			//+++++++++++++++

			//$ident_eleve_aff=$login[$nb_eleves_i];
			$pdf->SetXY($X_parent,$Y_parent);
			//$texte_1_responsable = $civilite_parents[$ident_eleve_aff][$responsable_place]." ".$nom_parents[$ident_eleve_aff][$responsable_place]." ".$prenom_parents[$ident_eleve_aff][$responsable_place];
			$texte_1_responsable=$tab_adr_ligne1[$num_resp];
			$hauteur_caractere=12;
			$pdf->SetFont($caractere_utilse,'B',$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte_1_responsable);
			$taille_texte = 90;
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if($taille_texte<$val) {
					$hauteur_caractere = $hauteur_caractere-0.3;
					$pdf->SetFont($caractere_utilse,'B',$hauteur_caractere);
					$val = $pdf->GetStringWidth($texte_1_responsable);
				}
				else {
					$grandeur_texte='ok';
				}
			}
			$pdf->Cell(90,7, $texte_1_responsable,0,2,'');

			$pdf->SetFont($caractere_utilse,'',10);
			//$texte_1_responsable = $adresse1_parents[$ident_eleve_aff][$responsable_place];
			$texte_1_responsable=$tab_adr_ligne2[$num_resp];
			$hauteur_caractere=10;
			$pdf->SetFont($caractere_utilse,'',$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte_1_responsable);
			$taille_texte = 90;
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if($taille_texte<$val) {
					$hauteur_caractere = $hauteur_caractere-0.3;
					$pdf->SetFont($caractere_utilse,'',$hauteur_caractere);
					$val = $pdf->GetStringWidth($texte_1_responsable);
				}
				else {
					$grandeur_texte='ok';
				}
			}

			$pdf->Cell(90,5, $texte_1_responsable,0,2,'');
			//$texte_1_responsable = $adresse2_parents[$ident_eleve_aff][$responsable_place];
			$texte_1_responsable=$tab_adr_ligne3[$num_resp];
			$hauteur_caractere=10;
			$pdf->SetFont($caractere_utilse,'',$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte_1_responsable);
			$taille_texte = 90;
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if($taille_texte<$val) {
					$hauteur_caractere = $hauteur_caractere-0.3;
					$pdf->SetFont($caractere_utilse,'',$hauteur_caractere);
					$val = $pdf->GetStringWidth($texte_1_responsable);
				}
				else {
					$grandeur_texte='ok';
				}
			}

			$pdf->Cell(90,5, $texte_1_responsable,0,2,'');
			$pdf->Cell(90,5, '',0,2,'');
			//$texte_1_responsable = $cp_parents[$ident_eleve_aff][$responsable_place]." ".$ville_parents[$ident_eleve_aff][$responsable_place];
			$texte_1_responsable=$tab_adr_ligne5[$num_resp];
			$hauteur_caractere=10;
			$pdf->SetFont($caractere_utilse,'',$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte_1_responsable);
			$taille_texte = 90;
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if($taille_texte<$val) {
					$hauteur_caractere = $hauteur_caractere-0.3;
					$pdf->SetFont($caractere_utilse,'',$hauteur_caractere);
					$val = $pdf->GetStringWidth($texte_1_responsable);
				}
				else {
					$grandeur_texte='ok';
				}
			}
			$pdf->Cell(90,5, $texte_1_responsable,0,2,'');
		}

		// BLOC NOTATION ET OBSERVATION
		//Titre du tableau
		$pdf->SetXY($X_cadre_note,$Y_cadre_note);
		$pdf->SetFont($caractere_utilse,'B',12);
		//if($cadre_titre==='1') { $var_encadrement_titre='LTR'; } else { $var_encadrement_titre=''; }
		if($cadre_titre==1) { $var_encadrement_titre='LTR'; } else { $var_encadrement_titre=''; }

		//$pdf->Cell(0, $hauteur_du_titre, $titre_du_cadre.' '.date_frc($_SESSION['date_debut_aff']).' au '.date_frc($_SESSION['date_fin_aff']), $var_encadrement_titre,0,'C');
		// A REVOIR...
		//$pdf->Cell(0, $hauteur_du_titre, $titre_du_cadre.' P�riode '.$tab_rel['nom_periode'], $var_encadrement_titre,0,'C');
		$pdf->Cell(0, $hauteur_du_titre, $titre_du_cadre.$tab_rel['nom_periode'], $var_encadrement_titre,0,'C');

		$hauteur_utilise = $hauteur_du_titre;

		/*
		$nb_matiere=0;
		for($j=0;$j<count($tab_rel['eleve'][$i]['groupe']);$j++) {
			if(isset($tab_bull['note'][$j][$i])) {
				// Si l'�l�ve suit l'option, sa note est affect�e (�ventuellement vide)
				$nb_matiere++;
			}
		}
		*/
		$nb_matiere=count($tab_rel['eleve'][$i]['groupe']);


		//s'il y des notes alors on affiche le cadre avec les notes
		//if(isset($nb_matiere[$eleve_select]) and !empty($nb_matiere[$eleve_select])) {
		if($nb_matiere>0) {
			// Hauteur d'une ligne pour une mati�re
			/*
			if($active_entete_regroupement === '1') {
				$hauteur_cadre_matiere=($hauteur_cadre_note_global-($nb_regroupement[$eleve_select]*$hauteur_dun_regroupement))/$nb_matiere[$eleve_select];
			}
			if($active_entete_regroupement != '1') {
			*/
				$hauteur_cadre_matiere=$hauteur_cadre_note_global/$nb_matiere;
			//}

			// Tableau des mati�res et des notes de l'�l�ve
			$cpt_i='1';
			$nom_regroupement_passer='';
			//while($cpt_i<=$nb_matiere[$eleve_select])
			//{
			for($m=0; $m<count($tab_rel['eleve'][$i]['groupe']); $m++) {

				// Si c'est une mati�re suivie par l'�l�ve
				if(isset($tab_rel['eleve'][$i]['groupe'][$m])) {

					//$id_groupe_selectionne=$groupe_select[$eleve_select][$cpt_i];
					$id_groupe_selectionne=$tab_rel['eleve'][$i]['groupe'][$m]['id_groupe'];
					//MATIERE
					$pdf->SetXY($X_cadre_note,$Y_cadre_note+$hauteur_utilise);

					// on affiche les nom des regroupements
					/*
					if($nom_regroupement[$eleve_select][$cpt_i]!=$nom_regroupement_passer and $active_entete_regroupement === '1')
					{
						$pdf->SetFont($caractere_utilse,'',8);
						$pdf->Cell($largeur_cadre_matiere, $hauteur_dun_regroupement, unhtmlentities($nom_regroupement[$eleve_select][$cpt_i]), 'LTB', 2, '');
						$hauteur_utilise=$hauteur_utilise+$hauteur_dun_regroupement;
						$nom_regroupement_passer=$nom_regroupement[$eleve_select][$cpt_i];
						$pdf->SetXY($X_cadre_note,$Y_cadre_note+$hauteur_utilise);
					}
					*/
					$pdf->SetFont($caractere_utilse,'B','9');
					$nom_matiere = $tab_rel['eleve'][$i]['groupe'][$m]['matiere_nom_complet'];
					$hauteur_caractere = 9;
					$pdf->SetFont($caractere_utilse,'B',$hauteur_caractere);
					$val = $pdf->GetStringWidth($nom_matiere);
					$taille_texte = $largeur_cadre_matiere;
					$grandeur_texte='test';
					while($grandeur_texte!='ok') {
						if($taille_texte<$val) {
							$hauteur_caractere = $hauteur_caractere-0.3;
							$pdf->SetFont($caractere_utilse,'B',$hauteur_caractere);
							$val = $pdf->GetStringWidth($nom_matiere);
						}
						else {
							$grandeur_texte='ok';
						}
					}
					$pdf->Cell($largeur_cadre_matiere, $hauteur_cadre_matiere/2, $nom_matiere, 'LRT', 2, '');
					//$pdf->Cell($largeur_cadre_matiere, $hauteur_cadre_matiere/2, $nom_matiere." ".count($tab_rel['eleve'][$i]['groupe'][$m]['prof_login']), 'LRT', 2, '');
					//$pdf->Cell($largeur_cadre_matiere, $hauteur_cadre_matiere/2, $nom_matiere." ".$tab_rel['eleve'][$i]['groupe'][$m]['prof_login'][0], 'LRT', 2, '');
					$nom_matiere = '';

					$nb_prof_matiere = count($tab_rel['eleve'][$i]['groupe'][$m]['prof_login']);
					$espace_matiere_prof = $hauteur_cadre_matiere/2;
					$nb_pass_count = '0';
					$text_prof = '';

					//if ( $nb_releve_par_page === '2' ) {
					if ($nb_releve_par_page==2) {
						$nb_pass_count_2 = 0;
						while ( !empty($tab_rel['eleve'][$i]['groupe'][$m]['prof_login'][$nb_pass_count_2]) )
						{
							if ( $nb_pass_count_2 === 0 ) {
								$text_prof = affiche_utilisateur($tab_rel['eleve'][$i]['groupe'][$m]['prof_login'][$nb_pass_count_2],$id_classe);
							}
							if ( $nb_pass_count_2 != 0 ) {
								$text_prof = $text_prof.', '.affiche_utilisateur($tab_rel['eleve'][$i]['groupe'][$m]['prof_login'][$nb_pass_count_2],$id_classe);
							}
							$nb_pass_count_2 = $nb_pass_count_2 + 1;
						}
						//$nb_prof_matiere = 1;
					}
					if ( $nb_prof_matiere != 1 ) {
						$espace_matiere_prof = $espace_matiere_prof/$nb_prof_matiere;
					}

					while ($nb_prof_matiere > $nb_pass_count) {

						// calcul de la hauteur du caract�re du prof
						//if ( $nb_releve_par_page === '1' ) {
						if ($nb_releve_par_page==1) {
							$text_prof = affiche_utilisateur($tab_rel['eleve'][$i]['groupe'][$m]['prof_login'][$nb_pass_count],$id_classe);
						}
						if ( $nb_prof_matiere <= 2 ) { $hauteur_caractere_prof = 9; }
						elseif ( $nb_prof_matiere == 3) { $hauteur_caractere_prof = 7; }
						elseif ( $nb_prof_matiere > 3) { $hauteur_caractere_prof = 2; }
						$pdf->SetFont($caractere_utilse,'',$hauteur_caractere_prof);
						$val = $pdf->GetStringWidth($text_prof);
						$taille_texte = ($largeur_cadre_matiere-0.6);
						$grandeur_texte='test';
						while($grandeur_texte!='ok') {
							if($taille_texte<$val)
							{
								$hauteur_caractere_prof = $hauteur_caractere_prof-0.3;
								$pdf->SetFont($caractere_utilse,'',$hauteur_caractere_prof);
								$val = $pdf->GetStringWidth($text_prof);
							}
							else {
								$grandeur_texte='ok';
							}
						}
						$grandeur_texte='test';
						$pdf->SetX($X_cadre_note);
						//$pdf->Cell($largeur_cadre_matiere, $espace_matiere_prof, 'prof '.$text_prof, 'LRB', 2, '');

						if( empty($tab_rel['eleve'][$i]['groupe'][$m]['prof_login'][$nb_pass_count+1]) or $nb_prof_matiere === 1 ) {
							$pdf->Cell($largeur_cadre_matiere, $espace_matiere_prof, $text_prof, 'LRB', 2, '');
						}
						if( !empty($tab_rel['eleve'][$i]['groupe'][$m]['prof_login'][$nb_pass_count+1]) and $nb_prof_matiere != 1 ) {
							$pdf->Cell($largeur_cadre_matiere, $espace_matiere_prof, $text_prof, 'LR', 2, '');
						}

						$nb_pass_count = $nb_pass_count + 1;
					}

					//			if(isset($prof_groupe[$id_groupe_selectionne][0]) and $prof_groupe[$id_groupe_selectionne][0] != '') { $prof_1 = $prof_groupe[$id_groupe_selectionne][0]; } else { $prof_1 = ''; }
					//			if(isset($prof_groupe[$id_groupe_selectionne][1]) and $prof_groupe[$id_groupe_selectionne][1] != '') { $prof_2 = $prof_groupe[$id_groupe_selectionne][1]; } else { $prof_2 = ''; }
					//			if(isset($prof_groupe[$id_groupe_selectionne][2]) and $prof_groupe[$id_groupe_selectionne][2] != '') { $prof_3 = $prof_groupe[$id_groupe_selectionne][2]; } else { $prof_3 = ''; }
					/*			 $nom_prof = $prof_1;
					$hauteur_caractere = 8;
					$pdf->SetFont($caractere_utilse,'I',$hauteur_caractere);
					$val = $pdf->GetStringWidth($nom_prof);
					$taille_texte = $largeur_cadre_matiere;
					$grandeur_texte='test';
					while($grandeur_texte!='ok') {
					if($taille_texte<$val)
					{
						$hauteur_caractere = $hauteur_caractere-0.3;
						$pdf->SetFont($caractere_utilse,'I',$hauteur_caractere);
						$val = $pdf->GetStringWidth($nom_prof);
					} else { $grandeur_texte='ok'; }
						}

					$pdf->Cell($largeur_cadre_matiere, $hauteur_cadre_matiere/2, $nom_prof, 'LRB', 2, '');*/
					//$pdf->Cell($largeur_cadre_matiere, $hauteur_cadre_matiere/3, $prof_2, 'LR', 2, '');
					//$pdf->Cell($largeur_cadre_matiere, $hauteur_cadre_matiere/4, $prof_3, 'LRB', 2, '');
					$hauteur_utilise=$hauteur_utilise+$hauteur_cadre_matiere;

				}
				$cpt_i=$cpt_i+1;
			}
		}

		$hauteur_utilise = $hauteur_du_titre;

		$cpt_i='1';
		$nom_regroupement_passer='';


		//while($cpt_i<=$nb_matiere[$eleve_select]) {
		for($m=0; $m<count($tab_rel['eleve'][$i]['groupe']); $m++) {
			//NOTES
			$largeur_utilise=$largeur_cadre_matiere;
			//=======================
			// AJOUT: chapel 20071019
			//if ( $affiche_bloc_observation === '1' ) {
			if ( $affiche_bloc_observation==1) {
				$largeur_cadre_note = $largeur_cadre_note;
			}
			else {
				$largeur_cadre_note = $largeur_cadre_note_global - $largeur_utilise;
			}
			//=======================
			$pdf->SetXY($X_cadre_note+$largeur_utilise,$Y_cadre_note+$hauteur_utilise);
			// on affiche les nom des regroupement
			/*
			if($nom_regroupement[$eleve_select][$cpt_i]!=$nom_regroupement_passer and $active_entete_regroupement === '1')
			{
				$pdf->SetFont($caractere_utilse,'',8);
				$pdf->Cell($largeur_cadre_note, $hauteur_dun_regroupement, '', 'RTB', 2, '');
				$hauteur_utilise=$hauteur_utilise+$hauteur_dun_regroupement;
				$nom_regroupement_passer=$nom_regroupement[$eleve_select][$cpt_i];
				$pdf->SetXY($X_cadre_note+$largeur_utilise,$Y_cadre_note+$hauteur_utilise);
			}
			*/

			$chaine_notes="";
			if(isset($tab_rel['eleve'][$i]['groupe'][$m]['devoir'])) {
				$kk=0;
				for($k=0;$k<count($tab_rel['eleve'][$i]['groupe'][$m]['devoir']);$k++) {
					// A FAIRE: TENIR COMPTE DE TOUS LES PARAMETRES POUR VOIR CE QU'IL FAUT AFFICHER
					if($kk>0) {$chaine_notes.=" - ";}
					if($tab_rel['eleve'][$i]['groupe'][$m]['devoir'][$k]['statut']!='v') {
						if($tab_rel['eleve'][$i]['groupe'][$m]['devoir'][$k]['statut']!='') {
							$chaine_notes.=$tab_rel['eleve'][$i]['groupe'][$m]['devoir'][$k]['statut'];
						}
						else {
							$chaine_notes.=$tab_rel['eleve'][$i]['groupe'][$m]['devoir'][$k]['note'];
						}

						if($tab_rel['rn_nomdev']=='y') {
							$chaine_notes.=" (".$tab_rel['eleve'][$i]['groupe'][$m]['devoir'][$k]['nom_court'].")";
						}

						if($tab_rel['rn_datedev']=='y') {
							$chaine_notes.=" (".formate_date($tab_rel['eleve'][$i]['groupe'][$m]['devoir'][$k]['date']).")";
						}

						if($tab_rel['rn_coefdev_si_diff']=='y') {
							if($tab_rel['eleve'][$i]['groupe'][$m]['differents_coef']=='y') {
								$chaine_notes.=" (coef ".$tab_rel['eleve'][$i]['groupe'][$m]['devoir'][$k]['coef'].")";
							}
						}
						else {
							if($tab_rel['rn_toutcoefdev']=='y') {
								$chaine_notes.=" (coef ".$tab_rel['eleve'][$i]['groupe'][$m]['devoir'][$k]['coef'].")";
							}
						}

						$kk++;
					}
				}
			}

			// d�termine la taille de la police de caract�re
			// on peut allez jusqu'a 275mm de caract�re dans trois cases de notes
			$hauteur_caractere_notes=9;
			$pdf->SetFont($caractere_utilse,'',$hauteur_caractere_notes);
			$val = $pdf->GetStringWidth($chaine_notes);
			$taille_texte = (($hauteur_cadre_matiere/4)*$largeur_cadre_note);
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if($taille_texte<$val) {
					$hauteur_caractere_notes = $hauteur_caractere_notes-0.3;
					$pdf->SetFont($caractere_utilse,'',$hauteur_caractere_notes);
					$val = $pdf->GetStringWidth($chaine_notes);
				}
				else {
					$grandeur_texte='ok';
				}
			}
			$pdf->drawTextBox($chaine_notes, $largeur_cadre_note, $hauteur_cadre_matiere, 'J', 'M', 1);
			$hauteur_utilise=$hauteur_utilise+$hauteur_cadre_matiere;

			//$cpt_i=$cpt_i+1;
		}


		// BLOC OBSERVATION
		//=======================
		// MODIF: chapel 20071019
		//if($affiche_bloc_observation === '1')
		if($affiche_bloc_observation==1)
		{
			$largeur_utilise=$largeur_cadre_matiere+$largeur_cadre_note;
			$largeur_restant=$largeur_cadre_note_global-$largeur_utilise;
			$hauteur_utilise = $hauteur_du_titre;
			//if($affiche_cachet_pp==='1' or $affiche_signature_parent==='1')
			if($affiche_cachet_pp==1 or $affiche_signature_parent==1)
			{
				$hauteur_cadre_observation=$hauteur_cadre_note_global-$hauteur_cachet;
			}
			else {
				$hauteur_cadre_observation=$hauteur_cadre_note_global;
			}
			$pdf->Rect($X_cadre_note+$largeur_utilise, $Y_cadre_note+$hauteur_utilise, $largeur_restant, $hauteur_cadre_observation, 'D');
			$pdf->SetXY($X_cadre_note+$largeur_utilise, $Y_cadre_note+$hauteur_utilise);
			$pdf->SetFont($caractere_utilse,'',11);
			$pdf->Cell($largeur_restant,7, $texte_observation,0,1,'C');
		}
		//=======================

		// BLOC SIGNATURE
		//if($affiche_cachet_pp==='1' or $affiche_signature_parent==='1')
		if($affiche_cachet_pp==1 or $affiche_signature_parent==1)
		{
			$nb_col_sign = 0;
			//if($affiche_cachet_pp==='1') { $nb_col_sign=$nb_col_sign+1; }
			//if($affiche_signature_parent==='1') { $nb_col_sign=$nb_col_sign+1; }
			if($affiche_cachet_pp==1) { $nb_col_sign=$nb_col_sign+1; }
			if($affiche_signature_parent==1) { $nb_col_sign=$nb_col_sign+1; }
			$largeur_utilise=$largeur_cadre_matiere+$largeur_cadre_note;

			$X_signature = $X_cadre_note+$largeur_utilise;
			$Y_signature = $Y_cadre_note+$hauteur_cadre_observation+$hauteur_du_titre;
			$hauteur_cadre_signature=$hauteur_cadre_note_global-$hauteur_cadre_observation;
			$largeur_cadre_signature=$largeur_cadre_note_global-$largeur_utilise;

			$pdf->SetFont($caractere_utilse,'',8);
			$pdf->Rect($X_signature, $Y_signature, $largeur_cadre_signature, $hauteur_cadre_signature, 'D');

			//if($affiche_cachet_pp==='1')
			if($affiche_cachet_pp==1)
			{
				$pdf->SetXY($X_signature, $Y_signature);
				$pdf->Cell($largeur_cadre_signature/$nb_col_sign,4, 'Signature','LTR',2,'C');
				$pdf->Cell($largeur_cadre_signature/$nb_col_sign,4, $gepi_prof_suivi,'LR',2,'C');
				$pdf->Cell($largeur_cadre_signature/$nb_col_sign,$hauteur_cachet-8, '','LR',2,'C');
				$X_signature = $X_signature+($largeur_restant/$nb_col_sign);
			}
			//if($affiche_signature_parent==='1')
			if($affiche_signature_parent==1)
			{
				$pdf->SetXY($X_signature, $Y_signature);
				$pdf->Cell($largeur_cadre_signature/$nb_col_sign,4, 'Signatures','LTR',2,'C');
				$pdf->Cell($largeur_cadre_signature/$nb_col_sign,4, 'des parents','LR',2,'C');
				$pdf->Cell($largeur_cadre_signature/$nb_col_sign,$hauteur_cachet-8, '','LR',2,'C');
			}
		}
		//}

		/*
		//PUB ;)
		$pdf->SetXY($X_cadre_note, $Y_cadre_note+$hauteur_cadre_note_global+$hauteur_du_titre);
		$pdf->SetFont('arial','',8);
		$pdf->Cell(200,5,'GEPI - Solution libre de Gestion des �l�ves par Internet',0,1,'');
		// CA ENTRE EN COLLISION AVEC LA FORMULE DU BULLETIN (ins�r�e via la fonction Footer() de class_php/gepi_pdf.class.php)
		*/
	//}

	/*
		$passage_i=$passage_i+1;
		$nb_eleves_i = $nb_eleves_i + 1;
	}

	// on pr�pare la 2�me boucle pour faire R1 et R2 != R1 si n�cessaire
	if ($nb_eleves_i > $nb_eleves) { // dans ce cas on a fait la premi�re boucle, on pr�pare la 2�me pour les R2 != � R1
		$nb_boucle++;
		$responsable_place = 1;
		$nb_eleves_i = 1;
	}
	*/

//}

// vider les variables de session
//    unset($_SESSION["classe"]);
//    unset($_SESSION["eleve"]);
//    unset($_SESSION["type"]);
//    unset($_SESSION["date_debut"]);
//    unset($_SESSION["date_fin"]);
//    unset($_SESSION["date_debut_aff"]);
//    unset($_SESSION["date_fin_aff"]);
//    unset($_SESSION["avec_nom_devoir"]);

/*
// sortie PDF sur �cran
$nom_releve=date("Ymd_Hi");
$nom_releve = 'Releve_'.$nom_releve.'.pdf';
$pdf->Output($nom_releve,'I');

// Le PDF n'est g�n�r� qu'en fin de boucle sur les bulletins
*/
}


?>
