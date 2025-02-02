<?php
/*
 * $Id: Session.class.php 2315 2008-08-24 19:58:20Z tbelliard $
 *
 * Copyright 2001, 2008 Thomas Belliard
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


# Cette classe sert � manipuler la session en cours.
# Elle g�re notamment l'authentification des utilisateurs
# � partir de diff�rentes sources.

class Session {

        # Attributs publics
        var $login = false;
	var $nom = false;
	var $prenom = false;
	var $statut = false;
	var $statut_special = false;
	var $statut_special_id = false;
	var $start = false;
	var $matiere = false;
	var $maxLength = "30"; # Dur�e par d�faut d'une session utilisateur : 30 minutes.
	var $rne = false; # false, n� RNE valide. Utilis� par le multisite.
	var $auth_locale = true; # true, false. Par d�faut, on utilise l'authentification locale
	var $auth_ldap = false; # false, true
	var $auth_sso = false; # false, cas, lemon, lcs
	var $current_auth_mode = false;  # gepi, ldap, sso, ou false : le mode d'authentification
					 # utilis� par l'utilisateur actuellement connect�

	# Attributs priv�s
	var $etat = false; 	# actif/inactif. Utilis� simplement en interne pour v�rifier que
							# l'utilisateur authentifi� de source externe est bien actif dans Gepi.

	function Session() {

		# On initialise la session
		session_name("GEPI");
		session_start();
		  
		$this->maxLength = getSettingValue("sessionMaxLength");
		# On charge les valeurs d�j� pr�sentes en session
		$this->load_session_data();

		# On charge des �l�ments de configuration li�s � l'authentification
		$this->auth_locale = getSettingValue("auth_locale") == 'yes' ? true : false;
		$this->auth_ldap = getSettingValue("auth_ldap") == 'yes' ? true : false;
		$this->auth_sso = in_array(getSettingValue("auth_sso"), array("lemon", "cas", "lcs")) ? getSettingValue("auth_sso") : false;

		if (!$this->is_anonymous()) {
		  # Il s'agit d'une session non anonyme qui existait d�j�.
		  # On regarde s'il n'y a pas de timeout
		  if ($this->timeout()) {
		  	# timeout : on remet � z�ro.
		  	$debut_session = $_SESSION['start'];
		  	$this->reset(3);
		  	if (isset($GLOBALS['niveau_arbo'])) {
		  		if ($GLOBALS['niveau_arbo'] == "0") {
		  			$logout_path = "./logout.php";
		  		} elseif ($GLOBALS['niveau_arbo'] == "2") {
		  			$logout_path = "../../logout.php";
		  		} elseif ($GLOBALS['niveau_arbo'] == "3") {
		  			$logout_path = "../../../logout.php";
		  		} else {
		  			$logout_path = "../logout.php";
		  		}
		  	} else {
		  		$logout_path = "../logout.php";
		  	}
		  	//echo $logout_path;die();
		  	header("Location:".$logout_path."?auto=3&debut_session=".$debut_session."&session_id=".session_id());
		  	exit();
		  } else {
		  	# Pas de timeout : on met � jour le log
		  	$this->update_log();
		  }
		}

	}

	# S'agit-il d'une session anonyme ?
	function is_anonymous() {
		# Retourne 'true' si login == false
		return !$this->login;
	}

	# Authentification d'un utilisateur pour la session
	# Cette m�thode remplace l'ancienne fonction openSession(...)
	# Valeurs retourn�es :
	# 1 : authentification valide
	# 2 : compte bloqu� : trop de tentatives erron�es
	# 3 : l'IP utilis�e pour se connecter est bloqu�e
	# 4	: authentification externe r�ussie, mais utilisateurs d�fini comme 'inactif'
	# 5 : authentification externe r�ussie, mais utilisateur d�fini pour une authentification autre
	# 6 : authentification externe r�ussie, mais compte inexistant en local et impossible d'importer depuis une source externe
	# 7 : l'administrateur a d�sactiv� les connexions � Gepi.
	# 8 : multisite ; impossibilit� d'obtenir le RNE de l'utilisateur qui s'est authentifi� correctement.
	# 9 : �chec de l'authentification (mauvais couple login/mot de passe, sans doute).
	function authenticate($_login = null, $_password = null) {

		// Quelques petits tests de s�curit�

	    // V�rification de la liste noire des adresses IP
	    if (isset($GLOBALS['liste_noire_ip']) && in_array($_SERVER['REMOTE_ADDR'], $GLOBALS['liste_noire_ip'])) {
		  tentative_intrusion(1, "Tentative de connexion depuis une IP sur liste noire (login utilis� : ".$_login.")");
	      return "3";
		  die();
	    }

	    // On initialise la session de l'utilisateur.
	    // On commence par extraire le mode d'authentification d�fini
	    // pour l'utilisateur. Si l'utilisateur n'existe pas, on essaiera
	    // l'authentification LDAP et le SSO quand m�me.
		$auth_mode = Session::user_auth_mode($_login);

		switch ($auth_mode) {
			case "gepi":
			  # Authentification locale sur la base de donn�es Gepi
			  $auth = $this->authenticate_gepi($_login,$_password);
			break;
			case "ldap":
			  # Authentification sur un serveur LDAP
			  $auth = $this->authenticate_ldap($_login,$_password);
			break;
			case "sso":
			  # Authentification g�r�e par un service de SSO
			  # On n'a pas besoin du login ni du mot de passe
			  switch ($this->auth_sso) {
			  	case "cas":
			  		$auth = $this->authenticate_cas();
			  	break;
			  	case "lemon":
			  		$auth = $this->authenticate_lemon();
			  	break;
			  	case "lcs":
			  		$auth = $this->authenticate_lcs();
			  	break;
			  }
			break;
			case false:
			  # L'utilisateur n'existe pas dans la base de donn�es ou bien
			  # n'a pas �t� pass� en param�tre.
			  # On va donc tenter d'abord une authentification LDAP,
			  # puis une authentification SSO, � condition que celles-ci
			  # soient bien s�r configur�es.
			  if ($this->auth_ldap && $_login != null && $_password != null) {
			  	$auth = $this->authenticate_ldap($_login,$_password);
			  } else if ($this->auth_sso && $_login == null) {
			  	// L'auth LDAP n'a pas march�, on essaie le SSO
				 switch ($this->auth_sso) {
				  	case "cas":
				  		$auth = $this->authenticate_cas();
				  	break;
				  	case "lemon":
				  		$auth = $this->authenticate_lemon();
				  	break;
				  	case "lcs":
				  		$auth = $this->authenticate_lcs();
				  	break;
				 }
			  } else {
			  	$auth = false;
			  }
			break;
			default:
			  # Si on arrive l�, c'est qu'il y a un probl�me avec la d�finition
			  # du mode d'authentification pour l'utilisateur en question.
			  $auth = false;
			break;
		}

		// A partir d'ici soit on a un avis d'�chec de l'authentification, soit
		// une session valide.
		if ($auth) {
			// L'authentification en elle-m�me est valide.

			// Dans le cas du multisite, il faut maintenant d�terminer le RNE
			// de l'utilisateur avant d'aller plus loin, sauf s'il a d�j� �t� pass�
			// en param�tre.
			if (isset($GLOBALS['multisite']) && $GLOBALS['multisite'] == "y") {
				if (!isset($_GET['rne']) AND !isset($_COOKIE["RNE"])) {
					if (LDAPServer::is_setup()) {
						// Le RNE n'a pas �t� transmis. Il faut le r�cup�rer et recharger la page
						// pour obtenir la bonne base de donn�es
						$ldap = new LDAPServer;
						$user = $ldap->get_user_profile($this->login);
						// On teste pour savoir si on a plusieurs RNE
						$test = count($user["rne"]);

						if ($test >= 1) {
							# On a au moins un RNE, on peut continuer
							if ($test > 1) {
								// On envoie l'utilisateur choisir lui m�me son RNE
								$rnes = NULL;
								for($a = 0 ; $a < $test ; $a++){
									$rnes .= $user["rne"][$a].'|';
								}

								header("Location: choix_rne.php?nbre=".$test."&lesrne=".$rnes);

							}else{
								// Il n'y en a qu'un, on recharge !
								if ($this->current_auth_mode == "sso") {
									header("Location: login_sso.php?rne=".$user["rne"]);
									exit();
								} else {
									header("Location: login.php?rne=".$user["rne"]);
									exit();
								}
							}

						} else {
							return "8";
							exit();
						}
					} else {
						return "8";
						exit();
					}
				}
			}


			// On va maintenant effectuer quelques tests pour v�rifier
			// que le compte n'est pas bloqu�.
			if ($this->account_is_locked()) {
				$this->reset(2);
				return "2";
				exit();
			}

			# On charge les donn�es de l'utilisateur
			if (!$this->load_user_data()) {
				# Si on ne parvient pas � charger les donn�es, c'est que
				# l'utilisateur n'est pas pr�sent en base de donn�es.
				# On essaie d'importer son profil depuis le LDAP.
				if (getSettingValue("may_import_user_profile") == "yes") {
					if (!$this->import_user_profile()) {
						return "6";
						exit();
					} else {
						# Si l'import a r�ussi, on tente � nouveau de charger
						# les donn�es de l'utilisateur.
						$this->load_user_data();
					}
				}
			}

			# On v�rifie que l'utilisateur est bien actif
			if ($this->etat != "actif") {
				$this->reset(2);
				return "4";
				exit();
			}

			# On v�rifie que les connexions sont bien activ�es.
		    $disable_login = getSettingValue("disable_login");
		    if ($this->statut != "administrateur" && ($disable_login == "yes" || $disable_login == "soft")) {
		    	$this->reset(2);
		    	return "7";
		    	exit();
		    }

			# On teste la coh�rence de mode de connexion
		    $auth_mode = Session::user_auth_mode($this->login);
		    if ($auth_mode != $this->current_auth_mode) {
		    	$this->reset(2);
		    	return "5";
		    	exit;
		    }

			# Tout est bon. On valide d�finitivement la session.
			$this->start = mysql_result(mysql_query("SELECT now();"),0);
			$_SESSION['start'] = $this->start;
			$this->insert_log();
			# On supprime l'historique des logs conform�ment � la dur�e d�finie.
			sql_query("delete from log where START < now() - interval " . getSettingValue("duree_conservation_logs") . " day and END < now()");

			# On envoie un mail, si l'option a �t� activ�e
			mail_connexion();
			return "1";
			exit();
		} else {
			// L'authentification a �chou�.
			// On nettoie la session.
			$this->reset(2);

			// On enregistre l'�chec.
			// En cas d'�chec r�p�t�, on renvoie un code d'erreur de
			// verrouillage de compte, pour brouiller les pistes en cas
			// d'attaque brute-force sur les logins.
			if ($this->record_failed_login($_login)) {
				return "2";
				exit();
			}

			// On retourne le code d'erreur g�n�rique
			return "9";
		}

	}

	# La m�thode ci-dessous est appel�e lorsque l'on veut s'assurer que l'on a
	# un utilisateur correctement authentifi�, et qu'il est bien autoris� �
	# l'�tre. Elle remplace la fonction resumeSession qui �tait pr�alablement utilis�e.
	function security_check() {
		# Codes renvoy�s :
		# 0 = logout automatique
		# 1 = session valide
		# c = changement forc� de mot de passe

		# D'abord on regarde si on a une tentative d'acc�s anonyme � une page prot�g�e :
		if ($this->is_anonymous()) {
			tentative_intrusion(1, "Acc�s � une page sans �tre logu� (peut provenir d'un timeout de session).");
			return "0";
			exit;
		}

		$sql = "SELECT statut, change_mdp, etat FROM utilisateurs where login = '" . $this->login . "'";
		$res = sql_query($sql);
		$row = mysql_fetch_object($res);

		$change_password = $row->change_mdp != "n" ? true : false;
		$statut_ok = $this->statut == $row->statut ? true : false;
		$etat_ok = $row->etat == "actif" ? true : false;
		$login_allowed = getSettingValue("disable_login") == "yes" ? false : true;

		if (!$login_allowed && $this->statut != "administrateur") {
			return "0";
			exit;
		}

		if (!$statut_ok) {
			return "0";
			exit;
		}

		if (!$etat_ok) {
			return "0";
			exit;
		}

		// Si on est l�, ce que l'utilisateur a le droit de rester.

		if ($change_password &&
				($this->current_auth_mode == "gepi" || getSettingValue("ldap_write_access") == "yes"))
			{
				return "c";
			}

		# Mieux vaut deux fois qu'une...
		if ($statut_ok && $etat_ok && ($login_allowed || $this->statut == "administrateur")) {
			return "1";
			exit;
		}
	}

	# On regarde si l'utilisateur existe dans la base de donn�es,
	# et on v�rifie quel est le mode d'authentification d�fini.
	function user_auth_mode($_login) {
		if ($_login == null) {
			return false;
			die();
		}

		$req = mysql_query("SELECT auth_mode FROM utilisateurs WHERE UPPER(login) = '".strtoupper($_login)."'");
		if (mysql_num_rows($req) == 0) {
			return false;
		} else {
			return mysql_result($req, 0, "auth_mode");
		}
	}

	function close ($_auto) {
		// $_auto_ reprend les codes de reset()
		$this->reset($_auto);
	}

	
	// Recr�er le log dans la table logs.
	// ATTENTION ! Cette m�thode n'est utile que dans un cas tr�s particulier :
	// la restauration d'une sauvegarde, qui compromet la session en cours de
	// l'administrateur. Elle ne devrait jamais �tre utilis�e dans un autre
	// cas.
	// A noter : la m�thode ne r�initialise pas la session. Elle ne fait que
	// r�enregistrer la session en cours dans la base de donn�es.
	function recreate_log() {
	   // On teste que le login enregistr� en session existe bien dans la table
	   // des utilisateurs. Ceci est pour v�rifier que cette op�ration de
	   // r��criture du log est bien n�cessaire, et valide !
	   if ($this->login == '') {
	      return false;
           } else {
	      $test = mysql_num_rows(mysql_query("SELECT login FROM utilisateurs WHERE login = '".$this->login."'"));
	      if ($test == 0) {
		  return false;
	       } else {
		  return $this->insert_log();
	      }
	   }
        }

	## METHODE PRIVEES ##

	// Cr�ation d'une entr�e de log
	function insert_log() {
		if (!isset($_SERVER['HTTP_REFERRER'])) $_SERVER['HTTP_REFERER'] = '';
	    $sql = "INSERT INTO log (LOGIN, START, SESSION_ID, REMOTE_ADDR, USER_AGENT, REFERER, AUTOCLOSE, END) values (
	                '" . $this->login . "',
	                '" . $this->start . "',
	                '" . session_id() . "',
	                '" . $_SERVER['REMOTE_ADDR'] . "',
	                '" . $_SERVER['HTTP_USER_AGENT'] . "',
	                '" . $_SERVER['HTTP_REFERER'] . "',
	                '1',
	                '" . $this->start . "' + interval " . $this->maxLength . " minute
	            )
	        ;";
	    $res = sql_query($sql);
	}

	// Mise � jour du log de l'utilisateur
	function update_log() {
		if ($this->is_anonymous()) {
			return false;
		} else {
			$sql = "UPDATE log SET END = now() + interval " . $this->maxLength . " minute where SESSION_ID = '" . session_id() . "' and START = '" . $this->start . "'";
        	$res = sql_query($sql);
		}
	}

	// Test pour voir si la session de l'utilisateur est en timeout
	function timeout() {
    	$sql = "SELECT now() > END TIMEOUT from log where SESSION_ID = '" . session_id() . "' and START = '" . $this->start . "'";
    	return sql_query1($sql);
	}

	// Remise � z�ro de la session : on supprime toutes les informations pr�sentes
	function reset($_auto = "0") {
		# Codes utilis�s pour $_auto :
		# 0 : logout normal
		# 2 : logout renvoy� par la fonction checkAccess (probl�me gepiPath ou acc�s interdit)
		# 3 : logout li� � un timeout

	    # On teste 'start' simplement pour simplement v�rifier que la session n'a pas encore �t� ferm�e.
		if ($this->start) {
	      $sql = "UPDATE log SET AUTOCLOSE = '" . $_auto . "', END = now() where SESSION_ID = '" . session_id() . "' and START = '" . $this->start . "'";
          $res = sql_query($sql);
    	}

    	// D�truit toutes les variables de session
	    session_unset();
	    $_SESSION = array();

	    // D�truit le cookie sur le navigateur
	    $CookieInfo = session_get_cookie_params();
	    @setcookie(session_name(), '', time()-3600, $CookieInfo['path']);

	    // d�truit la session sur le serveur
	    session_destroy();
	}

	function load_session_data() {
		# On ne met � jour que si la variable de session est assign�e.
		# Si elle est assign�e et null, on met 'false'.
		if (isset($_SESSION['login'])) {
			$this->login 	= $_SESSION['login'] != null ? $_SESSION["login"] : false;
		}
		if (isset($_SESSION['nom'])) {
			$this->nom 	= $_SESSION['nom'] != null ? $_SESSION["nom"] : false;
		}
		if (isset($_SESSION['prenom'])) {
			$this->prenom 	= $_SESSION['prenom'] != null ? $_SESSION["prenom"] : false;
		}
		if (isset($_SESSION['statut'])) {
			$this->statut 	= $_SESSION['statut'] != null ? $_SESSION["statut"] : false;
		}
		if (isset($_SESSION['start'])) {
			$this->start 	= $_SESSION['start'] != null ? $_SESSION["start"] : false;
		}
		if (isset($_SESSION['matiere'])) {
			$this->matiere 	= $_SESSION['matiere'] != null ? $_SESSION["matiere"] : false;
		}
		if (isset($_SESSION['rne'])) {
			$this->rne 	= $_SESSION['rne'] != null ? $_SESSION["rne"] : false;
		}
		if (isset($_SESSION['statut_special'])) {
			$this->statut_special 	= $_SESSION['statut_special'] != null ? $_SESSION["statut_special"] : false;
		}
		if (isset($_SESSION['statut_special_id'])) {
			$this->statut_special_id 	= $_SESSION['statut_special_id'] != null ? $_SESSION["statut_special_id"] : false;
		}
		if (isset($_SESSION['maxLength'])) {
			$this->maxLength 	= $_SESSION['maxLength'] != null ? $_SESSION["maxLength"] : false;
		}
		if (isset($_SESSION['current_auth_mode'])) {
			$this->current_auth_mode 	= $_SESSION['current_auth_mode'] != null ? $_SESSION["current_auth_mode"] : false;
		}
	}

	# Cette fonction permet de tester sous quelle forme le login est stock� dans la base
	# de donn�es. Elle renvoie true ou false.
	function use_uppercase_login($_login) {
		// On d�termine si l'utilisateur a un login en majuscule ou minuscule
		$test_uppercase = "SELECT login FROM utilisateurs WHERE (login = '" . strtoupper($_login) . "')";
		if (sql_count(sql_query($test_uppercase)) == "1") {
			return true;
		} else {
			# On a false soit si l'utilisateur n'est pas pr�sent dans la base, soit s'il est
			# en minuscule.
			return false;
		}
	}

	function authenticate_gepi($_login,$_password) {
		if ($this->use_uppercase_login($_login)) {
			# On passe le login en majuscule pour toute la session.
			$_login = strtoupper($_login);
		}
		$sql = "SELECT login, password FROM utilisateurs WHERE (login = '" . $_login . "' and etat != 'inactif')";
		$query = mysql_query($sql);
		if (mysql_num_rows($query) == "1") {
			# Un compte existe avec ce login
			if (mysql_result($query, 0, "password") == md5($_password)) {
				# Le mot de passe correspond. C'est bon !
				$this->login = $_login;
				$this->current_auth_mode = "gepi";
				return true;
			} else {
				return false;
			}
		} else {
			# Le login est erron� (n'existe pas dans la base)
			return false;
		}
	}

	function authenticate_ldap($_login,$_password) {
		if ($_login == null || $_password == null) {
	        return false;
	        exit();
	    }
	    $ldap_server = new LDAPServer;
	    if ($ldap_server->authenticate_user($_login,$_password)) {
	    	$this->login = $_login;
	    	$this->current_auth_mode = "ldap";
	    	return true;
	    } else {
	    	return false;
	    }
	}

	function authenticate_cas() {
		include_once('CAS.php');
		if ($GLOBALS['mode_debug']) {
		    phpCAS::setDebug($GLOBALS['debug_log_file']);
                }
		// config_cas.inc.php est le fichier d'informations de connexions au serveur cas
		$path = dirname(__FILE__)."/../secure/config_cas.inc.php";
		include($path);

		// Le premier argument est la version du protocole CAS
		phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_root, true);
		phpCAS::setLang('french');

		// redirige vers le serveur d'authentification si aucun utilisateur authentifi� n'a
		// �t� trouv� par le client CAS.
		phpCAS::setNoCasServerValidation();
		
		// Gestion du single sign-out
		phpCAS::handleLogoutRequests(false);
		
		// Authentification
		phpCAS::forceAuthentication();

		$this->login = phpCAS::getUser();

		// On r�initialise la session
		session_name("GEPI");
		session_start();
		$_SESSION['login'] = $this->login;

		$this->current_auth_mode = "sso";

		return true;
	}

	function logout_cas() {
		include_once('CAS.php');

		// config_cas.inc.php est le fichier d'informations de connexions au serveur cas
		$path = dirname(__FILE__)."/../secure/config_cas.inc.php";
		include($path);

		// Le premier argument est la version du protocole CAS
		phpCAS::client(CAS_VERSION_2_0,$cas_host,$cas_port,$cas_root,'');
		phpCAS::setLang('french');
		phpCAS::logout();
		// redirige vers le serveur d'authentification si aucun utilisateur authentifi� n'a
		// �t� trouv� par le client CAS.
		//phpCAS::setNoCasServerValidation();
		//phpCAS::forceAuthentication();

		//$this->login = phpCAS::getUser();

		// On r�initialise la session
		//session_name("GEPI");
		//session_start();
		//$_SESSION['login'] = $this->login;

		//$this->current_auth_mode = "sso";

		return true;
	}

	function authenticate_lemon() {
		#TODO: V�rifier que �a marche bien comme �a !!
	  if (isset($_GET['login'])) $login = $_GET['login']; else $login = "";
	  if (isset($_COOKIE['user'])) $cookie_user = $_COOKIE['user']; else $cookie_user="";
	  if(empty($cookie_user) or $cookie_user != $login) {
	  	return false;
	  } else {
		$this->login = $login;
		$this->current_auth_mode = "sso";
	  	return true;
	  }
	}

	function authenticate_lcs() {
		/*
		global $DBAUTH,$HTTP_COOKIE_VARS,$authlink,$dbHost,$dbUser,$dbPass,$db_nopersist,$dbDb;
		include LCS_PAGE_AUTH_INC_PHP;
		include LCS_PAGE_LDAP_INC_PHP;
		list ($idpers,$login) = isauth();
		*/
		global $login, $idpers;

		$DBAUTH = $GLOBALS['DBAUTH'];
		$HTTP_COOKIE_VARS = $GLOBALS['HTTP_COOKIE_VARS'];
		$authlink = $GLOBALS['authlink'];
		$dbHost = $GLOBALS['dbHost'];
		$dbUser = $GLOBALS['dbUser'];
		$dbPass = $GLOBALS['dbPass'];
		$db_nopersist = $GLOBALS['db_nopersist'];
		$dbDb = $GLOBALS['dbDb'];

		if ($idpers) {
			list($user, $groups)=people_get_variables($login, false);
			#TODO: Utiliser les infos des lignes ci-dessous pour mettre � jour
			# les informations de l'utilisateur dans la base.
			$lcs_tab_login["nom"] = $user["nom"];
			$lcs_tab_login["email"] = $user["email"];
			$long = strlen($user["fullname"]) - strlen($user["nom"]);
			$lcs_tab_login["fullname"] = substr($user["fullname"], 0, $long) ;

			// A ce stade, l'utilisateur est authentifi�
			// Etablir � nouveau la connexion � la base
      if (empty($db_nopersist))
				$db_c = mysql_pconnect($dbHost, $dbUser, $dbPass);
			else
				$db_c = mysql_connect($dbHost, $dbUser, $dbPass);

			if (!$db_c || !mysql_select_db ($dbDb)) {
				echo "\n<p>Erreur : Echec de la connexion � la base de donn�es";
				exit;
			}
			$this->login = $login;
			$this->current_auth_mode = "sso";
			return true;
			exit;
		} else {
			// L'utilisateur n'a pas �t� identifi�'
			header("Location:".LCS_PAGE_AUTHENTIF);
			exit;
		}
	}

	# Cette m�thode charge en session les donn�es de l'utilisateur,
	# � la suite d'une authentification r�ussie.
	function load_user_data() {
		# Petit test de d�part pour �tre s�r :
		if (!$this->login || $this->login == null) {
			return false;
			exit();
		}

		# Gestion du multisite : on a besoin du RNE de l'utilisateur.
		if (isset($GLOBALS['multisite']) && $GLOBALS['multisite'] == 'y' && LDAPServer::is_setup()) {
			$ldap = new LDAServer;
			$user = $ldap->get_user_profile($this->login);
			$this->rne = $user["rne"];
		}

		# On regarde si on doit utiliser un login en majuscule. Si c'est le cas, il faut imp�rativement
		# le faire *apr�s* un �ventuel import externe.
		if ($this->use_uppercase_login($this->login)) {
			$this->login = strtoupper($this->login);
		}

		# On interroge la base de donn�es
		$query = mysql_query("SELECT nom, prenom, statut, etat, now() start, change_mdp, auth_mode FROM utilisateurs WHERE (login = '".$this->login."')");

		# Est-ce qu'on a bien une entr�e ?
		if (mysql_num_rows($query) != "1") {
			return false;
			exit();
		}

		$sql = "SELECT id_matiere FROM j_professeurs_matieres WHERE (id_professeur = '" . $this->login . "') ORDER BY ordre_matieres LIMIT 1";
        $matiere_principale = sql_query1($sql);

		$row = mysql_fetch_object($query);

	    $_SESSION['login'] = $this->login;
	    $_SESSION['prenom'] = $row->prenom;
	    $_SESSION['nom'] = $row->nom;
	    $_SESSION['statut'] = $row->statut;
	    $_SESSION['start'] = $row->start;
	    $_SESSION['matiere'] = $matiere_principale;
	    $_SESSION['rne'] = $this->rne;
	    $_SESSION['current_auth_mode'] = $this->current_auth_mode;

	    # L'�tat de l'utilisateur n'est pas stock� en session, mais seulement en interne
	    # pour pouvoir effectuer quelques tests :
	    $this->etat = $row->etat;

		// Ajout pour les statuts priv�s
	    if ($_SESSION['statut'] == 'autre') {

	    	// On charge aussi le statut sp�cial
	    	$sql = "SELECT ds.id, ds.nom_statut FROM droits_statut ds, droits_utilisateurs du
											WHERE du.login_user = '".$this->login."'
											AND du.id_statut = ds.id";
			$query = mysql_query($sql);
			$result = mysql_fetch_array($query);

			$_SESSION['statut_special'] = $result['nom_statut'];
			$_SESSION['statut_special_id'] = $result['id'];

	    }

		/*
		$length = rand(35, 45);
		for($len=$length,$r='';strlen($r)<$len;$r.=chr(!mt_rand(0,2)? mt_rand(48,57):(!mt_rand(0,1) ? mt_rand(65,90) : mt_rand(97,122))));
		$_SESSION["gepi_alea"] = $r;
		*/
		generate_token();

	    # On charge les donn�es dans l'instance de Session.
	    $this->load_session_data();
	    return true;
	}

	function record_failed_login($_login) {
		# Une tentative de login avec un mot de passe erronn�e a �t� d�tect�e.
		$test_login = sql_count(sql_query("SELECT login FROM utilisateurs WHERE (login = '".$_login."')"));

		if ($test_login != "0") {
			tentative_intrusion(1, "Tentative de connexion avec un mot de passe incorrect. Ce peut �tre simplement une faute de frappe. Cette alerte n'est significative qu'en cas de r�p�tition. (login : ".$_login.")");
			# On a un vrai login.
			# On enregistre un log d'erreur de connexion.
	        $sql = "insert into log (LOGIN, START, SESSION_ID, REMOTE_ADDR, USER_AGENT, REFERER, AUTOCLOSE, END) values (
	        	'" . $_login . "',
	            now(),
	            '',
	            '" . $_SERVER['REMOTE_ADDR'] . "',
	            '" . $_SERVER['HTTP_USER_AGENT'] . "',
	            '" . $_SERVER['HTTP_REFERER'] . "',
	            '4',
	            now());";
	        $res = sql_query($sql);

	        // On compte de nombre de tentatives infructueuse issues de la m�me adresse IP
	        $sql = "select LOGIN from log where
	                LOGIN = '" . $_login . "' and
	                START > now() - interval " . getSettingValue("temps_compte_verrouille") . " minute and
	                REMOTE_ADDR = '".$_SERVER['REMOTE_ADDR']."'
	                ";
	        $res_test = sql_query($sql);
	        if (sql_count($res_test) > getSettingValue("nombre_tentatives_connexion")) {
	        	$this->lock_account($_login);
	        	return true;
	        } else {
	        	return false;
	        }
		} else {
			tentative_intrusion(1, "Tentative de connexion avec un login incorrect (n'existe pas dans la base Gepi). Ce peut �tre simplement une faute de frappe. Cette alerte n'est significative qu'en cas de r�p�tition. (login utilis� : ".$_login.")");
			// Le login n'existe pas. On fait donc un test sur l'IP.
			$sql = "select LOGIN from log where
                START > now() - interval " . getSettingValue("temps_compte_verrouille") . " minute and
                REMOTE_ADDR = '".$_SERVER['REMOTE_ADDR']."'";
            $res_test = sql_query($sql);
            if (sql_count($res_test) <= 10) {
				// On a moins de 10 enregistrements. On enregistre et on ne renvoie pas de code
				// de verrouillage.
            	$sql = "insert into log (LOGIN, START, SESSION_ID, REMOTE_ADDR, USER_AGENT, REFERER, AUTOCLOSE, END) values (
                    '" . $_login . "',
                    now(),
                    '',
                    '" . $_SERVER['REMOTE_ADDR'] . "',
                    '" . $_SERVER['HTTP_USER_AGENT'] . "',
                    '" . $_SERVER['HTTP_REFERER'] . "',
                    '4',
                    now()
                    )
                    ;";
                $res = sql_query($sql);
                return false;
            } else {
            	// On a 10 entr�es, on renvoie un code d'erreur de verouillage.
            	return true;
            }
		}
	}

	# Verrouillage d'un compte en raison d'un trop grand nombre d'�chec de connexion.
	function lock_account($_login) {
	   if ((!isset($GLOBALS['bloque_compte_admin'])) or ($GLOBALS['bloque_compte_admin'] != "n")) {
	      // On verrouille le compte m�me si c'est un admin
	      $reg_data = sql_query("UPDATE utilisateurs SET date_verrouillage=now() WHERE login='".$_login."'");
	   } else {
	      // on ne bloque pas le compte d'un administrateur
	      $reg_data = sql_query("UPDATE utilisateurs SET date_verrouillage=now() WHERE login='".$_login."' and statut!='administrateur'");
	   }
	   # On enregistre une alerte de s�curit�.
	   tentative_intrusion(2, "Verrouillage du compte ".$_login." en raison d'un trop grand nombre de tentatives de connexion infructueuses. Ce peut �tre une tentative d'attaque brute-force.");
	   return true;
	}

	# Renvoie true ou false selon que le compte est bloqu� ou non.
	function account_is_locked() {
		$test_verrouillage = sql_query1("select login, statut from utilisateurs where
			login = '" . $this->login . "' and
			date_verrouillage > now() - interval " . getSettingValue("temps_compte_verrouille") . " minute ");

		if ($test_verrouillage != "-1") {
			// Le compte est verrouill�.
			if ($this->statut == "administrateur" and $GLOBALS['bloque_compte_admin'] != "n") {
				// On ne veut pas bloquer le compte admin, alors on renvoie false.
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	function import_user_profile() {
		# On ne peut arriver ici quand dans le cas o� on a une authentification r�ussie.
		# L'import d'un utilisateur ne peut se faire qu'� partir d'un LDAP
		if (!LDAPServer::is_setup()) {
			return false;
			die();
		} else {
			# Le serveur LDAP est configur�, on y va.
			# Encore un dernier petit test quand m�me : est-ce que l'utilisateur
			# est bien absent de la base.
			$sql = mysql_query("SELECT login FROM utilisateurs WHERE (login = '".$this->login."')");
			if (mysql_num_rows($sql) != "0") {
				return false;
				die();
			}

			$ldap_server = new LDAPServer;
			$user = $ldap_server->get_user_profile($this->login);
			if ($user) {
				# On ne refait pas de tests ou de formattage. La m�thode get_user_profile
				# s'occupe de tout.
				$res = mysql_query("INSERT INTO utilisateurs SET
										login = '".$this->login."',
										prenom = '".$user["prenom"]."',
										nom = '".$user["nom"]."',
										email = '".$user["email"]."',
										civilite = '".$user["civilite"]."',
										statut = '".$user["statut"]."',
										password = '',
										etat = 'actif',
										auth_mode = '".$this->current_auth_mode."',
										change_mdp = 'n'");
				if (!$res) {
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}
	}
}
?>