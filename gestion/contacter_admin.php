<?php
/*
 * $Id$
 *
 * Copyright 2001, 2007 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Patrick Duthilleul
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

// On pr�cise de ne pas traiter les donn�es avec la fonction anti_inject
$traite_anti_inject = 'no';
// Initialisations files
require_once("../lib/initialisations.inc.php");

// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == '0') {
   header("Location: ../logout.php?auto=1");
   die();
};
if (!checkAccess()) {
    header("Location: ../logout.php?auto=1");
    die();
}

$action = isset($_POST["action"]) ? $_POST["action"] : '';
$nama = isset($_POST["nama"]) ? $_POST["nama"] : '';
$message = isset($_POST["message"]) ? $_POST["message"] : '';
$email_reponse = isset($_POST["email_reponse"]) ? $_POST["email_reponse"] : '';
$casier = isset($_POST["casier"]) ? $_POST["casier"] : '';

//**************** EN-TETE *****************
require_once("../lib/header.inc");
//**************** FIN EN-TETE *************

?>
<h1 class='gepi'>GEPI - Obtenir de l'aide de l'administrateur.</h1>
<?php

$objet_msg=isset($_POST['objet_msg']) ? $_POST['objet_msg'] : "Demande d'aide dans GEPI";
if($objet_msg=='') {
	echo "<p style='color:red'>Le sujet du mail n'a pas �t� renseign�.<br />Veuillez corriger.</p>\n";
	$action="";
}

switch($action)
{
//envoi du message
case "envoi":
	//N.B. pour peaufiner, mettre un script de v�rification de l'adresse email et du contenu du message !

	$corps_message=$message;

	$message = "Demande d'aide depuis GEPI :\n";
	$message .= "Demandeur : ".$nama."\n";
	$message .= "Statut : ".$_SESSION['statut']."\n";
	// ===============
	// Modif 20070927:
	//$message .= "Etablissement : ".getSettingValue("gepiSchoolName")."\n".unslashes($message);
	$message .= "Etablissement : ".getSettingValue("gepiSchoolName")."\n";
	// ===============

	$message.="\n".$corps_message."\n";

	if ($_SESSION['statut'] != "responsable" AND $_SESSION['statut'] != "eleve") {
		//$message .= "\n\nMode de r�ponse : ".($email_reponse =="" ? "dans le casier =>$casier" :"par email.");
		//$message .= "\n\nMode de r�ponse : ".($email_reponse =="" ? "dans le casier =>$casier" :"par email (<a href='mailto:$email_reponse'>$email_reponse</a>).");
		$message .= "\n\nMode de r�ponse : ".($email_reponse =="" ? "dans le casier =>$casier" :"par email ($email_reponse).");
	} else {
		$message .= "\n\nMode de r�ponse : par email ";
		if($email_reponse!="") {
			//$message.="(<a href='mailto:$email_reponse'>$email_reponse</a>)";
			$message.="($email_reponse)";
		}
		else{
			$message.="(si sp�cifi�)";
		}
	}

	// On ne devrait pas POSTer l'identit�, mais plut�t la lire de la SESSION...
	// ... ajout d'un test...
	if("$nama"!=$_SESSION['prenom']." ".$_SESSION['nom']){
		$message.="\n\n";
		$message.="Bizarrerie: L'identit� POST�e est: $nama\n            Et l'identit� de connexion est: ".$_SESSION['prenom']." ".$_SESSION['nom'];
	}

	// ===============
	// Ajout 20070927:
	unslashes($message);
	// ===============

	/*
	$envoi = mail(getSettingValue("gepiAdminAdress"),
		"Demande d'aide dans GEPI",
		$message,
	"From: ".($email_reponse != "" ? "$nama <$email_reponse>" : getSettingValue("gepiAdminAdress"))."\r\n"
	.($email_reponse != "" ? "Reply-To: $nama <$email_reponse>\r\n" :"")
	."X-Mailer: PHP/" . phpversion());
	*/
	//$gepiPrefixeSujetMail="[clg-tartemplume]";
	$gepiPrefixeSujetMail=getSettingValue("gepiPrefixeSujetMail") ? getSettingValue("gepiPrefixeSujetMail") : "";
	//$gepiPrefixeSujetMail=getSettingValue("gepiPrefixeSujetMail");
	if($gepiPrefixeSujetMail!='') {$gepiPrefixeSujetMail.=" ";}

	$gepiAdminAdress=getSettingValue("gepiAdminAdress");
	if($gepiAdminAdress==""){
		echo "<p><span style='color:red;>ERREUR</span>: L'adresse mail de l'administrateur n'est pas renseign�e.</p>\n";
		require("../lib/footer.inc.php");
		die();
	}

	//$objet_msg=isset($_POST['objet_msg']) ? $_POST['objet_msg'] : "Demande d'aide dans GEPI";
	$objet_msg=trim($objet_msg);
	unslashes($objet_msg);
	//echo "\$objet_msg=$objet_msg<br />";
	//stripslashes($objet_msg);
	//if($objet_msg=='') {$objet_msg="Demande d'aide dans GEPI";}

  $subject = $gepiPrefixeSujetMail.$objet_msg;
  $subject = "=?ISO-8859-1?B?".base64_encode($subject)."?=\r\n";

  $from = $email_reponse != "" ? "$nama <$email_reponse>" : $gepiAdminAdress;
  
  $headers = "X-Mailer: PHP/" . phpversion()."\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
  $headers .= "From: $from\r\n";
  if ($email_reponse != "") {
    $headers .= "Reply-To: $from\r\n";
    if (getSettingValue("gepiAdminAdressFormHidden")!="y") {
      $headers .= "Cc: $nama <$email_reponse>\r\n";
    }
  }

	$envoi = mail($gepiAdminAdress,
		$subject,
		$message,
    $headers);

	if ($envoi) {
		echo "<br /><br /><br />\n";
		echo "<p style=\"text-align: center\">Votre message a �t� envoy�";

		if($email_reponse!="") {
			echo ", vous recevrez rapidement<br />une r�ponse dans votre bo�te aux lettres �lectronique, veuillez la consulter r�guli�rement.";

			if(!my_ereg("[a-zA-Z0-9_.-]+@[a-zA-Z0-9.-]{2,}[.][a-zA-Z]{2,3}",$email_reponse)) {
				echo "</p>\n";
				echo "<p style=\"text-align: center\">L'adresse <span style='color:red'>$email_reponse</span> ne semble pas correctement format�e.<br />Si l'adresse est correcte, ne tenez pas compte de cette remarque.<br />Sinon, vous ne pourrez pas obtenir de r�ponse par courriel/email.\n";
			}
		}
		else {
			if($_SESSION['statut']=='professeur') {
				echo ", vous recevrez rapidement<br />une r�ponse dans votre casier, veuillez le consulter r�guli�rement.";
			}
			else {
				//echo ".";
				echo ", l'administrateur va le prendre en compte rapidement, mais ne pourra pas vous
				r�pondre par courrier �lectronique car vous n'avez pas compl�t� d'adresse courriel/email.";
			}
		}
		echo "<br /><br /><br />\n";
		echo "<a href=\"javascript:self.close();\">Fermer</a></p>\n";
		echo "<noscript><p style='color:red;'>Il n'est pas possible de refermer la fen�tre par le lien ci-dessus lorsque javascript est d�sactiv�.</p></noscript>\n";
	} else {
		echo "<br /><br /><br /><P style=\"text-align: center\"><font color=\"red\">ATTENTION : impossible d'envoyer le message, contactez l'administrateur pour lui signaler l'erreur ci-dessus.</font></p>\n";
	}
	break;
default://formulaire d'envoi
	echo "<table cellpadding='5'>";
	echo "<tr><td>Message post� par :</td><td><b>".$_SESSION['prenom'] . " " . $_SESSION['nom']."</b></td></tr>\n";
	echo "<tr><td>Nom et pr�nom de l'administrateur : </td><td><b>".getSettingValue("gepiAdminNom")." ".getSettingValue("gepiAdminPrenom")."</b></td></tr>\n";

	echo "<tr><td>Nom de l'�tablissement : </td><td><b>".getSettingValue("gepiSchoolName")."</b></td></tr>\n";

	if(getSettingValue("gepiAdminAdressFormHidden")!="y"){
		echo "<tr><td colspan=2>Utilisez l'adresse <b><a href=\"mailto:" . getSettingValue("gepiAdminAdress") . "\">".getSettingValue("gepiAdminAdress")."</a></b> ou bien r�digez votre message ci-dessous : </td><td></tr>\n";
	}
	else{
		echo "<tr><td colspan=2>R�digez votre message ci-dessous : </td><td></tr>\n";
	}

	echo "</table>\n";
	?>
	<form action="contacter_admin.php" method="post" name="doc">
	<input type="hidden" name="nama" value="<?php echo $_SESSION['prenom']." ".$_SESSION['nom']; ?>" />
	<input type="hidden" name="action" value="envoi" />
	<p>Sujet&nbsp;<span style='color:red' alt='Ce champ doit �tre renseign�' title='Ce champ doit �tre renseign�'>(*)</span>&nbsp;: <!--input type='text' name='objet_msg' value="Demande d'aide dans GEPI" size='35' maxlength='80' /--><input type='text' name='objet_msg' id='objet_msg' value="" size='35' maxlength='80' /><br />

	<?php
	echo "<textarea valign='bottom' name='message' cols='50' rows='5'>Contenu du message : $message</textarea><br />\n";


	echo "E-mail pour la r�ponse&nbsp;: ";
	if ($_SESSION['statut'] != "responsable" AND $_SESSION['statut'] != "eleve") {
		echo "(<i style='font-size:small;'>facultatif, une r�ponse vous sera adress�e dans votre casier si vous ne pr�cisez pas d'e-mail</i>)";
	}
	echo "<br />\n";

	echo "<input type='text' name='email_reponse' id='email_reponse' size='40' maxlength='256' ";
	if($email_reponse!='') {
		echo "value='$email_reponse' ";
	}
	else {
		$sql="SELECT email FROM utilisateurs WHERE login='".$_SESSION['login']."';";
		$res_mail=mysql_query($sql);
		if(mysql_num_rows($res_mail)>0) {
			$lig_mail=mysql_fetch_object($res_mail);
			echo "value='$lig_mail->email' ";
		}
	}
	echo "/>\n";
	echo "<br />\n";

	if ($_SESSION['statut'] != "responsable" AND $_SESSION['statut'] != "eleve") {
		echo "Ou num�ro de votre casier en salle des professeurs pour la r�ponse&nbsp;:<br />";

		if($casier!='') {
			echo "<input type='text' name='casier' size='40' maxlength='256' value='$casier' />\n";
		}
		else {
			echo "<input type='text' name='casier' size='40' maxlength='256' value='Casier N�' />\n";
		}
		echo "<br />\n";
	}
	echo "</p>\n";

	echo "<p align='center'>";
	//echo "<input type='submit' value='Envoyer le message' />\n";
	echo "<input type='button' value='Envoyer le message' onClick='verif_et_valide_envoi();' />\n";
	echo "</p>\n";

	echo "<script type='text/javascript'>
	function verif_et_valide_envoi() {
		if(document.getElementById('objet_msg').value=='') {
			alert('Aucun sujet n\\' a �t� pr�cis�. Veuillez corriger.')
		}
		else {

			if(document.getElementById('email_reponse')) {
				email=document.getElementById('email_reponse').value;
	
				if(email=='') {
					//confirmation=confirm('Vous n avez pas saisi d adresse courriel/email.\\nVous ne pourrez pas recevoir de r�ponse par courrier �lectronique.\\nSouhaitez-vous n�anmoins poster le message?');
					confirmation=confirm('Vous n\\'avez pas saisi d\\'adresse courriel/email.\\nVous ne pourrez pas recevoir de r�ponse par courrier �lectronique.\\nSouhaitez-vous n�anmoins poster le message?');
	
					if(confirmation) {	
						document.forms['doc'].submit();
					}
				}
				else {
					//var verif = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,3}$/
					//var verif2 = /^[a-zA-Z0-9_-]{1,}[.][a-zA-Z0-9_-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,3}$/
					//if (verif.exec(email) == null) {
					//if ((verif.exec(email) == null)&&(verif2.exec(email) == null)) {
					var verif = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9.-]{2,}[.][a-zA-Z]{2,3}$/
					if (verif.exec(email) == null) {
						confirmation=confirm('L\\'adresse courriel/email saisie ne semble pas valide.\\nVeuillez contr�ler la saisie et confirmer votre envoi si l\\'adresse est correcte.\\nSouhaitez-vous n�anmoins poster le message?');
	
						if(confirmation) {
							document.forms['doc'].submit();
						}
					}
					else {
						document.forms['doc'].submit();
					}
				}
			}
			else {
				document.forms['doc'].submit();
			}
		}
	}
</script>\n";
	echo "<noscript>\n";
	echo "<p align='center'><input type='submit' value='Envoyer le message sans javascript' /></p>\n";
	echo "</noscript>\n";
	echo "</form>\n";

	break;
}
require("../lib/footer.inc.php");
?>
