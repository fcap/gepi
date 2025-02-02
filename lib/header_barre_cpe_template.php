<?php

/**
 * Fichier qui permet de construire la barre de menu cpe des pages utilisant un gabarit
 * 
 * $Id: header_barre_cpe_template.php 7793 2011-08-16 17:39:17Z crob 
 * 
 * Variables envoy�es au gabarit
 * - $tbs_menu_admin : liens de la barre de menu 
 *
 * @license GNU/GPL v2
 * @package General
 * @subpackage Affichage
 * @see getSettingValue()
 * @see insert_confirm_abandon()
 * @todo R��crire la barre administrateur, le principe des gabarits, c'est d'envoyer des variables aux gabarits, 
 * pas d'�crire du code html dans le constructeur
 */

/* This file is part of GEPI.
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
 * Fichier qui permet de construire la barre de menu professeur
 *
 */
 
 
 
// ====== SECURITE =======

if (!$_SESSION["login"]) {
    header("Location: ../logout.php?auto=2");
    die();
}

// Fonction g�n�rant le menu Plugins
include("menu_plugins.inc.php");
$barre_plugin=menu_plugins();
if ($barre_plugin!="") {
	$barre_plugin = "<li class='li_inline'><a href=\"\">Plugins</a>"."\n"
					."	<ul class='niveau2'>\n"
					.$barre_plugin
					."	</ul>\n"
					."</li>\n";
}
// fin plugins

/*******************************************************************
 *
 *			Construction du menu horizontal de la page d'accueil 
 *			pour le profil administrateur
 *
 *******************************************************************/

	
	if ($_SESSION['statut'] == "cpe") {

		$menus = null;

		if (getSettingValue("active_module_absence") == 'y') {
			$menus .= '<li class="li_inline"><a href="#"'.insert_confirm_abandon().'>&nbsp;Absences</a>'."\n";
			$menus .= '   <ul class="niveau2">'."\n";
			$menus .= '     <li><a href="'.$gepiPath.'/mod_absences/gestion/gestion_absences.php"'.insert_confirm_abandon().'>Gestion absences</a></li>'."\n";
			$menus .= '     <li><a href="'.$gepiPath.'/mod_absences/gestion/voir_absences_viescolaire.php"'.insert_confirm_abandon().'>Visu. absences</a></li>'."\n";
			$menus .= '     <li><a href="'.$gepiPath.'/absences/index.php"'.insert_confirm_abandon().'>Absences bulletins</a></li>'."\n";
			$menus .= '   </ul>'."\n";
			$menus .= '</li>'."\n";
		}
		elseif (getSettingValue("active_module_absence") == '2') {
			$menus .= '<li class="li_inline"><a href="'.$gepiPath.'/mod_abs2/index.php"'.insert_confirm_abandon().'>&nbsp;Absences</a></li>'."\n";
		}

		//=======================================================
		// Module Cahier de textes
		if (getSettingValue("active_cahiers_texte") == 'y') {
			if(getSettingValue('GepiCahierTexteVersion')==2) {
				$menus .= '<li class="li_inline"><a href="'.$gepiPath.'/cahier_texte_2/see_all.php"'.insert_confirm_abandon().'>&nbsp;CDT</a>'."\n";
				$menus .= '   <ul class="niveau2">'."\n";
				$menus .= '     <li><a href="'.$gepiPath.'/cahier_texte_2/see_all.php"'.insert_confirm_abandon().'>Consultation CDT</a></li>'."\n";
			}
			else {
				$menus .= '<li class="li_inline"><a href="'.$gepiPath.'/cahier_texte/see_all.php"'.insert_confirm_abandon().'>&nbsp;CDT</a>'."\n";
				$menus .= '   <ul class="niveau2">'."\n";
				$menus .= '     <li><a href="'.$gepiPath.'/cahier_texte/see_all.php"'.insert_confirm_abandon().'>Consultation CDT</a></li>'."\n";
			}
			$menus .= '   </ul>'."\n";
			$menus .= '</li>'."\n";
		}
		//=======================================================

		if(getSettingValue("active_carnets_notes") == 'y'){
			//=======================================================
			// Bulletins
			$menus .= '<li class="li_inline"><a href="#"'.insert_confirm_abandon().'>&nbsp;Bulletins</a>'."\n";
			$menus .= '   <ul class="niveau2">'."\n";
	
			$menus .= '     <li><a href="'.$gepiPath.'/prepa_conseil/index2.php"'.insert_confirm_abandon().'>Moyennes une classe</a></li>'."\n";
			$menus .= '     <li><a href="'.$gepiPath.'/prepa_conseil/index3.php"'.insert_confirm_abandon().'>Bulletins simplifi�s</a></li>'."\n";
			$menus .= '     <li><a href="'.$gepiPath.'/absences/index.php"'.insert_confirm_abandon().'>Absences bulletins</a></li>'."\n";

			$menus .= '     <li class="plus"><a href="'.$gepiPath.'/visualisation/index.php"'.insert_confirm_abandon().'>Outils graphiques</a>'."\n";
			$menus .= '            <ul class="niveau3">'."\n";
			$menus .= '                <li><a href="'.$gepiPath.'/visualisation/affiche_eleve.php?type_graphe=courbe"'.insert_confirm_abandon().'>Courbe</a></li>'."\n";
			$menus .= '                <li><a href="'.$gepiPath.'/visualisation/affiche_eleve.php?type_graphe=etoile"'.insert_confirm_abandon().'>Etoile</a></li>'."\n";
			$menus .= '                <li><a href="'.$gepiPath.'/visualisation/eleve_classe.php"'.insert_confirm_abandon().'>El�ve/classe</a></li>'."\n";
			$menus .= '                <li><a href="'.$gepiPath.'/visualisation/eleve_eleve.php"'.insert_confirm_abandon().'>El�ve/�l�ve</a></li>'."\n";
			$menus .= '                <li><a href="'.$gepiPath.'/visualisation/evol_eleve.php"'.insert_confirm_abandon().'>Evol. �l�ve ann�e</a></li>'."\n";
			$menus .= '                <li><a href="'.$gepiPath.'/visualisation/evol_eleve_classe.php"'.insert_confirm_abandon().'>Evol. �l�ve/classe ann�e</a></li>'."\n";
			$menus .= '                <li><a href="'.$gepiPath.'/visualisation/stats_classe.php"'.insert_confirm_abandon().'>Evol. moyennes classes</a></li>'."\n";
			$menus .= '                <li><a href="'.$gepiPath.'/visualisation/classe_classe.php"'.insert_confirm_abandon().'>Classe/classe</a></li>'."\n";
			$menus .= '            </ul>'."\n";
			$menus .= '     </li>'."\n";
	
			$menus .= '   </ul>'."\n";
			$menus .= '</li>'."\n";
			//=======================================================
	
			//=======================================================
			// Carnets de notes
			$menus .= '<li class="li_inline"><a href="#"'.insert_confirm_abandon().'>&nbsp;Carnets de notes</a>'."\n";
			$menus .= '   <ul class="niveau2">'."\n";
			$menus .= '       <li><a href="'.$gepiPath.'/cahier_notes/visu_releve_notes_bis.php"'.insert_confirm_abandon().'>Relev�s de notes</a></li>'."\n";
			$menus .= '       <li><a href="'.$gepiPath.'/cahier_notes/index2.php"'.insert_confirm_abandon().'>Moyennes des CN</a></li>'."\n";
			$menus .= '   </ul>'."\n";
			$menus .= '</li>'."\n";
			//=======================================================
		}

		//=======================================================
		// Module emploi du temps
		if (getSettingValue("autorise_edt_tous") == "y") {
			$menus .= '<li class="li_inline"><a href="'.$gepiPath.'/edt_organisation/index_edt.php?visioedt=classe1"'.insert_confirm_abandon().'>Emploi du tps</a>'."\n";

			$menus .= '   <ul class="niveau2">'."\n";
			$menus .= '       <li><a href="'.$gepiPath.'/edt_organisation/index_edt.php?visioedt=classe1"'.insert_confirm_abandon().'>EDT classe</a></li>'."\n";
			$menus .= '       <li><a href="'.$gepiPath.'/edt_organisation/index_edt.php?visioedt=prof1"'.insert_confirm_abandon().'>EDT prof</a></li>'."\n";
			$menus .= '       <li><a href="'.$gepiPath.'/edt_organisation/index_edt.php?visioedt=salle1"'.insert_confirm_abandon().'>EDT salle</a></li>'."\n";
			$menus .= '       <li><a href="'.$gepiPath.'/edt_organisation/index_edt.php?visioedt=eleve1"'.insert_confirm_abandon().'>EDT �l�ve</a></li>'."\n";
			$menus .= '   </ul>'."\n";
			$menus .= '</li>'."\n";
		}
		//=======================================================

		//=======================================================
		// Module discipline
		if (getSettingValue("active_mod_discipline")=='y') {
			$menus .= '<li class="li_inline"><a href="'.$gepiPath.'/mod_discipline/index.php"'.insert_confirm_abandon().'>Discipline</a></li>'."\n";
		}
		//=======================================================

		//=======================================================
		// Gestion
		$menus .= '<li class="li_inline"><a href="#"'.insert_confirm_abandon().'>&nbsp;El�ves</a>'."\n";
		$menus .= '   <ul class="niveau2">'."\n";
		$menus .= '       <li><a href="'.$gepiPath.'/eleves/visu_eleve.php"'.insert_confirm_abandon().'>Consultation el�ve</a></li>'."\n";
		$menus .= '       <li><a href="'.$gepiPath.'/mod_trombinoscopes/trombinoscopes.php"'.insert_confirm_abandon().'>Trombinoscopes</a></li>'."\n";
		$menus .= '   </ul>'."\n";
		$menus .= '</li>'."\n";
		//=======================================================

		//=======================================================
		$menus .= '<li class="li_inline"><a href="#"'.insert_confirm_abandon().'>&nbsp;Listes</a>'."\n";
		$menus .= '   <ul class="niveau2">'."\n";
		$menus .= '       <li><a href="'.$gepiPath.'/groupes/visu_profs_class.php"'.insert_confirm_abandon().'>Visu. �quipes p�da</a></li>'."\n";
		$menus .= '       <li><a href="'.$gepiPath.'/groupes/visu_mes_listes.php"'.insert_confirm_abandon().'>Visu. mes �l�ves</a></li>'."\n";
		$menus .= '       <li><a href="'.$gepiPath.'/mod_ooo/publipostage_ooo.php"'.insert_confirm_abandon().'>Publipostage OOo</a></li>'."\n";
		$menus .= '       <li><a href="'.$gepiPath.'/impression/impression_serie.php"'.insert_confirm_abandon().'>Impression PDF listes</a></li>'."\n";
		$menus .= '       <li><a href="'.$gepiPath.'/groupes/mes_listes.php"'.insert_confirm_abandon().'>Export CSV listes</a></li>'."\n";
		$menus .= '   </ul>'."\n";
		$menus .= '</li>'."\n";
		//=======================================================

		$menus .= $barre_plugin;

		$tbs_menu_cpe[]=array("li"=> '<li class="li_inline"><a href="'.$gepiPath.'/accueil.php"'.insert_confirm_abandon().'>Accueil</a></li>'."\n");		
		$tbs_menu_cpe[]=array("li"=> $menus);

	}
	//print_r($tbs_menu_cpe);
?>
