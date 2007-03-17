DROP TABLE IF EXISTS `absences`;
CREATE TABLE `absences` (`login` varchar(50) NOT NULL default '', `periode` int(11) NOT NULL default '0', `nb_absences` char(2) NOT NULL default '', `non_justifie` char(2) NOT NULL default '', `nb_retards` char(2) NOT NULL default '', `appreciation` text NOT NULL, PRIMARY KEY  (`login`,`periode`));
DROP TABLE IF EXISTS `absences_gep`;
CREATE TABLE `absences_gep` ( `id_seq` char(2) NOT NULL default '', `type` char(1) NOT NULL default '', PRIMARY KEY  (`id_seq`));
DROP TABLE IF EXISTS `aid`;
CREATE TABLE `aid` (`id` varchar(100) NOT NULL default '', `nom` varchar(100) NOT NULL default '', `numero` varchar(8) NOT NULL default '0', `indice_aid` int(11) NOT NULL default '0', PRIMARY KEY  (`id`));
DROP TABLE IF EXISTS `aid_appreciations`;
CREATE TABLE `aid_appreciations` ( `login` varchar(50) NOT NULL default '', `id_aid` varchar(100) NOT NULL default '', `periode` int(11) NOT NULL default '0', `appreciation` text NOT NULL, `statut`  char(10) NOT NULL default '', `note` float default NULL, `indice_aid` int(11) NOT NULL default '0', PRIMARY KEY  (`login`,`id_aid`,`periode`));
DROP TABLE IF EXISTS `aid_config`;
CREATE TABLE `aid_config` (`nom` char(100) NOT NULL default '', `nom_complet` char(100) NOT NULL default '', `note_max` int(11) NOT NULL default '0', `order_display1` char(1) NOT NULL default '0', `order_display2` int(11) NOT NULL default '0', `type_note` char(5) NOT NULL default '', `display_begin` int(11) NOT NULL default '0', `display_end` int(11) NOT NULL default '0', `message` char(20) NOT NULL default '', `display_nom` char(1) NOT NULL default '', `indice_aid` int(11) NOT NULL default '0', `display_bulletin` char(1) NOT NULL default 'y', PRIMARY KEY  (`indice_aid`));
DROP TABLE IF EXISTS `avis_conseil_classe`;
CREATE TABLE `avis_conseil_classe` (`login` varchar(50) NOT NULL default '', `periode` int(11) NOT NULL default '0', `avis` text NOT NULL default '', `statut`  varchar(10) NOT NULL default '', PRIMARY KEY  (`login`,`periode`), KEY `login` (`login`,`periode`));
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes` (`id` smallint(6) unsigned NOT NULL auto_increment, `classe` varchar(100) NOT NULL default '', `nom_complet` varchar(100) NOT NULL default '', `suivi_par` varchar(50) NOT NULL default '', `formule` varchar(100) NOT NULL default '', `format_nom` varchar(5) NOT NULL default '', `display_rang` char(1) NOT NULL default 'n', `display_address` char(1) NOT NULL default 'n', `display_coef` char(1) NOT NULL default 'y', `display_mat_cat` CHAR(1) NOT NULL default 'n', `display_nbdev` char(1) NOT NULL default 'n', `display_moy_gen` char(1) NOT NULL default 'y', PRIMARY KEY `id` (`id`));
DROP TABLE IF EXISTS `cn_cahier_notes`;
CREATE TABLE `cn_cahier_notes` ( `id_cahier_notes` int(11) NOT NULL auto_increment, `id_groupe` INT(11) NOT NULL, `periode` int(11) NOT NULL default '0', PRIMARY KEY  (`id_cahier_notes`, `id_groupe`, `periode`));
DROP TABLE IF EXISTS `cn_conteneurs`;
CREATE TABLE `cn_conteneurs` ( `id` int(11) NOT NULL auto_increment, `id_racine` int(11) NOT NULL default '0', `nom_court` varchar(32) NOT NULL default '', `nom_complet` varchar(64) NOT NULL default '', `description` varchar(128) NOT NULL default '', `mode` char(1) NOT NULL default '2', `coef` decimal(3,1) NOT NULL default '1.0', `arrondir` char(2) NOT NULL default 's1', `ponderation` decimal(3,1) NOT NULL default '0.0', `display_parents` char(1) NOT NULL default '0', `display_bulletin` char(1) NOT NULL default '1', `parent` int(11) NOT NULL default '0', PRIMARY KEY  (`id`));
DROP TABLE IF EXISTS `cn_devoirs`;
CREATE TABLE `cn_devoirs` (`id` int(11) NOT NULL auto_increment, `id_conteneur` int(11) NOT NULL default '0', `id_racine` int(11) NOT NULL default '0', `nom_court` varchar(32) NOT NULL default '', `nom_complet` varchar(64) NOT NULL default '', `description` varchar(128) NOT NULL default '', `facultatif` char(1) NOT NULL default '', `date` datetime NOT NULL default '0000-00-00 00:00:00', `coef` decimal(3,1) NOT NULL default '0.0', `display_parents` char(1) NOT NULL default '', PRIMARY KEY  (`id`));
DROP TABLE IF EXISTS `cn_notes_conteneurs`;
CREATE TABLE `cn_notes_conteneurs` ( `login` varchar(50) NOT NULL default '', `id_conteneur` int(11) NOT NULL default '0', `note` float(10,1) NOT NULL default '0.0', `statut` char(1) NOT NULL default '', `comment` text NOT NULL, PRIMARY KEY  (`login`,`id_conteneur`));
DROP TABLE IF EXISTS `cn_notes_devoirs`;
CREATE TABLE `cn_notes_devoirs` ( `login` varchar(50) NOT NULL default '', `id_devoir` int(11) NOT NULL default '0', `note` float(10,1) NOT NULL default '0.0', `comment` text NOT NULL, `statut` varchar(4) NOT NULL default '', PRIMARY KEY  (`login`,`id_devoir`));
DROP TABLE IF EXISTS `ct_devoirs_entry`;
CREATE TABLE `ct_devoirs_entry` ( `id_ct` int(11) NOT NULL auto_increment, `id_groupe` INT(11) NOT NULL, `date_ct` int(11) NOT NULL default '0', `id_login` varchar(32) NOT NULL default '', `contenu` text NOT NULL, KEY `id_ct` (`id_ct`,`id_groupe`));
DROP TABLE IF EXISTS `ct_documents`;
CREATE TABLE `ct_documents` ( `id` int(11) NOT NULL auto_increment, `id_ct` int(11) NOT NULL default '0', `titre` varchar(255) NOT NULL default '', `taille` int(11) NOT NULL default '0', `emplacement` varchar(255) NOT NULL default '', PRIMARY KEY  (`id`));
DROP TABLE IF EXISTS `ct_entry`;
CREATE TABLE `ct_entry` ( `id_ct` int(11) NOT NULL auto_increment, `heure_entry` time NOT NULL default '00:00:00', `id_groupe` INT(11) NOT NULL, `date_ct` int(11) NOT NULL default '0', `id_login` varchar(32) NOT NULL default '', `contenu` text NOT NULL, KEY `id_ct` (`id_ct`, `id_groupe`));
DROP TABLE IF EXISTS `ct_types_documents`;
CREATE TABLE `ct_types_documents` ( `id_type` bigint(21) NOT NULL auto_increment, `titre` text NOT NULL, `extension` varchar(10) NOT NULL default '', `upload` enum('oui','non') NOT NULL default 'oui', PRIMARY KEY  (`id_type`), UNIQUE KEY `extension` (`extension`));
DROP TABLE IF EXISTS `droits`;
CREATE TABLE `droits` ( `id` varchar(200) NOT NULL default '', `administrateur` char(1) NOT NULL default '', `professeur` char(1) NOT NULL default '', `cpe` char(1) NOT NULL default '', `scolarite` char(1) NOT NULL default '', `eleve` char(1) NOT NULL default '', `responsable` char(1) NOT NULL default '', `secours` char(1) NOT NULL default '', `description` varchar(255) NOT NULL default '', `statut` char(1) NOT NULL default '', PRIMARY KEY  (`id`));
DROP TABLE IF EXISTS `eleves`;
CREATE TABLE `eleves` ( `no_gep` text, `login` varchar(50) NOT NULL default '', `nom`  varchar(50) NOT NULL default '', `prenom`  varchar(50) NOT NULL default '', `sexe`  varchar(1) NOT NULL default '', `naissance` date, `elenoet` varchar(50) NOT NULL default '', `ereno` varchar(50) NOT NULL default '', `ele_id` varchar(10) NOT NULL default '', `email` varchar(255) NOT NULL default '', PRIMARY KEY  (`login`));
DROP TABLE IF EXISTS `etablissements`;
CREATE TABLE `etablissements` ( `id` char(8) NOT NULL default '', `nom` char(50) NOT NULL default '', `niveau` char(50) NOT NULL default '', `type` char(50) NOT NULL default '', `cp` int(10) NOT NULL default '0', `ville` char(50) NOT NULL default '', PRIMARY KEY  (`id`));
DROP TABLE IF EXISTS `j_aid_eleves`;
CREATE TABLE `j_aid_eleves` ( `id_aid` varchar(100) NOT NULL default '', `login` varchar(60) NOT NULL default '', `indice_aid` int(11) NOT NULL default '0', PRIMARY KEY  (`id_aid`,`login`));
DROP TABLE IF EXISTS `j_aid_utilisateurs`;
CREATE TABLE `j_aid_utilisateurs` ( `id_aid` varchar(100) NOT NULL default '', `id_utilisateur` varchar(50) NOT NULL default '', `indice_aid` int(11) NOT NULL default '0', PRIMARY KEY  (`id_aid`,`id_utilisateur`));
DROP TABLE IF EXISTS `j_eleves_classes`;
CREATE TABLE `j_eleves_classes` ( `login` varchar(50) NOT NULL default '', `id_classe` int(11) NOT NULL default '0', `periode` int(11) NOT NULL default '0', `rang` smallint(6) NOT NULL default '0', PRIMARY KEY  (`login`,`id_classe`,`periode`));
DROP TABLE IF EXISTS `j_eleves_etablissements`;
CREATE TABLE `j_eleves_etablissements` ( `id_eleve` varchar(50) NOT NULL default '', `id_etablissement` varchar(8) NOT NULL default '',  PRIMARY KEY  (`id_eleve`,`id_etablissement`));
DROP TABLE IF EXISTS `j_eleves_professeurs`;
CREATE TABLE `j_eleves_professeurs` ( `login` varchar(50) NOT NULL default '', `professeur` varchar(50) NOT NULL default '', `id_classe` int(11) NOT NULL default '0', PRIMARY KEY  (`login`,`professeur`,`id_classe`));
DROP TABLE IF EXISTS `j_eleves_regime`;
CREATE TABLE `j_eleves_regime` ( `login` varchar(50) NOT NULL default '', `doublant` char(1) NOT NULL default '', `regime` varchar(5) NOT NULL default '', PRIMARY KEY  (`login`));
DROP TABLE IF EXISTS `j_matieres_categories_classes`;
CREATE TABLE `j_matieres_categories_classes` ( `categorie_id` int(11) NOT NULL default '0', `classe_id` int(11) NOT NULL default '0', `priority` smallint(6) NOT NULL default '0', `affiche_moyenne` tinyint(1) NOT NULL default '0', PRIMARY KEY  (`categorie_id`,`classe_id`));
DROP TABLE IF EXISTS `j_professeurs_matieres`;
CREATE TABLE `j_professeurs_matieres` ( `id_professeur` varchar(50) NOT NULL default '', `id_matiere` varchar(50) NOT NULL default '', `ordre_matieres` int(11) NOT NULL default '0', PRIMARY KEY  (`id_professeur`,`id_matiere`));
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` ( `LOGIN` varchar(50) NOT NULL default '', `START` datetime NOT NULL default '0000-00-00 00:00:00', `SESSION_ID` varchar(64) NOT NULL default '', `REMOTE_ADDR` varchar(16) NOT NULL default '', `USER_AGENT` varchar(64) NOT NULL default '', `REFERER` varchar(64) NOT NULL default '', `AUTOCLOSE` enum('0','1','2','3','4') NOT NULL default '0', `END` datetime NOT NULL default '0000-00-00 00:00:00', PRIMARY KEY  (`SESSION_ID`,`START`));
DROP TABLE IF EXISTS `matieres`;
CREATE TABLE `matieres` ( `matiere` varchar(255) NOT NULL default '', `nom_complet` varchar(200) NOT NULL default '', `priority` smallint(6) NOT NULL default '0', `categorie_id` INT NOT NULL default '1', PRIMARY KEY  (`matiere`));
DROP TABLE IF EXISTS `matieres_appreciations`;
CREATE TABLE `matieres_appreciations` ( `login` varchar(50) NOT NULL default '', `id_groupe` int(11) NOT NULL default '0', `periode` int(11) NOT NULL default '0', `appreciation` text NOT NULL, PRIMARY KEY  (`login`,`id_groupe`,`periode`));
DROP TABLE IF EXISTS `matieres_notes`;
CREATE TABLE `matieres_notes` ( `login` varchar(50) NOT NULL default '', `id_groupe` int(11) NOT NULL default '0', `periode` int(11) NOT NULL default '0', `note` float(10,1) default NULL, `statut` varchar(10) NOT NULL default '', `rang` smallint(6) NOT NULL default '0', PRIMARY KEY  (`login`,`id_groupe`,`periode`));
DROP TABLE IF EXISTS `matieres_categories`;
CREATE TABLE `matieres_categories` (`id` int(11) NOT NULL AUTO_INCREMENT, `nom_court` varchar(255) NOT NULL default '', `nom_complet` varchar(255) NOT NULL default '', `priority` smallint(6) NOT NULL default '0', PRIMARY KEY (`id`));
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` ( `id` int(11) NOT NULL auto_increment, `texte` text NOT NULL, `date_debut` int(11) NOT NULL default '0', `date_fin` int(11) NOT NULL default '0', `auteur` varchar(50) NOT NULL default '', `destinataires` varchar(10) NOT NULL default '', PRIMARY KEY  (`id`));
DROP TABLE IF EXISTS `periodes`;
CREATE TABLE `periodes` ( `nom_periode` varchar(50) NOT NULL default '', `num_periode` int(11) NOT NULL default '0', `verouiller` char(1) NOT NULL default '', `id_classe` int(11) NOT NULL default '0', PRIMARY KEY  (`num_periode`,`id_classe`));
DROP TABLE IF EXISTS `responsables`;
CREATE TABLE `responsables` ( `ereno` varchar(10) NOT NULL default '', `nom1` varchar(20) NOT NULL default '', `prenom1` varchar(20) NOT NULL default '', `adr1` varchar(100) NOT NULL default '', `adr1_comp` varchar(100) NOT NULL default '', `commune1` varchar(50) NOT NULL default '', `cp1` varchar(6) NOT NULL default '', `nom2` varchar(20) NOT NULL default '', `prenom2` varchar(20) NOT NULL default '', `adr2` varchar(100) NOT NULL default '', `adr2_comp` varchar(100) NOT NULL default '', `commune2` varchar(50) NOT NULL default '', `cp2` varchar(6) NOT NULL default '', PRIMARY KEY  (`ereno`));
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` ( `NAME` varchar(32) NOT NULL default '', `VALUE` text NOT NULL, PRIMARY KEY  (`NAME`));
DROP TABLE IF EXISTS `temp_gep_import`;
CREATE TABLE `temp_gep_import` ( `ID_TEMPO` varchar(40) NOT NULL default '', `LOGIN` varchar(40) NOT NULL default '', `ELENOM` varchar(40) NOT NULL default '', `ELEPRE` varchar(40) NOT NULL default '', `ELESEXE` varchar(40) NOT NULL default '', `ELEDATNAIS` varchar(40) NOT NULL default '', `ELENOET` varchar(40) NOT NULL default '', `ERENO` varchar(40) NOT NULL default '', `ELEDOUBL` varchar(40) NOT NULL default '', `ELENONAT` varchar(40) NOT NULL default '', `ELEREG` varchar(40) NOT NULL default '', `DIVCOD` varchar(40) NOT NULL default '', `ETOCOD_EP` varchar(40) NOT NULL default '', `ELEOPT1` varchar(40) NOT NULL default '', `ELEOPT2` varchar(40) NOT NULL default '', `ELEOPT3` varchar(40) NOT NULL default '', `ELEOPT4` varchar(40) NOT NULL default '', `ELEOPT5` varchar(40) NOT NULL default '', `ELEOPT6` varchar(40) NOT NULL default '', `ELEOPT7` varchar(40) NOT NULL default '', `ELEOPT8` varchar(40) NOT NULL default '', `ELEOPT9` varchar(40) NOT NULL default '', `ELEOPT10` varchar(40) NOT NULL default '', `ELEOPT11` varchar(40) NOT NULL default '', `ELEOPT12` varchar(40) NOT NULL default '');
DROP TABLE IF EXISTS `tempo`;
CREATE TABLE `tempo` ( `id_classe` int(11) NOT NULL default '0', `max_periode` int(11) NOT NULL default '0', `num` char(32) NOT NULL default '0');
DROP TABLE IF EXISTS `tempo2`;
CREATE TABLE `tempo2` ( `col1` varchar(100) NOT NULL default '', `col2` varchar(100) NOT NULL default '');
DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` ( `login` varchar(50) NOT NULL default '', `nom` varchar(50) NOT NULL default '', `prenom` varchar(50) NOT NULL default '', `civilite` varchar(5) NOT NULL default '', `password` varchar(32) NOT NULL default '', `email` varchar(50) NOT NULL default '', `show_email` varchar(3) NOT NULL default 'no', `statut` varchar(20) NOT NULL default '', `etat` varchar(20) NOT NULL default '', `change_mdp` char(1) NOT NULL default 'n', `date_verrouillage` datetime NOT NULL default '2006-01-01 00:00:00', `password_ticket` varchar(255) NOT NULL, `ticket_expiration` datetime NOT NULL, PRIMARY KEY  (`login`));
DROP TABLE IF EXISTS j_eleves_cpe;
CREATE TABLE j_eleves_cpe (e_login varchar(50) NOT NULL default '', cpe_login varchar(50) NOT NULL default '', PRIMARY KEY  (e_login,cpe_login));
DROP TABLE IF EXISTS suivi_eleve_cpe;
CREATE TABLE `suivi_eleve_cpe` (`id_suivi_eleve_cpe` int(11) NOT NULL auto_increment, `eleve_suivi_eleve_cpe` varchar(30) NOT NULL default '', `parqui_suivi_eleve_cpe` varchar(150) NOT NULL, `date_suivi_eleve_cpe` date NOT NULL default '0000-00-00', `heure_suivi_eleve_cpe` time NOT NULL, `komenti_suivi_eleve_cpe` text NOT NULL, `niveau_message_suivi_eleve_cpe` varchar(1) NOT NULL, `action_suivi_eleve_cpe` VARCHAR( 2 ) NOT NULL, PRIMARY KEY (`id_suivi_eleve_cpe`));
DROP TABLE IF EXISTS absences_eleves;
CREATE TABLE `absences_eleves` (`id_absence_eleve` int(11) NOT NULL auto_increment, `type_absence_eleve` char(1) NOT NULL default '', `eleve_absence_eleve` varchar(25) NOT NULL default '0', `justify_absence_eleve` char(3) NOT NULL default '', `info_justify_absence_eleve` text NOT NULL, `motif_absence_eleve` varchar(4) NOT NULL default '', `info_absence_eleve` text NOT NULL, `d_date_absence_eleve` date NOT NULL default '0000-00-00', `a_date_absence_eleve` date default NULL, `d_heure_absence_eleve` time default NULL, `a_heure_absence_eleve` time default NULL, `saisie_absence_eleve` varchar(50) NOT NULL default '', PRIMARY KEY  (`id_absence_eleve`));
DROP TABLE IF EXISTS absences_creneaux;
CREATE TABLE `absences_creneaux` (`id_definie_periode` int(11) NOT NULL auto_increment, `nom_definie_periode` varchar(10) NOT NULL default '', `heuredebut_definie_periode` time NOT NULL default '00:00:00', `heurefin_definie_periode` time NOT NULL default '00:00:00', PRIMARY KEY  (`id_definie_periode`));
DROP TABLE IF EXISTS absences_motifs;
CREATE TABLE `absences_motifs` (`id_motif_absence` int(11) NOT NULL auto_increment, `init_motif_absence` char(2) NOT NULL default '', `def_motif_absence` varchar(255) NOT NULL default '', PRIMARY KEY  (`id_motif_absence`));
DROP TABLE IF EXISTS groupes;
CREATE TABLE `groupes` (`id` int(11) NOT NULL auto_increment, `name` varchar(60) NOT NULL default '', `description` text NOT NULL, `recalcul_rang` varchar(10) NOT NULL default '', PRIMARY KEY  (`id`));
DROP TABLE IF EXISTS j_groupes_classes;
CREATE TABLE `j_groupes_classes` (`id_groupe` int(11) NOT NULL default '0', `id_classe` int(11) NOT NULL default '0', `priorite` smallint(6) NOT NULL, `coef` decimal(3,1) NOT NULL, `categorie_id` int(11) NOT NULL default '1', PRIMARY KEY  (`id_groupe`,`id_classe`));
DROP TABLE IF EXISTS j_groupes_matieres;
CREATE TABLE `j_groupes_matieres` (`id_groupe` int(11) NOT NULL default '0',`id_matiere` varchar(50) NOT NULL default '', PRIMARY KEY  (`id_groupe`,`id_matiere`));
DROP TABLE IF EXISTS j_groupes_professeurs;
CREATE TABLE `j_groupes_professeurs` (`id_groupe` int(11) NOT NULL default '0',`login` varchar(50) NOT NULL default '', `ordre_prof` smallint(6) NOT NULL default '0', PRIMARY KEY  (`id_groupe`,`login`));
DROP TABLE IF EXISTS j_eleves_groupes;
CREATE TABLE `j_eleves_groupes` (`login` varchar(50) NOT NULL default '', `id_groupe` int(11) NOT NULL default '0', `periode` int(11) NOT NULL default '0', PRIMARY KEY  (`id_groupe`,`login`,`periode`));
DROP TABLE IF EXISTS eleves_groupes_settings;
CREATE TABLE `eleves_groupes_settings` (login varchar(50) NOT NULL, id_groupe int(11) NOT NULL, `name` varchar(50) NOT NULL, `value` varchar(50) NOT NULL, PRIMARY KEY  (`id_groupe`,`login`,`name`));
CREATE TABLE IF NOT EXISTS `preferences` (`login` VARCHAR( 50 ) NOT NULL ,`name` VARCHAR( 32 ) NOT NULL ,`value` TEXT NOT NULL);
DROP TABLE IF EXISTS j_scol_classes;
CREATE TABLE `j_scol_classes` (`login` VARCHAR( 50 ) NOT NULL ,`id_classe` INT( 11 ) NOT NULL);
DROP TABLE IF EXISTS miseajour;
CREATE TABLE `miseajour` (`id_miseajour` int(11) NOT NULL auto_increment, `fichier_miseajour` varchar(250) NOT NULL, `emplacement_miseajour` varchar(250) NOT NULL, `date_miseajour` date NOT NULL, `heure_miseajour` time NOT NULL, PRIMARY KEY  (`id_miseajour`));
DROP TABLE IF EXISTS absences_actions;
CREATE TABLE `absences_actions` (`id_absence_action` int(11) NOT NULL auto_increment, `init_absence_action` char(2) NOT NULL default '', `def_absence_action` varchar(255) NOT NULL default '', PRIMARY KEY  (`id_absence_action`));
DROP TABLE IF EXISTS edt_classes;
CREATE TABLE `edt_classes` (`id_edt_classe` int(11) NOT NULL auto_increment, `groupe_edt_classe` int(11) NOT NULL, `prof_edt_classe` varchar(25) NOT NULL, `matiere_edt_classe` varchar(10) NOT NULL, `semaine_edt_classe` varchar(5) NOT NULL, `jour_edt_classe` tinyint(4) NOT NULL, `datedebut_edt_classe` date NOT NULL, `datefin_edt_classe` date NOT NULL, `heuredebut_edt_classe` time NOT NULL, `heurefin_edt_classe` time NOT NULL, `salle_edt_classe` varchar(50) NOT NULL, PRIMARY KEY  (`id_edt_classe`));
DROP TABLE IF EXISTS `responsables2`;
CREATE TABLE IF NOT EXISTS `responsables2` (
	`ele_id` varchar(10) NOT NULL,
	`pers_id` varchar(10) NOT NULL,
	`resp_legal` varchar(1) NOT NULL,
	`pers_contact` varchar(1) NOT NULL
	);
DROP TABLE IF EXISTS `resp_adr`;
CREATE TABLE IF NOT EXISTS `resp_adr` (
	`adr_id` varchar(10) NOT NULL,
	`adr1` varchar(100) NOT NULL,
	`adr2` varchar(100) NOT NULL,
	`adr3` varchar(100) NOT NULL,
	`adr4` varchar(100) NOT NULL,
	`cp` varchar(6) NOT NULL,
	`pays` varchar(50) NOT NULL,
	`commune` varchar(50) NOT NULL,
	PRIMARY KEY  (`adr_id`)
	);
DROP TABLE IF EXISTS `resp_pers`;
CREATE TABLE IF NOT EXISTS `resp_pers` (
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
	);
