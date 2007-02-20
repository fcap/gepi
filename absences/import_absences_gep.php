<?php
@set_time_limit(0);
/*
 * Last modification  : 17/05/2005
 *
 * Copyright 2001, 2005 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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

//Configuration du calendrier
include("../lib/calendrier/calendrier.class.php");
$cal1 = new Calendrier("form_absences", "display_date_debut");
$cal2 = new Calendrier("form_absences", "display_date_fin");


$id_classe = isset($_POST['id_classe']) ? $_POST['id_classe'] : (isset($_GET['id_classe']) ? $_GET['id_classe'] : NULL);

$periode_num = isset($_POST['periode_num']) ? $_POST['periode_num'] : (isset($_GET['periode_num']) ? $_GET['periode_num'] : NULL);

$step = isset($_POST['step']) ? $_POST['step'] : (isset($_GET['step']) ? $_GET['step'] : NULL);

if (!isset($step)) {
    // On verifie que la table absences_gep est remplie
    $test_abs_gep = mysql_query("select id_seq from absences_gep");
    if (mysql_num_rows($test_abs_gep) == 0) {
        $step_suivant = '1';
    } else {
        $step_suivant = '3';
    }

    // On verife que tous les �l�ves ont un num�ro GEP
    $test = mysql_query("select DISTINCT e.login, e.nom, e.prenom from eleves e, j_eleves_classes j where
    (
    e.login = j.login and
    j.id_classe = '".$id_classe."' and
    e.elenoet = ''
    )
    order by 'e.nom, e.prenom'
    ");
    $nb_test = mysql_num_rows($test);
    if ($nb_test != '0') {
        $step = "0";
    } else {
        // Tous les �l�ves on un num�ro GEP
        // On passe directement � la suite
        header("Location: ./import_absences_gep.php?step=$step_suivant&id_classe=$id_classe&periode_num=$periode_num");
        die();
    }
}


include "../lib/periodes.inc.php";
//**************** EN-TETE *****************
$titre_page = "Outil d'importation des absences � partir du fichier F_EABS.DBF de la base GEP";
require_once("../lib/header.inc");
//**************** FIN EN-TETE *****************
?>
<p class=bold>| <a href="../accueil.php">Accueil</a> | <a href="index.php?id_classe=<?php echo $id_classe; ?>">Retour</a> |</p>

<?php
$call_classe = mysql_query("SELECT classe FROM classes WHERE id = '$id_classe'");
$classe = mysql_result($call_classe, "0", "classe");
?>
<p><b>Classe de <?php echo "$classe"; ?> - Importation des absences : <?php echo $nom_periode[$periode_num]; ?></b></p>

<?php

// On v�rifie si l'extension d_base est active
verif_active_dbase();

if ($step == 0) {
    echo "<b>ATTENTION</b> : les �l�ves suivants ne disposent pas dans la base <b>GEPI</b> de <b>num�ro GEP</b>. Pour ces �l�ves, aucune donn�e sur les absences ne pourra donc �tre import�e.
    <ul>
    <li>Soit continuez l'importation et vous choisissez de remplir ult�rieurement � la main les rubriques absences pour ces �l�ves,</li>
    <li>soit vous devez, avant de proc�der � l'importation des absences, renseigner le num�ro GEP de ces �l�ves en modifiant leur fiche (voir l'administrateur de GEPI).</li>
    </ul>";
    echo "<table border=\"1\" cellpadding=\"5\" cellspacing=\"1\">
    <tr><td><b>Identifiant</b></td><td><b>Nom</b></td><td><b>Pr�nom</b></td></tr>";
    $i = 0;
    while ($i < $nb_test) {
        $login_eleve = mysql_result($test,$i,'e.login');
        $nom_eleve = mysql_result($test,$i,'e.nom');
        $prenom_eleve = mysql_result($test,$i,'e.prenom');
        echo "<tr><td>$login_eleve</td><td>$nom_eleve</td><td>$prenom_eleve</td></tr>";
        $i++;
    }
    echo "</table>";

    echo "<form enctype=\"multipart/form-data\" action=\"import_absences_gep.php\" method=\"post\" name=\"form_absences\">";
    echo "<input type=hidden name='step' value='$step_suivant' />";
    echo "<input type=hidden name='id_classe' value='".$id_classe."' />";
    echo "<input type=hidden name='periode_num' value='".$periode_num."' />";
    echo "<p align=\"center\"><input type=submit value=\"Continuer l'importation\" /></p>";
    echo "</form>";
} else if ($step==1) {
    // On demande le fichier F_NOMA.DBF
    echo "<form enctype=\"multipart/form-data\" action=\"import_absences_gep.php\" method=\"post\" name=\"form_absences\">";
    echo "<p class='bold'>Phase d'importation des s�quences li�es � la matin�e et des s�quences li�es � l'apr�s-midi</p>
    <p>Veuillez pr�ciser le nom complet du fichier <b>F_NOMA.DBF</b> :";
    echo "<input type='file' size='80' name='dbf_file' /></p>";
    echo "<p>(En g�n�ral, le fichier F_NOMA.DBF se trouve dans le r�pertoire parent du r�pertoire contenant le fichier F_EABS.DBF.)</p>";
    echo "<p align=\"center\"><input type=submit value='Valider' /></p>";
    echo "<input type=hidden name='step' value='2' />";
    echo "<input type=hidden name='id_classe' value='".$id_classe."' />";
    echo "<input type=hidden name='periode_num' value='".$periode_num."' />";
    echo "</form>";

    // On verifie que la table absences_gep est remplie
    $test_abs_gep = mysql_query("select id_seq from absences_gep");
    if (mysql_num_rows($test_abs_gep) != 0) {
        echo "<hr /><form enctype=\"multipart/form-data\" action=\"import_absences_gep.php\" method=\"post\" name=\"form_absences\">";
        echo "<p align=\"center\"><input type=submit value=\"Continuer sans proc�der � l'importation\" /></p>";
        echo "<input type=hidden name='step' value='3' />";
        echo "<input type=hidden name='id_classe' value='".$id_classe."' />";
        echo "<input type=hidden name='periode_num' value='".$periode_num."' />";
        echo "</form>";
    }

} else if ($step==2) {
    // On enregistre les donn�es du fichier F_NOMA.DBF dans la table absences_gep

    $dbf_file = isset($_FILES["dbf_file"]) ? $_FILES["dbf_file"] : NULL;
    if(strtoupper($dbf_file['name']) == "F_NOMA.DBF") {
    		if (is_uploaded_file($dbf_file['tmp_name'])) {
	        $fp = dbase_open($dbf_file['tmp_name'], 0);
	        if(!$fp) {
	            echo "<p>Impossible d'ouvrir le fichier dbf</p>";
	            echo "<p><a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=1'>Cliquer ici </a> pour recommencer !</center></p>";
	        } else {
	            // on constitue le tableau des champs � extraire
	            $tabchamps = array("TYPE","CODE","CHOIX");
	            //TYPE : Type de la donn�e
	            //CODE  : Intitul� de la s�quence
	            //CHOIX : M pour matin ou S pour soir

	            $nblignes = dbase_numrecords($fp); //number of rows
	            $nbchamps = dbase_numfields($fp); //number of fields

	            if (@dbase_get_record_with_names($fp,1)) {
	                $temp = @dbase_get_record_with_names($fp,1);
	            } else {
	                echo "<p>Le fichier s�lectionn� n'est pas valide !<br />";
	                echo "<a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=1'>Cliquer ici </a> pour recommencer !</center></p>";
	                die();
	            }

	            $nb = 0;
	            foreach($temp as $key => $val){
	                $en_tete[$nb] = $key;
	                $nb++;
	            }

	            // On range dans tabindice les indices des champs retenus
	            for ($k = 0; $k < count($tabchamps); $k++) {
	                for ($i = 0; $i < count($en_tete); $i++) {
	                    if ($en_tete[$i] == $tabchamps[$k]) {
	                        $tabindice[] = $i;
	                    }
	                }
	            }

	            // On vide la table absences_gep
	            $req = mysql_query("delete from absences_gep");

	            $erreur = 'no';
	            for($k = 1; ($k < $nblignes+1); $k++){
	                $ligne = dbase_get_record($fp,$k);
	                for($i = 0; $i < count($tabchamps); $i++) {
	                    $affiche[$i] = dbase_filter(trim($ligne[$tabindice[$i]]));
	                }
	                // On rep�re les lignes qui sont en rapport avec les s�quences
	                if ($affiche[0] == "S") {
	                   $reg = mysql_query("insert into absences_gep set id_seq='$affiche[1]', type='$affiche[2]'");
	                   if (!$reg) $erreur = 'yes';
	                }
	            }
	            dbase_close($fp);
	            echo "<p class='bold'>Phase d'importation des s�quences li�es � la matin�e et des s�quences li�es � l'apr�s-midi</p>";
	            if ($erreur == 'no') {
	                echo "Les donn�es du fichiers F_NOMA.DBF ont �t� enregistr�es.
	                <br /><b><a href=\"javascript:centrerpopup('seq_gep_absences.php',600,480,'scrollbars=yes,statusbar=no,resizable=yes')\">Visualiser les correspondances entre s�quences et types de demi-journ�es</a></b>";
	                echo "<form enctype=\"multipart/form-data\" action=\"import_absences_gep.php\" method=\"post\" name=\"form_absences\">";
	                echo "<p align=\"center\"><input type=submit value=\"Continuer l'importation\" /></p>";
	                echo "<input type=hidden name='step' value='3' />";
	                echo "<input type=hidden name='id_classe' value='".$id_classe."' />";
	                echo "<input type=hidden name='periode_num' value='".$periode_num."' />";
	                echo "</form>";
	            } else {
	                echo "<b>ATTENTION</b> : Il y a eu une ou plusieurs erreurs lors de l'enregistrement des donn�es du fichier F_NOMA.DBF.";
	            }
	        }
    		}
	} else if (trim($dbf_file['name'])=='') {
        echo "<p>Aucun fichier n'a �t� s�lectionn� !<br />";
        echo "<a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=1'>Cliquer ici </a> pour recommencer !</center></p>";
    } else {
        echo "<p>Le fichier s�lectionn� n'est pas valide !<br />";
        echo "<a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=1'>Cliquer ici </a> pour recommencer !</center></p>";
    }

} else if ($step==3) {
    echo "<form enctype=\"multipart/form-data\" action=\"import_absences_gep.php\" method=\"post\" name=\"form_absences\">";
    echo "<p><b>ATTENTION !</b> VEUILLEZ LIRE CE QUI SUIT</p>";
    echo "<p>L'importation des donn�es relatives aux absences depuis GEP est une manipulation d�licate. En effet, les fichiers de GEP sont au format DBF, mais GEP ne respecte pas ce standard � la lettre.";
    echo "<p>Mise en garde : vous DEVEZ suivre � la lettre la proc�dure d�crite ci-dessous afin d'obtenir une importation fiable.</p>";
    echo "<p class='bold'>Si vous omettez une �tape, aucun message d'erreur ne vous signalera une mauvaise valeur import�e.</p>";
    echo "<p class='bold'>PROCEDURE</p>";
    echo "<ul type='1'><li>R�cup�rez le fichier F_EABS.DBF depuis le r�pertoire de GEP et le copier dans un r�pertoire s�par�.</li>";
    echo "<li>Ouvrez le fichier ainsi copi� dans un tableur (de pr�f�rence OpenOffice.org, mais Excel fonctionne �galement).</li>";
    echo "<li>S�lectionnez l'ensemble des donn�es (Edition->S�lectionner tout), puis effectuez un tri (Donn�es->Trier) sur les colonnes B (ELENOET) et C (ABSDATD) dans l'ordre croissant. N'oubliez pas de mentionner dans les options de tri que la premi�re ligne correspond aux �tiquettes de colonnes.</li>";
    echo "<li>Enregistrer le fichier, en gardant le format d'origine (Fichier->Enregistrer).</li>";
    echo "<li>Chargez le fichier ainsi modifi� (seulement dans sa structure, vous n'avez � changer aucune donn�e) dans le champs ci-dessous</li>";
    echo "<li>Indiquez les limites temporelles prises en compte � l'importation, dans les deux champs dates pr�vus ci-dessous. Attention � bien utiliser le signe / comme d�limiteur entre les jours, mois, et ann�e, comme l'illustrent les dates d�j� visibles dans les champs de saisie.</li>";
    echo "<li>Si la classe pour laquelle vous effectuez l'importation a cours le samedi matin, cochez la case permettant la prise en compte du samedi matin.</li>";
    echo "</ul>";
    echo "<p><b>ATTENTION !</b> Le fichier DBF que vous avez utilis� pour cette importation n'est plus compatible GEP. Une fois les importations effectuez, d�truisez donc ce fichier, et continuer � utiliser l'original avec GEP.";
    echo "<p><b>Note :</b> les �tapes 1 � 4 ne sont � effectuer que lorsque vous repartez du fichier GEP original. Si vous effectuez les op�rations d'importation � la cha�ne pour toutes les classes, vous n'avez � effectuer ces op�rations qu'une seule fois.</p>";
    echo "<p class='bold'>EFFECTUER L'IMPORTATION</p>";
    echo "<ul><li>Importation du fichier <b>F_EABS.DBF</b> contenant les donn�es relatives aux absences : <br />
    veuillez pr�ciser le chemin complet du fichier <b>F_EABS.DBF</b> : ";
    echo "<input type='file'  size='80' name='dbf_file' /><br /><br /></li>";
    echo "<li><b>Choisissez la p�riode (format jj/mm/aaaa) : </b>";
    $annee = strftime("%Y");
    $mois = strftime("%m");
    $jour = strftime("%d");
    if (!isset($_POST['display_date_debut'])) $display_date_debut = $jour."/".$mois."/".$annee;
    if (!isset($_POST['display_date_fin'])) $display_date_fin = $jour."/".$mois."/".$annee;
    echo "<a name=\"calend\"></a>de la date : ";
    echo "<input type='text' name = 'display_date_debut' size='10' value = \"".$display_date_debut."\" />";
    echo "<a href=\"#calend\" onClick=\"".$cal1->get_strPopup('../lib/calendrier/pop.calendrier.php', 350, 170)."\"><img src=\"../lib/calendrier/petit_calendrier.gif\" border=\"0\" alt=\"Calendrier\" /></a>";
    echo "&nbsp;� la date : ";
    echo "<input type='text' name = 'display_date_fin' size='10' value = \"".$display_date_fin."\" />";
    echo "<a href=\"#calend\" onClick=\"".$cal2->get_strPopup('../lib/calendrier/pop.calendrier.php', 350, 170)."\"><img src=\"../lib/calendrier/petit_calendrier.gif\" border=\"0\" alt=\"Calendrier\" /></a>";
    echo "<li>Inclure le samedi matin dans le d�compte des demi-journ�es d'absence <input type=checkbox name='samedi_compte' value='yes' /></li>";
    //echo "</li></ul>";
    echo "</ul>";
    echo "<input type=hidden name='step' value='4' />";
    echo "<input type=hidden name='id_classe' value='".$id_classe."' />";
    echo "<input type=hidden name='periode_num' value='".$periode_num."' />";
    echo "<p align=\"center\"><input type=submit value='Valider' /></p>";
    echo "</form>";
    echo "<hr /><b>Remarque</b><br /><br />Des donn�es, issues du fichier \"F_NOMA.DBF\" (base GEP), concernant les
    <b><a href=\"javascript:centrerpopup('seq_gep_absences.php',600,480,'scrollbars=yes,statusbar=no,resizable=yes')\">correspondances entres s�quences et type de demi-journ�es</a></b>
     sont pr�sentes dans la base GEPI. Si ces donn�es ne sont plus exactes, vous pouvez proc�der � une <b><a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=1'>nouvelle importation</a></b>.";


} else if ($step==4) {

    // On fait quelques tests quand m�me, histoire de voir si les dates saisies sont coh�rentes

    // Extraction des dates de d�but et de fin
    $sep_date_d = explode("/", $_POST['display_date_debut']);
    $sep_date_f = explode("/", $_POST['display_date_fin']);

    $anneed = $sep_date_d['2'];
    $moisd = $sep_date_d['1'];
    $jourd = $sep_date_d['0'];
    $date_d_timestamp = mktime(0, 0, 0, $moisd, $jourd, $anneed);

    $datedebut = strftime("%Y%m%d", $date_d_timestamp);

    $anneef = $sep_date_f['2'];
    $moisf = $sep_date_f['1'];
    $jourf = $sep_date_f['0'];
    $date_f_timestamp = mktime(0, 0, 0, $moisf, $jourf, $anneef);
    $datefin = strftime("%Y%m%d", $date_f_timestamp);

    if ($date_f_timestamp < $date_d_timestamp) {
        echo "<p>La date de fin de la p�riode d'importation pr�c�de la date de d�but ! Veuillez recommencer la saisie des dates.</p>";
        echo "<p><a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=3'>Cliquer ici </a> pour recommencer !</center></p>";
        die();
    }

    // Test sur les dates.

    // traitement du fichier GEP

    // Conctitution du tableau sequence<->type
    $tab_seq = array();
    $sql = mysql_query("select id_seq, type  from absences_gep order by id_seq");
    $i = 0;
    while ($i < mysql_num_rows($sql)) {
        $id_seq = mysql_result($sql,$i,'id_seq');
        $tab_seq[$id_seq] = mysql_result($sql,$i,'type');
        $i++;
    }
    // Constitution du tableau login<->num�ro gep
    $tab = array();
    $abs = array();
    $abs_nj = array();
    $retard = array();
    $req_eleves = mysql_query("select DISTINCT e.login, e.elenoet from eleves e, j_eleves_classes j where (
    e.login = j.login and
    j.id_classe = '".$id_classe."'
    )
    order by e.nom, e.prenom");
    $i = 0;
    while ($i < mysql_num_rows($req_eleves)) {
        $login_eleve = mysql_result($req_eleves,$i,'e.login');
        $elenoet = mysql_result($req_eleves,$i,'e.elenoet');
        if ($elenoet != '') $tab[$login_eleve] = $elenoet;
        $i++;
    }
    // Initialisation des tableaux retard et absences
    foreach ($tab as $key => $value) {
        $abs[$key] = 0;
        $abs_nj[$key] = 0;
        $retard[$key] = 0;
    }
    $dbf_file = isset($_FILES["dbf_file"]) ? $_FILES["dbf_file"] : NULL;
    if ($dbf_file!= null AND !is_uploaded_file($dbf_file['tmp_name'])) $dbf_file = null;
    if(strtoupper($dbf_file['name']) == "F_EABS.DBF") {
        $fp = dbase_open($dbf_file['tmp_name'], 0);
        if(!$fp) {
            echo "<p>Impossible d'ouvrir le fichier dbf</p>";
            echo "<p><a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=3'>Cliquer ici </a> pour recommencer !</center></p>";
        } else {
            $tab_date = array();
            // on constitue le tableau des champs � extraire
            $tabchamps = array("ABSTYPE","ELENOET","ABSDATD","ABSDATF","ABSSEQD","ABSSEQF","ABSHEUR","ABSJUST","ABSMOTI","ABSACTI");
            //ABSTYPE : Absence ou Retard ou Infirmerie
            //ELENOET : num�ro de l'�l�ve
            //ABSDATD : date de d�but de l'absence
            //ABSDATF : date de fin de l'absence
            //ABSSEQD : num�ro de la s�quence de d�but de l'absence
            //ABSSEQF : num�ro de la s�quence de fin de l'absence
            //ABSHEUR : heure de rentr�e dans la cas d'un retard
            //ABSJUST : justification (Oui ou Non)
            //ABSMOTI : Motif
            //ABSACTI : ???? prend les valeurs suivantes AT, LE, CO, ... ?

            $nblignes = dbase_numrecords($fp); //number of rows
            $nbchamps = dbase_numfields($fp);  //number of fields

            if (@dbase_get_record_with_names($fp,1)) {
                $temp = @dbase_get_record_with_names($fp,1);
            } else {
                echo "<p>Le fichier s�lectionn� n'est pas valide !<br />";
                echo "<a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=3'>Cliquer ici </a> pour recommencer !</center></p>";
                die();
            }

            $nb = 0;
            foreach($temp as $key => $val){
                $en_tete[$nb] = $key;
                $nb++;
            }

            // On range dans tabindice les indices des champs retenus
            for ($k = 0; $k < count($tabchamps); $k++) {
                for ($i = 0; $i < count($en_tete); $i++) {
                    if ($en_tete[$i] == $tabchamps[$k]) {
                        $tabindice[] = $i;
                    }
                }
            }
//            echo "<table border=\"1\">";
//            echo "<tr><td>Num.</td><td>ABSTYPE</td><td>ELENOET</td><td>ABSDATD</td><td>ABSDATF</td><td>ABSSEQD</td><td>ABSSEQF</td><td>ABSHEUR</td><td>ABSJUST</td><td>ABSMOTI</td><td>ABSACTI</td></tr>";

            $nb_reg_no = 0;
            $nb_record = 0;
            for($k = 1; ($k < $nblignes+1); $k++){

//                echo "<tr><td>$k</td>";
                $ligne = dbase_get_record($fp,$k);
                for($i = 0; $i < count($tabchamps); $i++) {
                    $affiche[$i] = dbase_filter(trim($ligne[$tabindice[$i]]));
                }

                // premier tri sur les dates
                if (($affiche[2] >= $datedebut) and ($affiche[3] <= $datefin)) {
                    if ($temp=array_search($affiche[1], $tab)) {
                        // on comptabilise les retards
                        if ($affiche[0] == 'R') $retard[$temp]++;
                        // on comptabilise les absences

                        // Prise en compte du changement d'heure !
                        $test_timechange1 = mktime(0, 0, 0, 3, 27, 2005);
                        $test_timechange2 = mktime(0, 0, 0, 10, 30, 2005);
                        $test_timechange3 = mktime(0, 0, 0, 3, 26, 2006);
                        $test_timechange4 = mktime(0, 0, 0, 10, 29, 2006);

                        if (($affiche[0] == 'A') and ($affiche[2] != '') and ($affiche[3] != '') and ($affiche[4] != '') and ($affiche[5] != ''))  {
                            $debut_a = mktime(0, 0, 0, substr($affiche[2],4,2), substr($affiche[2],6,2), substr($affiche[2],0,4));
                            $fin_a = mktime(0, 0, 0, substr($affiche[3],4,2), substr($affiche[3],6,2), substr($affiche[3],0,4));

                            // Prise en compte du changement d'heure
                            if (($test_timechange1 > $debut_a AND $test_timechange1 < $fin_a) OR ($test_timechange3 > $debut_a AND $test_timechange3 < $fin_a)) {
                                $modifier = "3600";
                            } elseif (($test_timechange2 > $debut_a AND $test_timechange2 < $fin_a) OR ($test_timechange4 > $debut_a AND $test_timechange4 < $fin_a)) {
                                $modifier = "-3600";
                            } else {
                                $modifier = "0";
                            }

//                            $nb_demi_jour = (($fin_a - $debut_a)/(60*60*24)+1)*2; // Sans prise en compte du changement d'heure
                            $nb_demi_jour = (($fin_a - $debut_a + $modifier)/(60*60*24)+1)*2; // Avec prise en compte du changement d'heure
//                          echo "<p>Test : " . $affiche[1] . " " . $debut_a . ":" . $fin_a . ":" . $nb_demi_jour ."</p>"; // Quelques tests de d�buggage
                            if ($tab_seq[$affiche[4]] == "S") $nb_demi_jour--;
                            if ($tab_seq[$affiche[5]] == "M") $nb_demi_jour--;

                            // Question de la prise en compte des demie-journ�es de week-end : on filtre les samedi et dimanche.
                            $jour_debut = strftime("%u", $debut_a);
//                          $jour_fin = strftime("%u", $fin_a);
                            $duree_a = (($fin_a - $debut_a + $modifier)/(60*60*24)+1);

                            // Est-ce qu'on a un week-end dans la p�riode d'absence ?

                            $w = 0;
                            while (($duree_a + $jour_debut - 1) >= (7 + $w)) {
                                if ($_POST['samedi_compte'] == "yes") {
                                    $nb_demi_jour -= 3;
//                                  $temp_test = 3;
                                } else {
                                    $nb_demi_jour -= 4;
//                                  $temp_test = 4;
                                }
//                              echo "<p>WEEK-END : " . $temp_test . " demi-journ�es retir�es du calcul (d�but : $jour_debut ; fin : $jour_fin)</p>";
                                $w += 7;
                            }

                            // On regarde si l'on n'a pas d�j� enregistr� une absence pour la demi-journ�e concern�e

                            $current_eleve = $affiche[1];
                            if ($current_eleve != $previous_eleve) {
                                $tab_date = array();
                            }
                            $previous_eleve = $current_eleve;

                            $current_d_date = $affiche[2] . $tab_seq[$affiche[4]];
                            $current_f_date = $affiche[3] . $tab_seq[$affiche[5]];
//                          echo "<p>" . $affiche[1] . " : $current_d_date :: $current_f_date</p>";
//                          echo "<p>$tab_date[$current_d_date]</p>";
                            if ($tab_date[$current_d_date] == "yes") {
                                $nb_demi_jour--;
                            } else {
                                $tab_date[$current_d_date] = "yes";
                                $tab_date[$current_f_date] = "yes";
                            }



                            $abs[$temp]  += $nb_demi_jour;
                            if ($affiche[7] == 'N') $abs_nj[$temp] += $nb_demi_jour;


                        }
/*                    for($i = 0; $i < count($tabchamps); $i++) {
                            echo "<td bgcolor=\"#FF0000\">".$affiche[$i]."</td>";
                        }
                    } else {
                        for($i = 0; $i < count($tabchamps); $i++) {
                            echo "<td bgcolor=\"#00FF80\">".$affiche[$i]."</td>";
                        }*/
                    }
/*                } else {
                    for($i = 0; $i < count($tabchamps); $i++) {
                        echo "<td>".$affiche[$i]."</td>";
                    }*/
                }

//                echo "</tr>";
            }
            dbase_close($fp);
//            echo "</table>";
            // On affiche le tableau de la classe :

            $tab_session=serialize($tab);
            $retard_session =serialize($retard);
            $abs_session =serialize($abs);
            $abs_nj_session =serialize($abs_nj);
            $_SESSION['tab_session']=$tab_session;
            $_SESSION['retard_session']=$retard_session;
            $_SESSION['abs_session']=$abs_session;
            $_SESSION['abs_nj_session']=$abs_nj_session;

            echo "<p>Tableau r�capitulatif des absences pour la p�riode du <b>".$jourd."/".$moisd."/".$anneed."</b> au <b>".$jourf."/".$moisf."/".$anneef."</b></p>";
            echo "<p><b>Attention </b>: les donn�es ne sont pas encore enregistr�es dans la base GEPI.</p>";
            echo "<form enctype=\"multipart/form-data\" action=\"import_absences_gep.php\" method=\"post\" name=\"form_absences\">";
            echo "<p align=\"center\"><input type=submit value=\"Enregistrer les donn�es dans la base GEPI\" /></p>";
            echo "<input type=hidden name='step' value='5' />";
            echo "<input type=hidden name='id_classe' value='".$id_classe."' />";
            echo "<input type=hidden name='periode_num' value='".$periode_num."' />";
            echo "</form>";
            echo "<table border=\"1\" cellpadding=\"3\"><tr><td><b>Nom pr�nom</b></td><td><b>Nb. de retards</b></td><td><b>Nb. de 1/2 journ�es d'absence</b></td><td><b>1/2 j. non justifi�es</b></td></tr>";
            foreach ($tab as $key => $value) {
                $nom_eleve = sql_query1("select nom from eleves where login = '".$key."'");
                $prenom_eleve = sql_query1("select prenom from eleves where login = '".$key."'");
//              $num_gep = sql_query1("select elenoet from eleves WHERE login ='".$key."'");
                echo "<tr><td>$nom_eleve $prenom_eleve</td><td>$retard[$key]</td><td>$abs[$key]</td><td>$abs_nj[$key]</td></tr>";
            }
            echo "</table>";


        }
    } else if (trim($dbf_file['name'])=='') {
        echo "<p>Aucun fichier n'a �t� s�lectionn� !<br />";
        echo "<a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=3'>Cliquer ici </a> pour recommencer !</center></p>";

    } else {
        echo "<p>Le fichier s�lectionn� n'est pas valide !<br />";
        echo "<a href='import_absences_gep.php?id_classe=$id_classe&amp;periode_num=$periode_num&amp;step=3'>Cliquer ici </a> pour recommencer !</center></p>";
    }
} else if ($step = 5) {
    $tab=unserialize($_SESSION['tab_session']);
    $retard=unserialize($_SESSION['retard_session']);
    $abs=unserialize($_SESSION['abs_session']);
    $abs_nj=unserialize($_SESSION['abs_nj_session']);
    echo "L'importation des absences est termin�e.<br />Acc�der � la <a href='./saisie_absences.php?id_classe=$id_classe&amp;periode_num=$periode_num'>page de saisie des absences</a> pour v�rification.";
    echo "<ul>";
    foreach ($tab as $key => $value) {
        $nom_eleve = sql_query1("select nom from eleves where login = '".$key."'");
        $prenom_eleve = sql_query1("select prenom from eleves where login = '".$key."'");
        $test_eleve_nb_absences_query = mysql_query("SELECT * FROM absences WHERE (login='$key' AND periode='$periode_num')");
        $test_nb = mysql_num_rows($test_eleve_nb_absences_query);
        if ($test_nb != "0") {
            $register = mysql_query("UPDATE absences
            SET nb_absences='".$abs[$key]."',
            non_justifie='".$abs_nj[$key]."',
            nb_retards='".$retard[$key]."'
            WHERE (login='".$key."' AND periode='".$periode_num."')");
        } else {
            $register = mysql_query("INSERT INTO absences SET
            login='".$key."',
            periode='".$periode_num."',
            nb_absences='".$abs[$key]."',
            non_justifie='".$abs_nj[$key]."',
            nb_retards='".$retard[$key]."',
            appreciation=''");
        }
        if (!$register) {
            echo "<li><font color=\"#FF0000\">Erreur lors de l'enregistrement des donn�es de l'�l�ve $prenom_eleve $nom_eleve</font></li>";
        } else {
            echo "<li>Les donn�es concernant l'�l�ve $prenom_eleve $nom_eleve ont �t� correctement enregistr�es.</li>";
        }
    }
    echo "</ul>";

}

?>

</body>
</html>