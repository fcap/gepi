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

?>
