<?php
/*
 * $Id$
 *
 * Copyright 2001, 2011 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun, Gabriel Fischer
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

// Initialisation des feuilles de style apr�s modification pour am�liorer l'accessibilit�
$accessibilite="y";

// Initialisations files
require_once("../lib/initialisationsPropel.inc.php");
require_once("../lib/initialisations.inc.php");
require_once("../lib/transform_functions.php");

  function aff_debug($tableau){
    echo '<pre>';
    print_r($tableau);
    echo '</pre>';
  }
// Resume session
$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
	header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
	die();
} else if ($resultat_session == '0') {
	header("Location: ../logout.php?auto=1");
	die();
}

if (getSettingValue("GepiCahierTexteVersion") != '2') {
  tentative_intrusion(1, "Tentative d'acc�s au cahier de textes v2 alors qu'il n'est pas ouvert.");
  header("Location: ../cahier_texte/consultation.php");
  die();
}

if (!checkAccess()) {
  header("Location: ../logout.php?auto=1");
  die();
}

//On v�rifie si le module est activ�
if (getSettingValue("active_cahiers_texte")!='y') {
  tentative_intrusion(1, "Tentative d'acc�s au cahier de textes en consultation alors que le module n'est pas activ�.");
  die("Le module n'est pas activ�.");
}

include "../lib/mincals.inc";

unset($id_classe);
$id_classe = isset($_POST["id_classe"]) ? $_POST["id_classe"] : (isset($_GET["id_classe"]) ? $_GET["id_classe"] : NULL);
unset($day);
$day = isset($_POST["day"]) ? $_POST["day"] : (isset($_GET["day"]) ? $_GET["day"] : date("d"));
unset($month);
$month = isset($_POST["month"]) ? $_POST["month"] : (isset($_GET["month"]) ? $_GET["month"] : date("m"));
unset($year);
$year = isset($_POST["year"]) ? $_POST["year"] : (isset($_GET["year"]) ? $_GET["year"] : date("Y"));
unset($id_matiere);
$id_matiere = isset($_POST["id_matiere"]) ? $_POST["id_matiere"] : (isset($_GET["id_matiere"]) ? $_GET["id_matiere"] : -1);
unset($id_groupe);
// modification R�gis : trait� "matiere" au cas o� le javascript est d�sactiv�
$id_groupe = isset($_POST["id_groupe"]) ? $_POST["id_groupe"] :(isset($_GET["id_groupe"]) ? $_GET["id_groupe"] : (isset($_POST['matiere']) ?  substr(strstr($_POST['matiere'],"id_groupe="),10) : (isset($_GET["matiere"]) ?  substr(strstr($_GET["matiere"],"id_groupe="),10) :  NULL)));
if (is_numeric($id_groupe)) {
    $current_group = get_group($id_groupe);
    //if ($id_classe == NULL) $id_classe = $current_group["classes"]["list"][0];
} else {
    $current_group = false;
}

unset($selected_eleve);
// modification R�gis : trait� "eleve" au cas o� le javascript est d�sactiv�
$login_eleve = isset($_POST["login_eleve"]) ? $_POST["login_eleve"] :(isset($_GET["login_eleve"]) ? $_GET["login_eleve"] :(isset($_POST['eleve']) ? substr(strstr($_POST['eleve'],"login_eleve="),12) : (isset($_GET["eleve"]) ? substr(strstr($_GET["eleve"],"login_eleve="),12) :false)));
if ($login_eleve) {
	$selected_eleve = mysql_fetch_object(mysql_query("SELECT e.login, e.nom, e.prenom FROM eleves e WHERE (login = '" . $login_eleve . "')"));
} else {
	$selected_eleve = false;
}

if ($_SESSION['statut'] == 'eleve') {
	// On enregistre si un �l�ve essaie de voir le cahier de texte d'un autre �l�ve
	if ($selected_eleve) {
		if (strtolower($selected_eleve->login) != strtolower($_SESSION['login'])) {tentative_intrusion(2, "Tentative d'un �l�ve d'acc�der au cahier de textes d'un autre �l�ve.");}
	}
	$selected_eleve = mysql_fetch_object(mysql_query("SELECT e.login, e.nom, e.prenom FROM eleves e WHERE login = '".$_SESSION['login'] . "'"));
} elseif ($_SESSION['statut'] == "responsable") {
	$get_eleves = mysql_query("SELECT e.login, e.nom, e.prenom " .
			"FROM eleves e, resp_pers r, responsables2 re " .
			"WHERE (" .
			"e.ele_id = re.ele_id AND " .
			"re.pers_id = r.pers_id AND " .
			"r.login = '".$_SESSION['login']."' AND (re.resp_legal='1' OR re.resp_legal='2'));");

	if (mysql_num_rows($get_eleves) == 1) {
			// Un seul �l�ve associ� : on initialise tout de suite la variable $selected_eleve
			// Cela signifie entre autre que l'on ne prend pas en compte $login_eleve, fermant ainsi une
			// potentielle faille de s�curit�.
		$selected_eleve = mysql_fetch_object($get_eleves);
	} elseif (mysql_num_rows($get_eleves) == 0) {
		$selected_eleve = false;
	} elseif (mysql_num_rows($get_eleves) > 1 and $selected_eleve) {
		// Si on est l�, c'est que la variable $login_eleve a �t� utilis�e pour
		// g�n�rer $selected_eleve
		// On va v�rifier que l'�l�ve ainsi s�lectionn� fait bien partie des �l�ves
		// associ�s � l'utilisateur au statut 'responsable'
		$ok = false;
		while($test = mysql_fetch_object($get_eleves)) {
			if (strtolower($test->login) == strtolower($selected_eleve->login)) {$ok = true;}
		}
		if (!$ok) {
			// Si on est l�, ce qu'un utilisateur au statut 'responsable' a essay�
			// de s�lectionner un �l�ve pour lequel il n'est pas responsable.
			tentative_intrusion(2, "Tentative d'acc�s par un parent au cahier de textes d'un autre �l�ve que le ou les sien(s).");
			$selected_eleve = false;
		}
	}


	if((isset($login_eleve))&&($login_eleve!="")) {
		$sql="SELECT 1=1 FROM resp_pers r, responsables2 re, eleves e WHERE r.pers_id=re.pers_id AND re.ele_id=e.ele_id AND r.login='".$_SESSION['login']."' AND (re.resp_legal='1' OR re.resp_legal='2') AND e.login='".$login_eleve."';";
		//echo "$sql<br />";
		$verif_ele=mysql_query($sql);
		if(mysql_num_rows($verif_ele)==0) {
			tentative_intrusion(2, "Tentative d'acc�s par un parent au cahier de textes d'un autre �l�ve que le ou les sien(s).");
			header("Location: ../logout.php?auto=1");
			die();
			//echo "PB intrusion<br />";
		}
	}
}
$selected_eleve_login = $selected_eleve ? $selected_eleve->login : "";

// Nom complet de la classe
$appel_classe = mysql_query("SELECT classe FROM classes WHERE id='$id_classe'");
$classe_nom = @mysql_result($appel_classe, 0, "classe");
// Nom complet de la mati�re
$matiere_nom = $current_group["matiere"]["nom_complet"];
$matiere_nom_court = $current_group["matiere"]["matiere"];
// V�rification sur les dates
settype($month,"integer");
settype($day,"integer");
settype($year,"integer");
$minyear = strftime("%Y", getSettingValue("begin_bookings"));
$maxyear = strftime("%Y", getSettingValue("end_bookings"));
if ($day < 1) {$day = 1;}
if ($day > 31) {$day = 31;}
if ($month < 1) {$month = 1;}
if ($month > 12) {$month = 12;}
if ($year < $minyear) {$year = $minyear;}
if ($year > $maxyear) {$year = $maxyear;}

// On emp�che un �l�ve ou un parent de voir les CR des jours futurs
  /* --------- Ajout pour les s�quences ---------------- */
if ($_SESSION["statut"] == 'eleve' OR $_SESSION["statut"] == 'responsable'){

  if ($day > date("d")) {$day = date("d");}
  if ($month > date("m")) {$month = date("m");}
  if ($year > date("Y")) {$year = date("Y");}

}

# Make the date valid if day is more then number of days in month
while (!checkdate($month, $day, $year)) $day--;
$today=mktime(0,0,0,$month,$day,$year);
//**************** EN-TETE *****************
$titre_page = "Cahier de textes";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *************


//echo "<p>\$selected_eleve_login=$selected_eleve_login</p>";
//echo "<p>id_classe=$id_classe</p>";
//echo "<p>\$today=$today</p>";
if($selected_eleve_login!=""){
	$sql="SELECT * FROM j_eleves_classes WHERE login='$selected_eleve_login' ORDER BY periode DESC";
	//echo "$sql<br />\n";
	$res_ele_classe=mysql_query($sql);
	if(mysql_num_rows($res_ele_classe)>0){
		$ligtmp=mysql_fetch_object($res_ele_classe);
		//echo "<p>id_classe=$ligtmp->id_classe et periode=$ligtmp->periode</p>";
		$selected_eleve_classe=$ligtmp->id_classe;
	}
}

// On v�rifie que la date demand�e est bien comprise entre la date de d�but des cahiers de texte et la date de fin des cahiers de texte :
if ($today < getSettingValue("begin_bookings")) {
   $today = getSettingValue("begin_bookings");
} else if ($today > getSettingValue("end_bookings")) {
   $today = getSettingValue("end_bookings");
}
echo "<script type=\"text/javascript\" src=\"../lib/clock_fr.js\"></script>\n";
//-----------------------------------------------------------------------------------
//echo "<table width=\"98%\" cellspacing=\"0\" align=\"center\">\n<tr>\n";
//echo "<td valign='top'>\n";
// correction Regis : ajout d'une classe "centre_table", "ct_col_gauche"
echo "<div class=\"centre_table\">\n";
	// echo "<tr>\n";
		echo "<div class=\"ct_col_gauche\">\n";
			echo "<p class=\"menu_retour\">\n";
				echo "<a href=\"../accueil.php\"><img src='../images/icons/back.png' alt='Retour' class='back_link'/>\n";
					echo "Retour � l'accueil\n";
				echo "</a>\n";
			echo "</p>\n";
			echo "<p>Nous sommes le :&nbsp;<br />\n";
			echo "<script type=\"text/javascript\">\n";
			echo "<!--\n";
			echo "new LiveClock();\n";
			echo "//-->";
			echo "\n</script>\n</p>\n";
			echo "<noscript>\n<p>".strftime("%A %d %B %Y", $today)."</p>\n</noscript>";
//<p class='menu_retour'>".get_date_php()."</p>\n</noscript>";
			// On g�re la s�lection de l'�l�ve
			if ($_SESSION['statut'] == 'responsable') {
				echo make_eleve_select_html('consultation.php', $_SESSION['login'], $selected_eleve, $year, $month, $day);
			}
			if ($selected_eleve_login != "") {
				echo make_matiere_select_html('consultation.php', $selected_eleve_login, $id_groupe, $year, $month, $day);
				echo "<a href='see_all.php?&year=$year&amp;month=$month&amp;day=$day&amp;id_groupe=Toutes_matieres'>Voir l'ensemble du cahier de textes</a>";
			}
		echo "</div>\n";
		//echo "<td align=\"right\">\n";
		// Modification R�gis : la colonne de droite doit �tre avant la colonne centrale
		echo "<div class=\"ct_col_droit\">\n";
			echo "<h2 class='invisible'>Calendrier</h2>";
			echo "<form action=\"./consultation.php\" method=\"post\" style=\"width: 100%;\">\n";
				echo "<p>";
					genDateSelector("", $day, $month, $year,'');
					echo "<input type=\"hidden\" name=\"id_groupe\" value=\"$id_groupe\" />\n";
					echo "<input type=\"hidden\" name=\"id_classe\" value=\"$id_classe\" />\n";
					echo "<input type=\"hidden\" name=\"login_eleve\" value=\"$login_eleve\" />\n";
					echo "<input type=\"submit\" value=\"OK\" />\n";
				echo "</p>\n";
			echo "</form>\n";
			//Affiche le calendrier
			minicals($year, $month, $day, $id_groupe, 'consultation.php?');
		echo "</div>\n";
		//echo "<td style=\"text-align:center;\">\n";
		//echo "<td class=\"ct_col_centre\" style=\"text-align:center;\">\n";
		// Modification R�gis : la colonne centrale doit �tre � la fin pour que son contenu se positionne entre les 2 autres
		echo "<div class=\"ct_col_centre\">\n";
			echo "<p>\n";
				echo "<span class='grand'>Cahier de textes";
				if ($current_group) {echo " - $matiere_nom ($matiere_nom_court)";}
				if ($id_classe != null) {echo "<br />$classe_nom";}
				echo "</span>\n";

				// Test si le cahier de texte est partag�
				//if ($current_group) {
				if (($current_group)&&(isset($selected_eleve_classe))) {
					//echo "<br />\n<strong>(";
					echo "<br />\n<strong>(";
					$i=0;
					foreach ($current_group["profs"]["users"] as $prof) {
						if ($i != 0) echo ", ";
						//echo substr($prof["prenom"],0,1) . ". " . $prof["nom"];
						//echo "\$id_classe=$id_classe<br />".$prof["login"]."<br />";
						echo affiche_utilisateur($prof["login"],$selected_eleve_classe);
						$i++;
					}
				  //echo ")</strong>\n";
				  echo ")</strong>\n";
				}

			echo "</p>\n";
		echo "</div>\n";
	//echo "</tr>\n";
echo "</div>\n";
echo "<hr />\n";

// TEST: Est-ce qu'une p�riode au moins est ouverte en saisie dans le cas �l�ve?
if($selected_eleve) {
	$sql="SELECT * FROM periodes p, j_eleves_classes jec WHERE jec.id_classe=p.id_classe AND jec.login=''";


	// Pour les �l�ves qui ont chang� de classe, on risque des infos erron�es si on se base sur la derni�re p�riode ouverte en saisie (si leur classe actuelle est ferm�e pour toutes les p�riodes et que l'ancienne classe est ouverte sur une p�riode... on va r�cup�rer les devoirs de l'autre classe)

}

// Modification Regis : mise en page par CSS des devoirs � faire si la mati�re n'est pas s�lectionn�e

$test_cahier_texte = mysql_query("SELECT contenu FROM ct_entry WHERE (id_groupe='$id_groupe')");
$nb_test = mysql_num_rows($test_cahier_texte);
$delai = getSettingValue("delai_devoirs");
//Affichage des devoirs globaux s'il n'y a pas de notices dans ct_entry � afficher

if (($nb_test == 0) and ($id_classe != null OR $selected_eleve) and ($delai != 0)) {

	//echo "plop";
	//echo "id_classe=$id_classe<br />";

    if ($delai == "") die("Erreur : D�lai de visualisation du travail personnel non d�fini. Contactez l'administrateur de GEPI de votre �tablissement.");
    $nb_dev = 0;
    for ($i = 0; $i <= $delai; $i++) {
        //$aujourhui = $aujourdhui = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $aujourdhui = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $jour = mktime(0, 0, 0, date('m',$aujourdhui), (date('d',$aujourdhui) + $i), date('Y',$aujourdhui) );
        if (is_numeric($id_classe) AND $id_classe > 0) {
	        $sql="SELECT ct.id_sequence, ct.contenu, g.id, g.description, ct.date_ct, ct.id_ct " .
	            "FROM ct_devoirs_entry ct, groupes g, j_groupes_classes jc WHERE (" .
	            "ct.id_groupe = jc.id_groupe and " .
	            "g.id = jc.id_groupe and " .
	            "jc.id_classe = '" . $id_classe . "' and " .
	            "ct.contenu != '' and " .
	            "ct.date_ct = '$jour');";

        } elseif ($selected_eleve) {
			// Le DISTINCT est quand m�me utile parce que si plusieurs p�riodes sont ouvertes en saisie, on a une multiplication des retours par le nombre de p�riodes ouvertes en saisie
	        $sql="SELECT DISTINCT ct.id_sequence, ct.contenu, g.id, g.description, ct.date_ct, ct.id_ct " .
                "FROM ct_devoirs_entry ct, groupes g, j_eleves_groupes jeg, j_eleves_classes jec, periodes p WHERE (" .
                "ct.id_groupe = jeg.id_groupe and " .
                "g.id = jeg.id_groupe and " .
                "jeg.login = '" . $selected_eleve->login . "' and " .
                "jeg.periode = p.num_periode and " .
                "jec.periode = p.num_periode and " .
                "p.verouiller = 'N' and " .
                "p.id_classe = jec.id_classe and " .
                "jec.login = '" . $selected_eleve->login ."' and " .
                "ct.contenu != '' and " .
                "ct.date_ct = '$jour')";
        }
		//echo strftime("%a %d/%m/%y",$jour)."<br />";
		//echo "$sql<br />";
		$appel_devoirs_cahier_texte = mysql_query($sql);
        $nb_devoirs_cahier_texte = mysql_num_rows($appel_devoirs_cahier_texte);
        $ind = 0;
        if ($nb_devoirs_cahier_texte != 0) {
          $nb_dev++;
          if ($nb_dev == '1') {

            // Correction R�gis : cr�ation de classes pour g�rer la mise en page par fichier CSS
            echo "<p class=\"centre_texte no_print\">Date s�lectionn�e : ".strftime("%A %d %B %Y", $today)."\n</p>\n";
            echo "<h2 class=\"centre_texte_pt_cap petit_h2\">Travaux personnels des $delai jours suivant le ".strftime("%d %B %Y", $today)."</h2>\n";

            echo "<div class='cel_trav_futur couleur_bord_tableau_notice color_fond_notices_f'>\n";

          }

          // On range les devoirs les uns � c�t� des autres en fonction du jour
          /**
           * @todo il faudrait inverser le tableau et la m�thode avec mysql_result ne le permet pas facilement
           * pour afficher les devoirs en fonction du jour � la place des uns en dessous des autres.
           */
          //if ($i > 0) {$margin_left = 'margin-left:'.($i * 15).'%;';}else{$margin_left = NULL;}
          //echo "\n".'<div style="position relative;'.$margin_left.'width: 15%;">';
          echo "<h3 class=\"titre_a_faire couleur_bord_tableau_notice color_fond_notices_f color_police_travaux\">\n
                  Travaux personnels pour le ".strftime("%a %d %b", $jour)."</h3>\n";

          // Affichage des devoirs dans chaque mati�re
          while ($ind < $nb_devoirs_cahier_texte) {
            $content = mysql_result($appel_devoirs_cahier_texte, $ind, 'contenu');
            // Mise en forme du texte
            include "../lib/transform.php";
            $date_devoirs = mysql_result($appel_devoirs_cahier_texte, $ind, 'date_ct');
            $id_devoirs =  mysql_result($appel_devoirs_cahier_texte, $ind, 'id_ct');
            $id_groupe_devoirs = mysql_result($appel_devoirs_cahier_texte, $ind, 'id');
            $_id_sequence = mysql_result($appel_devoirs_cahier_texte, $ind, 'id_sequence');
            $matiere_devoirs = mysql_result($appel_devoirs_cahier_texte,$ind, 'description');
            //$test_prof = "SELECT nom, prenom FROM j_groupes_professeurs j, utilisateurs u WHERE (j.id_groupe='".$id_groupe_devoirs."' and u.login=j.login) ORDER BY nom, prenom";
            $test_prof = "SELECT nom, prenom,u.login FROM j_groupes_professeurs j, utilisateurs u 
                                                      WHERE (j.id_groupe='".$id_groupe_devoirs."' and u.login=j.login)
                                                      ORDER BY nom, prenom";
            $res_prof = sql_query($test_prof);
            $chaine = "";
            for ($k=0 ; $prof=sql_row($res_prof,$k) ; $k++) {
              if ($k != 0) $chaine .= ", ";
              //$chaine .= htmlspecialchars($prof[0])." ".substr(htmlspecialchars($prof[1]),0,1).".";
            	// ???????????????????????????????
        			// Faudrait-il modifier ici pour utiliser
            	$chaine.=affiche_utilisateur($prof[2],$selected_eleve_classe);
              // Comment est utilis� $chaine???
              // ???????????????????????????????
            }

            // On ajoute le nom de la s�quence si elle existe
            // On n'utilise pas les objets propel pour ne pas surcharger mais il faudra r��crire avec
            $aff_titre_seq = NULL;
            if ($_id_sequence != '0'){
              $sql_seq        = "SELECT titre FROM ct_sequences WHERE id = '".$_id_sequence."'";
              $query_seq      = mysql_query($sql_seq);
              $rep_seq        = mysql_fetch_array($query_seq);
              $aff_titre_seq  = '<p class="bold"> - <em>' . $rep_seq["titre"] . '</em> - </p>';
            }

            $html = "<div class=\"matiere_a_faire couleur_bord_tableau_notice couleur_cellule_f color_police_matieres\">\n
                      <h4 class=\"souligne\">".$matiere_devoirs." (".$chaine."):</h4>\n".$aff_titre_seq."".$html;
            // fichier joint
            $html .= affiche_docs_joints($id_devoirs,"t");
            $html .="</div>";
            if ($nb_devoirs_cahier_texte != 0)
                echo $html;
            $ind++;
          }
        	//echo "</div>";
        }
    }

    if ($nb_dev != 0) echo "</div>";
	require("../lib/footer.inc.php");
    die();
    //Affichage page de garde
} elseif ($nb_test == 0) {
	//echo "<center>"; correction R�gis : balise <center> d�pr�ci�e
	if ($_SESSION['statut'] == "responsable") {
		echo "<p class='gepi_garde'>Choisissez un �l�ve et une mati�re.</p>\n"; //correction R�gis : h3 doit venir apr�s h1 et h2
	} elseif ($_SESSION['statut'] == "eleve") {
		echo "<p class='gepi_garde'>Choisissez une mati�re</p>\n";
	} else {
		echo "<p class='gepi_garde'>Choisissez une classe et une mati�re.</p>\n";
	}
	//echo "</center>";
	require("../lib/footer.inc.php");
	die();
}
//echo "______________";
// Affichage des comptes rendus et des travaux � faire.

// Modification Regis : mise en page sur 2 colonnes par CSS

// echo "<table width=\"98%\" border=\"0\" align=\"center\">\n";
// echo "<table class=\"centre_cont_texte\" summary=\"Tableau des comptes rendus de travaux � effectuer\">\n";

// ---------------------------- D�but du conteneur 2 colonnes (div) ----

echo "<div class=\"centre_cont_texte\">\n";

// Premi�re colonne : affichage du 'travail � faire' � venir
//echo "<tr><td width=\"30%\" valign=\"top\">\n";
// correction Regis : mise en page d�plac�e dans ccs
//   echo "<tr>\n";

// ---------------------------- D�but de la colonne de gauche (div div)  ----

    echo "<div class=\"cct_gauche\">\n";
	 // ?????????????????????????????????????????????????????????
// ---------------------------- Lien vers see_all.php  ----
	   echo "<a href='see_all.php?id_classe=$id_classe&amp;login_eleve=$selected_eleve_login&amp;id_groupe=$id_groupe'>Voir l'ensemble du cahier de textes</a>\n<br />\n";
	// Cela provoque une d�connexion de l'�l�ve et le compte est rendu 'inactif'???
	// ?????????????????????????????????????????????????????????

// ---------------------------- Affichage des devoirs  ---

      if ($delai == "") die("Erreur : D�lai de visualisation des devoirs non d�fini. Contactez l'administrateur de GEPI de votre �tablissement.");
// Si l'affichage des devoirs est activ�e, on affiche les devoirs
      if ($delai != 0) {
// Affichage de la semaine en cours
        $nb_dev = 0;
        for ($i = 0; $i <= $delai; $i++) {
          $jour = mktime(0, 0, 0, date('m',$today), (date('d',$today) + $i), date('Y',$today) );
        // On regarde pour chaque jour, s'il y a des devoirs dans � faire
          if ($selected_eleve) {
			// On d�termine la p�riode active, pour ne pas avoir de duplication des entr�es
			// Le DISTINCT est quand m�me utile parce que si plusieurs p�riodes sont ouvertes en saisie, on a une multiplication des retours par le nombre de p�riodes ouvertes en saisie
	         $sql="SELECT DISTINCT ct.id_sequence, ct.contenu, g.id, g.description, ct.date_ct, ct.id_ct " .
                "FROM ct_devoirs_entry ct, groupes g, j_eleves_groupes jeg, j_eleves_classes jec, periodes p WHERE (" .
                "ct.id_groupe = jeg.id_groupe and " .
                "g.id = jeg.id_groupe and " .
                "jeg.login = '" . $selected_eleve->login . "' and " .
                "jeg.periode = p.num_periode and " .
                "jeg.periode = jec.periode and " .
                "p.verouiller = 'N' and " .
                "p.id_classe = jec.id_classe and " .
                "jec.login = '" . $selected_eleve->login ."' and " .
                "ct.contenu != '' and " .
                "ct.date_ct = '$jour');";
          } else {
	         $sql="SELECT ct.id_sequence, ct.contenu, g.id, g.description, ct.date_ct, ct.id_ct " .
	             "FROM ct_devoirs_entry ct, groupes g, j_groupes_classes jgc WHERE (" .
	             "ct.id_groupe = jgc.id_groupe and " .
	             "g.id = jgc.id_groupe and " .
	             "jgc.id_classe = '" . $id_classe . "' and " .
	             "ct.contenu != '' and " .
	             "ct.date_ct = '$jour');";
          }
		//echo strftime("%a %d/%m/%y",$jour)."<br />";
		//echo "$sql<br /><br />";
			$appel_devoirs_cahier_texte = mysql_query($sql);
          $nb_devoirs_cahier_texte = mysql_num_rows($appel_devoirs_cahier_texte);
          $ind = 0;
          if ($nb_devoirs_cahier_texte != 0) {
            $nb_dev++;
            if ($nb_dev == '1') {
              if ((strftime("%a",$today) == "lun") or (strftime("%a",$today) == "lun.")) {$debutsemaine = $today;}
              if ((strftime("%a",$today) == "mar") or (strftime("%a",$today) == "mar.")) {$debutsemaine = mktime(0, 0, 0, date('m',$today), (date('d',$today) - 1), date('Y',$today) );}
              if ((strftime("%a",$today) == "mer") or (strftime("%a",$today) == "mer.")) {$debutsemaine = mktime(0, 0, 0, date('m',$today), (date('d',$today) - 2), date('Y',$today) );}
              if ((strftime("%a",$today) == "jeu") or (strftime("%a",$today) == "jeu.")) {$debutsemaine = mktime(0, 0, 0, date('m',$today), (date('d',$today) - 3), date('Y',$today) );}
              if ((strftime("%a",$today) == "ven") or (strftime("%a",$today) == "ven.")) {$debutsemaine = mktime(0, 0, 0, date('m',$today), (date('d',$today) - 4), date('Y',$today) );}
              if ((strftime("%a",$today) == "sam") or (strftime("%a",$today) == "sam.")) {$debutsemaine = mktime(0, 0, 0, date('m',$today), (date('d',$today) - 5), date('Y',$today) );}
              if ((strftime("%a",$today) == "dim") or (strftime("%a",$today) == "dim.")) {$debutsemaine = mktime(0, 0, 0, date('m',$today), (date('d',$today) - 6), date('Y',$today) );}
              $finsemaine = mktime(0, 0, 0, date('m',$debutsemaine), (date('d',$debutsemaine) + 6), date('Y',$debutsemaine) );
 //echo "<p><strong><font color='blue' style='font-variant: small-caps;'>Semaine du ".strftime("%d %B", $debutsemaine)." au ".strftime("%d %B %Y", $finsemaine)."</font></strong></p>\n";
//echo "<strong>Travaux personnels des $delai prochains jours</strong>\n";
//echo "<table style=\"border-style:solid; border-width:0px; border-color: ".$couleur_bord_tableau_notice.";\" width = '100%' cellpadding='2'><tr><td>\n";

// ---------------------------- Affichage de la semaine et du titre  ---

// Correction R�gis : ajout de class pour g�rer la mise en page + <strong> � la place de <strong>
              echo "<p class=\"sem_du_au\"><strong>Semaine du ".strftime("%d %B", $debutsemaine)." au ".strftime("%d %B %Y", $finsemaine)."</strong></p>\n";
              echo "<h2 class='h2_label'><strong>Travaux personnels des $delai prochains jours</strong></h2>\n";

// ---------------------------- Affichage des travaux � faire (div div div)  ---

//              echo "<div class=\"a_faire_gauche\">\n";
              echo "<div class='cel_trav_futur couleur_bord_tableau_notice color_fond_notices_f color_police_travaux'>\n";
//                echo "<tr>\n";
//                  echo "<div>\n";
            }

            //echo "<div style=\"border-style:solid; border-width:1px; border-color: ".$couleur_bord_tableau_notice."; background-color: ".$color_fond_notices["f"].";\"><div style='color: ".$color_police_travaux."; font-variant: small-caps; text-align: center; font-weight: bold;'>Travaux personnels<br />pour le ".strftime("%a %d %b", $jour)."</div>\n";
            echo "<h3 class='titre_a_faire color_police_travaux'>Travaux personnels pour le<br />".strftime("%a %d %b", $jour)."</h3>\n";

            // Affichage des devoirs dans chaque mati�re
            while ($ind < $nb_devoirs_cahier_texte) {
              $content = mysql_result($appel_devoirs_cahier_texte, $ind, 'contenu');
              // Mise en forme du texte
              include "../lib/transform.php";
              $date_devoirs       = mysql_result($appel_devoirs_cahier_texte, $ind, 'date_ct');
              $id_devoirs         = mysql_result($appel_devoirs_cahier_texte, $ind, 'id_ct');
              $id_groupe_devoirs  = mysql_result($appel_devoirs_cahier_texte, $ind, 'id');
              $matiere_devoirs    = mysql_result($appel_devoirs_cahier_texte, $ind, 'description');
              $_id_sequence       = mysql_result($appel_devoirs_cahier_texte, $ind, 'id_sequence');

              $test_prof = "SELECT nom, prenom,u.login FROM j_groupes_professeurs j, utilisateurs u WHERE (j.id_groupe='".$id_groupe_devoirs."' and u.login=j.login) ORDER BY nom, prenom";
              $res_prof = sql_query($test_prof);
              $chaine = "";
              for ($k=0;$prof=sql_row($res_prof,$k);$k++) {
                if ($k != 0) $chaine .= ", ";
                //$chaine .= htmlspecialchars($prof[0])." ".substr(htmlspecialchars($prof[1]),0,1).".";
              $chaine.=affiche_utilisateur($prof[2],$selected_eleve_classe);
              }

              // On ajoute le nom de la s�quence si elle existe
              // On n'utilise pas les objets propel pour ne pas surcharger mais il faudra r��crire avec
              $aff_titre_seq = NULL;
              if ($_id_sequence != '0'){
                $sql_seq        = "SELECT titre FROM ct_sequences WHERE id = '".$_id_sequence."'";
                $query_seq      = mysql_query($sql_seq);
                $rep_seq        = mysql_fetch_array($query_seq);
                $aff_titre_seq  = '<p class="bold"> - <em>' . $rep_seq["titre"] . '</em> - </p>';
              }

// Correction R�gis : ajout de class pour g�rer la mise en page
              $html = "<div class='matiere_a_faire couleur_bord_tableau_notice couleur_cellule_f color_police_matieres'>\n
                  <h4 class='a_faire_titre color_police_matieres'>".$matiere_devoirs." (".$chaine.") :</h4>".$aff_titre_seq."\n<div class='txt_gauche'>\n".$html;
              // fichier joint
              $html .= affiche_docs_joints($id_devoirs,"t");
              $html .="</div>\n</div>\n";
              if ($nb_devoirs_cahier_texte != 0) echo $html;
              $ind++;
            }
			 //echo "</div><br />\n";
			 //echo "</div>\n";
        }
      }
      //if ($nb_dev != 0) echo "</td>\n</tr>\n</table>\n";
      if ($nb_dev != 0) echo "</div>\n";
    }
// ---------------------------- Fin Affichage des travaux � faire (div div /div) ---

// ---------------------------- Affichage des informations g�n�rales (div div div) ---
    $appel_info_cahier_texte = mysql_query("SELECT contenu, id_ct  FROM ct_entry WHERE (id_groupe='$id_groupe' and date_ct='')");

    $nb_cahier_texte = mysql_num_rows($appel_info_cahier_texte);
    $content = @mysql_result($appel_info_cahier_texte, 0, 'contenu');
    $id_ct = @mysql_result($appel_info_cahier_texte, 0, 'id_ct');
    include "../lib/transform.php";
// documents joints
    $html .= affiche_docs_joints($id_ct,"c");
    if ($html != '') {
//echo "<strong>Informations G�n�rales</strong><table style=\"border-style:solid; border-width:1px; border-color: ".$couleur_bord_tableau_notice."; background-color: ".$color_fond_notices["i"]."; padding: 2px; margin: 2px;\" width = '100%' cellpadding='5'><tr style=\"border-style:solid; border-width:1px; border-color: ".$couleur_bord_tableau_notice."; background-color: ".$couleur_cellule["i"]."; padding: 2px; margin: 2px;\"><td>".$html."</td></tr></table><br />\n";
// Correction R�gis : remplacement de <strong> par <strong> + ajout de class pour g�rer la mise en page
	   echo "<h2 class='h2_label'><strong>Informations G�n�rales</strong></h2>\n";
	   echo "<div class='ct_info_generale couleur_bord_tableau_notice color_fond_notices_i'>\n";
//		  echo "<tr class=\"tr_info_generale\">\n";
			 echo "<div class='tr_info_generale couleur_bord_tableau_notice couleur_cellule_i'>".$html."</div>\n\n";
//		  echo "</tr>\n";
	   echo "</div>\n";
// ---------------------------- Fin affichage des informations g�n�rales (div div /div) ---
	   echo "<br />\n";
    }
    echo "</div>\n";
// ----------------------------  Fin de la colonne de gauche (div /div) ---


// ----------------------------  D�but de la deuxi�me de droite (div div) ---
//echo "<td valign=\"top\">";
    echo "<div class=\"cct_droit\">\n";
// ----------------------------  Titre (div div div) --
            echo "<div class='titre_notice'>\n";
              echo "<h2 class='h2_label'><strong>les dix derni�res s�ances jusqu'au ".strftime("%A %d %B %Y", $today)." :</strong></h2>\n";
            echo "</div>\n";
// ----------------------------  Fin titre (div div /div) --

// ----------------------------  Dates (div div div) --
      echo "<div class='cdt_dates'>\n";
// Premi�re ligne
//echo "<tr><td style=\"width:50%\"><strong>" . strftime("%A %d %B %Y", $today) . "</strong>";
//        echo "<tr>\n";
// ----------------------------  Date du jour (div div div div) --
//          echo "<div class='cdt_dates_jour'>\n";
//            echo "<strong>" . strftime("%A %d %B %Y", $today) . "</strong>\n";
//          echo "</div>\n";
// ----------------------------  Fin date du jour (div div div /div) --

#y? sont les ann�e, mois et jour pr�c�dents
#t? sont les ann�e, mois et jour suivants
$i= mktime(0,0,0,$month,$day-1,$year);
$yy = date("Y",$i);
$ym = date("m",$i);
$yd = date("d",$i);
$i= mktime(0,0,0,$month,$day+1,$year);
$ty = date("Y",$i);
$tm = date("m",$i);
$td = date("d",$i);
//echo "</td>\n<td><a title=\"Aller au jour pr�c�dent\" href=\"consultation.php?year=$yy&amp;month=$ym&amp;day=$yd&amp;id_classe=$id_classe&amp;login_eleve=$selected_eleve_login&amp;id_groupe=$id_groupe\"><img src='".$gepiPath."/images/icons/back.png' alt='Jour pr�c�dent'></a></td>\n<td align=\"center\"><a href=\"consultation.php?id_classe=$id_classe&amp;login_eleve=$selected_eleve_login&amp;id_groupe=$id_groupe\">Aujourd'hui</a></td>\n<td align=\"right\"><a title=\"Aller au jour suivant\" href=\"consultation.php?year=$ty&amp;month=$tm&amp;day=$td&amp;id_classe=$id_classe&amp;login_eleve=$selected_eleve_login&amp;id_groupe=$id_groupe\"><img src='".$gepiPath."/images/icons/forward.png' alt='Jour suivant'></a></td>\n</tr>\n";
// correction R�gis : mise en page dans CSS
// ----------------------------  Jour pr�c�dent (div div div div) --
          echo "<div class='cdt_dates_precedent'>\n";
            echo "<a title=\"Aller au jour pr�c�dent\" href=\"consultation.php?year=$yy&amp;month=$ym&amp;day=$yd&amp;id_classe=$id_classe&amp;login_eleve=$selected_eleve_login&amp;id_groupe=$id_groupe\">\n";
              echo "<img src='".$gepiPath."/images/icons/back.png' alt='Jour pr�c�dent' />\n";
            echo "</a>\n";
          echo "</div>\n";
// ----------------------------  Fin jour pr�c�dent (div div div /div) --

// ----------------------------  Aujourd'hui (div div div div) --
          echo "<div class=\"cdt_dates_aujourdhui\">\n";
            echo "<a href=\"consultation.php?id_classe=$id_classe&amp;login_eleve=$selected_eleve_login&amp;id_groupe=$id_groupe\">\n";
              echo "Aujourd'hui\n";
            echo "</a>";
          echo "</div>\n";
// ----------------------------  Fin aujourd'hui (div div div /div) --

// ----------------------------  Jour suivant (div div div div) --
          echo "<div class=\"cdt_dates_suivant droite_texte\">\n";
            echo "<a title=\"Aller au jour suivant\" href=\"consultation.php?year=$ty&amp;month=$tm&amp;day=$td&amp;id_classe=$id_classe&amp;login_eleve=$selected_eleve_login&amp;id_groupe=$id_groupe\">\n";
              echo "<img src='".$gepiPath."/images/icons/forward.png' alt='Jour suivant' />\n";
            echo "</a>\n";
          echo "</div>\n";
// ----------------------------  Fin jour suivant (div div div /div) --
        echo "</div>\n";
// ----------------------------  Fin dates (div div /div) --

// ----------------------------  Notices 1 (div div div) --
//        echo "<div>\n";
//        echo "</tr>\n";

// affichage du texte
//        echo "<tr>\n";
          //  echo "<div>\n";
// echo "<center><strong>les dix derni�res s�ances jusqu'au ".strftime("%A %d %B %Y", $today)." :</strong></center></td>\n</tr>\n";
// echo "<tr><td colspan=\"4\" style=\"border-style:solid; border-width:1px; border-color: ".$couleur_bord_tableau_notice."; background: rgb(199, 255, 153); padding: 2px; margin: 2px;\">";
// echo "<tr>\n<td colspan=\"4\" style=\"border-style:solid; border-width:0px; border-color: ".$couleur_bord_tableau_notice."; padding: 2px; margin: 2px;\">\n";

			   // correction R�gis : mise en page dans CSS
            // echo "<div class=\"centre_texte\">\n";
              // echo "<h2 class='h2_label'><strong>les dix derni�res s�ances jusqu'au ".strftime("%A %d %B %Y", $today)." :</strong></h2>\n";
           //  echo "</div>\n";
          //  echo "</div>\n";
//        echo "</tr>\n";
//echo "<tr><td colspan=\"4\" style=\"border-style:solid; border-width:1px; border-color: ".$couleur_bord_tableau_notice."; background: rgb(199, 255, 153); padding: 2px; margin: 2px;\">";
//        echo "<tr>\n";

// ---------------------------- Toutes les notices (div div div div) --
          echo "<div class=\"ct_jour couleur_bord_tableau_notice\">\n";

          $req_notices =
              "select 'c' type, contenu, date_ct, id_ct, id_sequence
              from ct_entry
              where (contenu != ''
              and id_groupe='$id_groupe'
              and date_ct <= '$today'
              and date_ct != ''
              and date_ct >= '".getSettingValue("begin_bookings")."')
              ORDER BY date_ct DESC, heure_entry DESC limit 10";
          $res_notices = mysql_query($req_notices);
          $notice = mysql_fetch_object($res_notices);

          $req_devoirs =
              "select 't' type, contenu, date_ct, id_ct, id_sequence
              from ct_devoirs_entry
              where (contenu != ''
              and id_groupe = '".$id_groupe."'
              and date_ct != ''
              and date_ct <= '$today'
              and date_ct >= '".getSettingValue("begin_bookings")."'
              and date_ct <= '".getSettingValue("end_bookings")."'
              ) order by date_ct DESC limit 10";
          $res_devoirs = mysql_query($req_devoirs);
          $devoir = mysql_fetch_object($res_devoirs);

          // Boucle d'affichage des notices dans la colonne de gauche
          $date_ct_old = -1;
           while (true) {
            // On met les notices du jour avant les devoirs � rendre aujourd'hui
            if ($notice && (!$devoir || $notice->date_ct >= $devoir->date_ct)) {
                // Il y a encore une notice et elle est plus r�cente que le prochain devoir, o� il n'y a plus de devoirs
                $not_dev = $notice;
                $notice = mysql_fetch_object($res_notices);
              } elseif($devoir) {
                // Plus de notices et toujours un devoir, ou devoir plus r�cent
                $not_dev = $devoir;
                $devoir = mysql_fetch_object($res_devoirs);
              } else {
                // Plus rien � afficher, on sort de la boucle
                break;
              }
              // Passage en HTML
              $content = &$not_dev->contenu;
              include ("../lib/transform.php");
              $html .= affiche_docs_joints($not_dev->id_ct,$not_dev->type);
              $titre = "";
              if ($not_dev->type == "t") {
                $titre .= "<strong>A faire pour le : </strong>\n";
              }
              //$titre .= "<strong>" . strftime("%a %d %b %y", $not_dev->date_ct) . "</strong>\n";
              $titre .= "<strong>" . strftime("%a %d %b %y", $not_dev->date_ct) . "</strong>\n";
              // Num�rotation des notices si plusieurs notice sur la m�me journ�e
            if ($not_dev->type == "c") {
              if ($date_ct_old == $not_dev->date_ct) {
                $num_notice++;
                $titre .= " <stong><em>(notice N� ".$num_notice.")</em></strong>";
              } else {
                // on afffiche "(notice N� 1)" uniquement s'il y a plusieurs notices dans la m�me journ�e
                $nb_notices = sql_query1("SELECT count(id_ct) FROM ct_entry WHERE (id_groupe='" . $current_group["id"] ."' and date_ct='".$not_dev->date_ct."')");
                if ($nb_notices > 1) $titre .= " <strong><em>(notice N� 1)</em></strong>";
                //$titre .= " <strong><i>(notice N� 1)</i></strong>";
                // On r�initialise le compteur
                $num_notice = 1;
              }
            }
            // On ajoute le nom de la s�quence si elle existe
            // On n'utilise pas les objets propel pour ne pas surcharger mais il faudra r��crire avec
            $aff_titre_seq = NULL;
            if ($not_dev->id_sequence != '0'){
              $sql_seq        = "SELECT titre FROM ct_sequences WHERE id = '".$not_dev->id_sequence."'";
              $query_seq      = mysql_query($sql_seq);
              $rep_seq        = mysql_fetch_array($query_seq);
              $aff_titre_seq  = '<p class="bold"> - <em>' . $rep_seq["titre"] . '</em> - </p>';
            }
// ---------------------------- contenu chaque notice (div div div div div) --
            echo "<div class='cdt_une_notice '>\n";
//            echo "<tr>\n";
// ---------------------------- Titre notices (div div div div div div) --
// choisir le fond en fonction de $devoir ou $notice
              	 echo "<div class='cdt_titre_not_dev couleur_bord_tableau_notice color_fond_notices_".$not_dev->type."'>";
             /* if ($not_dev->type == "c") {
                echo "c'>";
              } else {
              	 echo "t'>";
              }*/
              echo "<h3>\n".$titre."</h3>".$aff_titre_seq."\n</div>\n";
// ---------------------------- Fin titre notices (div div div div div /div) --
//            echo "</tr>\n";
// ---------------------------- contenu notices (div div div div div div) --
//            echo "<tr>\n";
              echo "<div class='cdt_fond_not_dev couleur_cellule_gen'>".$html."</div>\n";
// ---------------------------- Fin contenu notices (div div div div div /div) --
//            echo "</tr>\n";
           echo "</div>\n";
// ---------------------------- Fin contenu chaque notice (div div div div /div) --
//           echo "<br />\n";
           if ($not_dev->type == "c") $date_ct_old = $not_dev->date_ct;
          }
          echo "</div>\n";
// ---------------------------- Fin toutes les notices (div div div /div) --
//        echo "</tr>\n";
//      echo "</div>\n";
// ---------------------------- Fin notices 1 (div div /div) --
    echo "</div>\n";
// ---------------------------- Fin de la colonne de droite (div /div) ---
//   echo "</tr>\n";
echo "</div>\n";
// ---------------------------- Fin du conteneur 2 colonnes (/div) --

	require("../lib/footer.inc.php");
?>
