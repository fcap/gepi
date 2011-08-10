<?php
/** Fonctions accessibles dans toutes les pages
 * 
 * $Id$
 * 
 * Copyright 2001, 2011 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
 * 
 * @package Initialisation
 * @subpackage general
 *
*/

/**
 * Affichage des statistiques de la classe sur les bulletins si � 1
 * 
 * @global int $GLOBALS['min_max_moyclas']
 * @name $min_max_moyclas
 */
$GLOBALS['min_max_moyclas'] = 0;

/**
 * Effectif du groupe
 * 
 * @global int $GLOBALS['eff_groupe']
 * @name $eff_groupe
 */
$GLOBALS['eff_groupe'] = 0;

/**
 * Tableau contenant les informations pour afficher une infobulle
 * 
 * @global array $GLOBALS['tabdiv_infobulle']
 * @name $tabdiv_infobulle
 */
$GLOBALS['tabdiv_infobulle'] = array();

/**
 * Texte � afficher quand une p�riode est close
 * 
 * @global string $GLOBALS['gepiClosedPeriodLabel']
 * @name $gepiClosedPeriodLabel
 */
$GLOBALS['gepiClosedPeriodLabel'] = '';

/**
 * Version de GEPI stable
 * 
 * @global mixed $GLOBALS['gepiVersion']
 * @name $gepiVersion
 */
$GLOBALS['gepiVersion'] = '';

/**
 * Version de GEPI release candidate
 * 
 * @global mixed $GLOBALS['gepiRcVersion']
 * @name $gepiRcVersion
 */
$GLOBALS['gepiRcVersion'] = '';

/**
 * Version de GEPI Beta
 * 
 * @global mixed $GLOBALS['gepiBetaVersion']
 * @name $gepiBetaVersion
 */
$GLOBALS['gepiBetaVersion'] = '';

/**
 * 
 * @global int $GLOBALS['totalsize']
 * @name $totalsize
 */
$GLOBALS['totalsize'] = 0;



/**
 * Fonctions de manipulation du gepi_alea contre les attaques CRSF
 * 
 * @see share-csrf.inc.php
 */
include_once dirname(__FILE__).'/share-csrf.inc.php';
/**
 * Fonctions qui produisent du code html
 * 
 * @see share-html.inc.php
 */
include_once dirname(__FILE__).'/share-html.inc.php';
/**
 * Fonctions de manipulation des conteneurs et des notes
 * 
 * @see share-notes.inc.php
 */
include_once dirname(__FILE__).'/share-notes.inc.php';
/**
 * Fonctions de manipulation des conteneurs et des notes
 * 
 * @see share-aid.inc.php
 */
include_once dirname(__FILE__).'/share-aid.inc.php';








/**
 * Envoi d'un courriel
 *
 * @param string $sujet Le sujet du message
 * @param string $message Le message
 * @param string $destinataire Le destinataire
 * @param string $ajout_headers Text � ajouter dans le header
 */
function envoi_mail($sujet, $message, $destinataire, $ajout_headers='') {

	$gepiPrefixeSujetMail=getSettingValue("gepiPrefixeSujetMail") ? getSettingValue("gepiPrefixeSujetMail") : "";

	if($gepiPrefixeSujetMail!='') {$gepiPrefixeSujetMail.=" ";}

  $subject = $gepiPrefixeSujetMail."GEPI : $sujet";
  $subject = "=?ISO-8859-1?B?".base64_encode($subject)."?=\r\n";
  
  $headers = "X-Mailer: PHP/" . phpversion()."\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
  $headers .= "From: Mail automatique Gepi <ne-pas-repondre@".$_SERVER['SERVER_NAME'].">\r\n";
  $headers .= $ajout_headers;

	// On envoie le mail
	$envoi = mail($destinataire,
		$subject,
		$message,
	  $headers);
}

/**
 * Verification de la validit� d'un mot de passe
 * 
 * longueur : getSettingValue("longmin_pwd") minimum
 * 
 * compos� de lettres et d'au moins un chiffre
 *

 * @param string $password Mot de passe
 * @param boolean $flag Si $flag = 1, il faut �galement au moins un caract�res sp�cial (voir $char_spec dans global.inc)
 * @return boolean TRUE si le mot de passe est valable
 * @see getSettingValue()
 * @todo on d�clare $char_spec alors qu'on ne l'utilise pas, n'y aurait-il pas un probl�me ?
 */
function verif_mot_de_passe($password,$flag) {
	if ($flag == 1) {
		if(preg_match("/(^[a-zA-Z]*$)|(^[0-9]*$)/", $password)) {
			return FALSE;
		}
		elseif(preg_match("/^[[:alnum:]\W]{".getSettingValue("longmin_pwd").",}$/", $password) and preg_match("/[\W]+/", $password) and preg_match("/[0-9]+/", $password)) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
	else {
		if(preg_match("/(^[a-zA-Z]*$)|(^[0-9]*$)/", $password)) {
			return FALSE;
		}
		elseif (strlen($password) < getSettingValue("longmin_pwd")) {
			return FALSE;
		}
		else {
			return TRUE;
		}
	}
}

/**
 * Teste si le login existe d�j� dans la base
 *
 * @param string $s le login test�
 * @return string yes si le login existe, no sinon
 */
function test_unique_login($s) {
    // On v�rifie que le login ne figure pas d�j� dans la base utilisateurs
    $test1 = mysql_num_rows(mysql_query("SELECT login FROM utilisateurs WHERE (login='$s' OR login='".strtoupper($s)."')"));
    if ($test1 != "0") {
        return 'no';
    } else {
        $test2 = mysql_num_rows(mysql_query("SELECT login FROM eleves WHERE (login='$s' OR login = '".strtoupper($s)."')"));
        if ($test2 != "0") {
            return 'no';
        } else {
			$test3 = mysql_num_rows(mysql_query("SELECT login FROM resp_pers WHERE (login='$s' OR login='".strtoupper($s)."')"));
			if ($test3 != "0") {
				return 'no';
			} else {
	            return 'yes';
	        }
        }
    }
}

/**
 * V�rifie l'unicit� du login
 * 
 * On v�rifie que le login ne figure pas d�j� dans une des bases �l�ve des ann�es pass�es 
 *
 * @param string $s le login � v�rifier
 * @param <type> $indice ??
 * @return string yes si le login existe, no sinon
 */
function test_unique_e_login($s, $indice) {
    // On v�rifie que le login ne figure pas d�j� dans la base utilisateurs
    $test7 = mysql_num_rows(mysql_query("SELECT login FROM utilisateurs WHERE (login='$s' OR login='".strtoupper($s)."')"));

    if ($test7 != "0") {

        // Si le login figure d�j� dans une des bases �l�ve des ann�es pass�es ou bien
        // dans la base utilisateurs, on retourne 'no' !
        return 'no';
    } else {
        // Si le login ne figure pas dans une des bases �l�ve des ann�es pass�es ni dans la base
        // utilisateurs, on v�rifie qu'un m�me login ne vient pas d'�tre attribu� !
        $test_tempo2 = mysql_num_rows(mysql_query("SELECT col2 FROM tempo2 WHERE (col2='$s' or col2='".strtoupper($s)."')"));
        if ($test_tempo2 != "0") {
            return 'no';
        } else {
            $reg = mysql_query("INSERT INTO tempo2 VALUES ('$indice', '$s')");
            return 'yes';
        }
    }
}

/**
 * G�n�re le login � partir du nom et du pr�nom
 * 
 * G�n�re puis nettoie un login pour qu'il soit valide et unique
 * 
 * Le mode de g�n�ration doit �tre pass� en argument
 * 
 * name             � partir du nom
 * 
 * name8            � partir du nom, r�duit � 8 caract�res
 * 
 * fname8           premi�re lettre du pr�nom + nom, r�duit � 8 caract�res
 * 
 * fname19          premi�re lettre du pr�nom + nom, r�duit � 19 caract�res
 * 
 * firstdotname     pr�nom.nom
 * 
 * firstdotname19   pr�nom.nom r�duit � 19 caract�res
 * 
 * namef8           nom r�duit � 7 caract�res + premi�re lettre du pr�nom
 * 
 * si $_mode est NULL, fname8 est utilis�
 * 
 * @param string $_nom nom de l'utilisateur
 * @param string $_prenom pr�nom de l'utilisateur
 * @param string $_mode Le mode de g�n�ration ou NULL
 * @return string|booleanLe login g�n�r� ou FALSE si on obtient un login vide
 * @see test_unique_login()
 */
function generate_unique_login($_nom, $_prenom, $_mode) {

	if ($_mode == NULL) {
		$_mode = "fname8";
	}
    // On g�n�re le login
	$_prenom = strtr($_prenom, "������������������������", "ceeeeEEEEuuuUUiiIIaaaAAA");
    $_prenom = preg_replace("/[^a-zA-Z.\-]/", "", $_prenom);
	$_nom = strtr($_nom, "������������������������", "ceeeeEEEEuuuUUiiIIaaaAAA");
    $_nom = preg_replace("/[^a-zA-Z.\-]/", "", $_nom);

	if($_nom=='') {return FALSE;}

    if ($_mode == "name") {
            $temp1 = $_nom;
            $temp1 = preg_replace("/ /","", $temp1);
            $temp1 = preg_replace("/-/","_", $temp1);
            $temp1 = preg_replace("/'/","", $temp1);
        } elseif ($_mode == "name8") {
            $temp1 = $_nom;
            $temp1 = preg_replace("/ /","", $temp1);
            $temp1 = preg_replace("/-/","_", $temp1);
            $temp1 = preg_replace("/'/","", $temp1);
            $temp1 = substr($temp1,0,8);
        } elseif ($_mode == "fname8") {
			if($_prenom=='') {return FALSE;}
            $temp1 = $_prenom{0} . $_nom;
            $temp1 = preg_replace("/ /","", $temp1);
            $temp1 = preg_replace("/-/","_", $temp1);
            $temp1 = preg_replace("/'/","", $temp1);
            $temp1 = substr($temp1,0,8);
        } elseif ($_mode == "fname19") {
			if($_prenom=='') {return FALSE;}
            $temp1 = $_prenom{0} . $_nom;
            $temp1 = preg_replace("/ /","", $temp1);
            $temp1 = preg_replace("/-/","_", $temp1);
            $temp1 = preg_replace("/'/","", $temp1);
            $temp1 = substr($temp1,0,19);
        } elseif ($_mode == "firstdotname") {
			if($_prenom=='') {return FALSE;}
            $temp1 = $_prenom . "." . $_nom;

            $temp1 = preg_replace("/ /","", $temp1);
            $temp1 = preg_replace("/-/","_", $temp1);
            $temp1 = preg_replace("/'/","", $temp1);
        } elseif ($_mode == "firstdotname19") {
			if($_prenom=='') {return FALSE;}
            $temp1 = $_prenom . "." . $_nom;
            $temp1 = preg_replace("/ /","", $temp1);
            $temp1 = preg_replace("/'/","", $temp1);
            $temp1 = substr($temp1,0,19);
        } elseif ($_mode == "namef8") {
			if($_prenom=='') {return FALSE;}
            $temp1 =  substr($_nom,0,7) . $_prenom{0};
            $temp1 = preg_replace("/ /","", $temp1);
            $temp1 = preg_replace("/-/","_", $temp1);
            $temp1 = preg_replace("/'/","", $temp1);
        } else {
        	return FALSE;
        }

        $login_user = $temp1;

        // Nettoyage final
        $login_user = substr($login_user, 0, 50);
        $login_user = preg_replace("/[^A-Za-z0-9._\-]/","",trim($login_user));

        $test1 = $login_user{0};
		while ($test1 == "_" OR $test1 == "-" OR $test1 == ".") {
			$login_user = substr($login_user, 1);
			$test1 = $login_user{0};
		}

		$test1 = $login_user{strlen($login_user)-1};
		while ($test1 == "_" OR $test1 == "-" OR $test1 == ".") {
			$login_user = substr($login_user, 0, strlen($login_user)-1);
			$test1 = $login_user{strlen($login_user)-1};
		}

        // On teste l'unicit� du login que l'on vient de cr�er
        $m = '';
        $test_unicite = 'no';
        while ($test_unicite != 'yes') {
            $test_unicite = test_unique_login($login_user.$m);
            if ($test_unicite != 'yes') {
            	if ($m == '') {
            		$m = 2;
            	} else {
                	$m++;
            	}
            } else {
            	$login_user = $login_user.$m;
            }
        }

		return $login_user;
}

/**
 * Fonction qui propose l'ordre d'affichage du nom, pr�nom et de la civilit� en fonction des r�glages de la classe de l'�l�ve
 *
 * @param string $login login de l'utilisateur
 * @param integer $id_classe Id de la classe
 * @return string nom, pr�nom, civilit� format�
 */
function affiche_utilisateur($login,$id_classe) {
    $req = mysql_query("select nom, prenom, civilite from utilisateurs where login = '".$login."'");
	$nom = @mysql_result($req, 0, 'nom');
    $prenom = @mysql_result($req, 0, 'prenom');
    $civilite = @mysql_result($req, 0, 'civilite');
    $req_format = mysql_query("select format_nom from classes where id = '".$id_classe."'");
    $format = mysql_result($req_format, 0, 'format_nom');
    $result = "";
    $i='';
    if ((($format == 'ni') OR ($format == 'in') OR ($format == 'cni') OR ($format == 'cin')) AND ($prenom != '')) {
        $temp = explode("-", $prenom);
        $i = substr($temp[0], 0, 1);
        if (isset($temp[1]) and ($temp[1] != '')) $i .= "-".substr($temp[1], 0, 1);
        $i .= ". ";
    }
    switch( $format ) {
    case 'np':
    $result = $nom." ".$prenom;
    break;
    case 'pn':
    $result = $prenom." ".$nom;
    break;
    case 'in':
    $result = $i.$nom;
    break;
    case 'ni':
    $result = $nom." ".$i;
    break;
    case 'cnp':
    if ($civilite != '') $result = $civilite." ";
    $result .= $nom." ".$prenom;
    break;
    case 'cpn':
    if ($civilite != '') $result = $civilite." ";
    $result .= $prenom." ".$nom;
    break;
    case 'cin':
    if ($civilite != '') $result = $civilite." ";
    $result .= $i.$nom;
    break;
    case 'cni':
    if ($civilite != '') $result = $civilite." ";
    $result .= $nom." ".$i;
    break;
    $result = $nom." ".$prenom;

    }
    return $result;
}

/**
 * Verifie si l'extension d_base est active
 *
 * Affiche une page d'avertissement si le module dbase n'est pas actif
 * 
 */
function verif_active_dbase() {
    if (!function_exists("dbase_open"))  {
        echo "<center><p class=grand>ATTENTION : PHP n'est pas configur� pour g�rer les fichiers GEP (dbf).
        <br />L'extension d_base n'est pas active. Adressez-vous � l'administrateur du serveur pour corriger le probl�me.</p></center></body></html>";
        die();
    }
}

/**
 * Ecrit une balise <select> de date jour mois ann�e
 * correction W3C : ajout de la balise de fin </option> � la fin de $out_html
 * Cr�ation d'un label pour passer les tests WAI
 *
 * @param string $prefix l'attribut name sera de la forme $prefixday, $prefixMois,...
 * @param integer $day
 * @param integer $month
 * @param integer $year
 * @param string $option Si = more_years, on ajoute +5 et -5 ann�es aux ann�es possibles
 * @see getSettingValue()
 */
function genDateSelector($prefix, $day, $month, $year, $option)
{
    if($day   == 0) $day = date("d");
    if($month == 0) $month = date("m");
    if($year  == 0) $year = date("Y");

	 echo "\n<label for=\"${prefix}jour\"><span style='display:none;'>Jour</span></label>\n";
    echo "<select id=\"${prefix}jour\" name=\"${prefix}day\">\n";

    for($i = 1; $i <= 31; $i++)
        echo "<option value = \"$i\"" . ($i == $day ? " selected=\"selected\"" : "") . ">$i</option>\n";

    echo "</select>\n";

	 echo "\n<label for=\"${prefix}mois\"><span style='display:none;'>Mois</span></label>\n";
    echo "<select id=\"${prefix}mois\" name=\"${prefix}month\">\n";

    for($i = 1; $i <= 12; $i++)
    {
        $m = strftime("%b", mktime(0, 0, 0, $i, 1, $year));

        echo "<option value=\"$i\"" . ($i == $month ? " selected=\"selected\"" : "") . ">$m</option>\n";
    }

    echo "</select>\n";

	 echo "\n<label for=\"${prefix}annee\"><span style='display:none;'>Ann�e</span></label>\n";
    echo "<select id=\"${prefix}annee\" name=\"${prefix}year\">\n";

    $min = strftime("%Y", getSettingValue("begin_bookings"));
    if ($option == "more_years") $min = date("Y") - 5;

    $max = strftime("%Y", getSettingValue("end_bookings"));
    if ($option == "more_years") $max = date("Y") + 5;

    for($i = $min; $i <= $max; $i++)
        print "<option" . ($i == $year ? " selected=\"selected\"" : "") . ">$i</option>\n";
    
    echo "</select>\n";
}


/**
 * Remplit un fichier de suivi des actions
 * 
 * Passer la variable $local_debug � "y" pour activer le remplissage du fichier "/tmp/calcule_moyenne.txt" de debug
 * 
 * @param string $texte 
 */
function fdebug($texte){
	$local_debug="n";
	if($local_debug=="y") {
		$fich=fopen("/tmp/calcule_moyenne.txt","a+");
		fwrite($fich,$texte);
		fclose($fich);
	}
}


/**
 * V�rifie que la page est bien accessible par l'utilisateur
 *
 * @global string 
 * @return booleanTRUE si la page est accessible, FALSE sinon
 * @see tentative_intrusion()
 */
function checkAccess() {
    global $gepiPath;
    $url = parse_url($_SERVER['REQUEST_URI']);
    if ($_SESSION["statut"] == 'autre') {

    	$sql = "SELECT autorisation
	    from droits_speciaux
    	where nom_fichier = '" . substr($url['path'], strlen($gepiPath)) . "'
		AND id_statut = '" . $_SESSION['statut_special_id'] . "'";

    }else{

		$sql = "select " . $_SESSION['statut'] . "
	    from droits
    	where id = '" . substr($url['path'], strlen($gepiPath)) . "'
    	;";

	}

    $dbCheckAccess = sql_query1($sql);
    if (substr($url['path'], 0, strlen($gepiPath)) != $gepiPath) {
        tentative_intrusion(2, "Tentative d'acc�s avec modification sauvage de gepiPath");
        return (FALSE);
    } else {
        if ($dbCheckAccess == 'V') {
            return (TRUE);
        } else {
            tentative_intrusion(1, "Tentative d'acc�s � un fichier sans avoir les droits n�cessaires");
            return (FALSE);
        }
    }
}


/**
 * V�rifie qu'un enseignant enseigne une mati�re dans une classe
 *
 * @deprecated la table j_classes_matieres_professeurs n'existe plus
 * @param string $login Login de l'enseignant
 * @param int $id_classe Id de la classe
 * @param type $matiere
 * @return boolean
 */
function Verif_prof_classe_matiere ($login,$id_classe,$matiere) {
    if(empty($login) || empty($id_classe) || empty($matiere)) {return FALSE;}
    $call_prof = mysql_query("SELECT id_professeur FROM j_classes_matieres_professeurs WHERE (id_classe='".$id_classe."' AND id_matiere='".$matiere."')");
    $nb_profs = mysql_num_rows($call_prof);
    $k = 0;
    $flag = 0;
    while ($k < $nb_profs) {
        $prof = @mysql_result($call_prof, $k, "id_professeur");
        if (strtolower($login) == strtolower($prof)) {$flag = 1;}
        $k++;
    }
    if ($flag == 0) {
        return FALSE;
    } else {
        return TRUE;
    }
}

/**
 * Recherche dans la base l'adresse courriel d'un utilisateur
 *
 * @param string $login_u Login de l'utilisateur
 * @return string adresse courriel de l'utilisateur
 */
function retourne_email ($login_u) {
$call = mysql_query("SELECT email FROM utilisateurs WHERE login = '$login_u'");
$email = @mysql_result($call, 0, "email");
return $email;

}

/**
 * Renvoie une chaine d�barass�e de l'encodage ASCII
 *
 * @param string $s le texte � convertir
 * @return string le texte avec les lettres accentu�es
 */
function dbase_filter($s){
  for($i = 0; $i < strlen($s); $i++){
    $code = ord($s[$i]);
    switch($code){
    case 129:    $s[$i] = "�"; break;
    case 130:   $s[$i] = "�"; break;
    case 131:    $s[$i] = "�"; break;
    case 132:    $s[$i] = "�"; break;
    case 133:    $s[$i] = "�"; break;
    case 135:    $s[$i] = "�"; break;
    case 136:    $s[$i] = "�"; break;
    case 137:    $s[$i] = "�"; break;
    case 138:    $s[$i] = "�"; break;
    case 139:    $s[$i] = "�"; break;
    case 140:    $s[$i] = "�"; break;
    case 147:    $s[$i] = "�"; break;
    case 148:    $s[$i] = "�"; break;
    case 150:    $s[$i] = "�"; break;
    case 151:    $s[$i] = "�"; break;
    }
  }
  return $s;
}

/**
 * Renvoie le navigateur et sa version
 *
 * @param string $HTTP_USER_AGENT
 * @return string navigateur - version
 */
function detect_browser($HTTP_USER_AGENT) {
	// D'apr�s le fichier db_details_common.php de phpmyadmin
	/*
	$f=fopen("/tmp/detect_browser.txt","a+");
	fwrite($f,date("d/m/Y His").": $HTTP_USER_AGENT\n");
	fclose($f);
	*/
	if(function_exists('preg_match')) {
		if (preg_match('/Opera(\/| )([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[2];
			$BROWSER_AGENT = 'OPERA';
		} elseif(preg_match('/MSIE ([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[1];
			$BROWSER_AGENT = 'Internet Explorer';
		} elseif(preg_match('/OmniWeb\/([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[1];
			$BROWSER_AGENT = 'OMNIWEB';
		} elseif(preg_match('/(Konqueror\/)(.*)(;)/', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[2];
			$BROWSER_AGENT = 'KONQUEROR';
		} elseif(preg_match('/Mozilla\/([0-9].[0-9]{1,2})/', $HTTP_USER_AGENT, $log_version)) {
			if(preg_match('/Chrome\/([0-9.]*)/', $HTTP_USER_AGENT, $log_version2)) {
				$BROWSER_VER = $log_version2[1];
				$BROWSER_AGENT = 'GoogleChrome';
			} elseif(preg_match('/Safari\/([0-9]*)/', $HTTP_USER_AGENT, $log_version2)) {
				$BROWSER_VER = $log_version[1] . '.' . $log_version2[1];
				$BROWSER_AGENT = 'SAFARI';
			} elseif(preg_match('/Firefox\/([0-9.]*)/', $HTTP_USER_AGENT, $log_version2)) {
				$BROWSER_VER = $log_version2[1];
				$BROWSER_AGENT = 'Firefox';
			} else {
				$BROWSER_VER = $log_version[1];
				$BROWSER_AGENT = 'MOZILLA';
			}
		} else {
			$BROWSER_VER = '';
			$BROWSER_AGENT = $HTTP_USER_AGENT;
		}
	}
	elseif(function_exists('mb_ereg')) {
		if (mb_ereg('Opera(/| )([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[2];
			$BROWSER_AGENT = 'OPERA';
		} elseif(mb_ereg('MSIE ([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[1];
			$BROWSER_AGENT = 'Internet Explorer';
		} elseif(mb_ereg('OmniWeb/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[1];
			$BROWSER_AGENT = 'OMNIWEB';
		} elseif(mb_ereg('(Konqueror/)(.*)(;)', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[2];
			$BROWSER_AGENT = 'KONQUEROR';
		} elseif((mb_ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version))&&(mb_ereg('GoogleChrome/([0-9.]*)', $HTTP_USER_AGENT, $log_version2))) {
			$BROWSER_VER = $log_version2[1];
			$BROWSER_AGENT = 'GoogleChrome';
		} elseif((mb_ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version))&&(mb_ereg('Safari/([0-9]*)', $HTTP_USER_AGENT, $log_version2))) {
			$BROWSER_VER = $log_version[1] . '.' . $log_version2[1];
			$BROWSER_AGENT = 'SAFARI';
		} elseif((mb_ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version))&&(mb_ereg('Firefox/([0-9.]*)', $HTTP_USER_AGENT, $log_version2))) {
			$BROWSER_VER = $log_version2[1];
			$BROWSER_AGENT = 'Firefox';
		} elseif(mb_ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[1];
			$BROWSER_AGENT = 'MOZILLA';
		} else {
			$BROWSER_VER = '';
			$BROWSER_AGENT = $HTTP_USER_AGENT;
		}
	}
	elseif(function_exists('ereg')) {
		if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[2];
			$BROWSER_AGENT = 'OPERA';
		} elseif(ereg('MSIE ([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[1];
			$BROWSER_AGENT = 'Internet Explorer';
		} elseif(ereg('OmniWeb/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[1];
			$BROWSER_AGENT = 'OMNIWEB';
		} elseif(ereg('(Konqueror/)(.*)(;)', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[2];
			$BROWSER_AGENT = 'KONQUEROR';
		} elseif((ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version))&&(ereg('GoogleChrome/([0-9.]*)', $HTTP_USER_AGENT, $log_version2))) {
			$BROWSER_VER = $log_version2[1];
			$BROWSER_AGENT = 'GoogleChrome';
		} elseif((ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version))&&(ereg('Safari/([0-9]*)', $HTTP_USER_AGENT, $log_version2))) {
			$BROWSER_VER = $log_version[1] . '.' . $log_version2[1];
			$BROWSER_AGENT = 'SAFARI';
		} elseif(ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
			$BROWSER_VER = $log_version[1];
			$BROWSER_AGENT = 'MOZILLA';
		} elseif((ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version))&&(ereg('Firefox/([0-9.]*)', $HTTP_USER_AGENT, $log_version2))) {
			$BROWSER_VER = $log_version2[1];
			$BROWSER_AGENT = 'Firefox';
		} else {
			$BROWSER_VER = '';
			$BROWSER_AGENT = $HTTP_USER_AGENT;
		}
	}
	else {
		$BROWSER_VER = '';
		$BROWSER_AGENT = $HTTP_USER_AGENT;
	}
	return  $BROWSER_AGENT." - ".$BROWSER_VER;
}

/**
 * Formate une date en jour/mois/ann�e
 * 
 * Accepte les dates aux formats YYYY-MM-DD ou YYYYMMDD ou YYYY-MM-DD xx:xx:xx
 * 
 * Retourne la date pass�e en argument si le format n'est pas bon
 *
 * @param date $date La date � formater
 * @return string la date format�e
 */
function affiche_date_naissance($date) {
    if (strlen($date) == 10) {
        // YYYY-MM-DD
        $annee = substr($date, 0, 4);
        $mois = substr($date, 5, 2);
        $jour = substr($date, 8, 2);
    }
    elseif (strlen($date) == 8 ) {
        // YYYYMMDD
        $annee = substr($date, 0, 4);
        $mois = substr($date, 4, 2);
        $jour = substr($date, 6, 2);
    }
    elseif (strlen($date) == 19 ) {
        // YYYY-MM-DD xx:xx:xx
        $annee = substr($date, 0, 4);
        $mois = substr($date, 5, 2);
        $jour = substr($date, 8, 2);
    }

    else {
        // Format inconnu
        return($date);
    }
    return $jour."/".$mois."/".$annee ;
}

/**
 *
 * @global mixed 
 * @global mixed 
 * @global mixed 
 * @return booleanTRUE si on a une nouvelle version 
 */
function test_maj() {
    global $gepiVersion, $gepiRcVersion, $gepiBetaVersion;
    $version_old = getSettingValue("version");
    $versionRc_old = getSettingValue("versionRc");
    $versionBeta_old = getSettingValue("versionBeta");

   if ($version_old =='') {
       return TRUE;
       die();
   }
   if ($gepiVersion > $version_old) {
        // On a une nouvelle version stable
       return TRUE;
       die();
   }
   if (($gepiVersion == $version_old) and ($versionRc_old!='')) {
        // On avait une RC
       if (($gepiRcVersion > $versionRc_old) or ($gepiRcVersion=='')) {
            // Soit on a une nouvelle RC, soit on est pass� de RC � stable
           return TRUE;
           die();
       }
   }
   if (($gepiVersion == $version_old) and ($versionBeta_old!='')) {
        // On avait une Beta
       if (($gepiBetaVersion > $versionBeta_old) or ($gepiBetaVersion=='')) {
            // Soit on a une nouvelle Beta, soit on est pass� � une RC ou une stable
           return TRUE;
           die();
       }
   }
   return FALSE;
}

/**
 * Recherche si la mise � jour est � faire
 *
 * @global mixed 
 * @global mixed 
 * @global mixed 
 * @param mixed $num le num�ro de version
 * @return booleanTRUE s'il faut faire la mise � jour
 */
function quelle_maj($num) {
    global $gepiVersion, $gepiRcVersion, $gepiBetaVersion;
    $version_old = getSettingValue("version");
    $versionRc_old = getSettingValue("versionRc");
    $versionBeta_old = getSettingValue("versionBeta");
    if ($version_old < $num) {
        return TRUE;
        die();
    }
    if ($version_old == $num) {
        if ($gepiRcVersion > $versionRc_old) {
            return TRUE;
            die();
        }
        if ($gepiRcVersion == $versionRc_old) {
            if ($gepiBetaVersion > $versionBeta_old) {
                return TRUE;
                die();
            }
        }
    }
    return FALSE;
}

/**
 *
 * @global text
 * @return booleanTRUE si tout c'est bien pass� 
 * @see getSettingValue()
 * @see saveSetting()
 */
function check_backup_directory() {

	global $multisite;

    $current_backup_dir = getSettingValue("backup_directory");
    if ($current_backup_dir == NULL) $current_backup_dir = "no_folder";
    if (!file_exists("./backup/".$current_backup_dir)) {
        $backupDirName = NULL;
        if ($multisite != 'y') {
        	// On regarde d'abord si le r�pertoire de backup n'existerait pas d�j�...
        	$handle=opendir('./backup');

        	while ($file = readdir($handle)) {
            	if (strlen($file) > 34 and is_dir('./backup/'.$file)) $backupDirName = $file;
        	}

        	closedir($handle);
        }

        if ($backupDirName != NULL) {
            // Il existe : on met simplement � jour le nom du r�pertoire...
            $update = saveSetting("backup_directory",$backupDirName);
        } else {
            // Il n'existe pas
            // On cr�e le r�pertoire de backup
            $length = rand(35, 45);
            for($len=$length,$r='';strlen($r)<$len;$r.=chr(!mt_rand(0,2)? mt_rand(48,57):(!mt_rand(0,1) ? mt_rand(65,90) : mt_rand(97,122))));
            $dirname = $r;
            $create = mkdir("./backup/" . $dirname, 0700);
            copy("./backup/index.html","./backup/".$dirname."/index.html");
            if ($create) {
                saveSetting("backup_directory", $dirname);
                saveSetting("backupdir_lastchange",time());
            } else {
                return FALSE;
                die();
            }

            // On d�place les �ventuels fichiers .sql dans ce nouveau r�pertoire

            $handle=opendir('./backup');
            $tab_file = array();
            $n=0;
            while ($file = readdir($handle)) {
                if (($file != '.') and ($file != '..') and ($file != 'remove.txt')
                and (preg_match('/sql$/',$file)) and ($file != '.htaccess') and ($file != '.htpasswd') and ($file != 'index.html') ) {
                    $tab_file[] = $file;
                    $n++;
                }
            }
            closedir($handle);
            foreach($tab_file as $filename) {
                rename("backup/".$filename, "backup/".$dirname."/".$filename);
            }
        }
    }

    // On v�rifie la date du dernier changement, et on change le nom
    // du r�pertoire si le dernier changement a eu lieu il y a plus de 48h
    $lastchange = getSettingValue("backupdir_lastchange");
    $current_time = time();

    // Si le dernier changement a eu lieu il y a plus de 48h, on change le nom du r�pertoire
    if ($current_time-$lastchange > 172800) {
        $dirname = getSettingValue("backup_directory");
        $length = rand(35, 45);
        for($len=$length,$r='';strlen($r)<$len;$r.=chr(!mt_rand(0,2) ? mt_rand(48,57):(!mt_rand(0,1)?mt_rand(65,90):mt_rand(97,122))));
        $newdirname = $r;
        if (rename("./backup/".$dirname, "./backup/".$newdirname)) {
            saveSetting("backup_directory",$newdirname);
            saveSetting("backupdir_lastchange",time());
            return TRUE;
        } else {
            echo "Erreur lors du renommage du dossier de sauvegarde.<br />";
            return FALSE;
        }
    }
    return TRUE;

}

/**
 * Fonction qui retourne le nombre de p�riodes pour une classe
 *
 * @param int identifiant num�rique de la classe
 * @return int Nombre de periodes d�finies pour cette classe
 */
function get_period_number($_id_classe) {
    $periode_query = mysql_query("SELECT count(*) FROM periodes WHERE id_classe = '" . $_id_classe . "'");
    $nb_periode = mysql_result($periode_query, 0);
    return $nb_periode;
}

/**
 * Renvoie le num�ro et le nom de la premi�re p�riode active pour une classe
 *
 * @param int $_id_classe identifiant unique de la classe
 * @return array num�ro de la p�riode 'num' et son nom 'nom'
 */
function get_periode_active($_id_classe){
  $periode_query  = mysql_query("SELECT num_periode, nom_periode FROM periodes WHERE id_classe = '" . $_id_classe . "' AND verouiller = 'N'");
  $reponse        = mysql_fetch_array($periode_query);

  return $retour = array('nom' => $reponse["num_periode"], 'nom' => $reponse["nom_periode"]);

}

/**
 *  Equivalent � html_entity_decode()
 * 
 * Pour les utilisateurs ayant des versions ant�rieures � PHP 4.3.0 :
 * la fonction html_entity_decode() est disponible a partir de la version 4.3.0 de php.
 * 
 * @deprecated GEPI ne fonctionne plus sans php 5.2 et plus
 * @param string $string
 * @return type 
 */
function html_entity_decode_all_version ($string)
{
   global $use_function_html_entity_decode;
   if (isset($use_function_html_entity_decode) and ($use_function_html_entity_decode == 0)) {
       // Remplace les entit�s num�riques
       $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
       $string = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $string);
       // Remplace les entit�s lit�rales
       $trans_tbl = get_html_translation_table (HTML_ENTITIES);
       $trans_tbl = array_flip ($trans_tbl);
       return strtr ($string, $trans_tbl);
   } else
       return html_entity_decode($string);
}

/**
 * Cette fonction est � appeler dans tous les cas o� une tentative
 * d'utilisation ill�gale de Gepi est manifestement av�r�e.
 * Elle est � appeler notamment dans tous les tests de s�curit� lorsqu'un test est n�gatif.
 * Possibilit� d'envoyer un mail � l'administrateur et de bloquer l'utilisateur
 *
 * @global string
 * @param integer $_niveau Niveau d'intrusion enregistr�
 * @param string $_description Message enregistr� pour cette tentative
 * @see getSettingValue()
 * @see mail()
 */
function tentative_intrusion($_niveau, $_description) {

	global $gepiPath;

	// On commence par enregistrer la tentative en question

	if (!isset($_SESSION['login'])) {
		// Ici, �a veut dire que l'attaque est ext�rieure. Il n'y a pas d'utilisateur logu�.
		$user_login = "-";
	} else {
		$user_login = $_SESSION['login'];
	}
	$adresse_ip = $_SERVER['REMOTE_ADDR'];
	$date = strftime("%Y-%m-%d %H:%M:%S");
	$url = parse_url($_SERVER['REQUEST_URI']);
    $fichier = substr($url['path'], strlen($gepiPath));
	$res = mysql_query("INSERT INTO tentatives_intrusion SET " .
			"login = '".$user_login."', " .
			"adresse_ip = '".$adresse_ip."', " .
			"date = '".$date."', " .
			"niveau = '".(int)$_niveau."', " .
			"fichier = '".$fichier."', " .
			"description = '".addslashes($_description)."', " .
			"statut = 'new'");

	// On a enregistr�.

	// On initialise des marqueurs pour les deux actions possibles : envoie d'un email � l'admin
	// et blocage du compte de l'utilisateur

	$send_email = FALSE;
	$block_user = FALSE;

	// Est-ce qu'on envoie un mail quoi qu'il arrive ?
	if (getSettingValue("security_alert_email_admin") == "yes" AND $_niveau >= getSettingValue("security_alert_email_min_level")) {
		$send_email = TRUE;
	}

	// Si la tentative d'intrusion a �t� effectu�e par un utilisateur connect� � Gepi,
	// on regarde si des seuils ont �t� d�pass�s et si certaines actions doivent �tre
	// effectu�es.

	if ($user_login != "-") {
		// On r�cup�re quelques infos
		$req = mysql_query("SELECT nom, prenom, statut, niveau_alerte, observation_securite FROM utilisateurs WHERE (login = '".$user_login."')");
		$user = mysql_fetch_object($req);
		// On va utiliser �a pour g�n�rer automatiquement les noms de settings, �a fait du code en moins...
		if ($user->observation_securite == "1") {
			$obs = "probation";
		} else {
			$obs = "normal";
		}

		// D'abord, on met � jour le niveau cumul�
		$nouveau_cumul = (int)$user->niveau_alerte+(int)$_niveau;

		$res = mysql_query("UPDATE utilisateurs SET niveau_alerte = '".$nouveau_cumul ."' WHERE (login = '".$user_login."')");

		$seuil1 = FALSE;
		$seuil2 = FALSE;
		// Maintenant on regarde les seuils.
		if ($nouveau_cumul >= getSettingValue("security_alert1_".$obs."_cumulated_level")
				AND $nouveau_cumul < getSettingValue("security_alert2_".$obs."_cumulated_level")) {
			// Seuil 1
			if (getSettingValue("security_alert1_".$obs."_email_admin") == "yes") $send_email = TRUE;
			if (getSettingValue("security_alert1_".$obs."_block_user") == "yes") $block_user = TRUE;
			$seuil1 = TRUE;

		} elseif ($nouveau_cumul >= getSettingValue("security_alert2_".$obs."_cumulated_level")) {
			// Seuil 2
			if (getSettingValue("security_alert2_".$obs."_email_admin") == "yes") $send_email = TRUE;
			if (getSettingValue("security_alert2_".$obs."_block_user") == "yes") $block_user = TRUE;
			$seuil2 = TRUE;
		}

		// On d�sactive le compte de l'utilisateur si n�cessaire :
		if ($block_user) {
			$res = mysql_query("UPDATE utilisateurs SET etat = 'inactif' WHERE (login = '".$user_login."')");
		}
	} // Fin : if ($user_login != "-")

	// On envoie un email � l'administrateur si n�cessaire
	if ($send_email) {
		$message = "** Alerte automatique s�curit� Gepi **\n\n";
		$message .= "Une nouvelle tentative d'intrusion a �t� d�tect�e par Gepi. Les d�tails suivants ont �t� enregistr�s dans la base de donn�es :\n\n";
		$message .= "Date : ".$date."\n";
		$message .= "Fichier vis� : ".$fichier."\n";
		$message .= "Niveau de gravit� : ".$_niveau."\n";
		$message .= "Description : ".$_description."\n\n";
		if ($user_login == "-") {
			$message .= "La tentative d'intrusion a �t� effectu�e par un utilisateur non connect� � Gepi.\n";
			$message .= "Adresse IP : ".$adresse_ip."\n";
		} else {
			$message .= "Informations sur l'utilisateur :\n";
			$message .= "Login : ".$user_login."\n";
			$message .= "Nom : ".$user->prenom . " ".$user->nom."\n";
			$message .= "Statut : ".$user->statut."\n";
			$message .= "Score cumul� : ".$nouveau_cumul."\n\n";
			if ($seuil1) $message .= "L'utilisateur a d�pass� le seuil d'alerte 1.\n\n";
			if ($seuil2) $message .= "L'utilisateur a d�pass� le seuil d'alerte 2.\n\n";
			if ($block_user) $message .= "Le compte de l'utilisateur a �t� d�sactiv�.\n";
		}

		$gepiPrefixeSujetMail=getSettingValue("gepiPrefixeSujetMail") ? getSettingValue("gepiPrefixeSujetMail") : "";
		if($gepiPrefixeSujetMail!='') {$gepiPrefixeSujetMail.=" ";}

    $subject = $gepiPrefixeSujetMail."GEPI : Alerte s�curit� -- Tentative d'intrusion";
    $subject = "=?ISO-8859-1?B?".base64_encode($subject)."?=\r\n";

    
    $headers = "X-Mailer: PHP/" . phpversion()."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
    $headers .= "From: Mail automatique Gepi <ne-pas-repondre@".$_SERVER['SERVER_NAME'].">\r\n";

		// On envoie le mail
		$envoi = mail(getSettingValue("gepiAdminAdress"),
		    $subject,
		    $message,
        $headers);
	}
}

/**
 * Fonction destin�e � cr�er un dossier temporaire al�atoire /temp/<alea>
 * 
 * Test le dossier en �criture et le cr�e au besoin
 *
 * @return booleanTRUE si tout c'est bien pass�
 * @see getSettingValue()
 * @see saveSetting()
 */
function check_temp_directory(){

	$dirname=getSettingValue("temp_directory");
	if(($dirname=='')||(!file_exists("./temp/$dirname"))){
		// Il n'existe pas
		// On cr�� le r�pertoire temp
		$length = rand(35, 45);
		for($len=$length,$r='';strlen($r)<$len;$r.=chr(!mt_rand(0,2)? mt_rand(48,57):(!mt_rand(0,1) ? mt_rand(65,90) : mt_rand(97,122))));
		$dirname = $r;
		$create = mkdir("./temp/".$dirname, 0700);

		if ($create) {
			$fich=fopen("./temp/".$dirname."/index.html","w+");
			fwrite($fich,'<html><head><script type="text/javascript">
    document.location.replace("../../login.php")
</script></head></html>
');
			fclose($fich);

			saveSetting("temp_directory", $dirname);
			return TRUE;
		} else {
			return FALSE;
			die();
		}
	} else {
		return TRUE;
	}
}

/**
 * Fonction destin�e � cr�er un dossier /temp/<alea> propre au professeur
 * 
 * Test le dossier en �criture et le cr�e au besoin
 *
 * @return booleanTRUE si tout c'est bien pass�
 */
function check_user_temp_directory(){

	$sql="SELECT temp_dir FROM utilisateurs WHERE login='".$_SESSION['login']."'";
	$res_temp_dir=mysql_query($sql);

	if(mysql_num_rows($res_temp_dir)==0){
		// Cela revient � dire que l'utilisateur n'est pas dans la table utilisateurs???
		return FALSE;
	}
	else{
		$lig_temp_dir=mysql_fetch_object($res_temp_dir);
		$dirname=$lig_temp_dir->temp_dir;

		if($dirname==""){
			// Le dossier n'existe pas
			// On cr�� le r�pertoire temp
			$length = rand(35, 45);
			for($len=$length,$r='';strlen($r)<$len;$r.=chr(!mt_rand(0,2)? mt_rand(48,57):(!mt_rand(0,1) ? mt_rand(65,90) : mt_rand(97,122))));
			$dirname = $_SESSION['login']."_".$r;
			$create = mkdir("./temp/".$dirname, 0700);

			if($create){
				$fich=fopen("./temp/".$dirname."/index.html","w+");
				fwrite($fich,'<html><head><script type="text/javascript">
	document.location.replace("../../login.php")
</script></head></html>
');
				fclose($fich);

				$sql="UPDATE utilisateurs SET temp_dir='$dirname' WHERE login='".$_SESSION['login']."'";
				$res_update=mysql_query($sql);
				if($res_update){
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
			else{
				return FALSE;
			}
		}
		else{
			if(!file_exists("./temp/".$dirname)){
				// Le dossier n'existe pas
				// On cr�� le r�pertoire temp
				$create = mkdir("./temp/".$dirname, 0700);

				if($create){
					$fich=fopen("./temp/".$dirname."/index.html","w+");
					fwrite($fich,'<html><head><script type="text/javascript">
	document.location.replace("../../login.php")
</script></head></html>
');
					fclose($fich);
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
			else{
				$fich=fopen("./temp/".$dirname."/test_ecriture.tmp","w+");
				$ecriture=fwrite($fich,'Test d �criture.');
				$fermeture=fclose($fich);
				if(file_exists("./temp/".$dirname."/test_ecriture.tmp")){
					unlink("./temp/".$dirname."/test_ecriture.tmp");
				}

				if(($fich)&&($ecriture)&&($fermeture)){
					return TRUE;
				}
				else{
					return FALSE;
				}
			}
		}
	}
}

/**
 * Renvoie le nom du r�pertoire temporaire de l'utilisateur
 *
 * @return bool|string retourne FALSE s'il n'existe pas et le nom du r�pertoire s'il existe, sans le chemin
 */
function get_user_temp_directory(){
	$sql="SELECT temp_dir FROM utilisateurs WHERE login='".$_SESSION['login']."'";
	$res_temp_dir=mysql_query($sql);
	if(mysql_num_rows($res_temp_dir)>0){
		$lig_temp_dir=mysql_fetch_object($res_temp_dir);
		$dirname=$lig_temp_dir->temp_dir;

		if(($dirname!="")&&(strlen(preg_replace("/[A-Za-z0-9_.]/","",$dirname))==0)) {
			if(file_exists("temp/".$dirname)){
				return $dirname;
			}
			else if(file_exists("../temp/".$dirname)) {
				return $dirname;
			}
			else if(file_exists("../../temp/".$dirname)) {
				return $dirname;
			}
			else{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}
	else{
		return FALSE;
	}
}

/**
 * Retourne un nombre format� en Mo, ko ou o suivant �a taille
 *
 * @param int $volume le nombre � formater
 * @return string le nombre format�
 */
function volume_human($volume){
	if($volume>=1048576){
		$volume=round(10*$volume/1048576)/10;
		return $volume." Mo";
	}
	elseif($volume>=1024){
		$volume=round(10*$volume/1024)/10;
		return $volume." ko";
	}
	else{
		return $volume." o";
	}
}

/**
 * Renvoie la taille d'un r�pertoire
 *
 * @global int 
 * @param string $dir Le r�pertoire � tester
 * @return string la taille format�e 
 * @see volume_dir()
 * @see volume_human()
 */
function volume_dir_human($dir){
	global $totalsize;
	$totalsize=0;

	$volume=volume_dir($dir);
	return volume_human($volume);
}

/**
 * Additionne la taille des r�pertoires et sous-r�pertoires
 *
 * @global int
 * @param string $dir r�pertoire � parser
 * @return int la taille totale du r�pertoire
 */
function volume_dir($dir){
	global $totalsize;

	$handle = @opendir($dir);
	while ($file = @readdir ($handle)){
		if (preg_match("/^\.{1,2}$/i",$file))
			continue;
		if(is_dir("$dir/$file")){
			$totalsize+=volume_dir("$dir/$file");
		}
		else{
			$tabtmpsize=stat("$dir/$file");
			$size=$tabtmpsize[7];

			$totalsize+=$size;
		}
	}
	@closedir($handle);

	return($totalsize);
}

/**
 * Supprime les fichiers d'un dossier
 *
 * @param string $dir le r�pertoire � vider
 * @return boolean TRUE si tout c'est bien pass�
 * @todo En ajoutant un param�tre � la fonction, on pourrait activer la suppression r�cursive (avec une profondeur par exemple)
 */
function vider_dir($dir){
	$statut=TRUE;
	$handle = @opendir($dir);
	while ($file = @readdir ($handle)){
		if (preg_match("/^\.{1,2}$/i",$file)){
			continue;
		}
		if(is_dir("$dir/$file")){
			// On ne cherche pas � vider r�cursivement.
			$statut=FALSE;

			echo "<!-- DOSSIER: $dir/$file -->\n";
			// En ajoutant un param�tre � la fonction, on pourrait activer la suppression r�cursive (avec une profondeur par exemple) lancer ici vider_dir("$dir/$file");
		}
		else{
			if(!unlink($dir."/".$file)) {
				$statut=FALSE;
				echo "<!-- Echec suppression: $dir/$file -->\n";
				break;
			}
		}
	}
	@closedir($handle);

	return $statut;
}


/**
 * Cette m�thode prend une cha�ne de caract�res et s'assure qu'elle est bien
 * retourn�e en ISO-8859-1.
 * 
 * @param string La chaine � tester
 * @return string La chaine trait�e
 * @todo On pourrait au moins passer en ISO-8859-15
 */
function ensure_iso8859_1($str) {
	$encoding = mb_detect_encoding($str);
	if ($encoding == 'ISO-8859-1') {
		return $str;
	} else {
		return mb_convert_encoding($str, 'ISO-8859-1');
	}
}

/**
 * Encode une chaine en utf8
 * 
 * @param string $chaine La chaine � tester
 * @return string La chaine trait�e
 */
function caract_ooo($chaine){
	if(function_exists('utf8_encode')){
		$retour=utf8_encode($chaine);
	}
	else{
		$caract_accent=array("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�");
		$caract_utf8=array("À","� ","Â","â","Ä","ä","É","é","è","Ê","ê","Ë","ë","Î","î","Ï","ï","Ô","ô","Ö","ö","Ù","ù","Û","û","Ü","ü","u");

		$retour=$chaine;
		for($i=0;$i<count($caract_accent);$i++){
			$retour=str_replace($caract_accent[$i],$caract_utf8[$i],$retour);
		}
	}

	$caract_special=array("&",
							'"',
							"'",
							"<",
							">");

	$caract_sp_encode=array("&amp;",
							"&quot;",
							"&apos;",
							"&lt;",
							"&gt;");

	for($i=0;$i<count($caract_special);$i++){
		$retour=str_replace($caract_special[$i],$caract_sp_encode[$i],$retour);
	}

	return $retour;
}

/**
 * Correspondances de caract�res accentu�s/d�saccentu�s
 * 
 * @global string $GLOBALS['liste_caracteres_accentues']
 * @name $liste_caracteres_accentues
 */
$GLOBALS['liste_caracteres_accentues']="���������������������ئ����ݾ��������������������������������";

/**
 * Correspondances de caract�res accentu�s/d�saccentu�s
 * 
 * @global string $GLOBALS['liste_caracteres_desaccentues']
 * @name $liste_caracteres_desaccentues
 */
$GLOBALS['liste_caracteres_desaccentues']="AAAAAACEEEEIIIINOOOOOOSUUUUYYZaaaaaaceeeeiiiinooooooosuuuuyyz";

/**
 * Remplace les accents dans une chaine
 * 
 * $mode = 'all' On remplace espaces et apostrophes par des '_' et les caract�res accentu�s par leurs �quivalents non accentu�s.
 * 
 * $mode = 'all_nospace' On remplace apostrophes par des '_' et les caract�res accentu�s par leurs �quivalents non accentu�s.
 * 
 *  Sinon, on remplace les caract�res accentu�s par leurs �quivalents non accentu�s.
 *
 * @global string 
 * @global string 
 * @param type $chaine La chaine � tester
 * @param type $mode Mode de conversion
 * @return type 
 */
function remplace_accents($chaine,$mode=''){
	global $liste_caracteres_accentues, $liste_caracteres_desaccentues;

	if($mode == 'all'){
		// On remplace espaces et apostrophes par des '_' et les caract�res accentu�s par leurs �quivalents non accentu�s.
		$retour=strtr(preg_replace("/�/","AE",preg_replace("/�/","ae",preg_replace("/�/","OE",preg_replace("/�/","oe","$chaine"))))," '$liste_caracteres_accentues","__$liste_caracteres_desaccentues");
	}
	elseif($mode == 'all_nospace'){
		// On remplace apostrophes par des '_' et les caract�res accentu�s par leurs �quivalents non accentu�s.
		$retour1=strtr(preg_replace("/�/","AE",preg_replace("/�/","ae",preg_replace("/�/","OE",preg_replace("/�/","oe","$chaine")))),"'$liste_caracteres_accentues"," $liste_caracteres_desaccentues");
		// On enl�ve aussi les guillemets
		$retour = preg_replace('/"/', '', $retour1);
	}
	else {
		// On remplace les caract�res accentu�s par leurs �quivalents non accentu�s.
		$retour=strtr(preg_replace("/�/","AE",preg_replace("/�/","ae",preg_replace("/�/","OE",preg_replace("/�/","oe","$chaine")))),"$liste_caracteres_accentues","$liste_caracteres_desaccentues");
	}
	return $retour;
}

/**
 * Fonction qui renvoie le login d'un �l�ve en �change de son ele_id
 *
 * @param int $id_eleve ele_id de l'�l�ve
 * @return string login de l'�l�ve
 */
function get_login_eleve($id_eleve){

	$sql = "SELECT login FROM eleves WHERE id_eleve = '".$id_eleve."'";
	$query = mysql_query($sql) OR trigger_error('Impossible de r�cup�rer le login de cet �l�ve.', E_USER_ERROR);
	if ($query) {
		$retour = mysql_result($query, 0,"login");
	}else{
		$retour = 'erreur';
	}
	return $retour;

}

/**
 * fonction qui renvoie le nom de la classe d'un �l�ve pour chaque p�riode
 *
 * @param string $ele_login login de l'�l�ve
 * @return array Tableau des classes en fonction des p�riodes
 */
function get_class_from_ele_login($ele_login){
	$sql="SELECT DISTINCT jec.id_classe, c.classe FROM j_eleves_classes jec, classes c WHERE jec.id_classe=c.id AND jec.login='$ele_login' ORDER BY periode,classe;";
	$res_class=mysql_query($sql);
	$a = 0;
	$tab_classe=array();
	if(mysql_num_rows($res_class)>0){
		$tab_classe['liste'] = "";
		$tab_classe['liste_nbsp'] = "";
		while($lig_tmp=mysql_fetch_object($res_class)){

			$tab_classe[$lig_tmp->id_classe]=$lig_tmp->classe;

			if($a>0) {$tab_classe['liste'].=", ";}
			$tab_classe['liste'].=$lig_tmp->classe;

			if($a>0) {$tab_classe['liste_nbsp'].=", ";}
			$tab_classe['liste_nbsp'].=preg_replace("/ /","&nbsp;",$lig_tmp->classe);

			$tab_classe['id'.$a] = $lig_tmp->id_classe;
			$a = $a++;
		}
	}
	return $tab_classe;
}

/**
 * Retourne les classes d'un �l�ve ordonn�es par p�riodes puis classes
 *
 * @param string $ele_login Login de l'�l�ve
 * @return array 
 */
function get_noms_classes_from_ele_login($ele_login){
	$sql="SELECT DISTINCT jec.id_classe, c.classe FROM j_eleves_classes jec, classes c WHERE jec.id_classe=c.id AND jec.login='$ele_login' ORDER BY periode,classe;";
	$res_class=mysql_query($sql);

	$tab_classe=array();
	if(mysql_num_rows($res_class)>0){
		while($lig_tmp=mysql_fetch_object($res_class)){
			$tab_classe[]=$lig_tmp->classe;
		}
	}
	return $tab_classe;
}

/**
 * Renvoie les �l�ves li�s � un responsable
 *
 * @param string $resp_login Login du responsable
 * @param string $mode Si avec_classe renvoie aussi la classe
 * @return array 
 * @see get_class_from_ele_login()
 */
function get_enfants_from_resp_login($resp_login,$mode='simple'){
	$sql="SELECT e.nom,e.prenom,e.login FROM eleves e,
											responsables2 r,
											resp_pers rp
										WHERE e.ele_id=r.ele_id AND
											rp.pers_id=r.pers_id AND
											rp.login='$resp_login' AND
											(r.resp_legal='1' OR r.resp_legal='2')
										ORDER BY e.nom,e.prenom;";
	$res_ele=mysql_query($sql);

	$tab_ele=array();
	if(mysql_num_rows($res_ele)>0){
		while($lig_tmp=mysql_fetch_object($res_ele)){
			$tab_ele[]=$lig_tmp->login;
			if($mode=='avec_classe') {
				$tmp_chaine_classes="";

				$tmp_tab_clas=get_class_from_ele_login($lig_tmp->login);
				if(isset($tmp_tab_clas['liste'])) {
					$tmp_chaine_classes=" (".$tmp_tab_clas['liste'].")";
				}

				$tab_ele[]=ucfirst(strtolower($lig_tmp->prenom))." ".strtoupper($lig_tmp->nom).$tmp_chaine_classes;
			}
			else {
				$tab_ele[]=ucfirst(strtolower($lig_tmp->prenom))." ".strtoupper($lig_tmp->nom);
			}
		}
	}
	return $tab_ele;
}

/**
 * Renvoie le statut avec des accents
 *
 * @param string $user_statut Statut � corriger
 * @return string Le statut corrig�
 */
function statut_accentue($user_statut){
	switch($user_statut){
		case "administrateur":
			$chaine="administrateur";
			break;
		case "scolarite":
			$chaine="scolarit�";
			break;
		case "professeur":
			$chaine="professeur";
			break;
		case "secours":
			$chaine="secours";
			break;
		case "cpe":
			$chaine="cpe";
			break;
		case "eleve":
			$chaine="�l�ve";
			break;
		case "responsable":
			$chaine="responsable";
			break;
		default:
			$chaine="statut inconnu";
			break;
	}
	return $chaine;
}

/**
 * Renvoie le nom d'une classe � partir de son Id
 * 
 * Renvoie classes.classe
 *
 * @param type $id_classe Id de la classe
 * @return string|bool Le nom d'une classe ou FALSE
 */
function get_nom_classe($id_classe){
	$sql="SELECT classe FROM classes WHERE id='$id_classe';";
	$res_class=mysql_query($sql);

	if(mysql_num_rows($res_class)>0){
		$lig_tmp=mysql_fetch_object($res_class);
		$classe=$lig_tmp->classe;
		return $classe;
	}
	else{
		return FALSE;
	}
}

/**
 * Formate une date au format jj/mm/aa
 *
 * @param string $date
 * @return string La date format�e
 */
function formate_date($date){
	$tmp_date=explode(" ",$date);
	$tab_date=explode("-",$tmp_date[0]);

	return sprintf("%02d",$tab_date[2])."/".sprintf("%02d",$tab_date[1])."/".$tab_date[0];
}

/**
 * Convertit les codes r�gimes de Sconet
 *
 * @param int $code_regime Le code Sconet
 * @return string Le r�gime dans G�pi
 */
function traite_regime_sconet($code_regime){
	$premier_caractere_code_regime=substr($code_regime,0,1);
	switch($premier_caractere_code_regime){
		case "0":
			// 0       EXTERN  EXTERNE LIBRE
			return "ext.";
			break;
		case "1":
			// 1       EX.SUR  EXTERNE SURVEILLE
			return "ext.";
			break;
		case "2":
			/*
			2       DP DAN  DEMI-PENSIONNAIRE DANS L'ETABLISSEMENT
			21      DP DAN  DEMI-PENSIONNAIRE DANS L'ETABLISSEMENT 1
			22      DP DAN  DEMI-PENSIONNAIRE DANS L'ETABLISSEMENT 2
			23      DP DAN  DEMI-PENSIONNAIRE DANS L'ETABLISSEMENT 3
			24      DP DAN  DEMI-PENSIONNAIRE DANS L'ETABLISSEMENT 4
			25      DP DAN  DEMI-PENSIONNAIRE DANS L'ETABLISSEMENT 5
			26      DP DAN  DEMI-PENSIONNAIRE DANS L'ETABLISSEMENT 6
			29      AU TIC  DEMI-PENSIONNAIRE AU TICKET
			*/
			return "d/p";
			break;
		case "3":
			/*
			3       INTERN  INTERNE DANS L'ETABLISSEMENT
			31      INT 1J  INTERNE 1 JOUR
			32      INT 2J  INTERNE 2 JOURS
			33      INT 3J  INTERNE 3 JOURS
			34      INT 4J  INTERNE 4 JOURS
			35      INT 5J  INTERNE 5 JOURS
			36      INT 6J  INTERNE 6 JOURS
			38      1/2 IN  DEMI INTERNE
			39      INT WE  INTERNE WEEK END
			*/
			return "int.";
			break;
		case "4":
			// 4       IN.EX.  INTERNE EXTERNE
			return "i-e";
			break;
		case "5":
			// 5       IN.HEB  INTERNE HEBERGE
			return "int.";
			break;
		case "6":
			// 6       DP HOR  DEMI-PENSIONNAIRE HORS L'ETABLISSEMENT
			return "d/p";
			break;
		default:
			return "ERR";
			//return "d/p";
			break;
	}
}

/**
 * Renvoie les pr�f�rences d'un utilisateur pour un item en interrogeant la table preferences
 *
 * @param string $login Login de l'utilisateur
 * @param string $item Item recherch�
 * @param string $default Valeur par d�faut
 * @return string La valeur de l'item
 */
function getPref($login,$item,$default){
	$sql="SELECT value FROM preferences WHERE login='$login' AND name='$item'";
	$res_prefs=mysql_query($sql);

	if(mysql_num_rows($res_prefs)>0){
		$ligne=mysql_fetch_object($res_prefs);
		return $ligne->value;
	}
	else{
		return $default;
	}
}

/**
 * Enregistre les pr�f�rences d'un utilisateur pour un item dans la table preferences
 *
 * @param string $login Login de l'utilisateur
 * @param string $item Item recherch�
 * @param string $valeur Valeur � enregistrer
 * @return boolean TRUE si tout c'est bien pass�
 */
function savePref($login,$item,$valeur){
	$sql="SELECT value FROM preferences WHERE login='$login' AND name='$item'";
	$res_prefs=mysql_query($sql);

	if(mysql_num_rows($res_prefs)>0){
		$sql="UPDATE preferences SET value='$valeur' WHERE login='$login' AND name='$item';";
	}
	else{
		$sql="INSERT INTO preferences SET login='$login', name='$item', value='$valeur';";
	}
	$res=mysql_query($sql);
	if($res) {return TRUE;} else {return FALSE;}
}

/**
 * Position horizontale initiale pour permettre un affichage sans superposition
 *
 * @global int $GLOBALS['$posDiv_infobulle']
 * @name $posDiv_infobulle
 */
$GLOBALS['$posDiv_infobulle'] = 0;

/**
 * 
 * @global array $GLOBALS['tabid_infobulle']
 * @name $tabid_infobulle
 */
$GLOBALS['tabid_infobulle'] = array();

/**
 * 
 * @global string $GLOBALS['unite_div_infobulle']
 * @name $unite_div_infobulle
 */
$GLOBALS['unite_div_infobulle'] = '';

/**
 * Les infobulles ne sont pas d�call�es si � oui
 * 
 * @global string $GLOBALS['pas_de_decalage_infobulle']
 * @name $pas_de_decalage_infobulle
 */
$GLOBALS['pas_de_decalage_infobulle'] = '';

/**
 * Ajoute un argument aux classes du div
 * 
 * @global string $GLOBALS['class_special_infobulle']
 * @name $class_special_infobulle
 */
$GLOBALS['class_special_infobulle'] = '';

/**
 * $bg_titre: Si $bg_titre est vide, on utilise la couleur par d�faut correspondant � .infobulle_entete (d�fini dans style.css et �ventuellement modifi� dans style_screen_ajout.css)
 * 
 * $bg_texte: Si $bg_texte est vide, on utilise la couleur par d�faut correspondant � .infobulle_corps (d�fini dans style.css et �ventuellement modifi� dans style_screen_ajout.css)
 * 
 * $hauteur: En mettant 0, on laisse le DIV s'adapter au contenu (se r�duire/s'ajuster)
 * 
 * $bouton_close: S'il est affich�, c'est dans la barre de titre. Si la barre de titre n'est pas affich�e, ce bouton ne peut pas �tre affich�.
		
 * 
 * @global type 
 * @global array 
 * @global type 
 * @global type 
 * @global type 
 * @global type 
 * @param string $id Identifiant du DIV conteneur
 * @param string $titre Texte du titre du DIV
 * @param string $bg_titre Couleur de fond de la barre de titre.
 * @param string $texte Texte du contenu du DIV
 * @param string $bg_texte Couleur de fond du DIV contenant le texte
 * @param int $largeur Largeur du DIV conteneur
 * @param int $hauteur Hauteur (minimale) du DIV conteneur
 * @param string $drag 'y' ou 'n' pour rendre le DIV draggable
 * @param string $bouton_close 'y' ou 'n' pour afficher le bouton Close
 * @param string $survol_close 'y' ou 'n' pour refermer le DIV automatiquement lorsque le survol quitte le DIV
 * @param string $overflow 'y' ou 'n' activer l'overflow automatique sur la partie Texte. Il faut que $hauteur soit non NULLe
 * @param int $zindex_infobulle 
 * @return string 
 */
function creer_div_infobulle($id,$titre,$bg_titre,$texte,$bg_texte,$largeur,$hauteur,$drag,$bouton_close,$survol_close,$overflow,$zindex_infobulle=1){
	/*	
		
		$overflow:		
	*/
	global $posDiv_infobulle;
	global $tabid_infobulle;
	global $unite_div_infobulle;
	global $niveau_arbo;
	global $pas_de_decalage_infobulle;
	global $class_special_infobulle;

	$style_box="color: #000000; border: 1px solid #000000; padding: 0px; position: absolute; z-index:$zindex_infobulle;";
	
	$style_bar="color: #ffffff; cursor: move; font-weight: bold; padding: 0px;";
	$style_close="color: #ffffff; cursor: move; font-weight: bold; float:right; width: 16px; margin-right: 1px;";

	// On fait la liste des identifiants de DIV pour cacher les Div avec javascript en fin de chargement de la page (dans /lib/footer.inc.php).
	$tabid_infobulle[]=$id;

	// Conteneur:
	if($bg_texte==''){
		$div="<div id='$id' class='infobulle_corps";
		if((isset($class_special_infobulle))&&($class_special_infobulle!='')) {$div.=" ".$class_special_infobulle;}
		$div.="' style='$style_box width: ".$largeur.$unite_div_infobulle."; ";
	}
	else{
		$div="<div id='$id' ";
		if((isset($class_special_infobulle))&&($class_special_infobulle!='')) {$div.="class='".$class_special_infobulle."' ";}
		$div.="style='$style_box background-color: $bg_texte; width: ".$largeur.$unite_div_infobulle."; ";
	}
	if($hauteur!=0){
		$div.="height: ".$hauteur.$unite_div_infobulle."; ";
	}
	// Position horizontale initiale pour permettre un affichage sans superposition si Javascript est d�sactiv�:
	$div.="left:".$posDiv_infobulle.$unite_div_infobulle.";";
	$div.="'>\n";


	// Barre de titre:
	// Elle n'est affich�e que si le titre est non vide
	if($titre!=""){
		if($bg_titre==''){
			$div.="<div class='infobulle_entete' style='$style_bar width: ".$largeur.$unite_div_infobulle.";'";
		}
		else{
			$div.="<div style='$style_bar background-color: $bg_titre; width: ".$largeur.$unite_div_infobulle.";'";
		}
		if($drag=="y"){
			// L� on utilise les fonctions de http://www.brainjar.com stock�es dans brainjar_drag.js
			$div.=" onmousedown=\"dragStart(event, '$id')\"";
		}
		$div.=">\n";

		if($bouton_close=="y"){
			$div.="<div style='$style_close'><a href='#' onclick=\"cacher_div('$id');return FALSE;\">";
			if(isset($niveau_arbo)&&$niveau_arbo==0){
				$div.="<img src='./images/icons/close16.png' width='16' height='16' alt='Fermer' />";
			}
			else if(isset($niveau_arbo)&&$niveau_arbo==1) {
				$div.="<img src='../images/icons/close16.png' width='16' height='16' alt='Fermer' />";
			}
			else if(isset($niveau_arbo)&&$niveau_arbo==2) {
				$div.="<img src='../../images/icons/close16.png' width='16' height='16' alt='Fermer' />";
			}
      else {
				$div.="<img src='../images/icons/close16.png' width='16' height='16' alt='Fermer' />";
      }
			$div.="</a></div>\n";
		}
		$div.="<span style='padding-left: 1px;'>\n";
		$div.=$titre."\n";
		$div.="</span>\n";
		$div.="</div>\n";
	}


	// Partie texte:
	//==================
	// 20110113
	$div.="<div id='".$id."_contenu_corps'";
	//==================
	if($survol_close=="y"){
		// On referme le DIV lorsque la souris quitte la zone de texte.
		$div.=" onmouseout=\"cacher_div('$id');\"";
	}
	$div.=">\n";
	if(($overflow=='y')&&($hauteur!=0)){
		$hauteur_hors_titre=$hauteur-1;
		$div.="<div style='width: ".$largeur.$unite_div_infobulle."; height: ".$hauteur_hors_titre.$unite_div_infobulle."; overflow: auto;'>\n";
		$div.="<div style='padding-left: 1px;'>\n";
		$div.=$texte;
		$div.="</div>\n";
		$div.="</div>\n";
	}
	else{
		$div.="<div style='padding-left: 1px;'>\n";
		$div.=$texte;
		$div.="</div>\n";
	}
	$div.="</div>\n";

	$div.="</div>\n";

	// Les div vont s'afficher c�te � c�te sans superposition en bas de page si JavaScript est d�sactiv�:
	if (isset($pas_de_decalage_infobulle) AND $pas_de_decalage_infobulle == "oui") {
		// on ne d�cale pas les div des infobulles
		$posDiv_infobulle = $posDiv_infobulle;
	}else{
		$posDiv_infobulle = $posDiv_infobulle+$largeur;
	}

	return $div;
}

/**
 * tableau des variables transmises d'une page � l'autre
 * 
 * @global array $GLOBALS['debug_var_count']
 * @name $debug_var_count
 */
$GLOBALS['debug_var_count']=array();

/**
 * indice de la variable transmise
 * 
 * @global int $GLOBALS['cpt_debug_debug_var']
 * @name $cpt_debug_debug_var
 */
$GLOBALS['cpt_debug_debug_var']=0;

/**
 * Affiche les variables transmises d'une page � l'autre: GET, POST, SERVER et SESSION
 *
 * @global array
 * @global int
 */
$debug_var_count=array();
$cpt_debug_debug_var=0;
function debug_var() {
	global $debug_var_count;
	global $cpt_debug_debug_var;

	$debug_var_count['POST']=0;
	$debug_var_count['GET']=0;
	$debug_var_count['SESSION']=0;
	$debug_var_count['SERVER']=0;

	$debug_var_count['COOKIE']=0;

	// Fonction destin�e � afficher les variables transmises d'une page � l'autre: GET, POST et SESSION
	echo "<div style='border: 1px solid black; background-color: white; color: black;'>\n";

	$cpt_debug_debug_var=0;

	echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
	echo "<p><strong>Variables transmises en POST, GET, SESSION,...</strong> (<a href='#ancre_debug_var_$cpt_debug_debug_var' onclick=\"tab_etat_debug_var[$cpt_debug_debug_var]=tab_etat_debug_var[$cpt_debug_debug_var]*(-1);affiche_debug_var('container_debug_var_$cpt_debug_debug_var',tab_etat_debug_var[$cpt_debug_debug_var]);return FALSE;\">*</a>)</p>\n";

	echo "<div id='container_debug_var_$cpt_debug_debug_var'>\n";
	$cpt_debug_debug_var++;

	echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
	echo "<p>Variables envoy�es en POST: ";
	if(count($_POST)==0) {
		echo "aucune";
	}
	else {
		echo "(<a href='#ancre_debug_var_$cpt_debug_debug_var' onclick=\"tab_etat_debug_var[$cpt_debug_debug_var]=tab_etat_debug_var[$cpt_debug_debug_var]*(-1);affiche_debug_var('container_debug_var_$cpt_debug_debug_var',tab_etat_debug_var[$cpt_debug_debug_var]);return FALSE;\">*</a>)";
	}
	echo "</p>\n";
	echo "<blockquote>\n";
	echo "<div id='container_debug_var_$cpt_debug_debug_var'>\n";
	$cpt_debug_debug_var++;

	echo "<script type='text/javascript'>
	tab_etat_debug_var=new Array();
	function affiche_debug_var(id,mode) {
		if(document.getElementById(id)) {
			if(mode==1) {
				document.getElementById(id).style.display='';
			}
			else {
				document.getElementById(id).style.display='none';
			}
		}
	}
</script>\n";


/**
 * Affiche un tableau des valeurs de GET, POST, SERVER ou SESSION
 *
 * @global int 
 * @global array 
 * @param type $chaine_tab_niv1
 * @param type $tableau
 * @param type $pref_chaine 
 */
	function tab_debug_var($chaine_tab_niv1,$tableau,$pref_chaine) {
		global $cpt_debug_debug_var;
		global $debug_var_count;

		//$cpt_debug_debug_var++;

		echo " (<a href='#ancre_debug_var_$cpt_debug_debug_var' onclick=\"tab_etat_debug_var[$cpt_debug_debug_var]=tab_etat_debug_var[$cpt_debug_debug_var]*(-1);affiche_debug_var('container_debug_var_$cpt_debug_debug_var',tab_etat_debug_var[$cpt_debug_debug_var]);return FALSE;\">*</a>)\n";

		echo "<table id='container_debug_var_$cpt_debug_debug_var' summary=\"Tableau de debug\">\n";
		foreach($tableau as $post => $val) {
			echo "<tr><td valign='top'>".$pref_chaine."['".$post."']=</td><td>".$val;

			if(is_array($tableau[$post])) {
				$cpt_debug_debug_var++;

				//tab_debug_var($chaine_tab_niv1,$tableau[$post],$pref_chaine.'['.$post.']',$cpt_debug_debug_var);
				tab_debug_var($chaine_tab_niv1,$tableau[$post],$pref_chaine.'['.$post.']');

				$cpt_debug_debug_var++;
			}
			elseif(isset($debug_var_count[$chaine_tab_niv1])) {
				$debug_var_count[$chaine_tab_niv1]++;
			}

			echo "</td></tr>\n";
		}
		echo "</table>\n";
	}


	echo "<table summary=\"Tableau de debug\">\n";
	foreach($_POST as $post => $val) {
		echo "<tr><td valign='top'>\$_POST['".$post."']=</td><td>".$val;

		if(is_array($_POST[$post])) {
			//tab_debug_var('POST',$_POST[$post],'$_POST['.$post.']',$cpt_debug_debug_var);
			echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
			tab_debug_var('POST',$_POST[$post],'$_POST['.$post.']');

			$cpt_debug_debug_var++;
		}
		else {
			$debug_var_count['POST']++;
		}

		echo "</td></tr>\n";
	}
	echo "</table>\n";

	echo "<p>Nombre de valeurs en POST: <b>".$debug_var_count['POST']."</b></p>\n";
	echo "</div>\n";
	echo "</blockquote>\n";


	echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
	echo "<p>Variables envoy�es en GET: ";
	if(count($_GET)==0) {
		echo "aucune";
	}
	else {
		echo "(<a href='#ancre_debug_var_$cpt_debug_debug_var' onclick=\"tab_etat_debug_var[$cpt_debug_debug_var]=tab_etat_debug_var[$cpt_debug_debug_var]*(-1);affiche_debug_var('container_debug_var_$cpt_debug_debug_var',tab_etat_debug_var[$cpt_debug_debug_var]);return FALSE;\">*</a>)";
	}
	echo "</p>\n";
	echo "<blockquote>\n";
	echo "<div id='container_debug_var_$cpt_debug_debug_var'>\n";
	$cpt_debug_debug_var++;
	echo "<table summary=\"Tableau de debug sur GET\">";
	foreach($_GET as $get => $val){
		//echo "\$_GET['".$get."']=".$val."<br />\n";
		//echo "<tr><td>\$_GET['".$get."']=</td><td>".$val."</td></tr>\n";

		echo "<tr><td valign='top'>\$_GET['".$get."']=</td><td>".$val;

		if(is_array($_GET[$get])) {
			//tab_debug_var('GET',$_GET[$get],'$_GET['.$get.']',$cpt_debug_debug_var);
			echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
			tab_debug_var('GET',$_GET[$get],'$_GET['.$get.']');

			$cpt_debug_debug_var++;
		}
		else {
			$debug_var_count['GET']++;
		}

		echo "</td></tr>\n";
	}
	echo "</table>\n";

	echo "<p>Nombre de valeurs en GET: <b>".$debug_var_count['GET']."</b></p>\n";

	echo "</div>\n";
	echo "</blockquote>\n";


	echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
	echo "<p>Variables envoy�es en SESSION: ";
	if(count($_SESSION)==0) {
		echo "aucune";
	}
	else {
		echo "(<a href='#ancre_debug_var_$cpt_debug_debug_var' onclick=\"tab_etat_debug_var[$cpt_debug_debug_var]=tab_etat_debug_var[$cpt_debug_debug_var]*(-1);affiche_debug_var('container_debug_var_$cpt_debug_debug_var',tab_etat_debug_var[$cpt_debug_debug_var]);return FALSE;\">*</a>)";
	}
	echo "</p>\n";
	echo "<blockquote>\n";
	echo "<div id='container_debug_var_$cpt_debug_debug_var'>\n";
	$cpt_debug_debug_var++;
	echo "<table summary=\"Tableau de debug sur SESSION\">";
	foreach($_SESSION as $variable => $val){
		//echo "\$_SESSION['".$variable."']=".$val."<br />\n";
		//echo "<tr><td>\$_SESSION['".$variable."']=</td><td>".$val."</td></tr>\n";

		echo "<tr><td valign='top'>\$_SESSION['".$variable."']=</td><td>".$val;
		if(is_array($_SESSION[$variable])) {
			//tab_debug_var('SESSION',$_SESSION[$variable],'$_SESSION['.$variable.']',$cpt_debug_debug_var);
			echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
			tab_debug_var('SESSION',$_SESSION[$variable],'$_SESSION['.$variable.']');

			$cpt_debug_debug_var++;
		}
		else {
			$debug_var_count['SESSION']++;
		}
		echo "</td></tr>\n";

	}
	echo "</table>\n";

	echo "<p>Nombre de valeurs en SESSION: <b>".$debug_var_count['SESSION']."</b></p>\n";
	echo "</div>\n";
	echo "</blockquote>\n";


	echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
	echo "<p>Variables envoy�es en SERVER: ";
	if(count($_SERVER)==0) {
		echo "aucune";
	}
	else {
		echo "(<a href='#ancre_debug_var_$cpt_debug_debug_var' onclick=\"tab_etat_debug_var[$cpt_debug_debug_var]=tab_etat_debug_var[$cpt_debug_debug_var]*(-1);affiche_debug_var('container_debug_var_$cpt_debug_debug_var',tab_etat_debug_var[$cpt_debug_debug_var]);return FALSE;\">*</a>)";
	}
	echo "</p>\n";
	echo "<blockquote>\n";
	echo "<div id='container_debug_var_$cpt_debug_debug_var'>\n";
	$cpt_debug_debug_var++;
	echo "<table summary=\"Tableau de debug sur SERVER\">";
	foreach($_SERVER as $variable => $valeur){
		//echo "\$_SERVER['".$variable."']=".$valeur."<br />\n";
		echo "<tr><td>\$_SERVER['".$variable."']=</td><td>".$valeur."</td></tr>\n";
	}
	echo "</table>\n";

	echo "<p>Nombre de valeurs en SERVER: <b>".$debug_var_count['SERVER']."</b></p>\n";
	echo "</div>\n";
	echo "</blockquote>\n";


	echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
	echo "<p>Variables envoy�es en FILES: ";
	if((!isset($_FILES))||(count($_FILES)==0)) {
		echo "aucune";
	}
	else {
		echo "(<a href='#ancre_debug_var_$cpt_debug_debug_var' onclick=\"tab_etat_debug_var[$cpt_debug_debug_var]=tab_etat_debug_var[$cpt_debug_debug_var]*(-1);affiche_debug_var('container_debug_var_$cpt_debug_debug_var',tab_etat_debug_var[$cpt_debug_debug_var]);return FALSE;\">*</a>)";
	}
	echo "</p>\n";
	if((isset($_FILES))&&(count($_FILES)>0)) {
		echo "<blockquote>\n";
		echo "<div id='container_debug_var_$cpt_debug_debug_var'>\n";
		$cpt_debug_debug_var++;

		//echo "cpt_debug=$cpt_debug_debug_var<br />";
		echo "<table summary=\"Tableau de debug\">\n";
		foreach($_FILES as $key => $val) {
			echo "<tr><td valign='top'>\$_FILES['".$key."']=</td><td>".$val;
	
			if(is_array($_FILES[$key])) {
				//tab_debug_var('FILES',$_FILES[$key],'$_FILES['.$key.']',$cpt_debug_debug_var);
				echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
				tab_debug_var('FILES',$_FILES[$key],'$_FILES['.$key.']');
	
				$cpt_debug_debug_var++;
			}
	
			echo "</td></tr>\n";
		}
		echo "</table>\n";
	
		echo "<p>Nombre de valeurs en FILES: <b>".$debug_var_count['FILES']."</b></p>\n";
		echo "</div>\n";
		echo "</blockquote>\n";
	}

	echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
	echo "<p>Variables COOKIES: ";
	if(count($_COOKIE)==0) {
		echo "aucune";
	}
	else {
		echo "(<a href='#ancre_debug_var_$cpt_debug_debug_var' onclick=\"tab_etat_debug_var[$cpt_debug_debug_var]=tab_etat_debug_var[$cpt_debug_debug_var]*(-1);affiche_debug_var('container_debug_var_$cpt_debug_debug_var',tab_etat_debug_var[$cpt_debug_debug_var]);return FALSE;\">*</a>)";
	}
	echo "</p>\n";
	echo "<blockquote>\n";
	echo "<div id='container_debug_var_$cpt_debug_debug_var'>\n";
	$cpt_debug_debug_var++;
	//echo "cpt_debug=$cpt_debug_debug_var<br />";
	echo "<table summary=\"Tableau de debug sur COOKIE\">";
	foreach($_COOKIE as $variable => $val){

		echo "<tr><td valign='top'>\$_COOKIE['".$variable."']=</td><td>".$val;

		if(is_array($val)) {
			//tab_debug_var('COOKIE',$_COOKIE[$get],'$_COOKIE['.$get.']',$cpt_debug_debug_var);
			echo "<a name='ancre_debug_var_$cpt_debug_debug_var'></a>\n";
			tab_debug_var('COOKIE',$val,'$_COOKIE['.$variable.']');

			$cpt_debug_debug_var++;
		}
		else {
			$debug_var_count['COOKIE']++;
		}

		echo "</td></tr>\n";
	}
	echo "</table>\n";
	echo "</div>\n";
	echo "</blockquote>\n";


	echo "<script type='text/javascript'>
	// On masque le cadre de debug au chargement:
	//affiche_debug_var('container_debug_var',var_debug_var_etat);

	//for(i=0;i<tab_etat_debug_var.length;i++) {
	for(i=0;i<$cpt_debug_debug_var;i++) {
		if(document.getElementById('container_debug_var_'+i)) {
			affiche_debug_var('container_debug_var_'+i,-1);
		}
		// Variable destin�e � alterner affichage/masquage
		tab_etat_debug_var[i]=-1;
	}
</script>\n";

	echo "</div>\n";
	echo "</div>\n";
}

/**
 *permet de v�rifier si tel statut peut avoir acc�s � l'EdT en fonction des settings de l'admin
 * 
 * @param string $statut Statut test�
 * @return string yes si peut avoir acc�s � l'EdT, no sinon
 */
function param_edt($statut){
		$verif = "";
	if ($statut == "administrateur") {
		$verif = getSettingValue("autorise_edt_admin");
	} elseif ($statut == "professeur" OR $statut == "scolarite" OR $statut == "cpe" OR $statut == "secours" OR $statut == "autre") {
		$verif = getSettingValue("autorise_edt_tous");
	} elseif ($statut = "eleve" OR $statut = "responsable") {
		$verif = getSettingValue("autorise_edt_eleve");
	} else {
		$verif = "";
	}
	// On v�rifie $verif et on renvoie le return
	if ($verif == "y" or $verif == "yes") {
		return "yes";
	} else {
		return "no";
	}
}

/**
 * Renvoie le nom de la photo de l'�l�ve ou du prof
 *
 * Renvoie NULL si :
 *
 * - le module trombinoscope n'est pas activ�
 *
 * - la photo n'existe pas.
 *
 * @param string $_elenoet_ou_login selon les cas, soit l'elenoet de l'�l�ve soit le login du professeur
 * @param string $repertoire "eleves" ou "personnels"
 * @param int $arbo niveau d'aborescence (1 ou 2).
 * @return string Le chemin vers la photo ou NULL
 */
function nom_photo($_elenoet_ou_login,$repertoire="eleves",$arbo=1) {
	if ($arbo==2) {$chemin = "../";} else {$chemin = "";}
	if (($repertoire != "eleves") and ($repertoire != "personnels")) {
		return NULL;
		die();
	}
	if (getSettingValue("active_module_trombinoscopes")!='y') {
		return NULL;
		die();
	}
		$photo=NULL;

	// En multisite, on ajoute le r�pertoire RNE
	if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y') {
		  // On r�cup�re le RNE de l'�tablissement
	  $repertoire2=getSettingValue("gepiSchoolRne")."/";
	}else{
	  $repertoire2="";
	}

	// Cas des �l�ves
	if ($repertoire == "eleves") {
	  
	  if($_elenoet_ou_login!='') {

		// on v�rifie si la photo existe

		if(file_exists($chemin."../photos/".$repertoire2."eleves/".$_elenoet_ou_login.".jpg")) {
			$photo=$chemin."../photos/".$repertoire2."eleves/".$_elenoet_ou_login.".jpg";
		}
		else if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y')
		{
		  // En multisite, on recherche aussi avec les logins
		  if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y') {
			// On r�cup�re le login de l'�l�ve
			$sql = 'SELECT login FROM eleves WHERE elenoet = "'.$_elenoet_ou_login.'"';
			$query = mysql_query($sql);
			$_elenoet_ou_login = mysql_result($query, 0,'login');
		  }

		  if(file_exists($chemin."../photos/".$repertoire2."eleves/$_elenoet_ou_login.jpg")) {
				$photo=$chemin."../photos/".$repertoire2."eleves/$_elenoet_ou_login.jpg";
			}
			else {
				if(file_exists($chemin."../photos/".$repertoire2."eleves/".sprintf("%05d",$_elenoet_ou_login).".jpg")) {
					$photo=$chemin."../photos/".$repertoire2."eleves/".sprintf("%05d",$_elenoet_ou_login).".jpg";
				} else {
					for($i=0;$i<5;$i++){
						if(substr($_elenoet_ou_login,$i,1)=="0"){
							$test_photo=substr($_elenoet_ou_login,$i+1);
							if(($test_photo!='')&&(file_exists($chemin."../photos/".$repertoire2."eleves/".$test_photo.".jpg"))) {
								$photo=$chemin."../photos/".$repertoire2."eleves/".$test_photo.".jpg";
								break;
							}
						}
					}
				}
			}

		}

	  }
	}
	// Cas des non-�l�ves
	else {

		$_elenoet_ou_login = md5(strtolower($_elenoet_ou_login));
			if(file_exists($chemin."../photos/".$repertoire2."personnels/$_elenoet_ou_login.jpg")){
				$photo=$chemin."../photos/".$repertoire2."personnels/$_elenoet_ou_login.jpg";
			} else {
				$photo = NULL;
		}
	}
	return $photo;
}


/**
 * Le message � afficher
 * 
 * @global string $GLOBALS['themessage']
 * @name $themessage
 */
$GLOBALS['themessage'] = '';

/**
 * Affiche un fen�tre de confirmation via javascript
 * 
 * Ajoute un attribut onclick � une balise pour appeler une fonction javascript contenant le message
 *
 * @global string
 * @return  string l'attribut onclick ou vide
 */
function insert_confirm_abandon(){
	global $themessage;

	if(isset($themessage)) {
		if($themessage!="") {
			return " onclick=\"return confirm_abandon(this, change, '$themessage')\" ";
		}
		else{
			return "";
		}
	}
	else{
		return "";
	}
}

/**
 * Largeur maximum d�sir�e
 * 
 * @global int $GLOBALS['photo_largeur_max']
 * @name $photo_largeur_max
 */
$GLOBALS['photo_largeur_max'] = 0;

/**
 * Hauteur maximum d�sir�e;
 * 
 * @global int $GLOBALS['photo_hauteur_max']
 * @name $photo_hauteur_max
 */
$GLOBALS['photo_hauteur_max'] = 0;

/**
 * Redimensionne une image
 *
 * @global int 
 * @global int 
 * @param string $photo l'adresse de la photo
 * @return array Les nouvelles dimensions de l'image (largeur, hauteur)
 */
function redimensionne_image2($photo){
	global $photo_largeur_max, $photo_hauteur_max;

	// prendre les informations sur l'image
	$info_image=getimagesize($photo);
	// largeur et hauteur de l'image d'origine
	$largeur=$info_image[0];
	$hauteur=$info_image[1];

	// calcule le ratio de redimensionnement
	$ratio_l=$largeur/$photo_largeur_max;
	$ratio_h=$hauteur/$photo_hauteur_max;
	$ratio=($ratio_l>$ratio_h)?$ratio_l:$ratio_h;

	// d�finit largeur et hauteur pour la nouvelle image
	$nouvelle_largeur=round($largeur/$ratio);
	$nouvelle_hauteur=round($hauteur/$ratio);

	return array($nouvelle_largeur, $nouvelle_hauteur);
}

/**
 * Enregistre les calculs de moyennes dans un fichier
 * 
 * Passer � 1 la variable $debug pour g�n�rer un fichier de debug...
 *
 * @param string $texte Le calcul � enregistrer
 */
function calc_moy_debug($texte){
	$debug=0;
	if($debug==1){
		$tmp_dir=get_user_temp_directory();
		if((!$tmp_dir)||(!file_exists("../temp/".$tmp_dir))) {$tmp_dir="/tmp";} else {$tmp_dir="../temp/".$tmp_dir;}
		$fich=fopen($tmp_dir."/calc_moy_debug.txt","a+");
		fwrite($fich,$texte);
		fclose($fich);
	}
}

/**
 * Renvoie le nom d'une classe � partir de son Id
 *
 * @param int $id_classe Id de la classe recherch�e
 * @return type nom de la classe (classe.classes)
 */
function get_class_from_id($id_classe) {
	$sql="SELECT classe FROM classes c WHERE id='$id_classe';";
	$res_class=mysql_query($sql);

	if(mysql_num_rows($res_class)>0){
		$lig_tmp=mysql_fetch_object($res_class);
		$classe=$lig_tmp->classe;
		return $classe;
	}
	else{
		return FALSE;
	}
}



/* Gestion des droits d'acc�s � confirm_query.php
*/
function PeutEffectuerActionSuppression($_login,$_action,$_cible1,$_cible2,$_cible3) {
    if ($_SESSION['statut'] == "administrateur") {
        return TRUE;
        die();
    }
    if (getSettingValue("active_mod_gest_aid")=="y") {
      if (($_action=="del_eleve_aid") or ($_action=="del_prof_aid") or ($_action=="del_aid")) {
      // on regarde si l'utilisateur est gestionnaire de l'aid
        $test1 = sql_query1("SELECT count(id_utilisateur) FROM j_aid_utilisateurs_gest WHERE (id_utilisateur = '" . $_login . "' and indice_aid = '".$_cible3."' and id_aid = '".$_cible2."')");
        $test2 = sql_query1("SELECT count(id_utilisateur) FROM j_aidcateg_super_gestionnaires WHERE (id_utilisateur = '" . $_login . "' and indice_aid = '".$_cible3."')");
        $test = max($test1,$test2);
        if ($test >= 1) {
            return TRUE;
        } else {
            return FALSE;
        }
      }
    } else
    return FALSE;
}

/*
function fdebug_mail_connexion($texte){
	// Passer la variable � "y" pour activer le remplissage du fichier de debug pour calcule_moyenne()
	$local_debug="n";
	if($local_debug=="y") {
		$fich=fopen("/tmp/mail_connexion.txt","a+");
		fwrite($fich,$texte);
		fclose($fich);
	}
}
*/


/**
 * 
 * 
 * @global string $GLOBALS['active_hostbyaddr']
 * @name  $active_hostbyaddr
 */
$GLOBALS['active_hostbyaddr'] = '';

/**
 *
 * @global string  
 */
function mail_connexion() {
	global $active_hostbyaddr;

	$test_envoi_mail=getSettingValue("envoi_mail_connexion");

	//$date = strftime("%Y-%m-%d %H:%M:%S");
	//$date = ucfirst(strftime("%A %d-%m-%Y � %H:%M:%S"));
	//fdebug_mail_connexion("\$_SESSION['login']=".$_SESSION['login']."\n\$test_envoi_mail=$test_envoi_mail\n\$date=$date\n====================\n");

	if($test_envoi_mail=="y") {
		$user_login = $_SESSION['login'];

		$sql="SELECT nom,prenom,email FROM utilisateurs WHERE login='$user_login';";
		$res_user=mysql_query($sql);
		if (mysql_num_rows($res_user)>0) {
			$lig_user=mysql_fetch_object($res_user);

			$adresse_ip = $_SERVER['REMOTE_ADDR'];
			$date = ucfirst(strftime("%A %d-%m-%Y � %H:%M:%S"));

			if (!(isset($active_hostbyaddr)) or ($active_hostbyaddr == "all")) {
				$result_hostbyaddr = " - ".@gethostbyaddr($adresse_ip);
			}
			else if($active_hostbyaddr == "no_local") {
				if ((substr($adresse_ip,0,3) == 127) or (substr($adresse_ip,0,3) == 10.) or (substr($adresse_ip,0,7) == 192.168)) {
					$result_hostbyaddr = "";
				}
				else{
					$tabip=explode(".",$adresse_ip);
					if(($tabip[0]==172)&&($tabip[1]>=16)&&($tabip[1]<=31)) {
						$result_hostbyaddr = "";
					}
					else{
						$result_hostbyaddr = " - ".@gethostbyaddr($adresse_ip);
					}
				}
			}
			else{
				$result_hostbyaddr = "";
			}


			$message = "** Mail connexion Gepi **\n\n";
			$message .= "\n";
			$message .= "Vous (*) vous �tes connect� � GEPI :\n\n";
			$message .= "Identit�                : ".strtoupper($lig_user->nom)." ".ucfirst(strtolower($lig_user->prenom))."\n";
			$message .= "Login                   : ".$user_login."\n";
			$message .= "Date                    : ".$date."\n";
			$message .= "Origine de la connexion : ".$adresse_ip."\n";
			if($result_hostbyaddr!="") {
				$message .= "Adresse IP r�solue en   : ".$result_hostbyaddr."\n";
			}
			$message .= "\n";
			$message .= "Ce message, s'il vous parvient alors que vous ne vous �tes pas connect� � la date/heure indiqu�e, est susceptible d'indiquer que votre identit� a pu �tre usurp�e.\nVous devriez contr�ler vos donn�es, changer votre mot de passe et avertir l'administrateur (et/ou l'administration de l'�tablissement) pour qu'il puisse prendre les mesures appropri�es.\n";
			$message .= "\n";
			$message .= "(*) Vous ou une personne tentant d'usurper votre identit�.\n";

			// On envoie le mail
			//fdebug_mail_connexion("\$message=$message\n====================\n");
			$destinataire=$lig_user->email;
			$sujet="GEPI : Connexion $date";
			envoi_mail($sujet, $message, $destinataire);
		}
	}
}



function mail_alerte($sujet,$texte,$informer_admin='n') {
	global $active_hostbyaddr;

	$user_login = $_SESSION['login'];

	$sql="SELECT nom,prenom,email FROM utilisateurs WHERE login='$user_login';";
	$res_user=mysql_query($sql);
	if (mysql_num_rows($res_user)>0) {
		$lig_user=mysql_fetch_object($res_user);

		$adresse_ip = $_SERVER['REMOTE_ADDR'];
		//$date = strftime("%Y-%m-%d %H:%M:%S");
		$date = ucfirst(strftime("%A %d-%m-%Y � %H:%M:%S"));
		//$url = parse_url($_SERVER['REQUEST_URI']);

		if (!(isset($active_hostbyaddr)) or ($active_hostbyaddr == "all")) {
			$result_hostbyaddr = " - ".@gethostbyaddr($adresse_ip);
		}
		else if($active_hostbyaddr == "no_local") {
			if ((substr($adresse_ip,0,3) == 127) or (substr($adresse_ip,0,3) == 10.) or (substr($adresse_ip,0,7) == 192.168)) {
				$result_hostbyaddr = "";
			}
			else{
				$tabip=explode(".",$adresse_ip);
				if(($tabip[0]==172)&&($tabip[1]>=16)&&($tabip[1]<=31)) {
					$result_hostbyaddr = "";
				}
				else{
					$result_hostbyaddr = " - ".@gethostbyaddr($adresse_ip);
				}
			}
		}
		else{
			$result_hostbyaddr = "";
		}


		//$message = "** Mail connexion Gepi **\n\n";
		$message=$texte;
		$message .= "\n";
		$message .= "Vous (*) vous �tes connect� � GEPI :\n\n";
		$message .= "Identit�                : ".strtoupper($lig_user->nom)." ".ucfirst(strtolower($lig_user->prenom))."\n";
		$message .= "Login                   : ".$user_login."\n";
		$message .= "Date                    : ".$date."\n";
		$message .= "Origine de la connexion : ".$adresse_ip."\n";
		if($result_hostbyaddr!="") {
			$message .= "Adresse IP r�solue en   : ".$result_hostbyaddr."\n";
		}
		$message .= "\n";
		$message .= "Ce message, s'il vous parvient alors que vous ne vous �tes pas connect� � la date/heure indiqu�e, est susceptible d'indiquer que votre identit� a pu �tre usurp�e.\nVous devriez contr�ler vos donn�es, changer votre mot de passe et avertir l'administrateur (et/ou l'administration de l'�tablissement) pour qu'il puisse prendre les mesures appropri�es.\n";
		$message .= "\n";
		$message .= "(*) Vous ou une personne tentant d'usurper votre identit�.\n";

		$ajout="";
		if(($informer_admin!='n')&&(getSettingValue("gepiAdminAdress")!='')) {
			$ajout="Bcc: ".getSettingValue("gepiAdminAdress")."\r\n";
		}

		// On envoie le mail
		//fdebug_mail_connexion("\$message=$message\n====================\n");

		$destinataire=$lig_user->email;
		$sujet="GEPI : $sujet $date";
		envoi_mail($sujet, $message, $destinataire, $ajout);

	}
}



function texte_html_ou_pas($texte){
	// Si le texte contient des < et >, on affiche tel quel
	if((strstr($texte,">"))||(strstr($texte,"<"))){
		$retour=$texte;
	}
	// Sinon, on transforme les retours � la ligne en <br />
	else{
		$retour=nl2br($texte);
	}
	return $retour;
}

function decompte_debug($motif,$texte) {
	global $tab_instant, $debug;
	if($debug=="y") {
		$instant=microtime();
		if(isset($tab_instant[$motif])) {
			$tmp_tab1=explode(" ",$instant);
			$tmp_tab2=explode(" ",$tab_instant[$motif]);
			if($tmp_tab1[1]!=$tmp_tab2[1]) {
				$diff=$tmp_tab1[1]-$tmp_tab2[1];
			}
			else {
				$diff=$tmp_tab1[0]-$tmp_tab2[0];
			}
				echo "<p style='color:green;'>$texte: ".$diff." s</p>\n";
		}
		else {
				echo "<p style='color:green;'>$texte</p>\n";
		}
		$tab_instant[$motif]=$instant;
	}
}


// Fonction qui retourne l'URI des �l�ves pour les flux rss
function retourneUri($eleve, $https, $type){

	global $gepiPath;
	$rep = array();

	// on v�rifie que la table en question existe d�j�
	$test_table = mysql_num_rows(mysql_query("SHOW TABLES LIKE 'rss_users'"));
	if ($test_table >= 1) {

		$sql = "SELECT user_uri FROM rss_users WHERE user_login = '".$eleve."' LIMIT 1";
		$query = mysql_query($sql);
		$nbre = mysql_num_rows($query);
		if ($nbre == 1) {
			$uri = mysql_fetch_array($query);
			if ($https == 'y') {
				$web = 'https://';
			}else{
				$web = 'http://';
			}
			if ($type == 'cdt') {
				$rep["uri"] = $web.$_SERVER["SERVER_NAME"].$gepiPath.'/class_php/syndication.php?rne='.getSettingValue("gepiSchoolRne").'&amp;ele_l='.$_SESSION["login"].'&amp;type=cdt&amp;uri='.$uri["user_uri"];
				$rep["text"] = $web.$_SERVER["SERVER_NAME"].$gepiPath.'/class_php/syndication.php?rne='.getSettingValue("gepiSchoolRne").'&amp;ele_l='.$_SESSION["login"].'&amp;type=cdt&amp;uri='.$uri["user_uri"];
			}

		}else{
			$rep["text"] = 'erreur1';
			$rep["uri"] = '#';
		}
	}else{

		$rep["text"] = 'Demandez � votre administrateur de g�n�rer les URI.';
		$rep["uri"] = '#';

	}

	return $rep;
}

function get_date_php() {
	$eng_words = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	$french_words = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche', 'Janvier', 'F�vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao�t', 'Septembre', 'Octobre', 'Novembre', 'D�cembre');
	$date_str = date('l').' '.date('d').' '.date('F').' '.date('Y');
	$date_str = str_replace($eng_words, $french_words, $date_str);
	return $date_str;
}

function casse_prenom($prenom) {
	$tab=explode("-",$prenom);

	$retour="";
	for($i=0;$i<count($tab);$i++) {
		if($i>0) {
			$retour.="-";
		}
		$tab[$i]=ucwords(strtolower($tab[$i]));
		$retour.=$tab[$i];
	}

	return $retour;
}

function traite_accents_utf8($chaine) {
	global $mode_utf8_pdf;
	if($mode_utf8_pdf=="y") {
		return utf8_encode($chaine);
	}
	else {
		return $chaine;
	}
}

function nf($nombre,$nb_chiffre_apres_virgule=1) {
	// Formatage des nombres
	// Precision:
	// Pour �tre s�r d'avoir un entier
	$nb_chiffre_apres_virgule=floor($nb_chiffre_apres_virgule);
	if($nb_chiffre_apres_virgule<1) {
		$precision=0.1;
		$nb_chiffre_apres_virgule=0;
	}
	else {
		$precision=pow(10,-1*$nb_chiffre_apres_virgule);
	}

	if(($nombre=='')||($nombre=='-')) {
		$valeur=$nombre;
	}
	else {
		$nombre=strtr($nombre,",",".");
		$valeur=number_format(round($nombre/$precision)*$precision, $nb_chiffre_apres_virgule, ',', '');
	}
	return $valeur;
}


function cell_ajustee($texte,$x,$y,$largeur_dispo,$h_cell,$hauteur_max_font,$hauteur_min_font,$bordure,$v_align='C',$align='L',$increment=0.3,$r_interligne=0.3) {
	global $pdf;

	// $increment:     nombre dont on r�duit la police � chaque essai
	// $r_interligne:  proportion de la taille de police pour les interlignes
	// $bordure:       LRBT
	// $v_align:       C(enter) ou T(op)

	// En cas de pb avec cell_ajustee1(), effectuer:
	// INSERT INTO setting SET name='cell_ajustee_old_way', value='y';
	// ou
	// UPDATE setting SET value='y' WHERE name='cell_ajustee_old_way';
	if(getSettingValue('cell_ajustee_old_way')=='y') {
		// On vire les balises en utilisant l'ancienne fonction qui ne g�rait pas les balises
		$texte=preg_replace('/<(.*)>/U','',$texte);
		cell_ajustee0($texte,$x,$y,$largeur_dispo,$h_cell,$hauteur_max_font,$hauteur_min_font,$bordure,$v_align,$align,$increment,$r_interligne);
	}
	else {
		cell_ajustee1($texte,$x,$y,$largeur_dispo,$h_cell,$hauteur_max_font,$hauteur_min_font,$bordure,$v_align,$align,$increment,$r_interligne);
	}
}

function my_echo_debug($texte) {
	global $mode_my_echo_debug;
	global $my_echo_debug;
	global $niveau_arbo;

	if($my_echo_debug==1) {
		if($mode_my_echo_debug!='fichier') {
			echo $texte;
		}
		else {
			$tempdir=get_user_temp_directory();
			if (isset($niveau_arbo) and ($niveau_arbo == "0")) {
				$points=".";
			}
			elseif (isset($niveau_arbo) and ($niveau_arbo == "2")) {
				$points="../..";
			}
			else {
				$points="..";
			}
			$dossier=$points."/temp/".$tempdir;

			// Pour simplifier en debug sur une machine perso sous *nix:
			$dossier="/tmp";

			$fichier=$dossier."/my_echo_debug_".date("Ymd_Hi").".txt";

			$f=fopen($fichier,"a+");
			fwrite($f,$texte);
			fclose($f);
		}
	}
}

function cell_ajustee1($texte,$x,$y,$largeur_dispo,$h_cell,$hauteur_max_font,$hauteur_min_font,$bordure,$v_align='C',$align='L',$increment=0.3,$r_interligne=0.3) {
	global $pdf;
	// Pour que la variable puisse �tre r�cup�r�e dans la fonction my_echo_debug(), il faut la d�clarer comme globale:
	global $my_echo_debug, $mode_my_echo_debug;

	// $increment:     nombre dont on r�duit la police � chaque essai
	// $r_interligne:  proportion de la taille de police pour les interlignes
	// $bordure:       LRBT
	// $v_align:       C(enter) ou T(op)

	$texte=trim($texte);
	$hauteur_texte=$hauteur_max_font;

	//================================
	// Options de debug
	// Passer � 1 pour d�bugger
	$my_echo_debug=0;
	//$my_echo_debug=1;

	// Les modes sont 'fichier' ou n'importe quoi d'autre qui provoque des echo... donc un �chec de la g�n�ration de PDF... � ouvrir avec un bloc-notes, pas avec un lecteur PDF
	// Voir la fonction my_echo_debug() pour l'emplacement du fichier g�n�r�
	$mode_my_echo_debug='fichier';
	//$mode_my_echo_debug='';
	//================================

	if($my_echo_debug==1) my_echo_debug("\n\n=========================================================\n");
	if($my_echo_debug==1) my_echo_debug("Lancement de\nmy_cell_ajustee(\$texte=$texte,\n\$x=$x,\n\$y=$y,\n\$largeur_dispo=$largeur_dispo,\n\$h_cell=$h_cell,\n\$hauteur_max_font=$hauteur_max_font,\n\$hauteur_min_font=$hauteur_min_font,\n\$bordure=$bordure,\n\$v_align=$v_align,\n\$align=$align,\n\$increment=$increment,\n\$r_interligne=$r_interligne)\n\n");

	if($my_echo_debug==1) my_echo_debug("\$texte=\"$texte\"\n");

	// Pour r�duire la taille du texte, il se peut qu'il faille supprimer les balises,...
	$supprimer_balises="n";
	$supprimer_retours_a_la_ligne="n";
	$tronquer="n";

	// On commence par essayer de remplir la cellule avec la taille de police propos�e
	// Et r�duire la taille de police si cela ne tient pas.
	// Si on arrive � une taille de police trop faible, on va supprimer des retours � la ligne, des balises ou m�me tronquer.

	// Pour forcer en debug:
	//$tronquer='y';

	while($tronquer!='y') {
		// On (re)d�marre un essai avec une taille de police

		$pdf->SetFontSize($hauteur_texte);

		// Nombre max de lignes avec la hauteur courante de police
		// Il manque l'interligne de bas de cellule
		$nb_max_lig=max(1,floor(($h_cell-$r_interligne*($hauteur_texte*26/100))/((1+$r_interligne)*($hauteur_texte*26/100))));
		
		if($my_echo_debug==1) my_echo_debug("\nOn lance un tour avec la taille de police:\n\$hauteur_texte=$hauteur_texte\n");
		if($my_echo_debug==1) my_echo_debug("\$nb_max_lig=$nb_max_lig\n");

		// Lignes dans la cellule
		unset($ligne);
		$ligne=array();

		// Compteur des lignes
		$cpt=0;

		// On pr�voit deux... trois espaces de marge en gras pour s'assurer que la ligne ne d�bordera pas
		$pdf->SetFont('','B');
		$un_espace_gras=$pdf->GetStringWidth(' ');
		if($my_echo_debug==1) my_echo_debug("Un espace en gras mesure $un_espace_gras\n");
		$marge_espaces=3*$un_espace_gras;
		if($my_echo_debug==1) my_echo_debug("On compte trois espaces de marge, soit $marge_espaces\n");
		$largeur_utile=$largeur_dispo-$marge_espaces;
		if($my_echo_debug==1) my_echo_debug("D'o� \$largeur_utile=$largeur_utile\n");

		// CONTROLER QUE \$largeur_utile>0
		if($largeur_utile<=0) {
			// On se laisse une chance que cela tienne en tronquant
			$tronquer="y";
			break;
		}

		$style_courant='';
		$pdf->SetFont('',$style_courant);

		// (R�-)initialisation du t�moin
		$temoin_reduire_police="n";

		if($supprimer_retours_a_la_ligne=="y") {
			$texte=trim(preg_replace("/\n/"," ",$texte));
		}

		$chaine_longueur_ligne_courante="0";

		$tab=preg_split('/<(.*)>/U',$texte,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($tab as $i=>$valeur) {
			// Avec $i pair on a le texte et les indices impairs correspondent aux balises (b et /b,...)

			// On initialise la ligne courante si n�cessaire pour le cas o� on aurait $texte="<b>Blabla..."
			// Il faut que la ligne soit initialis�e pour pouvoir ajouter le <b> dans $i%2!=0
			if(!isset($ligne[$cpt])) {
				$ligne[$cpt]='';
				$longueur_ligne_courante=0;
				$chaine_longueur_ligne_courante="0";
			}

			if($i%2==0) {
				if($my_echo_debug==1) my_echo_debug("\nParcours avec l'�l�ment \$i=$i: \"$tab[$i]\"\n");

				$tab2=explode(" ",$tab[$i]);
				// Si on g�re aussi les virgules et tirets, il y a une difficult� suppl�mentaire � g�rer pour re-concat�ner (normalement apr�s une virgule, on doit avoir un espace)... donc on ne g�re que les espaces

				if($my_echo_debug==1) my_echo_debug("_____________________________________________\n");
				for($j=0;$j<count($tab2);$j++) {
					if($my_echo_debug==1) my_echo_debug("Mot \$tab2[$j]=\"$tab2[$j]\"\n");
				}
				if($my_echo_debug==1) my_echo_debug("_____________________________________________\n");

				for($j=0;$j<count($tab2);$j++) {
					if($my_echo_debug==1) my_echo_debug("Mot \$tab2[$j]=\"$tab2[$j]\"\n");

					// Si un des mots d�passe $largeur_dispo, il faut r�duire la police (et si avec la police minimale, �a d�passe $largeur_dispo, il faudra couper n'importe o�...)
					if($pdf->GetStringWidth($tab2[$j])>$largeur_utile) {
						$temoin_reduire_police="y";
						break;
					}

					if($j>0) {
						// Il ne faut ajouter un espace que si on a augment� $j... (on n'est plus au premier mot de la ligne ~ voire... pb avec les d�coupes suivant les balises HTML)
						$largeur_espace=$pdf->GetStringWidth(' ');
						$longueur_ligne_courante+=$largeur_espace;
						$chaine_longueur_ligne_courante.="+".$largeur_espace;

						if($my_echo_debug==1) my_echo_debug("\$longueur_ligne_courante=$longueur_ligne_courante et \$largeur_utile=$largeur_utile\n");
						if($my_echo_debug==1) my_echo_debug("\$chaine_longueur_ligne_courante=$chaine_longueur_ligne_courante\n");

						if($longueur_ligne_courante>$largeur_utile) {
							// En ajoutant un espace, on d�passe la largeur_dispo
							$cpt++;
							if($cpt+1>$nb_max_lig) {
								// On d�passe le nombre max de lignes avec la taille de police courante
								$temoin_reduire_police="y";
								// On quitte la boucle sur les \n (boucle sur $tab3)
								break;
							}

							$ligne[$cpt]='';
							$longueur_ligne_courante=0;
							$chaine_longueur_ligne_courante="0";
						}
						else {
							$ligne[$cpt].=' ';
							if($my_echo_debug==1) my_echo_debug("On a ajout� un espace dans la longueur qui pr�c�de.\n");
							if($my_echo_debug==1) my_echo_debug("Longueur calcul�e sans g�rer les balises ".$pdf->GetStringWidth($ligne[$cpt])."\n");
						}
					}

					// Il n'y a pas d'espace dans $tab2[$j]
					// Si on scinde avec des \n, on aura un mot par indice de $tab3
					unset($tab3);
					$tab3=array();

					if($my_echo_debug==1) my_echo_debug("\$supprimer_retours_a_la_ligne=$supprimer_retours_a_la_ligne\n");
					// Prendre en compte � ce niveau les \n
					if($supprimer_retours_a_la_ligne=="n") {
						if($my_echo_debug==1) my_echo_debug("On d�coupe si n�cessaire les retours � la ligne\n");
						$tab3=explode("\n",$tab2[$j]);
						for($loop=0;$loop<count($tab3);$loop++) {if($my_echo_debug==1) my_echo_debug("   \$tab3[$loop]=\"$tab3[$loop]\"\n");}
					}
					else {
						$tab3[0]=$tab2[$j];
					}

					// Si supprimer_retours_a_la_ligne=='y', on ne fait qu'un tour dans la boucle
					for($k=0;$k<count($tab3);$k++) {
						if($k>0) {
							// On change de ligne

							if($my_echo_debug==1) my_echo_debug("\$ligne[$cpt]=\"$ligne[$cpt]\"\n");
							if($my_echo_debug==1) my_echo_debug("\$longueur_ligne_courante=$longueur_ligne_courante\n");
							if($my_echo_debug==1) my_echo_debug("\$chaine_longueur_ligne_courante=$chaine_longueur_ligne_courante\n");

							$cpt++;
							if($cpt+1>$nb_max_lig) {
								// On d�passe le nombre max de lignes avec la taille de police courante
								$temoin_reduire_police="y";
								// On quitte la boucle sur les \n (boucle sur $tab3)
								break;
							}
							$ligne[$cpt]='';
							$longueur_ligne_courante=0;
							$chaine_longueur_ligne_courante="0";
						}
						$test_longueur_ligne_courante=$longueur_ligne_courante+$pdf->GetStringWidth($tab3[$k]);
						if($my_echo_debug==1) my_echo_debug("La longueur du mot \$tab3[$k]=\"$tab3[$k]\" est ".$pdf->GetStringWidth($tab3[$k])."\n");

						if($test_longueur_ligne_courante>$largeur_utile) {
							$cpt++;
							if($cpt+1>$nb_max_lig) {
								// On d�passe le nombre max de lignes avec la taille de police courante
								$temoin_reduire_police="y";
								// On quitte la boucle sur les \n (boucle sur $tab3)
								break;
							}
							$ligne[$cpt]=$tab3[$k];
							$longueur_mot=$pdf->GetStringWidth($tab3[$k]);
							$longueur_ligne_courante=$longueur_mot;
							$chaine_longueur_ligne_courante=$longueur_mot;
						}
						else {
							// Ca tient encore sur la ligne courante
							$ligne[$cpt].=$tab3[$k];
							$longueur_mot=$pdf->GetStringWidth($tab3[$k]);
							$longueur_ligne_courante+=$longueur_mot;
							$chaine_longueur_ligne_courante.="+".$longueur_mot;
						}
						if($my_echo_debug==1) my_echo_debug("\$ligne[$cpt]=\"$ligne[$cpt]\"\n");
						if($my_echo_debug==1) my_echo_debug("\$longueur_ligne_courante=$longueur_ligne_courante\n");
						if($my_echo_debug==1) my_echo_debug("\$chaine_longueur_ligne_courante=$chaine_longueur_ligne_courante\n");
					}

					if($temoin_reduire_police=="y") {
						// On quitte la boucle sur les mots (boucle sur $tab2)
						break;
					}
				}
			}
			elseif($supprimer_balises=="n") {
				// On tient compte des balises
				if($valeur{0}=='/') {
					// On referme une balise
					if(strtoupper($valeur)=='/B') {
						$style_courant=preg_replace("/B/i","",$style_courant);
						$pdf->SetFont('',$style_courant);
						$ligne[$cpt].="</B>";
					}
					elseif(strtoupper($valeur)=='/I') {
						$style_courant=preg_replace("/I/i","",$style_courant);
						$pdf->SetFont('',$style_courant);
						$ligne[$cpt].="</I>";
					}
					elseif(strtoupper($valeur)=='/U') {
						$style_courant=preg_replace("/U/i","",$style_courant);
						$pdf->SetFont('',$style_courant);
						$ligne[$cpt].="</U>";
					}
				}
				else {
					// On ouvre une balise
					if(strtoupper($valeur)=='B') {
						$style_courant=$style_courant.'B';
						$pdf->SetFont('',$style_courant);
						$ligne[$cpt].="<B>";
					}
					elseif(strtoupper($valeur)=='I') {
						$style_courant=$style_courant.'I';
						$pdf->SetFont('',$style_courant);
						$ligne[$cpt].="<I>";
					}
					elseif(strtoupper($valeur)=='U') {
						$style_courant=$style_courant.'U';
						$pdf->SetFont('',$style_courant);
						$ligne[$cpt].="<U>";
					}
				}
				if($my_echo_debug==1) my_echo_debug("\$ligne[$cpt]=\"$ligne[$cpt]\"\n");
				if($my_echo_debug==1) my_echo_debug("\$longueur_ligne_courante=$longueur_ligne_courante\n");
				if($my_echo_debug==1) my_echo_debug("\$style_courant=$style_courant\n");
			}

			if($temoin_reduire_police=="y") {
				$hauteur_texte-=$increment;
				//if($hauteur_texte<=0) {
				if(($hauteur_texte<=0)||($hauteur_texte<$hauteur_min_font)) {
					// Probl�me... il va falloir:
					// - ne pas prendre en compte les \n
					// - ne pas prendre en compte les balises
					// - tronquer

					if($supprimer_retours_a_la_ligne=='n') {
						// On va virer les \n en les rempla�ant par des espaces
						$supprimer_retours_a_la_ligne='y';
						if($my_echo_debug==1) my_echo_debug("+++ On va supprimer les retours � la ligne.\n");
					}
					elseif($supprimer_balises=='n') {
						// On va un cran plus loin... en virant les balises... on ne gagnera que sur les mots en gras qui sont plus larges
						$supprimer_balises='y';
						if($my_echo_debug==1) my_echo_debug("+++ On va supprimer les balises.\n");
					}
					else {
						// Il va falloir tronquer... pas cool!

						// A FAIRE
						$tronquer="y";

						if($my_echo_debug==1) my_echo_debug("+++ On va tronquer.\n");
					}

					// R�initialiser la taille de police:
					$hauteur_texte=$hauteur_max_font;
				}
				else {
					if($my_echo_debug==1) my_echo_debug("+++++++++++++++\n");
					if($my_echo_debug==1) my_echo_debug("\nOn r�duit la taille de police:\n");
					if($my_echo_debug==1) my_echo_debug("\$hauteur_texte=".$hauteur_texte."\n");
				}

				// On quitte la boucle sur le tableau des d�coupages de balises HTML (boucle sur $tab)
				break;
			}
		}

		if($my_echo_debug==1) my_echo_debug("\$temoin_reduire_police=$temoin_reduire_police\n");

		if($temoin_reduire_police!="y") {
			// On a fini par trouver une taille  de police convenable

			if($my_echo_debug==1) my_echo_debug("\nOn a trouv� la bonne la taille de police:\n");

			// On quitte la boucle pour proc�der � l'affichage du contenu de $ligne plus bas
			break;
		}
	}

	if($tronquer=='y') {
		// A FAIRE: On va remplir en coupant n'importe o� dans les mots sans chercher � conserver des mots entiers
		//          Faut-il faire la boucle sur la taille de police?
		//          Ou prendre directement la taille minimale?

		if($my_echo_debug==1) my_echo_debug("---------------------------------\n");
		if($my_echo_debug==1) my_echo_debug("--- On va remplir en tronquant...\n");

		$hauteur_texte=$hauteur_min_font;

		$pdf->SetFontSize($hauteur_texte);

		// Nombre max de lignes avec la hauteur courante de police
		$nb_max_lig=max(1,floor(($h_cell-$r_interligne*($hauteur_texte*26/100))/((1+$r_interligne)*($hauteur_texte*26/100))));

		if($my_echo_debug==1) my_echo_debug("\$hauteur_texte=$hauteur_texte\n");
		if($my_echo_debug==1) my_echo_debug("\$nb_max_lig=$nb_max_lig\n");

		// Lignes dans la cellule
		unset($ligne);
		$ligne=array();

		// Compteur des lignes
		$cpt=0;

		$longueur_max_atteinte="n";

		// On pr�voit deux... trois espaces de marge en gras pour s'assurer que la ligne ne d�bordera pas
		$pdf->SetFont('','B');
		$marge_espaces=3*$pdf->GetStringWidth(' ');
		$largeur_utile=$largeur_dispo-$marge_espaces;

		// CONTROLER QUE \$largeur_utile>0
		if($largeur_utile>0) {
			$style_courant='';
			$pdf->SetFont('',$style_courant);

			// On va supprimer les retours � la ligne
			$texte=trim(preg_replace("/\n/"," ",$texte));
			if($my_echo_debug==1) my_echo_debug("\$texte=$texte\n");

			// On supprime les balises
			$texte=preg_replace('/<(.*)>/U','',$texte);
			if($my_echo_debug==1) my_echo_debug("\$texte=$texte\n");
			for($j=0;$j<strlen($texte);$j++) {

				if(!isset($ligne[$cpt])) {
					$ligne[$cpt]='';
				}
				if($my_echo_debug==1) my_echo_debug("\$ligne[$cpt]=\"$ligne[$cpt]\"\n");

				$chaine=$ligne[$cpt].substr($texte,$j,1);
				if($my_echo_debug==1) my_echo_debug("\$chaine=\"$chaine\"\n");

				if($pdf->GetStringWidth($chaine)>$largeur_utile) {

					if($my_echo_debug==1) my_echo_debug("Avec \$chaine, �a d�passe.\n");

					if($cpt+1>$nb_max_lig) {
						$longueur_max_atteinte="y";

						if($my_echo_debug==1) my_echo_debug("\$cpt=$cpt et \$nb_max_lig=$nb_max_lig.\nOn ne peut plus ajouter une ligne.\n");

						break;
					}

					$cpt++;
					$ligne[$cpt]=substr($texte,$j,1);
					if($my_echo_debug==1) my_echo_debug("On commence une nouvelle ligne avec le dernier caract�re: \"".substr($texte,$j-1,1)."\"\n");
					if($my_echo_debug==1) my_echo_debug("\$ligne[$cpt]=\"$ligne[$cpt]\"\n");
				}
				else {
					$ligne[$cpt].=substr($texte,$j,1);
					if($my_echo_debug==1) my_echo_debug("\$ligne[$cpt]=\"$ligne[$cpt]\"\n");
				}
			}

			if($my_echo_debug==1) my_echo_debug("On a fini le texte... ou atteint une limite\n");

		}
	}

	// On va afficher le texte

	// Hauteur de la police en mm
	$hauteur_texte_mm=$hauteur_texte*26/100;
	// Hauteur de la police en pt
	$taille_police=$hauteur_texte;
	// Hauteur totale du texte
	$hauteur_totale=($cpt+1)*$hauteur_texte_mm*(1+$r_interligne);
	// Marge verticale en mm entre les lignes
	$marge_verticale=$hauteur_texte_mm*$r_interligne;


	if($my_echo_debug==1) my_echo_debug("\$hauteur_texte=".$hauteur_texte."\n");
	if($my_echo_debug==1) my_echo_debug("\$hauteur_texte_mm=".$hauteur_texte_mm."\n");
	if($my_echo_debug==1) my_echo_debug("\$hauteur_totale=".$hauteur_totale."\n");
	if($my_echo_debug==1) my_echo_debug("\$marge_verticale=".$marge_verticale."\n");


	// On trace le rectangle (vide) du cadre:
	$pdf->SetXY($x,$y);
	$pdf->Cell($largeur_dispo,$h_cell, '',$bordure,2,'');

	// On va �crire les lignes avec la taille de police optimale d�termin�e (cf. $ifmax)
	$nb_lig=count($ligne);
	$h=$nb_lig*$hauteur_texte_mm*(1+$r_interligne);
	$t=$h_cell-$h;
	if($my_echo_debug==1) my_echo_debug("\$t=".$t."\n");
	$bord_debug='';
	//$bord_debug='LRBT';

	// On ne g�re que les v_align Top et Center... et ajout d'un mode a�r�
	if($v_align=='E') {
		// Mode a�r�
		$espace_v=($h_cell-4*$marge_verticale-$nb_lig*$hauteur_texte_mm)/max(1,$nb_lig-1);
	}
	elseif($v_align!='T') {
		// Par d�faut c'est Center
		//$decalage_v_top=($h_cell-$nb_lig*$hauteur_texte_mm-($nb_lig-1)*$marge_verticale)/2;
		$decalage_v_top=($h_cell-($nb_lig+1)*$hauteur_texte_mm-$nb_lig*$marge_verticale)/2;
	}

	for($i=0;$i<count($ligne);$i++) {

		if($v_align=='T') {
			$pdf->SetXY($x,$y+$i*($hauteur_texte_mm+$marge_verticale));

			// Pour pouvoir afficher le $bord_debug
			$pdf->Cell($largeur_dispo,$hauteur_texte_mm+2*$marge_verticale, '',$bord_debug,1,$align);

			$y_courant=$y+$i*($hauteur_texte_mm+$marge_verticale)-$marge_verticale;
			$pdf->SetXY($x,$y_courant);
			if($my_echo_debug==1) {
				$pdf->myWriteHTML($ligne[$i]." ".$i." ".round($y_courant));
			}
			else {
				$pdf->myWriteHTML($ligne[$i]);
			}
		}
		elseif($v_align=='E') {
			$y_courant=$y+$marge_verticale+$i*($hauteur_texte_mm+$espace_v);
			$pdf->SetXY($x,$y_courant);

			// Pour pouvoir afficher le $bord_debug
			$pdf->Cell($largeur_dispo,$h_cell/$nb_lig, '',$bord_debug,1,$align);

			$pdf->SetXY($x,$y_courant);
			$pdf->myWriteHTML($ligne[$i]);
		}
		else {
			$y_courant=$y+$decalage_v_top+$i*($hauteur_texte_mm+$marge_verticale);

			// Pour pouvoir afficher le $bord_debug A REFAIRE
			
			$pdf->SetXY($x,$y_courant);
			
			$pdf->myWriteHTML($ligne[$i]);
		}
	}
}

// Ancienne fonction cell_ajustee() ne g�rant pas les balises HTML B,I et U
function cell_ajustee0($texte,$x,$y,$largeur_dispo,$h_cell,$hauteur_max_font,$hauteur_min_font,$bordure,$v_align='C',$align='L',$increment=0.3,$r_interligne=0.3) {
	global $pdf;

	// $increment:     nombre dont on r�duit la police � chaque essai
	// $r_interligne:  proportion de la taille de police pour les interlignes
	// $bordure:       LRBT
	// $v_align:       C(enter) ou T(op)

	$texte=trim($texte);
	$hauteur_texte=$hauteur_max_font;
	$pdf->SetFontSize($hauteur_texte);
	$taille_texte_total=$pdf->GetStringWidth($texte);

	// Ca nous donne le nombre max de lignes en hauteur avec la taille de police maxi
	// Il faudrait plut�t d�terminer ce nombre d'apr�s une taille minimale acceptable de police
	$nb_max_lig=max(1,floor($h_cell/((1+$r_interligne)*($hauteur_min_font*26/100))));
	
	$fmax=0;

	$tab_lig=array();
	for($j=1;$j<=$nb_max_lig;$j++) {
		$hauteur_texte=$hauteur_max_font;

		unset($ligne);
		$ligne=array();

		$tab=explode(" ",$texte);
		$cpt=0;
		$i=0;
		while(TRUE) {
			if(isset($ligne[$cpt])) {$ligne[$cpt].=" ";} else {$ligne[$cpt]="";}

			if(preg_match("/\n/",$tab[$i])) {
				$tmp_tab=explode("\n",$tab[$i]);

				for($k=0;$k<count($tmp_tab)-1;$k++) {
					if(!isset($ligne[$cpt])) {$ligne[$cpt]="";}
					$ligne[$cpt].=$tmp_tab[$k];
					$cpt++;
				}
				if(!isset($ligne[$cpt])) {$ligne[$cpt]="";}
				$ligne[$cpt].=$tmp_tab[$k];
			}
			else {
				if($pdf->GetStringWidth($ligne[$cpt].$tab[$i])>=$largeur_dispo) {
					$cpt++;
					$ligne[$cpt]=$tab[$i];
				}
				else {
					$ligne[$cpt].=$tab[$i];
				}
			}
			$i++;
			if(!isset($tab[$i])) {break;}
		}

		// Recherche de la plus longue ligne:
		$taille_texte_ligne=0;
		$num=0;
		for($i=0;$i<count($ligne);$i++) {
			$l=$pdf->GetStringWidth($ligne[$i]);
			if($taille_texte_ligne<$l) {$taille_texte_ligne=$l;$num=$i;}
		}

		// On calcule la hauteur en mm de la police (proportionnalit�: 100pt -> 26mm)
		$hauteur_texte_mm=$hauteur_texte*26/100;
		// Hauteur totale: Nombre de lignes multipli� par la hauteur de police avec les marges verticales
		$hauteur_totale=($cpt+1)*$hauteur_texte_mm*(1+$r_interligne);

		// echo "On calcule la taille de la police d'apr�s \$ligne[$num]=".$ligne[$num]."<br/>";
		// On ajuste la taille de police avec la plus grande ligne pour que cela tienne en largeur
		// et on contr�le aussi que cela tient en hauteur, sinon on continue � r�duire la police.
		$grandeur_texte='test';
		while($grandeur_texte!='ok') {
			if(($largeur_dispo<$taille_texte_ligne)||($hauteur_totale>$h_cell)) {
				$hauteur_texte=$hauteur_texte-$increment;
				if($hauteur_texte<$hauteur_min_font) {break;}
				$hauteur_texte_mm=$hauteur_texte*26/100;
				$hauteur_totale=($cpt+1)*$hauteur_texte_mm*(1+$r_interligne);
				$pdf->SetFontSize($hauteur_texte);
				$taille_texte_ligne=$pdf->GetStringWidth($ligne[$num]);
			}
			else {
				$grandeur_texte='ok';
			}
		}

		if($grandeur_texte=='ok') {
			// Hauteur de la police en mm
			$hauteur_texte_mm=$hauteur_texte*26/100;
			$tab_lig[$j]['hauteur_texte_mm']=$hauteur_texte_mm;
			// Hauteur de la police en pt
			$tab_lig[$j]['taille_police']=$hauteur_texte;
			// Hauteur totale du texte
			$tab_lig[$j]['hauteur_totale']=($cpt+1)*$hauteur_texte_mm*(1+$r_interligne);
			// Marge verticale en mm entre les lignes
			$marge_verticale=$hauteur_texte_mm*$r_interligne;
			$tab_lig[$j]['marge_verticale']=$marge_verticale;
			// Tableau des lignes
			$tab_lig[$j]['lignes']=$ligne;

			// On choisit la hauteur de police la plus grande possible pour laquelle les lignes tiennent en hauteur
			// (la largeur a d�j� �t� utilis�e pour d�couper en lignes).
			if(($hauteur_texte>$fmax)&&($tab_lig[$j]['hauteur_totale']<=$h_cell)) {
				$ifmax=$j;
			}
		}
	}

	if((!isset($ifmax))||($tab_lig[$ifmax]['taille_police']<$hauteur_min_font)) {
		// On relance en rempla�ant les retours forc�s � la ligne (\n) par des espaces.

		$fmax=0;

		$tab_lig=array();
		for($j=1;$j<=$nb_max_lig;$j++) {
			$hauteur_texte=$hauteur_max_font;

			unset($ligne);
			$ligne=array();

			$tab=explode(" ",trim(preg_replace("/\n/"," ",$texte)));
			$cpt=0;
			$i=0;
			while(TRUE) {
				if(isset($ligne[$cpt])) {$ligne[$cpt].=" ";} else {$ligne[$cpt]="";}

				if($pdf->GetStringWidth($ligne[$cpt].$tab[$i])>=$largeur_dispo) {
					$cpt++;
					$ligne[$cpt]=$tab[$i];
				}
				else {
					$ligne[$cpt].=$tab[$i];
				}
				$i++;
				if(!isset($tab[$i])) {break;}
			}

			// Recherche de la plus longue ligne:
			$taille_texte_ligne=0;
			$num=0;
			for($i=0;$i<count($ligne);$i++) {
				// echo "\$ligne[$i]=$ligne[$i]<br />";
				$l=$pdf->GetStringWidth($ligne[$i]);
				if($taille_texte_ligne<$l) {$taille_texte_ligne=$l;$num=$i;}
			}

			// On calcule la hauteur en mm de la police (proportionnalit�: 100pt -> 26mm)
			$hauteur_texte_mm=$hauteur_texte*26/100;
			// Hauteur totale: Nombre de lignes multipli� par la hauteur de police avec les marges verticales
			$hauteur_totale=($cpt+1)*$hauteur_texte_mm*(1+$r_interligne);

			// echo "On calcule la taille de la police d'apr�s \$ligne[$num]=".$ligne[$num]."<br/>";
			// On ajuste la taille de police avec la plus grande ligne pour que cela tienne en largeur
			// et on contr�le aussi que cela tient en hauteur, sinon on continue � r�duire la police.
			$grandeur_texte='test';
			while($grandeur_texte!='ok') {
				if(($largeur_dispo<$taille_texte_ligne)||($hauteur_totale>$h_cell)) {
					$hauteur_texte=$hauteur_texte-$increment;
					if($hauteur_texte<$hauteur_min_font) {break;}
					$hauteur_texte_mm=$hauteur_texte*26/100;
					$hauteur_totale=($cpt+1)*$hauteur_texte_mm*(1+$r_interligne);
					$pdf->SetFontSize($hauteur_texte);
					$taille_texte_ligne=$pdf->GetStringWidth($ligne[$num]);
				}
				else {
					$grandeur_texte='ok';
				}
			}

			if($grandeur_texte=='ok') {
				// Hauteur de la police en mm
				$hauteur_texte_mm=$hauteur_texte*26/100;
				$tab_lig[$j]['hauteur_texte_mm']=$hauteur_texte_mm;
				// Hauteur de la police en pt
				$tab_lig[$j]['taille_police']=$hauteur_texte;
				// Hauteur totale du texte
				$tab_lig[$j]['hauteur_totale']=($cpt+1)*$hauteur_texte_mm*(1+$r_interligne);
				// Marge verticale en mm entre les lignes
				$marge_verticale=$hauteur_texte_mm*$r_interligne;
				$tab_lig[$j]['marge_verticale']=$marge_verticale;
				// Tableau des lignes
				$tab_lig[$j]['lignes']=$ligne;

				// On choisit la hauteur de police la plus grande possible pour laquelle les lignes tiennent en hauteur
				// (la largeur a d�j� �t� utilis�e pour d�couper en lignes).
				if(($hauteur_texte>$fmax)&&($tab_lig[$j]['hauteur_totale']<=$h_cell)) {
					$ifmax=$j;
				}
			}
		}


		// Si �a ne passe toujours pas, on prend $hauteur_min_font sans retours � la ligne et on tronque
		if(!isset($ifmax)) {
			
			$fmax=0;

			$tab_lig=array();
			$hauteur_texte=$hauteur_min_font;
			unset($ligne);
			$ligne=array();

			$tab=explode(" ",trim(preg_replace("/\n/"," ",$texte)));
			$cpt=0;
			$i=0;
			while(TRUE) {
				if(isset($ligne[$cpt])) {$ligne[$cpt].=" ";} else {$ligne[$cpt]="";}

				if($pdf->GetStringWidth($ligne[$cpt].$tab[$i])>=$largeur_dispo) {

					if(($cpt+2)*$hauteur_texte*(1+$r_interligne)*26/100>$h_cell) {
						$d=1;
						while(($pdf->GetStringWidth(substr($ligne[$cpt],0,strlen($ligne[$cpt])-$d)."...")>=$largeur_dispo)&&($d<strlen($ligne[$cpt]))) {
							$d++;
						}
						$ligne[$cpt]=substr($ligne[$cpt],0,strlen($ligne[$cpt])-$d)."...";
						break;
					}

					$cpt++;
					$ligne[$cpt]=$tab[$i];
				}
				else {
					$ligne[$cpt].=$tab[$i];
				}
				$i++;
				if(!isset($tab[$i])) {break;} // On ne devrait pas quitter sur �a puisque le texte va �tre trop long
			}

			$j=1;
			$ifmax=$j;
			$hauteur_texte_mm=$hauteur_texte*26/100;
			$tab_lig[$j]['hauteur_texte_mm']=$hauteur_texte_mm;
			// Hauteur de la police en pt
			$tab_lig[$j]['taille_police']=$hauteur_texte;
			// Hauteur totale du texte
			$tab_lig[$j]['hauteur_totale']=($cpt+1)*$hauteur_texte_mm*(1+$r_interligne);
			// Marge verticale en mm entre les lignes
			$marge_verticale=$hauteur_texte_mm*$r_interligne;
			$tab_lig[$j]['marge_verticale']=$marge_verticale;
			// Tableau des lignes
			$tab_lig[$j]['lignes']=$ligne;

		}
	}

	// On trace le rectangle (vide) du cadre:
	$pdf->SetXY($x,$y);
	$pdf->Cell($largeur_dispo,$h_cell, '',$bordure,2,'');

	// On va �crire les lignes avec la taille de police optimale d�termin�e (cf. $ifmax)
	//$marge_h=round(($h_cell-(count($ligne)*$hauteur_texte_mm+(count($ligne)-1)*$marge_verticale))/2);
	//$marge_h=round(($h_cell-$tab_lig[$ifmax]['hauteur_totale'])/2);
	$nb_lig=count($tab_lig[$ifmax]['lignes']);
	$h=count($tab_lig[$ifmax]['lignes'])*$tab_lig[$ifmax]['hauteur_texte_mm']*(1+$r_interligne);
	$t=$h_cell-$h;
	$bord_debug='';
	for($i=0;$i<count($tab_lig[$ifmax]['lignes']);$i++) {

		$pdf->SetXY($x,$y+$i*($tab_lig[$ifmax]['hauteur_texte_mm']+$tab_lig[$ifmax]['marge_verticale']));

		if($v_align=='T') {
			$pdf->Cell($largeur_dispo,$tab_lig[$ifmax]['hauteur_texte_mm']+2*$tab_lig[$ifmax]['marge_verticale'], $tab_lig[$ifmax]['lignes'][$i],$bord_debug,1,$align);
		}
		else {
			$pdf->Cell($largeur_dispo,$h_cell/count($tab_lig[$ifmax]['lignes']), $tab_lig[$ifmax]['lignes'][$i],$bord_debug,1,$align);
		}
	}
	
}

function cell_ajustee_une_ligne($texte,$x,$y,$largeur_dispo,$h_ligne,$hauteur_caractere,$fonte,$graisse,$alignement,$bordure) {
	global $pdf;

	$pdf->SetFont($fonte,$graisse,$hauteur_caractere);
	$val = $pdf->GetStringWidth($texte);
	$temoin='';
	while($temoin != 'ok') {
		if($largeur_dispo < $val){
			$hauteur_caractere = $hauteur_caractere-0.3;
			$pdf->SetFont($fonte,$graisse,$hauteur_caractere);
			$val = $pdf->GetStringWidth($texte);
		} else {
			$temoin = 'ok';
		}
	}

	$pdf->SetXY($x,$y);
	$pdf->Cell($largeur_dispo,$h_ligne, $texte,$bordure,2,$alignement);
}

function casse_mot($mot,$mode='maj') {
	if($mode=='maj') {
		return strtr(strtoupper($mot),"��������������������������������","�����������������������������Ƽ��");
	}
	elseif($mode=='min') {
		return strtr(strtolower($mot),"�����������������������������Ƽ��","��������������������������������");
	}
	elseif($mode=='majf') {
		if(strlen($mot)>1) {
			return strtr(strtoupper(substr($mot,0,1)),"��������������������������������","�����������������������������Ƽ��").strtr(strtolower(substr($mot,1)),"�����������������������������Ƽ��","��������������������������������");
		}
		else {
			return strtr(strtoupper($mot),"��������������������������������","�����������������������������Ƽ��");
		}
	}
	elseif($mode=='majf2') {
		$chaine="";
		$tab=explode(" ",$mot);
		for($i=0;$i<count($tab);$i++) {
			if($i>0) {$chaine.=" ";}
			$tab2=explode("-",$tab[$i]);
			for($j=0;$j<count($tab2);$j++) {
				if($j>0) {$chaine.="-";}
				if(strlen($tab2[$j])>1) {
					$chaine.=strtr(strtoupper(substr($tab2[$j],0,1)),"��������������������������������","�����������������������������Ƽ��").strtr(strtolower(substr($tab2[$j],1)),"�����������������������������Ƽ��","��������������������������������");
				}
				else {
					$chaine.=strtr(strtoupper($tab2[$j]),"��������������������������������","�����������������������������Ƽ��");
				}
			}
		}
		return $chaine;
	}
}


function javascript_tab_stat($pref_id,$cpt) {
	// Fonction � appeler avec une portion de code du type:
	/*
	echo "<div style='position: fixed; top: 200px; right: 200px;'>\n";
	javascript_tab_stat('tab_stat_',$cpt);
	echo "</div>\n";
	*/

	$alt=1;
	echo "<table class='boireaus' summary='Statistiques'>\n";
	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>Moyenne</th>\n";
	echo "<td id='".$pref_id."moyenne'></td>\n";
	echo "</tr>\n";

	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>1er quartile</th>\n";
	echo "<td id='".$pref_id."q1'></td>\n";
	echo "</tr>\n";

	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>M�diane</th>\n";
	echo "<td id='".$pref_id."mediane'></td>\n";
	echo "</tr>\n";

	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>3� quartile</th>\n";
	echo "<td id='".$pref_id."q3'></td>\n";
	echo "</tr>\n";

	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>Min</th>\n";
	echo "<td id='".$pref_id."min'></td>\n";
	echo "</tr>\n";

	$alt=$alt*(-1);
	echo "<tr class='lig$alt'>\n";
	echo "<th>Max</th>\n";
	echo "<td id='".$pref_id."max'></td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<script type='text/javascript' language='JavaScript'>

function calcul_moy_med() {
	var eff_utile=0;
	var total=0;
	var valeur;
	var tab_valeur=new Array();
	var i=0;
	var j=0;
	var n=0;
	var mediane;
	var moyenne;
	var q1;
	var q3;
	var rang=0;

	for(i=0;i<$cpt;i++) {
		if(document.getElementById('n'+i)) {
			valeur=document.getElementById('n'+i).value;

			valeur=valeur.replace(',','.');

			if((valeur!='abs')&&(valeur!='disp')&&(valeur!='-')&&(valeur!='')) {
				tab_valeur[j]=valeur;
				// Tambouille pour �viter que 'valeur' soit pris pour une chaine de caract�res
				total=eval((total*100+valeur*100)/100);
				eff_utile++;
				j++;
			}
		}
	}
	if(eff_utile>0) {
		moyenne=Math.round(10*total/eff_utile)/10;
		document.getElementById('".$pref_id."moyenne').innerHTML=moyenne;

		tab_valeur.sort((function(a,b){return a - b}));
		n=tab_valeur.length;
		if(n/2==Math.round(n/2)) {
			// Les indices commencent � z�ro
			// Tambouille pour �viter que 'valeur' soit pris pour une chaine de caract�res
			mediane=((eval(100*tab_valeur[n/2-1]+100*tab_valeur[n/2]))/100)/2;
		}
		else {
			mediane=tab_valeur[(n-1)/2];
		}
		document.getElementById('".$pref_id."mediane').innerHTML=mediane;

		if(eff_utile>=4) {
			rang=Math.ceil(eff_utile/4);
			q1=tab_valeur[rang-1];

			rang=Math.ceil(3*eff_utile/4);
			q3=tab_valeur[rang-1];

			document.getElementById('".$pref_id."q1').innerHTML=q1;
			document.getElementById('".$pref_id."q3').innerHTML=q3;
		}
		else {
			document.getElementById('".$pref_id."q1').innerHTML='-';
			document.getElementById('".$pref_id."q3').innerHTML='-';
		}

		document.getElementById('".$pref_id."min').innerHTML=tab_valeur[0];
		document.getElementById('".$pref_id."max').innerHTML=tab_valeur[n-1];
	}
	else {
		document.getElementById('".$pref_id."moyenne').innerHTML='-';
		document.getElementById('".$pref_id."mediane').innerHTML='-';
		document.getElementById('".$pref_id."q1').innerHTML='-';
		document.getElementById('".$pref_id."q3').innerHTML='-';
		document.getElementById('".$pref_id."min').innerHTML='-';
		document.getElementById('".$pref_id."max').innerHTML='-';
	}
}

calcul_moy_med();
</script>
";
}


function calcule_moy_mediane_quartiles($tab) {
	$tab2=array();

	$total=0;
	for($i=0;$i<count($tab);$i++) {
		if(($tab[$i]!='')&&($tab[$i]!='-')&&($tab[$i]!='&nbsp;')&&($tab[$i]!='abs')&&($tab[$i]!='disp')) {
			$tab2[]=preg_replace('/,/','.',$tab[$i]);
			$total+=preg_replace('/,/','.',$tab[$i]);
		}
	}

	// Initialisation
	$tab_retour['moyenne']='-';
	$tab_retour['mediane']='-';
	$tab_retour['min']='-';
	$tab_retour['max']='-';
	$tab_retour['q1']='-';
	$tab_retour['q3']='-';

	if(count($tab2)>0) {
		sort($tab2);

		$moyenne=round(10*$total/count($tab2))/10;

		if(count($tab2)%2==0) {
			$mediane=($tab2[count($tab2)/2-1]+$tab2[count($tab2)/2])/2;
		}
		else {
			$mediane=$tab2[(count($tab2)-1)/2];
		}

		$min=min($tab2);
		$max=max($tab2);

		if(count($tab2)>=4) {
			$q1=$tab2[ceil(count($tab2)/4)-1];
			$q3=$tab2[ceil(3*count($tab2)/4)-1];
		}

		$tab_retour['moyenne']=$moyenne;
		$tab_retour['mediane']=$mediane;
		$tab_retour['min']=$min;
		$tab_retour['max']=$max;
		$tab_retour['q1']=$q1;
		$tab_retour['q3']=$q3;
	}

	return $tab_retour;
}


function get_nom_prenom_eleve($login_ele,$mode='simple') {
	$sql="SELECT nom,prenom FROM eleves WHERE login='$login_ele';";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)==0) {
		// Si ce n'est pas un �l�ve, c'est peut-�tre un utilisateur prof, cpe, responsable,...
		$sql="SELECT 1=1 FROM utilisateurs WHERE login='$login_ele';";
		$res=mysql_query($sql);
		if(mysql_num_rows($res)>0) {
			return civ_nom_prenom($login_ele)." (non-�l�ve)";
		}
		else {
			return "El�ve inconnu ($login_ele)";
		}
	}
	else {
		$lig=mysql_fetch_object($res);

		$ajout="";
		if($mode=='avec_classe') {
			$tmp_tab_clas=get_class_from_ele_login($login_ele);
			if((isset($tmp_tab_clas['liste']))&&($tmp_tab_clas['liste']!='')) {
				$ajout=" (".$tmp_tab_clas['liste'].")";
			}
		}

		return casse_mot($lig->nom)." ".casse_mot($lig->prenom,'majf2').$ajout;
	}
}

function get_commune($code_commune_insee,$mode){
	$retour="";

	if(strstr($code_commune_insee,'@')) {
		// On a affaire � une commune �trang�re
		$tmp_tab=explode('@',$code_commune_insee);
		$sql="SELECT * FROM pays WHERE code_pays='$tmp_tab[0]';";
		//echo "$sql<br />";
		$res_pays=mysql_query($sql);
		if(mysql_num_rows($res_pays)==0) {
			$retour=stripslashes($tmp_tab[1])." ($tmp_tab[0])";
		}
		else {
			$lig_pays=mysql_fetch_object($res_pays);
			$retour=stripslashes($tmp_tab[1])." (".$lig_pays->nom_pays.")";
		}
	}
	else {
		$sql="SELECT * FROM communes WHERE code_commune_insee='$code_commune_insee';";
		$res=mysql_query($sql);
		if(mysql_num_rows($res)>0) {
			$lig=mysql_fetch_object($res);
			if($mode==0) {
				$retour=$lig->commune;
			}
			elseif($mode==1) {
				$retour=$lig->commune." (<i>".$lig->departement."</i>)";
			}
			elseif($mode==2) {
				$retour=$lig->commune." (".$lig->departement.")";
			}
		}
	}
	return $retour;
}


function civ_nom_prenom($login,$mode='prenom') {
	$retour="";
	$sql="SELECT nom,prenom,civilite FROM utilisateurs WHERE login='$login';";
	$res_user=mysql_query($sql);
	if (mysql_num_rows($res_user)>0) {
		$lig_user=mysql_fetch_object($res_user);
		if($lig_user->civilite!="") {
			$retour.=$lig_user->civilite." ";
		}
		if($mode=='prenom') {
			$retour.=strtoupper($lig_user->nom)." ".ucfirst(strtolower($lig_user->prenom));
		}
		else {
			// Initiale
			$retour.=strtoupper($lig_user->nom)." ".strtoupper(substr($lig_user->prenom,0,1));
		}
	}
	return $retour;
}

// Enleve le num�ro des titres num�rot�s ("1. Titre" -> "Titre")
// Exemple :  "12. Titre"  donne "Titre"
function supprimer_numero($texte) {
 return preg_replace(",^[[:space:]]*([0-9]+)([.)])[[:space:]]+,S","", $texte);
}


function test_ecriture_dossier($tab_restriction=array()) {
    global $gepiPath;

	//$tab_dossiers_rw=array("documents","images","secure","photos","backup","temp","mod_ooo/mes_modele","mod_ooo/tmp","mod_notanet/OOo/tmp","lib/standalone/HTMLPurifier/DefinitionCache/Serializer");
	//$tab_dossiers_rw=array("documents","images","photos","backup","temp","mod_ooo/mes_modele","mod_ooo/tmp","mod_notanet/OOo/tmp","lib/standalone/HTMLPurifier/DefinitionCache/Serializer");

	if(count($tab_restriction)>0) {
		$tab_dossiers_rw=$tab_restriction;
	}
	else {
		$tab_dossiers_rw=array("artichow/cache","backup","documents","documents/archives","images","images/background","lib/standalone/HTMLPurifier/DefinitionCache/Serializer","mod_ooo/mes_modeles","mod_ooo/tmp","photos","temp");
	}

	$nom_fichier_test='test_acces_rw';

	echo "<table class='boireaus' summary='Tableau des dossiers qui doivent �tre accessibles en �criture'>\n";
	echo "<tr>\n";
	echo "<th>Dossier</th>\n";
	echo "<th>Ecriture</th>\n";
	echo "</tr>\n";
	$alt=1;
	for($i=0;$i<count($tab_dossiers_rw);$i++) {
		$ok_rw="no";
		if ($f = @fopen("../".$tab_dossiers_rw[$i]."/".$nom_fichier_test, "w")) {
			@fputs($f, '<'.'?php $ok_rw = "yes"; ?'.'>');
			@fclose($f);
			include("../".$tab_dossiers_rw[$i]."/".$nom_fichier_test);
			$del = @unlink("../".$tab_dossiers_rw[$i]."/".$nom_fichier_test);
		}
		$alt=$alt*(-1);
		echo "<tr class='lig$alt white_hover'>\n";
		echo "<td style='text-align:left;'>$gepiPath/$tab_dossiers_rw[$i]</td>\n";
		echo "<td>";
		if($ok_rw=='yes') {
			echo "<img src='../images/enabled.png' height='20' width='20' alt=\"Le dossier est accessible en �criture.\" />";
		}
		else {
			echo "<img src='../images/disabled.png' height='20' width='20' alt=\"Le dossier n'est pas accessible en �criture.\" />";
		}
		echo "</td>\n";
		echo "</tr>\n";

		if($tab_dossiers_rw[$i]=="documents/archives") {
			if(getSettingValue('multisite')=='y') {
				$dossier_temp='documents/archives/'.$_COOKIE['RNE'];
			}
			else {
				$dossier_temp='documents/archives/etablissement';
			}

			if(file_exists("../$dossier_temp")) {
				$ok_rw="no";
				if ($f = @fopen("../".$dossier_temp."/".$nom_fichier_test, "w")) {
					@fputs($f, '<'.'?php $ok_rw = "yes"; ?'.'>');
					@fclose($f);
					include("../".$dossier_temp."/".$nom_fichier_test);
					$del = @unlink("../".$dossier_temp."/".$nom_fichier_test);
				}
				$alt=$alt*(-1);
				echo "<tr class='lig$alt white_hover'>\n";
				echo "<td style='text-align:left;'>$gepiPath/$dossier_temp</td>\n";
				echo "<td>";
				if($ok_rw=='yes') {
					echo "<img src='../images/enabled.png' height='20' width='20' alt=\"Le dossier est accessible en �criture.\" />";
				}
				else {
					echo "<img src='../images/disabled.png' height='20' width='20' alt=\"Le dossier n'est pas accessible en �criture.\" />";
				}
				echo "</td>\n";
				echo "</tr>\n";

			}
		}
	}
	echo "</table>\n";
}


function test_ecriture_style_screen_ajout() {
	$nom_fichier='style_screen_ajout.css';
	$f=@fopen("../".$nom_fichier, "a+");
	if($f) {
		$ecriture=fwrite($f, "/* Test d'ecriture dans $nom_fichier */\n");
		fclose($f);
		if($ecriture) {return TRUE;} else {return FALSE;}
	}
	else {
		return FALSE;
	}
}

function journal_connexions($login,$duree,$page='mon_compte',$pers_id=NULL) {
	switch( $duree ) {
	case 7:
		$display_duree="une semaine";
		break;
	case 15:
		$display_duree="quinze jours";
		break;
	case 30:
		$display_duree="un mois";
		break;
	case 60:
		$display_duree="deux mois";
		break;
	case 183:
		$display_duree="six mois";
		break;
	case 365:
		$display_duree="un an";
		break;
	case 'all':
		$display_duree="le d�but";
		break;
	}

	if($page=='mon_compte') {
		echo "<h2>Journal de vos connexions depuis <b>".$display_duree."</b>**</h2>\n";
	}
	else {
		echo "<h2>Journal des connexions de ".civ_nom_prenom($login)." depuis <b>".$display_duree."</b>**</h2>\n";
	}
	$requete = '';
	if ($duree != 'all') {$requete = "and START > now() - interval " . $duree . " day";}

	$sql = "select START, SESSION_ID, REMOTE_ADDR, USER_AGENT, AUTOCLOSE, END from log where LOGIN = '".$login."' ".$requete." order by START desc";
	//echo "$sql<br />";
	$day_now   = date("d");
	$month_now = date("m");
	$year_now  = date("Y");
	$hour_now  = date("H");
	$minute_now = date("i");
	$seconde_now = date("s");
	$now = mktime($hour_now, $minute_now, $seconde_now, $month_now, $day_now, $year_now);

	echo "<ul>
<li>Les lignes en rouge signalent une tentative de connexion avec un mot de passe erron�.</li>
<li>Les lignes en orange signalent une session close pour laquelle vous ne vous �tes pas d�connect� correctement.</li>
<li>Les lignes en noir signalent une session close normalement.</li>
<li>Les lignes en vert indiquent les sessions en cours (cela peut correspondre � une connexion actuellement close mais pour laquelle vous ne vous �tes pas d�connect� correctement).</li>
</ul>
<table class='col' style='width: 90%; margin-left: auto; margin-right: auto; margin-bottom: 32px;' cellpadding='5' cellspacing='0' summary='Connexions'>
	<tr>
		<th class='col'>D�but session</th>
		<th class='col'>Fin session</th>
		<th class='col'>Adresse IP et nom de la machine cliente</th>
		<th class='col'>Navigateur</th>
	</tr>
";

	$res = sql_query($sql);
	if ($res) {
		for ($i = 0; ($row = sql_row($res, $i)); $i++)
		{
			$annee_b = substr($row[0],0,4);
			$mois_b =  substr($row[0],5,2);
			$jour_b =  substr($row[0],8,2);
			$heures_b = substr($row[0],11,2);
			$minutes_b = substr($row[0],14,2);
			$secondes_b = substr($row[0],17,2);
			$date_debut = $jour_b."/".$mois_b."/".$annee_b." � ".$heures_b." h ".$minutes_b;

			$annee_f = substr($row[5],0,4);
			$mois_f =  substr($row[5],5,2);
			$jour_f =  substr($row[5],8,2);
			$heures_f = substr($row[5],11,2);
			$minutes_f = substr($row[5],14,2);
			$secondes_f = substr($row[5],17,2);
			$date_fin = $jour_f."/".$mois_f."/".$annee_f." � ".$heures_f." h ".$minutes_f;
			$end_time = mktime($heures_f, $minutes_f, $secondes_f, $mois_f, $jour_f, $annee_f);

			$temp1 = '';
			$temp2 = '';
			if ($end_time > $now) {
				$temp1 = "<font color='green'>";
				$temp2 = "</font>";
			} else if (($row[4] == 1) or ($row[4] == 2) or ($row[4] == 3)) {
				//$temp1 = "<font color=orange>\n";
				$temp1 = "<font color='#FFA500'>";
				$temp2 = "</font>";
			} else if ($row[4] == 4) {
				$temp1 = "<b><font color='red'>";
				$temp2 = "</font></b>";

			}

			echo "<tr>\n";
			echo "<td class=\"col\">".$temp1.$date_debut.$temp2."</td>\n";
			if ($row[4] == 2) {
				echo "<td class=\"col\">".$temp1."Tentative de connexion<br />avec mot de passe erron�.".$temp2."</td>\n";
			}
			else {
				echo "<td class=\"col\">".$temp1.$date_fin.$temp2."</td>\n";
			}
			if (!(isset($active_hostbyaddr)) or ($active_hostbyaddr == "all")) {
				$result_hostbyaddr = " - ".@gethostbyaddr($row[2]);
			}
			else if ($active_hostbyaddr == "no_local") {
				if ((substr($row[2],0,3) == 127) or
					(substr($row[2],0,3) == 10.) or
					(substr($row[2],0,7) == 192.168)) {
					$result_hostbyaddr = "";
				}
				else {
					$tabip=explode(".",$row[2]);
					if(($tabip[0]==172)&&($tabip[1]>=16)&&($tabip[1]<=31)) {
						$result_hostbyaddr = "";
					}
					else {
						$result_hostbyaddr = " - ".@gethostbyaddr($row[2]);
					}
				}
			}
			else {
				$result_hostbyaddr = "";
			}

			echo "<td class=\"col\"><span class='small'>".$temp1.$row[2].$result_hostbyaddr.$temp2. "</span></td>\n";
			echo "<td class=\"col\">".$temp1. detect_browser($row[3]) .$temp2. "</td>\n";
			echo "</tr>\n";
			flush();
		}
	}


	echo "</table>\n";

	echo "<form action=\"".$_SERVER['PHP_SELF']."#connexion\" name=\"form_affiche_log\" method=\"post\">\n";

	if($page=='modify_user') {
		echo "<input type='hidden' name='user_login' value='$login' />\n";
		echo "<input type='hidden' name='journal_connexions' value='y' />\n";
	}
	elseif($page=='modify_eleve') {
		echo "<input type='hidden' name='eleve_login' value='$login' />\n";
		echo "<input type='hidden' name='journal_connexions' value='y' />\n";
	}
	elseif($page=='modify_resp') {
		echo "<input type='hidden' name='pers_id' value='$pers_id' />\n";
		echo "<input type='hidden' name='journal_connexions' value='y' />\n";
	}

	echo "Afficher le journal des connexions depuis : <select name=\"duree\" size=\"1\">\n";
	echo "<option ";
	if ($duree == 7) echo "selected";
	echo " value=7>Une semaine</option>\n";
	echo "<option ";
	if ($duree == 15) echo "selected";
	echo " value=15 >Quinze jours</option>\n";
	echo "<option ";
	if ($duree == 30) echo "selected";
	echo " value=30>Un mois</option>\n";
	echo "<option ";
	if ($duree == 60) echo "selected";
	echo " value=60>Deux mois</option>\n";
	echo "<option ";
	if ($duree == 183) echo "selected";
	echo " value=183>Six mois</option>\n";
	echo "<option ";
	if ($duree == 365) echo "selected";
	echo " value=365>Un an</option>\n";
	echo "<option ";
	if ($duree == 'all') echo "selected";
	echo " value='all'>Le d�but</option>\n";
	echo "</select>\n";
	echo "<input type=\"submit\" name=\"Valider\" value=\"Valider\" />\n";

	echo "</form>\n";

	echo "<p class='small'>** Les renseignements ci-dessus peuvent vous permettre de v�rifier qu'une connexion pirate n'a pas �t� effectu�e sur votre compte.
	Dans le cas d'une connexion inexpliqu�e, vous devez imm�diatement en avertir l'<a href=\"mailto:" . getSettingValue("gepiAdminAdress") . "\">administrateur</a>.</p>\n";
}

/**********************************************************************************************
 *                                  Fonctions Trombinoscope
 **********************************************************************************************/

/**
 * Cr�e les r�pertoires photos/RNE_Etablissement, photos/RNE_Etablissement/eleves et
 * photos/RNE_Etablissement/personnels s'ils n'existent pas
 * @return boolean TRUE si tout se passe bien ou FALSE si la cr�ation d'un r�pertoire �choue
 */
function cree_repertoire_multisite() {
  if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y') {
		// On r�cup�re le RNE de l'�tablissement
	if (!$repertoire=getSettingValue("gepiSchoolRne"))
	  return FALSE;
	//on v�rifie que le dossier photos/RNE_Etablissement n'existe pas
	if (!is_dir("../photos/".$repertoire)){
	  // On cr�e le r�pertoire photos/RNE_Etablissement
	  if (!mkdir("../photos/".$repertoire, 0700))
		return FALSE;
	  // On enregistre un fichier index.html dans photos/RNE_Etablissement
	  if (!copy  (  "../photos/index.html"  ,  "../photos/".$repertoire."/index.html" ))
		return FALSE;
	}
	//on v�rifie que le dossier photos/RNE_Etablissement/eleves n'existe pas
	if (!is_dir("../photos/".$repertoire."/eleves")){
	  // On cr�e le r�pertoire photos/RNE_Etablissement/eleves
	  if (!mkdir("../photos/".$repertoire."/eleves", 0700))
		return FALSE;
	  // On enregistre un fichier index.html dans photos/RNE_Etablissement/eleves
	  if (!copy  (  "../photos/index.html"  ,  "../photos/".$repertoire."/eleves/index.html" ))
		return FALSE;
	 }
	//on v�rifie que le dossier photos/RNE_Etablissement/personnels n'existe pas
	if (!is_dir("../photos/".$repertoire."/personnels")){
	  // On cr�e le r�pertoire photos/RNE_Etablissement/personnels
	  if (!mkdir("../photos/".$repertoire."/personnels", 0700))
		return FALSE;
	  // On enregistre un fichier index.html dans photos/RNE_Etablissement/personnels
	  if (!copy  (  "../photos/index.html"  ,  "../photos/".$repertoire."/personnels/index.html" ))
		return FALSE;
	  }
	}
	return TRUE;
}

/**
 * Recherche les �l�ves sans photos
 *
 * @return array tableau de login - nom - pr�nom - classe - classe court - eleonet
 */
function recherche_eleves_sans_photo() {
  $eleve=NULL;
  $requete_liste_eleve = "SELECT e.elenoet, e.login, e.nom, e.prenom, c.nom_complet, c.classe
	FROM eleves e, j_eleves_classes jec, classes c
	WHERE e.login = jec.login
	AND jec.id_classe = c.id
	GROUP BY e.login
	ORDER BY id_classe, nom, prenom ASC";
  $res_eleve = mysql_query($requete_liste_eleve);
  while ($row = mysql_fetch_object($res_eleve)) {
	$nom_photo = nom_photo($row->elenoet);
	if (!($nom_photo and file_exists($nom_photo))) {
	  $eleve[]=$row;
	}
  }
  return $eleve;
}

/**
 *
 * @param string $statut statut recherch�
 * @return array tableau des personnels sans photo ou NULL
 */
function recherche_personnel_sans_photo($statut='professeur') {
  $personnel=NULL;
  $requete_liste_personnel = "SELECT login,nom,prenom FROM utilisateurs u
	WHERE u.statut='".$statut."'
	ORDER BY nom, prenom ASC";
  $res_personnel = mysql_query($requete_liste_personnel);
  while ($row = mysql_fetch_object($res_personnel)) {
	$nom_photo = nom_photo($row->login,"personnels");
	if (!($nom_photo and file_exists($nom_photo))) {
	  $personnel[]=$row;
	}
  }
  return $personnel;
}

/**
 * Efface le dossier photo pass� en argument
 * @param string $photos le dossier � effacer personnels ou eleves
 * @return string L'�tat de la suppression
 */
function efface_photos($photos) {
// on liste les fichier du dossier photos/personnels ou photos/eleves
  if (!($photos=="eleves" || $photos=="personnels"))
	return ("Le dossier <strong>".$photos."</strong> n'ai pas valide.");
  if (cree_zip_archive("photos")==TRUE){
	$fichier_sup=array();
	if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y') {
		  // On r�cup�re le RNE de l'�tablissement
	  if (!$repertoire=getSettingValue("gepiSchoolRne"))
		return ("Erreur lors de la r�cup�ration du dossier �tablissement.");
	} else {
	  $repertoire="";
	}
	$folder = "../photos/".$repertoire.$photos."/";
	$dossier = opendir($folder);
	while ($Fichier = readdir($dossier)) {
	  if ($Fichier != "." && $Fichier != ".." && $Fichier != "index.html") {
		$nomFichier = $folder."".$Fichier;
		$fichier_sup[] = $nomFichier;
	  }
	}
	closedir($dossier);
	if(count($fichier_sup)==0) {
	  return ("Le dossier <strong>".$folder."</strong> ne contient pas de photo.") ;
	} else {
	  foreach ($fichier_sup as $fic_efface) {
		if(file_exists($fic_efface)) {
		  @unlink($fic_efface);
		  if(file_exists($fic_efface)) {
			return ("Le fichier  <strong>".$fic_efface."</strong> n'a pas pu �tre effac�.");
		  }
		}
	  }
	  unset ($fic_efface);
	  return ("Le dossier <strong>".$folder."</strong> a �t� vid�.") ;
	}
  }else{
	return ("Erreur lors de la cr�ation de l'archive.") ;
  }

}

/**********************************************************************************************
 *                               Fin Fonctions Trombinoscope
 **********************************************************************************************/

/**********************************************************************************************
 *                                   Fil d'Ariane
 **********************************************************************************************/
/**
 * gestion du fil d'ariane en remplissant le tableau $_SESSION['ariane']
 * @param string $lien page atteinte par le lien
 * @param string $texte texte � afficher dans le fil d'ariane
 * @return booleanTrue si tout s'est bien pass�, False sinon
 */
function suivi_ariane($lien,$texte){
  if (!isset($_SESSION['ariane'])){
	$_SESSION['ariane']['lien'][] =$lien;
	$_SESSION['ariane']['texte'][] =$texte;
	return TRUE;
  }else{
	$trouve=FALSE;
	foreach ($_SESSION['ariane']['lien'] as $index=>$lienActuel){
	  if ($trouve){
		unset ($_SESSION['ariane']['lien'][$index]);
		unset ($_SESSION['ariane']['texte'][$index]);
	  }else{
		if ($lienActuel==$lien)
		  $trouve=TRUE;
	  }
	}
	unset ($index, $lienActuel);
	if (!$trouve){
	  $_SESSION['ariane']['lien'][] =$lien;
	  $_SESSION['ariane']['texte'][] =$texte;
	}
	  return TRUE;
  }
}

/**
 * Affiche le fil d'Ariane
 *
 * @param <boolean> $validation si True,
 * une validation sera demand�e en cas de modification de la page
 * @param <texte> $themessage message � afficher lors de la confirmation
 */
function affiche_ariane($validation= FALSE,$themessage="" ){
  if (isset($_SESSION['ariane'])){
	echo "<p class='ariane'>";
	foreach ($_SESSION['ariane']['lien'] as $index=>$lienActuel){
	  if ($index!="0"){
		echo " &gt;&gt; ";
	  }
	  if ($validation){
	  echo "<a class='bold' href='".$lienActuel."' onclick='return confirm_abandon (this, change, \"".$themessage."\")' >";
	  } else {
	  echo "<a class='bold' href='".$lienActuel."' >";
	  }
		echo $_SESSION['ariane']['texte'][$index] ;
	  echo " </a>";
	}
	unset ($index,$lienActuel);
	echo "</p>";
  }
}
/**********************************************************************************************
 *                               Fin Fil d'Ariane
 **********************************************************************************************/
/**********************************************************************************************
 *                               Manipulation de fichiers
 **********************************************************************************************/

/**
 * Renvoie le chemin relatif pour remonter � la racine du site
 * @param int $niveau niveau dans l'arborescence
 * @return string chemin relatif vers la racine
 */
function path_niveau($niveau=1){
  switch ($niveau) {
	case 0:
	  $path = "./";
		  break;
	case 1:
	  $path = "../";
		  break;
	case 2:
	  $path = "../../";
	default:
	  $path = "../";
  }
  return $path;
}

/**
 *
 * @param string $dossier_a_archiver limit� � documents ou photos
 * @param int $niveau niveau dans l'arborescence de la page appelante, racine = 0
 * @return bool
 */
function cree_zip_archive($dossier_a_archiver,$niveau=1) {
  $path = path_niveau();
  $dirname = "backup/".getSettingValue("backup_directory")."/";
  define( 'PCLZIP_TEMPORARY_DIR', $path.$dirname );
  require_once($path.'lib/pclzip.lib.php');

  if (isset($dossier_a_archiver)) {
	$suffixe_zip="_le_".date("Y_m_d_\a_H\hi");
	switch ($dossier_a_archiver) {
	case "documents":
	  $chemin_stockage = $path.$dirname."_cdt".$suffixe_zip.".zip"; //l'endroit o� sera stock�e l'archive
	  $dossier_a_traiter = $path.'documents/'; //le dossier � traiter
	  $dossier_dans_archive = 'documents'; //le nom du dossier dans l'archive cr��e
	  break;
	case "photos":
	  $chemin_stockage = $path.$dirname."_photos".$suffixe_zip.".zip";
	  $dossier_a_traiter = $path.'photos/'; //le dossier � traiter
	  if (isset($GLOBALS['multisite']) AND $GLOBALS['multisite'] == 'y') {
		$dossier_a_traiter .=getSettingValue("gepiSchoolRne")."/";
	  }
	  $dossier_dans_archive = 'photos'; //le nom du dossier dans l'archive cr�er
	  break;
	default:
	  $chemin_stockage = '';
	}

	if ($chemin_stockage !='') {
	  $archive = new PclZip($chemin_stockage);
	  $v_list = $archive->create($dossier_a_traiter,
			  PCLZIP_OPT_REMOVE_PATH,$dossier_a_traiter,
			  PCLZIP_OPT_ADD_PATH, $dossier_dans_archive);
	  if ($v_list == 0) {
		 die("Error : ".$archive->errorInfo(TRUE));
		return FALSE;
	  }else {
		return TRUE;
	  }
	}
  }
}

/**
 * D�place un fichier de $source vers $dest
 * @param string $source : emplacement du fichier � d�placer
 * @param string $dest : Nouvel emplacement du fichier
 * @return bool
 */
function deplacer_upload($source, $dest) {
    $ok = @copy($source, $dest);
    if (!$ok) $ok = (@move_uploaded_file($source, $dest));
    return $ok;
}

/**
 * T�l�charge un fichier dans $dirname apr�s avoir nettoyer son nom si tout se passe bien :
 * $sav_file['name']=my_ereg_replace("[^.a-zA-Z0-9_=-]+", "_", $sav_file['name'])
 * @param array $sav_file tableau de type $_FILE["nom_du_fichier"]
 * @param string $dirname
 * @return string ok ou message d'erreur
 */
function telecharge_fichier($sav_file,$dirname,$type,$ext){
  if (!isset($sav_file['tmp_name']) or ($sav_file['tmp_name'] =='')) {
	return ("Erreur de t�l�chargement.");
  } else if (!file_exists($sav_file['tmp_name'])) {
	return ("Erreur de t�l�chargement 2.");
  } else if (!preg_match('/'.$ext.'$/',$sav_file['name'])){
	return ("Erreur : seuls les fichiers ayant l'extension .".$ext." sont autoris�s.");
  } else if ($sav_file['type']!=$type ){
	return ("Erreur : seuls les fichiers de type '".$type."' sont autoris�s.");
  } else {
	$nom_corrige = preg_replace("/[^.a-zA-Z0-9_=-]+/", "_", $sav_file['name']);
	if (!deplacer_upload($sav_file['tmp_name'], $dirname."/".$nom_corrige)) {
	  return ("Probl�me de transfert : le fichier n'a pas pu �tre transf�r� sur le r�pertoire ".$dirname);
	} else {
	  $sav_file['name']=$nom_corrige;
	  return ("ok");
	}
  }
}

/**
 * Extrait une archive Zip
 * @param string $fichier le nom du fichier � d�zipper
 * @param string $repertoire le r�pertoire de destination
 * @param int $niveau niveau dans l'arborescence de la page appelante
 * @return string ok ou message d'erreur
 */
function dezip_PclZip_fichier($fichier,$repertoire,$niveau=1){
  $path = path_niveau();
  require_once($path.'lib/pclzip.lib.php');
  $archive = new PclZip($fichier);
  //if ($archive->extract() == 0) {
if ($archive->extract(PCLZIP_OPT_PATH, $repertoire) == 0) {
	return "Une erreur a �t� rencontr�e lors de l'extraction du fichier zip";
  }else {
	return "ok";
  }
}

/**********************************************************************************************
 *                              Fin Manipulation de fichiers
 **********************************************************************************************/

function check_droit_acces($id,$statut) {
    $tab_id = explode("?",$id);
    $query_droits = @mysql_query("SELECT * FROM droits WHERE id='$tab_id[0]'");
    $droit = @mysql_result($query_droits, 0, $statut);
    if ($droit == "V") {
        return "1";
    } else {
        return "0";
    }
}


function lignes_options_select_eleve($id_classe,$login_eleve_courant,$sql_ele="") {
	if($sql_ele!="") {
		$sql=$sql_ele;
	}
	else {
		$sql="SELECT DISTINCT jec.login,e.nom,e.prenom FROM j_eleves_classes jec, eleves e
							WHERE jec.login=e.login AND
								jec.id_classe='$id_classe'
							ORDER BY e.nom,e.prenom";
	}
	//echo "$sql<br />";
	//echo "\$login_eleve=$login_eleve<br />";
	$res_ele_tmp=mysql_query($sql);
	$chaine_options_login_eleves="";
	$cpt_eleve=0;
	$num_eleve=-1;
	if(mysql_num_rows($res_ele_tmp)>0){
		$login_eleve_prec=0;
		$login_eleve_suiv=0;
		$temoin_tmp=0;
		while($lig_ele_tmp=mysql_fetch_object($res_ele_tmp)){
			if($lig_ele_tmp->login==$login_eleve_courant){
				$chaine_options_login_eleves.="<option value='$lig_ele_tmp->login' selected='TRUE'>$lig_ele_tmp->nom $lig_ele_tmp->prenom</option>\n";
	
				$num_eleve=$cpt_eleve;
	
				$temoin_tmp=1;
				if($lig_ele_tmp=mysql_fetch_object($res_ele_tmp)){
					$login_eleve_suiv=$lig_ele_tmp->login;
					$chaine_options_login_eleves.="<option value='$lig_ele_tmp->login'>$lig_ele_tmp->nom $lig_ele_tmp->prenom</option>\n";
				}
				else{
					$login_eleve_suiv=0;
				}
			}
			else{
				$chaine_options_login_eleves.="<option value='$lig_ele_tmp->login'>$lig_ele_tmp->nom $lig_ele_tmp->prenom</option>\n";
			}
	
			if($temoin_tmp==0){
				$login_eleve_prec=$lig_ele_tmp->login;
			}
			$cpt_eleve++;
		}
	}

	return $chaine_options_login_eleves;
}

/**
 *
 * V�rifie si un utilisateur est prof principal (gepi_prof_suivi)
 *
 * @var string $login_prof login de l'utilisateur � tester
 * @var entier $id_classe identifiant de la classe (si vide, on teste juste si le prof est PP (�ventuellement pour un �l�ve particulier si login_eleve est non vide))
 * @var string $login_eleve login de l'�l�ve � tester (si vide, on teste juste si le prof est PP (�ventuellement pour la classe si id_classe est non vide))
 *
 * @return boolean TRUE/FALSE si l'utilisateur est PP avec les param�tres choisis
 *
 *
 */
function is_pp($login_prof,$id_classe="",$login_eleve="") {
	$retour=FALSE;
	if($login_eleve=='') {
		$sql="SELECT 1=1 FROM j_eleves_professeurs WHERE ";
		if($id_classe!="") {$sql.="id_classe='$id_classe' AND ";}
		$sql.="professeur='$login_prof';";
	}
	else {
		$sql="SELECT 1=1 FROM j_eleves_professeurs WHERE ";
		if($id_classe!="") {$sql.="id_classe='$id_classe' AND ";}
		$sql.="professeur='$login_prof' AND login='$login_eleve';";
	}
	$test=mysql_query($sql);
	if(mysql_num_rows($test)>0) {$retour=TRUE;}

	return $retour;
}

/**
 *
 * V�rifie qu'un utilisateur a le droit de voir la page en lien
 *
 * @var string $id l'adresse de la page
 * telle qu'enregistr�e dans la base droits
 * @var string $statut le statut de l'utilisateur
 *
 * @return entier 1 si l'utilisateur a le droit de voir la page
 * 0 sinon
 *
 *
 */
function acces($id,$statut) 
{ 
	if ($_SESSION['statut']!='autre') {
		$tab_id = explode("?",$id);
		$query_droits = @mysql_query("SELECT * FROM droits WHERE id='$tab_id[0]'");
		$droit = @mysql_result($query_droits, 0, $statut);
		if ($droit == "V") {
			return "1";
		} else {
			return "0";
		}
	} else {
		$sql="SELECT ds.autorisation FROM `droits_speciaux` ds,  `droits_utilisateurs` du
					WHERE (ds.nom_fichier='".$id."'
						AND ds.id_statut=du.id_statut
						AND du.login_user='".$_SESSION['login']."');" ;
		$result=mysql_query($sql);
		if (!$result) {
			return FALSE;
		} else {
			$row = mysql_fetch_row($result) ;
			if ($row[0]=='V' || $row[0]=='v'){
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
}

function ajout_index_sous_dossiers($dossier) {
	global $niveau_arbo;

	$nb_creation=0;
	$nb_erreur=0;
	$nb_fich_existant=0;

	$retour="";

	//$dossier="../documents";
	$dir= opendir($dossier);
	if(!$dir) {
		$retour.="<p style='color:red'>Erreur lors de l'acc�s au dossier '$dossier'.</p>\n";
	}
	else {
		$retour.="<p style='color:green'>Succ�s de l'acc�s au dossier '$dossier'.</p>\n";
		while($entree=@readdir($dir)) {
			//$retour.="$dossier/$entree<br />\n";
			if(is_dir($dossier.'/'.$entree)&&($entree!='.')&&($entree!='..')) {
				if(!file_exists($dossier."/".$entree."/index.html")) {
					if ($f = @fopen($dossier.'/'.$entree."/index.html", "w")) {
						if((!isset($niveau_arbo))||($niveau_arbo==1)) {
							@fputs($f, '<script type="text/javascript">document.location.replace("../login.php")</script>');
						}
						elseif($niveau_arbo==0) {
							@fputs($f, '<script type="text/javascript">document.location.replace("./login.php")</script>');
						}
						elseif($niveau_arbo==2) {
							@fputs($f, '<script type="text/javascript">document.location.replace("../../login.php")</script>');
						}
						else {
							@fputs($f, '<script type="text/javascript">document.location.replace("../../../login.php")</script>');
						}
						@fclose($f);
						$nb_creation++;
					}
					else {
						$retour.="<span style='color:red'>Erreur lors de la cr�ation de '$dir/$entree/index.html'.</span><br />\n";
						$nb_erreur++;
					}
				}
				else {
					$nb_fich_existant++;
				}
			}
		}

		if($nb_erreur>0) {
			$retour.="<p style='color:red'>$nb_erreur erreur(s) lors du traitement.</p>\n";
		}
		else {
			$retour.="<p style='color:green'>Aucune erreur lors de la cr�ation des fichiers index.html</p>\n";
		}
	
		if($nb_creation>0) {
			$retour.="<p style='color:green'>Cr�ation de $nb_creation fichier(s) index.html</p>\n";
		}
		else {
			$retour.="<p style='color:green'>Aucune cr�ation de fichiers index.html n'a �t� effectu�e.</p>\n";
		}
		$retour.="<p style='color:blue'>Il existait avant l'op�ration $nb_fich_existant fichier(s) index.html</p>\n";
	}

	return $retour;
}

// M�thode pour envoyer les en-t�tes HTTP n�cessaires au t�l�chargement de fichier.
// Le content-type est obligatoire, ainsi que le nom du fichier.
function send_file_download_headers($content_type, $filename, $content_disposition = 'attachment') {

  //header('Content-Encoding: utf-8');
  header('Content-Type: '.$content_type);
  header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  header('Content-Disposition: '.$content_disposition.'; filename="' . $filename . '"');
  
  // Contournement d'un bug IE lors d'un t�l�chargement en HTTPS...
  if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)) {
    header('Pragma: private');
    header('Cache-Control: private, must-revalidate');
  } else {
    header('Pragma: no-cache');
  }
}


function affiche_infos_actions() {
	$sql="SELECT ia.* FROM infos_actions ia, infos_actions_destinataires iad WHERE
	ia.id=iad.id_info AND
	((iad.nature='individu' AND iad.valeur='".$_SESSION['login']."') OR
	(iad.nature='statut' AND iad.valeur='".$_SESSION['statut']."'));";
	//echo "$sql<br />";
	$res=mysql_query($sql);
	$chaine_id="";
	if(mysql_num_rows($res)>0) {
		echo "<div id='div_infos_actions' style='width: 60%; border: 2px solid red; padding:3px; margin-left: 20%;'>\n";
		echo "<div id='info_action_titre' style='font-weight: bold;' class='infobulle_entete'>\n";
			echo "<div id='info_action_pliage' style='float:right; width: 1em'>\n";
			echo "<a href=\"javascript:div_alterne_affichage('conteneur')\"><span id='img_pliage_conteneur'><img src='images/icons/remove.png' width='16' height='16' /></span></a>";
			echo "</div>\n";
			echo "Actions en attente";
		echo "</div>\n";

		echo "<div id='info_action_corps_conteneur'>\n";

		$cpt_id=0;
		while($lig=mysql_fetch_object($res)) {
			echo "<div id='info_action_$lig->id' style='border: 1px solid black; margin:2px;'>\n";
				echo "<div id='info_action_titre_$lig->id' style='font-weight: bold;' class='infobulle_entete'>\n";
					echo "<div id='info_action_pliage_$lig->id' style='float:right; width: 1em'>\n";
					echo "<a href=\"javascript:div_alterne_affichage('$lig->id')\"><span id='img_pliage_$lig->id'><img src='images/icons/remove.png' width='16' height='16' /></span></a>";
					echo "</div>\n";
					echo $lig->titre;
				echo "</div>\n";

				echo "<div id='info_action_corps_$lig->id' style='padding:3px;' class='infobulle_corps'>\n";
					echo "<div style='float:right; width: 9em; text-align: right;'>\n";
					//echo "<a href=\"".$_SERVER['PHP_SELF']."?del_id_info=$lig->id".add_token_in_url()."\" onclick=\"return confirmlink(this, '".traitement_magic_quotes($lig->description)."', 'Etes-vous s�r de vouloir supprimer ".traitement_magic_quotes($lig->titre)."')\">Supprimer</span></a>";
					echo "<a href=\"".$_SERVER['PHP_SELF']."?del_id_info=$lig->id".add_token_in_url()."\" onclick=\"return confirmlink(this, '".traitement_magic_quotes($lig->titre)."', 'Etes-vous s�r de vouloir supprimer ".traitement_magic_quotes($lig->titre)."')\">Supprimer</span></a>";
					echo "</div>\n";

					echo nl2br($lig->description);
				echo "</div>\n";
			echo "</div>\n";
			if($cpt_id>0) {$chaine_id.=", ";}
			$chaine_id.="'$lig->id'";
			$cpt_id++;
		}
		echo "</div>\n";
		echo "</div>\n";

		echo "<script type='text/javascript'>
	function div_alterne_affichage(id) {
		if(document.getElementById('info_action_corps_'+id)) {
			if(document.getElementById('info_action_corps_'+id).style.display=='none') {
				document.getElementById('info_action_corps_'+id).style.display='';
				document.getElementById('img_pliage_'+id).innerHTML='<img src=\'images/icons/remove.png\' width=\'16\' height=\'16\' />'
			}
			else {
				document.getElementById('info_action_corps_'+id).style.display='none';
				document.getElementById('img_pliage_'+id).innerHTML='<img src=\'images/icons/add.png\' width=\'16\' height=\'16\' />'
			}
		}
	}

	chaine_id_action=new Array($chaine_id);
	for(i=0;i<chaine_id_action.length;i++) {
		id_a=chaine_id_action[i];
		if(document.getElementById('info_action_corps_'+id_a)) {
			div_alterne_affichage(id_a);
		}
	}
</script>\n";
	}
}

/**
 *
 * Enregistrer une action � effectuer pour qu'elle soit par la suite affich�e en page d'accueil pour tels ou tels utilisateurs
 *
 * @var string $titre titre de l'action/info
 * @var string $description le d�tail de l'action � effectuer avec autant que possible un lien vers la page et param�tres utiles pour l'action
 * @var string $destinataire le tableau des login ou statuts des utilisateurs pour lesquels l'affichage sera r�alis�
 * @var string $mode vaut 'individu' si $destinataire d�signe des logins et 'statut' si ce sont des statuts
 *
 * @return bolean TRUE si l'enregistrement s'est bien effectu�
 * FALSE sinon
 *
 *
 */
function enregistre_infos_actions($titre,$texte,$destinataire,$mode) {
	if(is_array($destinataire)) {
		$tab_dest=$destinataire;
	}
	else {
		$tab_dest=array($destinataire);
	}

	$sql="INSERT INTO infos_actions SET titre='".addslashes($titre)."', description='".addslashes($texte)."', date=NOW();";
	$insert=mysql_query($sql);
	if(!$insert) {
		return FALSE;
	}
	else {
		//$return=TRUE;
		$id_info=mysql_insert_id();
		$return=$id_info;
		for($loop=0;$loop<count($tab_dest);$loop++) {
			$sql="INSERT INTO infos_actions_destinataires SET id_info='$id_info', nature='$mode', valeur='$tab_dest[$loop]';";
			$insert=mysql_query($sql);
			if(!$insert) {
				$return=FALSE;
			}
		}

		return $return;
	}
}

function del_info_action($id_info) {
	// Dans le cas des infos destin�es � un statut... c'est le premier qui supprime qui vire pour tout le monde?
	// S'il s'agit bien de loguer des actions � effectuer... elle ne doit �tre effectu�e qu'une fois.
	// Ou alors il faudrait ajouter des champs pour marquer les actions comme effectu�es et n'afficher par d�faut que les actions non effectu�es

	$sql="SELECT 1=1 FROM infos_actions_destinataires WHERE id_info='$id_info' AND ((nature='statut' AND valeur='".$_SESSION['statut']."') OR (nature='individu' AND valeur='".$_SESSION['login']."'));";
	$test=mysql_query($sql);
	if(mysql_num_rows($test)>0) {
		$sql="DELETE FROM infos_actions_destinataires WHERE id_info='$id_info';";
		$del=mysql_query($sql);
		if(!$del) {
			return FALSE;
		}
		else {
			$sql="DELETE FROM infos_actions WHERE id='$id_info';";
			$del=mysql_query($sql);
			if(!$del) {
				return FALSE;
			}
			else {
				return TRUE;
			}
		}
	}
}

function affiche_date_sortie($date_sortie) {
	//affiche sous la forme JJ/MM/AAAA la date de sortie d'un �l�ve pr�sente dans la base comme un timestamp
    $eleve_date_de_sortie_time=strtotime($date_sortie);
	//r�cup�ration du jour, du mois et de l'ann�e
	$eleve_date_sortie_jour=date('j', $eleve_date_de_sortie_time); 
	$eleve_date_sortie_mois=date('m', $eleve_date_de_sortie_time);
	$eleve_date_sortie_annee=date('Y', $eleve_date_de_sortie_time); 
	return $eleve_date_sortie_jour."/".$eleve_date_sortie_mois."/".$eleve_date_sortie_annee;
}

function traite_date_sortie_to_timestamp($date_sortie) {
	//Traite une chaine de caract�res JJ/MM/AAAA vers un timestamp AAAA-MM-JJ 00:00:00
	$date=explode("/", $date_sortie);
	$jour = $date[0];
	$mois = $date[1];
	$annee = $date[2];

	return $annee."-".$mois."-".$jour." 00:00:00"; 
}

function affiche_acces_cdt() {
	$retour="";

	$tab_statuts=array('professeur', 'administrateur', 'scolarite');
	if(in_array($_SESSION['statut'], $tab_statuts)) {
		$sql="SELECT a.* FROM acces_cdt a ORDER BY date2;";
		//echo "$sql<br />";
		$res=mysql_query($sql);
		$chaine_id="";
		if(mysql_num_rows($res)>0) {
			$visible="y";
			if($_SESSION['statut']=='professeur') {
				$visible="n";
				$sql="SELECT ag.id_acces FROM acces_cdt_groupes ag, j_groupes_professeurs jgp WHERE jgp.id_groupe=ag.id_groupe AND jgp.login='".$_SESSION['login']."';";
				$res2=mysql_query($sql);
				if(mysql_num_rows($res2)>0) {
					$visible="y";
					$tab_id_acces=array();
					while($lig2=mysql_fetch_object($res2)) {
						$tab_id_acces[]=$lig2->id_acces;
					}
				}
			}
	
			if($visible=="y") {
				$retour.="<div id='div_infos_acces_cdt' style='width: 60%; border: 2px solid red; padding:3px; margin-left: 20%; margin-top:3px;'>\n";
				$retour.="<div id='info_acces_cdt_titre' style='font-weight: bold;' class='infobulle_entete'>\n";
					$retour.="<div id='info_acces_cdt_pliage' style='float:right; width: 1em'>\n";
					$retour.="<a href=\"javascript:div_alterne_affichage_acces_cdt('conteneur')\"><span id='img_pliage_acces_cdt_conteneur'><img src='images/icons/remove.png' width='16' height='16' /></span></a>";
					$retour.="</div>\n";
					$retour.="Acc�s ouvert � des CDT";
				$retour.="</div>\n";
		
				$retour.="<div id='info_acces_cdt_corps_conteneur'>\n";
		
				$cpt_id=0;
				while($lig=mysql_fetch_object($res)) {
					$visible="y";
					if(($_SESSION['statut']=='professeur')&&(!in_array($lig->id,$tab_id_acces))) {
						$visible="n";
					}
	
					if($visible=="y") {
						$retour.="<div id='info_acces_cdt_$lig->id' style='border: 1px solid black; margin:2px;'>\n";
							$retour.="<div id='info_acces_cdt_titre_$lig->id' style='font-weight: bold;' class='infobulle_entete'>\n";
								$retour.="<div id='info_acces_cdt_pliage_$lig->id' style='float:right; width: 1em'>\n";
								$retour.="<a href=\"javascript:div_alterne_affichage_acces_cdt('$lig->id')\"><span id='img_pliage_acces_cdt_$lig->id'><img src='images/icons/remove.png' width='16' height='16' /></span></a>";
								$retour.="</div>\n";
								$retour.="Acc�s CDT jusqu'au ".formate_date($lig->date2);
							$retour.="</div>\n";
			
							$retour.="<div id='info_acces_cdt_corps_$lig->id' style='padding:3px;' class='infobulle_corps'>\n";
								if(($_SESSION['statut']=='administrateur')||($_SESSION['statut']=='scolarite')) {
									$retour.="<div style='float:right; width: 9em; text-align: right;'>\n";
									$retour.="<a href=\"".$_SERVER['PHP_SELF']."?del_id_acces_cdt=$lig->id".add_token_in_url()."\" onclick=\"return confirmlink(this, '".traitement_magic_quotes($lig->description)."', 'Etes-vous s�r de vouloir supprimer cet acc�s')\">Supprimer l'acc�s</span></a>";
									$retour.="</div>\n";
								}
	
								$retour.="<p><b>L'acc�s a �t� ouvert pour le motif suivant&nbsp;:</b><br />";
								$retour.=preg_replace("/\\\\r\\\\n/","<br />",$lig->description);
								$retour.="</p>\n";
	
								$chaine_enseignements="<ul>";
								$sql="SELECT id_groupe FROM acces_cdt_groupes WHERE id_acces='$lig->id';";
								$res3=mysql_query($sql);
								if(mysql_num_rows($res3)>0) {
									$tab_champs=array('classes', 'professeurs');
									while($lig3=mysql_fetch_object($res3)) {
										$current_group=get_group($lig3->id_groupe);
	
										$chaine_profs="";
										$cpt=0;
										foreach($current_group['profs']['users'] as $login_prof => $current_prof) {
											if($cpt>0) {$chaine_profs.=", ";}
											$chaine_profs.=$current_prof['civilite']." ".$current_prof['nom']." ".$current_prof['prenom'];
											$cpt++;
										}
	
										$chaine_enseignements.="<li>";
										$chaine_enseignements.=$current_group['name']." (<i>".$current_group['description']."</i>) en ".$current_group['classlist_string']." (<i>".$chaine_profs."</i>)";
										//$chaine_enseignements.="<br />\n";
										$chaine_enseignements.="</li>\n";
									}
								}
								$chaine_enseignements.="</ul>";
								//$retour.="</p>\n";
	
								$retour.="<p>Les CDT accessibles � l'adresse <a href='$lig->chemin' target='_blank'>$lig->chemin</a> sont&nbsp;:<br />".$chaine_enseignements."</p>";
							$retour.="</div>\n";
						$retour.="</div>\n";
						if($cpt_id>0) {$chaine_id.=", ";}
						$chaine_id.="'$lig->id'";
						$cpt_id++;
					}
				}
				$retour.="</div>\n";
				$retour.="</div>\n";
		
				$retour.="<script type='text/javascript'>
			function div_alterne_affichage_acces_cdt(id) {
				if(document.getElementById('info_acces_cdt_corps_'+id)) {
					if(document.getElementById('info_acces_cdt_corps_'+id).style.display=='none') {
						document.getElementById('info_acces_cdt_corps_'+id).style.display='';
						document.getElementById('img_pliage_acces_cdt_'+id).innerHTML='<img src=\'images/icons/remove.png\' width=\'16\' height=\'16\' />'
					}
					else {
						document.getElementById('info_acces_cdt_corps_'+id).style.display='none';
						document.getElementById('img_pliage_acces_cdt_'+id).innerHTML='<img src=\'images/icons/add.png\' width=\'16\' height=\'16\' />'
					}
				}
			}
		
			chaine_id_acces_cdt=new Array($chaine_id);
			for(i=0;i<chaine_id_acces_cdt.length;i++) {
				id_a=chaine_id_acces_cdt[i];
				if(document.getElementById('info_acces_cdt_corps_'+id_a)) {
					div_alterne_affichage_acces_cdt(id_a);
				}
			}
		</script>\n";
			}
		}
	}
	echo $retour;
}

function del_acces_cdt($id_acces) {

	$sql="SELECT * FROM acces_cdt WHERE id='$id_acces';";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		$lig=mysql_fetch_object($res);

		$chemin=preg_replace("#/index.(html|php)#","",$lig->chemin);
		if((!preg_match("#^documents/acces_cdt_#",$chemin))||(strstr($chemin,".."))) {
			echo "<p><span style='color:red'>Chemin $chemin invalide</span></p>";
			return FALSE;
		}
		else {
			$suppr=deltree($chemin,TRUE);
			if(!$suppr) {
				echo "<p><span style='color:red'>Erreur lors de la suppression de $chemin</span></p>";
				return FALSE;
			}
			else {
				$sql="DELETE FROM acces_cdt_groupes WHERE id_acces='$id_acces';";
				$del=mysql_query($sql);
				if(!$del) {
					echo "<p><span style='color:red'>Erreur lors de la suppression des groupes associ�s � l'acc�s n�$id_acces</span></p>";
					return FALSE;
				}
				else {
					$sql="DELETE FROM acces_cdt WHERE id='$id_acces';";
					$del=mysql_query($sql);
					if(!$del) {
						echo "<p><span style='color:red'>Erreur lors de la suppression de l'acc�s n�$id_acces</span></p>";
						return FALSE;
					}
					else {
						return TRUE;
					}
				}
			}
		}
	}
}

//=======================================================
// Fonction r�cup�r�e dans /mod_ooo/lib/lib_mod_ooo.php

//$repaussi==TRUE ~> efface aussi $rep
//retourne TRUE si tout s'est bien pass�,
//FALSE si un fichier est rest� (probl�me de permission ou attribut lecture sous Win
//dans tous les cas, le maximum possible est supprim�.
function deltree($rep,$repaussi=TRUE) {
	static $niv=0;
	$niv++;
	if (!is_dir($rep)) {return FALSE;}
	$handle=opendir($rep);
	if (!$handle) {return FALSE;}
	while ($entree=readdir($handle)) {
		if (is_dir($rep.'/'.$entree)) {
			if ($entree!='.' && $entree!='..') {
				$ok=deltree($rep.'/'.$entree);
			}
			else {$ok=TRUE;}
		}
		else {
			$ok=@unlink($rep.'/'.$entree);
		}
	}
	closedir($handle);
	$niv--;
	if ($niv || $repaussi) $ok &= @rmdir($rep);
	return $ok;
}
//=======================================================

function check_mail($email,$mode='simple') {
	if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/" , $email)) {
		return FALSE;
	}
	else {
		if(($mode=='simple')||(!function_exists('checkdnsrr'))) {
			return TRUE;
		}
		else {
			$tab=explode('@', $email);
			if(checkdnsrr($tab[1], 'MX')) {return TRUE;}
			elseif(checkdnsrr($tab[1], 'A')) {return TRUE;}
		}
	}
}

// Fonction destin�e � prendre une date mysql aaaa-mm-jj HH:MM:SS et � retourner une date au format jj/mm/aaaa
function get_date_slash_from_mysql_date($mysql_date) {
	$tmp_tab=explode(" ",$mysql_date);
	if(isset($tmp_tab[0])) {
		$tmp_tab2=explode("-",$tmp_tab[0]);
		if(isset($tmp_tab2[2])) {
			return $tmp_tab2[2]."/".$tmp_tab2[1]."/".$tmp_tab2[0];
		}
		else {
			return "Date '".$tmp_tab[0]."' mal format�e?";
		}
	}
	else {
		return "Date '$mysql_date' mal format�e?";
	}
}

// Fonction destin�e � prendre une date mysql aaaa-mm-jj HH:MM:SS et � retourner une heure au format HH:MM
function get_heure_2pt_minute_from_mysql_date($mysql_date) {
	$tmp_tab=explode(" ",$mysql_date);
	if(isset($tmp_tab[1])) {
		$tmp_tab2=explode(":",$tmp_tab[1]);
		if(isset($tmp_tab2[1])) {
			return $tmp_tab2[0].":".$tmp_tab2[1];
		}
		else {
			return "Heure '".$tmp_tab[1]."' mal format�e?";
		}
	}
	else {
		return "Date '$mysql_date' mal format�e?";
	}
}

function get_date_heure_from_mysql_date($mysql_date) {
	return get_date_slash_from_mysql_date($mysql_date)." ".get_heure_2pt_minute_from_mysql_date($mysql_date);
}

function mysql_date_to_unix_timestamp($mysql_date) {
	//echo "\$mysql_date=$mysql_date<br />";
	$tmp_tab=explode(" ",$mysql_date);
	$tmp_tab2=explode("-",$tmp_tab[0]);
	if((!isset($tmp_tab[1]))||(!isset($tmp_tab2[2]))) {
		// Ces retours ne sont pas adapt�s... on fait g�n�ralement une comparaison sur le retour de cette fonction
		return "Date '$mysql_date' mal format�e?";
	}
	else {
		$tmp_tab3=explode(":",$tmp_tab[1]);

		if(!isset($tmp_tab3[2])) {
			// Ces retours ne sont pas adapt�s... on fait g�n�ralement une comparaison sur le retour de cette fonction
			return "Date '$mysql_date' mal format�e?";
		}
		else {
			$jour=$tmp_tab2[2];
			$mois=$tmp_tab2[1];
			$annee=$tmp_tab2[0];
		
			$heure=$tmp_tab3[0];
			$min=$tmp_tab3[1];
			$sec=$tmp_tab3[2];
		
			//echo "mktime($heure,$min,$sec,$mois,$jour,$annee)<br />\n";
			return mktime($heure,$min,$sec,$mois,$jour,$annee);
		}
	}
}

function get_tab_prof_suivi($id_classe) {
	$tab=array();

	$sql="SELECT DISTINCT jep.professeur FROM j_eleves_professeurs jep, j_eleves_classes jec WHERE jec.id_classe='$id_classe' AND jec.login=jep.login ORDER BY professeur;";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		while($lig=mysql_fetch_object($res)) {
			$tab[]=$lig->professeur;
		}
	}

	return $tab;
}

function get_cn_from_id_groupe_periode_num($id_groupe, $periode_num) {
	$id_cahier_notes="";

	$sql="SELECT id_cahier_notes FROM cn_cahier_notes WHERE id_groupe='$id_groupe' AND periode='$periode_num';";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		$lig=mysql_fetch_object($res);
		$id_cahier_notes=$lig->id_cahier_notes;
	}
	return $id_cahier_notes;
}


function message_accueil_utilisateur($login_destinataire,$texte,$date_debut=0,$date_fin=0,$date_decompte=0)
{
	// Afficher un message sur la page d'accueil du destinataire (ML 5/2011)
	
	// Les param�tres :
	//	$login_destinataire : login du destinataire (obligatoire)
	//	$texte : texte du message contenant �ventuellement des balises HTML et cod� en iso-8859-1  (obligatoire)
	//	$date_debut : date � partir de laquelle est affich� le message (timestamp, optionnel)
	//	$date_fin : date � laquelle le message n'est plus affich� (timestamp, optionnel)
	//	$date_decompte : date butoir du d�compte, la cha�ne _DECOMPTE_ dans $texte est remplac�e par un d�compte (timestamp, optionnel)
	
	// Retour :
	// TRUE ou FALSE selon que le massage a �t� enregistr� ou pas
	
	// Les appels possibles
	//	message_accueil_utilisateur("UNTEL","Bonjour Untel") : affiche le message "Bonjour Untel" sur la page d'accueil du destinataire de login "UNTEL" d�s l'appel de la fonction, pour une dur�e de 7 jours, avec d�compte sur le 7i�me jour
	//	message_accueil_utilisateur("UNTEL","Bonjour Untel",130674844) : affiche le message "Bonjour Untel" sur la page du destinataire de login "UNTEL" � partir de la date 130674844, pour une dur�e de 7 jours, avec d�compte sur le 7i�me jour	
	//	message_accueil_utilisateur("UNTEL","Bonjour Untel",130674844,130684567) : affiche le message "Bonjour Untel" sur la page du destinataire de login "UNTEL" � partir de la date 130674844, jusqu'� la date 130684567, avec d�compte sur la date 130684567
	//	message_accueil_utilisateur("UNTEL","Bonjour Untel",130674844,130684567,130690844) : affiche le message "Bonjour Untel" sur la page du destinataire de login "UNTEL" � partir de la date 130674844, jusqu'� la date 130684567, avec d�compte sur la date 130690844

	// On arrondit le timestamp d'appel � l'heure (pas n�ceassaire mais pour l'esth�tique)
	$t_appel=time()-(time()%3600);
	// suivant le nombre de param�tres pass�s :
	switch (func_num_args())
		{
		case 3:
			$date_fin=$date_debut + 3600*24*7;
			$date_decompte=$date_fin;
			break;
		case 4:
			$date_decompte=$date_fin;
			break;
		case 5:
			break;
		default :
			// valeurs par d�faut
			$date_debut=$t_appel;
			$date_fin=$t_appel + 3600*24*7;
			$date_decompte=$date_fin;		
		}
	$r_sql="INSERT INTO `messages` values('','".addslashes($texte)."','".$date_debut."','".$date_fin."','".$_SESSION['login']."','_','".$login_destinataire."','".$date_decompte."')";
	return mysql_query($r_sql);
}

function array_to_chaine($tableau) {
	$chaine="";
	$cpt=0;
	foreach($tableau as $key => $value) {
		if($cpt>0) {$chaine.=", ";}
		$chaine.="'$value'";
		$cpt++;
	}
	return $chaine;
}

function suppression_sauts_de_lignes_surnumeraires($chaine) {
	$retour=preg_replace('/(\\\r\\\n)+/',"\r\n",$chaine);
	$retour=preg_replace('/(\\\r)+/',"\r",$retour);
	$retour=preg_replace('/(\\\n)+/',"\n",$retour);
	return $retour;
}

function nb_saisies_bulletin($type, $id_groupe, $periode_num, $mode="") {
	$retour="";

	if($type=="notes") {
		$sql="SELECT 1=1 FROM matieres_notes WHERE id_groupe='".$id_groupe."' AND periode='".$periode_num."';";
	}
	else {
		$sql="SELECT 1=1 FROM matieres_appreciations WHERE id_groupe='".$id_groupe."' AND periode='".$periode_num."';";
	}
	$test=mysql_query($sql);
	$nb_saisies_bulletin=mysql_num_rows($test);

	$tab_champs=array('eleves');
	$current_group=get_group($id_groupe, $tab_champs);
	$effectif_groupe=count($current_group["eleves"][$periode_num]["users"]);

	if($mode=="couleur") {
		if($nb_saisies_bulletin==$effectif_groupe){
			$retour="<span style='font-size: x-small;' title='Saisies compl�tes'>";
			$retour.="($nb_saisies_bulletin/$effectif_groupe)";
			$retour.="</span>";
		}
		else {
			$retour="<span style='font-size: x-small; background-color: orangered;' title='Saisies incompl�tes ou non encore effectu�es'>";
			$retour.="($nb_saisies_bulletin/$effectif_groupe)";
			$retour.="</span>";
		}
	}
	else {
		$retour="($nb_saisies_bulletin/$effectif_groupe)";
	}

	return $retour;
}

function creation_index_redir_login($chemin_relatif,$niveau_arbo=1) {
	$retour=TRUE;

	if($niveau_arbo==0) {
		$pref=".";
	}
	else {
		$pref="";
		for($i=0;$i<$niveau_arbo;$i++) {
			if($i>0) {
				$pref.="/";
			}
			$pref.="..";
		}
	}

	$fich=fopen($chemin_relatif."/index.html","w+");
	if(!$fich) {
		$retour=FALSE;
	}
	else {
		$res=fwrite($fich,'<html><head><script type="text/javascript">
    document.location.replace("'.$pref.'/login.php")
</script></head></html>
');
		if(!$res) {
			$retour=FALSE;
		}
		fclose($fich);
	}

	return $retour;
}

function get_tab_file($path,$tab_exclusion=array(".", "..", "remove.txt", ".htaccess", ".htpasswd", "index.html")) {
	$tab_file = array();

	$handle=opendir($path);
	$n=0;
	while ($file = readdir($handle)) {
		if (!in_array(strtolower($file), $tab_exclusion)) {
			$tab_file[] = $file;
			$n++;
		}
	}
	closedir($handle);
	//arsort($tab_file);
	rsort($tab_file);

	return $tab_file;
}

function traduction_mention($code) {
	global $tableau_des_mentions_sur_le_bulletin;

	if((!is_array($tableau_des_mentions_sur_le_bulletin))||(count($tableau_des_mentions_sur_le_bulletin)==0)) {
		$tableau_des_mentions_sur_le_bulletin=get_mentions();
	}

	$retour="";
	if(!isset($tableau_des_mentions_sur_le_bulletin[$code])) {$retour="-";}
	else {$retour=$tableau_des_mentions_sur_le_bulletin[$code];}

	return $retour;
}


function get_mentions($id_classe=NULL) {
	$tab=array();
	if(!isset($id_classe)) {
		$sql="SELECT * FROM mentions ORDER BY id;";
	}
	else {
		$sql="SELECT m.* FROM mentions m, j_mentions_classes j WHERE j.id_mention=m.id AND j.id_classe='$id_classe' ORDER BY j.ordre, m.mention, m.id;";
	}
	//echo "$sql<br />";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		while($lig=mysql_fetch_object($res)) {
			$tab[$lig->id]=$lig->mention;
		}
	}
	return $tab;
}

// Pour interdire la suppression d'une mention saisie pour un �l�ve
function get_tab_mentions_affectees($id_classe=NULL) {
	$tab=array();
	if(!isset($id_classe)) {
		$sql="SELECT DISTINCT j.id_mention FROM j_mentions_classes j, avis_conseil_classe a WHERE a.id_mention=j.id_mention;";
	}
	else {
		$sql="SELECT DISTINCT j.id_mention FROM j_mentions_classes j, avis_conseil_classe a, j_eleves_classes jec WHERE a.id_mention=j.id_mention AND j.id_classe=jec.id_classe AND jec.periode=a.periode AND jec.login=a.login AND j.id_classe='$id_classe';";
	}
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		while($lig=mysql_fetch_object($res)) {
			$tab[]=$lig->id_mention;
		}
	}
	return $tab;
}

function champ_select_mention($nom_champ_select,$id_classe,$id_mention_selected="") {

	$tab_mentions=get_mentions($id_classe);
	//$retour="$id_mention_selected<select name='$nom_champ_select'>\n";
	$retour="<select name='$nom_champ_select' id='$nom_champ_select'>\n";
	$retour.="<option value=''";
	if(($id_mention_selected=="")||(!array_key_exists($id_mention_selected,$tab_mentions))) {
		$retour.=" selected";
	}
	$retour.="> </option>\n";
	foreach($tab_mentions as $key => $value) {
		$retour.="<option value='$key'";
		if($id_mention_selected==$key) {
			$retour.=" selected";
		}
		//$retour.=">".$value." ".$key."</option>\n";
		$retour.=">".$value."</option>\n";
	}
	$retour.="</select>\n";

	return $retour;
}

function test_existence_mentions_classe($id_classe) {
	$sql="SELECT 1=1 FROM j_mentions_classes WHERE id_classe='$id_classe';";
	$test=mysql_query($sql);
	if(mysql_num_rows($test)>0) {
		return TRUE;
	}
	else {
		return FALSE;
	}

}

function check_compte_actif($login) {
	$sql="SELECT etat FROM utilisateurs WHERE login='$login';";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)==0) {
		return 0;
	}
	else {
		$lig=mysql_fetch_object($res);
		if($lig->etat=='actif') {
			return 1;
		}
		else {
			return 2;
		}
	}
}

function lien_image_compte_utilisateur($login, $statut="", $target="", $avec_lien="y") {
	global $gepiPath;

	$retour="";

	if($target!="") {$target=" target='$target'";}

	$test=check_compte_actif($login);
	if($test!=0) {
		if($statut=="") {
			$statut=get_statut_from_login($login);
		}

		if($statut!="") {
			$refermer_lien="y";

			if($avec_lien=="y") {
				if($statut=='eleve') {
					$retour.="<a href='".$gepiPath."/eleves/modify_eleve.php?eleve_login=$login'$target>";
				}
				elseif($statut=='responsable') {
					$infos=get_infos_from_login_utilisateur($login);
					if(isset($infos['pers_id'])) {
						$retour.="<a href='".$gepiPath."/responsables/modify_resp.php?pers_id=".$infos['pers_id']."'$target>";
					}
					else {
						$refermer_lien="n";
					}
				}
				elseif($statut=='autre') {
					$retour.="<a href='".$gepiPath."/utilisateurs/creer_statut.php'$target>";
				}
				else {
					$retour.="<a href='".$gepiPath."/utilisateurs/modify_user.php?user_login=$login'$target>";
				}
			}

			if($test==1) {
				$retour.="<img src='".$gepiPath."/images/icons/buddy.png' width='16' height='16' alt='Compte $login actif' title='Compte $login actif' />";
			}
			else {
				$retour.="<img src='".$gepiPath."/images/icons/buddy_no.png' width='16' height='16' alt='Compte $login inactif' title='Compte $login inactif' />";
			}

			if($avec_lien=="y") {
				if($refermer_lien=="y") {
					$retour.="</a>";
				}
			}
		}
	}

	return $retour;
}

function get_statut_from_login($login) {
	$sql="SELECT statut FROM utilisateurs WHERE login='$login';";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)==0) {
		return "";
	}
	else {
		$lig=mysql_fetch_object($res);
		return $lig->statut;
	}
}

function get_infos_from_login_utilisateur($login, $tab_champs=array()) {
	$tab=array();

	$tab_champs_utilisateur=array('nom', 'prenom', 'civilite', 'email','show_email','statut','etat','change_mdp','date_verrouillage','ticket_expiration','niveau_alerte','observation_securite','temp_dir','numind','auth_mode');
	$sql="SELECT * FROM utilisateurs WHERE login='$login';";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		$lig=mysql_fetch_object($res);
		foreach($tab_champs_utilisateur as $key => $value) {
			$tab[$value]=$lig->$value;
		}

		if($tab['statut']=='responsable') {
			$sql="SELECT pers_id FROM resp_pers WHERE login='$login';";
			$res=mysql_query($sql);
			if(mysql_num_rows($res)>0) {
				$lig=mysql_fetch_object($res);
				$tab['pers_id']=$lig->pers_id;

				if(in_array('enfants', $tab_champs)) {
					// A compl�ter
				}
			}
		}
		elseif($tab['statut']=='eleve') {
			$sql="SELECT * FROM eleves WHERE login='$login';";
			$res=mysql_query($sql);
			if(mysql_num_rows($res)>0) {
				$lig=mysql_fetch_object($res);

				$tab_champs_eleve=array('no_gep','sexe','naissance','lieu_naissance','elenoet','ereno','ele_id','id_eleve','id_mef','date_sortie');
				foreach($tab_champs_eleve as $key => $value) {
					$tab[$value]=$lig->$value;
				}

				if(in_array('parents', $tab_champs)) {
					// A compl�ter
				}
			}

		}
		elseif($tab['statut']=='autre') {
			// A compl�ter
			$tab['statut_autre']="A EXTRAIRE";
		}
	}
	return $tab;
}

function affiche_actions_compte($login) {
	global $gepiPath;

	$retour="";

	$user=get_infos_from_login_utilisateur($login);

	$retour.="<p>\n";
	if ($user['etat'] == "actif") {
		$retour.="<a style='padding: 2px;' href='$gepiPath/gestion/security_panel.php?action=desactiver&amp;afficher_les_alertes_d_un_compte=y&amp;user_login=".$login;
		$retour.=add_token_in_url()."'>D�sactiver le compte</a>";
	} else {
		$retour.="<a style='padding: 2px;' href='$gepiPath/gestion/security_panel.php?action=activer&amp;afficher_les_alertes_d_un_compte=y&amp;user_login=".$login;
		$retour.=add_token_in_url()."'>R�activer le compte</a>";
	}
	$retour.="<br />\n";
	if ($user['observation_securite'] == 0) {
		$retour.="<a style='padding: 2px;' href='$gepiPath/gestion/security_panel.php?action=observer&amp;afficher_les_alertes_d_un_compte=y&amp;user_login=".$login;
		$retour.=add_token_in_url()."'>Placer en observation</a>";
	} else {
		$retour.="<a style='padding: 2px;' href='$gepiPath/gestion/security_panel.php?action=stop_observation&amp;afficher_les_alertes_d_un_compte=y&amp;user_login=".$login;
		$retour.=add_token_in_url()."'>Retirer l'observation</a>";
	}
	if($user['niveau_alerte']>0) {
		$retour.="<br />\n";
		$retour.="Score cumul�&nbsp;: ".$user['niveau_alerte'];
		$retour.="<br />\n";
		$retour.="<a style='padding: 2px;' href='$gepiPath/gestion/security_panel.php?action=reinit_cumul&amp;afficher_les_alertes_d_un_compte=y&amp;user_login=".$login;
		$retour.=add_token_in_url()."'>R�initialiser cumul</a>";
	}
	$retour.="</p>\n";

	return $retour;
}

function acces_resp_disc($login_resp) {
	if((check_compte_actif($login_resp)!=0)&&(getSettingValue('visuRespDisc')=='yes')) {
		return TRUE;
	}
	else {
		return FALSE;
	}
}

function acces_ele_disc($login_ele) {
	if((check_compte_actif($login_ele)!=0)&&(getSettingValue('visuEleDisc')=='yes')) {
		return TRUE;
	}
	else {
		return FALSE;
	}
}

function get_resp_from_ele_login($ele_login) {
	$tab="";

	$sql="SELECT rp.* FROM resp_pers rp, responsables2 r, eleves e WHERE e.login='$ele_login' AND rp.pers_id=r.pers_id AND r.ele_id=e.ele_id AND (r.resp_legal='1' OR r.resp_legal='2');";
	//echo "$sql<br />";
	$res=mysql_query($sql);
	if(mysql_num_rows($res)>0) {
		$cpt=0;
		while($lig=mysql_fetch_object($res)) {
			$tab[$cpt]=array();

			$tab[$cpt]['login']=$lig->login;
			$tab[$cpt]['nom']=$lig->nom;
			$tab[$cpt]['prenom']=$lig->prenom;
			$tab[$cpt]['civilite']=$lig->civilite;

			$tab[$cpt]['designation']=$lig->civilite." ".$lig->nom." ".$lig->prenom;

			$cpt++;
		}
	}

	//print_r($tab);

	return $tab;
}
?>
