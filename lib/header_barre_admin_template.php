<?php

/*
 * $Id: header_barre_admin_template.php $
 *
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
 * Fichier qui permet de construire la barre de menu professeur
 *
 */
 
 
/* ---------Variables envoy�es au gabarit
*	----- tableaux -----
* $tbs_menu_admin										liens se la barre de menu prof
*				-> li
*

$TBS->MergeBlock('tbs_menu_prof',$tbs_menu_prof) ;

unset($tbs_menu_prof);
*/
 
// ====== SECURITE =======

if (!$_SESSION["login"]) {
    header("Location: ../logout.php?auto=2");
    die();
}

// Fonction g�n�rant le menu Plugins
include("menu_plugins.inc.php");
	
/*******************************************************************
 *
 *			Construction du menu horizontal de la page d'accueil 
 *			pour le profil administrateur
 *
 *******************************************************************/
	
	
	if ($_SESSION['statut'] == "administrateur") {

		$menus = null;
		$menus .= '<li class="li_inline"><a href="#">&nbsp;Initialisation</a>'."\n";
		$menus .= '   <ul class="niveau2">'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/init_csv/index.php">Initialisation csv</a></li>'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/inti_xml2/index.php">Initialisation xml</a></li>'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/gestion/modify_impression.php">Fiches bienvenue</a></li>'."\n";
		$menus .= '   </ul>'."\n";
		$menus .= '</li>'."\n";
		$menus .= '<li class="li_inline"><a href="#">&nbsp;Param�tres</a>'."\n";
		$menus .= '   <ul class="niveau2">'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/gestion/param_gen.php">Config. g�n�rale</a></li>'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/mod_serveur/test_serveur.php">Config. serveur</a></li>'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/gestion/droits_acces.php">Droits d\'acc�s</a></li>'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/gestion/options_connect.php">Options connexions</a></li>'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/gestion/config_prefs.php">Interface Profs</a></li>'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/gestion/param_couleurs.php">Couleurs</a></li>'."\n";
		$menus .= '     <li><a href="'.$gepiPath.'/gestion/param_ordre_item.php">Ordre des menus</a></li>'."\n";
		$menus .= '   </ul>'."\n";
		$menus .= '</li>'."\n";
		$menus .= '<li class="li_inline"><a href="#">&nbsp;Maintenance</a>'."\n";
		$menus .= '  <ul class="niveau2">'."\n";
		$menus .= '    <li><a href="'.$gepiPath.'/gestion/accueil_sauve.php">Sauvegardes</a></li>'."\n";
		$menus .= '    <li><a href="'.$gepiPath.'/utilitaires/maj.php">Mise � jour</a></li>'."\n";
		$menus .= '    <li><a href="'.$gepiPath.'/utilitaires/clean_tables.php">Nettoyage</a></li>'."\n";
		$menus .= '    <li><a href="'.$gepiPath.'/gestion/efface_base.php">Effacer la base</a></li>'."\n";
		$menus .= '    <li><a href="'.$gepiPath.'/gestion/efface_photos.php">Effacer les photos</a></li>'."\n";
		$menus .= '    <li><a href="'.$gepiPath.'/gestion/gestion_temp_dir.php">Dossiers temp.</a></li>'."\n";
		$menus .= '</ul>'."\n";
		$menus .= '</li>'."\n";
		$menus .= '<li class="li_inline"><a href="#">&nbsp;Donn�es</a>'."\n";
		$menus .= '  <ul class="niveau2">'."\n";
		$menus .= '        <li ><a href="'.$gepiPath.'/responsables/maj_import.php">Maj Sconet</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/etablissements/index.php">Etablissements</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/matieres/index.php">Mati�res</a></li>'."\n";
		$menus .= '        <li class="plus"><a href="'.$gepiPath.'/utilisateurs/index.php">Utilisateurs</a>'."\n";
		$menus .= '            <ul class="niveau3">'."\n";
		$menus .= '                <li><a href="'.$gepiPath.'/utilisateurs/index.php?mode=personnels">Personnels</a></li>'."\n";
		$menus .= '                <li><a href="'.$gepiPath.'/utilisateurs/edit_responsable.php">Resp. l�gaux</a></li>'."\n";
		$menus .= '                <li><a href="'.$gepiPath.'/utilisateurs/edit_eleve.php">El�ves</a></li>'."\n";
		$menus .= '            </ul>'."\n";		
		$menus .= '        </li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/groupes/visu_profs_class.php">Equipes p�da</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/eleves/index.php">El�ves</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/eleves/visu_eleve.php">Fiches �l�ves</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/responsables/index.php">Resp. l�gaux</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/classes/index.php">Classes</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/aid/index.php">AID</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/mod_trombinoscopes/trombinoscopes_admin.php#gestion_fichiers">Trombinoscopes</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/mef/admin_mef.php">MEF</a></li>'."\n";
		$menus .= '        <li><a href="'.$gepiPath.'/gestion/gestion_base_test.php">Donn�es de tests</a></li>'."\n";
		$menus .= '  </ul>'."\n";
		$menus .= '</li>'."\n";
		$menus .= '<li class="li_inline"><a href="#">&nbsp;Modules</a>'."\n";
		$menus .= '<ul class="niveau2">'."\n";
		$menus .= '  <li class="plus"><a href="'.$gepiPath.'/accueil_modules.php">Param�trages</a>'."\n";
		$menus .= '    <ul class="niveau3">'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/cahier_texte_admin/index.php">Cahier de textes</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/cahier_notes_admin/index.php">Carnets de notes</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_absences/admin/index.php">Absences</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_abs2/admin/index.php">Absences 2</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/edt_organisation/edt.php">Emplois du temps</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_trombinoscopes/trombinoscopes_admin.php">Trombinoscopes</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_notanet/notanet_admin.php">Notanet/Brevet</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_inscription/inscription_admin.php">Inscription</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/cahier_texte_admin/rss_cdt_admin.php">Flux RSS</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/utilisateurs/creer_statut_admin.php">Statuts perso.</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_annees_anterieures/admin.php">Ann�es ant�rieures</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_discipline/discipline_admin.php">Discipline</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_ooo/ooo_admin.php">Mod�les OpenOffice</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_ects/ects_admin.php">Saisie ECTS</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_genese_classes/admin.php">G�n�se des classes</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_epreuve_blanche/admin.php">Epreuves blanches</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_examen_blanc/admin.php">Examens blancs</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_gest_aid/admin.php">Gestionnaires AID</a></li>'."\n";
		$menus .= '    </ul>'."\n";		
		$menus .= '  </li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_absences/gestion/voir_absences_viescolaire.php">Absences</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_trombinoscopes/trombinoscopes.php">Trombinoscopes</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/edt_organisation/index_edt.php">Emplois du temps</a></li>'."\n";
		$menus .= '  <li class="plus"><a href="#">Bulletins</a>'."\n";
		$menus .= '    <ul class="niveau3">'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/bulletin/autorisation_exceptionnelle_saisie_app.php">Droits saisie profs</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/classes/acces_appreciations.php">Droits acc�s �l�ves</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/bulletin/param_bull.php">Param. impression</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/bulletin/bull_index.php">Impression</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/statistiques/index.php">Extractions stats</a></li>'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/bulletin/saisie_mentions.php">'.ucfirst(getSettingValue('gepi_denom_mention')).'s</a></li>'."\n";
		$menus .= '    </ul>'."\n";		
		$menus .= '  </li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_notanet/index.php">Notanet/Brevet</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_annees_anterieures/index.php">Ann�es ant�rieures</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/messagerie/index.php">Panneau d\'affichage</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_ooo/index.php">Mod�les OpenOffice</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_discipline/index.php">Discipline/Sanctions</a></li>'."\n";
		//$menus .= '  <li><a href="'.$gepiPath.'/mod_genese_classes/index.php">G�n�se des classes</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_epreuve_blanche/index.php">Epreuves blanches</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_examen_blanc/index.php">Examens blancs</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/cahier_texte_admin/visa_ct.php">Visa c. de textes</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_inscription/inscription_config.php">Inscriptions</a></li>'."\n";
		$menus .= '  <li><a href="'.$gepiPath.'/mod_genese_classes/index.php">G�n�se des classes</a></li>'."\n";
		$menus .= '</ul>'."\n";	
		$menus .= '</li>'."\n";
		
		$menus .= '<li class="li_inline"><a href="#">&nbsp;Plugins</a>'."\n";
		$menus .= '    <ul class="niveau2">'."\n";
		$menus .= '      <li><a href="'.$gepiPath.'/mod_plugins/index.php">Gestion des plugins</a></li>'."\n";
		$menus.='		'.menu_plugins();
		$menus .= '    </ul>'."\n";		
		$menus .= '</li>'."\n";	
		
		$menus .= '  <li class="li_inline"><a href="'.$gepiPath.'/gestion/index.php">S�curit�</a>'."\n";
		$menus .= '    <ul class="niveau2">'."\n";
		$menus .= '          <li><a href="'.$gepiPath.'/gestion/gestion_connect.php">Connexions</a></li>'."\n";
		$menus .= '          <li><a href="'.$gepiPath.'/gestion/security_panel.php">Alertes</a></li>'."\n";
		$menus .= '          <li><a href="'.$gepiPath.'/gestion/security_policy.php">Politique de s�curit�</a></li>'."\n";
		$menus .= '    </ul>'."\n";
		$menus .= '  </li>'."\n";	

		$tbs_menu_admin[]=array("li"=> '<li class="li_inline"><a href="'.$gepiPath.'/accueil.php">Accueil</a></li>'."\n");		
		$tbs_menu_admin[]=array("li"=> $menus);	
	}

?>
