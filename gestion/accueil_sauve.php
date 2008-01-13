<?php
/*
 * $Id$
 *
 * Copyright 2001-2007 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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

// On d�samorce une tentative de contournement du traitement anti-injection lorsque register_globals=on
if (isset($_GET['traite_anti_inject']) OR isset($_POST['traite_anti_inject'])) $traite_anti_inject = "yes";

// On pr�cise de ne pas traiter les donn�es avec la fonction anti_inject
if (isset($_POST["action"]) and ($_POST["action"] == 'protect'))  $traite_anti_inject = 'no';

// Initialisations files
require_once("../lib/initialisations.inc.php");

unset($action);
$action = isset($_POST["action"]) ? $_POST["action"] : (isset($_GET["action"]) ? $_GET["action"] : NULL);

// Resume session
$resultat_session = resumeSession();
//D�commenter la ligne suivante pour le mode "manuel et bavard"
//$debug="yes";

if (!isset($action) or ($action != "restaure")) {
    if ($resultat_session == 'c') {
        header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
        die();
    } else if ($resultat_session == '0') {
        header("Location: ../logout.php?auto=1");
        die();
    };
}

if (!isset($action) or ($action != "restaure")) {
    if (!checkAccess()) {
        header("Location: ../logout.php?auto=1");
        die();
    }
} else {
	// On s'assure que l'utilisateur qui a initi� la restauration �tait bien
	// un admin !
	if (!isset($_SESSION["tempstatut"])) {
		$_SESSION["tempstatut"] = $_SESSION['statut'];
	}
	if ($_SESSION["tempstatut"] != "administrateur") {
		die();
	}
}


// Initialisation du r�pertoire actuel de sauvegarde
$dirname = getSettingValue("backup_directory");

// T�l�chargement d'un fichier vers backup
if (isset($action) and ($action == 'upload'))  {
    $sav_file = isset($_FILES["sav_file"]) ? $_FILES["sav_file"] : NULL;
    if (!isset($sav_file['tmp_name']) or ($sav_file['tmp_name'] =='')) {
        $msg = "Erreur de t�l�chargement.";
    } else if (!file_exists($sav_file['tmp_name'])) {
        $msg = "Erreur de t�l�chargement.";
    } else if (!preg_match('/sql$/',$sav_file['name']) AND !preg_match('/gz$/',$sav_file['name'])){
        $msg = "Erreur : seuls les fichiers ayant l'extension .sql ou .gz sont autoris�s.";
    } else {
        $dest = "../backup/".$dirname."/";
        $n = 0;
        $nom_corrige = ereg_replace("[^.a-zA-Z0-9_=-]+", "_", $sav_file['name']);
        if (!deplacer_fichier_upload($sav_file['tmp_name'], "../backup/".$dirname."/".$nom_corrige)) {
            $msg = "Probl�me de transfert : le fichier n'a pas pu �tre transf�r� sur le r�pertoire backup";
        } else {
            $msg = "T�l�chargement r�ussi.";
        }
    }
}

// Suppression d'un fichier
if (isset($action) and ($action == 'sup'))  {
    if (isset($_GET['file']) && ($_GET['file']!='')) {
        if (@unlink("../backup/".$dirname."/".$_GET['file'])) {
            $msg = "Le fichier <b>".$_GET['file']."</b> a �t� supprim�.<br />\n";
        } else {
            $msg = "Un probl�me est survenu lors de la tentative de suppression du fichier <b>".$_GET['file']."</b>.<br />
            Il s'agit peut-�tre un probl�me de droits sur le r�pertoire backup.<br />\n";
        }
    }
}

// Protection du r�pertoire backup
if (isset($action) and ($action == 'protect'))  {
    include_once("../lib/class.htaccess.php");
    // Instance of the htaccess class
    $ht = & new htaccess(TRUE);
    $user = array();
    // Get the logins from the password file
    $user = $ht->get_htpasswd();
    // Add an Administrator
    if(empty($_POST['pwd1_backup']) || empty($_POST['pwd2_backup'])) {
        $msg = "Probl�me : les deux mots de passe ne sont pas identiques ou sont vides.";
        $error = 1;
    } elseif ($_POST['pwd1_backup'] != $_POST['pwd2_backup']) {
        $msg = "Probl�me : les deux mots de passe ne sont pas identiques.";
        $error = 1;
    } elseif (empty($_POST['login_backup'])) {
        $msg = "Probl�me : l'identifiant est vide.";
        $error = 1;
    } else {
        $_login = strtolower(unslashes($_POST['login_backup']));
        if(is_array($user)) {
            foreach($user as $key => $value) {
                if($_login == $key) {
                   $ht->delete_user($_login);
                }
            }
        }
    }
    if(!isset($error)) {
        $ht->set_user($_login, $_POST['pwd1_backup']);
        $ht->set_htpasswd();
        $user = array();
        $user = $ht->get_htpasswd();
        clearstatcache();
        if(!is_file('../backup/'.$dirname.'/.htaccess')) {
            $ht->option['AuthName'] = '"PROTECTION BACKUP"';
            $ht->set_htaccess();
        }
    }
}

// Suppression de la protection
if (isset($action) and ($action == 'del_protect'))  {
   if ((@unlink("../backup/".$dirname."/.htaccess")) and (@unlink("../backup/".$dirname."/.htpasswd"))) {
       $msg = "Les fichiers .htaccess et .htpasswd ont �t� supprim�s. Le r�pertoire /backup n'est plus prot�g�\n";
   }
}

function gzip($src, $level = 5, $dst = false){
    // Pour compresser un fichier existant

    if($dst == false) {
        $dst = $src.".gz";
    }
    if(file_exists($src)){
        $filesize = filesize($src);
        $src_handle = fopen($src, "r");
        if(!file_exists($dst)){
            $dst_handle = gzopen($dst, "w$level");
            while(!feof($src_handle)){
                $chunk = fread($src_handle, 32768);
                gzwrite($dst_handle, $chunk);
            }
            fclose($src_handle);
            gzclose($dst_handle);
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function charset_to_iso($string, $method = "mbstring") {
	// Cette fonction a pour objet de convertir, si n�cessaire,
	// la cha�ne de caract�res $string avec l'encodage iso-8859-1
	// Il s'agit surtout de prendre en compte les backup r�alis�s
	// avec mysqldump, qui encodent en utf8...

	if (preg_match('%(?:[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})+%xs', $string)) {
    	// Ce preg_match d�tecte la pr�sence d'un caract�re cod� en utf-8
    	// Donc si elle retourne true, il faut convertir :
    	if ($method == "mbstring") {
    		return mb_convert_encoding($string, "ISO-8859-1", "UTF-8");
    		unset($string);
    	} else {
    		return iconv("UTF-8", "ISO-8859-1", $string);
    		unset($string);
    	}
    } else {
    	return $string;
    	unset($string);
    }
}

function deplacer_fichier_upload($source, $dest) {
    $ok = @copy($source, $dest);
    if (!$ok) $ok = @move_uploaded_file($source, $dest);
    return $ok;
}


function test_ecriture_backup() {
    $ok = 'no';
    if ($f = @fopen("../backup/test", "w")) {
        @fputs($f, '<'.'?php $ok = "yes"; ?'.'>');
        @fclose($f);
        include("../backup/test");
        $del = @unlink("../backup/test");
    }
    return $ok;
}

function mysql_version2() {
   $result = mysql_query('SELECT VERSION() AS version');
   if ($result != FALSE && @mysql_num_rows($result) > 0)
   {
      $row = mysql_fetch_array($result);
      $match = explode('.', $row['version']);
   }
   else
   {
      $result = @mysql_query('SHOW VARIABLES LIKE \'version\'');
      if ($result != FALSE && @mysql_num_rows($result) > 0)
      {
         $row = mysql_fetch_row($result);
         $match = explode('.', $row[1]);
      }
   }

   if (!isset($match) || !isset($match[0])) $match[0] = 3;
   if (!isset($match[1])) $match[1] = 21;
   if (!isset($match[2])) $match[2] = 0;
   return $match[0] . "." . $match[1] . "." . $match[2];
}

function init_time() {
    global $TPSDEB,$TPSCOUR;
    list ($usec,$sec)=explode(" ",microtime());
    $TPSDEB=$sec;
    $TPSCOUR=0;
}

function current_time() {
    global $TPSDEB,$TPSCOUR;
    list ($usec,$sec)=explode(" ",microtime());
    $TPSFIN=$sec;
    if (round($TPSFIN-$TPSDEB,1)>=$TPSCOUR+1) //une seconde de plus
    {
    $TPSCOUR=round($TPSFIN-$TPSDEB,1);
    flush();
    }
}

function backupMySql($db,$dumpFile,$duree,$rowlimit) {
    global $TPSCOUR,$offsettable,$offsetrow,$cpt,$debug;
    $fileHandle = fopen($dumpFile, "a");
    if(!$fileHandle) {
        echo "Ouverture de $dumpFile impossible<br />\n";
        return FALSE;
    }
    if ($offsettable==0&&$offsetrow==-1){
        $todump ="#**************** BASE DE DONNEES ".$db." ****************"."\n"
        .date("\#\ \L\e\ \:\ d\ m\ Y\ \a\ H\h\ i")."\n";
        $todump.="# Serveur : ".$_SERVER['SERVER_NAME']."\n";
        $todump.="# Version PHP : " . phpversion()."\n";
        $todump.="# Version mySQL : " . mysql_version2()."\n";
        $todump.="# IP Client : ".$_SERVER['REMOTE_ADDR']."\n";
        $todump.="# Fichier SQL compatible PHPMyadmin\n#\n";
        $todump.="# ******* debut du fichier ********\n";
        fwrite ($fileHandle,$todump);
    }
    $result=mysql_list_tables($db);
    $numtab=0;
    while ($t = mysql_fetch_array($result)) {
        $tables[$numtab]=$t[0];
        $numtab++;
    }
    if (mysql_error()) {
       echo "<hr />\n<font color='red'>ERREUR lors de la sauvegarde du � un probl�me dans la la base.</font><br />".mysql_error()."<hr/>\n";
       return false;
       die();
    }

    for (;$offsettable<$numtab;$offsettable++){
        // Dump de la strucutre table
        if ($offsetrow==-1){
            $todump = get_def($db,$tables[$offsettable]);
            if (isset($debug)) echo "<b><br />Dump de la structure de la table ".$tables[$offsettable]."</b><br />\n";
            fwrite($fileHandle,$todump);
            $offsetrow++;
            $cpt++;
        }
        current_time();
        if ($duree>0 and $TPSCOUR>=$duree) //on atteint la fin du temps imparti
            return TRUE;
        if (isset($debug)) echo "<b><br />Dump des donn�es de la table ".$tables[$offsettable]."<br /></b>\n";
        $fin=0;
        while (!$fin){
            $todump = get_content($db,$tables[$offsettable],$offsetrow,$rowlimit);
            $rowtodump=substr_count($todump, "INSERT INTO");
            if ($rowtodump>0){
                fwrite ($fileHandle,$todump);
                $cpt+=$rowtodump;
                $offsetrow+=$rowlimit;
                if ($rowtodump<$rowlimit) $fin=1;
                current_time();
                if ($duree>0 and $TPSCOUR>=$duree) {//on atteint la fin du temps imparti
                    if (isset($debug)) echo "<br /><br /><b>Nombre de lignes actuellement dans le fichier : ".$cpt."</b><br />\n";
                    return TRUE;
                }
            } else {
                $fin=1;$offsetrow=-1;
            }
        }
        if (isset($debug)) echo "Pour cette table, nombre de lignes sauvegard�es : ".$offsetrow."<br />\n";
        if ($fin) $offsetrow=-1;
        current_time();
        if ($duree>0 and $TPSCOUR>=$duree) //on atteint la fin du temps imparti
            return TRUE;
    }
    $offsettable=-1;
    $todump ="#\n";
    $todump.="# ******* Fin du fichier - La sauvegarde s'est termin�e normalement ********\n";
    fwrite ($fileHandle,$todump);
    fclose($fileHandle);
    return TRUE;
}

function restoreMySqlDump($dumpFile,$duree) {
    // $dumpFile, fichier source
    // $duree=timeout pour changement de page (-1 = aucun)

    global $TPSCOUR,$offset,$cpt;

    if(!file_exists($dumpFile)) {
         echo "$dumpFile non trouv�<br />\n";
         return FALSE;
    }
    $fileHandle = gzopen($dumpFile, "rb");

    if(!$fileHandle) {
        echo "Ouverture de $dumpFile impossible.<br />\n";
        return FALSE;
    }

    if ($offset!=0) {
        if (gzseek($fileHandle,$offset,SEEK_SET)!=0) { //erreur
            echo "Impossible de trouver l'octet ".number_format($offset,0,""," ")."<br />\n";
            return FALSE;
        }
        //else
        //    echo "Reprise � l'octet ".number_format($offset,0,""," ")."<br />";
        flush();
    }
    $formattedQuery = "";
    $old_offset = $offset;
    while(!gzeof($fileHandle)) {
        current_time();
        if ($duree>0 and $TPSCOUR>=$duree) {  //on atteint la fin du temps imparti
            if ($old_offset == $offset) {
                echo "<p align=\"center\"><b><font color=\"#FF0000\">La proc�dure de restauration ne peut pas continuer.
                <br />Un probl�me est survenu lors du traitement d'une requ�te pr�s de :.
                <br />".$debut_req."</font></b></p><hr />\n";
                return FALSE;
            }
            $old_offset = $offset;
            return TRUE;
        }
        //echo $TPSCOUR."<br />";
        $buffer=gzgets($fileHandle);
        if (substr($buffer,strlen($buffer),1)==0)
            $buffer=substr($buffer,0,strlen($buffer)-1);

        //echo $buffer."<br />";

        if(substr($buffer, 0, 1) != "#" AND substr($buffer, 0, 1) != "/") {
            if (!isset($debut_req))  $debut_req = $buffer;
            $formattedQuery .= $buffer;
              //echo $formattedQuery."<hr />";
            if ($formattedQuery) {
                // Iconv d�sactiv� pour l'instant... Il semble qu'il y ait une fuite m�moire...
                //if (function_exists("iconv")) {
                //	$sql = charset_to_iso($formattedQuery, "iconv");
                //} elseif (function_exists("mbstring_convert_encoding")) {
                if (function_exists("mb_convert_encoding")) {
                  	$sql = charset_to_iso($formattedQuery, "mbstring");
                } else {
                	$sql = $formattedQuery;
                }
                if (mysql_query($sql)) {//r�ussie sinon continue � concat�ner
                    $offset=gztell($fileHandle);
                    //echo $offset;
                    $formattedQuery = "";
                    unset($debut_req);
                    $cpt++;
                    //echo $cpt;
                }
            }
        }
    }

    if (mysql_error())
        echo "<hr />\nERREUR � partir de [$formattedQuery]<br />".mysql_error()."<hr />\n";

    gzclose($fileHandle);
    $offset=-1;
    return TRUE;
}

function get_def($db, $table) {
    $def="#\n# Structure de la table $table\n#\n";
    $def .="DROP TABLE IF EXISTS `$table`;\n";
    // requete de creation de la table
    $query = "SHOW CREATE TABLE $table";
    $resCreate = mysql_query($query);
    $row = mysql_fetch_array($resCreate);
    $schema = $row[1].";";
    $def .="$schema\n";
    return $def;
}

function get_content($db, $table,$from,$limit) {
    $search       = array("\x00", "\x0a", "\x0d", "\x1a");
    $replace      = array('\0', '\n', '\r', '\Z');
    // les donn�es de la table
    $def = '';
    $query = "SELECT * FROM $table LIMIT $from,$limit";
    $resData = @mysql_query($query);
    //peut survenir avec la corruption d'une table, on pr�vient
    if (!$resData) {
        $def .="Probl�me avec les donn�es de $table, corruption possible !\n";
    } else {
        if (@mysql_num_rows($resData) > 0) {
             $sFieldnames = "";
             $num_fields = mysql_num_fields($resData);
              $sInsert = "INSERT INTO $table $sFieldnames values ";
              while($rowdata = mysql_fetch_row($resData)) {
                  $lesDonnees = "";
                  for ($mp = 0; $mp < $num_fields; $mp++) {
                  $lesDonnees .= "'" . str_replace($search, $replace, traitement_magic_quotes($rowdata[$mp])) . "'";
                  //on ajoute � la fin une virgule si n�cessaire
                      if ($mp<$num_fields-1) $lesDonnees .= ", ";
                  }
                  $lesDonnees = "$sInsert($lesDonnees);\n";
                  $def .="$lesDonnees";
              }
        }
     }
     return $def;
}

// Type de fichier
$filetype = "sql";

// Chemin vers /backup
if (!isset($_GET["path"]))
    $path="../backup/" . $dirname . "/" ;
else
    $path=$_GET["path"];



// Dur�e d'une portion
if ((isset($_POST['duree'])) and ($_POST['duree'] > 0)) $_SESSION['defaulttimeout'] = $_POST['duree'];
if (getSettingValue("backup_duree_portion") > "4" and !isset($_POST['sauve_duree'])) $_SESSION['defaulttimeout'] = getSettingValue("backup_duree_portion");

if (!isset($_SESSION['defaulttimeout'])) {
    $max_time=min(get_cfg_var("max_execution_time"),get_cfg_var("max_input_time"));
    if ($max_time>5) {
        $_SESSION['defaulttimeout']=$max_time-2;
    } else {
        $_SESSION['defaulttimeout']=5;
    }
}

// Lors d'une sauvegarde, nombre de lignes trait�es dans la base entre chaque v�rification du temps restant
$defaultrowlimit=10;

//**************** EN-TETE *****************
$titre_page = "Outil de gestion | Sauvegardes/Restauration";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************

// Test d'�criture dans /backup
$test_write = test_ecriture_backup();
if ($test_write == 'no') {
    echo "<h3 class='gepi'>Probl�me de droits d'acc�s :</h3>\n";
    echo "<p>Le r�pertoire \"/backup\" n'est pas accessible en �criture.</p>\n";
    echo "<p>Vous ne pouvez donc pas acc�der aux fonctions de sauvegarde/restauration de GEPI.
    Contactez l'administrateur technique afin de r�gler ce probl�me.</p>\n";
    require("../lib/footer.inc.php");
    die();
}

if (!function_exists("gzwrite")) {
    echo "<h3 class='gepi'>Probl�me de configuration :</h3>\n";
    echo "<p>Les fonctions de compression 'zlib' ne sont pas activ�es. Vous devez configurer PHP pour qu'il utilise 'zlib'.</p>\n";
    echo "<p>Vous ne pouvez donc pas acc�der aux fonctions de sauvegarde/restauration de GEPI.
    Contactez l'administrateur technique afin de r�gler ce probl�me.</p>\n";
    require("../lib/footer.inc.php");
    die();
}

// Confirmation de la restauration
if (isset($action) and ($action == 'restaure_confirm'))  {
    echo "<h3>Confirmation de la restauration de la base</h3>\n";
    echo "Fichier s�lectionn� pour la restauration : <b>".$_GET['file']."</b>\n";
    echo "<p><b>ATTENTION :</b> La proc�dure de restauration de la base est <b>irr�versible</b>. Le fichier de restauration doit �tre valide. Selon le contenu de ce fichier, tout ou partie de la structure actuelle de la base ainsi que des donn�es existantes peuvent �tre supprim�es et remplac�es par la structure et les donn�es pr�sentes dans le fichier.
    <br /><br />\n<b>AVERTISSEMENT :</b> Cette proc�dure peut �tre tr�s longue selon la quantit� de donn�es � restaurer.</p>\n";
    echo "<p><b>Etes-vous s�r de vouloir continuer ?</b></p>\n";

    echo "<center><table cellpadding=\"5\" cellspacing=\"5\" border=\"0\">\n<tr><td>\n";
    echo "<form enctype=\"multipart/form-data\" action=\"accueil_sauve.php\" method=post name=formulaire_oui>\n";
    echo "<input type='submit' name='confirm' value = 'Oui' />\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"restaure\" />\n";
    echo "<input type=\"hidden\" name=\"file\" value=\"".$_GET['file']."\" />\n";
    echo "</form>\n";
    echo "</td>\n<td>\n";
    echo "<form enctype=\"multipart/form-data\" action=\"accueil_sauve.php\" method=post name=formulaire_non>\n";
    echo "<input type='submit' name='confirm' value = 'Non' />\n</form>\n</td></tr>\n</table>\n</center>\n";
    require("../lib/footer.inc.php");
    die();
}

// Restauration
if (isset($action) and ($action == 'restaure'))  {
    unset($file);
    $file = isset($_POST["file"]) ? $_POST["file"] : (isset($_GET["file"]) ? $_GET["file"] : NULL);

    init_time(); //initialise le temps
    //d�but de fichier
    if (!isset($_GET["offset"])) $offset=0;
    else  $offset=$_GET["offset"];

    //timeout
    if (!isset($_GET["duree"])) $duree=$_SESSION['defaulttimeout'];
        else $duree=$_GET["duree"];
    $fsize=filesize($path.$file);
    if(isset($offset)) {
        if ($offset==-1) $percent=100;
           else $percent=min(100,round(100*$offset/$fsize,0));
    }
    else $percent=0;

    if ($percent >= 0) {
        $percentwitdh=$percent*4;
        echo "<div align='center'><table class='tab_cadre' width='400'><tr><td width='400' align='center'><b>Restauration en cours</b><br /><br />Progression ".$percent."%</td></tr><tr><td><table><tr><td bgcolor='red'  width='$percentwitdh' height='20'>&nbsp;</td></tr></table></td></tr></table></div>\n";
    }
    flush();
    if ($offset!=-1) {
        if (restoreMySqlDump($path.$file,$duree)) {
            if (isset($debug)) echo "<br />\n<b>Cliquez <a href=\"accueil_sauve.php?action=restaure&file=".$file."&duree=$duree&offset=$offset&cpt=$cpt&path=$path\">ici</a> pour poursuivre la restauration</b>\n";
            if (!isset($debug))  echo "<br />\n<b>Redirection automatique sinon cliquez <a href=\"accueil_sauve.php?action=restaure&file=".$file."&duree=$duree&offset=$offset&cpt=$cpt&path=$path\">ici</a></b>\n";
            if (!isset($debug))  echo "<script>window.location=\"accueil_sauve.php?action=restaure&file=".$file."&duree=$duree&offset=$offset&cpt=$cpt&path=$path\";</script>\n";
            flush();
            exit;
        }
    } else {

        echo "<div align='center'><p>Restauration Termin�e.<br /><br />Votre session GEPI n'est plus valide, vous devez vous reconnecter<br /><a href = \"../login.php\">Se connecter</a></p></div>\n";
		require("../lib/footer.inc.php");
        die();
    }
}

// Sauvegarde
if (isset($action) and ($action == 'dump'))  {
	// On enregistre le param�tre pour s'en souvenir la prochaine fois
	saveSetting("mode_sauvegarde", "gepi");
	if (isset($_POST['sauve_duree'])) {
		if ($_POST['sauve_duree'] == "yes") {
			saveSetting("backup_duree_portion", $_SESSION['defaulttimeout']);
		}
	}
	// SAuvegarde de la base
    $nomsql = $dbDb."_le_".date("Y_m_d_\a_H\hi");
    $cur_time=date("Y-m-d H:i");
    $filename=$path.$nomsql.".".$filetype;

    if (!isset($_GET["duree"])&&is_file($filename)){
        echo "<font color=\"#FF0000\"><center><b>Le fichier existe d�j�. Patientez une minute avant de retenter la sauvegarde.</b></center></font>\n<hr />\n";
    } else {
        init_time(); //initialise le temps
        //d�but de fichier
        if (!isset($_GET["offsettable"])) $offsettable=0;
            else $offsettable=$_GET["offsettable"];
        //d�but de fichier
        if (!isset($_GET["offsetrow"])) $offsetrow=-1;
            else $offsetrow=$_GET["offsetrow"];
        //timeout de 5 secondes par d�faut, -1 pour utiliser sans timeout
        if (!isset($_GET["duree"])) $duree=$_SESSION['defaulttimeout'];
            else $duree=$_GET["duree"];
        //Limite de lignes � dumper � chaque fois
        if (!isset($_GET["rowlimit"])) $rowlimit=$defaultrowlimit;
            else  $rowlimit=$_GET["rowlimit"];
         //si le nom du fichier n'est pas en param�tre le mettre ici
         if (!isset($_GET["fichier"])) {
             $fichier=$filename;
         } else $fichier=$_GET["fichier"];


        $tab=mysql_list_tables($dbDb);
        $tot=mysql_num_rows($tab);
        if(isset($offsettable)){
            if ($offsettable>=0)
                $percent=min(100,round(100*$offsettable/$tot,0));
            else $percent=100;
        }
        else $percent=0;

        if ($percent >= 0) {
            $percentwitdh=$percent*4;
            echo "<div align='center'>\n<table width=\"400\" border=\"0\">
            <tr><td width='400' align='center'><b>Sauvegarde en cours</b><br/>
            <br/>A la fin de la sauvegarde, Gepi vous proposera automatiquement de t�l�charger le fichier.
            <br/><br/>Progression ".$percent."%</td></tr>\n<tr><td>\n<table><tr><td bgcolor='red'  width='$percentwitdh' height='20'>&nbsp;</td></tr></table>\n</td></tr>\n</table>\n</div>\n";
        }
        flush();
        if ($offsettable>=0){
            if (backupMySql($dbDb,$fichier,$duree,$rowlimit)) {
                if (isset($debug)) echo "<br />\n<b>Cliquez <a href=\"accueil_sauve.php?action=dump&duree=$duree&rowlimit=$rowlimit&offsetrow=$offsetrow&offsettable=$offsettable&cpt=$cpt&fichier=$fichier&path=$path\">ici</a> pour poursuivre la sauvegarde.</b>\n";
                if (!isset($debug))    echo "<br />\n<b>Redirection automatique sinon cliquez <a href=\"accueil_sauve.php?action=dump&duree=$duree&rowlimit=$rowlimit&offsetrow=$offsetrow&offsettable=$offsettable&cpt=$cpt&fichier=$fichier&path=$path\">ici</a></b>\n";
                if (!isset($debug))    echo "<script>window.location=\"accueil_sauve.php?action=dump&duree=$duree&rowlimit=$rowlimit&offsetrow=$offsetrow&offsettable=$offsettable&cpt=$cpt&fichier=$fichier&path=$path\";</script>\n";
                flush();
                exit;
           }
        } else {
			// La sauvegarde est termin�e. On compresse le fichier
			$compress = gzip($fichier, 9);
			if ($compress) {
				$filetype = ".sql.gz";
			}
			@unlink($fichier);

            echo "<div align='center'><p>Sauvegarde Termin�e.<br />\n";

			//$nomsql.$filetype
			$handle=opendir($path);
			$tab_file = array();
			$n=0;
			while ($file = readdir($handle)) {
				if (($file != '.') and ($file != '..') and ($file != 'remove.txt')
				//=================================
				// AJOUT: boireaus
				and ($file != 'csv')
				//=================================
				and ($file != '.htaccess') and ($file != '.htpasswd') and ($file != 'index.html')) {
					$tab_file[] = $file;
					$n++;
				}
			}
			closedir($handle);
			//arsort($tab_file);
			rsort($tab_file);

			//$filepath = null;
			//$filename = null;
			//echo "\$nomsql.$filetype=$nomsql.$filetype<br />";

			$fileid=null;
			if ($n > 0) {
				for($m=0;$m<count($tab_file);$m++){
					//echo "\$tab_file[$m]=$tab_file[$m]<br />";
					if($tab_file[$m]=="$nomsql.$filetype"){
						$fileid=$m;
					}
				}
				clearstatcache();
			}

            //echo "<br/><p class=grand><a href='savebackup.php?filename=$fichier'>T�l�charger le fichier g�n�r� par la sauvegarde</a></p>\n";
            echo "<br/><p class=grand><a href='savebackup.php?fileid=$fileid'>T�l�charger le fichier g�n�r� par la sauvegarde</a></p>\n";
            echo "<br/><br/><a href = \"accueil_sauve.php\">Retour vers l'interface de sauvegarde/restauration</a><br /></div>\n";
			require("../lib/footer.inc.php");
            die();
        }

    }
}

if (isset($action) and ($action == 'system_dump'))  {
	// On enregistre le param�tre pour s'en souvenir la prochaine fois
	saveSetting("mode_sauvegarde", "mysqldump");

	// Sauvegarde de la base en utilisant l'utilitaire syst�me mysqldump
    $nomsql = $dbDb."_le_".date("Y_m_d_\a_H\hi");
    $cur_time=date("Y-m-d H:i");
    $filetype = "sql.gz";
    $filename=$path.$nomsql.".".$filetype;
    // Juste pour �tre s�r :
    $dbHost = escapeshellarg($dbHost);
    $dbUser = escapeshellarg($dbUser);
    $dbPass = escapeshellarg($dbPass);
    $dbDb = escapeshellarg($dbDb);

	$req_version = mysql_result(mysql_query("SELECT version();"), 0);
	$ver_mysql = explode(".", $req_version);
	if (!is_numeric(substr($ver_mysql[2], 1, 1))) {
		$ver_mysql[2] = substr($ver_mysql[2], 0, 1);
	} else {
		$ver_mysql[2] = substr($ver_mysql[2], 0, 2);
	}

	if ($ver_mysql[0] == "5" OR ($ver_mysql[0] == "4" AND $ver_mysql[1] >= "1")) {
		$command = "mysqldump --skip-opt --add-drop-table --skip-disable-keys --quick -Q --create-options --set-charset --skip-comments -h $dbHost -u $dbUser --password=$dbPass $dbDb | gzip > $filename";
	} elseif ($ver_mysql[0] == "4" AND $ver_mysql[1] == "0" AND $ver_mysql[2] >= "17") {
		// Si on est l�, c'est que le serveur mysql est d'une version 4.0.17 ou sup�rieure
		$command = "mysqldump --add-drop-table --quick --quote-names --skip-comments -h $dbHost -u $dbUser --password=$dbPass $dbDb | gzip > $filename";
	} else {
		// Et l� c'est qu'on a une version inf�rieure � 4.0.17
		$command = "mysqldump --add-drop-table --quick --quote-names -h $dbHost -u $dbUser --password=$dbPass $dbDb | gzip > $filename";
	}


	$exec = exec($command);
	if (filesize($filename) > 10000) {
		echo "<center><p style='color: red; font-weight: bold;'>La sauvegarde a �t� r�alis�e avec succ�s.</p></center>\n";
	} else {
		echo "<center><p style='color: red; font-weight: bold;'>Erreur lors de la sauvegarde.</p></center>\n";
	}
}


?><b><a href='index.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a></b>
<?php
// Test pr�sence de fichiers htaccess
if (!(file_exists("../backup/".$dirname."/.htaccess")) or !(file_exists("../backup/".$dirname."/.htpasswd"))) {
    echo "<h3 class='gepi'>R�pertoire backup non prot�g� :</h3>\n";
    echo "<p><font color=\"#FF0000\"><b>Le r�pertoire \"/backup\" n'est actuellement pas prot�g�</b></font>.
    Si vous stockez des fichiers dans ce r�pertoire, ils seront accessibles de l'ext�rieur � l'aide d'un simple navigateur.</p>\n";
    echo "<form action=\"accueil_sauve.php\" name=\"protect\" method=\"post\">\n";
    echo "<table><tr><td>Nouvel identifiant : </td><td><input type=\"text\" name=\"login_backup\" value=\"\" size=\"20\" /></td></tr>\n";
    echo "<tr><td>Nouveau mot de passe : </td><td><input type=\"password\" name=\"pwd1_backup\" value=\"\" size=\"20\" /></td></tr>\n";
    echo "<tr><td>Confirmation du mot de passe : </td><td><input type=\"password\" name=\"pwd2_backup\" value=\"\" size=\"20\" /></td></tr></table>\n";

    echo "<p align=\"center\"><input type=\"submit\" Value=\"Envoyer\" /></p>\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"protect\" />\n";
    echo "</form>\n";
    echo "<hr />\n";
} else {
    echo " | <a href='#' onClick=\"clicMenu('2')\" style=\"cursor: hand\"><b>Protection du r�pertoire backup</b></a>\n";
    echo "<div style=\"display:none\" id=\"menu2\">\n";

    echo "<table border=\"1\" cellpadding=\"5\" bgcolor=\"#C0C0C0\">
	<tr>
		<td>
			<h3 class='gepi'>Protection du r�pertoire backup :</h3>\n";
    echo "
			<p>Le r�pertoire \"/backup\" est actuellement prot�g� par un identifiant et un mot de passe.
			Pour acc�der aux fichiers stock�s dans ce r�pertoire � partir d'un navigateur web, il est n�cessaire de s'authentifier.
			<br /><br />Cliquez sur le bouton ci-dessous pour <b>supprimer la protection</b>
			ou bien pour d�finir un nouvel <b>identifiant et un mot de passe</b></p>\n";
    echo "
			<form action=\"accueil_sauve.php\" name=\"del_protect\" method=\"post\">\n";
    echo "
			<p align=\"center\"><input type=\"submit\" Value=\"Modifier/supprimer la protection du r�pertoire\" /></p>\n";
    echo "
			<input type=\"hidden\" name=\"action\" value=\"del_protect\" />\n";
    echo "
			</form>
		</td>
	</tr>
</table>\n";
    echo "<hr /></div>\n";
}

?>

<H3>Cr�er un fichier de sauvegarde/restauration de la base <?php echo $dbDb; ?></H3>
<p>Deux m�thodes de sauvegarde sont disponibles : l'utilisation de la commande syst�me mysqldump ou bien le syst�me int�gr� � Gepi.<br/>
La premi�re m�thode (mysqldump) est vigoureusement recommand�e car beaucoup moins lourde en ressources, mais ne fonctionnera que sur certaines configurations serveurs.<br />
La seconde m�thode est lourde en ressources mais passera sur toutes les configurations.</p>
<form enctype="multipart/form-data" action="accueil_sauve.php" method=post name=formulaire>
<center><input type="submit" value="Sauvegarder" />
<select name='action' size='1'>
<option value='system_dump'<?php if (getSettingValue("mode_sauvegarde") == "mysqldump") echo " SELECTED";?>>avec mysqldump</option>
<option value='dump'<?php if (getSettingValue("mode_sauvegarde") == "gepi") echo " SELECTED";?>>sans mysqldump</option>
</select>
</center>

<span class='small'><b>Remarques</b> :</span>
<ul>
<li><span class='small'>le r�pertoire "documents" contenant les documents joints aux cahiers de texte ne sera pas sauvegard�.</span></li>
<li><span class='small'>Valeur de la <b>dur�e d'une portion</b> en secondes : <input type="text" name="duree" value="<?php echo $_SESSION['defaulttimeout']; ?>" size="5" />
<input type='checkbox' name='sauve_duree' value='yes' /> M�moriser la dur�e de la portion pour la prochaine fois
<br/><a href='#' onClick="clicMenu('1')" style="cursor: hand">Afficher/cacher l'aide</a>.</span></li>
</ul>
</form>
<div style="display:none" id="menu1">
<table border="1" cellpadding="5" bgcolor="#C0C0C0"><tr><td>La <b>valeur de la dur�e d'une portion</b> doit �tre inf�rieure � la
<b>valeur maximum d'ex�cution d'un script</b> sur le serveur (max_execution_time).
<br />
<br />Selon la taille de la base et selon la configuration du serveur,
la sauvegarde ou la restauration peut �chouer si le temps n�cessaire � cette op�ration est sup�rieur
au temps maximum autoris� pour l'ex�cution d'un script (max_execution_time).
<br />
Un message du type "Maximum execution time exceeded" appara�t alors, vous indiquant que le processus a �chou�.
<br /><br />
Pour palier cela, <b>ce script sauvegarde et restaure "par portions" d'une dur�e fix�e par l'utilisateur</b> en reprenant le processus � l'endroit o� il s'est interrompue pr�c�demment
jusqu'� ce que l'op�ration de sauvegarde ou de restauration soit termin�e.
</td></tr></table>
</div>
<hr />


<?php

$handle=opendir('../backup/' . $dirname);
$tab_file = array();
$n=0;
while ($file = readdir($handle)) {
    if (($file != '.') and ($file != '..') and ($file != 'remove.txt')
    //=================================
    // AJOUT: boireaus
    and ($file != 'csv')
    //=================================
    and ($file != '.htaccess') and ($file != '.htpasswd') and ($file != 'index.html')) {
        $tab_file[] = $file;
        $n++;
    }
}
closedir($handle);
arsort($tab_file);

if ($n > 0) {
    echo "<h3>Fichiers de restauration</h3>\n";
    echo "<p>Le tableau ci-dessous indique la liste des fichiers de restauration actuellement stock�s dans le r�pertoire \"backup\" � la racine de GEPI.</p>\n";
    //echo "<center>\n<table border=\"1\" cellpadding=\"5\" cellspacing=\"1\">\n<tr><td><b>Nom du fichier de sauvegarde</b></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>\n";
    echo "<center>\n<table class='boireaus' cellpadding=\"5\" cellspacing=\"1\">\n<tr><th><b>Nom du fichier de sauvegarde</b></th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>\n";
    $m = 0;
	$alt=1;
    foreach($tab_file as $value) {
        //echo "<tr><td><i>".$value."</i>&nbsp;&nbsp;(". round((filesize("../backup/".$dirname."/".$value)/1024),0)." Ko) </td>\n";
        $alt=$alt*(-1);
		echo "<tr class='lig$alt'><td><i>".$value."</i>&nbsp;&nbsp;(". round((filesize("../backup/".$dirname."/".$value)/1024),0)." Ko) </td>\n";
        echo "<td><a href='accueil_sauve.php?action=sup&amp;file=$value'>Supprimer</a></td>\n";
        echo "<td><a href='accueil_sauve.php?action=restaure_confirm&amp;file=$value'>Restaurer</a></td>\n";
        echo "<td><a href='savebackup.php?fileid=$m'>T�l�charger</a></td>\n";
        echo "<td><a href='../backup/".$dirname."/".$value."'>T�l�ch. direct</a></td>\n";
        echo "</tr>\n";
        $m++;
    }
    clearstatcache();
    echo "</table>\n</center>\n<hr />\n";
}

echo "<h3>Uploader un fichier (de restauration) vers le r�pertoire backup</h3>\n";
echo "<form enctype=\"multipart/form-data\" action=\"accueil_sauve.php\" method=\"post\" name=\"formulaire2\">\n";
$sav_file="";
echo "Les fichiers de sauvegarde sont sauvegard�s dans un sous-r�pertoire du r�pertoire \"/backup\", dont le nom change de mani�re al�atoire r�guli�rement.
Si vous le souhaitez, vous pouvez uploader un fichier de sauvegarde directement dans ce r�pertoire.
Une fois cela fait, vous pourrez le s�lectionner dans la liste des fichiers de restauration, sur cette page.\n";
/*
echo "<br />Selon la configuration du serveur et la taille du fichier, l'op�ration de t�l�chargement vers le r�pertoire \"/backup\" peut �chouer
(par exemple si la taille du fichier d�passe la <b>taille maximale autoris�e lors des t�l�chargements</b>).
<br />Si c'est le cas, signalez le probl�me � l'administrateur du serveur.
<br /><br />Vous pouvez �galement directement t�l�charger le fichier par ftp dans le r�pertoire \"/backup\".";
*/
echo "<br />Vous pouvez �galement directement t�l�charger le fichier par ftp dans le r�pertoire \"/backup\".\n";

echo "<br /><br /><b>Fichier � \"uploader\" </b>: <input type=\"file\" name=\"sav_file\" />
<input type=\"hidden\" name=\"action\" value=\"upload\" />
<input type=\"submit\" value=\"Valider\" name=\"bouton1\" />
</form>
<br />\n";

$post_max_size=ini_get('post_max_size');
$upload_max_filesize=ini_get('upload_max_filesize');
echo "<p><b>Attention:</b></p>\n";
echo "<p style='margin-left: 20px;'>Selon la configuration du serveur et la taille du fichier, l'op�ration de t�l�chargement vers le r�pertoire \"/backup\" peut �chouer
(<i>par exemple si la taille du fichier d�passe la <b>taille maximale autoris�e lors des t�l�chargements</b></i>).
<br />Si c'est le cas, signalez le probl�me � l'administrateur du serveur.</p>\n";

//echo "<table border='1' align='center'>\n";
echo "<table class='boireaus' align='center'>\n";
echo "<tr><th style='font-weight: bold; text-align: center;'>Variable</th><th style='font-weight: bold; text-align: center;'>Valeur</th></tr>\n";
echo "<tr class='lig1'><td style='font-weight: bold; text-align: center;'>post_max_size</td><td style='text-align: center;'>$post_max_size</td></tr>\n";
echo "<tr class='lig-1'><td style='font-weight: bold; text-align: center;'>upload_max_filesize</td><td style='text-align: center;'>$upload_max_filesize</td></tr>\n";
echo "</table>\n";

require("../lib/footer.inc.php");
?>