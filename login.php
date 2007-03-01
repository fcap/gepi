<?php
/*
 * Last modification  : 13/07/2006
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

// V�rification de la bonne installation de GEPI
require_once("./utilitaires/verif_install.php");

$niveau_arbo = 0;

// On indique qu'il faut cr�e des variables non prot�g�es (voir fonction cree_variables_non_protegees())
$variables_non_protegees = 'yes';

// Initialisations files
require_once("./lib/initialisations.inc.php");

// On se charge imm�diatement de l'authentification par SSO, si besoin

$use_sso = null;
$use_sso = getSettingValue('use_sso');
if (!(isset($_GET['local']))) $_GET['local'] = false;

if (isset($use_sso) and ($use_sso == "cas") and !$block_sso) {
    require_once("./lib/cas.inc.php");
    // A ce stade, l'utilisateur est authentifi� par CAS

    $password = '';
    $sso_login = 'cas';
    $result = openSession($login,$password,$sso_login);
    session_write_close();
    header("Location:accueil.php");
    die();
} elseif (isset($use_sso) and ($use_sso == "lemon") and !$block_sso) {
    if (isset($_GET['login'])) $login = $_GET['login']; else $login = "";
    if (isset($_COOKIE['user'])) $cookie_user=$_COOKIE['user']; else $cookie_user="";
    if(empty($cookie_user) or $cookie_user != $login) {
      header("Location: ./login.php");
      // Echec de l'authentification lemonldap
      die();
      echo "</body></html>";
    }
  // A ce stade, l'utilisateur est authentifi� par Lemonldap
    $sso_login = 'lemon';
    $password = '';
    $login = strtoupper($login);
    $result = openSession($login,$password,$sso_login) ;
    session_write_close();
    header("Location:accueil.php");
    die();
} elseif (!($_GET['local']) and isset($use_sso) and ($use_sso == "lcs") and !$block_sso and
 !(isset($_POST['login']) && isset($_POST['no_anti_inject_password']))) {
  include LCS_PAGE_AUTH_INC_PHP;
  include LCS_PAGE_LDAP_INC_PHP;
  list ($idpers,$login) = isauth();
  if ($idpers) {
      list($user, $groups)=people_get_variables($login, false);
      $lcs_tab_login["nom"] = $user["nom"];
      $lcs_tab_login["email"] = $user["email"];
      $long = strlen($user["fullname"]) - strlen($user["nom"]);
      $lcs_tab_login["fullname"] = substr($user["fullname"], 0, $long) ;
      // A ce stade, l'utilisateur est authentifi� par CAS
      // Etablir � nouveau la connexion � la base
      if (empty($db_nopersist))
          $db_c = mysql_pconnect($dbHost, $dbUser, $dbPass);
      else
          $db_c = mysql_connect($dbHost, $dbUser, $dbPass);
      if (!$db_c || !mysql_select_db ($dbDb)) {
          echo "\n<p>Erreur : Echec de la connexion � la base de donn�es";
          exit;
      }
      if (is_eleve($login)) {
         // On renvoie � la page d'accueil des cahiers de texte
         session_write_close();
         header("Location: ./public/index.php");
         die();
      }
      $password = '';
      $result = openSession($login,$password,"lcs",$lcs_tab_login) ;
      $message = '';
      if ($result=="1") {
        // on efface les logs conform�ment � la dur�e de conservation des logs
        sql_query("delete from log where START < now() - interval " . getSettingValue("duree_conservation_logs") . " day and END < now()");
        // On renvoie � la page d'accueil
        session_write_close();
        header("Location: ./accueil.php");
        die();
      } else if ($result=="dl") {
        $message = "GEPI est momentan�ment inaccessible.";
      } else if ($result=="verrouillage") {
        $message = "Trop de tentatives de connexion infructueuses : votre compte est momentan�ment verrouill�.";
      } else if ($result=="liste_noire") {
        $message = "Connexion impossible : vous tentez de vous connecter � partir d'une adresse IP interdite.";
      } else if ($result=="2") {
        $message = "Vous avez bien �t� identifi� mais la mise � jour de votre profil dans GEPI n'a pas pu s'effectuer correctement. Impossible de continuer. Veuillez signaler ce probl�me � l'administrateur du site.";
      } else if ($result=="3") {
        $message = "Vous avez bien �t� identifi� mais un utilisateur \"local\" dans la base de GEPI, ayant le m�me login, existe d�j�. Impossible de continuer. Veuillez signaler ce probl�me � l'administrateur du site.";
      } else if ($result=="4") {
        $message = "Vous avez bien �t� identifi� mais vous ne figurez pas parmi les utilisateurs dans la base de GEPI. Impossible de continuer. Veuillez signaler ce probl�me � l'administrateur du site.";
      } else {
        $message = "Vous avez bien �t� identifi� mais un probl�me est survenu. Impossible de continuer. Veuillez signaler ce probl�me � l'administrateur du site.";
      }
      if ($message != '') {
          echo $message;
          echo "</body></html>";
          die();
      }
      if (resumeSession() ) {
        // On renvoie � la page d'accueil
        session_write_close();
        header("Location: ./accueil.php");
        die();
      } else {
    // L'utilisateur n'a pas �t� identifi�'
         header("Location:".LCS_PAGE_AUTHENTIF);
      }
   } else {
    // L'utilisateur n'a pas �t� identifi�'
         header("Location:".LCS_PAGE_AUTHENTIF);
   }

}


// User wants to be authentified
if (isset($_POST['login']) && isset($_POST['no_anti_inject_password'])) {
    $md5password = md5($NON_PROTECT['password']);

    if (isset($use_sso) and ($use_sso == "ldap_scribe") and !$block_sso) {
        $temp = openSession($_POST['login'], $NON_PROTECT['password'], $use_sso);
    } else {
        $temp = openSession($_POST['login'], $md5password);
    }

    if ($temp=="1") {
        // on efface les logs conform�ment � la dur�e de conservation des logs
        sql_query("delete from log where START < now() - interval " . getSettingValue("duree_conservation_logs") . " day and END < now()");
        // On renvoie � la page d'accueil
        session_write_close();
        header("Location: ./accueil.php");
        die();
    } else if ($temp=="c") {
    	session_write_close();
        header("Location: ./utilisateurs/mon_compte.php?change_mdp=yes&retour=accueil#changemdp");
        die();
    } else if ($temp=="dl") {
        $message = "Site momentan�ment inaccessible.";
    } else if ($temp=="verrouillage") {
        $message = "Trop de tentatives de connexion infructueuses : votre compte est momentan�ment verrouill�.";
    } else if ($temp=="liste_noire") {
        $message = "Connexion impossible : vous tentez de vous connecter � partir d'une adresse IP interdite.";
    } else {
        $message = "Identifiant ou mot de passe incorrect";
    }
} else {
    // on ferme une �ventuelle session ouverte pr�c�demment
    //closeSession($_GET['auto']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache" />
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache" />
<META HTTP-EQUIV="Expires" CONTENT="0" />
<title><?php echo getSettingValue("gepiSchoolName"); ?> : base de donn�es �l�ves | Identifiez vous...</title>
<link rel="stylesheet" type="text/css" href="./style.css" />
<script src="lib/functions.js" type="text/javascript" language="javascript"></script>
<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
<link rel="icon" type="image/ico" href="./favicon.ico" />
</head>
<body onload="document.getElementById('login').focus()">
<div>
<?php
//On v�rifie si le module est activ�
if (getSettingValue("active_cahiers_texte")=='y') {
   echo "<div id='lien_cahier_texte'><a href=\"./public/index.php\"><img src='./images/icons/cahier_texte.png' alt='Cahier de texte' class='link' /> Consulter les cahiers de texte</a> (tout public)</div>";
}
echo "<div class='center'>";

if ((getSettingValue("disable_login"))=='yes') echo "<br><br><font color=\"red\" size=\"+1\">Le site est en cours de maintenance et temporairement inaccessible.<br />Veuillez nous excuser de ce d�rangement et r�essayer de vous connecter ult�rieurement.</font><br>";

?>
<form action="login.php" method="post" style="width: 100%; margin-top: 24px; margin-bottom: 48px;">

<fieldset id="login_box">
<h2><?php echo getSettingValue("gepiSchoolName"); ?></h2>
<table style="width: 75%; border: 0; margin-top: 10px; margin-right: 0; margin-left: auto;" cellpadding="3" cellspacing="0">
  <tr>
  	<td colspan="2" style="padding-bottom: 15px;">
  	<?php
		if (isset($message)) {
			echo("<p style='color: red; margin:0;padding:0;'>" . $message . "</p>");
		} else {
			echo "<p style='margin:0;padding:0;'>Afin d'utiliser Gepi, vous devez vous identifier.</p>";
		}
	?>
  	</td>
  </tr>
  <tr>
    <td style="text-align: right; width: 30%; font-variant: small-caps;"><label for="login">Identifiant</label></td>
    <td style="text-align: center; width: 40%;"><input type="text" id="login" name="login" size="16" tabindex="1" /></td>
  </tr>
  <tr>
    <td style="text-align: right; width: 30%; font-variant: small-caps;"><label for="no_anti_inject_password">Mot de passe</label></td>
    <td style="text-align: center; width: 40%;"><input type="password" id="no_anti_inject_password" name="no_anti_inject_password" size="16" onkeypress="capsDetect(arguments[0]);" tabindex="2" /></td>
  </tr>
  <tr>
    <td></td>
    <td style="text-align: center; width: 40%; padding-top: 20px;"><input type="submit" name="submit" value="Valider" style="font-variant: small-caps;" tabindex="3" /></td>
  </tr>
</table>
</fieldset>
</form>
</div>
<div class="center" style="margin-bottom: 32px;">
<p><a href="javascript:centrerpopup('gestion/info_vie_privee.php',700,480,'scrollbars=yes,statusbar=no,resizable=yes')"><img src='./images/icons/vie_privee.png' alt='Vie priv�e' class='link' /> Informations vie priv�e</a></p>
<p><a href="mailto:<?php echo getSettingValue("gepiAdminAdress"); ?>">[Contacter l'administrateur]</a></p>
</div>
<div id="login_footer">
<a href="http://gepi.mutualibre.org">GEPI : Outil de gestion, de suivi, et de visualisation graphique des r�sultats scolaires (�coles, coll�ges, lyc�es)</a><br />
Copyright &copy; 2001-2007
<?php
reset($gepiAuthors);
$i = 0;
while (list($name, $adress) = each($gepiAuthors)) {
    if ($i != "0") echo ", ";
    echo("<a href=\"mailto:" . $adress . "\">" . $name . "</a> ");
    $i++;
}

?>
</div>
</div>
</body>
</html>