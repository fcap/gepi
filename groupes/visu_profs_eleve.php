<?php
/*
 * $Id$
 *
 *  Copyright 2001, 2005 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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

// Initialisations files
require_once("../lib/initialisations.inc.php");

// Resume session

$resultat_session = resumeSession();
if ($resultat_session == 'c') {
    header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
    die();
} else if ($resultat_session == '0') {
    header("Location: ../logout.php?auto=1");
    die();
};

if (!checkAccess()) {
    header("Location: ../logout.php?auto=1");
    die();
}

// Quelques filtrages de d�part pour pr�-initialiser la variable qui nous importe ici : $login_eleve
$login_eleve = isset($_GET['login_eleve']) ? $_GET['login_eleve'] : (isset($_POST['login_eleve']) ? $_POST["login_eleve"] : NULL);
if ($_SESSION['statut'] == "responsable") {
	$get_eleves = mysql_query("SELECT e.login " .
			"FROM eleves e, resp_pers r, responsables2 re " .
			"WHERE (" .
			"e.ele_id = re.ele_id AND " .
			"re.pers_id = r.pers_id AND " .
			"r.login = '".$_SESSION['login']."')");
			
	if (mysql_num_rows($get_eleves) == 1) {
		// Un seul �l�ve associ� : on initialise tout de suite la variable $login_eleve
		$login_eleve = mysql_result($get_eleves, 0);
	} elseif (mysql_num_rows($get_eleves) == 0) {
		$login_eleve = false;
	}
	// Si le nombre d'�l�ves associ�s est sup�rieur � 1, alors soit $login_eleve a �t� d�j� d�fini, soit il faut pr�senter le formulaire.
	
} else if ($_SESSION['statut'] == "eleve") {
	// Si l'utilisateur identifi� est un �l�ve, pas le choix, il ne peut consulter que son �quipe p�dagogique
	$login_eleve = $_SESSION['login'];
}

//**************** EN-TETE **************************************
$titre_page = "Equipe p�dagogique";
require_once("../lib/header.inc");
//**************** FIN EN-TETE **********************************

echo "<p class='bold'>";
echo "<a href='../accueil.php'><img src='../images/icons/back.png' alt='Retour' class='back_link'/> Retour</a>";
echo "</p>\n";

// Quelques v�rifications de droits d'acc�s.
if ($_SESSION['statut'] == "responsable" and $login_eleve == false) {
	echo "<p>Il semble que vous ne soyez associ� � aucun �l�ve. Contactez l'administrateur pour r�soudre cette erreur.</p>";
	require "../lib/footer.inc.php";
	die();
}

if (
	($_SESSION['statut'] == "responsable" AND getSettingValue("GepiAccesEquipePedaParent") == "no") OR
	($_SESSION['statut'] == "eleve" AND getSettingValue("GepiAccesEquipePedaEleve") == "no") OR
	($_SESSION['statut'] != "responsable" AND $_SESSION['statut'] != "eleve")
	) {
	echo "<p>Vous n'�tes pas autoris� � visualiser cette page.</p>";
	require "../lib/footer.inc.php";
	die();
}

// Et une autre v�rification de s�curit� : est-ce que si on a un statut 'responsable' le $login_eleve est bien un �l�ve dont le responsable a la responsabilit�
if ($login_eleve != "null" and $_SESSION['statut'] == "responsable") {
	$test = mysql_query("SELECT count(e.login) " .
			"FROM eleves e, responsables2 re, resp_pers r " .
			"WHERE (" .
			"e.login = '" . $login_eleve . "' AND " .
			"e.ele_id = re.ele_id AND " .
			"re.pers_id = r.pers_id AND " .
			"r.login = '" . $_SESSION['login'] . "')");
	if (mysql_result($test, 0) == 0) {
	    echo "Vous ne pouvez visualiser que les relev�s de notes des �l�ves pour lesquels vous �tes responsable l�gal.\n";
	    require("../lib/footer.inc.php");
		die();
	}
}

// Maintenant on arrive au code en lui-m�me.
// On commence par traiter le cas o� il faut s�lectionner un �l�ve (cas d'un responsable de plusieurs �l�ves)

if ($login_eleve == null and $_SESSION['statut'] == "responsable") {
	// Si on est l� normalement c'est parce qu'on a un responsable de plusieurs �l�ves qui n'a pas encore choisi d'�l�ve.
	$quels_eleves = mysql_query("SELECT e.login, e.nom, e.prenom " .
				"FROM eleves e, responsables2 re, resp_pers r WHERE (" .
				"e.ele_id = re.ele_id AND " .
				"re.pers_id = r.pers_id AND " .
				"r.login = '" . $_SESSION['login'] . "')");
	
    echo "<form enctype=\"multipart/form-data\" action=\"visu_profs_eleve.php\" method=\"post\">\n";
	echo "<p clas='bold'>El�ve : ";
	echo "<select size=\"1\" name=\"login_eleve\">";
	while ($current_eleve = mysql_fetch_object($quels_eleves)) {
                echo "<option value=" . $current_eleve->login . ">" . $current_eleve->prenom . " " . $current_eleve->nom . "</option>";
	}
	echo "</select>";
	echo "<br /><br /><center><input type='submit' value='Valider' /></center>\n";
    echo "</form>\n";

} else {
	// On a un �l�ve. On affiche l'�quipe p�dagogique !
	$eleve = mysql_query("SELECT e.nom, e.prenom FROM eleves e WHERE e.login = '".$login_eleve."'");
	$nom_eleve = mysql_result($eleve, 0, "nom");
	$prenom_eleve = mysql_result($eleve, 0, "prenom");
	$id_classe = mysql_result(mysql_query("SELECT id_classe FROM j_eleves_classes WHERE login = '" . $login_eleve ."' LIMIT 1"), 0);
	
	
   	echo "<h3>Equipe p�dagogique de l'�l�ve : ".$prenom_eleve ." " . $nom_eleve . "</h3>\n";
   	
    echo "<table border='0'>\n";
    
    // On commence par le CPE
    $req = mysql_query("SELECT DISTINCT u.nom,u.prenom,u.email,jec.cpe_login " .
    		"FROM utilisateurs u,j_eleves_cpe jec " .
    		"WHERE jec.e_login='".$login_eleve."' AND " .
    		"u.login=jec.cpe_login " .
    		"ORDER BY jec.cpe_login");
    // Il ne doit y en avoir qu'un...
    $cpe = mysql_fetch_object($req);
    echo "<tr valign='top'><td>VIE SCOLAIRE</td>\n";
    echo "<td>";
    // On affiche l'email s'il est non nul et si l'utilisateur est autoris�
    if($cpe->email!="" AND (
    	($_SESSION['statut'] == "responsable" AND getSettingValue("GepiAccesEquipePedaEmailParent") == "yes") OR
    	($_SESSION['statut'] == "eleve" AND getSettingValue("GepiAccesEquipePedaEmailEleve") == "yes")
    	)){
        echo "<a href='mailto:".$cpe->email."?".urlencode("subject=[GEPI] eleve : ".$prenom_eleve . " ".$nom_eleve)."'>".$cpe->nom . " ".ucfirst(strtolower($cpe->prenom))."</a>";
    } else {
        echo $cpe->nom." ".ucfirst(strtolower($cpe->prenom));
    }
    echo "</td></tr>\n";

	// On passe maintenant les groupes un par un, sans se pr�occuper de la p�riode : on affiche tous les groupes
	// auxquel l'�l�ve appartient ou a appartenu
	$groupes = mysql_query("SELECT DISTINCT jeg.id_groupe, m.nom_complet " .
							"FROM j_eleves_groupes jeg, matieres m, j_groupes_matieres jgm, j_groupes_classes jgc WHERE " .
							"jeg.login = '".$login_eleve."' AND " .
							"m.matiere = jgm.id_matiere AND " .
							"jgm.id_groupe = jeg.id_groupe AND " .
							"jgc.id_groupe = jeg.id_groupe AND " .
							"jgc.id_classe = '".$id_classe . "' " .
							"ORDER BY jgc.priorite, m.matiere");
	while ($groupe = mysql_fetch_object($groupes)) {
		// On est dans la boucle 'groupes'. On traite les groupes un par un.
        
        // Mati�re correspondant au groupe:
        echo "<tr valign='top'><td>".htmlentities($groupe->nom_complet)."</td>\n";
        
        // Professeurs
        echo "<td>";
        $sql="SELECT jgp.login,u.nom,u.prenom,u.email FROM j_groupes_professeurs jgp,utilisateurs u WHERE jgp.id_groupe='".$groupe->id_groupe."' AND u.login=jgp.login";
        $result_prof=mysql_query($sql);
        while($lig_prof=mysql_fetch_object($result_prof)){
		    if($lig_prof->email!="" AND (
		    	($_SESSION['statut'] == "responsable" AND getSettingValue("GepiAccesEquipePedaEmailParent") == "yes") OR
		    	($_SESSION['statut'] == "eleve" AND getSettingValue("GepiAccesEquipePedaEmailEleve") == "yes")
		    	)){
                echo "<a href='mailto:$lig_prof->email?".urlencode("subject=[GEPI] eleve : ".$prenom_eleve . " " . $nom_eleve)."'>$lig_prof->nom ".ucfirst(strtolower($lig_prof->prenom))."</a>";
            }
            else{
                echo "$lig_prof->nom ".ucfirst(strtolower($lig_prof->prenom));
            }

            // Le prof est-il PP de l'�l�ve ?
            $sql="SELECT * FROM j_eleves_professeurs WHERE login = '".$login_eleve."' AND professeur='".$lig_prof->login."'";
            $res_pp=mysql_query($sql);
            if(mysql_num_rows($res_pp)>0){
                 echo " (<i>".getSettingValue('gepi_prof_suivi')."</i>)";
            }
            echo "<br />\n";
        }
        echo "</td>\n";
        echo "</tr>\n";	
	}
	// On a fini le traitement.
	echo "</table>\n";
   	
}

require "../lib/footer.inc.php";
?>