<?php
/*
* $Id$
*
* Copyright 2001, 2011 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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

$resultat_session = $session_gepi->security_check();
if ($resultat_session == 'c') {
	header("Location: ../utilisateurs/mon_compte.php?change_mdp=yes");
	die();
} else if ($resultat_session == '0') {
	header("Location: ../logout.php?auto=1");
	die();
}

//INSERT INTO droits VALUES ('/groupes/popup.php', 'V', 'V', 'V', 'V', 'F', 'F', 'Visualisation des �quipes p�dagogiques', '');
if (!checkAccess()) {
	header("Location: ../logout.php?auto=1");
	die();
}



$id_groupe=isset($_GET['id_groupe']) ? $_GET['id_groupe'] : NULL;
$id_classe=isset($_GET['id_classe']) ? $_GET['id_classe'] : NULL;
$periode_num=isset($_GET['periode_num']) ? $_GET['periode_num'] : NULL;

$avec_details=isset($_GET['avec_details']) ? $_GET['avec_details'] : "n";

$msg="";

if((isset($id_groupe))&&($id_groupe!='VIE_SCOLAIRE')) {
	$id_groupe=preg_replace('/[^0-9]/','',$id_groupe);
	if($id_groupe=='') {
		unset($id_groupe);
		//$msg.="Identifiant de groupe invalide.<br />\n";
	}
}

if(isset($id_classe)) {
	$id_classe=preg_replace('/[^0-9]/','',$id_classe);
	if($id_classe=='') {
		unset($id_classe);
		//$msg.="Identifiant de classe invalide.<br />\n";
	}
}

if(isset($periode_num)) {
	$periode_num=preg_replace('/[^0-9]/','',$periode_num);
	if($periode_num=='') {
		unset($periode_num);
		//$msg.="Num�ro de p�riode invalide.<br />\n";
	}
}
//echo "<!--\$id_classe=$id_classe-->\n";
//if($id_classe==""){
//	unset($id_classe);
//}

if(isset($id_groupe)) {

	// A FAIRE: TESTER LE CARACTERE NUMERIQUE DE $id_groupe
	if($id_groupe=="VIE_SCOLAIRE"){
		$enseignement="VIE SCOLAIRE";
	}
	else{
		if(strlen(my_ereg_replace("[0-9]","",$id_groupe))!=0){
			header("Location: ../accueil.php?msg=Numero_de_groupe_non_valide");
			die();
		}

		$current_group=get_group($id_groupe);
		$enseignement=$current_group['description'];
	}

	if(isset($id_classe)){

		// A FAIRE: TESTER LE CARACTERE NUMERIQUE DE $id_classe
		if(strlen(my_ereg_replace("[0-9]","",$id_classe))!=0){
			header("Location: ../accueil.php?msg=Numero_de_classe_non_valide");
			die();
		}

		$sql="SELECT classe FROM classes WHERE id='$id_classe'";
		$res_classe=mysql_query($sql);
		if(mysql_num_rows($res_classe)==1){
			$lig_classe=mysql_fetch_object($res_classe);
			$classe=$lig_classe->classe;
		}
		elseif(mysql_num_rows($res_classe)>1){
			$msg.="ERREUR: Plus d'une classe semble correspondre � la classe n�$id_classe";
		}
		else{
			$msg.="ERREUR: Aucune classe ne semble correspondre � la classe n�$id_classe.";
		}
	}
}
else {
	//header("Location: ../logout.php?auto=1");
	header("Location: ../accueil.php?msg=Aucun_groupe_choisi");
	die();
}

$gepi_prof_suivi=getSettingValue('gepi_prof_suivi');
if($gepi_prof_suivi==""){
	$gepi_prof_suivi="professeur principal";
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
	//$enseignement=urldecode($_GET['enseignement']);
	//$enseignement=rawurldecode($_GET['enseignement']);

	if(isset($id_classe)) {
		//echo "<title>El�ves de l'enseignement ".htmlentities($enseignement)." en ".htmlentities($classe)."</title>\n";
		echo "<title>".htmlentities($enseignement)." en ".htmlentities($classe)."</title>\n";
	}
	else {
		//echo "<title>El�ves de l'enseignement ".htmlentities($enseignement)."</title>\n";
		echo "<title>".htmlentities($enseignement)."</title>\n";
	}
?>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15" />
	<meta name="author" content="Stephane Boireau, A.S. RUE de Bernay/Pont-Audemer" />
	<!--link type="text/css" rel="stylesheet" href="../styles.css" /-->
	<link type="text/css" rel="stylesheet" href="../style.css" />
<?php
	if(isset($style_screen_ajout)){
		// Styles param�trables depuis l'interface:
		if($style_screen_ajout=='y'){
			// La variable $style_screen_ajout se param�tre dans le /lib/global.inc
			// C'est une s�curit�... il suffit de passer la variable � 'n' pour d�sactiver ce fichier CSS et �ventuellement r�tablir un acc�s apr�s avoir impos� une couleur noire sur noire
			echo "<link rel='stylesheet' type='text/css' href='$gepiPath/style_screen_ajout.css' />\n";
		}
	}

	$posDiv_infobulle=0;
	// $posDiv_infobulle permet de fixer la position horizontale initiale du Div.

	$tabdiv_infobulle=array();
	$tabid_infobulle=array();

	// Choix de l'unit� pour les dimensions des DIV: em, px,...
	$unite_div_infobulle="em";
	// Pour l'overflow dans les DIV d'aide, il vaut mieux laisser 'em'.

	// Variable pass�e � 'ok' en fin de page via le /lib/footer.inc.php
	echo "<script type='text/javascript'>
		var temporisation_chargement='n';
	</script>\n";

	echo "<script type='text/javascript' src='$gepiPath/lib/brainjar_drag.js'></script>\n";
	echo "<script type='text/javascript' src='$gepiPath/lib/position.js'></script>\n";


?>
</head>
<body>

<?php
	if($msg!=""){
		echo "<p style='color:red; text-align:center;'>".$msg."</p>\n";
	}

	//echo "<h2>El�ves de l'enseignement $enseignement</h2>\n";
	if(isset($id_classe)){
		//echo "<h2>El�ves de l'enseignement ".htmlentities($enseignement)." en ".htmlentities($classe)."</h2>\n";
		echo "<h2>".htmlentities($enseignement)." en ".htmlentities($classe)."</h2>\n";
	}
	else{
		//echo "<h2>El�ves de l'enseignement ".htmlentities($enseignement)."</h2>\n";
		echo "<h2>".htmlentities($enseignement)."</h2>\n";
	}

	echo "<div class='noprint' style='float:right; width: 20px; height: 20px'><a href='";
	echo $_SERVER['PHP_SELF']."?";
	if($avec_details=='y') {echo "avec_details=n";} else {echo "avec_details=y";}
	if(isset($id_groupe)) {echo "&amp;id_groupe=$id_groupe";}
	if(isset($id_classe)) {echo "&amp;id_classe=$id_classe";}
	if(isset($periode_num)) {echo "&amp;periode_num=$periode_num";}
	echo "'>";
	if($avec_details=='y') {echo "<img src='../images/icons/remove.png' width='16' height='16' alt='Sans d�tails' />";} else {echo "<img src='../images/icons/add.png' width='16' height='16' alt='Avec d�tails' />";}
	echo "</a></div>";

	$titre="Photo";
	$texte="";
	$tabdiv_infobulle[]=creer_div_infobulle('div_photo_eleve',$titre,"",$texte,"",10,0,'y','y','n','n');

	//echo "<p>Effectif de l'enseignement: ".$_GET['effectif']."</p>\n";
	//echo "<p>".urldecode($_GET['chaine'])."</p>\n";
	//echo "<p>".rawurldecode($_GET['chaine'])."</p>";

	$tabmail=array();

	if($id_groupe=="VIE_SCOLAIRE"){
        // Liste des CPE:
        //$sql="SELECT DISTINCT u.nom,u.prenom,u.email,jec.cpe_login FROM utilisateurs u,j_eleves_cpe jec,j_eleves_classes jecl WHERE jec.e_login=jecl.login AND jecl.id_classe='$id_classe' AND u.login=jec.cpe_login ORDER BY jec.cpe_login";
        $sql="SELECT DISTINCT u.login, u.nom, u.prenom, u.email, jec.cpe_login FROM utilisateurs u, j_eleves_cpe jec,j_eleves_classes jecl WHERE jec.e_login=jecl.login AND jecl.id_classe='$id_classe' AND u.login=jec.cpe_login ORDER BY jec.cpe_login";
        $result_cpe=mysql_query($sql);
        if(mysql_num_rows($result_cpe)>0){
			echo "<table class='boireaus' border='1'>\n";
			$alt=1;
            while($lig_cpe=mysql_fetch_object($result_cpe)){
				$alt=$alt*(-1);
                echo "<tr valign='top' class='lig$alt white_hover'><th>CPE:</th>\n";
                echo "<td>";
                /*
				if($lig_cpe->email!=""){
                    echo "<a href='mailto:$lig_cpe->email?".urlencode("subject=[GEPI] classe=".$classe['classe'])."'>$lig_cpe->nom ".ucfirst(strtolower($lig_cpe->prenom))."</a>";
                    $tabmail[]=$lig_cpe->email;
                }
                else{
				*/
                    //echo strtoupper($lig_cpe->nom)." ".ucfirst(strtolower($lig_cpe->prenom));
					echo affiche_utilisateur($lig_cpe->login,$id_classe);
                //}
                echo "</td></tr>\n";
            }
			echo "</table>\n";
        }

		if(isset($periode_num)) {
			//$sql="SELECT DISTINCT e.nom,e.prenom,e.email FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='$id_classe' AND jec.periode='$periode_num' ORDER BY e.nom,e.prenom";
			$sql="SELECT DISTINCT e.* FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='$id_classe' AND jec.periode='$periode_num' ORDER BY e.nom,e.prenom";
		}
		else {
			//$sql="SELECT DISTINCT e.nom,e.prenom,e.email FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='$id_classe' ORDER BY e.nom,e.prenom";
			$sql="SELECT DISTINCT e.* FROM eleves e, j_eleves_classes jec WHERE jec.login=e.login AND jec.id_classe='$id_classe' ORDER BY e.nom,e.prenom";
		}
		$res_eleves=mysql_query($sql);
		$nb_eleves=mysql_num_rows($res_eleves);

		echo "<p>Effectif de la classe: $nb_eleves</p>\n";
		if($nb_eleves>0){
			//echo "<p>";
			echo "<table class='boireaus' border='1'>\n";
			$alt=1;
			while($lig_eleve=mysql_fetch_object($res_eleves)){
				$alt=$alt*(-1);
                echo "<tr valign='top' class='lig$alt white_hover'>\n";
				echo "<td>\n";
				if($lig_eleve->email!=""){
					echo "<a href='mailto:$lig_eleve->email?".urlencode("subject=[GEPI]")."'>";
					echo "$lig_eleve->nom $lig_eleve->prenom<br />\n";
					echo "</a>";
					$tabmail[]=$lig_eleve->email;
				}
				else{
					echo "$lig_eleve->nom $lig_eleve->prenom<br />\n";
				}
				echo "</td>\n";

				if($avec_details=='y') {
					if(getSettingValue('active_module_trombinoscopes')=='y') {
						echo "<td>\n";
						$_photo_eleve = nom_photo($lig_eleve->elenoet);
						if($_photo_eleve!='') {
							echo "<a href='#' onclick=\"document.getElementById('div_photo_eleve_contenu_corps').innerHTML='<div align=\'center\'><img src=\'$_photo_eleve\' width=\'150\' /></div>';afficher_div('div_photo_eleve','y',-100,20); return false;\"><img src='../images/icons/buddy.png' alt=\"$lig_eleve->nom $lig_eleve->prenom\"></a>\n";
						}
						else {
							echo "&nbsp;";
						}
						echo "</td>\n";
					}
					echo "<td>\n";
					if(acces('/eleves/visu_eleve.php',$_SESSION['statut'])) {
						echo "<a href='../eleves/visu_eleve.php?ele_login=$lig_eleve->login&amp;cacher_header=y'>".affiche_date_naissance($lig_eleve->naissance)."</a>";
					}
					else {
						echo affiche_date_naissance($lig_eleve->naissance);
					}
					echo "</td>\n";
				}

				echo "</tr>\n";
			}
			//echo "</p>\n";
			echo "</table>\n";
		}
	}
	elseif(isset($id_classe)){
		echo "<table class='boireaus' border='1'>\n";
		$sql="SELECT jgp.login,u.nom,u.prenom,u.email FROM j_groupes_professeurs jgp,utilisateurs u WHERE jgp.id_groupe='$id_groupe' AND u.login=jgp.login";
		//echo "$sql<br />";
		$result_prof=mysql_query($sql);
		echo "<tr valign='top'><th>Professeur";
		if(mysql_num_rows($result_prof)>1){echo "s";}
		echo ":</th>\n";
		echo "<td class='lig-1'>";
		while($lig_prof=mysql_fetch_object($result_prof)){
			/*
			if($lig_prof->email!=""){
				echo "<a href='mailto:$lig_prof->email?".urlencode("subject=[GEPI] classe=".$classe['classe'])."'>$lig_prof->nom ".ucfirst(strtolower($lig_prof->prenom))."</a>";
				$tabmail[]=$lig_prof->email;
			}
			else{
			*/
				//echo strtoupper($lig_prof->nom)." ".ucfirst(strtolower($lig_prof->prenom));
				echo affiche_utilisateur($lig_prof->login,$id_classe);
			//}

			// Le prof est-il PP d'au moins un �l�ve de la classe?
			$sql="SELECT * FROM j_eleves_professeurs WHERE id_classe='$id_classe' AND professeur='$lig_prof->login'";
			//echo " (<i>$sql</i>)\n";
			$res_pp=mysql_query($sql);
			if(mysql_num_rows($res_pp)>0){
					echo " (<i>".$gepi_prof_suivi."</i>)";
			}
			echo "<br />\n";
		}
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";



		//$sql="SELECT DISTINCT e.nom,e.prenom FROM j_eleves_groupes jeg,eleves e WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' ORDER BY e.nom,e.prenom";
		if(isset($periode_num)) {
			//$sql="SELECT DISTINCT e.nom,e.prenom,e.email,c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login AND c.id='$id_classe' AND jeg.periode=jec.periode AND jec.periode='$periode_num' ORDER BY e.nom,e.prenom";
			$sql="SELECT DISTINCT e.*,c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login AND c.id='$id_classe' AND jeg.periode=jec.periode AND jec.periode='$periode_num' ORDER BY e.nom,e.prenom";
		}
		else {
			//$sql="SELECT DISTINCT e.nom,e.prenom,e.email,c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login AND c.id='$id_classe' ORDER BY e.nom,e.prenom";
			$sql="SELECT DISTINCT e.*,c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login AND c.id='$id_classe' ORDER BY e.nom,e.prenom";
		}
		$res_eleves=mysql_query($sql);
		$nb_eleves=mysql_num_rows($res_eleves);

		if(isset($periode_num)) {
			$sql="SELECT DISTINCT e.nom,e.prenom,c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login AND jeg.periode=jec.periode AND jec.periode='$periode_num' ORDER BY e.nom,e.prenom";
		}
		else {
			$sql="SELECT DISTINCT e.nom,e.prenom,c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login ORDER BY e.nom,e.prenom";
		}
		$res_tous_eleves=mysql_query($sql);
		$nb_tous_eleves=mysql_num_rows($res_tous_eleves);

		echo "<p>Effectif de l'enseignement: $nb_eleves/$nb_tous_eleves</p>\n";
		if($nb_eleves>0){
			//echo "<p>";
			echo "<table class='boireaus' border='1'>\n";
			$alt=1;
			while($lig_eleve=mysql_fetch_object($res_eleves)){
				$alt=$alt*(-1);
                echo "<tr valign='top' class='lig$alt white_hover'>\n";
				echo "<td>\n";
				if($lig_eleve->email!=""){
					echo "<a href='mailto:$lig_eleve->email?".urlencode("subject=[GEPI]")."'>";
					echo "$lig_eleve->nom $lig_eleve->prenom<br />\n";
					echo "</a>";
					$tabmail[]=$lig_eleve->email;
				}
				else{
					echo "$lig_eleve->nom $lig_eleve->prenom<br />\n";
				}
				echo "</td>\n";

				if($avec_details=='y') {
					if(getSettingValue('active_module_trombinoscopes')=='y') {
						echo "<td>\n";
						$_photo_eleve = nom_photo($lig_eleve->elenoet);
						if($_photo_eleve!='') {
							echo "<a href='#' onclick=\"document.getElementById('div_photo_eleve_contenu_corps').innerHTML='<div align=\'center\'><img src=\'$_photo_eleve\' width=\'150\' /></div>';afficher_div('div_photo_eleve','y',-100,20); return false;\"><img src='../images/icons/buddy.png' alt=\"$lig_eleve->nom $lig_eleve->prenom\"></a>\n";
						}
						else {
							echo "&nbsp;";
						}
						echo "</td>\n";
					}
					echo "<td>\n";
					if(acces('/eleves/visu_eleve.php',$_SESSION['statut'])) {
						echo "<a href='../eleves/visu_eleve.php?ele_login=$lig_eleve->login&amp;cacher_header=y'>".affiche_date_naissance($lig_eleve->naissance)."</a>";
					}
					else {
						echo affiche_date_naissance($lig_eleve->naissance);
					}
					echo "</td>\n";
				}

				echo "</tr>\n";
			}
			//echo "</p>\n";
			echo "</table>\n";
		}
	}
	else {
		if(isset($periode_num)) {
			//$sql="SELECT DISTINCT e.nom,e.prenom,e.email,c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login AND jeg.periode=jec.periode AND jec.periode='$periode_num'";
			$sql="SELECT DISTINCT e.*, c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login AND jeg.periode=jec.periode AND jec.periode='$periode_num'";
		}
		else {
			//$sql="SELECT DISTINCT e.nom,e.prenom,e.email,c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login";
			$sql="SELECT DISTINCT e.*, c.classe FROM j_eleves_groupes jeg, eleves e, j_eleves_classes jec, j_groupes_classes jgc, classes c WHERE jeg.login=e.login AND jeg.id_groupe='$id_groupe' AND jgc.id_classe=c.id AND jgc.id_groupe=jeg.id_groupe AND jec.id_classe=c.id AND jec.login=e.login";
		}
		if(isset($_GET['orderby'])){
			if($_GET['orderby']=='nom'){
				$orderby=" ORDER BY e.nom,e.prenom";
			}
			else{
				$orderby=" ORDER BY c.classe,e.nom,e.prenom";
			}
		}
		else{
			$orderby=" ORDER BY e.nom,e.prenom";
		}
		$sql.=$orderby;
		$res_eleves=mysql_query($sql);
		$nb_eleves=mysql_num_rows($res_eleves);
		echo "<p>Effectif: $nb_eleves</p>\n";
		if($nb_eleves>0){
			echo "<table class='boireaus' border='1'>\n";
			echo "<tr><th><a href='".$_SERVER['PHP_SELF']."?id_groupe=$id_groupe&amp;orderby=nom'>El�ve</a></th>\n";
			echo "<th><a href='".$_SERVER['PHP_SELF']."?id_groupe=$id_groupe&amp;orderby=classe'>Classe</a></th>\n";
			if($avec_details=='y') {
				// Ajouter un test sur le trombino actif ou non
				if(getSettingValue('active_module_trombinoscopes')=='y') {echo "<th>Photo</th>\n";}
				echo "<th>Naissance</th>\n";
			}
			echo "</tr>\n";
			$alt=1;
			while($lig_eleve=mysql_fetch_object($res_eleves)){
				$alt=$alt*(-1);
				echo "<tr class='lig$alt white_hover'><td>";
				if($lig_eleve->email!=""){
					echo "<a href='mailto:$lig_eleve->email?".urlencode("subject=[GEPI]")."'>";
					echo "$lig_eleve->nom $lig_eleve->prenom<br />\n";
					echo "</a>";
					$tabmail[]=$lig_eleve->email;
				}
				else{
					echo "$lig_eleve->nom $lig_eleve->prenom<br />\n";
				}
				echo "</td>\n";
				echo "<td>$lig_eleve->classe</td>\n";

				if($avec_details=='y') {
					if(getSettingValue('active_module_trombinoscopes')=='y') {
						echo "<td>\n";
						$_photo_eleve = nom_photo($lig_eleve->elenoet);
						if($_photo_eleve!='') {
							echo "<a href='#' onclick=\"document.getElementById('div_photo_eleve_contenu_corps').innerHTML='<div align=\'center\'><img src=\'$_photo_eleve\' width=\'150\' /></div>';afficher_div('div_photo_eleve','y',-100,20); return false;\"><img src='../images/icons/buddy.png' alt=\"$lig_eleve->nom $lig_eleve->prenom\"></a>\n";
						}
						else {
							echo "&nbsp;";
						}
						echo "</td>\n";
					}
					echo "<td>\n";
					if(acces('/eleves/visu_eleve.php',$_SESSION['statut'])) {
						echo "<a href='../eleves/visu_eleve.php?ele_login=$lig_eleve->login&amp;cacher_header=y'>".affiche_date_naissance($lig_eleve->naissance)."</a>";
					}
					else {
						echo affiche_date_naissance($lig_eleve->naissance);
					}
					echo "</td>\n";
				}

				echo "</tr>\n";
			}
			echo "</table>\n";
		}
	}


	if(getSettingValue('envoi_mail_liste')=='y') {
		$chaine_mail="";
		if(count($tabmail)>0){
			unset($tabmail2);
			$tabmail2=array();
			//$tabmail=array_unique($tabmail);
			//sort($tabmail);
			$chaine_mail=$tabmail[0];
			for ($i=1;$i<count($tabmail);$i++) {
				if((isset($tabmail[$i]))&&(!in_array($tabmail[$i],$tabmail2))) {
					$chaine_mail.=",".$tabmail[$i];
					$tabmail2[]=$tabmail[$i];
				}
			}
			//echo "<p>Envoyer un <a href='mailto:$chaine_mail?".rawurlencode("subject=[GEPI]")."'>mail � tous les �l�ves de l'enseignement</a>.</p>\n";
			echo "<p>Envoyer un <a href='mailto:$chaine_mail?".rawurlencode("subject=[GEPI]")."'>mail � tous les �l�ves</a>.</p>\n";
		}
	}
?>
<script language="JavaScript" type="text/javascript">
	window.focus();
</script>
<!--/body>
</html-->
<?php
	require("../lib/footer.inc.php");
?>
