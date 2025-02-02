<?php
/**
 * Mise � jour des bases vers la version 1.5.0
 * 
 * $Id: maj.php 7839 2011-08-20 08:17:28Z dblanqui $
 *
 * @copyright Copyright 2001, 2011 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
 * @license GNU/GPL,
 * @package General
 * @subpackage mise_a jour
 * @see msj_ok()
 * @see msj_erreur()
 * @see msj_present()
 */
		$result .= "<h3 class='titreMaJ'>Mise � jour vers la version 1.5.0" . $rc . $beta . " :</h3>";
		$result .= "<p>";
		$result .= "&nbsp;->Extension de la taille du champ NAME de la table 'setting'<br />";
		$query28 = mysql_query("ALTER TABLE setting CHANGE NAME NAME VARCHAR( 255 ) NOT NULL");
		if ($query28) {
			$result .= msj_ok();
		} else {
			$result .= msj_erreur();
		}

		$result .= "&nbsp;->Ajout du champ responsable � la table droits<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM droits LIKE 'responsable'"));
		if ($test1 == 0) {
			$query5 = mysql_query("ALTER TABLE `droits` ADD `responsable` varchar(1) NOT NULL DEFAULT 'F' AFTER `eleve`");
			if ($query5) {
				$result .= msj_ok();

				foreach ($droits_requests as $key => $value) {
					$exec = traite_requete($value);
				}
			} else {
				$result .= msj_erreur('(le champ existe d�j� ?)');
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}


		$result .= "&nbsp;->Ajout du champ 'email' � la table 'eleves'<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM eleves LIKE 'email'"));
		if ($test1 == 0) {
			$query5 = mysql_query("ALTER TABLE `eleves` ADD `email` varchar(255) NOT NULL");
			if ($query5) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur('!');
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}

		$result .= "&nbsp;->Ajout (si besoin) de param�tres par d�faut pour les acc�s �l�ves et parents<br/>";
		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesReleveEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesReleveEleve', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesCahierTexteEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesCahierTexteEleve', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesReleveParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesReleveParent', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesCahierTexteParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesCahierTexteParent', 'yes');");

		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';


		$result .= "&nbsp;->Ajout (si besoin) du param�tre autorisant l'utilisation de l'outil de r�cup�ration de mot de passe<br/>";
		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'enable_password_recovery'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('enable_password_recovery', 'no');");

		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';


		$result .= "&nbsp;->Ajout du champ password_ticket � la table utilisateurs<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM utilisateurs LIKE 'password_ticket'"));
		if ($test1 == 0) {
			$query5 = mysql_query("ALTER TABLE `utilisateurs` ADD `password_ticket` varchar(255) NOT NULL AFTER `date_verrouillage`");
			if ($query5) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur('!');
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}

		$result .= "&nbsp;->Ajout du champ ticket_expiration � la table utilisateurs<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM utilisateurs LIKE 'ticket_expiration'"));
		if ($test1 == 0) {
			$query5 = mysql_query("ALTER TABLE `utilisateurs` ADD `ticket_expiration` datetime NOT NULL AFTER `password_ticket`");
			if ($query5) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}

		$result .= "&nbsp;->Ajout (si besoin) de param�tres par d�faut pour les droits d'acc�s � la fonction de r�initialisation du mot de passe perdu<br/>";
		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiPasswordReinitProf'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiPasswordReinitProf', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiPasswordReinitScolarite'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiPasswordReinitScolarite', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiPasswordReinitCpe'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiPasswordReinitCpe', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiPasswordReinitAdmin'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiPasswordReinitAdmin', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiPasswordReinitEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiPasswordReinitEleve', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiPasswordReinitParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiPasswordReinitParent', 'yes');");


		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';

		$result .= "&nbsp;->Ajout (si besoin) du param�tre autorisant l'acc�s public aux cahiers de texte<br/>";
		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'cahier_texte_acces_public'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('cahier_texte_acces_public', 'no');");

		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';

		$result .= "&nbsp;->Ajout (si besoin) de param�tres par d�faut pour les droits d'acc�s � l'�quipe p�dagogique d'un �l�ve<br/>";
		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesEquipePedaEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesEquipePedaEleve', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesEquipePedaEmailEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesEquipePedaEmailEleve', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesCpePPEmailEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesCpePPEmailEleve', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesEquipePedaParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesEquipePedaParent', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesEquipePedaEmailParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesEquipePedaEmailParent', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesCpePPEmailParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesCpePPEmailParent', 'no');");

		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';

		$result .= "&nbsp;->Ajout (si besoin) de param�tres par d�faut pour les droits d'acc�s aux bulletins simplifi�s et relev�s de notes<br/>";
		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesBulletinSimpleEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesBulletinSimpleEleve', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesBulletinSimpleParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesBulletinSimpleParent', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesBulletinSimpleProf'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesBulletinSimpleProf', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesBulletinSimpleProfTousEleves'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesBulletinSimpleProfTousEleves', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesBulletinSimpleProfToutesClasses'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesBulletinSimpleProfToutesClasses', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesReleveProfTousEleves'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesReleveProfTousEleves', 'yes');");

		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';

		$result .= "&nbsp;->Ajout (si besoin) de param�tres par d�faut pour les droits d'acc�s aux moyennes par les professeurs<br/>";

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesMoyennesProf'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesMoyennesProf', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesMoyennesProfTousEleves'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesMoyennesProfTousEleves', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesMoyennesProfToutesClasses'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesMoyennesProfToutesClasses', 'yes');");

		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';

		$result .= "&nbsp;->Ajout (si besoin) de param�tres par d�faut pour les droits d'acc�s aux graphiques de visualisation (eleves et responsables)<br/>";
		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesGraphEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesGraphEleve', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'GepiAccesGraphParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('GepiAccesGraphParent', 'yes');");

		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';

		$result .= "&nbsp;->Ajout (si besoin) de param�tres par d�faut pour les fiches d'information destin�e aux nouveaux utilisateurs<br/>";
		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'ImpressionParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('ImpressionParent', '');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'ImpressionEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('ImpressionEleve', '');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'ImpressionNombre'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('ImpressionNombre', '1');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'ImpressionNombreParent'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('ImpressionNombreParent', '1');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'ImpressionNombreEleve'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('ImpressionNombreEleve', '1');");

		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';

		$result .= "&nbsp;->Ajout du champ show_email � la table utilisateurs<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM utilisateurs LIKE 'show_email'"));
		if ($test1 == 0) {
			$query5 = mysql_query("ALTER TABLE `utilisateurs` ADD `show_email` varchar(3) NOT NULL DEFAULT 'no' AFTER `email`");
			if ($query5) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}

		$result .= "&nbsp;->Ajout du champ ele_id � la table eleves<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM eleves LIKE 'ele_id'"));
		if ($test1 == 0) {
			$query5 = mysql_query("ALTER TABLE `eleves` ADD `ele_id` varchar(10) NOT NULL DEFAULT '' AFTER `ereno`");
			if ($query5) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}

		$req_test= mysql_query("SELECT VALUE FROM setting WHERE NAME = 'bull_categ_font_size_avis'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0){
			$query = mysql_query("INSERT INTO setting VALUES ('bull_categ_font_size_avis', '10');");
			$result .= "Initialisation du param�tre bull_categ_font_size_avis � '10': ";
			if($query){
				$result .= msj_ok();
			}
			else{
				$result .= msj_erreur('!');
			}
		}

		$req_test= mysql_query("SELECT VALUE FROM setting WHERE NAME = 'bull_police_avis'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0){
			$query = mysql_query("INSERT INTO setting VALUES ('bull_police_avis', 'Times New Roman');");
			$result .= "Initialisation du param�tre bull_police_avis � 'Times New Roman': ";
			if($query){
				$result .= msj_ok();
			}
			else{
				$result .= msj_erreur('!');
			}
		}

		$req_test= mysql_query("SELECT VALUE FROM setting WHERE NAME = 'bull_font_style_avis'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0){
			$query = mysql_query("INSERT INTO setting VALUES ('bull_font_style_avis', 'Normal');");
			$result .= "Initialisation du param�tre bull_font_style_avis � Normal: ";
			if($query){
				$result .= msj_ok();
			}
			else{
				$result .= msj_erreur('!');
			}
		}


		$result .= "&nbsp;->Cr�ation de la table responsables2<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'responsables2'"));
		if ($test1 == 0) {
			$query1 = mysql_query("CREATE TABLE IF NOT EXISTS `responsables2` (
`ele_id` varchar(10) NOT NULL,
`pers_id` varchar(10) NOT NULL,
`resp_legal` varchar(1) NOT NULL,
`pers_contact` varchar(1) NOT NULL
);");
			if ($query1) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('La table existe d�j�');
		}

		$result .= "&nbsp;->Cr�ation de la table resp_pers<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'resp_pers'"));
		if ($test1 == 0) {
			$query1 = mysql_query("CREATE TABLE IF NOT EXISTS `resp_pers` (
`pers_id` varchar(10) NOT NULL,
`login` varchar(50) NOT NULL,
`nom` varchar(30) NOT NULL,
`prenom` varchar(30) NOT NULL,
`civilite` varchar(5) NOT NULL,
`tel_pers` varchar(255) NOT NULL,
`tel_port` varchar(255) NOT NULL,
`tel_prof` varchar(255) NOT NULL,
`mel` varchar(100) NOT NULL,
`adr_id` varchar(10) NOT NULL,
PRIMARY KEY  (`pers_id`)
);");
			if ($query1) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('La table existe d�j�');
		}

		$result .= "&nbsp;->Cr�ation de la table resp_adr<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'resp_adr'"));
		if ($test1 == 0) {
			$query1 = mysql_query("CREATE TABLE IF NOT EXISTS `resp_adr` (
`adr_id` varchar(10) NOT NULL,
`adr1` varchar(100) NOT NULL,
`adr2` varchar(100) NOT NULL,
`adr3` varchar(100) NOT NULL,
`adr4` varchar(100) NOT NULL,
`cp` varchar(6) NOT NULL,
`pays` varchar(50) NOT NULL,
`commune` varchar(50) NOT NULL,
PRIMARY KEY  (`adr_id`)
);");
			if ($query1) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('La table existe d�j�');
		}


		$result .= "&nbsp;->Passage de 10 caract�res � 255 caract�res des champs tel_pers, tel_port et tel_prof de la table resp_pers.<br />";
		$alter1 = mysql_query("ALTER TABLE `resp_pers` CHANGE `tel_pers` `tel_pers` VARCHAR( 255 )");
		$result .= "tel_pers: ";
		if ($alter1) {
			$result .= msj_ok();
		} else {
			$result .= msj_erreur();
		}
		$alter2 = mysql_query("ALTER TABLE `resp_pers` CHANGE `tel_port` `tel_port` VARCHAR( 255 )");
		$result .= "tel_port: ";
		if ($alter2) {
			$result .= msj_ok();
		} else {
			$result .= msj_erreur();
		}
		$alter3 = mysql_query("ALTER TABLE `resp_pers` CHANGE `tel_prof` `tel_prof` VARCHAR( 255 )");
		$result .= "tel_prof: ";
		if ($alter3) {
			$result .= msj_ok();
		} else {
			$result .= msj_erreur();
		}


		// affectation des mod�les de bulletin  PDF aux classes
		$result .= "&nbsp;->Ajout du champs `modele_bulletin_pdf` � la table `classes`.<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM classes LIKE 'modele_bulletin_pdf'"));
		if ($test1 == 0) {
			$query5 = mysql_query("ALTER TABLE `classes` ADD `modele_bulletin_pdf` VARCHAR( 255 ) NULL AFTER `display_moy_gen`");
			if ($query5) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur('!');
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}


		$req_test= mysql_query("SELECT VALUE FROM setting WHERE NAME = 'option_modele_bulletin'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0){
			$query = mysql_query("INSERT INTO `setting` VALUES ('option_modele_bulletin', '2');;");
			$result .= "Initialisation du param�tre option_modele_bulletin � '2': ";
			if($query){
				$result .= msj_ok();
			}
			else{
				$result .= msj_erreur('!');
			}
		}

		$result .= "&nbsp;->Cr�ation de la table tentatives_intrusion<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'tentatives_intrusion'"));
		if ($test1 == 0) {
			$query1 = mysql_query("CREATE TABLE `tentatives_intrusion` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `login` VARCHAR( 255 ) NULL , `adresse_ip` VARCHAR( 255 ) NOT NULL , `date` DATETIME NOT NULL , `niveau` SMALLINT NOT NULL , `fichier` VARCHAR( 255 ) NOT NULL , `description` TEXT NOT NULL , `statut` VARCHAR( 255 ) NOT NULL , PRIMARY KEY ( `id`, `login` ))");
			if ($query1) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('La table existe d�j�');
		}

		$result .= "&nbsp;->Ajout du champs `niveau_alerte` � la table `utilisateurs`.<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM utilisateurs LIKE 'niveau_alerte'"));
		if ($test1 == 0) {
			$query5 = mysql_query("ALTER TABLE `utilisateurs` ADD `niveau_alerte` SMALLINT NOT NULL DEFAULT '0'");
			if ($query5) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur('!');
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}

		$result .= "&nbsp;->Ajout du champs `observation_securite` � la table `utilisateurs`.<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM utilisateurs LIKE 'observation_securite'"));
		if ($test1 == 0) {
			$query5 = mysql_query("ALTER TABLE `utilisateurs` ADD `observation_securite` TINYINT NOT NULL DEFAULT '0'");
			if ($query5) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur('!');
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}

		$result .= "&nbsp;->Ajout (si besoin) de param�tres par d�faut pour la d�finition de la politique de s�curit�<br/>";

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert_email_admin'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert_email_admin', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert_email_min_level'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert_email_min_level', '1');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert1_normal_cumulated_level'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert1_normal_cumulated_level', '3');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert1_normal_email_admin'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert1_normal_email_admin', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert1_normal_block_user'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert1_normal_block_user', 'no');");


		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert1_probation_cumulated_level'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert1_probation_cumulated_level', '2');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert1_probation_email_admin'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert1_probation_email_admin', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert1_probation_block_user'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert1_probation_block_user', 'no');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert2_normal_cumulated_level'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert2_normal_cumulated_level', '7');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert2_normal_email_admin'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert2_normal_email_admin', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert2_normal_block_user'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert2_normal_block_user', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert2_probation_cumulated_level'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert2_probation_cumulated_level', '5');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert2_probation_email_admin'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert2_probation_email_admin', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'security_alert2_probation_block_user'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('security_alert2_probation_block_user', 'yes');");

		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'deverouillage_auto_periode_suivante'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('deverouillage_auto_periode_suivante', 'n');");

		// Ajout Mod_absences
		$result .= "&nbsp;->Cr�ation de la table vs_alerts_eleves<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'vs_alerts_eleves'"));
		if ($test1 == 0) {
			$query1 = mysql_query("CREATE TABLE `vs_alerts_eleves` (
				  `id_alert_eleve` int(11) NOT NULL auto_increment,
				  `eleve_alert_eleve` varchar(100) NOT NULL,
				  `date_alert_eleve` date NOT NULL,
				  `groupe_alert_eleve` int(11) NOT NULL,
				  `type_alert_eleve` int(11) NOT NULL,
				  `nb_trouve` int(11) NOT NULL,
				  `temp_insert` varchar(100) NOT NULL,
				  `etat_alert_eleve` tinyint(4) NOT NULL,
				  `etatpar_alert_eleve` varchar(100) NOT NULL,
				  PRIMARY KEY  (`id_alert_eleve`)
				  );");
			if ($query1) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('La table existe d�j�');
		}

		$result .= "&nbsp;->Cr�ation de la table vs_alerts_groupes<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'vs_alerts_groupes'"));
		if ($test1 == 0) {
			$query1 = mysql_query("CREATE TABLE `vs_alerts_groupes` (
				  `id_alert_groupe` int(11) NOT NULL auto_increment,
				  `nom_alert_groupe` varchar(150) NOT NULL,
				  `creerpar_alert_groupe` varchar(100) NOT NULL,
				  PRIMARY KEY  (`id_alert_groupe`)
				  );");
			if ($query1) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('La table existe d�j�');
		}

		$result .= "&nbsp;->Cr�ation de la table vs_alerts_types<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'vs_alerts_types'"));
		if ($test1 == 0) {
			$query1 = mysql_query("CREATE TABLE `vs_alerts_types` (
				  `id_alert_type` int(11) NOT NULL auto_increment,
				  `groupe_alert_type` int(11) NOT NULL,
				  `type_alert_type` varchar(10) NOT NULL,
				  `specifisite_alert_type` varchar(25) NOT NULL,
				  `eleve_concerne` text NOT NULL,
				  `date_debut_comptage` date NOT NULL,
				  `nb_comptage_limit` varchar(200) NOT NULL,
				  PRIMARY KEY  (`id_alert_type`)
				  );");
			if ($query1) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('La table existe d�j�');
		}
		// Fin Ajout Mod_absences


		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';

		$result .= "&nbsp;->Ajout (si besoin) du param�tre s�lectionnant la feuille de style � utiliser<br/>";
		$req_test = mysql_query("SELECT VALUE FROM setting WHERE NAME = 'gepi_stylesheet'");
		$res_test = mysql_num_rows($req_test);
		if ($res_test == 0)
		$result_inter .= traite_requete("INSERT INTO setting VALUES ('gepi_stylesheet', 'style');");

		if ($result_inter == '') {
			$result .= msj_ok();
		} else {
			$result .= $result_inter;
		}
		$result_inter = '';


		$result .= "&nbsp;->Ajout du champ temp_dir � la table utilisateurs<br />";
		$test1 = mysql_num_rows(mysql_query("SHOW COLUMNS FROM utilisateurs LIKE 'temp_dir'"));
		if ($test1 == 0) {
			$query3 = mysql_query("ALTER TABLE `utilisateurs` ADD `temp_dir` VARCHAR( 255 ) NOT NULL AFTER `observation_securite`");
			if ($query3) {
				$result .= msj_ok();
			} else {
				$result .= msj_erreur();
			}
		} else {
			$result .= msj_present('Le champ existe d�j�');
		}
        
        $result .= "</p>";
		
?>
