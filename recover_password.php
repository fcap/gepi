<?php
/*
 * $Id$
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
$niveau_arbo = 0;

// On indique qu'il faut cr�e des variables non prot�g�es (voir fonction cree_variables_non_protegees())
// ceci pour que les mots de passe ne soient pas alt�r�s
$variables_non_protegees = 'yes';

// Initialisations files
require_once("./lib/initialisations.inc.php");

if (getSettingValue("enable_password_recovery") != "yes") {
	echo "<p>Vous n'avez pas � �tre ici.</p>";
	die();
}
$message = false;

if (isset($_POST['login'])) {
	$email = (isset($_POST['email'])) ? $_POST['email'] : "noemail";
	$user_login = (!empty($_POST['login'])) ? $_POST['login'] : "nologin";
	// Le formulaire de demande a �t� post�, on v�rifie et on envoit un mail
	$test = mysql_query("SELECT statut FROM utilisateurs WHERE (" .
			"login = '" . $user_login . "' and " .
			"email = '" . $email . "')");
	if (mysql_num_rows($test) == 1) {
		// On a un utilisateur qui a bien ces coordonn�es.
		
		// On va maintenant v�rifier son statut, et s'assurer que le statut en question
		// est bien autoris� � utiliser l'outil de r�initialisation
		$user_statut = mysql_result($test, 0);
		$ok = false;
		
		if (
			($user_statut == "administrateur" AND getSettingValue("GepiPasswordReinitAdmin") == "yes") OR
			($user_statut == "professeur" AND getSettingValue("GepiPasswordReinitProf") == "yes") OR
			($user_statut == "scolarite" AND getSettingValue("GepiPasswordReinitScolarite") == "yes") OR
			($user_statut == "cpe" AND getSettingValue("GepiPasswordReinitCpe") == "yes") OR
			($user_statut == "eleve" AND getSettingValue("GepiPasswordReinitEleve") == "yes") OR
			($user_statut == "responsable" AND getSettingValue("GepiPasswordReinitParent") == "yes")
		) {
			$ok = true;
		} else {
			$ok = false;
		}
		
		if (!$ok) {
			$message = "Pour des raisons de s�curit�, votre statut utilisateur ne vous permet pas de r�initialiser votre mot de passe par cette proc�dure. Vous devrez donc contacter l'administrateur pour obtenir un nouveau mot de passe.";
		} else {
			//On envoie un mail!
			// On g�n�re le ticket
	        $length = rand(85, 100);
	        for($len=$length,$r='';strlen($r)<$len;$r.=chr(!mt_rand(0,2)? mt_rand(48,57):(!mt_rand(0,1) ? mt_rand(65,90) : mt_rand(97,122))));
	        $ticket = $r;
	        // On enregistre le ticket dans la base
	        $expiration_timestamp = time()+15*60;
	        $expiration_date = date("Y-m-d G:i:s", $expiration_timestamp);
	        $res = mysql_query("UPDATE utilisateurs SET " .
	        		"password_ticket = '" . $ticket . "', " .
	        		"ticket_expiration = '" . $expiration_date . "' WHERE (" .
	        		"login = '" . $user_login . "')");
	        if ($res) {
	        	// Si l'enregistrement s'est bien pass�, on envoi le mail
	        	$ticket_url = "";
	        	if (!empty($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != "Off") {
	        		$ticket_url .= "https://";
	        	} else {
	        		$ticket_url .= "http://";
	        	}
	        	$ticket_url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . "?ticket=".$ticket; 
	        	$mail_content = "Bonjour,\n" .
	        			"Afin de r�initialiser votre mot de passe, veuillez cliquer sur le lien ci-dessous.\n" .
	        			"Vous pouvez �galement copier/coller l'adresse compl�te dans votre navigateur.\n" .
	        			"Ce lien doit �tre utilis� avant l'heure suivante : " .
	        			date("G:i:s",$expiration_timestamp) ."\n" .
	        			"Si vous n'utilisez pas ce lien avant cette limite, le ticket ne sera plus valide.\n" .
	        			"\n" .
	        			"Lien pour r�initialiser votre mot de passe :\n" .
	        			$ticket_url . "\n";
	        	
	        	//- Debug - echo $mail_content;
	        	//- Debug - if ($mail_content) {		
	        	if (mail($email, "Gepi - r�initialisation de votre mot de passe", $mail_content)) {
	        		$message = "Un mail vient de vous �tre envoy�.";
	        	} else {
	        		$message = "Erreur lors de l'envoi du mail.";
	        	}
	        } else {
	        	echo mysql_error();
	        }
		} // Fin: statut autoris�
	} else {
		$message = "Votre login ou votre email n'est pas valide.";
	}
}

if (isset($_POST['no_anti_inject_password'])) {
	// Une r�initialisation de mot de passe vient d'�tre valid�e
	// On v�rifie que le mot de passe et sa confirmation sont correctes et
	// que le mot de passe r�pond aux crit�res de s�curit� requis
	$message = false;
	// On r�cup�re le statut de l'utilisateur associ� au ticket, et l'heure d'expiration :
	$req = mysql_query("SELECT statut, UNIX_TIMESTAMP(ticket_expiration) expiration FROM utilisateurs WHERE password_ticket = '" . $_GET['ticket'] . "'");
	if (mysql_num_rows($req) != 1) {
		$message = "Erreur : votre ticket n'est pas valide.";
	} else {
		$user_status = mysql_result($req, 0, "statut");
		$expiration = mysql_result($req, 0, "expiration");
		if ($expiration < time()) {
			$message = "Erreur : votre ticket n'est pas valide";
		} else {
			if (($user_status == 'professeur') or ($user_status == 'cpe') or ($user_status == 'responsable') or ($user_status == 'eleve')) {
			    // Mot de passe comportant des lettres et des chiffres
			    $flag = 0;
			} else {
			    // Mot de passe comportant des lettres et des chiffres et au moins un caract�re sp�cial
			    $flag = 1;
			}
			
			if ($NON_PROTECT["password"] != $NON_PROTECT["confirmation"]) {
				$message = "Mot de passe et confirmation non identiques !";
			} else if (!(verif_mot_de_passe($NON_PROTECT['password'],$flag))) {
				$message = "Mot de passe non conforme.";
			}
			if (!$message) {
				// Si aucune erreur n 'a �t� renvoy�e, on enregistre le mot de passe
				$reg_password = md5($NON_PROTECT["password"]);
				$res = mysql_query("UPDATE utilisateurs SET password_ticket = '', password = '" . $reg_password . "' WHERE password_ticket = '" . $_GET['ticket'] . "'");
				if ($res) {
					$update_successful = true;
				} else {
					$message = "Erreur lors de la mise � jour de votre mot de passe.";
				}
			}
		}
	}	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache" />
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache" />
<META HTTP-EQUIV="Expires" CONTENT="0" />
<title><?php echo getSettingValue("gepiSchoolName"); ?> : R�cup�ration du mot de passe...</title>
<link rel="stylesheet" type="text/css" href="./style.css" />
<script src="lib/functions.js" type="text/javascript" language="javascript"></script>
<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
<link rel="icon" type="image/ico" href="./favicon.ico" />
</head>
<body>
<div>
<?php
echo "<div class='center'>";

// Inutile d'aller plus loin si les connexions ont �t� d�sactiv�es.
if ((getSettingValue("disable_login"))=='yes') {
	echo "<br/><br/><font color=\"red\" size=\"+1\">Le site est en cours de maintenance et temporairement inaccessible.<br />Veuillez nous excuser de ce d�rangement et r�essayer de vous connecter ult�rieurement.</font><br>";
	echo "</div></body></html>";
}

if (isset($update_successful)) {
	echo "<p style='margin-top: 100px; color:red;'>Votre mot de passe a �t� mis � jour avec succ�s.</p>";
	echo "<p class=bold style='margin-left: auto; margin-right: auto; margin-top: 40px;'><a href=\"login.php\"><img src='./images/icons/back.png' alt='Retour' class='back_link'/> Retour page de login</a></p>";	
	echo "</div></body></html>";
	die();
}

if (isset($_GET['ticket']) and !isset($update_successful)) {

	// Un ticket a �t� propos�. Il a d�j� �t� filtr� contre les injections.
	$error = false;
	$ticket = $_GET['ticket'];
	if (strlen($ticket) < 85) {
		$error = true;
	} else {
		$test = mysql_query("SELECT statut FROM utilisateurs WHERE password_ticket = '" . $ticket . "'");
		if (mysql_num_rows($test) != "1") {
			$error = true;
		} else {
			// Si on arrive l�, c'est que le ticket est valide !
			// On affiche le formulaire pour changer le mot de passe.
			$user_status = mysql_result($test, 0);
			if (($user_status == 'professeur') or ($user_status == 'cpe') or ($user_status == 'responsable') or ($user_status == 'eleve')) {
			    // Mot de passe comportant des lettres et des chiffres
			    $flag = 0;
			} else {
			    // Mot de passe comportant des lettres et des chiffres et au moins un caract�re sp�cial
			    $flag = 1;
			}
	?>
<form action="recover_password.php?ticket=<?php echo $ticket; ?>" method="post" style="width: 100%; margin-top: 24px; margin-bottom: 48px;">
<?php    echo "<p style='margin-top: 50px; color:red; margin-bottom: 30px;width: 80%;margin-left: auto; margin-right: auto;'><b>Attention : le mot de passe doit comporter ".getSettingValue("longmin_pwd") ." caract�res minimum. ";
    if ($flag == 1)
        echo "Il doit comporter au moins une lettre, au moins un chiffre et au moins un caract�re sp�cial parmi&nbsp;: ".htmlentities($char_spec);
    else
        echo "Il doit comporter au moins une lettre et au moins un chiffre.";
?>
<fieldset id="login_box" style="width: 50%; margin-top: 0;">
<div id="header">
<h2>R�initialisation du mot de passe</h2>
</div>
<table style="width: 85%; border: 0; margin-top: 10px; margin-right: 15px; margin-left: auto;" cellpadding="3" cellspacing="0">
  <tr>
  	<td colspan="2" style="padding-bottom: 15px; text-align: right;">
  	<?php
		if ($message) {
			echo("<p style='color: red; margin:0;padding:0;'>" . $message . "</p></td></tr>");
		} else {
			echo "<p style='margin:0;padding:0;'>Veuillez saisir et confirmer votre nouveau mot de passe</p>";
		}
	?>
  	</td>
  </tr>
  <tr>
    <td style="text-align: right; width: 50%; font-variant: small-caps;"><label for="password">Mot de passe</label></td>
    <td style="text-align: center; width: 40%;"><input type="password" id="password" name="no_anti_inject_password" size="16" tabindex="1" /></td>
  </tr>
  <tr>
    <td style="text-align: right; width: 50%; font-variant: small-caps;"><label for="confirmation">Confirmation</label></td>
    <td style="text-align: center; width: 40%;"><input type="password" id="confirmation" name="no_anti_inject_confirmation" size="16" tabindex="2" /></td>
  </tr>
  <tr>
    <td style="text-align: center; padding-top: 10px;">
    </td>
    <td style="text-align: center; width: 40%; padding-top: 20px;"><input type="submit" name="submit" value="Valider" style="font-variant: small-caps;" tabindex="3" /></td>
  </tr>
</table>
</fieldset>
</form>
</div>	
	
	
	<?php			
		}
	}
	
	if ($error) {
		echo "<p style='margin-top: 100px; color:red;'>Votre ticket n'est pas valide.</p>";		
	}

} else {
?>

<p style='margin-top: 60px;padding-left: 20%; padding-right: 20%;'>Afin de r�initialiser votre mot de passe, vous devez valider ce formulaire en indiquant votre login et votre adresse e-mail.
Cette adresse e-mail doit �tre d�j� associ�e � votre compte au sein de Gepi.
<br/>Si votre login et votre e-mail sont corrects, vous recevrez sur cette adresse les instructions pour r�initialiser votre mot de passe.<br/>
<span class='red'>Vous devez r�initialiser votre mot de passe dans les 15 minutes suivant la validation de ce formulaire.</span></p>
<form action="recover_password.php" method="post" style="width: 100%; margin-top: 24px; margin-bottom: 48px;">

<fieldset id="login_box" style="width: 50%; margin-top: 0;">
<div id="header">
<h2>Mot de passe perdu</h2>
</div>
<table style="width: 85%; border: 0; margin-top: 10px; margin-right: 15px; margin-left: auto;" cellpadding="3" cellspacing="0">
  <tr>
  	<td colspan="2" style="padding-bottom: 15px; text-align: right;">
  	<?php
		if ($message) {
			echo("<p style='color: red; margin:0;padding:0 0 0 30px;'>" . $message . "</p></td></tr>");
		} else {
			echo "<p style='margin:0;padding:0;'>Veuillez indiquer votre login et votre email</p>";
	?>
  	</td>
  </tr>
  <tr>
    <td style="text-align: right; width: 50%; font-variant: small-caps;"><label for="login">Identifiant</label></td>
    <td style="text-align: center; width: 40%;"><input type="text" id="login" name="login" size="16" tabindex="1" /></td>
  </tr>
  <tr>
    <td style="text-align: right; width: 50%; font-variant: small-caps;"><label for="email">Adresse e-mail</label></td>
    <td style="text-align: center; width: 40%;"><input type="text" id="email" name="email" size="16" tabindex="2" /></td>
  </tr>
  <tr>
    <td style="text-align: center; padding-top: 10px;">
    </td>
    <td style="text-align: center; width: 40%; padding-top: 20px;"><input type="submit" name="submit" value="Valider" style="font-variant: small-caps;" tabindex="3" /></td>
  </tr>
  <?php } ?>
</table>
</fieldset>
</form>
</div>

<?php } ?>

<div class="center" style="margin-bottom: 32px;">
<p><a href="mailto:<?php echo getSettingValue("gepiAdminAdress"); ?>">[Contacter l'administrateur]</a></p>
</div>
<div id="login_footer">
<a href="http://gepi.mutualibre.org">GEPI : Outil de gestion, de suivi, et de visualisation graphique des r�sultats scolaires (�coles, coll�ges, lyc�es)</a><br />
Copyright &copy; 2001-2007
</div>
</div>
</body>
</html>