<?php
/*
 * $Id$
 *
 * Fichier de mise � jour de la version 1.5.4 � la version 1.5.5
 * Le code PHP pr�sent ici est ex�cut� tel quel.
 * Pensez � conserver le code parfaitement compatible pour une application
 * multiple des mises � jour. Toute modification ne doit �tre r�alis�e qu'apr�s
 * un test pour s'assurer qu'elle est n�cessaire.
 *
 * Le r�sultat de la mise � jour est du html pr�format�. Il doit �tre concat�n�
 * dans la variable $result, qui est d�j� initialis�.
 *
 * Exemple : $result .= "<font color='gree'>Champ XXX ajout� avec succ�s</font>";
 */

$result .= "<br /><br /><b>Mise � jour vers la version 1.5.5" . $rc . $beta . " :</b><br />";

//===================================================
/*

// Exemples de sections:

// Ajout d'un champ dans setting

$req_test=mysql_query("SELECT value FROM setting WHERE name = 'cas_attribut_prenom'");
$res_test=mysql_num_rows($req_test);
if ($res_test==0){
  $result_inter = traite_requete("INSERT INTO setting VALUES ('cas_attribut_prenom', '');");
  if ($result_inter == '') {
    $result.="<font color=\"green\">D�finition du param�tre cas_attribut_prenom : Ok !</font><br />";
  } else {
    $result.="<font color=\"red\">D�finition du param�tre cas_attribut_prenom : Erreur !</font><br />";
  }
} else {
  $result .= "<font color=\"blue\">Le param�tre cas_attribut_prenom existe d�j� dans la table setting.</font><br />";
}

//===================================================

// Ajout d'une table

$result .= "<br /><br /><b>Ajout d'une table modeles_grilles_pdf :</b><br />";
$test = sql_query1("SHOW TABLES LIKE 'modeles_grilles_pdf'");
if ($test == -1) {
	$result_inter = traite_requete("CREATE TABLE IF NOT EXISTS modeles_grilles_pdf (
		id_modele INT(11) NOT NULL auto_increment,
		login varchar(50) NOT NULL default '',
		nom_modele varchar(255) NOT NULL,
		par_defaut ENUM('y','n') DEFAULT 'n',
		PRIMARY KEY (id_modele)
		);");
	if ($result_inter == '') {
		$result .= "<font color=\"green\">SUCCES !</font><br />";
	}
	else {
		$result .= "<font color=\"red\">ECHEC !</font><br />";
	}
} else {
		$result .= "<font color=\"blue\">La table existe d�j�</font><br />";
}

//===================================================

// Ajout d'un champ � une table

$result .= "&nbsp;->Ajout d'un champ id_lieu � la table 'a_types'<br />";
$test_date_decompte=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_types LIKE 'id_lieu';"));
if ($test_date_decompte>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
}
else {
	$query = mysql_query("ALTER TABLE a_types ADD id_lieu INTEGER(11) COMMENT 'cle etrangere du lieu ou se trouve l\'eleve' AFTER commentaire,
       ADD INDEX a_types_FI_1 (id_lieu),
       ADD CONSTRAINT a_types_FK_1
		FOREIGN KEY (id_lieu)
		REFERENCES a_lieux (id)
		ON DELETE SET NULL ;");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
}

*/
//===================================================

$result .= "<br /><br /><b>Ajout d'une table 'j_groupes_visibilite' :</b><br />";
$test = sql_query1("SHOW TABLES LIKE 'j_groupes_visibilite'");
if ($test == -1) {
	$result_inter = traite_requete("CREATE TABLE IF NOT EXISTS j_groupes_visibilite (
			id INT(11) NOT NULL auto_increment,
			id_groupe INT(11) NOT NULL,
			domaine varchar(255) NOT NULL default '',
			visible varchar(255) NOT NULL default '',
			PRIMARY KEY (id),
			INDEX id_groupe_domaine (id_groupe, domaine)
		);");
	if ($result_inter == '') {
		$result .= "<font color=\"green\">SUCCES !</font><br />";
	}
	else {
		$result .= "<font color=\"red\">ECHEC !</font><br />";
	}
} else {
		$result .= "<font color=\"blue\">La table existe d�j�</font><br />";
}

//===================================================

$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM ct_documents LIKE 'visible';"));
if ($test_champ>0) {
	$result .= "&nbsp;->Ajout d'un champ visible � la table 'ct_documents'<br />";
	$query = mysql_query("ALTER TABLE ct_documents DROP visible;");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'visible_eleve_parent' � la table 'ct_documents'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM ct_documents LIKE 'visible_eleve_parent';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
}
else {
	$query = mysql_query("ALTER TABLE ct_documents ADD visible_eleve_parent BOOLEAN DEFAULT true COMMENT 'Visibilit� �l�ve/parent du document joint' AFTER emplacement;");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
}

$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM ct_devoirs_documents LIKE 'visible';"));
if ($test_champ>0) {
	$result .= "&nbsp;->Suppression du champ 'visible' de la table 'ct_devoirs_documents'<br />";
	$query = mysql_query("ALTER TABLE ct_devoirs_documents DROP visible;");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'visible_eleve_parent' � la table 'ct_devoirs_documents'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM ct_devoirs_documents LIKE 'visible_eleve_parent';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
}
else {
	$query = mysql_query("ALTER TABLE ct_devoirs_documents ADD visible_eleve_parent BOOLEAN DEFAULT true COMMENT 'Visibilit� �l�ve/parent du document joint' AFTER emplacement;");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
}

//===================================================

$result .= "&nbsp;->Ajout d'un champ 'date_visibilite_eleve' � la table 'ct_devoirs_entry'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM ct_devoirs_entry LIKE 'date_visibilite_eleve';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
}
else {
	$query = mysql_query("ALTER TABLE ct_devoirs_entry ADD date_visibilite_eleve TIMESTAMP NOT NULL default now() COMMENT 'Timestamp precisant quand les devoirs sont portes a la conaissance des eleves' AFTER id_sequence;");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
}

//===================================================

$result .= "<br /><br /><b>Mots de passe :</b><br />";
$result .= "&nbsp;->Ajout d'un champ 'salt' � la table 'utilisateur' et allongement du champs password<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM utilisateurs LIKE 'salt';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
}
else {
	$query = mysql_query("ALTER TABLE utilisateurs ADD salt varchar(128) COMMENT 'sel pour le hmac du mot de passe' AFTER password;");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
	$query = mysql_query("ALTER TABLE utilisateurs MODIFY password varchar(128);");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
}

//===================================================

$result .= "<br /><br /><b>Messagerie :</b><br />";
$result .= "&nbsp;->Modification du champ 'destinataires' de la table 'messages' en 'statuts_destinataires'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM messages LIKE 'statuts_destinataires';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ est d�j� modifi�.</font><br />";
}
else {
	$query = mysql_query("ALTER TABLE messages CHANGE destinataires statuts_destinataires VARCHAR( 10 ) NOT NULL DEFAULT '';");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'login_destinataire' � la table 'messages'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM messages LIKE 'login_destinataire';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
}
else {
	$query = mysql_query("ALTER TABLE messages ADD login_destinataire VARCHAR( 50 ) NOT NULL default '' AFTER statuts_destinataires, ADD INDEX ( login_destinataire ) ;");
	if ($query) {
			$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur</font><br />";
	}
}

// Ajout d'index
$result .= "&nbsp;->Ajout de l'index 'login_destinataire' � la table 'messages'<br />";
$req_res=0;
$req_test = mysql_query("SHOW INDEX FROM messages ");
if (mysql_num_rows($req_test)!=0) {
	while ($enrg = mysql_fetch_object($req_test)) {
		if ($enrg-> Key_name == 'login_destinataire') {$req_res++;}
	}
}
if ($req_res == 0) {
	$query = mysql_query("ALTER TABLE messages ADD INDEX login_destinataire ( login_destinataire )");
	if ($query) {
		$result .= "<font color=\"green\">Ok !</font><br />";
	} else {
		$result .= "<font color=\"red\">Erreur</font><br />";
	}
} else {
	$result .= "<font color=\"blue\">L'index existe d�j�.</font><br />";
}

$result .= "<br /><br /><b>Ajout d'une table 's_travail_mesure' :</b><br />";
$test = sql_query1("SHOW TABLES LIKE 's_travail_mesure'");
if ($test == -1) {
	$result_inter = traite_requete("CREATE TABLE IF NOT EXISTS s_travail_mesure (id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,id_incident INT( 11 ) NOT NULL ,login_ele VARCHAR( 50 ) NOT NULL , travail TEXT NOT NULL);");
	if ($result_inter == '') {
		$result .= "<font color=\"green\">SUCCES !</font><br />";
	}
	else {
		$result .= "<font color=\"red\">ECHEC !</font><br />";
	}
} else {
		$result .= "<font color=\"blue\">La table existe d�j�</font><br />";
}

$result .= "<br /><br /><b>Absence 2 :</b><br />";
$result .= "&nbsp;->Ajout des champs versions a la table a_saisies<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_saisies LIKE 'version';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Les versions de saisies existent d�j�.</font><br />";
} else {
	 $query = mysql_query("ALTER TABLE a_saisies ADD (version INTEGER DEFAULT 0, version_created_at DATETIME, version_created_by VARCHAR(100));");
	if ($query) {
                $result .= "<font color=\"green\">Ok !</font><br />";
				$result .= "&nbsp;->Remplissage des champs version_created_at et version_created_by de la table a_saisies<br />";
				$query = mysql_query("UPDATE
												a_saisies
											SET
												version_created_at = created_at,
												version_created_by = utilisateur_id,
												version = 1
											WHERE
												version = 0");
				if ($query) {
					$result .= "<font color=\"green\">Ok !</font><br />";
				} else {
				    $result .= "<font color=\"red\">Erreur</font><br />";
				}
	} else {
                $result .= "<font color=\"red\">Erreur</font><br />";
	}
}


$test = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'a_saisies_version'"));
if ($test!=0) {
	$result .= "<font color=\"blue\">La table des versions de saisies existent d�j�.</font><br />";
} else {
	$query = mysql_query("CREATE TABLE a_saisies_version
(
	id INTEGER(11) NOT NULL,
	utilisateur_id VARCHAR(100) COMMENT 'Login de l\'utilisateur professionnel qui a saisi l\'absence',
	eleve_id INTEGER(11) COMMENT 'id_eleve de l\'eleve objet de la saisie, egal � null si aucun eleve n\'est saisi',
	commentaire TEXT COMMENT 'commentaire de l\'utilisateur',
	debut_abs DATETIME COMMENT 'Debut de l\'absence en timestamp UNIX',
	fin_abs DATETIME COMMENT 'Fin de l\'absence en timestamp UNIX',
	id_edt_creneau INTEGER(12) COMMENT 'identifiant du creneaux de l\'emploi du temps',
	id_edt_emplacement_cours INTEGER(12) COMMENT 'identifiant du cours de l\'emploi du temps',
	id_groupe INTEGER COMMENT 'identifiant du groupe pour lequel la saisie a ete effectuee',
	id_classe INTEGER COMMENT 'identifiant de la classe pour lequel la saisie a ete effectuee',
	id_aid INTEGER COMMENT 'identifiant de l\'aid pour lequel la saisie a ete effectuee',
	id_s_incidents INTEGER COMMENT 'identifiant de la saisie d\'incident discipline',
	id_lieu INTEGER(11) COMMENT 'cle etrangere du lieu ou se trouve l\'eleve',
	deleted_by VARCHAR(100) COMMENT 'Login de l\'utilisateur professionnel qui a supprim� la saisie',
	created_at DATETIME COMMENT 'Date de creation de la saisie',
	updated_at DATETIME COMMENT 'Date de modification de la saisie, y compris suppression, restauration et changement de version',
	deleted_at DATETIME,
	version INTEGER DEFAULT 0,
	version_created_at DATETIME,
	version_created_by VARCHAR(100),
	PRIMARY KEY (id,version),
	CONSTRAINT a_saisies_version_FK_1
		FOREIGN KEY (id)
		REFERENCES a_saisies (id)
		ON DELETE CASCADE
) ENGINE=MyISAM;");
	if ($query) {
                $result .= "<font color=\"green\">Ok !</font><br />";
                
				$result .= "&nbsp;->Remplissage de la table a_saisies_version<br />";
				$query = mysql_query("INSERT INTO a_saisies_version (id,utilisateur_id,eleve_id,commentaire,debut_abs,fin_abs,id_edt_creneau,id_edt_emplacement_cours,id_groupe,id_classe,id_aid,id_s_incidents,id_lieu,created_at,updated_at,version,version_created_at,version_created_by)
									SELECT                           id,utilisateur_id,eleve_id,commentaire,debut_abs,fin_abs,id_edt_creneau,id_edt_emplacement_cours,id_groupe,id_classe,id_aid,id_s_incidents,id_lieu,created_at,updated_at,version,version_created_at,version_created_by FROM a_saisies;");
				if ($query) {
					$result .= "<font color=\"green\">Ok !</font><br />";
				} else {
	                $result .= "<font color=\"red\">Erreur</font><br />";
				}
		
	} else {
                $result .= "<font color=\"red\">Erreur</font><br />";
	}
}

$result .= "&nbsp;->Suppression du champs inutile modifie_par_utilisateur_id de la table a_saisies<br />";
$query = mysql_query("ALTER TABLE `a_saisies` DROP `modifie_par_utilisateur_id` ;");
if ($query) {
		$result .= "<font color=\"blue\">Le champ modifie_par_utilisateur_id de la table a_saisies n'existe plus.</font><br />";
} else {
		$result .= "<font color=\"green\">Ok !</font><br />";
}

$result .= "&nbsp;->Suppression du champs inutile modifie_par_utilisateur_id de la table a_saisies_version<br />";
$query = mysql_query("ALTER TABLE `a_saisies_version` DROP `modifie_par_utilisateur_id` ;");
if ($query) {
		$result .= "<font color=\"blue\">Le champ modifie_par_utilisateur_id de la table a_saisies_version n'existe plus.</font><br />";
} else {
		$result .= "<font color=\"green\">Ok !</font><br />";
}

$result .= "&nbsp;->Ajout d'un champ 'deleted_at' � la table 'a_saisies'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_saisies LIKE 'deleted_at';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
} else {
	$query = mysql_query("ALTER TABLE `a_saisies` ADD deleted_at DATETIME AFTER updated_at;");
	if ($query) {
			$result .= "<font color=\"blue\">Le champ deleted_at de la table a_saisies a �t� ajout�.</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur : Le champ deleted_at de la table a_saisies n'a pas �t� ajout�</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'deleted_at' � la table 'a_saisies_version'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_saisies_version LIKE 'deleted_at';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
} else {
	$query = mysql_query("ALTER TABLE `a_saisies_version` ADD deleted_at DATETIME AFTER updated_at;");
	if ($query) {
			$result .= "<font color=\"blue\">Le champ deleted_at de la table a_saisies_version a �t� ajout�.</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur : Le champ deleted_at de la table a_saisies_version n'a pas �t� ajout�</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'deleted_by' � la table 'a_saisies'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_saisies LIKE 'deleted_by';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
} else {
	$query = mysql_query("ALTER TABLE `a_saisies` ADD deleted_by VARCHAR(100) AFTER id_lieu;");
	if ($query) {
			$result .= "<font color=\"blue\">Le champ deleted_by de la table a_saisies a �t� ajout�.</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur : Le champ deleted_by de la table a_saisies n'a pas �t� ajout�</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'deleted_by' � la table 'a_saisies_version'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_saisies_version LIKE 'deleted_by';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
} else {
	$query = mysql_query("ALTER TABLE `a_saisies_version` ADD deleted_by VARCHAR(100) AFTER id_lieu;");
	if ($query) {
			$result .= "<font color=\"blue\">Le champ deleted_by de la table a_saisies_version a �t� ajout�.</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur : Le champ deleted_by de la table a_saisies_version n'a pas �t� ajout�</font><br />";
	}
}


$test = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'a_agregation_decompte'"));
if ($test!=0) {
	$result .= "<font color=\"blue\">La table des agr�gation de d�compte de saisies existe d�j�.</font><br />";
} else {
	$result .= "<font color=\"blue\">Ajout de la table des agr�gation de d�compte de saisies.</font><br />";
	$query = mysql_query("CREATE TABLE a_agregation_decompte
(
	eleve_id INTEGER(11) NOT NULL COMMENT 'id de l\'eleve',
	date_demi_jounee DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL COMMENT 'Date de la demi journ�e agr�g�e : 00:00 pour une matin�e, 12:00 pour une apr�s midi',
	manquement_obligation_presence TINYINT DEFAULT 0 COMMENT 'Cette demi journ�e est compt�e comme absence',
	justifiee TINYINT DEFAULT 0 COMMENT 'Si cette demi journ�e est compt� comme absence, y a-t-il une justification',
	notifiee TINYINT DEFAULT 0 COMMENT 'Si cette demi journ�e est compt� comme absence, y a-t-il une notification � la famille',
	nb_retards INTEGER DEFAULT 0 COMMENT 'Nombre de retards d�compt�s dans la demi journ�e',
	nb_retards_justifies INTEGER DEFAULT 0 COMMENT 'Nombre de retards justifi�s d�compt�s dans la demi journ�e',
	motifs_absences TEXT COMMENT 'Liste des motifs (table a_motifs) associ�s � cette demi-journ�e d\'absence',
	motifs_retards TEXT COMMENT 'Liste des motifs (table a_motifs) associ�s aux retard de cette demi-journ�e',
	created_at DATETIME,
	updated_at DATETIME,
	PRIMARY KEY (eleve_id,date_demi_jounee),
	CONSTRAINT a_agregation_decompte_FK_1
		FOREIGN KEY (eleve_id)
		REFERENCES eleves (id_eleve)
		ON DELETE CASCADE
) ENGINE=MyISAM COMMENT='Table d\'agregation des decomptes de demi journees d\'absence et de retard';
	");
	if ($query) {
                $result .= "<font color=\"green\">Ok !</font><br />";
	} else {
                $result .= "<font color=\"red\">Erreur</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'deleted_at' � la table 'a_traitements'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_traitements LIKE 'deleted_at';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
} else {
	$query = mysql_query("ALTER TABLE `a_traitements` ADD deleted_at DATETIME AFTER updated_at;");
	if ($query) {
			$result .= "<font color=\"blue\">Le champ deleted_at de la table a_traitements a �t� ajout�.</font><br />";
	} else {
			$result .= "<font color=\"red\">Erreur : Le champ deleted_at de la table a_traitements n'a pas �t� ajout�</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'created_at' et 'updated_at' � la table 'a_types'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_types LIKE 'created_at';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Les champs existent d�j�.</font><br />";
} else {
	$query = mysql_query("ALTER TABLE `a_types` ADD (created_at DATETIME, updated_at DATETIME);");
	if ($query) {
			$result .= "<font color=\"blue\">Les champs ont �t�s ajout�s.</font><br />";
			$query = mysql_query("UPDATE a_types SET created_at = NOW(), updated_at = NOW();");
	} else {
			$result .= "<font color=\"red\">Erreur : Les champ created_at' et 'updated_at' de la table a_types n'ont pas �t�s ajout�s</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'created_at' et 'updated_at' � la table 'a_motifs'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_motifs LIKE 'created_at';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Les champs existent d�j�.</font><br />";
} else {
	$query = mysql_query("ALTER TABLE `a_motifs` ADD (created_at DATETIME, updated_at DATETIME);");
	if ($query) {
			$result .= "<font color=\"blue\">Les champs ont �t�s ajout�s.</font><br />";
			$query = mysql_query("UPDATE a_motifs SET created_at = NOW(), updated_at = NOW();");
	} else {
			$result .= "<font color=\"red\">Erreur : Les champ created_at' et 'updated_at' de la table a_motifs n'ont pas �t�s ajout�s</font><br />";
	}
}

$result .= "&nbsp;->Ajout d'un champ 'created_at' et 'updated_at' � la table 'a_justifications'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM a_justifications LIKE 'created_at';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Les champs existent d�j�.</font><br />";
} else {
	$query = mysql_query("ALTER TABLE `a_justifications` ADD (created_at DATETIME, updated_at DATETIME);");
	if ($query) {
			$result .= "<font color=\"blue\">Les champs ont �t�s ajout�s.</font><br />";
			$query = mysql_query("UPDATE a_justifications SET created_at = NOW(), updated_at = NOW();");
	} else {
			$result .= "<font color=\"red\">Erreur : Les champ created_at' et 'updated_at' de la table a_justifications n'ont pas �t�s ajout�s</font><br />";
	}
}

$result .= add_index('a_saisies','a_saisies_I_1','`deleted_at`');
$result .= add_index('a_saisies','a_saisies_I_2','`debut_abs`');
$result .= add_index('a_saisies','a_saisies_I_3','`fin_abs`');
$result .= add_index('a_traitements','a_traitements_I_1','`deleted_at`');

$result .= "<br /><br /><b>Ajout d'une table mentions :</b><br />";
$test = sql_query1("SHOW TABLES LIKE 'mentions'");
if ($test == -1) {
	$result_inter = traite_requete("CREATE TABLE IF NOT EXISTS mentions (
	id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	mention VARCHAR(255) NOT NULL);");
	if ($result_inter == '') {
		$result .= "<font color=\"green\">SUCCES !</font><br />";
	}
	else {
		$result .= "<font color=\"red\">ECHEC !</font><br />";
	}
} else {
		$result .= "<font color=\"blue\">La table existe d�j�</font><br />";
}

$result .= "<br /><br /><b>Ajout d'une table j_mentions_classes :</b><br />";
$test = sql_query1("SHOW TABLES LIKE 'j_mentions_classes'");
if ($test == -1) {
	$result_inter = traite_requete("CREATE TABLE IF NOT EXISTS j_mentions_classes (
id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
id_mention INT(11) NOT NULL ,
id_classe INT(11) NOT NULL ,
ordre TINYINT(4) NOT NULL);");
	if ($result_inter == '') {
		$result .= "<font color=\"green\">SUCCES !</font><br />";
	}
	else {
		$result .= "<font color=\"red\">ECHEC !</font><br />";
	}
} else {
		$result .= "<font color=\"blue\">La table existe d�j�</font><br />";
}

$result .= "&nbsp;->Ajout d'un champ 'id_mention' � la table 'avis_conseil_classe'<br />";
$test_champ=mysql_num_rows(mysql_query("SHOW COLUMNS FROM avis_conseil_classe LIKE 'id_mention';"));
if ($test_champ>0) {
	$result .= "<font color=\"blue\">Le champ existe d�j�.</font><br />";
} else {
	$query = mysql_query("ALTER TABLE avis_conseil_classe ADD id_mention INT( 11 ) NOT NULL DEFAULT '0' AFTER avis;");
	if ($query) {
		$result .= "<font color=\"blue\">Le champ id_mention de la table avis_conseil_classe a �t� ajout�.</font><br />";
	} else {
		$result .= "<font color=\"red\">Erreur : Le champ id_mention de la table avis_conseil_classe n'a pas �t� ajout�</font><br />";
	}
}
?>