<?php
/*
 * $Id$
 *
 * Copyright 2001, 2011 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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

// Initialisation des variables
$mode = isset($_POST["mode"]) ? $_POST["mode"] : (isset($_GET["mode"]) ? $_GET["mode"] : false);
$action = isset($_POST["action"]) ? $_POST["action"] : (isset($_GET["action"]) ? $_GET["action"] : false);
// Test SSO. Dans le cas d'un SSO, on laisse le mot de passevide.
$test_sso = ((getSettingValue('use_sso') != "cas" and getSettingValue("use_sso") != "lemon"  and (getSettingValue("use_sso") != "lcs") and getSettingValue("use_sso") != "ldap_scribe") OR $block_sso);

$mdp_INE=isset($_POST["mdp_INE"]) ? $_POST["mdp_INE"] : (isset($_GET["mdp_INE"]) ? $_GET["mdp_INE"] : NULL);

$msg = '';

// Si on est en traitement par lot, on s�lectionne tout de suite la liste des utilisateurs impliqu�s
$error = false;
if ($mode == "classe") {
	$nb_comptes = 0;
	if ($_POST['classe'] == "all") {
		$quels_eleves = mysql_query("SELECT distinct(jec.login) login, u.auth_mode " .
				"FROM classes c, j_eleves_classes jec, utilisateurs u WHERE (" .
				"jec.id_classe = c.id and u.login = jec.login)");
		if (!$quels_eleves) $msg .= mysql_error();
	} elseif (is_numeric($_POST['classe'])) {
		$quels_eleves = mysql_query("SELECT distinct(jec.login), u.auth_mode " .
				"FROM classes c, j_eleves_classes jec, utilisateurs u WHERE (" .
				"jec.id_classe = '" . $_POST['classe']."' and u.login = jec.login)");
		if (!$quels_eleves) $msg .= mysql_error();
	} else {
		$error = true;
		$msg .= "Vous devez s�lectionner au moins une classe !<br/>";
	}
}

// Trois actions sont possibles depuis cette page : activation, d�sactivation et suppression.
// L'�dition se fait directement sur la page de gestion des responsables

if (!$error) {

	if($action) {
		check_token();
	}

	if ($action == "rendre_inactif") {
		// D�sactivation d'utilisateurs actifs
		if ($mode == "individual") {
			// D�sactivation pour un utilisateur unique
			$test = mysql_result(mysql_query("SELECT count(login) FROM utilisateurs WHERE (login = '" . $_GET['eleve_login']."' AND etat = 'actif')"), 0);
			if ($test == "0") {
				$msg .= "Erreur lors de la d�sactivation de l'utilisateur : celui-ci n'existe pas ou bien est d�j� inactif.";
			} else {
				$res = mysql_query("UPDATE utilisateurs SET etat='inactif' WHERE (login = '".$_GET['eleve_login']."')");
				if ($res) {
					$msg .= "L'utilisateur ".$_GET['eleve_login'] . " a �t� d�sactiv�.";
				} else {
					$msg .= "Erreur lors de la d�sactivation de l'utilisateur.";
				}
			}
		} elseif ($mode == "classe" and !$error) {
			// Pour tous les �l�ves qu'on a d�j� s�lectionn�s un peu plus haut, on d�sactive les comptes
			while ($current_eleve = mysql_fetch_object($quels_eleves)) {
				$test = mysql_result(mysql_query("SELECT count(login) FROM utilisateurs WHERE login = '" . $current_eleve->login ."'"), 0);
				if ($test > 0) {
					// L'utilisateur existe bien dans la tables utilisateurs, on d�sactive
					$res = mysql_query("UPDATE utilisateurs SET etat = 'inactif' WHERE login = '" . $current_eleve->login . "'");
					if (!$res) {
						$msg .= "Erreur lors de la d�sactivation du compte ".$current_eleve->login."<br/>";
					} else {
						$nb_comptes++;
					}
				}
			}
			$msg .= "$nb_comptes comptes ont �t� d�sactiv�s.";
		}
	} elseif ($action == "rendre_actif") {
		// Activation d'utilisateurs pr�alablement d�sactiv�s
		if ($mode == "individual") {
			// Activation pour un utilisateur unique
			$test = mysql_result(mysql_query("SELECT count(login) FROM utilisateurs WHERE (login = '" . $_GET['eleve_login']."' AND etat = 'inactif')"), 0);
			if ($test == "0") {
				$msg .= "Erreur lors de la d�sactivation de l'utilisateur : celui-ci n'existe pas ou bien est d�j� actif.";
			} else {
				$res = mysql_query("UPDATE utilisateurs SET etat='actif' WHERE (login = '".$_GET['eleve_login']."')");
				if ($res) {
					$msg .= "L'utilisateur ".$_GET['eleve_login'] . " a �t� activ�.";
				} else {
					$msg .= "Erreur lors de l'activation de l'utilisateur.";
				}
			}
		} elseif ($mode == "classe") {
			// Pour tous les �l�ves qu'on a d�j� s�lectionn�s un peu plus haut, on d�sactive les comptes
			while ($current_eleve = mysql_fetch_object($quels_eleves)) {
				$test = mysql_result(mysql_query("SELECT count(login) FROM utilisateurs WHERE login = '" . $current_eleve->login ."'"), 0);
				if ($test > 0) {
					// L'utilisateur existe bien dans la tables utilisateurs, on d�sactive
					$res = mysql_query("UPDATE utilisateurs SET etat = 'actif' WHERE login = '" . $current_eleve->login . "'");
					if (!$res) {
						$msg .= "Erreur lors de l'activation du compte ".$current_eleve->login."<br/>";
					} else {
						$nb_comptes++;
					}
				}
			}
			$msg .= "$nb_comptes comptes ont �t� activ�s.";
		}
	
	} elseif ($action == "supprimer") {
		$ldap_write_access=false;
	
		//if ($gepiSettings['ldap_write_access']) {
		if ($gepiSettings['ldap_write_access']=='yes') {
			//echo "\$ldap_write_access<br />";
			$ldap_write_access = true;
			$ldap_server = new LDAPServer;
		}
	
		// Suppression d'un ou plusieurs utilisateurs
		if ($mode == "individual") {
			// Suppression pour un utilisateur unique
			$test = mysql_result(mysql_query("SELECT count(login) FROM utilisateurs WHERE (login = '" . $_GET['eleve_login']."')"), 0);
			if ($test == "0") {
				$msg .= "Erreur lors de la suppression de l'utilisateur : celui-ci n'existe pas.";
			} else {
				$res = mysql_query("DELETE FROM utilisateurs WHERE (login = '".$_GET['eleve_login']."')");
				if ($res) {
					$msg .= "L'utilisateur ".$_GET['eleve_login'] . " a �t� supprim�.";
					if ($ldap_write_access) {
						if ($ldap_server->test_user($_GET['eleve_login'])) {
							$write_ldap_success = $ldap_server->delete_user($_GET['eleve_login']);
							if ($write_ldap_success) {
								$msg .= "<br/>L'utilisateur a �t� supprim� de l'annuaire LDAP.";
							}
						}
					}
				} else {
					$msg .= "Erreur lors de la suppression de l'utilisateur.";
				}
			}
		} elseif ($mode == "classe") {
			// Pour tous les �l�ves qu'on a d�j� s�lectionn�s un peu plus haut, on d�sactive les comptes
			while ($current_eleve = mysql_fetch_object($quels_eleves)) {
				$test = mysql_result(mysql_query("SELECT count(login) FROM utilisateurs WHERE login = '" . $current_eleve->login ."'"), 0);
				if ($test > 0) {
					// L'utilisateur existe bien dans la tables utilisateurs, on d�sactive
					$res = mysql_query("DELETE FROM utilisateurs WHERE login = '" . $current_eleve->login . "'");
					if (!$res) {
						$msg .= "Erreur lors de la suppression du compte ".$current_eleve->login."<br/>";
					} else {
						$nb_comptes++;
						if ($ldap_write_access && $current_eleve->auth_mode != 'gepi') {
							if (!$ldap_server->test_user($current_eleve->login)) {
								// L'utilisateur n'a pas �t� trouv� dans l'annuaire.
								$write_ldap_success = true;
							} else {
								$write_ldap_success = $ldap_server->delete_user($current_eleve->login);
							}
						}
					}
				}
			}
			$msg .= "$nb_comptes comptes ont �t� supprim�s.";
		}
	} elseif ($action == "reinit_password") {
		if ($mode != "classe") {
			$msg .= "Erreur : Vous devez s�lectionner une classe.";
		} elseif ($mode == "classe") {
	
			if(isset($mdp_INE)) {
				$chaine_mdp_INE="&amp;mdp_INE=$mdp_INE";
			}
			else {
				$chaine_mdp_INE="";
			}
	
			if ($_POST['classe'] == "all") {
				$msg .= "Vous allez r�initialiser les mots de passe de tous les utilisateurs ayant le statut 'eleve'.<br/>Si vous �tes vraiment s�r de vouloir effectuer cette op�ration, cliquez sur le lien ci-dessous :";
				$msg .= "<br/><a href=\"reset_passwords.php?user_status=eleve&amp;mode=html$chaine_mdp_INE".add_token_in_url()."\" target='_blank'>R�initialiser les mots de passe (Impression HTML)</a>";
				$msg .= "<br/><a href=\"reset_passwords.php?user_status=eleve&amp;mode=csv$chaine_mdp_INE".add_token_in_url()."\" target='_blank'>R�initialiser les mots de passe (Export CSV)</a>";
				$msg .= "<br/><a href=\"reset_passwords.php?user_status=eleve&amp;mode=pdf$chaine_mdp_INE".add_token_in_url()."\" target='_blank'>R�initialiser les mots de passe (Impression PDF)</a>";
			} else if (is_numeric($_POST['classe'])) {
				$msg .= "Vous allez r�initialiser les mots de passe de tous les utilisateurs ayant le statut 'eleve' pour cette classe.<br/>Si vous �tes vraiment s�r de vouloir effectuer cette op�ration, cliquez sur le lien ci-dessous :";
				$msg .= "<br/><a href=\"reset_passwords.php?user_status=eleve&amp;user_classe=".$_POST['classe']."&amp;mode=html$chaine_mdp_INE".add_token_in_url()."\" target='_blank'>R�initialiser les mots de passe (Impression HTML)</a>";
				$msg .= "<br/><a href=\"reset_passwords.php?user_status=eleve&amp;user_classe=".$_POST['classe']."&amp;mode=csv$chaine_mdp_INE".add_token_in_url()."\" target='_blank'>R�initialiser les mots de passe (Export CSV)</a>";
				$msg .= "<br/><a href=\"reset_passwords.php?user_status=eleve&amp;user_classe=".$_POST['classe']."&amp;mode=pdf$chaine_mdp_INE".add_token_in_url()."\" target='_blank'>R�initialiser les mots de passe (Impression PDF)</a>";
			}
		}
	} elseif ($action == "change_auth_mode") {
		$ldap_write_access = false;
		if ($gepiSettings['ldap_write_access'] == "yes") {
			$ldap_write_access = true;
			$ldap_server = new LDAPServer;
		}
		$nb_comptes = 0;
		$reg_auth_mode = (in_array($_POST['reg_auth_mode'], array("gepi", "ldap", "sso"))) ? $_POST['reg_auth_mode'] : "gepi";
		if ($mode != "classe") {
			$msg .= "Erreur : Vous devez s�lectionner une classe.";
		} elseif ($mode == "classe") {
			while ($current_eleve = mysql_fetch_object($quels_eleves)) {
				$test = mysql_result(mysql_query("SELECT count(login) FROM utilisateurs WHERE login = '" . $current_eleve->login ."'"), 0);
				if ($test > 0) {
					// L'utilisateur existe bien dans la tables utilisateurs, on modifie
					// Si on change le mode d'authentification, il faut quelques op�rations particuli�res
					$old_auth_mode = $current_eleve->auth_mode;
					if ($_POST['reg_auth_mode'] != $old_auth_mode) {
						// On modifie !
						$nb_comptes++;
						$res = mysql_query("UPDATE utilisateurs SET auth_mode = '".$reg_auth_mode."' WHERE login = '".$current_eleve->login."'");
	
						// On regarde si des op�rations sp�cifiques sont n�cessaires
						if ($old_auth_mode == "gepi" && ($_POST['reg_auth_mode'] == "ldap" || $_POST['reg_auth_mode'] == "sso")) {
							// On passe du mode Gepi � un mode externe : il faut supprimer le mot de passe
							$oldmd5password = mysql_result(mysql_query("SELECT password FROM utilisateurs WHERE login = '".$current_eleve->login."'"), 0);
							mysql_query("UPDATE utilisateurs SET password = '', salt = '' WHERE login = '".$current_eleve->login."'");
							// Et si on a un acc�s en �criture au LDAP, il faut cr�er l'utilisateur !
							if ($ldap_write_access) {
								$create_ldap_user = true;
							}
						} elseif (($old_auth_mode == "sso" || $old_auth_mode == "ldap") && $_POST['reg_auth_mode'] == "gepi") {
							// Passage au mode Gepi, rien de sp�cial � faire, si ce n'est annoncer � l'administrateur
							// qu'il va falloir r�initialiser les mots de passe
							$pass_init_required = true;
							// Et si acc�s en �criture au LDAP, on supprime le compte.
							if ($ldap_write_access) {
								$delete_ldap_user = true;
							}
						}
	
						// On effectue les op�rations LDAP
						if (isset($create_ldap_user) && $create_ldap_user) {
							if (!$ldap_server->test_user($current_eleve->login)) {
								$eleve = mysql_fetch_object(mysql_query("SELECT distinct(e.login), e.nom, e.prenom, e.sexe, e.email " .
														"FROM eleves e WHERE (" .
														"e.login = '" . $current_eleve->login."')"));
								$reg_civilite = $eleve->sexe == "M" ? "M." : "Mlle";
								$write_ldap_success = $ldap_server->add_user($eleve->login, $eleve->nom, $eleve->prenom, $eleve->email, $reg_civilite, md5(rand()), "eleve");
								// On transfert le mot de passe � la main
								$ldap_server->set_manual_password($current_eleve->login, "{MD5}".base64_encode(pack("H*",$oldmd5password)));
							}
						}
						if (isset($delete_ldap_user) && $delete_ldap_user) {
							if (!$ldap_server->test_user($current_eleve->login)) {
								// L'utilisateur n'a pas �t� trouv� dans l'annuaire.
								$write_ldap_success = true;
							} else {
								$write_ldap_success = $ldap_server->delete_user($current_eleve->login);
							}
						}
	
					}
				}
			}
			$msg .= "$nb_comptes comptes ont �t� modifi�s.";
			if (isset($pass_init_required) && $pass_init_required) {
				$msg .= "<br/>Attention ! Des modifications appliqu�es n�cessitent la r�initialisation de mots de passe !";
			}
		}
	}
}

//**************** EN-TETE *****************
$titre_page = "Modifier des comptes �l�ves";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
?>
<p class=bold><a href="index.php"><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a> |
<a href="create_eleve.php"> Ajouter de nouveaux comptes</a>
<?php

$quels_eleves = mysql_query("SELECT 1=1 FROM utilisateurs WHERE statut='eleve' ORDER BY nom,prenom");
if(mysql_num_rows($quels_eleves)==0){
	echo "<p>Aucun compte �l�ve n'existe encore.<br />Vous pouvez ajouter des comptes �l�ves � l'aide du lien ci-dessus.</p>\n";
	require("../lib/footer.inc.php");
	die;
}
echo " | <a href='impression_bienvenue.php?mode=eleve'>Fiches bienvenue</a>";

echo " | <a href='import_prof_csv.php?export_statut=eleve'>Export CSV</a>";

echo "</p>\n";

//echo "<p><b>Actions par lot</b> :";
echo "<form action='edit_eleve.php' method='post'>\n";

echo add_token_field();

echo "<p style='font-weight:bold;'>Actions par lot pour les comptes �l�ves existants : </p>\n";
echo "<blockquote>\n";
echo "<p>\n";

echo "<select name='classe' size='1'>\n";
echo "<option value='none'>S�lectionnez une classe</option>\n";
echo "<option value='all'>Toutes les classes</option>\n";

//$quelles_classes = mysql_query("SELECT id,classe FROM classes ORDER BY classe");
$quelles_classes = mysql_query("SELECT DISTINCT c.id,c.classe FROM classes c, j_eleves_classes jec, utilisateurs u
									WHERE jec.login=u.login AND
											jec.id_classe=c.id
									ORDER BY classe");

while ($current_classe = mysql_fetch_object($quelles_classes)) {
	echo "<option value='".$current_classe->id."'>".$current_classe->classe."</option>\n";
}
echo "</select>\n";
echo "<input type='hidden' name='mode' value='classe' />\n";
echo "<br />\n";
echo "<input type='radio' name='action' id='action_rendre_inactif' value='rendre_inactif' onchange='traite_p_mdp_INE()' /><label for='action_rendre_inactif' style='cursor:pointer;'> Rendre inactif</label>\n";
echo "<input type='radio' name='action' id='action_rendre_actif' value='rendre_actif' style='margin-left: 20px;' onchange='traite_p_mdp_INE()' /><label for='action_rendre_actif' style='cursor:pointer;'> Rendre actif</label> \n";
if ($session_gepi->auth_locale || $gepiSettings['ldap_write_access']) {
    echo "<input type='radio' name='action' id='action_reinit_password' value='reinit_password' style='margin-left: 20px;' onchange='traite_p_mdp_INE()' /><label for='action_reinit_password' style='cursor:pointer;'> R�initialiser mots de passe</label>\n";
}
echo "<input type='radio' name='action' id='action_supprimer' value='supprimer' style='margin-left: 20px;' onchange='traite_p_mdp_INE()' /><label for='action_supprimer' style='cursor:pointer;'> Supprimer</label><br />\n";
echo "<input type='radio' name='action' id='action_change_auth_mode' value='change_auth_mode' onchange='traite_p_mdp_INE()' /><label for='action_change_auth_mode' style='cursor:pointer;'> Modifier authentification : </label>";
?>
<select id="select_auth_mode" name="reg_auth_mode" size="1">
<option value='gepi'>Locale (base Gepi)</option>
<option value='ldap'>LDAP</option>
<option value='sso'>SSO (Cas, LCS, LemonLDAP)</option>
</select>
<?php
echo "<br />\n";
//echo "<br />\n";
echo "&nbsp;<input type='submit' name='Valider' value='Valider' />\n";
echo "</p>\n";

echo "<p id='p_mdp_INE' style='font-size:x-small;'><input type='checkbox' name='mdp_INE' id='mdp_INE' value='y' /> <label for='mdp_INE' style='cursor:pointer'>Utiliser le num�ro national de l'�l�ve (<i>INE</i>) comme mot de passe lorsqu'il est renseign� pour la r�initialisation des mots de passe de la (des) classe(s).</label></p>\n";

echo "<script type='text/javascript'>
	document.getElementById('p_mdp_INE').style.display='none';

	function traite_p_mdp_INE() {
		if(document.getElementById('action_reinit_password').checked) {
			document.getElementById('p_mdp_INE').style.display='';
		}
		else {
			document.getElementById('p_mdp_INE').style.display='none';
		}
	}
</script>\n";

echo "</blockquote>\n";
echo "</form>\n";


echo "<p><br /></p>\n";

echo "<p><b>Liste des comptes �l�ves existants</b> :</p>\n";
echo "<blockquote>\n";

$afficher_tous_les_eleves=isset($_POST['afficher_tous_les_eleves']) ? $_POST['afficher_tous_les_eleves'] : "n";
$critere_recherche=isset($_POST['critere_recherche']) ? $_POST['critere_recherche'] : "";
$critere_recherche=preg_replace("/[^a-zA-Z�������������ܽ�����������������_ -]/", "", $critere_recherche);

//====================================
echo "<form enctype='multipart/form-data' name='form_rech' action='".$_SERVER['PHP_SELF']."' method='post'>\n";
echo "<table style='border:1px solid black;' summary=\"Filtrage\">\n";
echo "<tr>\n";
echo "<td valign='top' rowspan='3'>\n";
echo "Filtrage:";
echo "</td>\n";
echo "<td>\n";
echo "<input type='submit' name='filtrage' value='Afficher' /> les �l�ves ayant un login dont le <b>nom</b> contient: ";
echo "<input type='text' name='critere_recherche' value='$critere_recherche' />\n";
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td>\n";
echo "ou";
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td>\n";
echo "<input type='button' name='afficher_tous' value='Afficher tous les �l�ves ayant un login' onClick=\"document.getElementById('afficher_tous_les_eleves').value='y'; document.form_rech.submit();\" />\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

echo "<input type='hidden' name='afficher_tous_les_eleves' id='afficher_tous_les_eleves' value='n' />\n";
echo "</form>\n";
//====================================
echo "<br />\n";

?>
<!--table border="1"-->
<table class='boireaus' border='1' summary="Liste des comptes existants">
<tr>
	<th>Identifiant</th><th>Nom Pr�nom</th><th>Etat</th><th>Actions</th><th>Classe</th>
</tr>
<?php
//$quels_eleves = mysql_query("SELECT * FROM utilisateurs WHERE statut = 'eleve' ORDER BY nom,prenom");

$sql="SELECT * FROM utilisateurs u WHERE u.statut='eleve'";

if($afficher_tous_les_eleves!='y'){
	if($critere_recherche!=""){
		$sql.=" AND u.nom like '%".$critere_recherche."%'";
	}
}
$sql.=" ORDER BY u.nom,u.prenom";

// Effectif sans login avec filtrage sur le nom:
$nb1 = mysql_num_rows(mysql_query($sql));

if($afficher_tous_les_eleves!='y'){
	if($critere_recherche==""){
		$sql.=" LIMIT 20";
	}
}
$quels_eleves = mysql_query($sql);

$alt=1;
while ($current_eleve = mysql_fetch_object($quels_eleves)) {
	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
		echo "<td>\n";
			echo "<a href='../eleves/modify_eleve.php?eleve_login=".$current_eleve->login."&amp;journal_connexions=y'>".$current_eleve->login."</a>";
		echo "</td>\n";
		echo "<td>\n";
			echo $current_eleve->nom . " " . $current_eleve->prenom;
		echo "</td>\n";
		echo "<td align='center'>\n";
			//echo $current_eleve->etat;
			//echo "<br/>";
			if ($current_eleve->etat == "actif") {
				echo "<font color='green'>".$current_eleve->etat."</font>";
				echo "<br />\n";
				echo "<a href='edit_eleve.php?action=rendre_inactif&amp;mode=individual&amp;eleve_login=".$current_eleve->login.add_token_in_url()."'>D�sactiver";
			} else {
				echo "<font color='red'>".$current_eleve->etat."</font>";
				echo "<br />\n";
				echo "<a href='edit_eleve.php?action=rendre_actif&amp;mode=individual&amp;eleve_login=".$current_eleve->login.add_token_in_url()."'>Activer";
			}
			echo "</a>\n";
		echo "</td>\n";
		echo "<td>\n";
		echo "<a href='edit_eleve.php?action=supprimer&amp;mode=individual&amp;eleve_login=".$current_eleve->login.add_token_in_url()."' onclick=\"javascript:return confirm('�tes-vous s�r de vouloir supprimer l\'utilisateur ?')\">Supprimer</a>\n";

		if($current_eleve->etat == "actif" && ($current_eleve->auth_mode == "gepi" || $gepiSettings['ldap_write_access'] == "yes")) {
			echo "<br />";
			echo "R�initialiser le mot de passe : <a href=\"reset_passwords.php?user_login=".$current_eleve->login."&amp;user_status=eleve&amp;mode=html".add_token_in_url()."\" onclick=\"javascript:return confirm('�tes-vous s�r de vouloir effectuer cette op�ration ?\\n Celle-ci est irr�versible, et r�initialisera le mot de passe de l\'utilisateur avec un mot de passe alpha-num�rique g�n�r� al�atoirement.\\n En cliquant sur OK, vous lancerez la proc�dure, qui g�n�rera une page contenant la fiche-bienvenue � imprimer imm�diatement pour distribution � l\'utilisateur concern�.')\" target='_blank'>Al�atoirement</a>";
			echo " - <a href=\"change_pwd.php?user_login=".$current_eleve->login."\" onclick=\"javascript:return confirm('�tes-vous s�r de vouloir effectuer cette op�ration ?\\n Celle-ci r�initialisera le mot de passe de l\'utilisateur avec un mot de passe que vous choisirez.\\n En cliquant sur OK, vous lancerez une page qui vous demandera de saisir un mot de passe et de le valider.')\" target='_blank'>choisi </a>";
		}
		echo "</td>\n";

		echo "<td>\n";
		$tmp_class=get_class_from_ele_login($current_eleve->login);
		if(isset($tmp_class['liste'])) {
			echo $tmp_class['liste'];
		}
		else {
			echo "<span style='color:red;'>Aucune</span>";
		}
		echo "</td>\n";

		/*
		echo "<td>\n";
		$tmp_class=get_class_from_ele_login($current_eleve->login);
		if(isset($tmp_class['liste'])) {
			echo $tmp_class['liste'];
		}
		else {
			echo "<span style='color:red;'>Aucune</span>";
		}
		echo "</td>\n";
		*/
	echo "</tr>\n";
}
?>
</table>
<?php
echo "</blockquote>\n";

if (mysql_num_rows($quels_eleves) == "0") {
	echo "<p>Pour cr�er de nouveaux comptes d'acc�s associ�s aux �l�ves d�finis dans Gepi, vous devez cliquer sur le lien 'Ajouter de nouveaux comptes' ci-dessus.</p>\n";
}

require("../lib/footer.inc.php");?>