<?php
/**
 *
 * @version $Id$
 *
 * Copyright 2001, 2011 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Julien Jocal
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
 *
 */
// S�curit� : �viter que quelqu'un appelle ce fichier seul
$serveur_script = $_SERVER["SCRIPT_NAME"];
$analyse = explode("/", $serveur_script);
$analyse[3] = isset($analyse[3]) ? $analyse[3] : NULL;
	if ($analyse[3] == "serveur_infos.class.php") {
		die();
	}
/**
 * Classe qui renvoie l'ensemble des infos utiles
 * sur les param�tres du serveur
 */
class infos{

	/**
	 * Constructor
	 * @access public
	 */
	public function __construct(){
		// inutile ici
	}

    /**
     * Renvoie un message sur la version de php et teste si elle convient
     *
     * @return string Message
     */
	function versionPhp(){
		$test = phpversion();
		// on teste le premier chiffre
		$version = substr($test, 0, 1);
		if ($version == 5) {
			$retour = '<span style="color: green;">'.phpversion().' (Gepi n�cessite php 5.2.x minimum)</span>';
		}elseif($version == 4 AND substr($test, 2, 2) >= 3){
			$retour = '<span style="color: green;">'.phpversion().'(Attention, Gepi ne fonctionne pas avec cette version, elle est trop ancienne)</span>';
		}else{
			$retour = '<span style="color: red;">'.phpversion().'(version ancienne !)</span>';
		}
		return $retour;
	}
	function versionGd(){
		if (function_exists("gd_info")) {
			$gd = gd_info();
		}else{
			$gd["GD Version"] = false;
		}
		return $gd["GD Version"];
	}
	function versionMysql(){
		$test = mysql_get_server_info();
		// On regarde si c'est une version 4 ou 5
		$version = substr($test, 0, 1);
		if ($version == 4 OR $version == 5) {
			$retour = '<span style="color: green;">'.mysql_get_server_info().'</span>';
		}else{
			$retour = '<span style="color: red;">'.mysql_get_server_info().'(version ancienne !)</span>';
		}
		return $retour;
	}
	function listeExtension(){
		$extensions = get_loaded_extensions();
		//$nbre = count(get_loaded_extensions());
		$nbre = count($extensions);
		$retour = '<table style="border: 1px solid black;" summary="Liste des extensions">';
		for($a = 0; $a < $nbre; $a++){
			//$extensions = get_loaded_extensions();

			sort($extensions);

			$b = $a + 1;
			$c = $a + 2;
			$d = $a + 3;
			$retour .= '<tr>
				<td style="border: 1px solid black;">'.$extensions[$a].'</td>';
			if (isset($extensions[$b])) {
			$retour .= '
				<td style="border: 1px solid black;">'.$extensions[$b].'</td>';
			}else{
				$retour .= '<td>-</td>';
			}
			if (isset($extensions[$c])) {
				$retour .= '
					<td style="border: 1px solid black;">'.$extensions[$c].'</td>';
			}else{
				$retour .= '<td>-</td>';
			}
			if (isset($extensions[$d])) {
				$retour .= '
					<td style="border: 1px solid black;">'.$extensions[$d].'</td>';
			}else{
				$retour .= '<td>-</td>';
			}
				$retour .= '
					</tr>';
			$a = $a + 3;
		}
		$retour .= '</table><br />';

		if(!in_array('pdo_mysql',$extensions)) {$retour.="<span style='color:red'>ATTENTION&nbsp;</span> Il semble que le module 'pdo_mysql' ne soit pas pr�sent.<br />Cela risque de rendre impossible l'utilisation des modules cahier_texte_2, mod_ects, mod_plugins,...<br />";}

		if(in_array('suhosin',$extensions)) {$retour.="<span style='color:red'>ATTENTION&nbsp;</span> Il semble que le module '<b>suhosin</b>' soit pr�sent.<br />Si les restrictions impos�es par ce module sont trop s�v�res, certaines pages de Gepi peuvent �tre perturb�es.<br /><em>Exemple de perturbation&nbsp;:</em> Seule une partie des valeurs des formulaires est transmise parce que le module limite le nombre de variables pouvant �tre envoy�es en POST par un formulaire.<br />";}

		return $retour;
	}
	function memoryLimit(){
		return ini_get('memory_limit');
	}
	function maxSize(){
		return ini_get('post_max_size');
	}
	function maxExecution(){
		return ini_get("max_execution_time");
	}
	function tailleMaxFichier(){
		return ini_get("upload_max_filesize");
	}
	function secureServeur(){
		if (ini_get('register_globals') == 1) {
			$register_g = "on";
		}elseif(ini_get('register_globals') == ''){
			$register_g = "off";
		}else{
			$register_g = "param�tre inconnu";
		}
		$retour = $register_g;

		return $retour;
	}
	function defautCharset(){
		$rep['defaut'] = $rep['toutes'] = NULL;
		if (strpos($_SERVER['HTTP_ACCEPT_CHARSET'], "ISO-8859-1") === 0) {
			$rep['defaut'] = "ISO-8859-1";
		}elseif (strpos($_SERVER['HTTP_ACCEPT_CHARSET'], "utf-8") === 0) {
			$rep['defaut'] = "utf-8";
		}else{
			$rep['defaut'] = "inconnu";
		}

		$rep['toutes'] = $_SERVER['HTTP_ACCEPT_CHARSET'];

		return $rep;
	}
	function version_serveur(){
		if ($_SERVER['SERVER_SOFTWARE']) {
			return $_SERVER['SERVER_SOFTWARE'];
		}else{
			return 'inconnu';
		}
	}
} // fin class infos

?>
