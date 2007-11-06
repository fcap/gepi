<?php

/**
 * Fichier d'initialisation de l'EdT par des fichiers CSV
 *
 * @version Int�gration de ce module en 1.5.1
 * @copyright 2007
 */

$titre_page = "Emploi du temps - Initialisation";
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
// S�curit� suppl�mentaire par rapport aux param�tres du module EdT / Calendrier
if (param_edt($_SESSION["statut"]) != "yes") {
	Die('Vous devez demander � votre administrateur l\'autorisation de voir cette page.');
}
// CSS et js particulier � l'EdT
$javascript_specifique = "edt_organisation/script/fonctions_edt";
$style_specifique = "edt_organisation/style_edt";
//==============PROTOTYPE===============
$utilisation_prototype = "ok";
//============fin PROTOTYPE=============
// On ins�re l'ent�te de Gepi
require_once("../lib/header.inc");

// On ajoute le menu EdT
require_once("./menu.inc.php"); ?>


<br />
<!-- la page du corps de l'EdT -->

	<div id="lecorps">

<?php

 // Initialisation des variables
$action = isset($_POST["action"]) ? $_POST["action"] : NULL;
$csv_file = isset($_FILES["csv_file"]) ? $_FILES["csv_file"] : NULL;
$aff_depart = ""; // pour ne plus afficher le html apr�s une initialisation

	// Initialisation de l'EdT (fichier g_edt.csv). Librement copi� du fichier init_csv/eleves.php
        // On va donc afficher le contenu du fichier tel qu'il va �tre enregistr� dans Gepi
        // en proposant des champs de saisie pour modifier les donn�es si on le souhaite
	if ($action == "upload_file") {
        // On v�rifie le nom du fichier...
        if(strtolower($csv_file['name']) == "g_edt.csv") {

            // Le nom est ok. On ouvre le fichier
            $fp = fopen($csv_file['tmp_name'],"r");

            if(!$fp) {
                // Prob sur l'ouverture du fichier
                echo "<p>Impossible d'ouvrir le fichier CSV !</p>";
                echo "<p><a href=\"./edt_init_csv.php\">Cliquer ici </a> pour recommencer !</center></p>";
            } //!$fp
            else {
            	// A partir de l�, on vide la table edt_cours
            $vider_table = mysql_query("TRUNCATE TABLE edt_cours");
            	// On affiche alors toutes les lignes de tous les champs
            	$nbre = 1;
				while($tab = fgetcsv($fp, 1000, ";")) {
					$num = count($tab);
    				echo "<p> ".$num." champs pour la ligne ".$nbre.": <br /></p>\n";
    				$nbre++;
    				echo '<span class="legende">';
    					for ($c=0; $c < $num; $c++) {
        					echo $tab[$c] . " - \n";
     					}
    				echo '</span> ';
    	// On consid�re qu'il n'y a aucun probl�me dans la ligne
    		$probleme = "";
    // Pour chaque entr�e, on cherche l'id_groupe qui correspond � l'association prof-mati�re-classe
    	// On r�cup�re le login du prof
    	$nom = strtoupper(strtr($tab[0], "����", "eeee"));
    	$prenom = strtoupper(strtr($tab[1], "����", "eeee"));
    $req_prof = mysql_query("SELECT login FROM utilisateurs WHERE nom = '".$nom."' AND prenom = '".$prenom."'");
    $rep_prof = mysql_fetch_array($req_prof);
    	if ($rep_prof == "") {
    		$probleme .="<p>Le professeur n'est pas reconnu.</p>\n";
    	}

		// On r�cup�re l'id de la mati�re et l'id de la classe
		$matiere = strtoupper(strtr($tab[2], "����", "eeee"));
		$sql_matiere = mysql_query("SELECT nom_complet FROM matieres WHERE matiere = '".$matiere."'");
		$rep_matiere = mysql_fetch_array($sql_matiere);
			if ($rep_matiere == "") {
				$probleme .= "<p>Gepi ne retrouve pas la bonne mati&egrave;re.</p>\n";
			}
		$classe = strtoupper(strtr($tab[3], "����", "eeee"));
	$sql_classe = mysql_query("SELECT id FROM classes WHERE classe = '".$classe."'");
	$rep_classe = mysql_fetch_array($sql_classe);
		if ($rep_classe == "") {
			$probleme .= "<p>La classe n'a pas &eacute;t&eacute; trouv&eacute;e.</p>\n";
		}

		// On r�cup�re l'id de la salle
	$sql_salle = mysql_query("SELECT id_salle FROM salle_cours WHERE numero_salle = '".$tab[4]."'");
	$req_salle = mysql_fetch_array($sql_salle);
	$rep_salle = $req_salle["id_salle"];
		if ($rep_salle == "") {
			$probleme .= "<p>La salle n'a pas &eacute;t&eacute; trouv&eacute;e.</p>\n";
		}

		// Le jour et le cr�neau de d�but du cours
	$rep_jour = $tab[5];
		$req_heuredebut = mysql_fetch_array(mysql_query("SELECT id_definie_periode FROM absences_creneaux WHERE heuredebut_definie_periode = '".$tab[6]."'"));
			// le champ heuredeb_dec = 0 par d�faut mais = 0.5 si le cours commence au milieu du cr�neau
		if ($req_heuredebut["id_definie_periode"] == "") {
			$rep_heuredeb_dec = '0.5';
			// On d�termine dans quel cr�neau on est
			$req_creneau = mysql_query("SELECT id_definie_periode FROM absences_creneaux WHERE heuredebut_definie_periode < '".$tab[6]."' AND heurefin_definie_periode > '".$tab[6]."'");
			$rep_creneau = mysql_fetch_array($req_creneau);
				if ($rep_creneau == "") {
					$probleme .= "<p>Le cr&eacute;neau n'a pas &eacute;t&eacute; trouv&eacute;.</p>\n";
				} else {
					$rep_heuredebut = $rep_creneau["id_definie_periode"];
				}
		}
		else {
		$rep_heuredebut = $req_heuredebut["id_definie_periode"];
		$rep_heuredeb_dec = '0';
		}
		// et la dur�e du cours et le type de semaine
	$rep_duree = $tab[7] * 2;
	$rep_typesemaine = $tab[8];
	/*$req_type_sem = mysql_query("SELECT SQL_SMALL_RESULT DISTINCT type_edt_semaine FROM edt_semaines LIMIT 5");
	$rep_type_sem = mysql_fetch_array($req_type_sem);
	$nbre_type_sem = mysql_num_rows($req_type_sem);

		if ($tab[8] == "0" OR $tab[8] == "1" OR $tab[8] == "2") {
			$rep_typesemaine = $tab[8];
		}
		for($a=0; $a<$nbre_type_sem; $a++) {
			if ($rep_type_sem[$a] == $tab[8]) {
				$rep_typesemaine == $tab[8];
			}
			else $rep_typesemaine = "0";
		}*/

		// le champ modif_edt = 0 pour toutes les entr�es
		$rep_modifedt = '0';
		// V�rifier si ce cours dure toute l'ann�e ou seulement durant une p�riode
		if ($tab[9] == "0" OR $tab[10] == "0") {
			$rep_calendar = '0';
		}
		else {
			$req_calendar = mysql_query("SELECT id_calendrier FROM edt_calendrier WHERE jourdebut_calendrier = '".$tab[9]."' AND jourfin_calendrier = '".$tab[10]."'");
			$req_tab_calendar = mysql_fetch_array($req_calendar);
				if ($req_tab_calendar == "") {
					$probleme .= "<p>La p&eacute;riode du calendrier n'a pas &eacute;t&eacute; trouv&eacute;e.</p>\n";
				} else {
					$rep_calendar = $req_tab_calendar[0];
				}
		}

		// On retrouve l'id_groupe et on v�rifie qu'il est unique
	$req_groupe = mysql_query("SELECT jgp.id_groupe FROM j_groupes_professeurs jgp, j_groupes_classes jgc, j_groupes_matieres jgm WHERE jgp.login = '".$rep_prof["login"]."' AND jgc.id_classe = '".$rep_classe["id"]."' AND jgm.id_matiere = '".$matiere."' AND jgp.id_groupe = jgc.id_groupe AND jgp.id_groupe = jgm.id_groupe");
    		$rep_groupe = mysql_fetch_array($req_groupe);
    		if ($rep_groupe == "") {
				$probleme .= "<p>Gepi ne retrouve pas le bon enseignement.</p>\n";
			} else {
    			if (count($req_groupe) > 1) {
    				echo "Cette combinaison renvoie plusieurs groupes : ";
    				for ($a=0; $a<count($rep_groupe); $a++) {
						// Il faut trouver un truc pour que l'admin choisisse le bon groupe
						// Il faut donc afficher les infos sur les groupes en question
						// (liste d'�l�ve, classe, mati�re en question) avec une infobulle.
						echo $rep_groupe[$a]." - ";
					}
    			}
    		} // fin du else

		// Si tout est ok, on rentre la ligne dans la table sinon, on affiche le probl�me
		$insert_csv = "INSERT INTO edt_cours (`id_groupe`, `id_salle`, `jour_semaine`, `id_definie_periode`, `duree`, `heuredeb_dec`, `id_semaine`, `id_calendrier`, `modif_edt`) VALUES ('$rep_groupe[0]', '$rep_salle', '$rep_jour', '$rep_heuredebut', '$rep_duree', '$rep_heuredeb_dec', '$rep_typesemaine', '$rep_calendar', '0')";
			// On v�rifie que les items existent
		if ($rep_groupe[0] != "" AND $rep_jour != "" AND $rep_heuredebut != "" AND $probleme == "") {
			$req_insert_csv = mysql_query($insert_csv);
			echo "<br /><span class=\"accept\">Cours enregistr&eacute;</span><br />\n";
		}
		else {
			$req_insert_csv = "";
			echo "<br /><span class=\"refus\">Ce cours n'est pas reconnu par Gepi.</span>\n".$probleme."<br />";
		}
    	//echo $rep_groupe[0]." salle n�".$tab[4]."(id n� ".$rep_salle["id_salle"]." ) le ".$rep_jour." dans le cr�neau dont l'id est ".$rep_heuredebut." et pour une dur�e de ".$rep_duree." demis-cr�neaux et le calend =".$rep_calendar.".";
				} // while
			} // else du d�but
		fclose($fp);
		// on n'affiche plus le reste de la page
		$aff_depart = "non";
		echo "<hr /><a href=\"./edt_init_csv.php\">Revenir � l'initialisation par csv.</a>";
	} // if ... == "g_edt.csv")
	else
	echo 'Ce n\'est pas le bon nom de fichier, revenez en arri�re en <a href="edt_init_csv.php">cliquant ici</a> !';
} // if ($action == "upload_file")

	// On s'occupe maintenant du fichier des salles
	if ($action == "upload_file_salle") {
        // On v�rifie le nom du fichier...
        if(strtolower($csv_file['name']) == "g_salles.csv") {

            // Le nom est ok. On ouvre le fichier
            $fp=fopen($csv_file['tmp_name'],"r");

            if(!$fp) {
                // Prob sur l'ouverture du fichier
                echo "<p>Impossible d'ouvrir le fichier CSV !</p>";
                echo "<p><a href=\"./edt_init_csv.php\">Cliquer ici </a> pour recommencer !</center></p>";
            } // if (!$fp)...
            else {

            	// On affiche alors toutes les lignes de tous les champs
				while($tab_salle = fgetcsv($fp, 1000, ";")) {
					$numero = htmlentities($tab_salle[0]);
					$nom_brut_salle = htmlentities($tab_salle[1]);
				// On ne garde que les 30 premiers caract�res du nom de la salle
				$nom_salle = substr($nom_brut_salle, 0, 30);
					if ($nom_salle == "") {
						$nom_salle = "Salle ".$numero;
					}
				// On lance la requ�te pour ins�rer les nouvelles salles
				$req_insert_salle = mysql_query("INSERT INTO salle_cours (`numero_salle`, `nom_salle`) VALUES ('$numero', '$nom_salle')");
					if (!$req_insert_salle) {
						echo "La salle : ".$nom_salle." portant le num&eacute;ro : ".$numero." n'a pas &eacute;t&eacute; enregistr&eacute;e.<br />";
					} else {
						echo "La salle : ".$numero." est enregistr&eacute;e(<i> ".$nom_salle."</i>).<br />";
					}
				} // while
			} // else
		fclose($fp);
			// on n'affiche plus le reste de la page
		$aff_depart = "non";
		echo "<hr /><a href=\"./edt_init_csv.php\">Revenir � l'initialisation par csv.</a>";

		} //if(strtolower($csv_file['name']) =....
		else {
			echo '<h3>Ce n\'est pas le bon nom de fichier !</h3>';
			echo "<p><a href=\"./edt_init_csv.php\">Cliquer ici </a> pour recommencer !</center></p>";
		}
	} // if ($action == "upload_file_salle")

	// On pr�cise l'�tat du display du div aff_init_csv en fonction de $aff_depart
	if ($aff_depart == "oui") {
		$aff_div_csv = "block";
	} elseif ($aff_depart == "non") {
		$aff_div_csv = "none";
	} else {
		$aff_div_csv = "block";
	}

	// Pour la liste de <p>, on pr�cise les contenus des infobulles
		$forme_matiere = mysql_fetch_array(mysql_query("SELECT matiere, nom_complet FROM matieres"));
			$aff1_forme_matiere = $forme_matiere["matiere"];
			$aff2_forme_matiere = $forme_matiere["nom_complet"];
	$contenu_matiere = "Attention de bien respecter le nom court utilis&eacute; dans Gepi. Il est de la forme $aff1_forme_matiere pour $aff2_forme_matiere.";
		$forme_classe = mysql_fetch_array(mysql_query("SELECT classe FROM classes WHERE id = '1'"));
		$aff_forme_classe = $forme_classe["classe"];
	$contenu_classe = "Attention de bien respecter le nom court utilis&eacute; dans Gepi. Il est de la forme $aff_forme_classe.";
	$contenu_heuredebut = "Attention de bien respecter la forme <span class='red'>HH:MM:SS</span>. Quand un cours commence au d&eacute;but d'un cr&eacute;neau, ce qui est le cas le plus courant, l'heure doit correspondre � ce qui a &eacute;t&eacute; indiqu&eacute; dans le param&eacute;trage !";
	$contenu_duree = "La dur�e s'exprime en nombre de cr&eacute;neaux occup&eacute;�s. Pour les cours qui durent un cr&eacute;neau et demi, il faut utiliser la forme 1.5 -";
	$contenu_typesemaine = "Par d�faut, ce champ est �gal � 0 pour les cours se d�roulant toutes les semaines. Pour les semaines par quinzaine, pr&eacute;cisez les m�mes types que dans le param&eacute;trage du module absences.";
	$contenu_datedebut = "Pour les cours qui n'ont pas lieu toute l'ann&eacute;e, pr&eacute;cisez la date de d&eacute;but (incluse) du cours sous la forme <span class='red'>AAAA-MM-JJ</span>. Pour les autres cours, ce champ doit �tre = 0.";
	$contenu_datefin = "Pour les cours qui n'ont pas lieu toute l'ann&eacute;e, pr&eacute;cisez la date de fin (incluse) du cours sous la forme <span class='red'>AAAA-MM-JJ</span>. Pour les autres cours, ce champ doit �tre = 0.";
?>
<div id="aff_init_csv" style="display: <?php echo $aff_div_csv; ?>;">
L'initialisation &agrave; partir de fichiers csv se d&eacute;roule en plusieurs &eacute;tapes:

<hr />
	<h4 class='refus'>Premi&egrave;re &eacute;tape</h4>
	<p>Pour &eacute;viter de multiplier les r&eacute;glages, une partie de l'initialisation
	se fait par le module absences : les diff&eacute;rents cr&eacute;neaux de la journ&eacute;e,
	 le type de semaine (paire/impaire, A/B/C, 1/2,...) et les horaires de l'&eacute;tablissement.
	Il faut aller dans le module absences m&ecirc;me si vous ne l'utilisez pas en cliquant
	 sur ce <a href="../mod_absences/admin/index.php">lien</a>, dans la partie intitul&eacute;e
	  "Configuration avanc&eacute;e".</p>


<hr />
	<h4 class='refus'>Deuxi&egrave;me &eacute;tape</h4>
	<p>Il faut renseigner le calendrier en cliquant sur le menu &agrave; gauche. Toutes les p&eacute;riodes
	qui apparaissent dans l'emploi du temps doivent &ecirc;tre d&eacute;finies : trimestres, vacances, ... Si tous vos
	cours durent le temps de l'ann&eacute;e scolaire, vous pouvez vous passer de cette &eacute;tape.</p>
<hr />
	<h4 class='refus'>Troisi&egrave;me &eacute;tape</h4>
	<p>Attention, cette initialisation efface toutes les donn&eacute;es concernant les salles d&eacute;j&agrave; pr&eacute;sentes.
	Pour les salles de votre &eacute;tablissement, vous devez fournir un fichier csv. Vous pourrez ensuite en ajouter, en supprimer ou modifier leur nom dans le menu Gestion des salles.</p>
	<p>Les champs suivants doivent �tre pr�sents, dans l'ordre, <b>s�par�s par un point-virgule et encadr&eacute;s par des guillemets ""</b> (sans ligne d'ent&ecirc;te) :</p>
	<ol>
		<li>num&eacute;ro salle (5 caract&egrave;res max.)</li>
		<li>nom salle (30 caract&egrave;res max.)</li>
	</ol>
	<p>Veuillez pr�ciser le nom complet du fichier <b>g_salles.csv</b>.</p>
	<form enctype='multipart/form-data' action='edt_init_csv.php' method='post'>
		<input type='hidden' name='action' value='upload_file_salle' />
		<input type='hidden' name='initialiser' value='ok' />
		<input type='hidden' name='csv' value='ok' />
		<p><input type="file" size="80" name="csv_file" /></p>
		<p><input type='submit' value='Valider' /></p>
	</form>

<hr />
	<h4 class='refus'>Quatri&egrave;me &eacute;tape</h4>
	<p><span class='red'>Attention</span> de bien respecter les heures, jour, nom de mati&egrave;re,... de Gepi que vous avez pr&eacute;cis&eacute; auparavant.
	Pour l'emploi du temps, vous devez fournir un fichier csv dont les champs suivants
	 doivent �tre pr�sents, dans l'ordre, <b>s�par�s par un point-virgule et encadr&eacute;s par des guillemets ""</b> (sans ligne d'ent&ecirc;te) :</p>
<!-- AIDE init csv -->

<a href="#" onClick="javascript:changerDisplayDiv('aide_initcsv');">
	<img src="../images/info.png" alt="Plus d'infos..." Title="Cliquez pour plus d'infos..." />
</a>
	<div style="display: none;" id="aide_initcsv">
	<hr />
	<span class="red">Attention</span>, ces champs ont des r&egrave;gles &agrave; suivre : il faut respecter la forme retenue par Gepi
	<br />
	<p>Pour la mati&egrave;re, il faut utiliser le nom court qui est de la forme <?php echo "\"".$aff1_forme_matiere."\" pour ".$aff2_forme_matiere; ?>.</p>
	<p>Pour la classe, le nom court est de la forme "<?php echo $aff_forme_classe; ?>".</p>
	<p>Le num&eacute;ro de la salle et le jour doivent correspondre &agrave; des informations existantes d&eacute;j&agrave;
	dans Gepi.</p>
	<p>Pour l'heure de d&eacute;but, la forme <span class='red'>"HH:MM:SS"</span> est imp&eacute;rative. Quand un cours commence au d&eacute;but
	d'un cr&eacute;neau, ce qui est le cas le plus courant, l'heure doit correspondre &agrave; ce qui a &eacute;t&eacute; indiqu&eacute;
	dans le param&eacute;trage !</p>
	<p>La dur&eacute;e s'exprime en nombre de cr&eacute;neaux occup&eacute;s. Pour les cours qui durent un cr&eacute;neau et demi,
	il faut utiliser la forme "1.5" -</p>
	<p>Le type de semaine est �gal � "0" pour les cours se d�roulant toutes les semaines. Pour les semaines par quinzaine,
	pr&eacute;cisez les m&ecirc;mes types que dans le param&eacute;trage du module absences.</p>
	<p>Pour les cours qui n'ont pas lieu toute l'ann&eacute;e, pr&eacute;cisez la date de d&eacute;but (incluse) du cours sous
	la forme <span class='red'>"AAAA-MM-JJ"</span>. Pour les autres cours, ce champ doit &ecirc;tre &eacute;gal &agrave; "0".</p>
	<p>Pour les cours qui n'ont pas lieu toute l'ann&eacute;e, pr&eacute;cisez la date de fin (incluse) du cours sous la forme
	<span class='red'>"AAAA-MM-JJ"</span>. Pour les autres cours, ce champ doit &ecirc;tre &eacute;gal &agrave; "0".</p>
	<hr />
	</div>
<!-- Fin aide init csv -->
	<ol>
	 	<li>nom professeur</li>
		<li>prenom professeur</li>
		<li>matiere</li>
		<li>classe</li>
		<li>numero salle</li>
		<li>jour</li>
		<li>heure debut</li>
		<li>duree</li>
		<li>type semaine</li>
		<li>date debut</li>
		<li>date fin</li>
	</ol>

	<p>Veuillez pr�ciser le nom complet du fichier <b>g_edt.csv</b>.</p>
		<form enctype='multipart/form-data' action='edt_init_csv.php' method='post'>
			<input type='hidden' name='action' value='upload_file' />
			<input type='hidden' name='initialiser' value='ok' />
			<input type='hidden' name='csv' value='ok' />
			<p><input type="file" size="80" name="csv_file" /></p>
			<p><input type='submit' value='Valider' /></p>
		</form>
</div><!-- fin du div aff_init_csv -->
	</div><!-- fin du div lecorps -->

<?php
// inclusion du footer
require("../lib/footer.inc.php");
?>