<?php
/*
 * Last modification  : 20/08/2006
 *
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
require_once("../lib/initialisations.inc.php");

// Resume session

$resultat_session = resumeSession();
if ($resultat_session == 'c') {
    header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
    die();
} else if ($resultat_session == '0') {
    header("Location: ../logout.php?auto=1");
    die();
};

if (!checkAccess()) {
    header("Location: ../logout.php?auto=1");
    die();
}

//**************** EN-TETE *****************
$titre_page = "Outil de gestion";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

?>



<center>
<!--table width=700 border=2 cellspacing=1 bordercolor=#330033 cellpadding=5-->
<table width='700' class='bordercolor'>
<tr>
    <td width=200><a href="param_gen.php">Configuration g�n�rale</a></td>
    <td>Permet de modifier des param�tres g�n�raux (nom de l'�tablissement, adresse, ...).
    </td>
</tr>
<?php
$use_sso = getSettingValue('use_sso');
if ($use_sso == "ldap_scribe") {
    ?>
<tr>
    <td width=200><a href="../init_scribe/index.php">Initialisation � partir de l'annuaire LDAP du serveur Eole Scribe</a></td>
    <td>Permet d'importer les donn�es �l�ves, classes, professeurs, mati�res directement depuis le serveur LDAP de Scribe.
    </td>
</tr>
<?php
} else if ($use_sso == "lcs") {
    ?>
<tr>
    <td width=200><a href="../init_lcs/index.php">Initialisation � partir de l'annuaire LDAP du serveur LCS</a></td>
    <td>Permet d'importer les donn�es �l�ves, classes, professeurs, mati�res directement depuis le serveur LDAP de LCS.
    </td>
</tr>
<?php
} else {
?>
<tr>
    <td width=200><a href="../init_csv/index.php">Initialisation des donn�es � partir de fichiers CSV</a></td>
    <td>Permet d'importer les donn�es �l�ves, classes, professeurs, mati�res depuis des fichiers CSV, par exemple des exports depuis Sconet.
    </td>
</tr>
<tr>
    <td width=200><a href="../init_dbf_sts/index.php">Initialisation des donn�es � partir de fichiers DBF et XML</a></td>
    <td>Permet d'importer les donn�es �l�ves, classes, professeurs, mati�res depuis deux fichiers DBF et l'export XML de STS.
    </td>
</tr>
<?php
}
?>
<?php
/* Cette partie n'a plus lieu d'�tre, �tant remplac�e par la proc�dure compl�te d'initialisation par fichiers CSV
<tr>
    <td width=200><a href="import_csv.php">Importation d'un fichier d'�l�ves</a></td>
    <td>Permet d'importer des donn�es "�l�ve" � partir d'un fichier au format csv (s�parateur point-virgule).
    </td>
</tr>
*/
?>
<tr>
    <td width=200><a href="../initialisation/index.php">Initialisation des donn�es � partir des fichiers GEP</a> (OBSOLETE)</td>
    <td>Permet d'importer les donn�es �l�ves, classes, professeurs, mati�res depuis les fichiers GEP. Cette proc�dure est d�sormais obsol�te avec la g�n�ralisation de Sconet.
    </td>
</tr>
<tr>
    <td width=200><a href="accueil_sauve.php">Sauvegardes et restauration</a></td>
    <td>Sauvegarder la base GEPI sous la forme d'un fichier au format "mysql".<br />
    Restaurer des donn�es dans la base Mysql de GEPI � partir d'un fichier.
    </td>
</tr>
<tr>
    <td width=200><a href="../utilitaires/maj.php">Mise � jour de la base</a></td>
    <td>Permet d'effectuer une mise � jour de la base MySql apr�s un changement de version  de GEPI.
    </td>
</tr>
<tr>
    <td width=200><a href="../utilitaires/clean_tables.php">Nettoyage des tables</a></td>
    <td>Proc�der � un nettoyage des tables de la base MySql de GEPI (suppression de certains doublons et/ou lignes obsol�tes ou orphelines).
    </td>
</tr>
<tr>
    <td width=200><a href="efface_base.php">Effacer la base</a></td>
    <td>Permet de r�initialiser les bases en effa�ant toutes les donn�es �l�ves de la base.
    </td>
</tr>
<tr>
    <td width=200><a href="modify_impression.php">Gestion de la fiche "bienvenue"</a></td>
    <td>Permet de modifier la feuille d'information � imprimer pour chaque nouvel utilisateur cr��.
    </td>
</tr>
<tr>
    <td width=200><a href="gestion_connect.php">Gestion des connexions</a></td>
    <td>Param�trage du mode de connexion (autonome ou Single Sign-On), affichage des connexions en cours, journal des connexions, changement de mot de passe obligatoire.
    </td>
</tr>
<tr>
    <td width=200><a href="config_prefs.php">Interface simplifi�e</a></td>
    <td>Param�trage des items de l'interface simplifi�e pour certaines pages.</td>
</tr>

</table>

</center>
</body>
</html>