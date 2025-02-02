<?php
/* $Id$ */

function bull_exb($tab_ele,$i) {
	global
		//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = == = = =
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
		$gepiSchoolEmail,
		$gepiYear,

		$logo_etab,

		$bull_intitule_app,

		$bull_formule_bas,

		// Param�tre transmis depuis la page d'impression des bulletins
		$un_seul_bull_par_famille,

		$compteur_bulletins,

		// Datation du bulletin (param�tre initi� dans l'ent�te du bulletin PDF)
		$date_bulletin,

		// Param�tres du mod�le PDF
		$tab_modele_pdf,

		$use_cell_ajustee,

		// Objet PDF initi� hors de la pr�sente fonction donnant la page du bulletin pour un �l�ve
		$pdf;
		//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = ==

	// Pr�paration des lignes d'adresse

	//echo "\$i=$i et \$nb_bulletins=$nb_bulletins<br />";

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

	if (!isset($tab_ele['resp'][0])) {
		//$tab_adr_ligne1[0]="<font color='red'><b>ADRESSE MANQUANTE</b></font>";
		$tab_adr_ligne1[0]="ADRESSE MANQUANTE";
		$tab_adr_ligne2[0]="";
		$tab_adr_ligne3[0]="";
		$tab_adr_ligne4[0]="";
		$tab_adr_ligne5[0]="";

		// Initialisation parce qu'on a des blagues s'il n'y a pas de resp:
		$nb_bulletins=1;
	}
	else {
		if (isset($tab_ele['resp'][1])) {
			if((isset($tab_ele['resp'][1]['adr1']))&&
				(isset($tab_ele['resp'][1]['adr2']))&&
				(isset($tab_ele['resp'][1]['adr3']))&&
				(isset($tab_ele['resp'][1]['adr4']))&&
				(isset($tab_ele['resp'][1]['cp']))&&
				(isset($tab_ele['resp'][1]['commune']))
			) {
				// Le deuxi�me responsable existe et est renseign�
				if (($tab_ele['resp'][0]['adr_id']==$tab_ele['resp'][1]['adr_id']) OR
					(
						($tab_ele['resp'][0]['adr1']==$tab_ele['resp'][1]['adr1'])&&
						($tab_ele['resp'][0]['adr2']==$tab_ele['resp'][1]['adr2'])&&
						($tab_ele['resp'][0]['adr3']==$tab_ele['resp'][1]['adr3'])&&
						($tab_ele['resp'][0]['adr4']==$tab_ele['resp'][1]['adr4'])&&
						($tab_ele['resp'][0]['cp']==$tab_ele['resp'][1]['cp'])&&
						($tab_ele['resp'][0]['commune']==$tab_ele['resp'][1]['commune'])
					)
				) {
					// Les adresses sont identiques
					$nb_bulletins=1;

					if(($tab_ele['resp'][0]['nom']!=$tab_ele['resp'][1]['nom'])&&
						($tab_ele['resp'][1]['nom']!="")) {
						// Les noms des responsables sont diff�rents
						$tab_adr_ligne1[0]=$tab_ele['resp'][0]['civilite']." ".$tab_ele['resp'][0]['nom']." ".$tab_ele['resp'][0]['prenom']." et ".$tab_ele['resp'][1]['civilite']." ".$tab_ele['resp'][1]['nom']." ".$tab_ele['resp'][1]['prenom'];

						/*
						$tab_adr_ligne1[0]=$tab_ele['resp'][0]['civilite']." ".$tab_ele['resp'][0]['nom']." ".$tab_ele['resp'][0]['prenom'];
						//$tab_adr_ligne1[0].=" et ";
						$tab_adr_ligne1[0].="<br />\n";
						$tab_adr_ligne1[0].="et ";
						$tab_adr_ligne1[0].=$tab_ele['resp'][1]['civilite']." ".$tab_ele['resp'][1]['nom']." ".$tab_ele['resp'][1]['prenom'];
						*/
					}
					else{
						if(($tab_ele['resp'][0]['civilite']!="")&&($tab_ele['resp'][1]['civilite']!="")) {
							$tab_adr_ligne1[0]=$tab_ele['resp'][0]['civilite']." et ".$tab_ele['resp'][1]['civilite']." ".$tab_ele['resp'][0]['nom']." ".$tab_ele['resp'][0]['prenom'];
						}
						else {
							$tab_adr_ligne1[0]="M. et Mme ".$tab_ele['resp'][0]['nom']." ".$tab_ele['resp'][0]['prenom'];
						}
					}

					$tab_adr_ligne2[0]=$tab_ele['resp'][0]['adr1'];
					if($tab_ele['resp'][0]['adr2']!=""){
						$tab_adr_ligne3[0]=$tab_ele['resp'][0]['adr2'];
					}
					if($tab_ele['resp'][0]['adr3']!=""){
						$tab_adr_ligne4[0]=$tab_ele['resp'][0]['adr3'];
					}
					
					$tab_adr_ligne5[0]=$tab_ele['resp'][0]['cp']." ".$tab_ele['resp'][0]['commune'];


					if(($tab_ele['resp'][0]['pays']!="")&&(strtolower($tab_ele['resp'][0]['pays'])!=strtolower($gepiSchoolPays))) {
						$tab_adr_ligne6[0]=$tab_ele['resp'][0]['pays'];
					}

				}
				else {
					// Les adresses sont diff�rentes
					//if ($un_seul_bull_par_famille!="oui") {
					// On teste en plus si la deuxi�me adresse est valide
					if (($un_seul_bull_par_famille!="oui")&&
						($tab_ele['resp'][1]['adr1']!="")&&
						($tab_ele['resp'][1]['commune']!="")
					) {
						$nb_bulletins=2;
					}
					else {
						$nb_bulletins=1;
					}

					for($cpt=0;$cpt<$nb_bulletins;$cpt++) {
						if($tab_ele['resp'][$cpt]['civilite']!="") {
							$tab_adr_ligne1[$cpt]=$tab_ele['resp'][$cpt]['civilite']." ".$tab_ele['resp'][$cpt]['nom']." ".$tab_ele['resp'][$cpt]['prenom'];
						}
						else {
							$tab_adr_ligne1[$cpt]=$tab_ele['resp'][$cpt]['nom']." ".$tab_ele['resp'][$cpt]['prenom'];
						}

						$tab_adr_ligne2[$cpt]=$tab_ele['resp'][$cpt]['adr1'];
						if($tab_ele['resp'][$cpt]['adr2']!=""){
							$tab_adr_ligne3[$cpt]=$tab_ele['resp'][$cpt]['adr2'];
						}
						if($tab_ele['resp'][$cpt]['adr3']!=""){
							$tab_adr_ligne4[$cpt]=$tab_ele['resp'][$cpt]['adr3'];
						}
						
						$tab_adr_ligne5[$cpt]=$tab_ele['resp'][$cpt]['cp']." ".$tab_ele['resp'][$cpt]['commune'];

						if(($tab_ele['resp'][$cpt]['pays']!="")&&(strtolower($tab_ele['resp'][$cpt]['pays'])!=strtolower($gepiSchoolPays))) {
							$tab_adr_ligne6[$cpt]=$tab_ele['resp'][$cpt]['pays'];
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
					if($tab_ele['resp'][$cpt]['civilite']!="") {
						$tab_adr_ligne1[$cpt]=$tab_ele['resp'][$cpt]['civilite']." ".$tab_ele['resp'][$cpt]['nom']." ".$tab_ele['resp'][$cpt]['prenom'];
					}
					else {
						$tab_adr_ligne1[$cpt]=$tab_ele['resp'][$cpt]['nom']." ".$tab_ele['resp'][$cpt]['prenom'];
					}

					$tab_adr_ligne2[$cpt]=$tab_ele['resp'][$cpt]['adr1'];
					if($tab_ele['resp'][$cpt]['adr2']!=""){
						$tab_adr_ligne3[$cpt]=$tab_ele['resp'][$cpt]['adr2'];
					}
					if($tab_ele['resp'][$cpt]['adr3']!=""){
						$tab_adr_ligne4[$cpt]=$tab_ele['resp'][$cpt]['adr3'];
					}
					
					$tab_adr_ligne5[$cpt]=$tab_ele['resp'][$cpt]['cp']." ".$tab_ele['resp'][$cpt]['commune'];

					if(($tab_ele['resp'][$cpt]['pays']!="")&&(strtolower($tab_ele['resp'][$cpt]['pays'])!=strtolower($gepiSchoolPays))) {
						$tab_adr_ligne6[$cpt]=$tab_ele['resp'][$cpt]['pays'];
					}
				}
			}
		}
		else {
			// Il n'y a pas de deuxi�me responsable
			$nb_bulletins=1;

			if($tab_ele['resp'][0]['civilite']!="") {
				$tab_adr_ligne1[0]=$tab_ele['resp'][0]['civilite']." ".$tab_ele['resp'][0]['nom']." ".$tab_ele['resp'][0]['prenom'];
			}
			else {
				$tab_adr_ligne1[0]=$tab_ele['resp'][0]['nom']." ".$tab_ele['resp'][0]['prenom'];
			}

			$tab_adr_ligne2[0]=$tab_ele['resp'][0]['adr1'];
			if($tab_ele['resp'][0]['adr2']!=""){
				$tab_adr_ligne3[0]=$tab_ele['resp'][0]['adr2'];
			}
			if($tab_ele['resp'][0]['adr3']!=""){
				$tab_adr_ligne4[0]=$tab_ele['resp'][0]['adr3'];
			}
			
			$tab_adr_ligne5[0]=$tab_ele['resp'][0]['cp']." ".$tab_ele['resp'][0]['commune'];

			if(($tab_ele['resp'][0]['pays']!="")&&(strtolower($tab_ele['resp'][0]['pays'])!=strtolower($gepiSchoolPays))) {
				$tab_adr_ligne6[0]=$tab_ele['resp'][0]['pays'];
			}
		}
	}
	//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = ==


	//+++++++++++++++++++++++++++++++++++++++++++
	// A FAIRE
	// Mettre ici une boucle pour $nb_bulletins
	// Et tenir compte par la suite de la demande d'intercaler le relev� de notes ou non
	//+++++++++++++++++++++++++++++++++++++++++++

	for($num_resp_bull=0;$num_resp_bull<$nb_bulletins;$num_resp_bull++) {
//echo "debug";
		$pdf->AddPage(); //ajout d'une page au document
		$pdf->SetFont('Arial');

		//= = = == = = == = = == = = == = = == = = == = = == = = =
		// On ins�re le footer d�s que la page est cr��e:
		//Positionnement � 1 cm du bas et 0,5cm + 0,5cm du cot� gauche
		$pdf->SetXY(5,-10);
		//Police Arial Gras 6
		$pdf->SetFont('Arial','B',8);
		// $fomule = 'Bulletin � conserver pr�cieusement. Aucun duplicata ne sera d�livr�. - GEPI : solution libre de gestion et de suivi des r�sultats scolaires.'
		$pdf->Cell(0,4.5, $bull_formule_bas,0,0,'C');
		//= = = == = = == = = == = = == = = == = = == = = == = = =

		// A VERIFIER: CETTE VARIABLE NE DOIT PAS ETRE UTILE
		// SI LES VALEURS AFFICHEES PROVIENNENT DE L'EXTRACTION HORS DE LA FONCTION
		$total_coef_en_calcul=0;
/*

		// quand on change d'�l�ve on vide les variables suivantes
		$categorie_passe = '';
		$total_moyenne_classe_en_calcul = 0;
		$total_moyenne_min_en_calcul = 0;
		$total_moyenne_max_en_calcul = 0;
		$total_coef_en_calcul = 0;
*/
		// ...
		$hauteur_pris=0;


		//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = ==

		// R�cup�ration de l'identifiant de la classe:
		$classe_id=$tab_ele['id_classe'];

		//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = ==

		if($tab_modele_pdf["affiche_filigrame"][$classe_id]=='1'){
			$pdf->SetFont('Arial','B',50);
			$pdf->SetTextColor(255,192,203);
			//$pdf->TextWithRotation(40,190,$texte_filigrame[$classe_id],45);
			$pdf->TextWithRotation(40,190,$tab_modele_pdf["texte_filigrame"][$classe_id],45);
			$pdf->SetTextColor(0,0,0);
		}

		//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = ==

		// Bloc identification etablissement
		$logo = '../images/'.getSettingValue('logo_etab');
		$format_du_logo = strtolower(str_replace('.','',strstr(getSettingValue('logo_etab'), '.')));

		// Logo
		if($tab_modele_pdf["affiche_logo_etab"][$classe_id]=='1' and file_exists($logo) and getSettingValue('logo_etab') != '' and ($format_du_logo=='jpg' or $format_du_logo=='png'))
		//if($affiche_logo_etab=='1' and file_exists($logo) and getSettingValue('logo_etab') != '' and ($format_du_logo=='jpg' or $format_du_logo=='png'))
		{
			$valeur=redimensionne_image($logo, $tab_modele_pdf["L_max_logo"][$classe_id], $tab_modele_pdf["H_max_logo"][$classe_id]);
			//$valeur=redimensionne_image($logo, $L_max_logo, $H_max_logo);
			$X_logo = 5;
			$Y_logo = 5;
			$L_logo = $valeur[0];
			$H_logo = $valeur[1];
			$X_etab = $X_logo + $L_logo + 1;
			$Y_etab = $Y_logo;

			if ( !isset($tab_modele_pdf["centrage_logo"][$classe_id]) or empty($tab_modele_pdf["centrage_logo"][$classe_id]) ) {
				$tab_modele_pdf["centrage_logo"][$classe_id] = '0';
			}

			if ( $tab_modele_pdf["centrage_logo"][$classe_id] == '1' ) {
				// centrage du logo
				$centre_du_logo = ( $H_logo / 2 );
				$Y_logo = $tab_modele_pdf["Y_centre_logo"][$classe_id] - $centre_du_logo;
			}

			//logo
			$pdf->Image($logo, $X_logo, $Y_logo, $L_logo, $H_logo);
		}

		//$pdf->SetXY(100,5);
		//$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
		//$pdf->Cell(90,7, "\$format_du_logo=$format_du_logo",0,2,'');

		//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = ==

		// Adresse �tablissement
		if ( !isset($X_etab) or empty($X_etab) ) {
			$X_etab = '5';
			$Y_etab = '5';
		}
		$pdf->SetXY($X_etab,$Y_etab);
		$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',14);
		//$pdf->SetFont('Arial','',14);

		//= = = == = = == = = == = = == = = == = = ==
		// AJOUT: boireaus 20081224
		//        Ajout du test $tab_modele_pdf["affiche_nom_etab"][$classe_id] et $tab_modele_pdf["affiche_adresse_etab"][$classe_id]
		//= = = == = = == = = == = = == = = == = = ==
		//$tab_modele_pdf["affiche_nom_etab"][$classe_id]=0;

		if(((isset($tab_modele_pdf["affiche_nom_etab"][$classe_id]))&&($tab_modele_pdf["affiche_nom_etab"][$classe_id]!="0"))||
			(!isset($tab_modele_pdf["affiche_nom_etab"][$classe_id]))) {
			// mettre en gras le nom de l'�tablissement si $nom_etab_gras = 1
			if ( $tab_modele_pdf["nom_etab_gras"][$classe_id] == '1' ) {
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',14);
			}
			$pdf->Cell(90,7, traite_accents_utf8($gepiSchoolName),0,2,'');
		}
		//$pdf->SetFont('Arial','B',14);
		//$pdf->Cell(90,7, traite_accents_utf8($gepiSchoolName),0,2,'');

		//$tab_modele_pdf["affiche_adresse_etab"][$classe_id]=0;
		if(((isset($tab_modele_pdf["affiche_adresse_etab"][$classe_id]))&&($tab_modele_pdf["affiche_adresse_etab"][$classe_id]!="0"))||
			(!isset($tab_modele_pdf["affiche_adresse_etab"][$classe_id]))) {
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);

			if ( $gepiSchoolAdress1 != '' ) {
				$pdf->Cell(90,5, traite_accents_utf8($gepiSchoolAdress1),0,2,'');
			}
			if ( $gepiSchoolAdress2 != '' ) {
				$pdf->Cell(90,5, traite_accents_utf8($gepiSchoolAdress2),0,2,'');
			}

			$pdf->Cell(90,5, traite_accents_utf8($gepiSchoolZipCode." ".$gepiSchoolCity),0,2,'');
		}

		$passealaligne = '0';
		// ent�te t�l�phone
		// emplacement du cadre t�l�com
		$x_telecom = $pdf->GetX();
		$y_telecom = $pdf->GetY();

		if( $tab_modele_pdf["entente_tel"][$classe_id]=='1' ) {
			$grandeur = ''; $text_tel = '';

			if ( $tab_modele_pdf["tel_image"][$classe_id] != '' ) {
				$a = $pdf->GetX();
				$b = $pdf->GetY();
				$ima = '../images/imabulle/'.$tab_modele_pdf["tel_image"][$classe_id].'.jpg';
				$valeurima=redimensionne_image($ima, 15, 15);
				$pdf->Image($ima, $a, $b, $valeurima[0], $valeurima[1]);
				$text_tel = '      '.$gepiSchoolTel;
				$grandeur = $pdf->GetStringWidth($text_tel);
				$grandeur = $grandeur + 2;
			}
			if ( $tab_modele_pdf["tel_texte"][$classe_id] != '' and $tab_modele_pdf["tel_image"][$classe_id] == '' ) {
				$text_tel = $tab_modele_pdf["tel_texte"][$classe_id].''.$gepiSchoolTel;
				$grandeur = $pdf->GetStringWidth($text_tel);
			}

			$pdf->Cell($grandeur,5, $text_tel,0,$passealaligne,'');
		}

		$passealaligne = '2';
		// ent�te fax
		if( $tab_modele_pdf["entente_fax"][$classe_id]=='1' ) {
			$text_fax = '';

			if ( $tab_modele_pdf["fax_image"][$classe_id] != '' ) {
				$a = $pdf->GetX();
				$b = $pdf->GetY();
				$ima = '../images/imabulle/'.$tab_modele_pdf["fax_image"][$classe_id].'.jpg';
				$valeurima=redimensionne_image($ima, 15, 15);
				$pdf->Image($ima, $a, $b, $valeurima[0], $valeurima[1]);
				$text_fax = '      '.$gepiSchoolFax;
			}
			if ( $tab_modele_pdf["fax_texte"][$classe_id] != '' and $tab_modele_pdf["fax_image"][$classe_id] == '' ) {
				$text_fax = $tab_modele_pdf["fax_texte"][$classe_id].''.$gepiSchoolFax;
			}

			//$text_fax='Fax : '.$gepiSchoolFax;
			$pdf->Cell(90,5, $text_fax,0,$passealaligne,'');
		}

		if($tab_modele_pdf["entente_mel"][$classe_id]=='1') {
			$text_mel = '';
			$y_telecom = $y_telecom + 5;
			$pdf->SetXY($x_telecom,$y_telecom);

			$text_mel = $gepiSchoolEmail;

			if ( $tab_modele_pdf["courrier_image"][$classe_id] != '' ) {
				$a = $pdf->GetX();
				$b = $pdf->GetY();
				$ima = '../images/imabulle/'.$tab_modele_pdf["courrier_image"][$classe_id].'.jpg';
				$valeurima=redimensionne_image($ima, 15, 15);
				$pdf->Image($ima, $a, $b, $valeurima[0], $valeurima[1]);
				$text_mel = '      '.$gepiSchoolEmail;
			}
			if ( $tab_modele_pdf["courrier_texte"][$classe_id] != '' and $tab_modele_pdf["courrier_image"][$classe_id] == '' ) {
				$text_mel = $tab_modele_pdf["courrier_texte"][$classe_id].' '.$gepiSchoolEmail;
			}

			//$text_mel='Email : '.$gepiSchoolEmail;
			$pdf->Cell(90,5, $text_mel,0,2,'');
		}

		//$pdf->Cell($pdf->getX(),$pdf->getY(), "DEBUG",0,2,'');
		//echo "DEBUG";

		// = = = == = = == = = = FIN ENTETE BULLETIN = = = == = = == = = == = = == = = == = = ==

		//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = ==

		// A VOIR: REMPLACER LE $i PAR AUTRE CHOSE POUR EVITER LA COLLISION AVEC L'INDICE $i pass� � la fonction
		//$i = $nb_eleve_aff;

		//$id_periode = $periode_classe[$id_classe_selection][$cpt_info_periode];
//		$id_periode = $tab_bull['num_periode'];

		// AJOUT ERIC
		//$classe_id=$id_classe_selection;

		$pdf->SetFont('Arial','B',12);

		// gestion des styles
		$pdf->SetStyle2("b","arial","B",8,"0,0,0");
		$pdf->SetStyle2("i","arial","I",8,"0,0,0");
		$pdf->SetStyle2("u","arial","U",8,"0,0,0");

		// style pour la case appr�ciation g�n�rale
		// identit� du professeur principal

		if ( $tab_modele_pdf["taille_profprincipal_bloc_avis_conseil"][$classe_id] != '' and $tab_modele_pdf["taille_profprincipal_bloc_avis_conseil"][$classe_id] < '15' ) {
			$taille = $tab_modele_pdf["taille_profprincipal_bloc_avis_conseil"][$classe_id];
		} else {
			$taille = '10';
		}
		//$taille = '10';
		$pdf->SetStyle2("bppc","arial","B",$taille,"0,0,0");
		$pdf->SetStyle2("ippc","arial","I",$taille,"0,0,0");

		// bloc affichage de l'adresse des parents
		//if($tab_modele_pdf["active_bloc_adresse_parent"][$classe_id]=='1') {
		if($tab_modele_pdf["active_bloc_adresse_parent"][$classe_id]=='1') {
//echo "DEBUG";
			$pdf->SetXY($tab_modele_pdf["X_parent"][$classe_id],$tab_modele_pdf["Y_parent"][$classe_id]);
			// d�finition des Largeur - hauteur
			if ( $tab_modele_pdf["largeur_bloc_adresse"][$classe_id] != '' and $tab_modele_pdf["largeur_bloc_adresse"][$classe_id] != '0' ) {
				$longeur_cadre_adresse = $tab_modele_pdf["largeur_bloc_adresse"][$classe_id];
			} else {
				$longeur_cadre_adresse = '90';
			}
			if ( $tab_modele_pdf["hauteur_bloc_adresse"][$classe_id] != '' and $tab_modele_pdf["hauteur_bloc_adresse"][$classe_id] != '0' ) {
				$hauteur_cadre_adresse = $tab_modele_pdf["hauteur_bloc_adresse"][$classe_id];
			} else {
				$hauteur_cadre_adresse = '1';
			}
			//= = = == = = == = = == = = == = = == = = ==

			$texte_1_responsable = trim($tab_adr_ligne1[$num_resp_bull]);
//echo " - $texte_1_responsable -";
			$hauteur_caractere=12;
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte_1_responsable);
			$taille_texte = $longeur_cadre_adresse;
			$grandeur_texte='test';
			while($grandeur_texte != 'ok') {
				if($taille_texte < $val){
					$hauteur_caractere = $hauteur_caractere-0.3;
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',$hauteur_caractere);
					$val = $pdf->GetStringWidth($texte_1_responsable);
				} else {
					$grandeur_texte = 'ok';
				}
			}
			$pdf->Cell(90,7, traite_accents_utf8($texte_1_responsable),0,2,'');

			$texte_1_responsable = $tab_adr_ligne2[$num_resp_bull];
			$hauteur_caractere=10;
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte_1_responsable);
			$taille_texte = $longeur_cadre_adresse;
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if($taille_texte<$val){
					$hauteur_caractere = $hauteur_caractere-0.3;
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
					$val = $pdf->GetStringWidth($texte_1_responsable);
				} else {
					$grandeur_texte='ok';
				}
			}
			$pdf->Cell(90,5, traite_accents_utf8($texte_1_responsable),0,2,'');

			$texte_1_responsable = $tab_adr_ligne3[$num_resp_bull];
			$hauteur_caractere=10;
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte_1_responsable);
			$taille_texte = $longeur_cadre_adresse;
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if($taille_texte<$val){
					$hauteur_caractere = $hauteur_caractere-0.3;
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
					$val = $pdf->GetStringWidth($texte_1_responsable);
				} else {
					$grandeur_texte='ok';
				}
			}
			$pdf->Cell(90,5, traite_accents_utf8($texte_1_responsable),0,2,'');

			// Suppression du saut de ligne pour mettre la ligne 3 de l'adresse
			//$pdf->Cell(90,5, '',0,2,'');

			$texte_1_responsable = $tab_adr_ligne4[$num_resp_bull];
			$hauteur_caractere=10;
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte_1_responsable);
			$taille_texte = $longeur_cadre_adresse;
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if($taille_texte<$val){
					$hauteur_caractere = $hauteur_caractere-0.3;
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
					$val = $pdf->GetStringWidth($texte_1_responsable);
				} else {
					$grandeur_texte='ok';
				}
			}
			$pdf->Cell(90,5, traite_accents_utf8($texte_1_responsable),0,2,'');

			//$texte_1_responsable = $cp_parents[$ident_eleve_aff][$responsable_place]." ".$ville_parents[$ident_eleve_aff][$responsable_place];
			$texte_1_responsable = $tab_adr_ligne5[$num_resp_bull];
			$hauteur_caractere=10;
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte_1_responsable);
			$taille_texte = $longeur_cadre_adresse;
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if($taille_texte<$val){
					$hauteur_caractere = $hauteur_caractere-0.3;
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
					$val = $pdf->GetStringWidth($texte_1_responsable);
				} else {
					$grandeur_texte='ok';
				}
			}
			$pdf->Cell(90,5, traite_accents_utf8($texte_1_responsable),0,2,'');


			//= = = == = = == = = == = = == = = == = = == = = =
			//if((strtolower($gepiSchoolPays)!=strtolower($pays_parents[$ident_eleve_aff][$responsable_place]))&&($pays_parents[$ident_eleve_aff][$responsable_place]!="")) {
			if(isset($tab_adr_ligne6[$num_resp_bull])) {
				$texte_1_responsable = $tab_adr_ligne6[$num_resp_bull];
				$hauteur_caractere=10;
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
				$val = $pdf->GetStringWidth($texte_1_responsable);
				$taille_texte = $longeur_cadre_adresse;
				$grandeur_texte='test';
				while($grandeur_texte!='ok') {
					if($taille_texte<$val){
						$hauteur_caractere = $hauteur_caractere-0.3;
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
						$val = $pdf->GetStringWidth($texte_1_responsable);
					} else {
						$grandeur_texte='ok';
					}
				}
				$pdf->Cell(90,5, traite_accents_utf8($texte_1_responsable),0,2,'');
			}
			//= = = == = = == = = == = = == = = == = = == = = =

			$texte_1_responsable = '';
			if ( $tab_modele_pdf["cadre_adresse"][$classe_id] != 0 ) {
				$pdf->Rect($tab_modele_pdf["X_parent"][$classe_id], $tab_modele_pdf["Y_parent"][$classe_id], $longeur_cadre_adresse, $hauteur_cadre_adresse, 'D');
			}
		}

		//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = ==

		// Bloc affichage information sur l'�l�ve
		if($tab_modele_pdf["active_bloc_eleve"][$classe_id]=='1') {
			$pdf->SetXY($tab_modele_pdf["X_eleve"][$classe_id],$tab_modele_pdf["Y_eleve"][$classe_id]);
			// d�finition des Lageur - hauteur
			if ( $tab_modele_pdf["largeur_bloc_eleve"][$classe_id] != '' and $tab_modele_pdf["largeur_bloc_eleve"][$classe_id] != '0' ) {
				$longeur_cadre_eleve = $tab_modele_pdf["largeur_bloc_eleve"][$classe_id];
			} else {
				$longeur_cadre_eleve = $pdf->GetStringWidth($tab_ele['nom']." ".$tab_ele['prenom']);
				$rajout_cadre_eleve = 100-$longeur_cadre_eleve;
				$longeur_cadre_eleve = $longeur_cadre_eleve + $rajout_cadre_eleve;
			}
			if ( $tab_modele_pdf["hauteur_bloc_eleve"][$classe_id] != '' and $tab_modele_pdf["hauteur_bloc_eleve"][$classe_id] != '0' ) {
				$hauteur_cadre_eleve = $tab_modele_pdf["hauteur_bloc_eleve"][$classe_id];
			} else {
				$nb_ligne = 5;
				$hauteur_ligne = 6;
				$hauteur_cadre_eleve = $nb_ligne*$hauteur_ligne;
			}

			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',14);

			if($tab_modele_pdf["cadre_eleve"][$classe_id]!=0) {
				$pdf->Rect($tab_modele_pdf["X_eleve"][$classe_id], $tab_modele_pdf["Y_eleve"][$classe_id], $longeur_cadre_eleve, $hauteur_cadre_eleve, 'D');
			}

			$X_eleve_2 = $tab_modele_pdf["X_eleve"][$classe_id]; $Y_eleve_2=$tab_modele_pdf["Y_eleve"][$classe_id];

			//photo de l'�l�ve
			if ( !isset($tab_modele_pdf["ajout_cadre_blanc_photo"][$classe_id]) or empty($tab_modele_pdf["ajout_cadre_blanc_photo"][$classe_id]) ) {
				$tab_modele_pdf["ajout_cadre_blanc_photo"][$classe_id] = '0';
			}
			if ( $tab_modele_pdf["ajout_cadre_blanc_photo"][$classe_id] == '1' ) {
				$ajouter = '1';
			} else {
				$ajouter = '0';
			}

/*
			$photo[$i]="../photos/eleves/".$tab_ele['elenoet'].".jpg";
			if(!file_exists($photo[$i])) {
				$photo[$i]="../photos/eleves/0".$tab_ele['elenoet'].".jpg";
			}
*/
			$photo[$i]=nom_photo($tab_ele['elenoet']);
			if(!$photo[$i]) {
				$photo[$i]="";
			}

			if($tab_modele_pdf["active_photo"][$classe_id]=='1' and $photo[$i]!='' and file_exists($photo[$i])) {
				$L_photo_max = ($hauteur_cadre_eleve - ( $ajouter * 2 )) * 2.8;
				$H_photo_max = ($hauteur_cadre_eleve - ( $ajouter * 2 )) * 2.8;
				$valeur=redimensionne_image($photo[$i], $L_photo_max, $H_photo_max);
				$X_photo = $tab_modele_pdf["X_eleve"][$classe_id]+ 0.20 + $ajouter;
				$Y_photo = $tab_modele_pdf["Y_eleve"][$classe_id]+ 0.25 + $ajouter;
				$L_photo = $valeur[0]; $H_photo = $valeur[1];
				$X_eleve_2 = $tab_modele_pdf["X_eleve"][$classe_id] + $L_photo + $ajouter + 1;
				$Y_eleve_2 = $Y_photo;
				$pdf->Image($photo[$i], $X_photo, $Y_photo, $L_photo, $H_photo);
				$longeur_cadre_eleve = $longeur_cadre_eleve - ( $valeur[0] + $ajouter );
			}


			$pdf->SetXY($X_eleve_2,$Y_eleve_2);
			$pdf->Cell(90,7, $tab_ele['nom']." ".$tab_ele['prenom'],0,2,'');
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
			if($tab_modele_pdf["affiche_date_naissance"][$classe_id]=='1') {
				if($tab_ele['naissance']!="") {
					$info_naissance="N�";
					if($tab_ele['sexe']=="F") {$info_naissance.="e";}
					$info_naissance.=" le ".$tab_ele['naissance'];
					$pdf->Cell(90,5, traite_accents_utf8($info_naissance),0,2,'');
				}
			}


			$rdbt = '';
			if($tab_modele_pdf["affiche_dp"][$classe_id]=='1') {
				if($tab_modele_pdf["affiche_doublement"][$classe_id]=='1') {
					//if($tab_ele['doublant']!="") {
					if($tab_ele['doublant']=="R") {
						//$rdbt=" ; ".$doublement[$i];
						//$rdbt=" ; redoublant";
						$rdbt="redoublant";
						if($tab_ele['sexe']=="F") {
							$rdbt.="e";
						}
					}
					//if(isset($tab_ele['regime'])) {
					if((isset($tab_ele['regime']))&&($tab_ele['regime']!="")) {
						if($rdbt=="") {
							$pdf->Cell(90,4, traite_accents_utf8(regime($tab_ele['regime'])),0,2,'');
						}
						else {
							$pdf->Cell(90,4, traite_accents_utf8(regime($tab_ele['regime'])."; ".$rdbt),0,2,'');
						}
					} else {
						$pdf->Cell(90,4,traite_accents_utf8($rdbt),0,2,'');
					}
				}
			} else {
				if($tab_modele_pdf["affiche_doublement"][$classe_id]=='1') {
					//if($tab_ele['doublant']!="") {
					if($tab_ele['doublant']=="R") {
						//$pdf->Cell(90,4.5, $doublement[$i],0,2,'');
						//$rdbt=" ; redoublant";
						$rdbt="redoublant";
						if($tab_ele['sexe']=="F") {
							$rdbt.="e";
						}
						$pdf->Cell(90,4.5, traite_accents_utf8($rdbt),0,2,'');
					}
				}
			}

			// affiche le nom court de la classe
			if ( $tab_modele_pdf["affiche_nom_court"][$classe_id] == '1' )
			{
				if($tab_ele['classe']!="")
				{
					// si l'affichage du num�ro INE est activ� alors on ne passe pas
					$passe_a_la_ligne = 0;
					//if ( $tab_modele_pdf["affiche_ine"][$classe_id] != '1' or $tab_modele_pdf["INE_eleve"][$i] == '' )
					if ( $tab_modele_pdf["affiche_ine"][$classe_id] != '1' or $tab_ele['no_gep'] == '' )
					{
						$passe_a_la_ligne = 1;
					}
					$pdf->Cell(45,4.5, traite_accents_utf8(unhtmlentities($tab_ele['classe'])),0, $passe_a_la_ligne,'');
				}
			}

			// affiche l'INE de l'�l�ve
			if ( $tab_modele_pdf["affiche_ine"][$classe_id] == '1' )
			{
				if ( $tab_ele['no_gep'] != '' )
				{
					$pdf->Cell(45,4.5, 'INE: '.$tab_ele['no_gep'], 0, 1,'');
				}
			}

			// Affichage du num�ro d'impression
			$pdf->SetX($X_eleve_2);

			if($tab_modele_pdf["affiche_effectif_classe"][$classe_id]=='1') {
				if($tab_modele_pdf["affiche_numero_impression"][$classe_id]=='1') {
					$pass_ligne = '0';
				} else {
					$pass_ligne = '2';
				}
/*
				if($tab_bull['eff_classe']!="") {
					$pdf->Cell(45,4.5, traite_accents_utf8('Effectif : '.$tab_bull['eff_classe'].' �l�ves'),0,$pass_ligne,'');
				}
*/
			}
			if($tab_modele_pdf["affiche_numero_impression"][$classe_id]=='1') {
				//+++++++++++++++++++
				//+++++++++++++++++++
				// A VOIR... CE $i...
				// Si on n'imprime que certains bulletins, on r�cup�re le num�ro d'ordre (alphab�tique) de l'�l�ve dans la classe.
				//+++++++++++++++++++
				//+++++++++++++++++++
				//$num_ordre = $i;
				$num_ordre = $i+1;
				$pdf->Cell(45,4, 'Bulletin N� '.$num_ordre,0,2,'');
			}

			// Affichage de l'�tablissement d'origine
			// On n'affiche pas l'�tablissement d'origine si c'est le m�me que l'�tablissement actuel: $RneEtablissement
			//if($tab_modele_pdf["affiche_etab_origine"][$classe_id]=='1' and !empty($etablissement_origine[$i]) ) {
			//if($tab_modele_pdf["affiche_etab_origine"][$classe_id]=='1' and isset($tab_ele['etab_id']) and !empty($tab_ele['etab_id']) ) {
			if(($tab_modele_pdf["affiche_etab_origine"][$classe_id]=='1')&&(isset($tab_ele['etab_id']))&&(!empty($tab_ele['etab_id']))&&(strtolower($tab_ele['etab_id'])!=strtolower($RneEtablissement))) {
				$pdf->SetX($X_eleve_2);
				$hauteur_caractere_etaborigine = '10';
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_etaborigine);
				$val = $pdf->GetStringWidth('Etab. Origine : '.$tab_ele['etab_niveau']." ".$tab_ele['etab_nom']." (".$tab_ele['etab_ville'].")");
				$taille_texte = $longeur_cadre_eleve-3;
				$grandeur_texte='test';
				while($grandeur_texte!='ok') {
					if($taille_texte<$val) {
						$hauteur_caractere_etaborigine = $hauteur_caractere_etaborigine-0.3;
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_etaborigine);
						$val = $pdf->GetStringWidth('Etab. Origine : '.$tab_ele['etab_niveau']." ".$tab_ele['etab_nom']." (".$tab_ele['etab_ville'].")");
					} else {
						$grandeur_texte='ok';
					}
				}
				$grandeur_texte='test';
				$pdf->Cell(90,4, traite_accents_utf8('Etab. Origine : '.$tab_ele['etab_niveau']." ".$tab_ele['etab_nom']." (".$tab_ele['etab_ville'].")"),0,2);
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
			}
		} // fin du bloc affichage information sur l'�l�ves

		//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = ==

		// Bloc affichage datation du bulletin:
		// Classe, p�riode,...
		if($tab_modele_pdf["active_bloc_datation"][$classe_id]=='1') {
			$pdf->SetXY($tab_modele_pdf["X_datation_bul"][$classe_id], $tab_modele_pdf["Y_datation_bul"][$classe_id]);

			// d�finition des Largeur - hauteur
			if ( $tab_modele_pdf["largeur_bloc_datation"][$classe_id] != '' and $tab_modele_pdf["largeur_bloc_datation"][$classe_id] != '0' ) {
				$longeur_cadre_datation_bul = $tab_modele_pdf["largeur_bloc_datation"][$classe_id];
			} else {
				$longeur_cadre_datation_bul = '95';
			}
			if ( $tab_modele_pdf["hauteur_bloc_datation"][$classe_id] != '' and $tab_modele_pdf["hauteur_bloc_datation"][$classe_id] != '0' ) {
				$hauteur_cadre_datation_bul = $tab_modele_pdf["hauteur_bloc_datation"][$classe_id];
			} else {
				$nb_ligne_datation_bul = 3;
				$hauteur_ligne_datation_bul = 6;
				$hauteur_cadre_datation_bul = $nb_ligne_datation_bul*$hauteur_ligne_datation_bul;
			}

			if($tab_modele_pdf["cadre_datation_bul"][$classe_id]!=0) {
				$pdf->Rect($tab_modele_pdf["X_datation_bul"][$classe_id], $tab_modele_pdf["Y_datation_bul"][$classe_id], $longeur_cadre_datation_bul, $hauteur_cadre_datation_bul, 'D');
			}
			$taille_texte = '14'; $type_texte = 'B';
			if ( $tab_modele_pdf["taille_texte_classe"][$classe_id] != '' and $tab_modele_pdf["taille_texte_classe"][$classe_id] != '0' ) {
				$taille_texte = $tab_modele_pdf["taille_texte_classe"][$classe_id];
			} else {
				$taille_texte = '14';
			}
			if ( $tab_modele_pdf["type_texte_classe"][$classe_id] != '' ) {
				if ( $tab_modele_pdf["type_texte_classe"][$classe_id] == 'N' ) {
					$type_texte = '';
				} else {
					$type_texte = $tab_modele_pdf["type_texte_classe"][$classe_id];
				}
			} else {
				$type_texte = 'B';
			}
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id], $type_texte, $taille_texte);
			//$pdf->Cell(90,7, traite_accents_utf8("Classe de ".unhtmlentities($tab_bull['classe_nom_complet'])),0,2,'C');
			$pdf->Cell(90,7, traite_accents_utf8("Classe de ".unhtmlentities($tab_ele['classe_nom_complet'])),0,2,'C');
			$taille_texte = '12'; $type_texte = '';
			if ( $tab_modele_pdf["taille_texte_annee"][$classe_id] != '' and $tab_modele_pdf["taille_texte_annee"][$classe_id] != '0') {
				$taille_texte = $tab_modele_pdf["taille_texte_annee"][$classe_id];
			} else {
				$taille_texte = '12';
			}

			if ( $tab_modele_pdf["type_texte_annee"][$classe_id] != '' ) {
				if ( $tab_modele_pdf["type_texte_annee"][$classe_id] == 'N' ) {
					$type_texte = '';
				}
				else {
					$type_texte = $tab_modele_pdf["type_texte_annee"][$classe_id];
				}
			}
			else {
				$type_texte = '';
			}
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id], $type_texte, $taille_texte);
			$annee_scolaire = $gepiYear;
			$pdf->Cell(90,5, traite_accents_utf8("Ann�e scolaire ".$annee_scolaire),0,2,'C');
			$taille_texte = '10'; $type_texte = '';
			if ( $tab_modele_pdf["taille_texte_periode"][$classe_id] != '' and $tab_modele_pdf["taille_texte_periode"][$classe_id] != '0' ) {
				$taille_texte = $tab_modele_pdf["taille_texte_periode"][$classe_id];
			}
			else {
				$taille_texte = '10';
			}
			if ( $tab_modele_pdf["type_texte_periode"][$classe_id] != '' ) {
				if ( $tab_modele_pdf["type_texte_periode"][$classe_id] == 'N' ) {
					$type_texte = '';
				}
				else {
					$type_texte = $tab_modele_pdf["type_texte_periode"][$classe_id];
				}
			}
			else {
				$type_texte = '';
			}
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id], $type_texte, $taille_texte);
			//$pdf->Cell(90,5, traite_accents_utf8("Bulletin du ".unhtmlentities($tab_bull['nom_periode'])),0,2,'C');
			$pdf->Cell(90,5, traite_accents_utf8("Examen blanc : ".$tab_ele['intitule_exam']),0,2,'C');
			$taille_texte = '8';
			$type_texte = '';

			if ( $tab_modele_pdf["affiche_date_edition"][$classe_id] == '1' ) {
				if ( $tab_modele_pdf["taille_texte_date_edition"][$classe_id] != '' and $tab_modele_pdf["taille_texte_date_edition"][$classe_id] != '0' ) {
					$taille_texte = $tab_modele_pdf["taille_texte_date_edition"][$classe_id];
				}
				else {
					$taille_texte = '8';
				}
				if ( $tab_modele_pdf["type_texte_date_datation"][$classe_id] != '' ) {
					if ( $tab_modele_pdf["type_texte_date_datation"][$classe_id] == 'N' ) {
						$type_texte = '';
					}
					else {
						$type_texte = $tab_modele_pdf["type_texte_date_datation"][$classe_id];
					}
				}
				else {
					$type_texte = '';
				}
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id], $type_texte, $taille_texte);
				$pdf->Cell(95,7, traite_accents_utf8($date_bulletin),0,2,'R');
			}

			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
		}


		$nb_matiere=count($tab_ele['matieres']);

		if($tab_modele_pdf["active_bloc_note_appreciation"][$classe_id]=='1' and $nb_matiere!='0') {
			$pdf->Rect($tab_modele_pdf["X_note_app"][$classe_id], $tab_modele_pdf["Y_note_app"][$classe_id], $tab_modele_pdf["longeur_note_app"][$classe_id], $tab_modele_pdf["hauteur_note_app"][$classe_id], 'D');
			//ent�te du tableau des notes et app
			$nb_entete_moyenne = $tab_modele_pdf["active_moyenne_eleve"][$classe_id]+$tab_modele_pdf["active_moyenne_classe"][$classe_id]+$tab_modele_pdf["active_moyenne_min"][$classe_id]+$tab_modele_pdf["active_moyenne_max"][$classe_id]; //min max classe eleve
			$hauteur_entete = 8;
			$hauteur_entete_pardeux = $hauteur_entete/2;
			$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id], $tab_modele_pdf["Y_note_app"][$classe_id]);
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
			$pdf->Cell($tab_modele_pdf["largeur_matiere"][$classe_id], $hauteur_entete, traite_accents_utf8($tab_modele_pdf["titre_entete_matiere"][$classe_id]),1,0,'C');
			$largeur_utilise = $tab_modele_pdf["largeur_matiere"][$classe_id];

			// coefficient mati�re
			if($tab_modele_pdf["active_coef_moyenne"][$classe_id]=='1') {
				$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]);
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
				$pdf->Cell($tab_modele_pdf["largeur_coef_moyenne"][$classe_id], $hauteur_entete, traite_accents_utf8($tab_modele_pdf["titre_entete_coef"][$classe_id]),'LRB',0,'C');
				$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_coef_moyenne"][$classe_id];
			}
/*
			// nombre de notes
			// 20081118
			//if($tab_modele_pdf["active_nombre_note_case"][$classe_id]=='1') {
			if(($tab_modele_pdf["active_nombre_note_case"][$classe_id]=='1')&&($tab_modele_pdf["active_nombre_note"][$classe_id]!='1')) {
				$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]);
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
				$pdf->Cell($tab_modele_pdf["largeur_nombre_note"][$classe_id], $hauteur_entete, traite_accents_utf8($tab_modele_pdf["titre_entete_nbnote"][$classe_id]),'LRB',0,'C');
				$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_nombre_note"][$classe_id];
			}
*/
			// eleve | min | classe | max | rang | niveau | appreciation |
			if ( $tab_modele_pdf["ordre_entete_model_bulletin"][$classe_id] == '1' ) {
				$ordre_moyenne[0] = 'eleve';
				$ordre_moyenne[1] = 'min';
				$ordre_moyenne[2] = 'classe';
				$ordre_moyenne[3] = 'max';
				$ordre_moyenne[4] = 'rang';
				$ordre_moyenne[5] = 'niveau';
				$ordre_moyenne[6] = 'appreciation';
			}

			// min | classe | max | eleve | niveau | rang | appreciation |
			if ( $tab_modele_pdf["ordre_entete_model_bulletin"][$classe_id] == '2' ) {
				$ordre_moyenne[0] = 'min';
				$ordre_moyenne[1] = 'classe';
				$ordre_moyenne[2] = 'max';
				$ordre_moyenne[3] = 'eleve';
				$ordre_moyenne[4] = 'niveau';
				$ordre_moyenne[5] = 'rang';
				$ordre_moyenne[6] = 'appreciation';
			}

			// eleve | niveau | rang | appreciation | min | classe | max
			if ( $tab_modele_pdf["ordre_entete_model_bulletin"][$classe_id] == '3' ) {
				$ordre_moyenne[0] = 'eleve';
				$ordre_moyenne[1] = 'niveau';
				$ordre_moyenne[2] = 'rang';
				$ordre_moyenne[3] = 'appreciation';
				$ordre_moyenne[4] = 'min';
				$ordre_moyenne[5] = 'classe';
				$ordre_moyenne[6] = 'max';
			}

			// eleve | classe | min | max | rang | niveau | appreciation |
			if ( $tab_modele_pdf["ordre_entete_model_bulletin"][$classe_id] == '4' ) {
				$ordre_moyenne[0] = 'eleve';
				$ordre_moyenne[1] = 'classe';
				$ordre_moyenne[2] = 'min';
				$ordre_moyenne[3] = 'max';
				$ordre_moyenne[4] = 'rang';
				$ordre_moyenne[5] = 'niveau';
				$ordre_moyenne[6] = 'appreciation';
			}

			// eleve | min | classe | max | niveau | rang | appreciation |
			if ( $tab_modele_pdf["ordre_entete_model_bulletin"][$classe_id] == '5' ) {
				$ordre_moyenne[0] = 'eleve';
				$ordre_moyenne[1] = 'min';
				$ordre_moyenne[2] = 'classe';
				$ordre_moyenne[3] = 'max';
				$ordre_moyenne[4] = 'niveau';
				$ordre_moyenne[5] = 'rang';
				$ordre_moyenne[6] = 'appreciation';
			}

			// min | classe | max | eleve | rang | niveau | appreciation |
			//if ( $ordre_entete_model_bulletin[$classe_id] == '6' ) {
			if ( $tab_modele_pdf["ordre_entete_model_bulletin"][$classe_id] == '6' ) {
				$ordre_moyenne[0] = 'min';
				$ordre_moyenne[1] = 'classe';
				$ordre_moyenne[2] = 'max';
				$ordre_moyenne[3] = 'eleve';
				$ordre_moyenne[4] = 'rang';
				$ordre_moyenne[5] = 'niveau';
				$ordre_moyenne[6] = 'appreciation';
			}


			$cpt_ordre = 0;
			$chapeau_moyenne = 'non';
			while ( !empty($ordre_moyenne[$cpt_ordre]) ) {

				// Je ne saisis pas pourquoi cette variable est initialis�e � ce niveau???
				//$categorie_passe_count = 0;

				// le chapeau des moyennes
				$ajout_espace_au_dessus = 4;
				if ( $tab_modele_pdf["entete_model_bulletin"][$classe_id] == '1' and $nb_entete_moyenne > 1 and ( $ordre_moyenne[$cpt_ordre] == 'classe' or $ordre_moyenne[$cpt_ordre] == 'min' or $ordre_moyenne[$cpt_ordre] == 'max' or $ordre_moyenne[$cpt_ordre] == 'eleve' ) and $chapeau_moyenne == 'non' and $tab_modele_pdf["ordre_entete_model_bulletin"][$classe_id] != '3' )
				{
					$largeur_moyenne = $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id] * $nb_entete_moyenne;
					$text_entete_moyenne = 'Moyenne';
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]);
					$pdf->Cell($largeur_moyenne, $hauteur_entete_pardeux, traite_accents_utf8($text_entete_moyenne),1,0,'C');
					$chapeau_moyenne = 'oui';
				}

				if ( ($tab_modele_pdf["entete_model_bulletin"][$classe_id] == '2' and $nb_entete_moyenne > 1 and ( $ordre_moyenne[$cpt_ordre] == 'classe' or $ordre_moyenne[$cpt_ordre] == 'min' or $ordre_moyenne[$cpt_ordre] == 'max' ) and $chapeau_moyenne == 'non' ) or ( $tab_modele_pdf["entete_model_bulletin"][$classe_id] == '1' and $tab_modele_pdf["ordre_entete_model_bulletin"][$classe_id] == '3' and $chapeau_moyenne == 'non' and ( $ordre_moyenne[$cpt_ordre] == 'classe' or $ordre_moyenne[$cpt_ordre] == 'min' or $ordre_moyenne[$cpt_ordre] == 'max' )  ) )
				{
					$largeur_moyenne = $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id] * ( $nb_entete_moyenne - 1 );
					$text_entete_moyenne = 'Pour la classe';
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]);
					$hauteur_caractere=10;
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
					$val = $pdf->GetStringWidth($text_entete_moyenne);
					$taille_texte = $largeur_moyenne;
					$grandeur_texte='test';
					while($grandeur_texte!='ok') {
						if($taille_texte<$val)
						{
							$hauteur_caractere = $hauteur_caractere-0.3;
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
							$val = $pdf->GetStringWidth($text_entete_moyenne);
						}
						else {
							$grandeur_texte='ok';
						}
					}
					$pdf->Cell($largeur_moyenne, $hauteur_entete_pardeux, traite_accents_utf8($text_entete_moyenne),1,0,'C');
					$chapeau_moyenne = 'oui';
				}

				//eleve
				if($tab_modele_pdf["active_moyenne_eleve"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'eleve' ) {
					$ajout_espace_au_dessus = 4;
					$hauteur_de_la_cellule = $hauteur_entete_pardeux;
					if ( $tab_modele_pdf["entete_model_bulletin"][$classe_id] == '2' and $tab_modele_pdf["active_moyenne_eleve"][$classe_id] == '1' and $nb_entete_moyenne > 1 )
					{
						$hauteur_de_la_cellule = $hauteur_entete;
						$ajout_espace_au_dessus = 0;
					}
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]+$ajout_espace_au_dessus);
					$pdf->SetFillColor($tab_modele_pdf["couleur_reperage_eleve1"][$classe_id], $tab_modele_pdf["couleur_reperage_eleve2"][$classe_id], $tab_modele_pdf["couleur_reperage_eleve3"][$classe_id]);
					$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $hauteur_de_la_cellule, traite_accents_utf8("El�ve"),1,0,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
					$pdf->SetFillColor(0, 0, 0);
					$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
				}

				//classe
				if($tab_modele_pdf["active_moyenne_classe"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'classe' ) {
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]+4);
					$hauteur_caractere = '8.5';

					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
					$text_moy_classe = 'Classe';
					if ( $tab_modele_pdf["entete_model_bulletin"][$classe_id] == '2' ) {
						$text_moy_classe = 'Moy.';
					}
					$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $hauteur_entete_pardeux, traite_accents_utf8($text_moy_classe),1,0,'C');
					$X_moyenne_classe = $tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise;
					$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
				}
				//min
				if($tab_modele_pdf["active_moyenne_min"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'min' ) {
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]+4);
					$hauteur_caractere = '8.5';
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
					$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $hauteur_entete_pardeux, "Min.",1,0,'C');
					$X_min_classe = $tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise;
					$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
				}
				//max
				if($tab_modele_pdf["active_moyenne_max"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'max' ) {
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]+4);
					$hauteur_caractere = '8.5';
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
					$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $hauteur_entete_pardeux, "Max.",1,0,'C');
					$X_max_classe = $tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise;
					$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
				}

				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);

				// rang de l'�l�ve
				if( $tab_modele_pdf["active_rang"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'rang' ) {
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]);
					$pdf->Cell($tab_modele_pdf["largeur_rang"][$classe_id], $hauteur_entete, traite_accents_utf8($tab_modele_pdf["titre_entete_rang"][$classe_id]),'LRB',0,'C');
					//$pdf->Cell($tab_modele_pdf["largeur_rang"][$classe_id], $hauteur_entete, $tab_modele_pdf["titre_entete_rang"][$classe_id],'LRB',0,'C');
					$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_rang"][$classe_id];
				}

				// graphique de niveau
				if( $tab_modele_pdf["active_graphique_niveau"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'niveau' ) {
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]);
					$hauteur_caractere = '10';
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
					$pdf->Cell($tab_modele_pdf["largeur_niveau"][$classe_id], $hauteur_entete_pardeux, "Niveau",'LR',0,'C');
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]+4);
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
					$pdf->Cell($tab_modele_pdf["largeur_niveau"][$classe_id], $hauteur_entete_pardeux, "ABC+C-DE",'LRB',0,'C');
					$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_niveau"][$classe_id];
				}

				//appreciation
				$hauteur_caractere = '10';
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere);
				if($tab_modele_pdf["active_appreciation"][$classe_id]=='1' and $ordre_moyenne[$cpt_ordre] == 'appreciation' ) {
					$pdf->SetXY($tab_modele_pdf["X_note_app"][$classe_id]+$largeur_utilise, $tab_modele_pdf["Y_note_app"][$classe_id]);
					if ( !empty($ordre_moyenne[$cpt_ordre+1]) ) {
						$cpt_ordre_sous = $cpt_ordre + 1;
						$largeur_appret = 0;
						while ( !empty($ordre_moyenne[$cpt_ordre_sous]) ) {
							if ( $ordre_moyenne[$cpt_ordre_sous] == 'eleve' ) { $largeur_appret = $largeur_appret + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id]; }
							if ( $ordre_moyenne[$cpt_ordre_sous] == 'rang' ) { $largeur_appret = $largeur_appret + $tab_modele_pdf["largeur_rang"][$classe_id]; }
							if ( $ordre_moyenne[$cpt_ordre_sous] == 'niveau' ) { $largeur_appret = $largeur_appret + $tab_modele_pdf["largeur_niveau"][$classe_id]; }
							if ( $ordre_moyenne[$cpt_ordre_sous] == 'min' ) { $largeur_appret = $largeur_appret + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id]; }
							if ( $ordre_moyenne[$cpt_ordre_sous] == 'classe' ) { $largeur_appret = $largeur_appret + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id]; }
							if ( $ordre_moyenne[$cpt_ordre_sous] == 'max' ) { $largeur_appret = $largeur_appret + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id]; }
							$cpt_ordre_sous = $cpt_ordre_sous + 1;
						}
						$largeur_appreciation = $tab_modele_pdf["longeur_note_app"][$classe_id] - $largeur_utilise - $largeur_appret;
					} else {
						$largeur_appreciation = $tab_modele_pdf["longeur_note_app"][$classe_id]-$largeur_utilise;
					}
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);

					//$titre_entete_appreciation=$bull_intitule_app;
					$titre_entete_appreciation=$tab_modele_pdf['titre_entete_appreciation'][$classe_id];

					$pdf->Cell($largeur_appreciation, $hauteur_entete, traite_accents_utf8($titre_entete_appreciation),'LRB',0,'C');
					$largeur_utilise = $largeur_utilise + $largeur_appreciation;
				}
				$cpt_ordre = $cpt_ordre + 1;
			}
			$largeur_utilise = 0;
			// fin de boucle d'ordre

			//+++++++++++++++++++++++++++++++++++++++++++++

			$X_bloc_matiere=$tab_modele_pdf["X_note_app"][$classe_id]; $Y_bloc_matiere=$tab_modele_pdf["Y_note_app"][$classe_id]+$hauteur_entete;
			$longeur_bloc_matiere=$tab_modele_pdf["longeur_note_app"][$classe_id];
			// calcul de la hauteur totale que peut prendre le cadre mati�re dans sa globalit�
			if ( $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $tab_modele_pdf["active_moyenne_general"][$classe_id] == '1' )
			{
				// si les moyennes et la moyenne g�n�ral sont activ� alors on les ajoute � ceux qui vaudras soustraire au cadre global matiere
				$hauteur_toute_entete = $hauteur_entete + $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id];
			}
			else {
				$hauteur_toute_entete = $hauteur_entete;
			}

			$hauteur_bloc_matiere=$tab_modele_pdf["hauteur_note_app"][$classe_id]-$hauteur_toute_entete;
			$X_note_moy_app = $tab_modele_pdf["X_note_app"][$classe_id];
			$Y_note_moy_app = $tab_modele_pdf["Y_note_app"][$classe_id]+$tab_modele_pdf["hauteur_note_app"][$classe_id]-$hauteur_entete;

			if($tab_modele_pdf["active_entete_regroupement"][$classe_id]=='1') {
				$espace_entre_matier = ($hauteur_bloc_matiere-($nb_categories_select*5))/$nb_matiere;
			}
			else {
				$espace_entre_matier = $hauteur_bloc_matiere/$nb_matiere;
			}
/*
			fich_debug_bull("\$hauteur_bloc_matiere=$hauteur_bloc_matiere\n");
			fich_debug_bull("\$nb_matiere=$nb_matiere\n");
			fich_debug_bull("\$espace_entre_matier=$espace_entre_matier\n");
*/
			/*
			//++++++++++++++++
			// Pour debug:
			$pdf->SetXY(100, 30);
			$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
			$pdf->Cell($tab_modele_pdf["largeur_matiere"][$classe_id], 8, "espace_entre_matier=$espace_entre_matier",1,0,'C');
			//++++++++++++++++
			*/

			$pdf->SetXY($X_bloc_matiere, $Y_bloc_matiere);
			$Y_decal = $Y_bloc_matiere;

			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			// Compteur du nombre de mati�res dans la cat�gorie
			//$categorie_passe_count=0;

			//for($m=0; $m<$nb_matiere; $m++)
			//for($m=0;$m<count($tab_ele['matieres']); $m++) {
			$m=0;
			//foreach($tab_ele['matieres'] as $key => $current_matiere) {
			foreach($tab_ele['matieres'] as $current_matiere => $tab_current_matiere) {
				$pdf->SetXY($X_bloc_matiere, $Y_decal);

/*
				fich_debug_bull("\n");
				fich_debug_bull("Cat�gorie pr�c�dente: \$categorie_passe=$categorie_passe\n");
				//fich_debug_bull("Cat�gorie courante :  \$tab_bull['nom_cat_complet'][$m]=".$tab_bull['nom_cat_complet'][$m]."\n");
				if(isset($tab_bull['nom_cat_complet'][$m])) {
                  fich_debug_bull("Cat�gorie courante :  \$tab_bull['nom_cat_complet'][$m]=".$tab_bull['nom_cat_complet'][$m]."\n");
                }
                else {
                  fich_debug_bull("Cat�gorie courante :  \$tab_bull['nom_cat_complet'][$m] non affect�e.\n");
                }
				fich_debug_bull("\$tab_bull['groupe'][$m]['matiere']['matiere']=".$tab_bull['groupe'][$m]['matiere']['matiere']."\n");
				fich_debug_bull("\$X_bloc_matiere=$X_bloc_matiere\n");
				fich_debug_bull("\$Y_decal=$Y_decal\n");
				// si on affiche les cat�gories
				if($tab_modele_pdf["active_entete_regroupement"][$classe_id]=='1') {
					//si on affiche les moyennes des cat�gories
					//if($matiere[$ident_eleve_aff][$id_periode][$m]['categorie']!=$categorie_passe)
					//if($tab_bull['cat_id'][$m]!=$categorie_passe)
					if($tab_bull['nom_cat_complet'][$m]!=$categorie_passe)
					{
						$hauteur_caractere_catego = '10';
						if ( $tab_modele_pdf["taille_texte_categorie"][$classe_id] != '' and $tab_modele_pdf["taille_texte_categorie"][$classe_id] != '0' ) {
							$hauteur_caractere_catego = $tab_modele_pdf["taille_texte_categorie"][$classe_id];
						}
						else {
							$hauteur_caractere_catego = '10';
						}
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_catego);
						$tt_catego = unhtmlentities($tab_bull['nom_cat_complet'][$m]);
						$val = $pdf->GetStringWidth($tt_catego);
						$taille_texte = ($tab_modele_pdf["largeur_matiere"][$classe_id]);
						$grandeur_texte='test';
						while($grandeur_texte!='ok') {
							if($taille_texte<$val)
							{
								$hauteur_caractere_catego = $hauteur_caractere_catego-0.3;
								$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_catego);
								$val = $pdf->GetStringWidth($tt_catego);
							}
							else {
								$grandeur_texte='ok';
							}
						}
						$grandeur_texte='test';
						$pdf->SetFillColor($tab_modele_pdf["couleur_categorie_entete1"][$classe_id], $tab_modele_pdf["couleur_categorie_entete2"][$classe_id], $tab_modele_pdf["couleur_categorie_entete3"][$classe_id]);

						fich_debug_bull("On �crit $tt_catego � \$Y_decal=$Y_decal\n");

						$pdf->Cell($tab_modele_pdf["largeur_matiere"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], traite_accents_utf8(unhtmlentities($tt_catego)),'TLB',0,'L',$tab_modele_pdf["couleur_categorie_entete"][$classe_id]);
						$largeur_utilise = $tab_modele_pdf["largeur_matiere"][$classe_id];

						// coefficient mati�re
						if($tab_modele_pdf["active_coef_moyenne"][$classe_id]=='1') {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal);
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
							$pdf->Cell($tab_modele_pdf["largeur_coef_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C',$tab_modele_pdf["couleur_categorie_entete"][$classe_id]);
							$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_coef_moyenne"][$classe_id];
						}

						// nombre de note
						// 20081118
						//if($tab_modele_pdf["active_nombre_note_case"][$classe_id]=='1') {
						if(($tab_modele_pdf["active_nombre_note_case"][$classe_id]=='1')&&($tab_modele_pdf["active_nombre_note"][$classe_id]!='1')) {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal);
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
							$pdf->Cell($tab_modele_pdf["largeur_nombre_note"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C',$tab_modele_pdf["couleur_categorie_entete"][$classe_id]);
							$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_nombre_note"][$classe_id];
						}
						$pdf->SetFillColor(0, 0, 0);

						// les moyennes eleve, classe, min, max par cat�gorie
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal);

						$cpt_ordre = 0;
						$chapeau_moyenne = 'non';
						while ( !empty($ordre_moyenne[$cpt_ordre]) ) {
							// Moyenne de l'�l�ve dans la cat�gorie
							if($tab_modele_pdf["active_moyenne_eleve"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'eleve' ) {
								$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal);
								if($tab_modele_pdf["active_moyenne_regroupement"][$classe_id]=='1') {
									//$categorie_passage=$matiere[$ident_eleve_aff][$id_periode][$m]['categorie'];
									$categorie_passage=$tab_bull['nom_cat_complet'][$m];
									//if($matiere[$ident_eleve_aff][$id_periode][$m]['affiche_moyenne']=='1')
									if(isset($tab_bull['moy_cat_eleve'][$i][$tab_bull['cat_id'][$m]]))
									{
										// On va afficher la moyenne de l'�l�ve pour la cat�gorie
										if (($tab_bull['moy_cat_eleve'][$i][$tab_bull['cat_id'][$m]]=="")||($tab_bull['moy_cat_eleve'][$i][$tab_bull['cat_id'][$m]]=="-")) {
											$valeur = "-";
										} else {
											//$calcule_moyenne_eleve_categorie[$categorie_passage]=$matiere[$ident_eleve_aff][$id_periode][$categorie_passage]['moy_eleve']/$matiere[$ident_eleve_aff][$id_periode][$categorie_passage]['coef_tt_catego'];
											$valeur = present_nombre(my_ereg_replace(",",".",$tab_bull['moy_cat_eleve'][$i][$tab_bull['cat_id'][$m]]), $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
											//$valeur =$tab_bull['moy_cat_eleve'][$i][$tab_bull['cat_id'][$m]];
										}
										$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',8);
										$pdf->SetFillColor($tab_modele_pdf["couleur_reperage_eleve1"][$classe_id], $tab_modele_pdf["couleur_reperage_eleve2"][$classe_id], $tab_modele_pdf["couleur_reperage_eleve3"][$classe_id]);
										$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id],$valeur,1,0,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
										$pdf->SetFillColor(0, 0, 0);
										$valeur = "";
									} else {
										$pdf->SetFillColor(255, 255, 255);
										$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','TL',0,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
									}
								} else {
									$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C');
								}

								$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
							}

							// Moyenne de la classe dans la cat�gorie
							if($tab_modele_pdf["active_moyenne_classe"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'classe' ) {
								$pdf->SetXY($X_moyenne_classe, $Y_decal);
								if($tab_modele_pdf["active_moyenne_regroupement"][$classe_id]=='1') {
									//$categorie_passage=$matiere[$ident_eleve_aff][$id_periode][$m]['categorie'];
									$categorie_passage=$tab_bull['nom_cat_complet'][$m];
									//if($matiere[$ident_eleve_aff][$id_periode][$m]['affiche_moyenne']=='1')
									if(isset($tab_bull['moy_cat_classe'][$i][$tab_bull['cat_id'][$m]]))
									{
										// On va afficher la moyenne de la classe pour la cat�gorie
										//= = = == = = == = = == = = == = = == = = == = = == = = == = = == = = == = = == = = =
										$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
										//$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], present_nombre($tab_bull['moy_cat_classe'][$i][$tab_bull['cat_id'][$m]], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]),'TLR',0,'C');
										//$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], $tab_bull['moy_cat_classe'][$i][$tab_bull['cat_id'][$m]], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id],'TLR',0,'C');

										// Patch mal foutu parce que present_nombre() attend un nombre au format 16.5 et que la moyenne de cat�gorie est d�j� format�e avec virgule 16,5... du coup on perdait la partie d�cimale.
										//$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], present_nombre(my_ereg_replace(",",".",$tab_bull['moy_cat_classe'][$i][$tab_bull['cat_id'][$m]]), $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]),'TLR',0,'C');

										if (($tab_bull['moy_cat_classe'][$i][$tab_bull['cat_id'][$m]]=="")||($tab_bull['moy_cat_classe'][$i][$tab_bull['cat_id'][$m]]=="-")) {
											$valeur = "-";
										} else {
											$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], present_nombre($tab_bull['moy_cat_classe'][$i][$tab_bull['cat_id'][$m]], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]),'TLR',0,'C');
										}
									} else {
										$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C');
									}
								} else {
									$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C');
								}
								$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
							}

							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
							// Moyenne minimale de la classe dans la cat�gorie
							if($tab_modele_pdf["active_moyenne_min"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'min' ) {
								$pdf->SetXY($X_min_classe, $Y_decal);
								if($tab_modele_pdf["active_moyenne_regroupement"][$classe_id]=='1') {
									$categorie_passage=$tab_bull['nom_cat_complet'][$m];

									//if($matiere[$ident_eleve_aff][$id_periode][$m]['affiche_moyenne']=='1')
									if(isset($tab_bull['moy_cat_min'][$i][$tab_bull['cat_id'][$m]]))
									{
										// On va afficher la moyenne min de la classe pour la cat�gorie

										//$calcule_moyenne_classe_categorie[$categorie_passage]=my_ereg_replace(",",".",$tab_bull['moy_cat_min'][$i][$tab_bull['cat_id'][$m]]);

										$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
										if (($tab_bull['moy_cat_min'][$i][$tab_bull['cat_id'][$m]]=="")||($tab_bull['moy_cat_min'][$i][$tab_bull['cat_id'][$m]]=="-")) {
											$valeur = "-";
										} else {
											$calcule_moyenne_classe_categorie[$categorie_passage]=my_ereg_replace(",",".",$tab_bull['moy_cat_min'][$i][$tab_bull['cat_id'][$m]]);

											$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], present_nombre($calcule_moyenne_classe_categorie[$categorie_passage], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]),'TLR',0,'C');
										}
									} else {
										$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C');
									}
								} else {
									$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C');
								}
								$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
							}

							// Moyenne maximale de la classe dans la cat�gorie
							if($tab_modele_pdf["active_moyenne_max"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'max' ) {
								$pdf->SetXY($X_max_classe, $Y_decal);

								if($tab_modele_pdf["active_moyenne_regroupement"][$classe_id]=='1') {
									$categorie_passage=$tab_bull['nom_cat_complet'][$m];

									//if($matiere[$ident_eleve_aff][$id_periode][$m]['affiche_moyenne']=='1')
									if(isset($tab_bull['moy_cat_max'][$i][$tab_bull['cat_id'][$m]]))
									{
										// On va afficher la moyenne max de la classe pour la cat�gorie

										//$calcule_moyenne_classe_categorie[$categorie_passage]=my_ereg_replace(",",".",$tab_bull['moy_cat_max'][$i][$tab_bull['cat_id'][$m]]);

										//$pdf->SetFont($caractere_utilse[$classe_id],'',8);
										$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
										//$pdf->Cell($largeur_d_une_moyenne[$classe_id], $hauteur_info_categorie[$classe_id], present_nombre($calcule_moyenne_classe_categorie[$categorie_passage], $arrondie_choix[$classe_id], $nb_chiffre_virgule[$classe_id], $chiffre_avec_zero[$classe_id]),'TLR',0,'C');
										if (($tab_bull['moy_cat_max'][$i][$tab_bull['cat_id'][$m]]=="")||($tab_bull['moy_cat_max'][$i][$tab_bull['cat_id'][$m]]=="-")) {
											$valeur = "-";
										} else {
											$calcule_moyenne_classe_categorie[$categorie_passage]=my_ereg_replace(",",".",$tab_bull['moy_cat_max'][$i][$tab_bull['cat_id'][$m]]);

											$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], present_nombre($calcule_moyenne_classe_categorie[$categorie_passage], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]),'TLR',0,'C');
										}
									} else {

										$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C');
									}
								} else {
									$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C');
								}
								$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
							}
							$cpt_ordre = $cpt_ordre + 1;
						}
						$largeur_utilise = 0;
						// fin de boucle d'ordre

						// Rang de l'�l�ve
						if($tab_modele_pdf["active_rang"][$classe_id]=='1') {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal);
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
							$pdf->Cell($tab_modele_pdf["largeur_rang"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C');
							$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_rang"][$classe_id];
						}
						// Graphique de niveau
						if($tab_modele_pdf["active_graphique_niveau"][$classe_id]=='1') {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal);
							$pdf->Cell($tab_modele_pdf["largeur_niveau"][$classe_id], $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','T',0,'C');
							$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_niveau"][$classe_id];
						}
						// Appreciation
						if($tab_modele_pdf["active_appreciation"][$classe_id]=='1') {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal);
							$pdf->Cell($largeur_appreciation, $tab_modele_pdf["hauteur_info_categorie"][$classe_id], '','TB',0,'C');
							$largeur_utilise=0;
						}
						$Y_decal = $Y_decal + 5;

					}
				}

				fich_debug_bull("Apr�s les cat�gories\n");
				fich_debug_bull("\$Y_decal=$Y_decal\n");
*/
				//= = = == = = == = = == = = == = = == = = == = = =
				// Modif: boireaus 20070828
/*
				if($tab_modele_pdf["active_regroupement_cote"][$classe_id]=='1' or $tab_modele_pdf["active_entete_regroupement"][$classe_id]=='1') {
					//if($matiere[$ident_eleve_aff][$id_periode][$m]['categorie']==$categorie_passe) {
					if($tab_bull['nom_cat_complet'][$m]!=$categorie_passe) {
						$categorie_passe_count=0;

						$Y_categ_cote=$Y_decal;
					}

					if(isset($tab_bull['note'][$m][$i])) {
						$categorie_passe_count++;
					}
					// fin des moyen par cat�gorie
				}

				fich_debug_bull("\$categorie_passe_count=$categorie_passe_count\n");
*/
				//= = = == = = == = = == = = == = = == = = == = = =

				// si on affiche les cat�gories sur le c�t�
/*
				if(!isset($tab_bull['nom_cat_complet'][$m+1])) {
					//$matiere[$ident_eleve_aff][$id_periode][$m+1]['categorie']='';
					$tab_bull['nom_cat_complet'][$m+1]='';
				}

				if($tab_modele_pdf["active_regroupement_cote"][$classe_id]=='1') {
					if($tab_bull['nom_cat_complet'][$m]!=$tab_bull['nom_cat_complet'][$m+1] and $categorie_passe!='')
					{
						//hauteur du regroupement hauteur des matier * nombre de matier de la cat�gorie
						//$hauteur_regroupement=$espace_entre_matier*($categorie_passe_count+1);
						$hauteur_regroupement=$espace_entre_matier*$categorie_passe_count;

						fich_debug_bull("\$espace_entre_matier=$espace_entre_matier\n");
						fich_debug_bull("\$categorie_passe_count=$categorie_passe_count\n");
						fich_debug_bull("\$hauteur_regroupement=$hauteur_regroupement\n");

						//placement du cadre
						//if($nb_eleve_aff==0) { $enplus = 5; }
						//if($nb_eleve_aff!=0) { $enplus = 0; }
						//if($compteur_bulletins==0) { $enplus = 5; }
						//if($compteur_bulletins!=0) { $enplus = 0; }

						fich_debug_bull("Position du cadre $categorie_passe\n");
						$tmp_val=$Y_decal-$hauteur_regroupement+$espace_entre_matier;
						fich_debug_bull("\$Y_decal-\$hauteur_regroupement+\$espace_entre_matier=".$Y_decal."-".$hauteur_regroupement."+".$espace_entre_matier."=".$tmp_val."\n");

						//$pdf->SetXY($X_bloc_matiere-5,$Y_decal-$hauteur_regroupement+$espace_entre_matier);
						$pdf->SetXY($X_bloc_matiere-5,$Y_categ_cote);


						$pdf->SetFillColor($tab_modele_pdf["couleur_categorie_cote1"][$classe_id], $tab_modele_pdf["couleur_categorie_cote2"][$classe_id], $tab_modele_pdf["couleur_categorie_cote3"][$classe_id]);
						if($tab_modele_pdf["couleur_categorie_cote"][$classe_id] == '1') {
							$mode_choix_c = '2';
						}
						else {
							$mode_choix_c = '1';
						}
						$pdf->drawTextBox("", 5, $hauteur_regroupement, 'C', 'T', $mode_choix_c);
						//texte � afficher
						$hauteur_caractere_vertical = '8';
						if ( $tab_modele_pdf["taille_texte_categorie_cote"][$classe_id] != '' and $tab_modele_pdf["taille_texte_categorie_cote"][$classe_id] != '0') {
							$hauteur_caractere_vertical = $tab_modele_pdf["taille_texte_categorie_cote"][$classe_id];
						}
						else {
							$hauteur_caractere_vertical = '8';
						}
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_vertical);
						$text_s = unhtmlentities($tab_bull['nom_cat_complet'][$m]);
						$longeur_test_s = $pdf->GetStringWidth($text_s);

						// gestion de la taille du texte vertical
						$taille_texte = $hauteur_regroupement;
						$grandeur_texte = 'test';
						while($grandeur_texte != 'ok') {
							if($taille_texte < $longeur_test_s)
							{
								$hauteur_caractere_vertical = $hauteur_caractere_vertical-0.3;
								$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_vertical);
								$longeur_test_s = $pdf->GetStringWidth($text_s);
							}
							else {
								$grandeur_texte = 'ok';
							}
						}


						//d�calage pour centre le texte
						$deca = ($hauteur_regroupement-$longeur_test_s)/2;
						$deca = 0;
						$deca = ($hauteur_regroupement-$longeur_test_s)/2;

						//place le texte dans le cadre
						$placement = $Y_decal+$espace_entre_matier-$deca;
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_vertical);
						$pdf->TextWithDirection($X_bloc_matiere-1,$placement,traite_accents_utf8(unhtmlentities($text_s)),'U');
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
						$pdf->SetFillColor(0, 0, 0);
					}
				}

				fich_debug_bull("Apr�s les cat�gories sur le c�t�\n");
				fich_debug_bull("\$Y_decal=$Y_decal\n");

				if($tab_modele_pdf["active_regroupement_cote"][$classe_id]=='1' or $tab_modele_pdf["active_entete_regroupement"][$classe_id]=='1') {
					// fin d'affichage cat�gorie sur le cot�
					$categorie_passe=$tab_bull['nom_cat_complet'][$m];
					// fin de gestion de cat�gorie
				}
				//= = = == = = == = = == = = == = = == = = == = = =
*/

				// Lignes de Mati�re, Note, Rang,... Appr�ciation

				$pdf->SetXY($X_bloc_matiere, $Y_decal);

				// Si c'est une mati�re suivie par l'�l�ve
				//if(isset($tab_ele['matieres'][$m][$i])) {

					// calcul la taille du titre de la mati�re
					$hauteur_caractere_matiere=10;
					if ( $tab_modele_pdf["taille_texte_matiere"][$classe_id] != '' and $tab_modele_pdf["taille_texte_matiere"][$classe_id] != '0' and $tab_modele_pdf["taille_texte_matiere"][$classe_id] < '11' )
					{
						$hauteur_caractere_matiere = $tab_modele_pdf["taille_texte_matiere"][$classe_id];
					}
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',$hauteur_caractere_matiere);

					/*
					// Pour parer au bug sur la suppression de mati�re alors que des groupes sont conserv�s:
					if(isset($tab_bull['groupe'][$m]['matiere']['nom_complet'])) {
						$info_nom_matiere=$tab_bull['groupe'][$m]['matiere']['nom_complet'];
					}
					else {
						$info_nom_matiere=$tab_bull['groupe'][$m]['name']." (".$tab_bull['groupe'][$m]['id'].")";
					}
					*/
					//echo "\$info_nom_matiere=\$tab_ele['matieres'][\"$current_matiere\"]['nom_complet']\n";
					$info_nom_matiere=$tab_ele['matieres']["$current_matiere"]['nom_complet'];

					$val = $pdf->GetStringWidth($info_nom_matiere);
					$taille_texte = $tab_modele_pdf["largeur_matiere"][$classe_id] - 2;
					$grandeur_texte='test';
					while($grandeur_texte!='ok') {
						if($taille_texte<$val)
						{
							$hauteur_caractere_matiere = $hauteur_caractere_matiere-0.3;
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',$hauteur_caractere_matiere);
							$val = $pdf->GetStringWidth($info_nom_matiere);
						}
						else {
							$grandeur_texte='ok';
						}
					}
					$grandeur_texte='test';
					$pdf->Cell($tab_modele_pdf["largeur_matiere"][$classe_id], $espace_entre_matier/2, traite_accents_utf8($info_nom_matiere),'LR',1,'L');
					$Y_decal = $Y_decal+($espace_entre_matier/2);
					$pdf->SetXY($X_bloc_matiere, $Y_decal);
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);

//					fich_debug_bull("\$info_nom_matiere=$info_nom_matiere\n");
//					fich_debug_bull("\$Y_decal=$Y_decal\n");

					// nom des professeurs
					if(isset($tab_ele['matieres']["$current_matiere"]['profs_list']))
					{

						$nb_prof_matiere = count($tab_ele['matieres']["$current_matiere"]['profs_list']);
						$espace_matiere_prof = $espace_entre_matier/2;
						if($nb_prof_matiere>0){
							$espace_matiere_prof = $espace_matiere_prof/$nb_prof_matiere;
						}
						$nb_pass_count = '0';
						$text_prof = '';
						while ($nb_prof_matiere > $nb_pass_count)
						{
							$tmp_login_prof=$tab_ele['matieres']["$current_matiere"]["profs_list"][$nb_pass_count];
							$text_prof=affiche_utilisateur($tmp_login_prof,$tab_ele['id_classe']);

							if ( $nb_prof_matiere <= 2 ) { $hauteur_caractere_prof = 8; }
							elseif ( $nb_prof_matiere == 3) { $hauteur_caractere_prof = 5; }
							elseif ( $nb_prof_matiere > 3) { $hauteur_caractere_prof = 2; }
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_prof);
							$val = $pdf->GetStringWidth($text_prof);
							$taille_texte = ($tab_modele_pdf["largeur_matiere"][$classe_id]);
							$grandeur_texte='test';
							while($grandeur_texte!='ok') {
								if($taille_texte<$val)
								{
									$hauteur_caractere_prof = $hauteur_caractere_prof-0.3;
									$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_prof);
									$val = $pdf->GetStringWidth($text_prof);
								}
								else {
									$grandeur_texte='ok';
								}
							}
							$grandeur_texte='test';
							$pdf->SetX($X_bloc_matiere);
							if( empty($tab_ele['matieres']["$current_matiere"]["profs_list"][$nb_pass_count+1]) ) {
								$pdf->Cell($tab_modele_pdf["largeur_matiere"][$classe_id], $espace_matiere_prof, traite_accents_utf8($text_prof),'LRB',1,'L');
							}
							if( !empty($tab_ele['matieres']["$current_matiere"]["profs_list"][$nb_pass_count+1]) ) {
								$pdf->Cell($tab_modele_pdf["largeur_matiere"][$classe_id], $espace_matiere_prof, traite_accents_utf8($text_prof),'LR',1,'L');
							}
							$nb_pass_count = $nb_pass_count + 1;
						}
					}
					$largeur_utilise = $tab_modele_pdf["largeur_matiere"][$classe_id];

					// coefficient mati�re
					if($tab_modele_pdf["active_coef_moyenne"][$classe_id]=='1') {
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
						if($tab_ele['matieres']["$current_matiere"]['bonus']=='y') {
							$pdf->Cell($tab_modele_pdf["largeur_coef_moyenne"][$classe_id], $espace_entre_matier, $tab_ele['matieres']["$current_matiere"]['coef']."(*)",1,0,'C');
						}
						else {
							$pdf->Cell($tab_modele_pdf["largeur_coef_moyenne"][$classe_id], $espace_entre_matier, $tab_ele['matieres']["$current_matiere"]['coef'],1,0,'C');
						}
						$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_coef_moyenne"][$classe_id];
					}

					//permet le calcul total des coefficients
					// if(empty($moyenne_min[$id_classe][$id_periode])) {
						$total_coef_en_calcul=$total_coef_en_calcul+$tab_ele['matieres']["$current_matiere"]['coef'];
					//}
/*
					// nombre de note
					// 20081118
					//if($tab_modele_pdf["active_nombre_note_case"][$classe_id]=='1') {
					if(($tab_modele_pdf["active_nombre_note_case"][$classe_id]=='1')&&($tab_modele_pdf["active_nombre_note"][$classe_id]!='1')) {
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
						$valeur = $tab_bull['nbct'][$m][$i] . "/" . $tab_bull['groupe'][$m]['nbct'];
						$pdf->Cell($tab_modele_pdf["largeur_nombre_note"][$classe_id], $espace_entre_matier, $valeur,1,0,'C');
						$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_nombre_note"][$classe_id];
					}
*/
					// les moyennes eleve, classe, min, max
					$cpt_ordre = 0;
					while (!empty($ordre_moyenne[$cpt_ordre]) ) {
						//eleve
						if($tab_modele_pdf["active_moyenne_eleve"][$classe_id] == '1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'eleve' ) {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',10);
							$pdf->SetFillColor($tab_modele_pdf["couleur_reperage_eleve1"][$classe_id], $tab_modele_pdf["couleur_reperage_eleve2"][$classe_id], $tab_modele_pdf["couleur_reperage_eleve3"][$classe_id]);

							// calcul nombre de sous affichage

							$nb_sousaffichage='1';
							if(empty($active_coef_sousmoyene)) { $active_coef_sousmoyene = ''; }

							if($active_coef_sousmoyene=='1') { $nb_sousaffichage = $nb_sousaffichage + 1; }
							if($tab_modele_pdf["active_nombre_note"][$classe_id]=='1') { $nb_sousaffichage = $nb_sousaffichage + 1; }
							if($tab_modele_pdf["toute_moyenne_meme_col"][$classe_id]=='1') { if($tab_modele_pdf["active_moyenne_classe"][$classe_id]=='1') { $nb_sousaffichage = $nb_sousaffichage + 1; } }
							if($tab_modele_pdf["toute_moyenne_meme_col"][$classe_id]=='1') { if($tab_modele_pdf["active_moyenne_min"][$classe_id]=='1') { $nb_sousaffichage = $nb_sousaffichage + 1; } }
							if($tab_modele_pdf["toute_moyenne_meme_col"][$classe_id]=='1') { if($tab_modele_pdf["active_moyenne_max"][$classe_id]=='1') { $nb_sousaffichage = $nb_sousaffichage + 1; } }

							// On filtre si la moyenne est vide, on affiche seulement un tiret
							//if ($tab_ele['matieres']["$current_matiere"]['note']=="-") {
							if (($tab_ele['matieres']["$current_matiere"]['note']=="-")||($tab_ele['matieres']["$current_matiere"]['statut']=="v")) {
								$valeur = "-";
							}
							elseif($tab_ele['matieres']["$current_matiere"]['statut']!="") {
								$valeur=$tab_ele['matieres']["$current_matiere"]['statut'];
							}
							else {
								//$valeur = present_nombre($tab_ele['matieres']["$current_matiere"]['note'], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
								$valeur = present_nombre(strtr($tab_ele['matieres']["$current_matiere"]['note'],",","."), $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
								//$valeur = $tab_ele['matieres']["$current_matiere"]['note'];
							}
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_entre_matier/$nb_sousaffichage, $valeur,1,2,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
							$valeur = "";

							if($active_coef_sousmoyene=='1') {
								$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'I',7);
								$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_entre_matier/$nb_sousaffichage, 'coef. '.$tab_ele['matieres']["$current_matiere"]['coef'],'LR',2,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
							}

							if($tab_modele_pdf["toute_moyenne_meme_col"][$classe_id]=='1') {
								// On affiche toutes les moyennes dans la m�me colonne
								$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'I',7);
								if($tab_modele_pdf["active_moyenne_classe"][$classe_id]=='1') {
									//if ($tab_bull['moy_classe_grp'][$m]=="-") {
									if (($tab_ele['matieres']["$current_matiere"]['moy_classe_grp']=="-")||($tab_ele['matieres']["$current_matiere"]['moy_classe_grp']=="")) {
										$valeur = "-";
									}
									else {
										$valeur = present_nombre($tab_ele['matieres']["$current_matiere"]['moy_classe_grp'], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
									}
									$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_entre_matier/$nb_sousaffichage, 'cla.'.$valeur,'LR',2,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
								}


								if($tab_modele_pdf["active_moyenne_min"][$classe_id]=='1') {
									//if ($tab_bull['moy_min_classe_grp'][$m]=="-") {
									//if (($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp']=="-")||($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp']=="")) {
									if (($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp']=="-")||($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp']=="")||($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp']=="1000")) {
										$valeur = "-";
									} else {
										$valeur = present_nombre($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp'], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
									}
									$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_entre_matier/$nb_sousaffichage, 'min.'.$valeur,'LR',2,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
								}


								if($tab_modele_pdf["active_moyenne_max"][$classe_id]=='1') {
									//if ($tab_bull['moy_max_classe_grp'][$m]=="-") {
									//if (($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=="-")||($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=="")) {
									//if (($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=="-")||($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=="")||(strtr($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp'],",",".")<0)) {
									if (($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=="-")||($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=="")||($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=='-1')) {
										$valeur = "-";
									} else {
										$valeur = present_nombre($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp'], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
									}
									$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_entre_matier/$nb_sousaffichage, 'max.'.$valeur,'LRD',2,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
									$valeur = ''; // on remet � vide.
								}
							}
/*
							if($tab_modele_pdf["active_nombre_note"][$classe_id]=='1') {
								$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'I',7);
								$espace_pour_nb_note = $espace_entre_matier/$nb_sousaffichage;
								$espace_pour_nb_note = $espace_pour_nb_note / 2;
								$valeur1 = ''; $valeur2 = '';
								if ($tab_bull['nbct'][$m][$i]!= 0 ) {
									$valeur1 = $tab_bull['nbct'][$m][$i].' note';
									if($tab_bull['nbct'][$m][$i]>1){$valeur1.='s';}
									$valeur2 = 'sur '.$tab_bull['groupe'][$m]['nbct'];
								}
								$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_pour_nb_note, $valeur1, 'LR',2,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
								$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_pour_nb_note, $valeur2, 'LRB',2,'C',$tab_modele_pdf["active_reperage_eleve"][$classe_id]);
								$valeur1 = ''; $valeur2 = '';
							}
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
							$pdf->SetFillColor(0, 0, 0);
*/
							$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];

						} // Fin affichage �l�ve

						//classe
						if( $tab_modele_pdf["active_moyenne_classe"][$classe_id] == '1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'classe' ) {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
							//if ($tab_bull['moy_classe_grp'][$m]=="-") {
							if (($tab_ele['matieres']["$current_matiere"]['moy_classe_grp']=="-")||($tab_ele['matieres']["$current_matiere"]['moy_classe_grp']=="")) {
								$valeur = "-";
							} else {
								$valeur = present_nombre($tab_ele['matieres']["$current_matiere"]['moy_classe_grp'], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
							}
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_entre_matier, $valeur,'TLRB',0,'C');
							/*
							//permet le calcul de la moyenne g�n�ral de la classe
							if(empty($moyenne_classe[$id_classe][$id_periode])) {
								$total_moyenne_classe_en_calcul=$total_moyenne_classe_en_calcul+($matiere[$ident_eleve_aff][$id_periode][$m]['moy_classe']*$matiere[$ident_eleve_aff][$id_periode][$m]['coef']);
							}
							*/
							$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
						}


						//min
						if( $tab_modele_pdf["active_moyenne_min"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'min' ) {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
							//if ($tab_bull['moy_min_classe_grp'][$m]=="-") {
							if (($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp']=="-")||($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp']=="")||($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp']=="1000")) {
								$valeur = "-";
							} else {
								$valeur = present_nombre($tab_ele['matieres']["$current_matiere"]['moy_min_classe_grp'], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
							}
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_entre_matier, $valeur,'TLRB',0,'C');
							/*
							//permet le calcul de la moyenne mini
							if(empty($moyenne_min[$id_classe][$id_periode])) {
								$total_moyenne_min_en_calcul=$total_moyenne_min_en_calcul+($matiere[$ident_eleve_aff][$id_periode][$m]['moy_min']*$matiere[$ident_eleve_aff][$id_periode][$m]['coef']);
							}
							*/
							$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
						}

						//max
						if( $tab_modele_pdf["active_moyenne_max"][$classe_id] == '1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'max' ) {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
							//if ($tab_bull['moy_max_classe_grp'][$m]== "-") {
							if (($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=="-")||($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=="")||($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp']=='-1')) {
								$valeur = "-";
							} else {
								$valeur = present_nombre($tab_ele['matieres']["$current_matiere"]['moy_max_classe_grp'], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
							}
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $espace_entre_matier, $valeur,'TLRB',0,'C');
							/*
							//permet le calcul de la moyenne maxi
							if(empty($moyenne_max[$id_classe][$id_periode])) {
								$total_moyenne_max_en_calcul=$total_moyenne_max_en_calcul+($matiere[$ident_eleve_aff][$id_periode][$m]['moy_max']*$matiere[$ident_eleve_aff][$id_periode][$m]['coef']);
							}
							*/
							$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
						}
						//$largeur_utilise = $largeur_utilise+$largeur_moyenne;

/*
						// rang de l'�l�ve
						if($tab_modele_pdf["active_rang"][$classe_id]=='1' and $ordre_moyenne[$cpt_ordre] == 'rang' ) {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
							// A REVOIR: J'AI l'EFFECTIF DU GROUPE, mais faut-il compter les �l�ves ABS, DISP,...?
							//if((isset($tab_bull['rang'][$i][$m]))&&(isset($tab_bull['groupe'][$m]['effectif']))) {
								//$pdf->Cell($tab_modele_pdf["largeur_rang"][$classe_id], $espace_entre_matier, $tab_bull['rang'][$i][$m].'/'.$tab_bull['groupe'][$m]['effectif'],1,0,'C');
							if((isset($tab_bull['rang'][$m][$i]))&&(isset($tab_bull['groupe'][$m]['effectif']))) {
								$pdf->Cell($tab_modele_pdf["largeur_rang"][$classe_id], $espace_entre_matier, $tab_bull['rang'][$m][$i].'/'.$tab_bull['groupe'][$m]['effectif_avec_note'],1,0,'C');
							}
							else {
								$pdf->Cell($tab_modele_pdf["largeur_rang"][$classe_id], $espace_entre_matier, '',1,0,'C');
							}
							$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_rang"][$classe_id];
						}

						// graphique de niveau
						if($tab_modele_pdf["active_graphique_niveau"][$classe_id]=='1' and $ordre_moyenne[$cpt_ordre] == 'niveau' ) {
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
							$id_groupe_graph = $tab_bull['groupe'][$m]['id'];
							// placement de l'�l�ve dans le graphique de niveau

							// AJOUT: La variable n'�tait pas initialis�e dans le bulletin_pdf_avec_modele...
							$place_eleve='';

							if ($tab_bull['note'][$m][$i]!="") {
								if(isset($tab_bull['place_eleve'][$m][$i])) {
									$place_eleve=$tab_bull['place_eleve'][$m][$i];
								}
							}
							$data_grap[0]=$tab_bull['quartile1_grp'][$m];
							$data_grap[1]=$tab_bull['quartile2_grp'][$m];
							$data_grap[2]=$tab_bull['quartile3_grp'][$m];
							$data_grap[3]=$tab_bull['quartile4_grp'][$m];
							$data_grap[4]=$tab_bull['quartile5_grp'][$m];
							$data_grap[5]=$tab_bull['quartile6_grp'][$m];
							//if (array_sum($data_grap[$id_periode][$id_groupe_graph]) != 0) {
							if (array_sum($data_grap) != 0) {
								//$pdf->DiagBarre($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2), $tab_modele_pdf["largeur_niveau"][$classe_id], $espace_entre_matier, $data_grap[$id_periode][$id_groupe_graph], $place_eleve);
								$pdf->DiagBarre($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2), $tab_modele_pdf["largeur_niveau"][$classe_id], $espace_entre_matier, $data_grap, $place_eleve);
							}
							$place_eleve=''; // on vide la variable
							$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_niveau"][$classe_id];
						}
*/

						//appr�ciation
						if($tab_modele_pdf["active_appreciation"][$classe_id]=='1' and $ordre_moyenne[$cpt_ordre] == 'appreciation' ) {
							// si on autorise l'affichage des sous mati�re et s'il y en a alors on les affiche
//							$id_groupe_select = $tab_bull['groupe'][$m]['id'];
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
							$X_sous_matiere = 0; $largeur_sous_matiere=0;
/*
							if($tab_modele_pdf["autorise_sous_matiere"][$classe_id]=='1' and !empty($tab_bull['groupe'][$m][$i]['cn_nom'])) {
								$X_sous_matiere = $X_note_moy_app+$largeur_utilise;
								$Y_sous_matiere = $Y_decal-($espace_entre_matier/2);
								$n=0;
								$largeur_texte_sousmatiere=0; $largeur_sous_matiere=0;
								while( !empty($tab_bull['groupe'][$m][$i]['cn_nom'][$n]) )
								{
									$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
									$largeur_texte_sousmatiere = $pdf->GetStringWidth($tab_bull['groupe'][$m][$i]['cn_nom'][$n].': '.$tab_bull['groupe'][$m][$i]['cn_note'][$n]);
									if($largeur_sous_matiere<$largeur_texte_sousmatiere) { $largeur_sous_matiere=$largeur_texte_sousmatiere; }
									$n = $n + 1;
								}
								if($largeur_sous_matiere!='0') { $largeur_sous_matiere = $largeur_sous_matiere + 2; }
								$n=0;
								while( !empty($tab_bull['groupe'][$m][$i]['cn_nom'][$n]) )
								{
									$pdf->SetXY($X_sous_matiere, $Y_sous_matiere);
									$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
									$pdf->Cell($largeur_sous_matiere, $espace_entre_matier/count($tab_bull['groupe'][$m][$i]['cn_nom']), traite_accents_utf8($tab_bull['groupe'][$m][$i]['cn_nom'][$n].': '.$tab_bull['groupe'][$m][$i]['cn_note'][$n]),1,0,'L');
									$Y_sous_matiere = $Y_sous_matiere+$espace_entre_matier/count($tab_bull['groupe'][$m][$i]['cn_nom']);
									$n = $n + 1;
								}
								$largeur_utilise = $largeur_utilise+$largeur_sous_matiere;
							}
*/
							$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_decal-($espace_entre_matier/2));
							// calcul de la taille du texte des appr�ciation
							$hauteur_caractere_appreciation = 9;
							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_appreciation);

							//suppression des espace en d�but et en fin
							$app_aff = trim($tab_ele['matieres']["$current_matiere"]['app']);
/*
							fich_debug_bull("__________________________________________\n");
							fich_debug_bull("$app_aff\n");
							fich_debug_bull("__________________________________________\n");
*/
							// DEBUT AJUSTEMENT TAILLE APPRECIATION
							$taille_texte_total = $pdf->GetStringWidth($app_aff);
							$largeur_appreciation2 = $largeur_appreciation - $largeur_sous_matiere;

							if($use_cell_ajustee=="n") {
								//$taille_texte = (($espace_entre_matier/3)*$largeur_appreciation2);
								$nb_ligne_app = '2.8';
								//$nb_ligne_app = '3.8';
								//$nb_ligne_app = '4.8';
								$taille_texte_max = $nb_ligne_app * ($largeur_appreciation2-4);
								//$taille_texte_max = $nb_ligne_app * ($largeur_appreciation2);
								$grandeur_texte='test';
	
								fich_debug_bull("\$taille_texte_total=$taille_texte_total\n");
								fich_debug_bull("\$largeur_appreciation2=$largeur_appreciation2\n");
								fich_debug_bull("\$nb_ligne_app=$nb_ligne_app\n");
								//fich_debug_bull("\$taille_texte_max = \$nb_ligne_app * (\$largeur_appreciation2-4)=$nb_ligne_app * ($largeur_appreciation2-4)=$taille_texte_max\n");
								fich_debug_bull("\$taille_texte_max = \$nb_ligne_app * (\$largeur_appreciation2)=$nb_ligne_app * ($largeur_appreciation2)=$taille_texte_max\n");
	
								while($grandeur_texte!='ok') {
									if($taille_texte_max < $taille_texte_total)
									{
										$hauteur_caractere_appreciation = $hauteur_caractere_appreciation-0.3;
										//$hauteur_caractere_appreciation = $hauteur_caractere_appreciation-0.1;
										$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',$hauteur_caractere_appreciation);
										$taille_texte_total = $pdf->GetStringWidth($app_aff);
									}
									else {
										$grandeur_texte='ok';
									}
								}
								$grandeur_texte='test';
								$pdf->drawTextBox(traite_accents_utf8($app_aff), $largeur_appreciation2, $espace_entre_matier, 'J', 'M', 1);
							}
							else {
								$texte=$app_aff;
								//$texte="Bla bla\nbli ".$app_aff;
								$taille_max_police=$hauteur_caractere_appreciation;
								$taille_min_police=ceil($taille_max_police/3);

								$largeur_dispo=$largeur_appreciation2;
								$h_cell=$espace_entre_matier;

								cell_ajustee(traite_accents_utf8($texte),$pdf->GetX(),$pdf->GetY(),$largeur_dispo,$h_cell,$taille_max_police,$taille_min_police,'LRBT');
							}

							$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
							$largeur_utilise = $largeur_utilise + $largeur_appreciation2;
							//$largeur_utilise = 0;
						}

						$cpt_ordre = $cpt_ordre + 1;
					}
					$largeur_utilise = 0;
					// fin de boucle d'ordre
					$Y_decal = $Y_decal+($espace_entre_matier/2);
				//}
			}



			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			// Ligne moyenne g�n�rale
			//bas du tableau des note et app si les affichage des moyennes ne sont pas affich� le bas du tableau ne seras pas affich�
			if ( $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $tab_modele_pdf["active_moyenne_general"][$classe_id] == '1' ) {
				$X_note_moy_app = $tab_modele_pdf["X_note_app"][$classe_id];
				$Y_note_moy_app = $tab_modele_pdf["Y_note_app"][$classe_id]+$tab_modele_pdf["hauteur_note_app"][$classe_id]-$tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id];
				$pdf->SetXY($X_note_moy_app, $Y_note_moy_app);
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
				$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);
				$pdf->Cell($tab_modele_pdf["largeur_matiere"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], traite_accents_utf8("Moyenne g�n�rale"),1,0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
				$largeur_utilise = $tab_modele_pdf["largeur_matiere"][$classe_id];

				// coefficient mati�re
				if($tab_modele_pdf["active_coef_moyenne"][$classe_id]=='1') {
					$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);
					$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);
					$pdf->Cell($tab_modele_pdf["largeur_coef_moyenne"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], "",1,0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
					$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_coef_moyenne"][$classe_id];
				}
/*
				// nombre de note
				// 20081118
				//if($tab_modele_pdf["active_nombre_note_case"][$classe_id]=='1') {
				if(($tab_modele_pdf["active_nombre_note_case"][$classe_id]=='1')&&($tab_modele_pdf["active_nombre_note"][$classe_id]!='1')) {
					$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);
					$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);
					$pdf->Cell($tab_modele_pdf["largeur_nombre_note"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], "",1,0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
					$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_nombre_note"][$classe_id];
				}
*/
				$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);

				$cpt_ordre = 0;
				while ( !empty($ordre_moyenne[$cpt_ordre]) ) {
					//eleve
					if($tab_modele_pdf["active_moyenne_eleve"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'eleve' ) {
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',10);
						$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);

						// On a deux param�tres de couleur qui se croisent. On utilise une variable tierce.
						$utilise_couleur = $tab_modele_pdf["couleur_moy_general"][$classe_id];
						if($tab_modele_pdf["active_reperage_eleve"][$classe_id]=='1') {
							// Si on affiche une couleur sp�cifique pour les moyennes de l'�l�ve,
							// on utilise cette couleur ici aussi, quoi qu'il arrive
							$pdf->SetFillColor($tab_modele_pdf["couleur_reperage_eleve1"][$classe_id], $tab_modele_pdf["couleur_reperage_eleve2"][$classe_id], $tab_modele_pdf["couleur_reperage_eleve3"][$classe_id]);
							$utilise_couleur = 1;
						}

						if(($tab_ele['moyenne']=="")||($tab_ele['moyenne']=="-")) {
							$val_tmp="-";
						}
						else {
							//$val_tmp=present_nombre($tab_bull['moy_gen_eleve'][$i], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
							//$val_tmp=$tab_bull['moy_gen_eleve'][$i];
							$val_tmp=present_nombre(my_ereg_replace(',','.',$tab_ele['moyenne']), $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
							/*
							$tmp_fich=fopen("/tmp/test_moy_gen.txt","a+");
							fwrite($tmp_fich,$tab_bull['eleve'][$i]['login']." present_nombre(\$tab_bull['moy_gen_eleve'][$i], \$tab_modele_pdf[\"arrondie_choix\"][$classe_id], \$tab_modele_pdf[\"nb_chiffre_virgule\"][$classe_id], \$tab_modele_pdf[\"chiffre_avec_zero\"][$classe_id])=present_nombre(".$tab_bull['moy_gen_eleve'][$i].", ".$tab_modele_pdf["arrondie_choix"][$classe_id].", ".$tab_modele_pdf["nb_chiffre_virgule"][$classe_id].",". $tab_modele_pdf["chiffre_avec_zero"][$classe_id].")=".present_nombre($tab_bull['moy_gen_eleve'][$i], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id])."\n");

							fwrite($tmp_fich,$tab_bull['eleve'][$i]['login']." present_nombre(my_ereg_replace(',','.',\$tab_bull['moy_gen_eleve'][$i]), \$tab_modele_pdf[\"arrondie_choix\"][$classe_id], \$tab_modele_pdf[\"nb_chiffre_virgule\"][$classe_id], \$tab_modele_pdf[\"chiffre_avec_zero\"][$classe_id])=".present_nombre(my_ereg_replace(',','.',$tab_bull['moy_gen_eleve'][$i]), $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id])."\n");

							fclose($tmp_fich);
							*/
						}

						//$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], present_nombre($tab_bull['moy_gen_eleve'][$i], $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]),1,0,'C',$utilise_couleur);
						$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], $val_tmp,1,0,'C',$utilise_couleur);

						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
						$pdf->SetFillColor(0, 0, 0);
						$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
					}

					//classe
					if($tab_modele_pdf["active_moyenne_classe"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'classe' ) {
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
						$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);

						/*
						if( $total_coef_en_calcul != 0){
							$moyenne_classe = $total_moyenne_classe_en_calcul / $total_coef_en_calcul;
						}
						else{
							$moyenne_classe = '-';
						}
						*/
						if(($tab_ele['moy_generale_classe']=="")||($tab_ele['moy_generale_classe']=="-")) {
							$moyenne_classe = '-';
						}
						else{
							$moyenne_classe = present_nombre(my_ereg_replace(',','.',$tab_ele['moy_generale_classe']), $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]);
						}

						if ( $moyenne_classe != '-' ) {
							//$moyenne_classe=$tab_bull['moy_generale_classe'];
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], $moyenne_classe,1,0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
						} else {
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], '-',1,0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
						}
						$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
					}

					//min
					if($tab_modele_pdf["active_moyenne_min"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'min' ) {
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
						$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);

						/*
						if($total_coef_en_calcul != 0 and $tab_modele_pdf["affiche_moyenne_mini_general"][$classe_id] == '1' ){
							$moyenne_min = $total_moyenne_min_en_calcul / $total_coef_en_calcul;
						}
						else{
							$moyenne_min = '-';
						}
						*/

						if ($tab_ele['moy_min_classe']!='-') {
							//$moyenne_min=$tab_moy_min_classe[$classe_id][$id_periode];
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], present_nombre(my_ereg_replace(',','.',$tab_ele['moy_min_classe']), $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]),1,0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
						} else {
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], '-',1,0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
						}
						$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
					}

					//max
					if($tab_modele_pdf["active_moyenne_max"][$classe_id]=='1' and $tab_modele_pdf["active_moyenne"][$classe_id] == '1' and $ordre_moyenne[$cpt_ordre] == 'max' ) {
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
						$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);

						/*
						if($total_coef_en_calcul != 0 and $tab_modele_pdf["affiche_moyenne_maxi_general"][$classe_id] == '1' ){
							$moyenne_max = $total_moyenne_max_en_calcul / $total_coef_en_calcul;
						} else {
							$moyenne_max = '-';
						}
						*/

						if ($tab_ele['moy_max_classe']!='-') {
							$moyenne_max=$tab_ele['moy_max_classe'];
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], present_nombre(my_ereg_replace(',','.',$tab_ele['moy_max_classe']), $tab_modele_pdf["arrondie_choix"][$classe_id], $tab_modele_pdf["nb_chiffre_virgule"][$classe_id], $tab_modele_pdf["chiffre_avec_zero"][$classe_id]),1,0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
						} else {
							$pdf->Cell($tab_modele_pdf["largeur_d_une_moyenne"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], '-',1,0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
						}
						$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_d_une_moyenne"][$classe_id];
					}
/*
					// rang de l'�l�ve
					if($tab_modele_pdf["active_rang"][$classe_id]=='1' and $ordre_moyenne[$cpt_ordre] == 'rang') {
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',8);
						$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);
						if ($tab_bull['rang_classe'][$i]!= 0) {
							$rang_a_afficher=$tab_bull['rang_classe'][$i].'/'.$tab_bull['eff_classe'];
						} else {
							$rang_a_afficher = "";
						}
						$pdf->Cell($tab_modele_pdf["largeur_rang"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], $rang_a_afficher ,'TLRB',0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
						$largeur_utilise = $largeur_utilise + $tab_modele_pdf["largeur_rang"][$classe_id];
					}

					// graphique de niveau
					if($tab_modele_pdf["active_graphique_niveau"][$classe_id]=='1' and $ordre_moyenne[$cpt_ordre] == 'niveau' ) {
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);
						$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);
						// placement de l'�l�ve dans le graphique de niveau
						//if ($tab_bull['moy_gen_eleve'][$i]!="") {
						if (($tab_bull['moy_gen_eleve'][$i]!="")&&($tab_bull['moy_gen_eleve'][$i]!="-")) {
							$place_eleve=$tab_bull['place_eleve_classe'][$i];
						}
						$data_grap_classe[0]=$tab_bull['quartile1_classe_gen'];
						$data_grap_classe[1]=$tab_bull['quartile2_classe_gen'];
						$data_grap_classe[2]=$tab_bull['quartile3_classe_gen'];
						$data_grap_classe[3]=$tab_bull['quartile4_classe_gen'];
						$data_grap_classe[4]=$tab_bull['quartile5_classe_gen'];
						$data_grap_classe[5]=$tab_bull['quartile6_classe_gen'];

						if (array_sum($data_grap_classe) != 0) {
							//$pdf->DiagBarre($X_note_moy_app+$largeur_utilise, $Y_note_moy_app, $tab_modele_pdf["largeur_niveau"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], $data_grap_classe[$id_periode][$id_classe_selection], $place_eleve);
							$pdf->DiagBarre($X_note_moy_app+$largeur_utilise, $Y_note_moy_app, $tab_modele_pdf["largeur_niveau"][$classe_id], $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], $data_grap_classe, $place_eleve);
						}
						$place_eleve=''; // on vide la variable
						$largeur_utilise = $largeur_utilise+$tab_modele_pdf["largeur_niveau"][$classe_id];
					}
*/
					//appr�ciation
					if($tab_modele_pdf["active_appreciation"][$classe_id]=='1' and $ordre_moyenne[$cpt_ordre] == 'appreciation' ) {
						$pdf->SetXY($X_note_moy_app+$largeur_utilise, $Y_note_moy_app);
						$pdf->SetFillColor($tab_modele_pdf["couleur_moy_general1"][$classe_id], $tab_modele_pdf["couleur_moy_general2"][$classe_id], $tab_modele_pdf["couleur_moy_general3"][$classe_id]);
						$pdf->Cell($largeur_appreciation, $tab_modele_pdf["hauteur_entete_moyenne_general"][$classe_id], '','TLRB',0,'C', $tab_modele_pdf["couleur_moy_general"][$classe_id]);
						$largeur_utilise = $largeur_utilise + $largeur_appreciation;
					}
					$cpt_ordre = $cpt_ordre + 1;
				}
				$largeur_utilise = 0;
				// fin de boucle d'ordre
				$pdf->SetFillColor(0, 0, 0);
			}

			//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			$Y_avis_cons_init = $tab_modele_pdf["Y_avis_cons"][$classe_id];
			$Y_sign_chef_init = $tab_modele_pdf["Y_sign_chef"][$classe_id];

			$hauteur_avis_cons_init = $tab_modele_pdf["hauteur_avis_cons"][$classe_id] - 0.5;
			$hauteur_sign_chef_init = $tab_modele_pdf["hauteur_sign_chef"][$classe_id] - 0.5;

			// = = = == = = === bloc avis du conseil de classe = = = == = = == = = =
			if($tab_modele_pdf["active_bloc_avis_conseil"][$classe_id]=='1') {
				if($tab_modele_pdf["cadre_avis_cons"][$classe_id]!=0) {
					//$pdf->Rect($tab_modele_pdf["X_avis_cons"][$classe_id], $tab_modele_pdf["Y_avis_cons"][$classe_id], $tab_modele_pdf["longeur_avis_cons"][$classe_id], $tab_modele_pdf["hauteur_avis_cons"][$classe_id], 'D');
					$pdf->Rect($tab_modele_pdf["X_avis_cons"][$classe_id], $Y_avis_cons_init, $tab_modele_pdf["longeur_avis_cons"][$classe_id], $hauteur_avis_cons_init, 'D');
				}
				//$pdf->SetXY($tab_modele_pdf["X_avis_cons"][$classe_id],$tab_modele_pdf["Y_avis_cons"][$classe_id]);
				$pdf->SetXY($tab_modele_pdf["X_avis_cons"][$classe_id],$Y_avis_cons_init);

				if ( $tab_modele_pdf["taille_titre_bloc_avis_conseil"][$classe_id] != '' and $tab_modele_pdf["taille_titre_bloc_avis_conseil"][$classe_id] < '15' ) {
					$taille = $tab_modele_pdf["taille_titre_bloc_avis_conseil"][$classe_id];
				} else {
					$taille = '10';
				}
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'I',$taille);
/*
				if ( $tab_modele_pdf["titre_bloc_avis_conseil"][$classe_id] != '' ) {
					$tt_avis = $tab_modele_pdf["titre_bloc_avis_conseil"][$classe_id];
				} else {
					$tt_avis = 'Avis du Conseil de classe:';
				}
*/
				$tt_avis = 'Avis g�n�ral :';

				$pdf->Cell($tab_modele_pdf["longeur_avis_cons"][$classe_id],5, $tt_avis,0,2,'');

				//$pdf->SetXY($tab_modele_pdf["X_avis_cons"][$classe_id]+2.5,$tab_modele_pdf["Y_avis_cons"][$classe_id]+5);
				$pdf->SetXY($tab_modele_pdf["X_avis_cons"][$classe_id]+2.5,$Y_avis_cons_init+5);

				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
				$texteavis = $tab_ele['avis'];

				//$pdf->drawTextBox(traite_accents_utf8($texteavis), $tab_modele_pdf["longeur_avis_cons"][$classe_id]-5, $tab_modele_pdf["hauteur_avis_cons"][$classe_id]-10, 'J', 'M', 0);

				if($use_cell_ajustee=="n") {
					$pdf->drawTextBox(traite_accents_utf8($texteavis), $tab_modele_pdf["longeur_avis_cons"][$classe_id]-5, $hauteur_avis_cons_init-10, 'J', 'M', 0);
				}
				else {
					$texte=$texteavis;
					$taille_max_police=10;
					$taille_min_police=ceil($taille_max_police/3);

					$largeur_dispo=$tab_modele_pdf["longeur_avis_cons"][$classe_id]-5;
					$h_cell=$hauteur_avis_cons_init-10;

					cell_ajustee(traite_accents_utf8($texte),$pdf->GetX(),$pdf->GetY(),$largeur_dispo,$h_cell,$taille_max_police,$taille_min_police,'');
				}

				//= = = == = = == = = == = = ==
				// MODIF: boireaus 20081220
				// DEBUG:
				//$pdf->drawTextBox(traite_accents_utf8($texteavis." \$Y_avis_cons_init=".$Y_avis_cons_init." \$tab_modele_pdf[\"hauteur_avis_cons\"][$classe_id]=".$tab_modele_pdf["hauteur_avis_cons"][$classe_id]." \$hauteur_pris_app_abs=".$hauteur_pris_app_abs), $tab_modele_pdf["longeur_avis_cons"][$classe_id]-5, $tab_modele_pdf["hauteur_avis_cons"][$classe_id]-10, 'J', 'M', 0);
				//= = = == = = == = = == = = ==
				$X_pp_aff=$tab_modele_pdf["X_avis_cons"][$classe_id];

				//$Y_pp_aff=$tab_modele_pdf["Y_avis_cons"][$classe_id]+$tab_modele_pdf["hauteur_avis_cons"][$classe_id]-5;
				$Y_pp_aff=$Y_avis_cons_init+$hauteur_avis_cons_init-5;

				$pdf->SetXY($X_pp_aff,$Y_pp_aff);
				if ( $tab_modele_pdf["taille_profprincipal_bloc_avis_conseil"][$classe_id] != '' and $tab_modele_pdf["taille_profprincipal_bloc_avis_conseil"][$classe_id] < '15' ) {
					$taille = $tab_modele_pdf["taille_profprincipal_bloc_avis_conseil"][$classe_id];
				} else {
					$taille = '10';
				}
				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'I',$taille);
				// Le nom du professeur principal
				$pp_classe[$i]="";
				if(isset($tab_ele['pp']['login'])) {
					$pp_classe[$i]="<b>".ucfirst($gepi_prof_suivi)."</b> <i>".affiche_utilisateur($tab_ele['pp']['login'],$tab_ele['id_classe'])."</i>";
				}
				else {
					$pp_classe[$i]="";
				}
				//$pdf->MultiCellTag(200, 5, traite_accents_utf8($pp_classe[$i]), '', 'J', '');
				$pdf->ext_MultiCellTag(200, 5, traite_accents_utf8($pp_classe[$i]), '', 'J', '');
			}


			// = = = == = = == = = == = = = bloc du pr�sident du conseil de classe = = = == = = ===
			if( $tab_modele_pdf["active_bloc_chef"][$classe_id] == '1' ) {
				if( $tab_modele_pdf["cadre_sign_chef"][$classe_id] != 0 ) {
					//$pdf->Rect($tab_modele_pdf["X_sign_chef"][$classe_id], $tab_modele_pdf["Y_sign_chef"][$classe_id], $tab_modele_pdf["longeur_sign_chef"][$classe_id], $tab_modele_pdf["hauteur_sign_chef"][$classe_id], 'D');
					$pdf->Rect($tab_modele_pdf["X_sign_chef"][$classe_id], $Y_sign_chef_init, $tab_modele_pdf["longeur_sign_chef"][$classe_id], $hauteur_sign_chef_init, 'D');
				}
				//$pdf->SetXY($tab_modele_pdf["X_sign_chef"][$classe_id],$tab_modele_pdf["Y_sign_chef"][$classe_id]);
				$pdf->SetXY($tab_modele_pdf["X_sign_chef"][$classe_id],$Y_sign_chef_init);

				$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'',10);
				if( $tab_modele_pdf["affichage_haut_responsable"][$classe_id] == '1' ) {
					if ( $tab_modele_pdf["affiche_fonction_chef"][$classe_id] == '1' ){
						if ( $tab_modele_pdf["taille_texte_fonction_chef"][$classe_id] != '' and $tab_modele_pdf["taille_texte_fonction_chef"][$classe_id] != '0' and $tab_modele_pdf["taille_texte_fonction_chef"][$classe_id] < '15' ) {
							$taille = $tab_modele_pdf["taille_texte_fonction_chef"][$classe_id];
						} else {
							$taille = '10';
						}
						$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'B',$taille);
						$pdf->Cell($tab_modele_pdf["longeur_sign_chef"][$classe_id],5, traite_accents_utf8($tab_bull['formule']),0,2,'');
					}
					if ( $tab_modele_pdf["taille_texte_identitee_chef"][$classe_id] != '' and $tab_modele_pdf["taille_texte_identitee_chef"][$classe_id] != '0' and $tab_modele_pdf["taille_texte_identitee_chef"][$classe_id] < '15' ) {
						$taille = $tab_modele_pdf["taille_texte_identitee_chef"][$classe_id];
					} else {
						$taille_avis = '8';
					}
					$pdf->SetFont($tab_modele_pdf["caractere_utilse"][$classe_id],'I',$taille);
					$pdf->Cell($tab_modele_pdf["longeur_sign_chef"][$classe_id],5, traite_accents_utf8($tab_ele['suivi_par']),0,2,'');
				} else {
					//$pdf->MultiCell($longeur_sign_chef[$classe_id],5, "Visa du Chef d'�tablissement\nou de son d�l�gu�",0,2,'');
					$pdf->MultiCell($tab_modele_pdf["longeur_sign_chef"][$classe_id],5, traite_accents_utf8("Visa du Chef d'�tablissement\nou de son d�l�gu�"),0,2,'');
				}
			}




		}


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


function javascript_tab_stat2($pref_id,$cpt) {
	// Fonction � appeler avec une portion de code du type:
	/*
	echo "<div style='position: fixed; top: 200px; right: 200px;'>\n";
	javascript_tab_stat('tab_stat_',$cpt);
	echo "</div>\n";
	*/

	$alt=1;
	echo "<table class='boireaus' summary='Statistiques'>\n";
	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>Moyenne</th>\n";
	echo "<td id='".$pref_id."moyenne'></td>\n";
	echo "</tr>\n";

	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>1er quartile</th>\n";
	echo "<td id='".$pref_id."q1'></td>\n";
	echo "</tr>\n";

	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>M�diane</th>\n";
	echo "<td id='".$pref_id."mediane'></td>\n";
	echo "</tr>\n";

	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>3� quartile</th>\n";
	echo "<td id='".$pref_id."q3'></td>\n";
	echo "</tr>\n";
	
	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>Min</th>\n";
	echo "<td id='".$pref_id."min'></td>\n";
	echo "</tr>\n";
	
	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>Max</th>\n";
	echo "<td id='".$pref_id."max'></td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<script type='text/javascript' language='JavaScript'>

function calcul_moy_med_".$pref_id."() {
	var eff_utile=0;
	var total=0;
	var valeur;
	var tab_valeur=new Array();
	var i=0;
	var j=0;
	var n=0;
	var mediane;
	var moyenne;
	var q1;
	var q3;
	var rang=0;

	for(i=0;i<$cpt;i++) {
		if(document.getElementById('$pref_id'+i)) {
			valeur=document.getElementById('$pref_id'+i).value;
			if((valeur!='abs')&&(valeur!='disp')&&(valeur!='-')&&(valeur!='')) {
				tab_valeur[j]=valeur;
				// Tambouille pour �viter que 'valeur' soit pris pour une chaine de caract�res
				total=eval((total*100+valeur*100)/100);
				eff_utile++;
				j++;
			}
		}
	}
	if(eff_utile>0) {
		moyenne=Math.round(10*total/eff_utile)/10;
		document.getElementById('".$pref_id."moyenne').innerHTML=moyenne;

		tab_valeur.sort((function(a,b){return a - b}));
		n=tab_valeur.length;
		if(n/2==Math.round(n/2)) {
			// Les indices commencent � z�ro
			// Tambouille pour �viter que 'valeur' soit pris pour une chaine de caract�res
			mediane=((eval(100*tab_valeur[n/2-1]+100*tab_valeur[n/2]))/100)/2;
		}
		else {
			mediane=tab_valeur[(n-1)/2];
		}
		document.getElementById('".$pref_id."mediane').innerHTML=mediane;

		if(eff_utile>=4) {
			rang=Math.ceil(eff_utile/4);
			q1=tab_valeur[rang-1];

			rang=Math.ceil(3*eff_utile/4);
			q3=tab_valeur[rang-1];

			document.getElementById('".$pref_id."q1').innerHTML=q1;
			document.getElementById('".$pref_id."q3').innerHTML=q3;
		}
		else {
			document.getElementById('".$pref_id."q1').innerHTML='-';
			document.getElementById('".$pref_id."q3').innerHTML='-';
		}

		document.getElementById('".$pref_id."min').innerHTML=tab_valeur[0];
		document.getElementById('".$pref_id."max').innerHTML=tab_valeur[n-1];
	}
	else {
		document.getElementById('".$pref_id."moyenne').innerHTML='-';
		document.getElementById('".$pref_id."mediane').innerHTML='-';
		document.getElementById('".$pref_id."q1').innerHTML='-';
		document.getElementById('".$pref_id."q3').innerHTML='-';
		document.getElementById('".$pref_id."min').innerHTML='-';
		document.getElementById('".$pref_id."max').innerHTML='-';
	}
}

setTimeout('calcul_moy_med_".$pref_id."()',1000);
</script>
";
}

// Fonction destin�e � s'assurer en cas d'acc�s professeur principal que l'examen ne concerne bien que la classe du prof
function is_pp_proprio_exb($id_exam) {
	$retour=true;

	$sql="SELECT * FROM ex_classes WHERE id_exam='$id_exam';";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		//$retour=false;

		while($lig=mysql_fetch_object($res)) {
			$sql="SELECT 1=1 FROM j_eleves_professeurs jep, j_eleves_classes jec WHERE jec.login=jep.login AND jep.id_classe=jec.id_classe AND jec.id_classe='$lig->id_classe';";
			$test=mysql_query($sql);
			if(mysql_num_rows($test)==0) {
				$retour=false;
				break;
			}
		}
	}

	return $retour;
}
?>
