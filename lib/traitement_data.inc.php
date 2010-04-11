<?php
// $version : $Id$
// on force la valeur de magic_quotes_runtime � off de fa�on � ce que les valeurs r�cup�r�es dans la base
// puissent �tre affich�es directement, sans caract�re "\"
@set_magic_quotes_runtime(0);

// Corrige les caracteres degoutants utilises par les Windozeries
function corriger_caracteres($texte) {
    // 145,146,180 = simple quote ; 147,148 = double quote ; 150,151 = tiret long
    $texte = strtr($texte, chr(145).chr(146).chr(180).chr(147).chr(148).chr(150).chr(151), "'''".'""--');
    return my_ereg_replace( chr(133), "...", $texte );
}

function traitement_magic_quotes($_value) {
    global $use_function_mysql_real_escape_string;
   if (get_magic_quotes_gpc())    $_value = stripslashes($_value);
   if (!is_numeric($_value)) {
        if (isset($use_function_mysql_real_escape_string) and ($use_function_mysql_real_escape_string==0))
             $_value = mysql_escape_string($_value);
        else
             $_value = mysql_real_escape_string($_value);
   }
   return $_value;
}

function unslashes($s)
{
    if (get_magic_quotes_gpc()) return stripslashes($s);
    else return $s;
}

# Nettoyage des variables dans $_POST et $_GET pour pr�venir tout probl�me
# d'injection SQL
function anti_inject(&$_value, $_key) {
   global $use_function_mysql_real_escape_string;
   if (is_array($_value)) {
       foreach ($_value as $key2 => $value2) {
           $value2 = corriger_caracteres($value2);
           if (get_magic_quotes_gpc()) $_value[$key2] = stripslashes($value2);
           if (!is_numeric($_value[$key2])) {
//               $_value[$key2] = htmlspecialchars($value2, ENT_QUOTES);
               $_value[$key2] = htmlentities($_value[$key2], ENT_QUOTES);
               if (isset($use_function_mysql_real_escape_string) and ($use_function_mysql_real_escape_string==0))
                  $_value[$key2] = mysql_escape_string($_value[$key2]);
               else
                  $_value[$key2] = mysql_real_escape_string($_value[$key2]);
           }
//           echo "valeur : ".$_value[$key2]."<br>";
       }
   } else {
       $_value = corriger_caracteres($_value);
       if (get_magic_quotes_gpc())    $_value = stripslashes($_value);
       if (!is_numeric($_value)) {
           $_value = htmlspecialchars($_value, ENT_NOQUOTES);
//           $_value = htmlentities($_value, ENT_QUOTES);
           if (isset($use_function_mysql_real_escape_string) and ($use_function_mysql_real_escape_string==0))
               $_value = mysql_escape_string($_value);
           else
               $_value = mysql_real_escape_string($_value);
       }
//       echo "valeur : ".$_value."<br>";
   }
}

// Cr�e des variables � partir du tableau $_POST qui ne sont pas trait�es par la fonction anti_inject
// Exemple : traitement particulier des mots de passe
// Ce sont des variables du type $_POST["no_anti_inject_nom_quelquonque"]
// On cr�e alors des variables $NON_PROTECT['nom_quelquonque']
function cree_variables_non_protegees() {
    global $NON_PROTECT;
    foreach ($_POST as $key => $value) {
        if (substr($key,0,15) == "no_anti_inject_") {
            $temp = substr($key,15,strlen($key));
            if (get_magic_quotes_gpc())
                $NON_PROTECT[$temp] = stripslashes($_POST[$key]);
            else
                $NON_PROTECT[$temp] = $_POST[$key];

        }
    }
}

if (isset($variables_non_protegees)) cree_variables_non_protegees();

unset($liste_scripts_non_traites);
// Liste des scripts pour lesquels les donn�es post�es ne sont pas trait�es si $traite_anti_inject = 'no';
$liste_scripts_non_traites = array(
"/visualisation/draw_artichow1.php",
"/visualisation/draw_artichow2.php",
"/public/contacter_admin_pub.php",
"/lib/create_im_mat.php",
"/gestion/contacter_admin.php",
"/messagerie/index.php",
"/gestion/accueil_sauve.php",
"/cahier_texte/index.php",
"/cahier_texte_2/ajax_enregistrement_compte_rendu.php",
"/cahier_texte_2/ajax_enregistrement_devoir.php",
"/cahier_texte_2/ajax_enregistrement_notice_privee.php",
"/cahier_texte_2/creer_sequence.php"
);

// On ajoute la possibilit� pour les plugins de s'ajouter � la liste
if (isset($_ajouter_fichier_anti_inject)){
  $liste_scripts_non_traites[] = "/mod_plugins/" . $_ajouter_fichier_anti_inject;
}

$url = parse_url($_SERVER['REQUEST_URI']);
// On traite les donn�es post�es si n�cessaire
if ((!(in_array(substr($url['path'], strlen($gepiPath)),$liste_scripts_non_traites))) OR ((in_array(substr($url['path'], strlen($gepiPath)),$liste_scripts_non_traites)) AND (!(isset($traite_anti_inject)) OR (isset($traite_anti_inject) AND $traite_anti_inject !="no")))) {
  array_walk($_GET, 'anti_inject');
  array_walk($_POST, 'anti_inject');
}

// On nettoie aussi $_SERVER et $_COOKIE de mani�re syst�matique
array_walk($_SERVER, 'anti_inject');
array_walk($_COOKIE, 'anti_inject');

//===========================================================
// Passer la variable � "y" pour tester... et trouver la liste d'attributs "id�ale".
// Il faudra peut-�tre mettre les $aAllowedTags et $aAllowedAttr dans global.inc
$filtrage_html="n";
if($filtrage_html=='y') {

	//$aAllowedTags = array("u", "b", "i", "span", "p", "blink", "blockquote", "br", "caption");
	//$aAllowedAttr = array("abbr", "align", "alt", "axis", "background", "behavior", "title", "class");

	$aAllowedTags = array("a", "b", "blink", "blockquote", "br", "caption", "center", "col", "colgroup", "comment", "em", "font", "h1", "h2", "h3", "h4", "h5", "h6", "hr", "img", "li", "marquee", "ol", "p", "pre", "s", "small", "span", "strike", "strong", "sub", "sup", "table", "tbody", "td", "tfoot", "th", "thead", "tr", "tt", "u", "ul", "color");
	
	$aAllowedAttr = array("abbr", "align", "alt", "axis", "background", "behavior", "bgcolor", "border", "bordercolor", "bordercolordark", "bordercolorlight", "bottompadding", "cellpadding", "cellspacing", "char", "charoff", "cite", "clear", "color", "cols", "direction", "face", "font-weight", "headers", "height", "href", "hspace", "leftpadding", "loop", "noshade", "nowrap", "point-size", "rel", "rev", "rightpadding", "rowspan", "rules", "scope", "scrollamount", "scrolldelay", "size", "span", "src", "start", "summary", "target", "title", "toppadding", "type", "valign", "value", "vspace", "width", "wrap", "style");


	$oMyFilter = new InputFilter($aAllowedTags, $aAllowedAttr, 0, 0, 1);

	foreach($_GET as $key => $value) {
		if(!is_array($value)) {
			$_GET[$key]=$oMyFilter->process($value);
		}
		else {
			foreach($_GET[$key] as $key2 => $value2) {
				$_GET[$key][$key2]=$oMyFilter->process($value2);
			}
		}
	}

	foreach($_POST as $key => $value) {
		if(!is_array($value)) {
			$_POST[$key]=$oMyFilter->process($value);
		}
		else {
			foreach($_POST[$key] as $key2 => $value2) {
				$_POST[$key][$key2]=$oMyFilter->process($value2);
			}
		}
	}

	if(isset($NON_PROTECT)) {
		foreach($NON_PROTECT as $key => $value) {
			if(!is_array($value)) {$NON_PROTECT[$key]=$oMyFilter->process($value);}
			else {
				foreach($NON_PROTECT[$key] as $key2 => $value2) {
					$NON_PROTECT[$key][$key2]=$oMyFilter->process($value2);;
				}
			}
		}
	}
}
//===========================================================

//On r�tablit les "&" dans $_SERVER['REQUEST_URI']
$_SERVER['REQUEST_URI'] = str_replace("&amp;","&",$_SERVER['REQUEST_URI']);

// Et on traite les fichiers upload�s
if (!isset($AllowedFilesExtensions)) {
	$AllowedFilesExtensions = array("bmp","csv","doc","epg","gif","ico","jpg","odg","odp","ods","odt","pdf","png","ppt","swf","txt","xcf","xls","zip","pps");
}

if (isset($_FILES) and !empty($_FILES)) {		
    foreach ($_FILES as &$file) {
		if (is_array($file['name'])) {
			$i = 0;
			while (isset($file['name'][$i])) {
				if ($file['name'][$i] != "") {
					if (!is_uploaded_file($file['tmp_name'][$i])) {
						$file['name'][$i] = "";
					}
					$delete_file = true;
					$k = 0;
					trim($file['name'][$i]);
					while (isset($AllowedFilesExtensions[$k])) {
						if (preg_match("/".$AllowedFilesExtensions[$k]."$/i",$file['name'][$i])) $delete_file = false;
						$k++;
					}
					if ($delete_file) {
						$file['name'][$i] = "";
						unlink($file['tmp_name'][$i]);
					}
				}
				$i++;
			}
		}
		else {
			if (isset($file['name'])) {
				if ($file['name'] != "") {
					if (!is_uploaded_file($file['tmp_name'])) {
						$file['name'] = "";
					}
					$delete_file = true;
					$k = 0;
					trim($file['name']);
					while (isset($AllowedFilesExtensions[$k])) {
						if (preg_match("/".$AllowedFilesExtensions[$k]."$/i",$file['name'])) $delete_file = false;
						$k++;
					}
					if ($delete_file) {
						$file['name'] = "";
						unlink($file['tmp_name']);
					}
				}
			}		
		
		}
    }
}
?>
