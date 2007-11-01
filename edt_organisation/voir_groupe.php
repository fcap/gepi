<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2007
 */

$titre_page = "Emploi du temps - Groupes";
$affiche_connexion = 'yes';
$niveau_arbo = 1;

// Initialisations files
require_once("../lib/initialisations.inc.php");

// fonctions edt
require_once("./fonctions_edt.php");

// Resume session
$resultat_session = resumeSession();
if ($resultat_session == 'c') {
   header("Location:utilisateurs/mon_compte.php?change_mdp=yes&retour=accueil#changemdp");
   die();
} else if ($resultat_session == '0') {
    header("Location: ../logout.php?auto=1");
    die();
}

// S�curit�
if (!checkAccess()) {
    header("Location: ../logout.php?auto=2");
    die();
}
// CSS particulier � l'EdT
$style_specifique = "edt_organisation/style_edt";

//++++++++++ l'ent�te de Gepi +++++
require_once("../lib/header.inc");
//++++++++++ fin ent�te +++++++++++
//++++++++++ le menu EdT ++++++++++
require_once("./menu.inc.php");
//++++++++++ fin du menu ++++++++++
?>
<br />
<!-- la page du corps de l'EdT -->

	<div id="lecorps">

<?php
	echo'
	<h3>Voici la liste de tous les enseignements enregistr�s dans la base de Gepi</h3>
	<table class="tab_edt">
	<tbody>
	<tr>
		<th>- id-groupe -</th>
		<th>- Nom -</th>
		<th>- Description -</th>
		<th>- classes -</th>
		<th>- professeurs -</th>
	</tr>';

$req_nbr_group = mysql_query("SELECT id FROM groupes");
$aff_nbr_group = mysql_num_rows($req_nbr_group);

	for($i=0; $i<$aff_nbr_group; $i++) {
		$gr[$i]["id"] = mysql_result($req_nbr_group, $i, "id");
		$groupe_complet = get_group($gr[$i]["id"]);
    		$get_classes = mysql_query("SELECT c.id, c.classe, c.nom_complet, j.priorite, j.coef, j.categorie_id FROM classes c, j_groupes_classes j WHERE (" .
                                    "c.id = j.id_classe and j.id_groupe = '" . $gr[$i]["id"] . "')");
    		$nb_classes = mysql_num_rows($get_classes);

    		$get_profs = mysql_query("SELECT u.login, u.nom, u.prenom, u.civilite FROM utilisateurs u, j_groupes_professeurs j WHERE (" .
                                "u.login = j.login and j.id_groupe = '" . $gr[$i]["id"] . "') ORDER BY u.nom, u.prenom");

    		$nb = mysql_num_rows($get_profs);

		for ($a=0; $a<$nb_classes; $a++) {
			$c_id = $groupe_complet["classes"]["list"][$a];

		for ($b=0; $b<$nb; $b++) {
			$p_login = $groupe_complet["profs"]["list"][$b];


echo '
<tr>
	<td>'.$groupe_complet["id"].'</td>
	<td>'.$groupe_complet["name"].'</td>
	<td>'.$groupe_complet["description"].'</td>
	<td>'.$groupe_complet["classes"]["classes"][$c_id]["classe"].'</td>
	<td>'.$groupe_complet["profs"]["users"][$p_login]["nom"].' '.$groupe_complet["profs"]["users"][$p_login]["prenom"].'</td>
</tr>'."\n";
		}
		}
}
	echo '
	</tbody>
	</table>
	';
/* Fonction qui permet de lire le contenu d'un tableau multidimentionnel
function show_array($array)
{
    for (reset($array); $key = key($array), $pos = pos($array); next($array))
    {
    if(is_array($pos))
    {
        echo "$key : <UL>";
        show_array($pos);
        echo "</UL>";
    }
    else
        echo "$key = $pos <br />";
    }
}
show_array($groupe_complet);*/
?>

	</div>
<br />
<br />
<?php
// inclusion du footer
require("../lib/footer.inc.php");
?>