<?php
	@set_time_limit(0);

	// $Id$

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

	if (!checkAccess()) {
		header("Location: ../logout.php?auto=1");
		die();
	}

	//**************** EN-TETE *****************
	$titre_page = "Outil d'initialisation de l'ann�e : Importation des �l�ves";
	require_once("../lib/header.inc");
	//**************** FIN EN-TETE *****************

	require_once("init_xml_lib.php");

	function extr_valeur($lig){
		unset($tabtmp);
		$tabtmp=explode(">",my_ereg_replace("<",">",$lig));
		return trim($tabtmp[2]);
	}

	function ouinon($nombre){
		if($nombre==1){return "O";}elseif($nombre==0){return "N";}else{return "";}
	}
	function sexeMF($nombre){
		//if($nombre==2){return "F";}else{return "M";}
		if($nombre==2){return "F";}elseif($nombre==1){return "M";}else{return "";}
	}

	function affiche_debug($texte){
		// Passer � 1 la variable pour g�n�rer l'affichage des infos de debug...
		$debug=0;
		if($debug==1){
			echo "<font color='green'>".$texte."</font>";
			flush();
		}
	}

	function maj_min_comp($chaine){
		$tmp_tab1=explode(" ",$chaine);
		$new_chaine="";
		for($i=0;$i<count($tmp_tab1);$i++){
			$tmp_tab2=explode("-",$tmp_tab1[$i]);
			$new_chaine.=ucfirst(strtolower($tmp_tab2[0]));
			for($j=1;$j<count($tmp_tab2);$j++){
				$new_chaine.="-".ucfirst(strtolower($tmp_tab2[$j]));
			}
			$new_chaine.=" ";
		}
		$new_chaine=trim($new_chaine);
		return $new_chaine;
	}


	// Etape...
	$step=isset($_POST['step']) ? $_POST['step'] : (isset($_GET['step']) ? $_GET['step'] : NULL);


	if(isset($_GET['ad_retour'])){
		$_SESSION['ad_retour']=$_GET['ad_retour'];
	}

	$mysql_collate=getSettingValue("mysql_collate") ? getSettingValue("mysql_collate") : "";
	$chaine_mysql_collate="";
	if($mysql_collate!="") {$chaine_mysql_collate="COLLATE $mysql_collate";}

	//echo "\$_SESSION['ad_retour']=".$_SESSION['ad_retour']."<br />";

	unset($remarques);
	$remarques=array();


	// On va uploader les fichiers XML dans le tempdir de l'utilisateur (administrateur, ou scolarit� pour les m�j Sconet)
	$tempdir=get_user_temp_directory();
	if(!$tempdir){
		echo "<p style='color:red'>Il semble que le dossier temporaire de l'utilisateur ".$_SESSION['login']." ne soit pas d�fini!?</p>\n";
		// Il ne faut pas aller plus loin...
		// SITUATION A GERER
	}


	// =======================================================
	// EST-CE ENCORE UTILE?
	if(isset($_GET['nettoyage'])) {
		check_token(false);
		//echo "<h1 align='center'>Suppression des CSV</h1>\n";
		echo "<h2>Suppression des XML</h2>\n";
		echo "<p class=bold><a href='";
		if(isset($_SESSION['ad_retour'])){
			echo $_SESSION['ad_retour'];
		}
		else{
			echo "index.php";
		}
		echo "'> <img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>";
		echo "<a href='".$_SERVER['PHP_SELF']."'> | Autre import</a></p>\n";
		//echo "</div>\n";

		echo "<p>Si des fichiers XML existent, ils seront supprim�s...</p>\n";
		//$tabfich=array("f_ele.csv","f_ere.csv");
		$tabfich=array("eleves.xml","nomenclature.xml");

		for($i=0;$i<count($tabfich);$i++){
			if(file_exists("../temp/".$tempdir."/$tabfich[$i]")) {
				echo "<p>Suppression de $tabfich[$i]... ";
				if(unlink("../temp/".$tempdir."/$tabfich[$i]")){
					echo "r�ussie.</p>\n";
				}
				else{
					echo "<font color='red'>Echec!</font> V�rifiez les droits d'�criture sur le serveur.</p>\n";
				}
			}
		}

		require("../lib/footer.inc.php");
		die();
	}
	// =======================================================
	else{
		echo "<center><h3 class='gepi'>Premi�re phase d'initialisation<br />Pr�paration des donn�es �l�ves/classes/p�riodes/options</h3></center>\n";
		//echo "<h2>Pr�paration des donn�es �l�ves/classes/p�riodes/options</h2>\n";
		echo "<p class=bold><a href='";
		if(isset($_SESSION['ad_retour'])){
			// On peut venir de l'index init_xml, de la page de conversion ou de la page de mise � jour Sconet
			echo $_SESSION['ad_retour'];
		}
		else{
			echo "index.php";
		}
		echo "'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>";

		//echo " | <a href='".$_SERVER['PHP_SELF']."'>Autre import</a>";
		echo " | <a href='".$_SERVER['PHP_SELF']."?nettoyage=oui".add_token_in_url()."'>Suppression des fichiers XML existants</a>";
		echo "</p>\n";
		//echo "</div>\n";

		//if(!isset($_POST['is_posted'])){
		if(!isset($step)) {
			echo "<p>Cette page permet de remplir des tables temporaires avec les informations �l�ves.<br />\n";
			echo "</p>\n";

			echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post'>\n";
			echo add_token_field();
			echo "<p>Veuillez fournir le fichier ElevesAvecAdresses.xml (<i>ou ElevesSansAdresses.xml</i>):<br />\n";
			echo "<input type=\"file\" size=\"65\" name=\"eleves_xml_file\" /><br />\n";
			if ($gepiSettings['unzipped_max_filesize']>=0) {
				echo "<p style=\"font-size:small; color: red;\"><i>REMARQUE&nbsp;:</i> Vous pouvez fournir � Gepi le fichier compress� issu directement de SCONET. (Ex : ElevesSansAdresses.zip)</p>";
			}
			echo "<input type='hidden' name='step' value='0' />\n";
			echo "<input type='hidden' name='is_posted' value='yes' />\n";
			echo "<p><input type='submit' value='Valider' /></p>\n";
			echo "</form>\n";
		}
		else{
			check_token(false);
			$post_max_size=ini_get('post_max_size');
			$upload_max_filesize=ini_get('upload_max_filesize');
			$max_execution_time=ini_get('max_execution_time');
			$memory_limit=ini_get('memory_limit');

			if($step==0){
				$xml_file = isset($_FILES["eleves_xml_file"]) ? $_FILES["eleves_xml_file"] : NULL;

				if(!is_uploaded_file($xml_file['tmp_name'])) {
					echo "<p style='color:red;'>L'upload du fichier a �chou�.</p>\n";

					echo "<p>Les variables du php.ini peuvent peut-�tre expliquer le probl�me:<br />\n";
					echo "post_max_size=$post_max_size<br />\n";
					echo "upload_max_filesize=$upload_max_filesize<br />\n";
					echo "</p>\n";

					// Il ne faut pas aller plus loin...
					// SITUATION A GERER
					require("../lib/footer.inc.php");
					die();
				}
				else{
					if(!file_exists($xml_file['tmp_name'])){
						echo "<p style='color:red;'>Le fichier aurait �t� upload�... mais ne serait pas pr�sent/conserv�.</p>\n";

						echo "<p>Les variables du php.ini peuvent peut-�tre expliquer le probl�me:<br />\n";
						echo "post_max_size=$post_max_size<br />\n";
						echo "upload_max_filesize=$upload_max_filesize<br />\n";
						echo "et le volume de ".$xml_file['name']." serait<br />\n";
						echo "\$xml_file['size']=".volume_human($xml_file['size'])."<br />\n";
						echo "</p>\n";
						// Il ne faut pas aller plus loin...
						// SITUATION A GERER
						require("../lib/footer.inc.php");
						die();
					}

					echo "<p>Le fichier a �t� upload�.</p>\n";

					/*
					echo "\$xml_file['tmp_name']=".$xml_file['tmp_name']."<br />\n";
					echo "\$tempdir=".$tempdir."<br />\n";

					echo "<p>Les variables du php.ini peuvent peut-�tre expliquer le probl�me:<br />\n";
					echo "post_max_size=$post_max_size<br />\n";
					echo "upload_max_filesize=$upload_max_filesize<br />\n";
					echo "\$xml_file['size']=".volume_human($xml_file['size'])."<br />\n";
					echo "</p>\n";
					*/

					//$source_file=stripslashes($xml_file['tmp_name']);
					$source_file=$xml_file['tmp_name'];
					$dest_file="../temp/".$tempdir."/eleves.xml";
					$res_copy=copy("$source_file" , "$dest_file");

					//===============================================================
					// ajout prise en compte des fichiers ZIP: Marc Leygnac

					$unzipped_max_filesize=getSettingValue('unzipped_max_filesize')*1024*1024;
					// $unzipped_max_filesize = 0    pas de limite de taille pour les fichiers extraits
					// $unzipped_max_filesize < 0    extraction zip d�sactiv�e
					if($unzipped_max_filesize>=0) {
						$fichier_emis=$xml_file['name'];
						$extension_fichier_emis=strtolower(strrchr($fichier_emis,"."));
						if (($extension_fichier_emis==".zip")||($xml_file['type']=="application/zip"))
							{
							require_once('../lib/pclzip.lib.php');
							$archive = new PclZip($dest_file);

							if (($list_file_zip = $archive->listContent()) == 0) {
								echo "<p style='color:red;'>Erreur : ".$archive->errorInfo(true)."</p>\n";
								require("../lib/footer.inc.php");
								die();
							}

							if(sizeof($list_file_zip)!=1) {
								echo "<p style='color:red;'>Erreur : L'archive contient plus d'un fichier.</p>\n";
								require("../lib/footer.inc.php");
								die();
							}

							/*
							echo "<p>\$list_file_zip[0]['filename']=".$list_file_zip[0]['filename']."<br />\n";
							echo "\$list_file_zip[0]['size']=".$list_file_zip[0]['size']."<br />\n";
							echo "\$list_file_zip[0]['compressed_size']=".$list_file_zip[0]['compressed_size']."</p>\n";
							*/
							//echo "<p>\$unzipped_max_filesize=".$unzipped_max_filesize."</p>\n";

							if(($list_file_zip[0]['size']>$unzipped_max_filesize)&&($unzipped_max_filesize>0)) {
								echo "<p style='color:red;'>Erreur : La taille du fichier extrait (<i>".$list_file_zip[0]['size']." octets</i>) d�passe la limite param�tr�e (<i>$unzipped_max_filesize octets</i>).</p>\n";
								require("../lib/footer.inc.php");
								die();
							}

							//unlink("$dest_file"); // Pour Wamp...
							$res_extract=$archive->extract(PCLZIP_OPT_PATH, "../temp/".$tempdir);
							if ($res_extract != 0) {
								echo "<p>Le fichier upload� a �t� d�zipp�.</p>\n";
								$fichier_extrait=$res_extract[0]['filename'];
								unlink("$dest_file"); // Pour Wamp...
								$res_copy=rename("$fichier_extrait" , "$dest_file");
							}
							else {
								echo "<p style='color:red'>Echec de l'extraction de l'archive ZIP.</p>\n";
								require("../lib/footer.inc.php");
								die();
							}
						}
					}
					//fin  ajout prise en compte des fichiers ZIP
					//===============================================================

					if(!$res_copy){
						echo "<p style='color:red;'>La copie du fichier vers le dossier temporaire a �chou�.<br />V�rifiez que l'utilisateur ou le groupe apache ou www-data a acc�s au dossier temp/$tempdir</p>\n";
						// Il ne faut pas aller plus loin...
						// SITUATION A GERER
						require("../lib/footer.inc.php");
						die();
					}
					else{
						echo "<p>La copie du fichier vers le dossier temporaire a r�ussi.</p>\n";

						$sql="DROP TABLE IF EXISTS temp_gep_import2;";
						$suppr_table = mysql_query($sql);

						$sql="CREATE TABLE IF NOT EXISTS `temp_gep_import2` (
						`ID_TEMPO` varchar(40) NOT NULL default '',
						`LOGIN` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELENOM` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEPRE` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELESEXE` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEDATNAIS` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELENOET` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELE_ID` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEDOUBL` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELENONAT` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEREG` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`DIVCOD` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ETOCOD_EP` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT1` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT2` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT3` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT4` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT5` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT6` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT7` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT8` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT9` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT10` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT11` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`ELEOPT12` varchar(40) $chaine_mysql_collate NOT NULL default '',
						`LIEU_NAISSANCE` varchar(50) $chaine_mysql_collate NOT NULL default ''
						);";
						$create_table = mysql_query($sql);


						$sql="TRUNCATE TABLE temp_gep_import2;";
						$vide_table = mysql_query($sql);

						$sql="CREATE TABLE IF NOT EXISTS temp_grp (
						id smallint(6) unsigned NOT NULL auto_increment, 
						ELE_ID varchar(40) NOT NULL default '',
						NOM_GRP varchar(255) NOT NULL default '',
						PRIMARY KEY id (id));";
						$create_table = mysql_query($sql);

						$sql="TRUNCATE TABLE temp_grp;";
						$vide_table = mysql_query($sql);

						// On va lire plusieurs fois le fichier pour remplir des tables temporaires.

						$ele_xml=simplexml_load_file($dest_file);
						if(!$ele_xml) {
							echo "<p style='color:red;'>ECHEC du chargement du fichier avec simpleXML.</p>\n";
							require("../lib/footer.inc.php");
							die();
						}

						$nom_racine=$ele_xml->getName();
						if(strtoupper($nom_racine)!='BEE_ELEVES') {
							echo "<p style='color:red;'>ERREUR: Le fichier XML fourni n'a pas l'air d'�tre un fichier XML El�ves.<br />Sa racine devrait �tre 'BEE_ELEVES'.</p>\n";
							require("../lib/footer.inc.php");
							die();
						}

						echo "<p>\n";
						echo "Analyse du fichier pour extraire les informations de la section STRUCTURES pour ne conserver que les identifiants d'�l�ves affect�s dans une classe...<br />\n";

						$tab_champs_struct=array("CODE_STRUCTURE","TYPE_STRUCTURE");
						$tab_ele_id=array();

						$i=-1;
						$objet_structures=($ele_xml->DONNEES->STRUCTURES);
						foreach ($objet_structures->children() as $structures_eleve) {
							//echo("<p><b>Structure</b><br />");
					
							$chaine_structures_eleve="STRUCTURES_ELEVE";
							foreach($structures_eleve->attributes() as $key => $value) {
								//echo("$key=".$value."<br />");

								if(strtoupper($key)=='ELEVE_ID') {
									// On teste si l'ELEVE_ID existe d�j�: �a ne devrait pas arriver
									if(in_array($value,$tab_ele_id)) {
										echo "<b style='color:red;'>ANOMALIE&nbsp;:</b> Il semble qu'il y a plusieurs sections STRUCTURES_ELEVE pour l'ELEVE_ID '$value'.<br />";
									}
									else {
										$i++;
										$eleves[$i]=array();

										$eleves[$i]['eleve_id']=$value;

										$eleves[$i]["structures"]=array();
										$j=0;
										//foreach($objet_structures->STRUCTURES_ELEVE->children() as $structure) {
										foreach($structures_eleve->children() as $structure) {
											$eleves[$i]["structures"][$j]=array();
											foreach($structure->children() as $key => $value) {
												if(in_array(strtoupper($key),$tab_champs_struct)) {
													$eleves[$i]["structures"][$j][strtolower($key)]=preg_replace('/"/','',trim(traite_utf8($value)));
													//echo("\$structure->$key=".$value."<br />)";
												}
											}
											$j++;
										}
			
										if($debug_import=='y') {
											echo "<pre style='color:green;'><b>Tableau \$eleves[$i]&nbsp;:</b>";
											print_r($eleves[$i]);
											echo "</pre>";
										}
									}
								}
							}
						}

						//if(count($eleves)==0) {
						if(!isset($eleves)) {
							echo "<p style='color:red'>Les classes d'affectation des �l�ves ne sont pas d�finies dans le fichier XML.<br />Votre secr�taire n'a pas encore remont� cette information dans Sconet... ou bien la remont�e n'est pas encore prise en compte dans les XML.<br />Pendant un temps, la saisie n'�tait prise en compte dans les XML que le lendemain de la saisie.</p>\n";
							require("../lib/footer.inc.php");
							die();
						}

						$nb_err=0;
						// $cpt: Identifiant id_tempo
						$id_tempo=1;
						for($i=0;$i<count($eleves);$i++){

							$temoin_div_trouvee="";
							if(isset($eleves[$i]["structures"])){
								if(count($eleves[$i]["structures"])>0){
									for($j=0;$j<count($eleves[$i]["structures"]);$j++){
										if($eleves[$i]["structures"][$j]["type_structure"]=="D"){
											$temoin_div_trouvee="oui";
											//break;

											$eleves[$i]["classe"]=$eleves[$i]["structures"][$j]["code_structure"];
										}
										elseif($eleves[$i]["structures"][$j]["type_structure"]=="G") {
											$sql="INSERT INTO temp_grp SET ele_id='".$eleves[$i]['eleve_id']."', nom_grp='".addslashes($eleves[$i]["structures"][$j]["code_structure"])."';";
											$insert_assoc_grp=mysql_query($sql);
										}
									}
									/*
									if($temoin_div_trouvee!=""){
										$eleves[$i]["classe"]=$eleves[$i]["structures"][$j]["code_structure"];
									}
									*/
								}
							}

							if($temoin_div_trouvee=='oui'){
								$sql="INSERT INTO temp_gep_import2 SET id_tempo='$id_tempo', ";
								$sql.="ele_id='".$eleves[$i]['eleve_id']."', ";
								$sql.="divcod='".$eleves[$i]['classe']."';";
								//echo "$sql<br />\n";
								$res_insert=mysql_query($sql);
								if(!$res_insert){
									echo "Erreur lors de la requ�te $sql<br />\n";
									$nb_err++;
								}
								$id_tempo++;
							}
						}
						if($nb_err==0) {
							echo "<p>La premi�re phase s'est pass�e sans erreur.</p>\n";
						}
						elseif($nb_err==1) {
							echo "<p>$nb_err erreur.</p>\n";
						}
						else{
							echo "<p>$nb_err erreurs</p>\n";
						}

						$stat=$id_tempo-1-$nb_err;
						echo "<p>$stat associations identifiant �l�ve/classe ont �t� ins�r�(s) dans la table 'temp_gep_import2'.</p>\n";

						//echo "<p><a href='".$_SERVER['PHP_SELF']."?etape=1&amp;step=1'>Suite</a></p>\n";
						echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."?step=1".add_token_in_url()."'>Suite</a></p>\n";

						require("../lib/footer.inc.php");
						die();
					}
				}
			} // Fin du $step=0
			elseif($step==1){
				$dest_file="../temp/".$tempdir."/eleves.xml";

				$sql="CREATE TABLE IF NOT EXISTS `temp_etab_import` (
												`id` char(8) NOT NULL default '',
												`nom` char(50) NOT NULL default '',
												`niveau` char(50) NOT NULL default '',
												`type` char(50) NOT NULL default '',
												`cp` int(10) NOT NULL default '0',
												`ville` char(50) NOT NULL default '',
												PRIMARY KEY  (`id`)
												);";
				$create_table = mysql_query($sql);

				$sql="TRUNCATE TABLE temp_etab_import;";
				$vide_table = mysql_query($sql);




				// On r�cup�re les ele_id des �l�ves qui sont affect�s dans une classe
				$sql="SELECT ele_id FROM temp_gep_import2 ORDER BY id_tempo";
				$res_ele_id=mysql_query($sql);
				affiche_debug("count(\$res_ele_id)=".count($res_ele_id)."<br />");

				unset($tab_ele_id);
				$tab_ele_id=array();
				$cpt=0;
				// Pourquoi est-ce que cela ne fonctionne pas en mysql_fetch_object()???
				// TROUV�: C'EST SENSIBLE � LA CASSE: IL FAUDRAIT $lig->ELE_ID
				//while($lig=mysql_fetch_object($res_ele_id)){
				while($lig=mysql_fetch_array($res_ele_id)){
					//$tab_ele_id[$cpt]="$lig->ele_id";
					$tab_ele_id[$cpt]=$lig[0];
					affiche_debug("\$tab_ele_id[$cpt]=$tab_ele_id[$cpt]<br />");
					$cpt++;
				}

				echo "<p>Analyse du fichier pour extraire les informations de la section ELEVES...<br />\n";
				//echo "<blockquote>\n";

				$cpt=0;
				$eleves=array();

				//Compteur �l�ve:
				$i=-1;

				$tab_champs_eleve=array("ID_NATIONAL",
				"ELENOET",
				"NOM",
				"PRENOM",
				"DATE_NAISS",
				"DOUBLEMENT",
				"DATE_SORTIE",
				"CODE_REGIME",
				"DATE_ENTREE",
				"CODE_MOTIF_SORTIE",
				"CODE_SEXE",
				"CODE_COMMUNE_INSEE_NAISS"
				);

				$tab_champs_scol_an_dernier=array("CODE_STRUCTURE",
				"CODE_RNE",
				"SIGLE",
				"DENOM_PRINC",
				"DENOM_COMPL",
				"LIGNE1_ADRESSE",
				"LIGNE2_ADRESSE",
				"LIGNE3_ADRESSE",
				"LIGNE4_ADRESSE",
				"BOITE_POSTALE",
				"MEL",
				"TELEPHONE",
				"CODE_COMMUNE_INSEE",
				"LL_COMMUNE_INSEE"
				);

				$avec_scolarite_an_dernier="y";

				$ele_xml=simplexml_load_file($dest_file);
				if(!$ele_xml) {
					echo "<p style='color:red;'>ECHEC du chargement du fichier avec simpleXML.</p>\n";
					require("../lib/footer.inc.php");
					die();
				}

				$nom_racine=$ele_xml->getName();
				if(strtoupper($nom_racine)!='BEE_ELEVES') {
					echo "<p style='color:red;'>ERREUR: Le fichier XML fourni n'a pas l'air d'�tre un fichier XML El�ves.<br />Sa racine devrait �tre 'BEE_ELEVES'.</p>\n";
					require("../lib/footer.inc.php");
					die();
				}

				//$indice_from_eleve_id=array();

				$objet_eleves=($ele_xml->DONNEES->ELEVES);
				foreach ($objet_eleves->children() as $eleve) {
					$i++;
					//echo "<p><b>El�ve $i</b><br />";
			
					$eleves[$i]=array();
			
					foreach($eleve->attributes() as $key => $value) {
						//echo "$key=".$value."<br />";
			
						$eleves[$i][strtolower($key)]=trim(traite_utf8($value));
						/*
						if(strtoupper($key)=='ELEVE_ID') {
							$indice_from_eleve_id["$value"]=$i;
						}
						elseif(strtoupper($key)=='ELENOET') {
							$indice_from_elenoet["$value"]=$i;
						}
						*/
					}

					foreach($eleve->children() as $key => $value) {
						if(in_array(strtoupper($key),$tab_champs_eleve)) {
							$eleves[$i][strtolower($key)]=preg_replace('/"/','',trim(traite_utf8($value)));
							//echo "\$eleve->$key=".$value."<br />";
						}

						if(($avec_scolarite_an_dernier=='y')&&(strtoupper($key)=='SCOLARITE_AN_DERNIER')) {
							$eleves[$i]["scolarite_an_dernier"]=array();
			
							foreach($eleve->SCOLARITE_AN_DERNIER->children() as $key2 => $value2) {
								//echo "\$eleve->SCOLARITE_AN_DERNIER->$key2=$value2<br />";
								if(in_array(strtoupper($key2),$tab_champs_scol_an_dernier)) {
									$eleves[$i]["scolarite_an_dernier"][strtolower($key2)]=preg_replace('/"/','',trim(traite_utf8($value2)));
								}
							}
						}
					}

					if(isset($eleves[$i]["date_naiss"])){
						//echo $eleves[$i]["date_naiss"]."<br />\n";
						unset($naissance);
						$naissance=explode("/",$eleves[$i]["date_naiss"]);
						//$eleve_naissance_annee=$naissance[2];
						//$eleve_naissance_mois=$naissance[1];
						//$eleve_naissance_jour=$naissance[0];
						if(isset($naissance[2])){
							$eleve_naissance_annee=$naissance[2];
						}
						else{
							$eleve_naissance_annee="";
						}
						if(isset($naissance[1])){
							$eleve_naissance_mois=$naissance[1];
						}
						else{
							$eleve_naissance_mois="";
						}
						if(isset($naissance[0])){
							$eleve_naissance_jour=$naissance[0];
						}
						else{
							$eleve_naissance_jour="";
						}

						$eleves[$i]["date_naiss"]=$eleve_naissance_annee.$eleve_naissance_mois.$eleve_naissance_jour;
					}

					if($debug_import=='y') {
						echo "<pre style='color:green;'><b>Tableau \$eleves[$i]&nbsp;:</b>";
						print_r($eleves[$i]);
						echo "</pre>";
					}
				}

				affiche_debug("count(\$eleves)=".count($eleves)."<br />\n");
				affiche_debug("count(\$tab_ele_id)=".count($tab_ele_id)."<br />\n");
				$stat=0;
				$nb_err=0;
				$stat_etab=0;
				$nb_err_etab=0;
				unset($tab_list_etab);
				$tab_list_etab=array();
				$info_anomalie="";
				for($i=0;$i<count($eleves);$i++){
					// On ne traite que les �l�ves affect�s dans une classe ($tab_ele_id)
					if(in_array($eleves[$i]['eleve_id'],$tab_ele_id)){
						/*
						if(!isset($eleves[$i]["code_sexe"])){
							$remarques[]="Le sexe de l'�l�ve <a href='#sexe_manquant_".$i."'>".$eleves[$i]["nom"]." ".$eleves[$i]["prenom"]."</a> n'est pas renseign� dans Sconet.";
						}
						*/

						$temoin_date_sortie="n";
						if(isset($eleves[$i]['date_sortie'])) {
							echo $eleves[$i]['prenom']." ".$eleves[$i]['nom']." a quitt� l'�tablissement le ".$eleves[$i]['date_sortie']."<br />\n";

							$tmp_tab_date=explode("/",$eleves[$i]['date_sortie']);
							if(checkdate($tmp_tab_date[1],$tmp_tab_date[0],$tmp_tab_date[2])) {
								$timestamp_sortie=mktime(0,0,0,$tmp_tab_date[1],$tmp_tab_date[0],$tmp_tab_date[2]);
								$timestamp_instant=time();
								if($timestamp_instant>$timestamp_sortie){
									$temoin_date_sortie="y";
								}
							}
						}

						if($temoin_date_sortie=="y") {
							$sql="DELETE FROM temp_gep_import2 WHERE ele_id='".$eleves[$i]['eleve_id']."';";
							$nettoyage=mysql_query($sql);
						}
						else {

							$sql="UPDATE temp_gep_import2 SET ";
							$sql.="elenoet='".$eleves[$i]['elenoet']."', ";
							if(isset($eleves[$i]['id_national'])) {$sql.="elenonat='".$eleves[$i]['id_national']."', ";}
							$sql.="elenom='".addslashes($eleves[$i]['nom'])."', ";
							$sql.="elepre='".addslashes($eleves[$i]['prenom'])."', ";
							if(!isset($eleves[$i]["code_sexe"])) {
								$eleves[$i]["code_sexe"]=1;
								$info_anomalie.="Le sexe de ".$eleves[$i]['nom']." ".$eleves[$i]['prenom']." n'est pas renseign� dans le fichier XML.<br />\n";
							}
							$sql.="elesexe='".sexeMF($eleves[$i]["code_sexe"])."', ";
							$sql.="eledatnais='".$eleves[$i]['date_naiss']."', ";
							$sql.="eledoubl='".ouinon($eleves[$i]["doublement"])."', ";
							if(isset($eleves[$i]["scolarite_an_dernier"]["code_rne"])){$sql.="etocod_ep='".$eleves[$i]["scolarite_an_dernier"]["code_rne"]."', ";}
							if(isset($eleves[$i]["code_regime"])){$sql.="elereg='".$eleves[$i]["code_regime"]."', ";}

							if(isset($eleves[$i]["code_commune_insee_naiss"])){$sql.="lieu_naissance='".$eleves[$i]["code_commune_insee_naiss"]."', ";}

							$sql=substr($sql,0,strlen($sql)-2);
							$sql.=" WHERE ele_id='".$eleves[$i]['eleve_id']."';";
							affiche_debug("$sql<br />\n");
							$res_insert=mysql_query($sql);
							if(!$res_insert){
								echo "Erreur lors de la requ�te $sql<br />\n";
								$nb_err++;
								flush();
							}
							else{
								$stat++;
							}


							// Insertion des informations de l'�tablissement pr�c�dent dans une table temporaire:
							if(isset($eleves[$i]["scolarite_an_dernier"]["code_rne"])){
								$sql="INSERT INTO temp_etab_import SET ";
								$cpt_debut_requete=0;


								if($eleves[$i]["scolarite_an_dernier"]["code_rne"]!=""){

									// Renseigner un tableau pour indiquer que c'est un RNE d�j� trait�... et tester le contenu du tableau
									if(!in_array($eleves[$i]["scolarite_an_dernier"]["code_rne"],$tab_list_etab)){
										$tab_list_etab[]=$eleves[$i]["scolarite_an_dernier"]["code_rne"];

										if(isset($eleves[$i]["scolarite_an_dernier"]["code_rne"])){
											$sql.="id='".addslashes($eleves[$i]["scolarite_an_dernier"]["code_rne"])."'";
											$cpt_debut_requete++;
										}

										// NIVEAU
										$chaine="";
										if(isset($eleves[$i]["scolarite_an_dernier"]["denom_princ"])){
											if(my_ereg("ECOLE",$eleves[$i]["scolarite_an_dernier"]["denom_princ"])){
												$chaine="ecole";
											}
											elseif(my_ereg("COLLEGE",$eleves[$i]["scolarite_an_dernier"]["denom_princ"])){
												$chaine="college";
											}
											elseif(my_ereg("LYCEE",$eleves[$i]["scolarite_an_dernier"]["denom_princ"])){
												if(my_ereg("PROF",$eleves[$i]["scolarite_an_dernier"]["denom_princ"])){
													$chaine="lprof";
												}
												else{
													$chaine="lycee";
												}
											}
											else{
												$chaine="";
											}

											if($cpt_debut_requete>0){
												$sql.=", ";
											}
											$sql.="niveau='".$chaine."'";
											$cpt_debut_requete++;
										}


										// NOM
										if(isset($eleves[$i]["scolarite_an_dernier"]["denom_compl"])){
											if($cpt_debut_requete>0){
												$sql.=", ";
											}
											$nom_etab=trim(maj_min_comp($eleves[$i]["scolarite_an_dernier"]["denom_compl"]));
											if($nom_etab=="") {
												$nom_etab=ucfirst(strtolower($chaine));
											}
											//$sql.="nom='".addslashes(maj_min_comp($eleves[$i]["scolarite_an_dernier"]["denom_compl"]))."'";
											$sql.="nom='".addslashes($nom_etab)."'";
											$cpt_debut_requete++;
										}
										else{
											$sql.=", ";
											$nom_etab=ucfirst(strtolower($chaine));
											$sql.="nom='".addslashes($nom_etab)."'";
											$cpt_debut_requete++;
										}


										// TYPE
										if(isset($eleves[$i]["scolarite_an_dernier"]["denom_princ"])){
											if(my_ereg("PRIVE",$eleves[$i]["scolarite_an_dernier"]["denom_princ"])){
												$chaine="prive";
											}
											else{
												$chaine="public";
											}

											if($cpt_debut_requete>0){
												$sql.=", ";
											}
											$sql.="type='".$chaine."'";
											$cpt_debut_requete++;
										}

										// CODE POSTAL: Non pr�sent dans le fichier ElevesSansAdresses.xml
										//              Ca y est, il a �t� ajout�.
										// ***************************************
										// ERREUR: code_commune_insee!=code_postal
										// ***************************************
										// Il faudrait le fichier Communes.xml ou quelque chose de ce genre.
										if(isset($eleves[$i]["scolarite_an_dernier"]["code_commune_insee"])){
											if($cpt_debut_requete>0){
												$sql.=", ";
											}
											// *****************************************
											// PROBLEME: code_commune_insee!=code_postal
											// *****************************************
											$sql.="cp='".addslashes(maj_min_comp($eleves[$i]["scolarite_an_dernier"]["code_commune_insee"]))."'";
											$cpt_debut_requete++;
										}

										// COMMUNE
										if(isset($eleves[$i]["scolarite_an_dernier"]["ll_commune_insee"])){
											if($cpt_debut_requete>0){
												$sql.=", ";
											}
											$sql.="ville='".addslashes(maj_min_comp($eleves[$i]["scolarite_an_dernier"]["ll_commune_insee"]))."'";
											$cpt_debut_requete++;
										}

										//echo "$sql<br />";

										$res_insert_etab=mysql_query($sql);
										if(!$res_insert_etab){
											echo "Erreur lors de la requ�te $sql<br />\n";
											$nb_err_etab++;
											flush();
										}
										else{
											$stat_etab++;
										}
									}
								}
							}
						}
					}
				}
				if($nb_err==0) {
					echo "<p>La deuxi�me phase s'est pass�e sans erreur.</p>\n";
				}
				elseif($nb_err==1) {
					echo "<p>$nb_err erreur.</p>\n";
				}
				else{
					echo "<p>$nb_err erreurs</p>\n";
				}

				if($info_anomalie!='') {
					echo "<p style='color:red;'>$info_anomalie</p>\n";
				}

				echo "<p>$stat enregistrement(s) ont �t� mis � jour dans la table 'temp_gep_import2'.</p>\n";

				//echo "<p><a href='".$_SERVER['PHP_SELF']."?etape=1&amp;step=2'>Suite</a></p>\n";
				echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."?step=2".add_token_in_url()."'>Suite</a></p>\n";

				require("../lib/footer.inc.php");
				die();
			} // Fin du $step=1
			elseif($step==2){
				$dest_file="../temp/".$tempdir."/eleves.xml";

				// On r�cup�re les ele_id des �l�ves qui sont affect�s dans une classe
				$sql="SELECT ele_id FROM temp_gep_import2 ORDER BY id_tempo";
				$res_ele_id=mysql_query($sql);
				//echo "count(\$res_ele_id)=".count($res_ele_id)."<br />";

				unset($tab_ele_id);
				$tab_ele_id=array();
				$cpt=0;
				// Pourquoi est-ce que cela ne fonctionne pas en mysql_fetch_object()???
				// TROUV�: C'EST SENSIBLE � LA CASSE: IL FAUDRAIT $lig->ELE_ID
				//while($lig=mysql_fetch_object($res_ele_id)){
				while($lig=mysql_fetch_array($res_ele_id)){
					//$tab_ele_id[$cpt]="$lig->ele_id";
					$tab_ele_id[$cpt]=$lig[0];
					affiche_debug("\$tab_ele_id[$cpt]=$tab_ele_id[$cpt]<br />");
					$cpt++;
				}

				/*
				echo "<p>Lecture du fichier El�ves...<br />\n";
				//echo "<blockquote>\n";
				while(!feof($fp)){
					$ligne[]=fgets($fp,4096);
				}
				fclose($fp);
				//echo "<p>Termin�.</p>\n";
				*/
				flush();

				echo "<p>";
				echo "Analyse du fichier pour extraire les informations de la section OPTIONS...<br />\n";
				//echo "<blockquote>\n";

				$ele_xml=simplexml_load_file($dest_file);
				if(!$ele_xml) {
					echo "<p style='color:red;'>ECHEC du chargement du fichier avec simpleXML.</p>\n";
					require("../lib/footer.inc.php");
					die();
				}

				$nom_racine=$ele_xml->getName();
				if(strtoupper($nom_racine)!='BEE_ELEVES') {
					echo "<p style='color:red;'>ERREUR: Le fichier XML fourni n'a pas l'air d'�tre un fichier XML El�ves.<br />Sa racine devrait �tre 'BEE_ELEVES'.</p>\n";
					require("../lib/footer.inc.php");
					die();
				}

				$tab_champs_opt=array("NUM_OPTION","CODE_MODALITE_ELECT","CODE_MATIERE");

				$i=-1;

				// PARTIE <OPTIONS>
				$objet_options=($ele_xml->DONNEES->OPTIONS);
				foreach ($objet_options->children() as $option) {
					// $option est un <OPTION ELEVE_ID="145778" ELENOET="2643">
					//echo "<p><b>Option</b><br />";

					$i++;
					//echo "<p><b>El�ve $i</b><br />";
			
					$eleves[$i]=array();
			
					foreach($option->attributes() as $key => $value) {
						//echo "$key=".$value."<br />";
						$eleves[$i][strtolower($key)]=trim(traite_utf8($value));
					}

					$eleves[$i]["options"]=array();
					$j=0;
					// $option fait r�f�rence � un �l�ve
					// Les enfants sont des OPTIONS_ELEVE
					foreach($option->children() as $options_eleve) {
						foreach($options_eleve->children() as $key => $value) {
							// Les enfants indiquent NUM_OPTION, CODE_MODALITE_ELECT, CODE_MATIERE
							if(in_array(strtoupper($key),$tab_champs_opt)) {
								$eleves[$i]["options"][$j][strtolower($key)]=preg_replace('/"/','',trim(traite_utf8($value)));
								//echo "\$eleve->$key=".$value."<br />";
								//echo "\$eleves[$i][\"options\"][$j][".strtolower($key)."]=".$value."<br />";
							}
						}
						$j++;
					}
		
					if($debug_import=='y') {
						echo "<pre style='color:green;'><b>Tableau \$eleves[$i]&nbsp;:</b>";
						print_r($eleves[$i]);
						echo "</pre>";
					}
				}

				// Insertion des codes num�riques d'options
				$nb_err=0;
				$stat=0;
				for($i=0;$i<count($eleves);$i++){
					if(in_array($eleves[$i]['eleve_id'],$tab_ele_id)){
						for($j=0;$j<count($eleves[$i]["options"]);$j++){
							$k=$j+1;
							$sql="UPDATE temp_gep_import2 SET ";
							$sql.="eleopt$k='".$eleves[$i]["options"][$j]['code_matiere']."'";
							$sql.=" WHERE ele_id='".$eleves[$i]['eleve_id']."';";
							affiche_debug("$sql<br />\n");
							$res_update=mysql_query($sql);
							if(!$res_update){
								echo "Erreur lors de la requ�te $sql<br />\n";
								flush();
								$nb_err++;
							}
							else{
								$stat++;
							}
						}
					}
				}
				if($nb_err==0) {
					echo "<p>La troisi�me phase s'est pass�e sans erreur.</p>\n";
				}
				elseif($nb_err==1) {
					echo "<p>$nb_err erreur.</p>\n";
				}
				else{
					echo "<p>$nb_err erreurs</p>\n";
				}

				echo "<p>$stat option(s) ont �t� mises � jour dans la table 'temp_gep_import2'.</p>\n";

				//echo "<p><a href='".$_SERVER['PHP_SELF']."?etape=1&amp;step=3'>Suite</a></p>\n";
				echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."?step=3".add_token_in_url()."'>Suite</a></p>\n";

				require("../lib/footer.inc.php");
				die();
			}
			elseif($step==3){

				echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post'>\n";
				echo add_token_field();
				echo "<p>Les codes num�riques des options doivent maintenant �tre traduits en leurs �quivalents alphab�tiques (<i>ex.: 030201 -&gt; AGL1</i>).</p>\n";
				echo "<p>Veuillez fournir le fichier Nomenclature.xml:<br />\n";
				echo "<input type=\"file\" size=\"65\" name=\"nomenclature_xml_file\" /></p>\n";
				if ($gepiSettings['unzipped_max_filesize']>=0) {
					echo "<p style=\"font-size:small; color: red;\"><i>REMARQUE&nbsp;:</i> Vous pouvez fournir � Gepi le fichier compress� issu directement de SCONET. (Ex : Nomenclature.zip)</p>";
				}
				//echo "<input type='hidden' name='etape' value='$etape' />\n";
				echo "<input type='hidden' name='step' value='4' />\n";
				echo "<input type='hidden' name='is_posted' value='yes' />\n";
				//echo "</p>\n";
				echo "<p><input type='submit' value='Valider' /></p>\n";
				echo "</form>\n";
				require("../lib/footer.inc.php");
				die();
			}
			elseif($step==4){
				$xml_file = isset($_FILES["nomenclature_xml_file"]) ? $_FILES["nomenclature_xml_file"] : NULL;

				if(!is_uploaded_file($xml_file['tmp_name'])) {
					echo "<p style='color:red;'>L'upload du fichier a �chou�.</p>\n";

					echo "<p>Les variables du php.ini peuvent peut-�tre expliquer le probl�me:<br />\n";
					echo "post_max_size=$post_max_size<br />\n";
					echo "upload_max_filesize=$upload_max_filesize<br />\n";
					echo "</p>\n";

					// Il ne faut pas aller plus loin...
					// SITUATION A GERER
					require("../lib/footer.inc.php");
					die();
				}
				else{
					if(!file_exists($xml_file['tmp_name'])){
						echo "<p style='color:red;'>Le fichier aurait �t� upload�... mais ne serait pas pr�sent/conserv�.</p>\n";

						echo "<p>Les variables du php.ini peuvent peut-�tre expliquer le probl�me:<br />\n";
						echo "post_max_size=$post_max_size<br />\n";
						echo "upload_max_filesize=$upload_max_filesize<br />\n";
						echo "et le volume de ".$xml_file['name']." serait<br />\n";
						echo "\$xml_file['size']=".volume_human($xml_file['size'])."<br />\n";
						echo "</p>\n";
						// Il ne faut pas aller plus loin...
						// SITUATION A GERER
						require("../lib/footer.inc.php");
						die();
					}

					echo "<p>Le fichier a �t� upload�.</p>\n";

					/*
					echo "\$xml_file['tmp_name']=".$xml_file['tmp_name']."<br />\n";
					echo "\$tempdir=".$tempdir."<br />\n";

					echo "<p>Les variables du php.ini peuvent peut-�tre expliquer le probl�me:<br />\n";
					echo "post_max_size=$post_max_size<br />\n";
					echo "upload_max_filesize=$upload_max_filesize<br />\n";
					echo "\$xml_file['size']=".volume_human($xml_file['size'])."<br />\n";
					echo "</p>\n";
					*/

					//$source_file=stripslashes($xml_file['tmp_name']);
					$source_file=$xml_file['tmp_name'];
					$dest_file="../temp/".$tempdir."/nomenclature.xml";
					$res_copy=copy("$source_file" , "$dest_file");

					//===============================================================
					// ajout prise en compte des fichiers ZIP: Marc Leygnac

					$unzipped_max_filesize=getSettingValue('unzipped_max_filesize')*1024*1024;
					// $unzipped_max_filesize = 0    pas de limite de taille pour les fichiers extraits
					// $unzipped_max_filesize < 0    extraction zip d�sactiv�e
					if($unzipped_max_filesize>=0) {
						$fichier_emis=$xml_file['name'];
						$extension_fichier_emis=strtolower(strrchr($fichier_emis,"."));
						if (($extension_fichier_emis==".zip")||($xml_file['type']=="application/zip"))
							{
							require_once('../lib/pclzip.lib.php');
							$archive = new PclZip($dest_file);

							if (($list_file_zip = $archive->listContent()) == 0) {
								echo "<p style='color:red;'>Erreur : ".$archive->errorInfo(true)."</p>\n";
								require("../lib/footer.inc.php");
								die();
							}

							if(sizeof($list_file_zip)!=1) {
								echo "<p style='color:red;'>Erreur : L'archive contient plus d'un fichier.</p>\n";
								require("../lib/footer.inc.php");
								die();
							}

							/*
							echo "<p>\$list_file_zip[0]['filename']=".$list_file_zip[0]['filename']."<br />\n";
							echo "\$list_file_zip[0]['size']=".$list_file_zip[0]['size']."<br />\n";
							echo "\$list_file_zip[0]['compressed_size']=".$list_file_zip[0]['compressed_size']."</p>\n";
							*/
							//echo "<p>\$unzipped_max_filesize=".$unzipped_max_filesize."</p>\n";

							if(($list_file_zip[0]['size']>$unzipped_max_filesize)&&($unzipped_max_filesize>0)) {
								echo "<p style='color:red;'>Erreur : La taille du fichier extrait (<i>".$list_file_zip[0]['size']." octets</i>) d�passe la limite param�tr�e (<i>$unzipped_max_filesize octets</i>).</p>\n";
								require("../lib/footer.inc.php");
								die();
							}

							$res_extract=$archive->extract(PCLZIP_OPT_PATH, "../temp/".$tempdir);
							if ($res_extract != 0) {
								echo "<p>Le fichier upload� a �t� d�zipp�.</p>\n";
								$fichier_extrait=$res_extract[0]['filename'];
								unlink("$dest_file"); // Pour Wamp...
								$res_copy=rename("$fichier_extrait" , "$dest_file");
							}
							else {
								echo "<p style='color:red'>Echec de l'extraction de l'archive ZIP.</p>\n";
								require("../lib/footer.inc.php");
								die();
							}
						}
					}
					//fin  ajout prise en compte des fichiers ZIP
					//===============================================================

					if(!$res_copy){
						echo "<p style='color:red;'>La copie du fichier vers le dossier temporaire a �chou�.<br />V�rifiez que l'utilisateur ou le groupe apache ou www-data a acc�s au dossier temp/$tempdir</p>\n";
						// Il ne faut pas aller plus loin...
						// SITUATION A GERER
						require("../lib/footer.inc.php");
						die();
					}
					else{
						// Lecture du fichier Nomenclature... pour changer les codes num�riques d'options dans 'temp_gep_import2' en leur code gestion

						$dest_file="../temp/".$tempdir."/nomenclature.xml";

						$nomenclature_xml=simplexml_load_file($dest_file);
						if(!$nomenclature_xml) {
							echo "<p style='color:red;'>ECHEC du chargement du fichier avec simpleXML.</p>\n";
							require("../lib/footer.inc.php");
							die();
						}
	
						$nom_racine=$nomenclature_xml->getName();
						if(strtoupper($nom_racine)!='BEE_NOMENCLATURES') {
							echo "<p style='color:red;'>ERREUR: Le fichier XML fourni n'a pas l'air d'�tre un fichier XML Nomenclatures.<br />Sa racine devrait �tre 'BEE_NOMENCLATURES'.</p>\n";
							require("../lib/footer.inc.php");
							die();
						}

						$tab_champs_matiere=array("CODE_GESTION",
						"LIBELLE_COURT",
						"LIBELLE_LONG",
						"LIBELLE_EDITION",
						"MATIERE_ETP"
						);

						echo "<p>";
						echo "Analyse du fichier pour extraire les associations CODE_MATIERE/CODE_GESTION...<br />\n";
	
						$matieres=array();
						$i=-1;

						$objet_matieres=($nomenclature_xml->DONNEES->MATIERES);
						foreach ($objet_matieres->children() as $matiere) {
							$i++;
							//echo "<p><b>Mati�re $i</b><br />";
					
							$matieres[$i]=array();
					
							foreach($matiere->attributes() as $key => $value) {
								// <MATIERE CODE_MATIERE="001400">
								//echo "$key=".$value."<br />";
					
								$matieres[$i][strtolower($key)]=trim(traite_utf8($value));
							}
	
							foreach($matiere->children() as $key => $value) {
								if(in_array(strtoupper($key),$tab_champs_matiere)) {
									$matieres[$i][strtolower($key)]=preg_replace('/"/','',trim(traite_utf8($value)));
									//echo "\$matiere->$key=".$value."<br />";
								}
							}
						}

						$sql="SELECT * FROM temp_gep_import2";
						$res1=mysql_query($sql);

						if(mysql_num_rows($res1)==0) {
							echo "<p>La table 'temp_gep_import2' est vide.<br />Ce n'est pas normal.<br />Auriez-vous saut� des �tapes???</p>\n";
							require("../lib/footer.inc.php");
							die();
						}

						$stat=0;
						$nb_err=0;
						while($lig=mysql_fetch_object($res1)){
							//echo "<p>";
							//echo "$lig->nom $lig->prenom: ";
							//echo $lig->ELENOM." ".$lig->ELEPRE.": ";
							//echo "$lig->ELEOPT1, ";
							//echo "$lig->ELEOPT2, ";
							//echo "$lig->ELEOPT3, ";

							// T�moin pour tester si une option au moins doit �tre corrig�e:
							$temoin=0;
							$sql="UPDATE temp_gep_import2 SET ";
							for($i=1;$i<12;$i++){
								$eleopt="ELEOPT$i";
								if($lig->$eleopt!=''){
									//if($i>1){
									//	echo ", ";
									//}
									//echo $lig->$eleopt;

									$option="";
									for($k=0;$k<count($matieres);$k++) {
										if($matieres[$k]["code_matiere"]==$lig->$eleopt) {
											$option=$matieres[$k]["code_gestion"];
											break;
										}
									}

									if($option!=""){
										$sql.="$eleopt='$option', ";
										$temoin++;
									}
								}
							}

							if($temoin>0){
								$sql=substr($sql,0,strlen($sql)-2);
								$sql.=" WHERE ele_id='$lig->ELE_ID';";
								affiche_debug($sql."<br />\n");
								$res2=mysql_query($sql);
								if(!$res2){
									echo "Erreur lors de la requ�te $sql<br />\n";
									flush();
									$nb_err++;
								}
								else{
									$stat++;
								}
							}
							//echo "</p>\n";
						}

						if($nb_err==0) {
							echo "<p>La quatri�me phase s'est pass�e sans erreur.</p>\n";
						}
						elseif($nb_err==1) {
							echo "<p>$nb_err erreur.</p>\n";
						}
						else{
							echo "<p>$nb_err erreurs</p>\n";
						}

						echo "<p>$stat enregistrement(s) ont �t� mis � jour dans la table 'temp_gep_import2'.</p>\n";

						//echo "<p><a href='".$_SERVER['PHP_SELF']."?etape=1&amp;step=5'>Suite</a></p>\n";

						// PROBLEME AVEC LE code_commune_insee != code_postal
						// On saute l'�tape de remplissage de l'�tablissment pr�c�dent...
						//echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."?step=5'>Suite</a></p>\n";
						// NON... trait� autrement sans modifier la table 'etablissements'
						//echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."?step=5'>Suite</a></p>\n";
						// SI: L'association �l�ve/rne est faite en step3.php
						echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."?step=7".add_token_in_url()."'>Suite</a></p>\n";

						require("../lib/footer.inc.php");
						die();
					}
				}
			}
			elseif($step==5){

				echo "<p>Etablissements d'origine des �l�ves.</p>\n";



				$sql="SELECT * FROM temp_etab_import ORDER BY ville,nom";
				$res_etab=mysql_query($sql);
				if(mysql_num_rows($res_etab)==0){
					echo "<p>Aucun �tablissement pr�c�dent n'a �t� trouv�.</p>\n";

					echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."?step=7".add_token_in_url()."'>Suite</a></p>\n";
				}
				else{
					echo "<form enctype='multipart/form-data' action='".$_SERVER['PHP_SELF']."' method='post'>\n";
					echo add_token_field();

					echo "<table class='boireaus' summary='Tableau des �tablissements'>\n";
					echo "<tr>\n";
					echo "<th>\n";
					echo "<a href='javascript:modif_case(true)'><img src='../images/enabled.png' width='15' height='15' alt='Tout cocher' /></a>/\n";
					echo "<a href='javascript:modif_case(false)'><img src='../images/disabled.png' width='15' height='15' alt='Tout d�cocher' /></a>\n";
					echo "</th>\n";
					echo "<th>Statut</th>\n";
					echo "<th>RNE</th>\n";
					echo "<th>Nom</th>\n";
					echo "<th>Niveau</th>\n";
					echo "<th>Type</th>\n";
					echo "<th>Code postal</th>\n";
					echo "<th>Commune</th>\n";
					echo "</tr>\n";
					$alt=1;
					$nombre_ligne=0;
					while($lig=mysql_fetch_object($res_etab)){
						$alt=$alt*(-1);
						$sql="SELECT * FROM etablissements WHERE id='$lig->id'";
						$res_etab2=mysql_query($sql);
						if(mysql_num_rows($res_etab2)==0){
							// Nouvelle entr�e

							// #AAE6AA; vert
							// #FAFABE; jaune
							// #96C8F0; bleu

							//echo "<tr style='background-color: #96C8F0;'>\n";
							echo "<tr class='lig$alt'>\n";
							echo "<td class='nouveau'>\n";
							echo "<input type='checkbox' id='case$nombre_ligne' name='rne[]' value='$lig->id' />\n";
							echo "</td>\n";

							echo "<td class='nouveau'>\n";
							echo "Nouveau\n";
							echo "</td>\n";

							echo "<td>\n";
							echo "$lig->id\n";
							echo "</td>\n";

							echo "<td>\n";
							echo "$lig->nom\n";
							echo "</td>\n";

							echo "<td>\n";
							echo "$lig->niveau\n";
							echo "</td>\n";

							echo "<td>\n";
							echo "$lig->type\n";
							echo "</td>\n";

							// *****************************************
							// PROBLEME: code_commune_insee!=code_postal
							// *****************************************
							echo "<td>\n";
							//echo "$lig->cp\n";
							echo "&nbsp;\n";
							echo "</td>\n";

							echo "<td>\n";
							echo "$lig->ville\n";
							echo "</td>\n";

							echo "</tr>\n";

						}
						else{
							// Entr�e existante
							$lig2=mysql_fetch_object($res_etab2);

							//echo "<tr style='background-color: #AAE6AA;'>\n";
							echo "<tr class='lig$alt'>\n";
							echo "<td class='modif'>\n";
							echo "<input type='checkbox' id='case$nombre_ligne' name='rne_modif[]' value='$lig->id' />\n";
							echo "</td>\n";

							echo "<td class='modif'>\n";
							echo "Modification\n";
							echo "</td>\n";

							echo "<td>\n";
							echo "$lig->id\n";
							echo "</td>\n";

							echo "<td>\n";
							if($lig->nom!=$lig2->nom){
								echo "$lig2->nom";
								echo " <span style='color:red'>-&gt;</span> ";
							}
							echo "$lig->nom\n";
							echo "</td>\n";

							echo "<td>\n";
							if($lig->niveau!=$lig2->niveau){
								echo "$lig2->niveau";
								echo " <span style='color:red'>-&gt;</span> ";
							}
							echo "$lig->niveau\n";
							echo "</td>\n";

							echo "<td>\n";
							if($lig->type!=$lig2->type){
								echo "$lig2->type";
								echo " <span style='color:red'>-&gt;</span> ";
							}
							echo "$lig->type\n";
							echo "</td>\n";

							echo "<td>\n";
							// *****************************************
							// PROBLEME: code_commune_insee!=code_postal
							// *****************************************
							echo "<span style='color:red'>$lig2->cp</span>";
							/*
							if($lig->cp!=$lig2->cp){
								echo "$lig2->cp";
								echo " <span style='color:red'>-&gt;</span> ";
							}
							echo "$lig->cp\n";
							*/
							echo "</td>\n";

							echo "<td>\n";
							if($lig->ville!=$lig2->ville){
								echo "$lig2->ville";
								echo " <span style='color:red'>-&gt;</span> ";
							}
							echo "$lig->ville\n";
							echo "</td>\n";

							echo "</tr>\n";
						}
						$nombre_ligne++;
					}
					echo "</table>\n";

					echo "<input type='hidden' name='step' value='6' />\n";
					echo "<input type='hidden' name='is_posted' value='yes' />\n";
					echo "<p><input type='submit' value='Valider' /></p>\n";
					echo "</form>\n";

					echo "<p><i>NOTE:</i> Les fichiers de SCONET contiennent le code_commune_insee, mais pas le code_postal (<i>les valeurs diff�rent</i>).<br />\n";
					echo "Les �tablissements ne sont donc pas import�s (<i>pour �viter des insertions erron�es</i>).<br />\n";
					echo "Seules les associations �l�ve/RNE sont import�es.</p>\n";


					echo "<script type='text/javascript' language='javascript'>
	function modif_case(statut){
		// statut: true ou false
		for(k=0;k<$nombre_ligne;k++){
			if(document.getElementById('case'+k)){
				document.getElementById('case'+k).checked=statut;
			}
		}
		changement();
	}
</script>\n";

				}


				require("../lib/footer.inc.php");
				die();
			}
			elseif($step==6){

				echo "<p>Etablissements d'origine des �l�ves.</p>\n";

				$rne=isset($_POST['rne']) ? $_POST['rne'] : NULL;
				$rne_modif=isset($_POST['rne_modif']) ? $_POST['rne_modif'] : NULL;

				$nb_err=0;
				$stat=0;
				if(isset($rne)){
					for($i=0;$i<count($rne);$i++){
						$sql="INSERT INTO etablissements SELECT id,nom,niveau,type,cp,ville FROM temp_etab_import WHERE temp_etab_import.id='".$rne[$i]."'";
						//echo "$sql<br />";
						$res=mysql_query($sql);

						if(!$res){
							echo "Erreur lors de la requ�te $sql<br />\n";
							flush();
							$nb_err++;
						}
						else{
							$stat++;
						}
					}
				}

				if($nb_err>0){
					echo "<p>$nb_err erreur(s) lors de l'insertion des nouveaux �tablissements.</p>\n";
				}
				if($stat>0){
					if($stat==1){
						echo "<p>$stat nouvel �tablissement ajout�.</p>\n";
					}
					else{
						echo "<p>$stat nouveaux �tablissements ajout�s.</p>\n";
					}
				}

				$nb_err=0;
				$stat=0;
				if(isset($rne_modif)){
					for($i=0;$i<count($rne_modif);$i++){
						$sql="DELETE FROM etablissements WHERE id='".$rne_modif[$i]."'";
						//echo "$sql<br />";
						$res=mysql_query($sql);

						$sql="INSERT INTO etablissements SELECT id,nom,niveau,type,cp,ville FROM temp_etab_import WHERE temp_etab_import.id='".$rne_modif[$i]."'";
						//echo "$sql<br />";
						$res=mysql_query($sql);

						if(!$res){
							echo "Erreur lors de la requ�te $sql<br />\n";
							flush();
							$nb_err++;
						}
						else{
							$stat++;
						}
					}
				}

				if($nb_err>0){
					echo "<p>$nb_err erreur(s) lors de la modification des �tablissements.</p>\n";
				}
				if($stat>0){
					if($stat==1){
						echo "<p>$stat modification d'�tablissement effectu�e.</p>\n";
					}
					else{
						echo "<p>$stat modifications d'�tablissements effectu�es.</p>\n";
					}
				}

				echo "<p align='center'><a href='".$_SERVER['PHP_SELF']."?step=7".add_token_in_url()."'>Suite</a></p>\n";

				require("../lib/footer.inc.php");
				die();
			}
			else{
				// TERMIN�?
				// A LA DERNIERE ETAPE, IL FAUT SUPPRIMER LE FICHIER "../temp/".$tempdir."/eleves.xml"

				if(file_exists("../temp/".$tempdir."/eleves.xml")) {
					echo "<p>Suppression de eleves.xml... ";
					if(unlink("../temp/".$tempdir."/eleves.xml")){
						echo "r�ussie.</p>\n";
					}
					else{
						echo "<font color='red'>Echec!</font> V�rifiez les droits d'�criture sur le serveur.</p>\n";
					}
				}

				if(file_exists("../temp/".$tempdir."/nomenclature.xml")) {
					echo "<p>Suppression de nomenclature.xml... ";
					if(unlink("../temp/".$tempdir."/nomenclature.xml")){
						echo "r�ussie.</p>\n";
					}
					else{
						echo "<font color='red'>Echec!</font> V�rifiez les droits d'�criture sur le serveur.</p>\n";
					}
				}

				echo "<p>Suite: <a href='step2.php'>Classes et p�riodes</a></p>\n";

				require("../lib/footer.inc.php");
				die();
			}



			/*
			if(count($remarques)>0){
				echo "<a name='remarques'></a><h3>Remarques</h3>\n";
				if(count($remarques)==1){
					echo "<p>Une anomalie a �t� not�e lors du parcours de vos fichiers:</p>\n";
				}
				else{
					echo "<p>Des anomalies ont �t� not�es lors du parcours de vos fichiers:</p>\n";
				}
				echo "<ul>\n";
				for($i=0;$i<count($remarques);$i++){
					echo "<li>".$remarques[$i]."</li>\n";
				}
				echo "</ul>\n";
			}
			*/
		}
	}
	require("../lib/footer.inc.php");
?>