INSERT INTO utilisateurs SET login = 'ADMIN', nom = 'GEPI', prenom = 'Administrateur', civilite = 'M.', password = 'ab4f63f9ac65152575886860dde480a1', statut = 'administrateur', etat = 'actif', change_mdp = 'y';
INSERT INTO setting VALUES ('version', '1.5.0');
INSERT INTO setting VALUES ('versionRc', '');
INSERT INTO setting VALUES ('versionBeta', '1');
INSERT INTO setting VALUES ('sessionMaxLength', '30');
INSERT INTO setting VALUES ('Impression','<center><p class = \"grand\">Gestion des El�ves Par Internet</p></center>\r\n<br />\r\n<p class = \"grand\">Qu\'est-ce que GEPI ?</p>\r\n\r\n<p>Afin d\'�tudier les modalit�s d\'informatisation des bulletins scolaires : notes et appr�ciations via Internet, une exp�rimentation (baptis�e Gestion des El�ves Par Internet)a �t� mise en place. Cette exp�rimentation concerne les classes suivantes : \r\n<br />* ....\r\n<br />* ....\r\n<br />\r\n<br />\r\nCeci vous concerne car vous �tes professeur enseignant dans l\'une ou l\'autre de ces classes.\r\n<br />\r\n<br />\r\nA partir de la r�ception de ce document, vous pourrez remplir les bulletins informatis�s :\r\n<span class = \"norme\">\r\n<UL><li>soit au lyc�e � partir de n\'importe quel poste connect� � Internet,\r\n<li>soit chez vous si vous disposez d\'une connexion Internet.\r\n</ul>\r\n</span>\r\n<p class = \"grand\">Comment acc�der au module de saisie (notes etappr�ciations) :</p>\r\n<span class = \"norme\">\r\n<UL>\r\n    <LI>Se connecter � Internet\r\n    <LI>Lancer un navigateur (FireFox de pr�f�rence, Opera, Internet Explorer, ...)\r\n    <LI>Se connecter au site : https://adresse_du_site/gepi\r\n    <LI>Apr�s quelques instants une page appara�t vous invitant � entrer un nom d\'identifiant et un mot de passe (cesinformations figurent en haut de cette page).\r\n    <br />ATTENTION : votre mot de passe est strictement confidentiel.\r\n    <br />\r\n    <br />Une fois ces informations fournies, cliquez sur le bouton \"Ok\".\r\n    <LI> Apr�s quelques instants une page d\'accueil appara�t.<br />\r\nLa premi�re fois, Gepi vous demande de changer votre mot de passe.\r\nChoisissez-en un facile � retenir, mais non trivial (�vitez toute date\r\nde naissance, nom d\'animal familier, pr�nom, etc.), et contenant\r\nlettre(s), chiffre(s), et caract�re(s) non alphanum�rique(s).<br />\r\nLes fois suivantes, vous arriverez directement au menu g�n�ral de\r\nl\'application. Pour bien prendre connaissance des possibilit�s de\r\nl\'application, n\'h�sitez pas � essayer tous les liens disponibles !\r\n</ul></span>\r\n<p class = \"grand\">Remarque :</p>\r\n<p>GEPI est pr�vu pour que chaque professeur ne puisse modifier les notes ou les appr�ciations que dans les rubriques qui le concernent et uniquement pour ses �l�ves.\r\n<br />\r\nJe reste � votre disposition pour tout renseignement compl�mentaire.\r\n    <br />\r\n    Le proviseur adjoint\r\n</p>');
INSERT INTO setting VALUES ('gepiYear', '2006/2007');
INSERT INTO setting VALUES ('gepiSchoolName', 'Nom du Lyc�e');
INSERT INTO setting VALUES ('gepiSchoolAdress1', 'Adresse');
INSERT INTO setting VALUES ('gepiSchoolAdress2', 'Bo�te postale');
INSERT INTO setting VALUES ('gepiSchoolZipCode', 'Code postal');
INSERT INTO setting VALUES ('gepiSchoolCity', 'Ville');
INSERT INTO setting VALUES ('gepiAdminAdress', 'email.admin@gepi.fr');
INSERT INTO setting VALUES ('titlesize', '14');
INSERT INTO setting VALUES ('textsize', '8');
INSERT INTO setting VALUES ('cellpadding', '3');
INSERT INTO setting VALUES ('cellspacing', '1');
INSERT INTO setting VALUES ('largeurtableau', '800');
INSERT INTO setting VALUES ('col_matiere_largeur', '150');
INSERT INTO setting VALUES ('begin_bookings', '1157058000');
INSERT INTO setting VALUES ('end_bookings', '1188594000');
INSERT INTO setting VALUES ('max_size', '307200');
INSERT INTO setting VALUES ('total_max_size', '5242880');
INSERT INTO setting VALUES ('col_note_largeur', '30');
INSERT INTO setting VALUES ('active_cahiers_texte', 'y');
INSERT INTO setting VALUES ('active_carnets_notes', 'y');
INSERT INTO setting VALUES ('active_observatoire', 'n');
INSERT INTO setting VALUES ('logo_etab', 'logo.gif');
INSERT INTO setting VALUES ('longmin_pwd', '5');
INSERT INTO setting VALUES ('duree_conservation_logs', '365');
INSERT INTO setting VALUES ('GepiRubConseilProf', 'yes');
INSERT INTO setting VALUES ('GepiRubConseilScol', 'yes');
INSERT INTO setting VALUES ('bull_ecart_entete', '0');
INSERT INTO setting VALUES ('gepi_prof_suivi', 'professeur principal');
INSERT INTO setting VALUES ('GepiProfImprBul', 'no');
INSERT INTO setting VALUES ('GepiProfImprBulSettings', 'no');
INSERT INTO setting VALUES ('GepiScolImprBulSettings', 'yes');
INSERT INTO setting VALUES ('GepiAdminImprBulSettings', 'no');
INSERT INTO setting VALUES ('GepiAccesReleveScol', 'yes');
INSERT INTO setting VALUES ('GepiAccesReleveCpe', 'no');
INSERT INTO setting VALUES ('GepiAccesReleveProf', 'no');
INSERT INTO setting VALUES ('GepiAccesReleveProfTousEleves', 'no');
INSERT INTO setting VALUES ('GepiAccesReleveProfP', 'yes');
INSERT INTO setting VALUES ('page_garde_imprime', 'no');
INSERT INTO setting VALUES ('page_garde_texte', 'Madame, Monsieur<br/><br/>Veuillez trouvez ci-joint le bulletin scolaire de votre enfant. Nous vous rappelons que la journ&eacute;e <span style="font-weight: bold;">Portes ouvertes</span> du Lyc&eacute;e aura lieu samedi 20 mai entre 10 h et 17 h.<br/><br/>Veuillez agr&eacute;er, Madame, Monsieur, l''expression de mes meilleurs sentiments.<br/><br/><div style="text-align: right;">Le proviseur</div>');
INSERT INTO setting VALUES ('page_garde_padding_top', '4');
INSERT INTO setting VALUES ('page_garde_padding_left', '11');
INSERT INTO setting VALUES ('page_garde_padding_text', '6');
INSERT INTO setting VALUES ('addressblock_padding_top', '40');
INSERT INTO setting VALUES ('addressblock_padding_right', '20');
INSERT INTO setting VALUES ('addressblock_padding_text', '20');
INSERT INTO setting VALUES ('addressblock_length', '60');
INSERT INTO setting VALUES ('bull_espace_avis', '5');
INSERT INTO setting VALUES ('change_ordre_aff_matieres', 'ok');
INSERT INTO setting VALUES ('disable_login', 'no');
INSERT INTO setting VALUES ('bull_formule_bas', 'Bulletin � conserver pr�cieusement. Aucun duplicata ne sera d�livr�. - GEPI : solution libre de gestion et de suivi des r�sultats scolaires.');
INSERT INTO setting VALUES ('delai_devoirs', '7');
INSERT INTO setting VALUES ('active_module_absence', 'y');
INSERT INTO setting VALUES ('active_module_absence_professeur', 'y');
INSERT INTO setting VALUES ('gepiSchoolTel', '00 00 00 00 00');
INSERT INTO setting VALUES ('gepiSchoolFax', '00 00 00 00 00');
INSERT INTO setting VALUES ('gepiSchoolEmail', 'ce.XXXXXXXX@ac-xxxxx.fr');
INSERT INTO setting VALUES ('col_boite_largeur', '120');
INSERT INTO setting VALUES ('bull_mention_doublant', 'no');
INSERT INTO setting VALUES ('bull_affiche_numero', 'no');
INSERT INTO setting VALUES ('nombre_tentatives_connexion', '10');
INSERT INTO setting VALUES ('temps_compte_verrouille', '30');
INSERT INTO setting VALUES ('bull_affiche_appreciations', 'y');
INSERT INTO setting VALUES ('bull_affiche_absences', 'y');
INSERT INTO setting VALUES ('bull_affiche_avis', 'y');
INSERT INTO setting VALUES ('bull_affiche_aid', 'y');
INSERT INTO setting VALUES ('bull_affiche_formule', 'y');
INSERT INTO setting VALUES ('bull_affiche_signature', 'y');
INSERT INTO setting VALUES ('l_max_aff_trombinoscopes', '120');
INSERT INTO setting VALUES ('h_max_aff_trombinoscopes','160');
INSERT INTO setting VALUES ('l_max_imp_trombinoscopes', '70');
INSERT INTO setting VALUES ('h_max_imp_trombinoscopes','100');
INSERT INTO setting VALUES ('active_module_msj', 'n');
INSERT INTO setting VALUES ('site_msj_gepi', 'http://gepi.sylogix.net/releases/');
INSERT INTO setting VALUES ('rc_module_msj', 'n');
INSERT INTO setting VALUES ('beta_module_msj', 'n');
INSERT INTO setting VALUES ('dossier_ftp_gepi', 'gepi');
INSERT INTO etablissements VALUES ('999', '�tranger', 'aucun', 'aucun', 999, '');
INSERT INTO droits VALUES ('/absences/index.php', 'F', 'F', 'V', 'F', 'F', 'F', 'V', 'Saisie des absences', '');
INSERT INTO droits VALUES ('/absences/saisie_absences.php', 'F', 'F', 'V', 'F', 'F', 'F', 'V', 'Saisie des absences', '');
INSERT INTO droits VALUES ('/accueil_admin.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', '', '');
INSERT INTO droits VALUES ('/accueil_modules.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', '', '');
INSERT INTO droits VALUES ('/accueil.php', 'V', 'V', 'V', 'V', 'V', 'V', 'V', '', '');
INSERT INTO droits VALUES ('/aid/add_aid.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration des AID', '');
INSERT INTO droits VALUES ('/aid/config_aid.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration des AID', '');
INSERT INTO droits VALUES ('/aid/export_csv_aid.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration des AID', '');
INSERT INTO droits VALUES ('/aid/help.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration des AID', '');
INSERT INTO droits VALUES ('/aid/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration des AID', '');
INSERT INTO droits VALUES ('/aid/index2.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration des AID', '');
INSERT INTO droits VALUES ('/aid/modify_aid.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration des AID', '');
INSERT INTO droits VALUES ('/bulletin/edit.php', 'F', 'V', 'F', 'V', 'F', 'F', 'F', 'Edition des bulletins', '1');
INSERT INTO droits VALUES ('/bulletin/index.php', 'F', 'V', 'F', 'V', 'F', 'F', 'F', 'Edition des bulletins', '1');
INSERT INTO droits VALUES ('/bulletin/param_bull.php', 'V', 'V', 'F', 'V', 'F', 'F', 'F', 'Edition des bulletins', '1');
INSERT INTO droits VALUES ('/bulletin/verif_bulletins.php', 'F', 'V', 'F', 'V', 'F', 'F', 'F', 'V�rification du remplissage des bulletins', '');
INSERT INTO droits VALUES ('/bulletin/verrouillage.php', 'F', 'F', 'F', 'V', 'F', 'F', 'F', '(de)Verrouillage des p�riodes', '');
INSERT INTO droits VALUES ('/cahier_notes_admin/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Gestion des carnets de notes', '');
INSERT INTO droits VALUES ('/cahier_notes/add_modif_conteneur.php', 'F', 'V', 'F', 'F', 'F', 'F', 'F', 'Carnet de notes', '1');
INSERT INTO droits VALUES ('/cahier_notes/add_modif_dev.php', 'F', 'V', 'F', 'F', 'F', 'F', 'F', 'Carnet de notes', '1');
INSERT INTO droits VALUES ('/cahier_notes/index.php', 'F', 'V', 'F', 'F', 'F', 'F', 'F', 'Carnet de notes', '1');
INSERT INTO droits VALUES ('/cahier_notes/saisie_notes.php', 'F', 'V', 'F', 'F', 'F', 'F', 'F', 'Carnet de notes', '1');
INSERT INTO droits VALUES ('/cahier_notes/toutes_notes.php', 'F', 'V', 'F', 'F', 'F', 'F', 'F', 'Carnet de notes', '1');
INSERT INTO droits VALUES ('/cahier_notes/visu_releve_notes.php', 'F', 'V', 'V', 'V', 'V', 'V', 'F', 'Visualisation et impression des relev�s de notes', '');
INSERT INTO droits VALUES ('/cahier_texte_admin/admin_ct.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Gestion des cahier de texte', '');
INSERT INTO droits VALUES ('/cahier_texte_admin/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Gestion des cahier de texte', '');
INSERT INTO droits VALUES ('/cahier_texte_admin/modify_limites.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Gestion des cahier de texte', '');
INSERT INTO droits VALUES ('/cahier_texte_admin/modify_type_doc.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Gestion des cahier de texte', '');
INSERT INTO droits VALUES ('/cahier_texte/index.php', 'F', 'V', 'F', 'F', 'F', 'F', 'F', 'Cahier de texte', '1');
INSERT INTO droits VALUES ('/cahier_texte/traite_doc.php', 'F', 'V', 'F', 'F', 'F', 'F', 'F', 'Cahier de texte', '1');
INSERT INTO droits VALUES ('/classes/classes_ajout.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/classes_const.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/cpe_resp.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Affectation des CPE aux classes', '');
INSERT INTO droits VALUES ('/classes/duplicate_class.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/eleve_options.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/init_options.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/modify_class.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/modify_nom_class.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/modify_options.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/periodes.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/prof_suivi.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/classes/scol_resp.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Affectation des comptes scolarit� aux classes', '');
INSERT INTO droits VALUES ('/eleves/help.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des �l�ves', '');
INSERT INTO droits VALUES ('/eleves/import_eleves_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des �l�ves', '');
INSERT INTO droits VALUES ('/eleves/index.php', 'V', 'F', 'F', 'V', 'F', 'F', 'F', 'Gestion des �l�ves', '');
INSERT INTO droits VALUES ('/eleves/modify_eleve.php', 'V', 'F', 'F', 'V', 'F', 'F', 'F', 'Gestion des �l�ves', '');
INSERT INTO droits VALUES ('/etablissements/help.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des �tablissements', '');
INSERT INTO droits VALUES ('/etablissements/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des �tablissements', '');
INSERT INTO droits VALUES ('/etablissements/modify_etab.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des �tablissements', '');
INSERT INTO droits VALUES ('/groupes/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Edition des groupes', '');
INSERT INTO droits VALUES ('/groupes/add_group.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Ajout de groupes', '');
INSERT INTO droits VALUES ('/groupes/edit_group.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Edition de groupes', '');
INSERT INTO droits VALUES ('/groupes/edit_eleves.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Edition des �l�ves des groupes', '');
INSERT INTO droits VALUES ('/groupes/edit_class.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Edition des groupes de la classe', '');
INSERT INTO droits VALUES ('/gestion/accueil_sauve.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Restauration, suppression et sauvegarde de la base', '');
INSERT INTO droits VALUES ('/gestion/savebackup.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'T�l�chargement de sauvegardes la base', '');
INSERT INTO droits VALUES ('/gestion/efface_base.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Restauration, suppression et sauvegarde de la base', '');
INSERT INTO droits VALUES ('/gestion/gestion_connect.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Gestion des connexions', '');
INSERT INTO droits VALUES ('/gestion/help_import.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/gestion/help.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', '', '');
INSERT INTO droits VALUES ('/gestion/import_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/gestion/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', '', '');
INSERT INTO droits VALUES ('/gestion/modify_impression.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Gestion des param�tres de la feuille de bienvenue', '');
INSERT INTO droits VALUES ('/gestion/param_gen.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration g�n�rale', '');
INSERT INTO droits VALUES ('/gestion/traitement_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_csv/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation CSV de l\'ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_csv/eleves.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation CSV de l\'ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_csv/responsables.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation CSV de l\'ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_csv/disciplines.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation CSV de l\'ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_csv/professeurs.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation CSV de l\'ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_csv/eleves_classes.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation CSV de l\'ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_csv/prof_disc_classes.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation CSV de l\'ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_csv/eleves_options.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation CSV de l\'ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_scribe/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_scribe/professeurs.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_scribe/eleves.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_scribe/eleves_options.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_scribe/prof_disc_classes.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_scribe/disciplines.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_lcs/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation LCS de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_lcs/eleves.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation LCS de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_lcs/professeurs.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation LCS de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_lcs/disciplines.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation LCS de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_lcs/affectations.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation LCS de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/clean_tables.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/disciplines.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/init_options.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/prof_disc_classe.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/professeurs.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/responsables.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/step1.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/step2.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/initialisation/step3.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO droits VALUES ('/lib/confirm_query.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', '', '');
INSERT INTO droits VALUES ('/matieres/help.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des mati�res', '');
INSERT INTO droits VALUES ('/matieres/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des mati�res', '');
INSERT INTO droits VALUES ('/matieres/modify_matiere.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des mati�res', '');
INSERT INTO droits VALUES ('/matieres/matieres_param.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/matieres/matieres_categories.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Edition des cat�gories de mati�re', '');
INSERT INTO droits VALUES ('/prepa_conseil/edit_limite.php', 'V', 'V', 'V', 'V', 'V', 'V', 'F', 'Edition des bulletins simplifi�s (documents de travail)', '');
INSERT INTO droits VALUES ('/prepa_conseil/help.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', '', '');
INSERT INTO droits VALUES ('/prepa_conseil/index1.php', 'F', 'V', 'F', 'V', 'F', 'F', 'F', 'Visualisation des notes et appr�ciations', '1');
INSERT INTO droits VALUES ('/prepa_conseil/index2.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation des notes par classes', '');
INSERT INTO droits VALUES ('/prepa_conseil/index3.php', 'F', 'V', 'V', 'V', 'V', 'V', 'F', 'Edition des bulletins simplifi�s (documents de travail)', '');
INSERT INTO droits VALUES ('/prepa_conseil/visu_aid.php', 'F', 'V', 'F', 'F', 'F', 'F', 'F', 'Visualisation des notes et appr�ciations AID', '');
INSERT INTO droits VALUES ('/prepa_conseil/visu_toutes_notes.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation des notes par classes', '');
INSERT INTO droits VALUES ('/responsables/index.php', 'V', 'F', 'F', 'V', 'F', 'F', 'F', 'Configuration et gestion des responsables �l�ves', '');
INSERT INTO droits VALUES ('/responsables/modify_resp.php', 'V', 'F', 'F', 'V', 'F', 'F', 'F', 'Configuration et gestion des responsables �l�ves', '');
INSERT INTO droits VALUES ('/saisie/help.php', 'F', 'V', 'F', 'F', 'F', 'F', 'V', '', '');
INSERT INTO droits VALUES ('/saisie/import_class_csv.php', 'F', 'V', 'F', 'V', 'F', 'F', 'V', '', '');
INSERT INTO droits VALUES ('/saisie/import_note_app.php', 'F', 'V', 'F', 'F', 'F', 'F', 'V', '', '');
INSERT INTO droits VALUES ('/saisie/index.php', 'F', 'V', 'F', 'F', 'F', 'V', 'F', '', '');
INSERT INTO droits VALUES ('/saisie/saisie_aid.php', 'F', 'V', 'F', 'F', 'F', 'F', 'V', 'Saisie des notes et appr�ciations AID', '');
INSERT INTO droits VALUES ('/saisie/saisie_appreciations.php', 'F', 'V', 'F', 'F', 'F', 'F', 'V', 'Saisie des appr�ciations du bulletins', '');
INSERT INTO droits VALUES ('/saisie/saisie_avis.php', 'F', 'V', 'F', 'V', 'F', 'F', 'V', 'Saisie des avis du conseil de classe', '');
INSERT INTO droits VALUES ('/saisie/saisie_avis1.php', 'F', 'V', 'F', 'V', 'F', 'F', 'V', 'Saisie des avis du conseil de classe', '');
INSERT INTO droits VALUES ('/saisie/saisie_avis2.php', 'F', 'V', 'F', 'V', 'F', 'F', 'V', 'Saisie des avis du conseil de classe', '');
INSERT INTO droits VALUES ('/saisie/saisie_notes.php', 'F', 'V', 'F', 'F', 'F', 'F', 'V', 'Saisie des notes du bulletins', '');
INSERT INTO droits VALUES ('/saisie/traitement_csv.php', 'F', 'V', 'F', 'F', 'F', 'F', 'V', 'Saisie des notes du bulletins', '');
INSERT INTO droits VALUES ('/utilisateurs/change_pwd.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des utilisateurs', '');
INSERT INTO droits VALUES ('/utilisateurs/help.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des utilisateurs', '');
INSERT INTO droits VALUES ('/utilisateurs/import_prof_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des utilisateurs', '');
INSERT INTO droits VALUES ('/utilisateurs/impression_bienvenue.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des utilisateurs', '');
INSERT INTO droits VALUES ('/utilisateurs/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des utilisateurs', '');
INSERT INTO droits VALUES ('/utilisateurs/reset_passwords.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'R�initialisation des mots de passe', '');
INSERT INTO droits VALUES ('/utilisateurs/modify_user.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des utilisateurs', '');
INSERT INTO droits VALUES ('/utilisateurs/mon_compte.php', 'V', 'V', 'V', 'V', 'V', 'V', 'V', 'Gestion du compte (informations personnelles, mot de passe, ...)', '');
INSERT INTO droits VALUES ('/visualisation/classe_classe.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation graphique des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/visualisation/eleve_classe.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation graphique des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/visualisation/eleve_eleve.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation graphique des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/visualisation/evol_eleve_classe.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation graphique des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/visualisation/evol_eleve.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation graphique des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/visualisation/index.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation graphique des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/visualisation/stats_classe.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation graphique des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/classes/classes_param.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des classes', '');
INSERT INTO droits VALUES ('/fpdf/imprime_pdf.php', 'V', 'V', 'V', 'V', 'F', 'F', 'V', '', '');
INSERT INTO droits VALUES ('/etablissements/import_etab_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration et gestion des �tablissements', '');
INSERT INTO droits VALUES ('/saisie/import_app_cons.php', 'F', 'V', 'F', 'V', 'F', 'F', 'F', 'Importation csv des avis du conseil de classe', '');
INSERT INTO droits VALUES ('/messagerie/index.php', 'V', 'F', 'F', 'V', 'F', 'F', 'F', 'Gestion de la messagerie', '');
INSERT INTO droits VALUES ('/absences/import_absences_gep.php', 'F', 'F', 'V', 'F', 'F', 'F', 'V', 'Saisie des absences', '');
INSERT INTO droits VALUES ('/absences/seq_gep_absences.php', 'F', 'F', 'V', 'F', 'F', 'F', 'V', 'Saisie des absences', '');
INSERT INTO droits VALUES ('/utilitaires/clean_tables.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Maintenance', '');
INSERT INTO droits VALUES ('/gestion/contacter_admin.php', 'V', 'V', 'V', 'V', 'V', 'V', 'V', '', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/gestion_absences.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/impression_absences.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/select.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/ajout_ret.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/ajout_dip.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/ajout_inf.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/ajout_abs.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/bilan_absence.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/bilan.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/gestion/lettre_aux_parents.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', 'Gestion des absences', '');
INSERT INTO droits VALUES ('/mod_absences/lib/tableau.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', '', '');
INSERT INTO droits VALUES ('/mod_absences/admin/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Administration du module absences', '');
INSERT INTO droits VALUES ('/mod_absences/admin/admin_motifs_absences.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Administration du module absences', '');
INSERT INTO droits VALUES ('/mod_absences/admin/admin_periodes_absences.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Administration du module absences', '');
INSERT INTO droits VALUES ('/mod_absences/lib/liste_absences.php', 'F', 'V', 'V', 'F', 'F', 'F', 'F', '', '');
INSERT INTO droits VALUES ('/mod_absences/lib/graphiques.php', 'F', 'F', 'V', 'F', 'F', 'F', 'F', '', '');
INSERT INTO droits VALUES ('/mod_absences/professeurs/prof_ajout_abs.php', 'F', 'V', 'F', 'F', 'F', 'F', 'F', 'Ajout des absences en classe', '');
INSERT INTO droits VALUES ('/mod_absences/admin/admin_actions_absences.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Gestion des actions absences', '');
INSERT INTO droits VALUES ('/mod_trombinoscopes/trombinoscopes.php', 'F', 'V', 'V', 'F', 'F', 'F', 'F', 'Visualiser le trombinoscope', '');
INSERT INTO droits VALUES ('/mod_trombinoscopes/trombi_impr.php', 'F', 'V', 'V', 'F', 'F', 'F', 'F', 'Visualiser le trombinoscope', '');
INSERT INTO droits VALUES ('/mod_trombinoscopes/trombinoscopes_admin.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Administration du trombinoscope', '');
INSERT INTO `droits` VALUES ('/cahier_notes/visu_toutes_notes2.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation des moyennes des carnets de notes', '');
INSERT INTO `droits` VALUES ('/cahier_notes/index2.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation des moyennes des carnets de notes', '');
INSERT INTO `droits` VALUES ('/utilitaires/verif_groupes.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'V�rification des incoh�rences d appartenances � des groupes', '');
INSERT INTO `droits` VALUES ('/referencement.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'R�f�rencement de Gepi sur la base centralis�e des utilisateurs de Gepi', '');
INSERT INTO ct_types_documents VALUES (1, 'JPEG', 'jpg', 'oui');
INSERT INTO ct_types_documents VALUES (2, 'PNG', 'png', 'oui');
INSERT INTO ct_types_documents VALUES (3, 'GIF', 'gif', 'oui');
INSERT INTO ct_types_documents VALUES (4, 'BMP', 'bmp', 'oui');
INSERT INTO ct_types_documents VALUES (5, 'Photoshop', 'psd', 'oui');
INSERT INTO ct_types_documents VALUES (6, 'TIFF', 'tif', 'oui');
INSERT INTO ct_types_documents VALUES (7, 'AIFF', 'aiff', 'oui');
INSERT INTO ct_types_documents VALUES (8, 'Windows Media', 'asf', 'oui');
INSERT INTO ct_types_documents VALUES (9, 'Windows Media', 'avi', 'oui');
INSERT INTO ct_types_documents VALUES (10, 'Midi', 'mid', 'oui');
INSERT INTO ct_types_documents VALUES (12, 'QuickTime', 'mov', 'oui');
INSERT INTO ct_types_documents VALUES (13, 'MP3', 'mp3', 'oui');
INSERT INTO ct_types_documents VALUES (14, 'MPEG', 'mpg', 'oui');
INSERT INTO ct_types_documents VALUES (15, 'Ogg', 'ogg', 'oui');
INSERT INTO ct_types_documents VALUES (16, 'QuickTime', 'qt', 'oui');
INSERT INTO ct_types_documents VALUES (17, 'RealAudio', 'ra', 'oui');
INSERT INTO ct_types_documents VALUES (18, 'RealAudio', 'ram', 'oui');
INSERT INTO ct_types_documents VALUES (19, 'RealAudio', 'rm', 'oui');
INSERT INTO ct_types_documents VALUES (20, 'Flash', 'swf', 'oui');
INSERT INTO ct_types_documents VALUES (21, 'WAV', 'wav', 'oui');
INSERT INTO ct_types_documents VALUES (22, 'Windows Media', 'wmv', 'oui');
INSERT INTO ct_types_documents VALUES (23, 'Adobe Illustrator', 'ai', 'oui');
INSERT INTO ct_types_documents VALUES (24, 'BZip', 'bz2', 'oui');
INSERT INTO ct_types_documents VALUES (25, 'C source', 'c', 'oui');
INSERT INTO ct_types_documents VALUES (26, 'Debian', 'deb', 'oui');
INSERT INTO ct_types_documents VALUES (27, 'Word', 'doc', 'oui');
INSERT INTO ct_types_documents VALUES (29, 'LaTeX DVI', 'dvi', 'oui');
INSERT INTO ct_types_documents VALUES (30, 'PostScript', 'eps', 'oui');
INSERT INTO ct_types_documents VALUES (31, 'GZ', 'gz', 'oui');
INSERT INTO ct_types_documents VALUES (32, 'C header', 'h', 'oui');
INSERT INTO ct_types_documents VALUES (33, 'HTML', 'html', 'oui');
INSERT INTO ct_types_documents VALUES (34, 'Pascal', 'pas', 'oui');
INSERT INTO ct_types_documents VALUES (35, 'PDF', 'pdf', 'oui');
INSERT INTO ct_types_documents VALUES (36, 'PowerPoint', 'ppt', 'oui');
INSERT INTO ct_types_documents VALUES (37, 'PostScript', 'ps', 'oui');
INSERT INTO ct_types_documents VALUES (38, 'gr', 'gr', 'oui');
INSERT INTO ct_types_documents VALUES (39, 'RTF', 'rtf', 'oui');
INSERT INTO ct_types_documents VALUES (40, 'StarOffice', 'sdd', 'oui');
INSERT INTO ct_types_documents VALUES (41, 'StarOffice', 'sdw', 'oui');
INSERT INTO ct_types_documents VALUES (42, 'Stuffit', 'sit', 'oui');
INSERT INTO ct_types_documents VALUES (43, 'OpenOffice Calc', 'sxc', 'oui');
INSERT INTO ct_types_documents VALUES (44, 'OpenOffice Impress', 'sxi', 'oui');
INSERT INTO ct_types_documents VALUES (45, 'OpenOffice', 'sxw', 'oui');
INSERT INTO ct_types_documents VALUES (46, 'LaTeX', 'tex', 'oui');
INSERT INTO ct_types_documents VALUES (47, 'TGZ', 'tgz', 'oui');
INSERT INTO ct_types_documents VALUES (48, 'texte', 'txt', 'oui');
INSERT INTO ct_types_documents VALUES (49, 'GIMP multi-layer', 'xcf', 'oui');
INSERT INTO ct_types_documents VALUES (50, 'Excel', 'xls', 'oui');
INSERT INTO ct_types_documents VALUES (51, 'XML', 'xml', 'oui');
INSERT INTO ct_types_documents VALUES (52, 'Zip', 'zip', 'oui');
INSERT INTO ct_types_documents VALUES (53, 'Texte OpenDocument', 'odt', 'oui');
INSERT INTO ct_types_documents VALUES (54, 'Classeur OpenDocument', 'ods', 'oui');
INSERT INTO ct_types_documents VALUES (55, 'Pr�sentation OpenDocument', 'odp', 'oui');
INSERT INTO ct_types_documents VALUES (56, 'Dessin OpenDocument', 'odg', 'oui');
INSERT INTO ct_types_documents VALUES (57, 'Base de donn�es OpenDocument', 'odb', 'oui');
INSERT INTO absences_creneaux VALUES (1, 'M1', '08:00:00', '08:55:00');
INSERT INTO absences_creneaux VALUES (2, 'M2', '08:55:00', '09:50:00');
INSERT INTO absences_creneaux VALUES (3, 'M3', '10:05:00', '11:00:00');
INSERT INTO absences_creneaux VALUES (4, 'M4', '11:00:00', '11:55:00');
INSERT INTO absences_creneaux VALUES (5, 'S1', '13:30:00', '14:25:00');
INSERT INTO absences_creneaux VALUES (6, 'S2', '14:25:00', '15:20:00');
INSERT INTO absences_creneaux VALUES (7, 'S3', '15:35:00', '16:30:00');
INSERT INTO absences_creneaux VALUES (8, 'S4', '16:30:00', '17:30:00');
INSERT INTO absences_creneaux VALUES (32, 'M5', '11:55:00', '12:30:00');
INSERT INTO absences_creneaux VALUES (31, 'P1', '09:50:00', '10:05:00');
INSERT INTO absences_creneaux VALUES (33, 'R', '12:00:00', '13:00:00');
INSERT INTO absences_creneaux VALUES (34, 'R1', '13:00:00', '13:30:00');
INSERT INTO absences_creneaux VALUES (35, 'P2', '15:20:00', '15:35:00');
INSERT INTO absences_creneaux VALUES (36, 'S5', '17:30:00', '18:25:00');
INSERT INTO absences_motifs VALUES (1, 'A', 'Aucun motif');
INSERT INTO absences_motifs VALUES (2, 'AS', 'Accident sport');
INSERT INTO absences_motifs VALUES (3, 'AT', 'Absent en retenue');
INSERT INTO absences_motifs VALUES (4, 'C', 'Dans la cour');
INSERT INTO absences_motifs VALUES (5, 'CF', 'Convenances familiales');
INSERT INTO absences_motifs VALUES (6, 'CO', 'Convocation bureau');
INSERT INTO absences_motifs VALUES (7, 'CS', 'Comp�tition sportive');
INSERT INTO absences_motifs VALUES (8, 'DI', 'Dispense d''E.P.S.');
INSERT INTO absences_motifs VALUES (9, 'ET', 'Erreur d''emploi du temps');
INSERT INTO absences_motifs VALUES (10, 'EX', 'Examen');
INSERT INTO absences_motifs VALUES (11, 'H', 'Hospitalisation');
INSERT INTO absences_motifs VALUES (12, 'JP', 'Justification par le Principal');
INSERT INTO absences_motifs VALUES (13, 'MA', 'Maladie');
INSERT INTO absences_motifs VALUES (14, 'OR', 'Conseiller');
INSERT INTO absences_motifs VALUES (15, 'PR', 'R�veil');
INSERT INTO absences_motifs VALUES (16, 'RC', 'Refus de venir en cours');
INSERT INTO absences_motifs VALUES (17, 'RE', 'Renvoi');
INSERT INTO absences_motifs VALUES (18, 'RT', 'Pr�sent en retenue');
INSERT INTO absences_motifs VALUES (19, 'RV', 'Renvoi du cours');
INSERT INTO absences_motifs VALUES (20, 'SM', 'Refus de justification');
INSERT INTO absences_motifs VALUES (21, 'SP', 'Sortie p�dagogique');
INSERT INTO absences_motifs VALUES (22, 'ST', 'Stage � l''ext�rieur');
INSERT INTO absences_motifs VALUES (23, 'T', 'T�l�phone');
INSERT INTO absences_motifs VALUES (24, 'TR', 'Transport');
INSERT INTO absences_motifs VALUES (25, 'VM', 'Visite m�dicale');
INSERT INTO absences_motifs VALUES (26, 'IN', 'Infirmerie');
INSERT INTO `droits` VALUES ('/utilisateurs/tab_profs_matieres.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Affectation des matieres aux professeurs', '');
INSERT INTO `droits` VALUES ('/matieres/matieres_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Importation des mati�res depuis un fichier CSV', '');
INSERT INTO `droits` VALUES ('/groupes/edit_class_grp_lot.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Gestion des enseignements simples par lot.', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/clean_tables.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/init_options.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/responsables.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/step1.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/step2.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/step3.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/disciplines_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/prof_disc_classe_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/prof_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/lecture_xml_sts_emp.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/init_pp.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/init_dbf_sts/save_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation de l''ann�e scolaire', '');
INSERT INTO `droits` VALUES ('/groupes/visu_profs_class.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation des �quipes p�dagogiques', '');
INSERT INTO `droits` VALUES ('/groupes/popup.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation des �quipes p�dagogiques', '');
INSERT INTO `matieres_categories` VALUES (1, 'Autres', 'Autres', '5');
INSERT INTO droits VALUES ('/visualisation/affiche_eleve.php', 'F', 'V', 'V', 'V', 'V', 'V', 'F', 'Visualisation graphique des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/visualisation/draw_graphe.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Visualisation graphique des r�sultats scolaires', '');
INSERT INTO setting VALUES ('gepi_denom_boite','boite');
INSERT INTO setting VALUES ('gepi_denom_boite_genre','f');
INSERT INTO droits VALUES ('/groupes/mes_listes.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'Acc�s aux CSV des listes d �l�ves', '');
INSERT INTO droits VALUES ('/groupes/get_csv.php', 'F', 'V', 'V', 'V', 'F', 'F', 'V', 'G�n�ration de CSV �l�ves', '');
INSERT INTO droits VALUES ('/visualisation/choix_couleurs.php', 'V', 'F', 'F', 'V', 'F', 'F', 'F', 'Choix des couleurs des graphiques des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/visualisation/couleur.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Choix d une couleur pour le graphique des r�sultats scolaires', '');
INSERT INTO droits VALUES ('/gestion/config_prefs.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'D�finition des pr�f�rences d utilisateurs', '');
INSERT INTO setting VALUES ('addressblock_font_size', '12');
INSERT INTO setting VALUES ('addressblock_logo_etab_prop', '50');
INSERT INTO setting VALUES ('addressblock_classe_annee', '35');
INSERT INTO setting VALUES ('bull_ecart_bloc_nom', '1');
INSERT INTO setting VALUES ('addressblock_debug', 'n');
INSERT INTO droits VALUES ('/utilitaires/recalcul_moy_conteneurs.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Correction des moyennes des conteneurs', '');
INSERT INTO droits VALUES ('/saisie/commentaires_types.php', 'V', 'V', 'V', 'V', 'F', 'F', 'V', 'Saisie de commentaires-types', '');
INSERT INTO droits VALUES ('/mod_absences/lib/fiche_eleve.php', 'F', 'V', 'V', 'F', 'F', 'F', 'F', 'Fiche du suivie de l''�l�ve', '');
INSERT INTO droits VALUES ('/cahier_notes/releve_pdf.php', 'V', 'V', 'F', 'V', 'F', 'F', 'V', 'Relev� de note au format PDF', '');
INSERT INTO droits VALUES ('/impression/parametres_impression_pdf.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Impression des listes PDF; r�glage des param�tres', '');
INSERT INTO droits VALUES ('/impression/impression_serie.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Impression des listes (PDF) en s�rie', '');
INSERT INTO droits VALUES ('/impression/impression.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Impression rapide d une listes (PDF) ', '');
INSERT INTO droits VALUES ('/impression/liste_pdf.php', 'F', 'V', 'V', 'V', 'F', 'F', 'F', 'Impression des listes (PDF)', '');
INSERT INTO droits VALUES ('/init_xml/lecture_xml_sconet.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/init_pp.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/clean_tables.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/step2.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/step1.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/disciplines_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/prof_disc_classe_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/lecture_xml_sts_emp.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/prof_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/index.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/init_options.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/save_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/responsables.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/init_xml/step3.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Initialisation ann�e scolaire', '');
INSERT INTO droits VALUES ('/responsables/maj_import.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Mise � jour depuis Sconet', '');
INSERT INTO droits VALUES ('/responsables/conversion.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Conversion des donn�es responsables', '');
INSERT INTO droits VALUES ('/utilisateurs/create_responsable.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Cr�ation des utilisateurs au statut responsable', '');
INSERT INTO droits VALUES ('/utilisateurs/create_eleve.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Cr�ation des utilisateurs au statut responsable', '');
INSERT INTO droits VALUES ('/utilisateurs/edit_responsable.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Edition des utilisateurs au statut responsable', '');
INSERT INTO droits VALUES ('/utilisateurs/edit_eleve.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Edition des utilisateurs au statut �l�ve', '');
INSERT INTO droits VALUES ('/cahier_texte/consultation.php', 'F', 'F', 'F', 'F', 'V', 'V', 'F', 'Consultation des cahiers de texte', '');
INSERT INTO droits VALUES ('/cahier_texte/see_all.php', 'F', 'F', 'F', 'F', 'V', 'V', 'F', 'Consultation des cahiers de texte', '');
INSERT INTO droits VALUES ('/gestion/droits_acces.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Param�trage des droits d acc�s', '');
INSERT INTO setting VALUES ('GepiAccesReleveEleve', 'yes');
INSERT INTO setting VALUES ('GepiAccesCahierTexteEleve', 'yes');
INSERT INTO setting VALUES ('GepiAccesReleveParent', 'yes');
INSERT INTO setting VALUES ('GepiAccesCahierTexteParent', 'yes');
INSERT INTO setting VALUES ('enable_password_recovery', 'no');
INSERT INTO setting VALUES ('GepiPasswordReinitProf', 'no');
INSERT INTO setting VALUES ('GepiPasswordReinitScolarite', 'no');
INSERT INTO setting VALUES ('GepiPasswordReinitCpe', 'no');
INSERT INTO setting VALUES ('GepiPasswordReinitAdmin', 'no');
INSERT INTO setting VALUES ('GepiPasswordReinitEleve', 'yes');
INSERT INTO setting VALUES ('GepiPasswordReinitParent', 'yes');
INSERT INTO setting VALUES ('cahier_texte_acces_public', 'no');
INSERT INTO setting VALUES ('GepiAccesEquipePedaEleve', 'yes');
INSERT INTO setting VALUES ('GepiAccesEquipePedaEmailEleve', 'no');
INSERT INTO setting VALUES ('GepiAccesEquipePedaParent', 'yes');
INSERT INTO setting VALUES ('GepiAccesEquipePedaEmailParent', 'no');
INSERT INTO droits VALUES ('/groupes/visu_profs_eleve.php', 'F', 'F', 'F', 'F', 'V', 'V', 'F', 'Consultation �quipe p�dagogique', '');
INSERT INTO setting VALUES ('GepiAccesBulletinSimpleParent', 'yes');
INSERT INTO setting VALUES ('GepiAccesBulletinSimpleEleve', 'yes');
INSERT INTO droits VALUES ('/saisie/impression_avis.php', 'F', 'V', 'F', 'V', 'F', 'F', 'F', 'Impression des avis trimestrielles des conseils de classe.', '');
INSERT INTO droits VALUES ('/impression/avis_pdf.php', 'F', 'V', 'F', 'V', 'F', 'F', 'F', 'Impression des avis trimestrielles des conseils de classe. Module PDF', '');
INSERT INTO droits VALUES ('/impression/parametres_impression_pdf_avis.php', 'F', 'V', 'F', 'V', 'F', 'F', 'F', 'Impression des avis conseil classe PDF; reglage des parametres', '');
INSERT INTO setting VALUES ('GepiAccesGraphEleve', 'yes');
INSERT INTO setting VALUES ('GepiAccesGraphParent', 'yes');
INSERT INTO droits VALUES ('/utilisateurs/password_csv.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Export des identifiants et mots de passe en csv', '');
INSERT INTO droits VALUES ('/impression/password_pdf.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F','Impression des identifiants et des mots de passe en PDF', '');
INSERT INTO `setting` (`NAME`, `VALUE`) VALUES ('choix_bulletin', '2');
INSERT INTO setting VALUES ('min_max_moyclas', '0');
INSERT INTO setting VALUES ('bull_categ_font_size_avis', '10');
INSERT INTO setting VALUES ('bull_police_avis', 'Times New Roman');
INSERT INTO setting VALUES ('bull_font_style_avis', 'Normal');
INSERT INTO setting VALUES ('bull_affiche_eleve_une_ligne', 'yes');
INSERT INTO setting VALUES ('bull_mention_nom_court', 'yes');
INSERT INTO `model_bulletin` (`id_model_bulletin`, `nom_model_bulletin`, `active_bloc_datation`, `active_bloc_eleve`, `active_bloc_adresse_parent`, `active_bloc_absence`, `active_bloc_note_appreciation`, `active_bloc_avis_conseil`, `active_bloc_chef`, `active_photo`, `active_coef_moyenne`, `active_nombre_note`, `active_nombre_note_case`, `active_moyenne`, `active_moyenne_eleve`, `active_moyenne_classe`, `active_moyenne_min`, `active_moyenne_max`, `active_regroupement_cote`, `active_entete_regroupement`, `active_moyenne_regroupement`, `active_rang`, `active_graphique_niveau`, `active_appreciation`, `affiche_doublement`, `affiche_date_naissance`, `affiche_dp`, `affiche_nom_court`, `affiche_effectif_classe`, `affiche_numero_impression`, `caractere_utilse`, `X_parent`, `Y_parent`, `X_eleve`, `Y_eleve`, `cadre_eleve`, `X_datation_bul`, `Y_datation_bul`, `cadre_datation_bul`, `hauteur_info_categorie`, `X_note_app`, `Y_note_app`, `longeur_note_app`, `hauteur_note_app`, `largeur_coef_moyenne`, `largeur_nombre_note`, `largeur_d_une_moyenne`, `largeur_niveau`, `largeur_rang`, `X_absence`, `Y_absence`, `hauteur_entete_moyenne_general`, `X_avis_cons`, `Y_avis_cons`, `longeur_avis_cons`, `hauteur_avis_cons`, `cadre_avis_cons`, `X_sign_chef`, `Y_sign_chef`, `longeur_sign_chef`, `hauteur_sign_chef`, `cadre_sign_chef`, `affiche_filigrame`, `texte_filigrame`, `affiche_logo_etab`, `entente_mel`, `entente_tel`, `entente_fax`, `L_max_logo`, `H_max_logo`, `toute_moyenne_meme_col`, `active_reperage_eleve`, `couleur_reperage_eleve1`, `couleur_reperage_eleve2`, `couleur_reperage_eleve3`, `couleur_categorie_entete`, `couleur_categorie_entete1`, `couleur_categorie_entete2`, `couleur_categorie_entete3`, `couleur_categorie_cote`, `couleur_categorie_cote1`, `couleur_categorie_cote2`, `couleur_categorie_cote3`, `couleur_moy_general`, `couleur_moy_general1`, `couleur_moy_general2`, `couleur_moy_general3`, `titre_entete_matiere`, `titre_entete_coef`, `titre_entete_nbnote`, `titre_entete_rang`, `titre_entete_appreciation`, `active_coef_sousmoyene`, `arrondie_choix`, `nb_chiffre_virgule`, `chiffre_avec_zero`, `autorise_sous_matiere`, `affichage_haut_responsable`, `entete_model_bulletin`, `ordre_entete_model_bulletin`, `affiche_etab_origine`, `imprime_pour`) VALUES
(1, 'Standard', 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 'Arial', 110, 40, 5, 40, 1, 110, 5, 1, 5, 5, 72, 200, 175, 8, 8, 10, 18, 5, 5, 246.3, 5, 5, 250, 130, 37, 1, 138, 250, 67, 37, 0, 1, 'DUPLICATA INTERNET', 1, 1, 1, 1, 75, 75, 0, 1, 255, 255, 207, 1, 239, 239, 239, 1, 239, 239, 239, 1, 239, 239, 239, 'Mati�re', 'coef.', 'nb. n.', 'rang', 'Appr�ciation / Conseils', 0, 0.01, 2, 0, 1, 1, 0, 0, 0, 0),
(2, 'Standard avec photo', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 'Arial', 110, 40, 5, 40, 1, 110, 5, 1, 5, 5, 72, 200, 175, 8, 8, 10, 18, 5, 5, 246.3, 5, 5, 250, 130, 37, 1, 138, 250, 67, 37, 0, 1, 'DUPLICATA INTERNET', 1, 1, 1, 1, 75, 75, 0, 1, 255, 255, 207, 1, 239, 239, 239, 1, 239, 239, 239, 1, 239, 239, 239, 'Mati�re', 'coef.', 'nb. n.', 'rang', 'Appr�ciation / Conseils', 0, 0, 2, 0, 1, 1, 0, 0, 0, 0),
(3, 'Affiche tout', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'Arial', 110, 40, 5, 40, 1, 110, 5, 1, 5, 5, 72, 200, 175, 8, 8, 10, 16.5, 6.5, 5, 246.3, 5, 5, 250, 130, 37, 1, 138, 250, 67, 37, 1, 1, 'DUPLICATA INTERNET', 1, 1, 1, 1, 75, 75, 1, 1, 255, 255, 207, 1, 239, 239, 239, 1, 239, 239, 239, 1, 239, 239, 239, 'Mati�re', 'coef.', 'nb. n.', 'rang', 'Appr�ciation / Conseils', 1, 0.01, 2, 0, 1, 1, 2, 1, 1, 1);
INSERT INTO droits VALUES ('/bulletin/buletin_pdf.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'Bulletin scolaire au format PDF', '');

INSERT INTO `lettres_cadres` VALUES (1, 'adresse responsable', 100, 40, 100, 5, 'A l\'attention de\r\n<civilitee_court_responsable> <nom_responsable> <prenom_responsable>\r\n<adresse_responsable>\r\n<cp_responsable> <commune_responsable>\r\n', 0, '||'),
(2, 'adresse etablissement', 0, 0, 0, 0, '', 0, ''),
(3, 'datation', 0, 0, 0, 0, '', 0, ''),
(4, 'corp avertissement', 10, 70, 0, 5, '<u>Objet: </u> <g>Avertissement</g>\r\n\r\n\r\n<nom_civilitee_long>,\r\n\r\nJe me vois dans l\'obligation de donner un <b>AVERTISSEMENT</b>\r\n\r\n� <g><nom_eleve> <prenom_eleve></g> �l�ve de la classe <g><classe_eleve></g>.\r\n\r\n\r\npour la raison suivante : <g><sujet_eleve></g>\r\n\r\n<remarque_eleve>\r\n\r\n\r\n\r\nComme le pr�voit le r�glement int�rieur de l\'�tablissement, il pourra �tre sanctionn� � partir de ce jour.\r\nSanction(s) possible(s) :\r\n\r\n\r\n\r\n\r\nJe vous remercie de me renvoyer cet exemplaire apr�s l\'avoir dat� et sign�.\r\nVeuillez agr�er <nom_civilitee_long> <nom_responsable> l\'assurance de ma consid�ration distingu�e.\r\n\r\n\r\n\r\nDate et signatures des parents :	', 0, '||'),
(5, 'corp blame', 10, 70, 0, 5, '<u>Objet</u>: <g>Bl�me</g>\r\n\r\n\r\n<nom_civilitee_long>\r\n\r\nJe me vois dans l\'obligation de donner un BLAME \r\n\r\n� <g><nom_eleve> <prenom_eleve></g> �l�ve de la classe <g><classe_eleve></g>.\r\n\r\nDemand� par: <g><courrier_demande_par></g>\r\n\r\npour la raison suivante: <g><raison></g>\r\n\r\n<remarque>\r\n\r\nJe vous remercie de me renvoyer cet exemplaire apr�s l\'avoir dat� et sign�.\r\nVeuillez agr�er <g><nom_civilitee_long> <nom_responsable></g> l\'assurance de ma consid�ration distingu�e.\r\n\r\n<u>Date et signatures des parents:</u>\r\n\r\n\r\n\r\n\r\n\r\nNous demandons un entretien avec la personne ayant demand� la sanction OUI / NON.\r\n(La prise de rendez-vous est � votre initiative)\r\n', 0, '||'),
(6, 'corp convocation parents', 10, 70, 0, 5, '<u>Objet</u>: <g>Convocation des parents</g>\r\n\r\n\r\n<nom_civilitee_long>,\r\n\r\nVous �tes pri� de prendre contact avec le Conseiller Principal d\'Education dans les plus brefs d�lais, au sujet de <g><nom_eleve> <prenom_eleve></g> inscrit en classe de <g><classe_eleve></g>.\r\n\r\npour le motif suivant:\r\n\r\n<remarque>\r\n\r\n\r\n\r\nSans nouvelle de votre part avant le ........................................., je serai dans l\'obligation de proc�der � la descolarisation de l\'�l�ve, avec les cons�quences qui en r�sulteront, jusqu\'� votre rencontre.\r\n\r\n\r\nVeuillez agr�er <g><nom_civilitee_long> <nom_responsable></g> l\'assurance de ma consid�ration distingu�e.', 0, '||'),
(7, 'corp exclusion', 10, 70, 0, 5, '<u>Objet: </u> <g>Sanction - Exclusion de l\'�tablissement</g>\r\n\r\n\r\n<nom_civilitee_long>,\r\n\r\nPar la pr�sente, je tiens � vous signaler que <nom_eleve>\r\n\r\ninscrit en classe de  <classe_eleve>\r\n\r\n\r\ns\'�tant rendu coupable des faits suivants : \r\n\r\n<remarque>\r\n\r\n\r\n\r\nEst exclu de l\'�tablissement,\r\n� compter du: <b><date_debut></b> � <b><heure_debut></b>,\r\njusqu\'au: <b><date_fin></b> � <b><heure_fin></b>.\r\n\r\n\r\nIl devra se pr�senter, au bureau de la Vie Scolaire \r\n\r\nle ....................................... � ....................................... ACCOMPAGNE DE SES PARENTS.\r\n\r\n\r\n\r\n\r\nVeuillez agr�er &lt;TYPEPARENT&gt; &lt;NOMPARENT&gt; l\'assurance de ma consid�ration distingu�e.', 0, '||'),
(8, 'corp demande justificatif absence', 10, 70, 0, 5, '<u>Objet: </u> <g>Demande de justificatif d\'absence</g>\r\n\r\n\r\n<civilitee_long_responsable>,\r\n\r\nJ\'ai le regret de vous informer que <b><nom_eleve> <prenom_eleve></b>, �l�ve en classe de <b><classe_eleve></b> n\'a pas assist� au(x) cours:\r\n\r\n<liste>\r\n\r\nJe vous prie de bien vouloir me faire conna�tre le motif de son absence.\r\n\r\nPour permettre un contr�le efficace des pr�sences, toute absence d\'un �l�ve doit �tre justifi�e par sa famille, le jour m�me soit par t�l�phone, soit par �crit, soit par fax.\r\n\r\nAvant de regagner les cours, l\'�l�ve absent devra se pr�senter au bureau du Conseiller Principal d\'Education muni de son carnet de correspondance avec un justificatif sign� des parents.\r\n\r\nVeuillez agr�er <civilitee_long_responsable> <nom_responsable>, l\'assurance de ma consid�ration distingu�e.\r\n                                               \r\nCPE\r\n<civilitee_long_cpe> <nom_cpe> <prenom_cpe>\r\n\r\nPri�re de renvoyer, par retour du courrier, le pr�sent avis sign� des parents :\r\n\r\nMotif de l\'absence : \r\n________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________\r\n\r\n\r\n\r\nDate et signatures des parents :  \r\n', 0, '||'),
(10, 'signature', 100, 180, 0, 5, '<b><courrier_signe_par_fonction></b>,\r\n<courrier_signe_par>\r\n', 0, '||');


INSERT INTO `absences_actions` VALUES (1, 'RC', 'Renvoi du cours'),
(2, 'RD', 'Renvoi d&eacute;finitif'),
(3, 'LP', 'Lettre aux parents'),
(4, 'CE', 'Demande de convocation de l&#039;&eacute;l&egrave;ve en vie scolaire'),
(5, 'A', 'Aucune');

INSERT INTO `etiquettes_formats` VALUES (1, 'Avery - A4 - 63,5 x 33,9 mm', 2, 2, 5, 5, 63.5, 33, 3, 8);

INSERT INTO `horaires_etablissement` VALUES (1, '0000-00-00', 'lundi', '08:00:00', '17:30:00', '00:45:00', 1),
(2, '0000-00-00', 'mardi', '08:00:00', '17:30:00', '00:45:00', 1),
(3, '0000-00-00', 'mercredi', '08:00:00', '12:00:00', '00:00:00', 1),
(4, '0000-00-00', 'jeudi', '08:00:00', '17:30:00', '00:45:00', 1),
(5, '0000-00-00', 'vendredi', '08:00:00', '17:30:00', '00:45:00', 1);


INSERT INTO `lettres_types` VALUES (1, 'blame', 'sanction', ''),
(2, 'convocation des parents', 'suivi', ''),
(3, 'avertissement', 'sanction', ''),
(4, 'exclusion', 'sanction', ''),
(5, 'certificat de scolarit�', 'suivi', ''),
(6, 'demande de justificatif d''absence', 'suivi', 'oui'),
(7, 'demande de justificatif de retard', 'suivi', ''),
(8, 'rapport d''incidence', 'sanction', ''),
(9, 'regime de sortie', 'suivi', ''),
(10, 'retenue', 'sanction', '');


INSERT INTO `droits` VALUES ('/mod_absences/gestion/etiquette_pdf.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'Etiquette au format PDF', '');
INSERT INTO `droits` VALUES ('/mod_absences/lib/export_csv.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'Fichier d''exportation en csv des absences', '');
INSERT INTO `droits` VALUES ('/mod_absences/lib/statistiques.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'Statistique du module vie scolaire', '1');
INSERT INTO `droits` VALUES ('/mod_absences/lib/graph_camembert.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'graphique camembert', '');
INSERT INTO `droits` VALUES ('/mod_absences/lib/graph_ligne.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'graphique camembert', '');
INSERT INTO `droits` VALUES ('/mod_absences/admin/admin_horaire_ouverture.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'D�finition des horaires d''ouverture de l''�tablissement', '');
INSERT INTO `droits` VALUES ('/mod_absences/admin/admin_config_semaines.php', 'V', 'F', 'F', 'F', 'F', 'F', 'F', 'Configuration des types de semaines', '');
INSERT INTO `droits` VALUES ('/mod_absences/gestion/fiche_pdf.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'Fiche r�capitulatif des absences', '');
INSERT INTO `droits` VALUES ('/mod_absences/lib/graph_double_ligne.php', 'V', 'V', 'V', 'V', 'F', 'F', 'F', 'graphique absence et retard sur le m�me graphique', '');

INSERT INTO `lettres_tcs` VALUES (1, 3, 0, 0, 0, 0, 0, 0),
(2, 3, 0, 0, 0, 0, 0, 0),
(3, 3, 0, 0, 0, 0, 0, 0),
(4, 3, 0, 0, 0, 0, 0, 0),
(5, 3, 0, 0, 0, 0, 0, 0),
(6, 3, 0, 0, 0, 0, 0, 0),
(7, 3, 0, 0, 0, 0, 0, 0),
(8, 3, 0, 0, 0, 0, 0, 0),
(9, 3, 0, 0, 0, 0, 0, 0),
(10, 3, 0, 0, 0, 0, 0, 0),
(11, 3, 0, 0, 0, 0, 0, 0),
(12, 3, 0, 0, 0, 0, 0, 0),
(13, 3, 0, 0, 0, 0, 0, 0),
(14, 3, 0, 0, 0, 0, 0, 0),
(15, 3, 0, 0, 0, 0, 0, 0),
(16, 3, 0, 0, 0, 0, 0, 0),
(17, 3, 0, 0, 0, 0, 0, 0),
(18, 3, 0, 0, 0, 0, 0, 0),
(19, 3, 0, 0, 0, 0, 0, 0),
(20, 3, 0, 0, 0, 0, 0, 0),
(21, 3, 0, 0, 0, 0, 0, 0),
(22, 3, 0, 0, 0, 0, 0, 0),
(23, 3, 0, 0, 0, 0, 0, 0),
(24, 3, 0, 0, 0, 0, 0, 0),
(25, 3, 0, 0, 0, 0, 0, 0),
(26, 3, 0, 0, 0, 0, 0, 0),
(27, 3, 0, 0, 0, 0, 0, 0),
(28, 3, 0, 0, 0, 0, 0, 0),
(29, 3, 0, 0, 0, 0, 0, 0),
(30, 3, 0, 0, 0, 0, 0, 0),
(31, 3, 0, 0, 0, 0, 0, 0),
(32, 3, 0, 0, 0, 0, 0, 0),
(33, 3, 0, 0, 0, 0, 0, 0),
(34, 3, 0, 0, 0, 0, 0, 0),
(35, 3, 0, 0, 0, 0, 0, 0),
(36, 3, 0, 0, 0, 0, 0, 0),
(37, 3, 0, 0, 0, 0, 0, 0),
(38, 3, 0, 0, 0, 0, 0, 0),
(39, 3, 0, 0, 0, 0, 0, 0),
(40, 3, 0, 0, 0, 0, 0, 0),
(41, 3, 0, 0, 0, 0, 0, 0),
(42, 3, 0, 0, 0, 0, 0, 0),
(43, 3, 0, 0, 0, 0, 0, 0),
(44, 3, 0, 0, 0, 0, 0, 0),
(45, 3, 0, 0, 0, 0, 0, 0),
(46, 3, 0, 0, 0, 0, 0, 0),
(47, 3, 0, 0, 0, 0, 0, 0),
(48, 3, 0, 0, 0, 0, 0, 0),
(49, 3, 0, 0, 0, 0, 0, 0),
(50, 3, 0, 0, 0, 0, 0, 0),
(51, 3, 0, 0, 0, 0, 0, 0),
(52, 3, 0, 0, 0, 0, 0, 0),
(53, 3, 0, 0, 0, 0, 0, 0),
(56, 3, 1, 100, 40, 100, 5, 0),
(57, 3, 4, 10, 70, 0, 5, 0),
(58, 1, 0, 0, 0, 0, 0, 0),
(59, 1, 0, 0, 0, 0, 0, 0),
(60, 1, 0, 0, 0, 0, 0, 0),
(61, 1, 0, 0, 0, 0, 0, 0),
(62, 1, 0, 0, 0, 0, 0, 0),
(63, 1, 0, 0, 0, 0, 0, 0),
(64, 1, 0, 0, 0, 0, 0, 0),
(65, 1, 1, 100, 40, 100, 5, 0),
(66, 1, 5, 10, 70, 0, 5, 0),
(68, 2, 1, 100, 40, 100, 5, 0),
(69, 2, 6, 10, 70, 0, 5, 0),
(70, 4, 1, 100, 40, 100, 5, 0),
(71, 4, 7, 10, 70, 0, 5, 0),
(72, 6, 0, 0, 0, 0, 0, 0),
(73, 6, 0, 0, 0, 0, 0, 0),
(74, 6, 0, 0, 0, 0, 0, 0),
(75, 6, 0, 0, 0, 0, 0, 0),
(76, 6, 0, 0, 0, 0, 0, 0),
(77, 6, 0, 0, 0, 0, 0, 0),
(78, 6, 0, 0, 0, 0, 0, 0),
(79, 6, 0, 0, 0, 0, 0, 0),
(80, 6, 0, 0, 0, 0, 0, 0),
(81, 6, 0, 0, 0, 0, 0, 0),
(82, 6, 0, 0, 0, 0, 0, 0),
(83, 6, 0, 0, 0, 0, 0, 0),
(84, 6, 0, 0, 0, 0, 0, 0),
(85, 6, 0, 0, 0, 0, 0, 0),
(86, 6, 0, 0, 0, 0, 0, 0),
(87, 6, 0, 0, 0, 0, 0, 0),
(88, 6, 0, 0, 0, 0, 0, 0),
(89, 6, 1, 100, 40, 100, 5, 0),
(90, 6, 8, 10, 70, 0, 5, 0),
(91, 7, 0, 0, 0, 0, 0, 0),
(92, 7, 0, 0, 0, 0, 0, 0),
(93, 7, 0, 0, 0, 0, 0, 0),
(94, 7, 0, 0, 0, 0, 0, 0),
(95, 7, 0, 0, 0, 0, 0, 0),
(96, 7, 0, 0, 0, 0, 0, 0),
(97, 7, 0, 0, 0, 0, 0, 0),
(98, 7, 0, 0, 0, 0, 0, 0),
(99, 7, 0, 0, 0, 0, 0, 0),
(100, 7, 0, 0, 0, 0, 0, 0),
(101, 7, 0, 0, 0, 0, 0, 0),
(102, 7, 0, 0, 0, 0, 0, 0),
(103, 7, 0, 0, 0, 0, 0, 0),
(104, 7, 0, 0, 0, 0, 0, 0),
(105, 7, 0, 0, 0, 0, 0, 0),
(106, 7, 0, 0, 0, 0, 0, 0),
(107, 7, 0, 0, 0, 0, 0, 0),
(108, 7, 0, 0, 0, 0, 0, 0),
(109, 7, 0, 0, 0, 0, 0, 0),
(110, 7, 0, 0, 0, 0, 0, 0),
(111, 1, 0, 0, 0, 0, 0, 0),
(112, 1, 0, 0, 0, 0, 0, 0),
(113, 1, 0, 0, 0, 0, 0, 0),
(114, 1, 0, 0, 0, 0, 0, 0),
(115, 1, 0, 0, 0, 0, 0, 0),
(116, 1, 0, 0, 0, 0, 0, 0),
(117, 1, 0, 0, 0, 0, 0, 0),
(118, 1, 0, 0, 0, 0, 0, 0),
(119, 1, 0, 0, 0, 0, 0, 0),
(120, 1, 0, 0, 0, 0, 0, 0),
(121, 1, 0, 0, 0, 0, 0, 0),
(122, 1, 0, 0, 0, 0, 0, 0),
(123, 1, 0, 0, 0, 0, 0, 0),
(124, 1, 0, 0, 0, 0, 0, 0),
(125, 1, 0, 0, 0, 0, 0, 0),
(126, 1, 0, 0, 0, 0, 0, 0),
(127, 1, 0, 0, 0, 0, 0, 0),
(128, 1, 0, 0, 0, 0, 0, 0),
(129, 1, 0, 0, 0, 0, 0, 0),
(130, 1, 0, 0, 0, 0, 0, 0),
(131, 2, 10, 100, 180, 0, 5, 0),
(132, 6, 0, 0, 0, 0, 0, 0),
(133, 6, 0, 0, 0, 0, 0, 0),
(134, 6, 0, 0, 0, 0, 0, 0),
(135, 6, 0, 0, 0, 0, 0, 0),
(136, 6, 0, 0, 0, 0, 0, 0),
(137, 6, 0, 0, 0, 0, 0, 0),
(138, 6, 0, 0, 0, 0, 0, 0),
(139, 6, 0, 0, 0, 0, 0, 0),
(140, 6, 0, 0, 0, 0, 0, 0),
(141, 6, 0, 0, 0, 0, 0, 0),
(142, 6, 0, 0, 0, 0, 0, 0),
(143, 6, 0, 0, 0, 0, 0, 0),
(144, 6, 0, 0, 0, 0, 0, 0),
(145, 6, 0, 0, 0, 0, 0, 0),
(146, 6, 0, 0, 0, 0, 0, 0),
(147, 6, 0, 0, 0, 0, 0, 0),
(148, 6, 0, 0, 0, 0, 0, 0),
(149, 6, 0, 0, 0, 0, 0, 0),
(150, 6, 0, 0, 0, 0, 0, 0),
(151, 6, 0, 0, 0, 0, 0, 0),
(152, 6, 0, 0, 0, 0, 0, 0),
(153, 6, 0, 0, 0, 0, 0, 0),
(154, 6, 0, 0, 0, 0, 0, 0),
(155, 6, 0, 0, 0, 0, 0, 0),
(156, 6, 0, 0, 0, 0, 0, 0),
(157, 6, 0, 0, 0, 0, 0, 0),
(158, 6, 0, 0, 0, 0, 0, 0),
(159, 6, 0, 0, 0, 0, 0, 0),
(160, 6, 0, 0, 0, 0, 0, 0),
(161, 6, 0, 0, 0, 0, 0, 0),
(162, 6, 0, 0, 0, 0, 0, 0),
(163, 6, 0, 0, 0, 0, 0, 0),
(164, 6, 0, 0, 0, 0, 0, 0),
(165, 6, 0, 0, 0, 0, 0, 0),
(166, 6, 0, 0, 0, 0, 0, 0),
(167, 6, 0, 0, 0, 0, 0, 0),
(168, 6, 0, 0, 0, 0, 0, 0),
(169, 6, 0, 0, 0, 0, 0, 0),
(170, 6, 0, 0, 0, 0, 0, 0),
(171, 6, 0, 0, 0, 0, 0, 0),
(172, 6, 0, 0, 0, 0, 0, 0),
(173, 6, 0, 0, 0, 0, 0, 0),
(174, 6, 0, 0, 0, 0, 0, 0),
(175, 6, 0, 0, 0, 0, 0, 0),
(176, 6, 0, 0, 0, 0, 0, 0),
(177, 6, 0, 0, 0, 0, 0, 0),
(178, 6, 0, 0, 0, 0, 0, 0),
(179, 6, 0, 0, 0, 0, 0, 0),
(180, 6, 0, 0, 0, 0, 0, 0),
(181, 6, 0, 0, 0, 0, 0, 0),
(182, 6, 0, 0, 0, 0, 0, 0),
(183, 6, 0, 0, 0, 0, 0, 0),
(184, 6, 0, 0, 0, 0, 0, 0),
(185, 6, 0, 0, 0, 0, 0, 0),
(186, 6, 0, 0, 0, 0, 0, 0),
(187, 6, 0, 0, 0, 0, 0, 0),
(188, 6, 0, 0, 0, 0, 0, 0),
(189, 6, 0, 0, 0, 0, 0, 0),
(190, 6, 0, 0, 0, 0, 0, 0),
(191, 6, 0, 0, 0, 0, 0, 0),
(192, 6, 0, 0, 0, 0, 0, 0),
(193, 6, 0, 0, 0, 0, 0, 0),
(194, 6, 0, 0, 0, 0, 0, 0),
(195, 6, 0, 0, 0, 0, 0, 0),
(196, 6, 0, 0, 0, 0, 0, 0),
(197, 6, 0, 0, 0, 0, 0, 0),
(198, 6, 0, 0, 0, 0, 0, 0),
(199, 6, 0, 0, 0, 0, 0, 0),
(200, 6, 0, 0, 0, 0, 0, 0);

NSERT INTO `edt_semaines` VALUES (1, 1, 'A'),
(2, 2, 'A'),
(3, 3, 'A'),
(4, 4, 'A'),
(5, 5, 'A'),
(6, 6, 'A'),
(7, 7, 'A'),
(8, 8, 'A'),
(9, 9, 'A'),
(10, 10, 'A'),
(11, 11, 'A'),
(12, 12, 'A'),
(13, 13, 'A'),
(14, 14, 'A'),
(15, 15, 'A'),
(16, 16, 'A'),
(17, 17, 'A'),
(18, 18, 'A'),
(19, 19, 'A'),
(20, 20, 'A'),
(21, 21, 'A'),
(22, 22, 'A'),
(23, 23, 'A'),
(24, 24, 'A'),
(25, 25, 'A'),
(26, 26, 'A'),
(27, 27, 'A'),
(28, 28, 'A'),
(29, 29, 'A'),
(30, 30, 'A'),
(31, 31, 'A'),
(32, 32, 'A'),
(33, 33, 'A'),
(34, 34, 'A'),
(35, 35, 'A'),
(36, 36, 'A'),
(37, 37, 'A'),
(38, 38, 'A'),
(39, 39, 'A'),
(40, 40, 'A'),
(41, 41, 'A'),
(42, 42, 'A'),
(43, 43, 'A'),
(44, 44, 'A'),
(45, 45, 'A'),
(46, 46, 'A'),
(47, 47, 'A'),
(48, 48, 'A'),
(49, 49, 'A'),
(50, 50, 'A'),
(51, 51, 'A'),
(52, 52, 'A');
