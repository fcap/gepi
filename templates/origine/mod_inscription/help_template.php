<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
/*
 * $Id$
 *
 * Copyright 2001, 2008 Thomas Belliard, Laurent Delineau, Edouard Hue, Eric Lebrun
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

?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
<!-- on inclut l'ent�te -->
	<?php
	  $tbs_bouton_taille = "..";
	  include('../templates/origine/header_template.php');
	?>

  <script type="text/javascript" src="../templates/origine/lib/fonction_change_ordre_menu.js"></script>

	<link rel="stylesheet" type="text/css" href="../templates/origine/css/bandeau.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../templates/origine/css/gestion.css" media="screen" />

<!-- corrections internet Exploreur -->
	<!--[if lte IE 7]>
		<link title='bandeau' rel='stylesheet' type='text/css' href='../templates/origine/css/accueil_ie.css' media='screen' />
		<link title='bandeau' rel='stylesheet' type='text/css' href='../templates/origine/css/bandeau_ie.css' media='screen' />
	<![endif]-->
	<!--[if lte IE 6]>
		<link title='bandeau' rel='stylesheet' type='text/css' href='../templates/origine/css/accueil_ie6.css' media='screen' />
	<![endif]-->
	<!--[if IE 7]>
		<link title='bandeau' rel='stylesheet' type='text/css' href='../templates/origine/css/accueil_ie7.css' media='screen' />
	<![endif]-->


<!-- Style_screen_ajout.css -->
	<?php
		if (count($Style_CSS)) {
			foreach ($Style_CSS as $value) {
				if ($value!="") {
					echo "<link rel=\"$value[rel]\" type=\"$value[type]\" href=\"$value[fichier]\" media=\"$value[media]\" />\n";
				}
			}
		}
	?>

<!-- Fin des styles -->



</head>


<!-- ************************* -->
<!-- D�but du corps de la page -->
<!-- ************************* -->
<body onload="show_message_deconnexion();<?php echo $tbs_charger_observeur;?>">

<!-- fil d'ariane -->
<div id="cache_ariane" class="bold">
<?php
  if (isset($messageEnregistrer) && $messageEnregistrer !="" ){
	affiche_ariane(TRUE,$messageEnregistrer);
  }else{
	if(isset($_SESSION['ariane']) && (count($_SESSION['ariane']['lien'])>1)){
	  affiche_ariane();
	}
  }
?>
  </div>
  <script type='text/javascript'>
	//<![CDATA[
	document.getElementById('cache_ariane').addClassName("invisible");
	//]]>
  </script>
<!-- fin fil d'ariane -->

  <div id='container'>
	
  <h1>Le module Inscription</h1>
  <p>
	Le module Inscription permet de d�finir un ou plusieurs items (journ�e, stage, intervention, ...), au(x)quel(s) les utilisateurs pourront s'inscrire ou se d�sinscrire en cochant ou d�cochant une croix.
  </p>
  <ul>
	<li>La configuration du module est accessible aux administrateurs et � la scolarit�.</li>
	<li>L'interface d'inscription/d�sinscription est accessible aux professeurs, cpe, administrateurs et scolarit�.</li>
  </ul>

  <p>
	Apr�s avoir activ� le module, les administrateurs et la scolarit� disposent dans la page d'accueil
   d'un nouveau module de configuration.
  </p>
  <p>La premi�re �tape consiste � configurer ce module :</p>
  <ul>
	<li>
	  <span class="bold">
		Activation / D�sactivation :
	  </span>
	  <br />
	  Tant que le module n'est pas enti�rement configur�, vous avez int�r�t � ne pas activer la page autorisant
	  les inscriptions. De cette fa�on, ce module reste invisible aux autres utilisateurs (professeurs et cpe).
	  <br />
	  De m�me, lorsque les inscriptions sont closes, vous pouvez d�sactiver les inscriptions, tout en gardant
	  l'acc�s au module de configuration.
	</li>
	<li>
	  <span class="bold">
		Liste des items :
	  </span>
	  <br />
	  C'est la liste des entit�s auxquelles les utilisateurs pourront s'incrire.
	  <br />
	  Chaque entit� est carat�ris�e par un identifiant num�rique, une date (format AAAA/MM/JJ),
	  une heure (20 caract�res max), une description (200 caract�res max).
	</li>
	<li>
	  <span class="bold">
		Titre du module :
	  </span>
	  <br />
	  Vous avez ici la possibilit� de personnaliser l'intitul� du module visible dans la page d'accueil.
	</li>
	<li>
	  <span class="bold">
		Texte explicatif :
	  </span>
	  <br />
	  Ce texte sera visible par les personnes acc�dant au module d'inscription/d�sincription.
	</li>
  </ul>


<!-- D�but du pied -->
	<div id='EmSize' style='visibility:hidden; position:absolute; left:1em; top:1em;'></div>

	<script type='text/javascript'>
	  //<![CDATA[
		var ele=document.getElementById('EmSize');
		var em2px=ele.offsetLeft
	  //]]>
	</script>


	<script type='text/javascript'>
	  //<![CDATA[
		temporisation_chargement='ok';
	  //]]>
	</script>

</div>

		<?php
			if ($tbs_microtime!="") {
				echo "
   <p class='microtime'>Page g�n�r�e en ";
   			echo $tbs_microtime;
				echo " sec</p>
   			";
	}
?>

		<?php
			if ($tbs_pmv!="") {
				echo "
	<script type='text/javascript'>
		//<![CDATA[
   			";
				echo $tbs_pmv;
				echo "
		//]]>
	</script>
   			";

	}
?>

</body>
</html>

