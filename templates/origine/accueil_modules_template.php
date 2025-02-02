<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
/*
* $Id$
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
*
* ******************************************** *
* Appelle les sous-mod�les                     *
* templates/origine/header_template.php        *
* templates/origine/bandeau_template.php       *
* ******************************************** *
*/
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
<!-- on inclut l'ent�te -->
	<?php include('templates/origine/header_template.php');?>

	<link rel="stylesheet" type="text/css" href="./templates/origine/css/accueil.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./templates/origine/css/bandeau.css" media="screen" />


<!-- corrections internet Exploreur -->
	<!--[if lte IE 7]>
		<link title='bandeau' rel='stylesheet' type='text/css' href='./templates/origine/css/accueil_ie.css' media='screen' />
		<link title='bandeau' rel='stylesheet' type='text/css' href='./templates/origine/css/bandeau_ie.css' media='screen' />
	<![endif]-->
	<!--[if lte IE 6]>
		<link title='bandeau' rel='stylesheet' type='text/css' href='./templates/origine/css/accueil_ie6.css' media='screen' />
	<![endif]-->
	<!--[if IE 7]>
		<link title='bandeau' rel='stylesheet' type='text/css' href='./templates/origine/css/accueil_ie7.css' media='screen' />
	<![endif]-->


<!-- Style_screen_ajout.css -->
	<?php
		if (count($Style_CSS)) {
			foreach ($Style_CSS as $value) {
				if ($value!="") {
					echo "<link rel=\"$value[rel]\" type=\"$value[type]\" href=\"$value[fichier]\" media=\"$value[media]\" />\n";
				}
			}
			unset($value);
		}
	?>

<!-- Fin des styles -->


</head>


<!-- ************************* -->
<!-- D�but du corps de la page -->
<!-- ************************* -->
<body onload="show_message_deconnexion();<?php echo $tbs_charger_observeur;?>">

<!-- on inclut le bandeau -->
	<?php include('templates/origine/bandeau_template.php');?>

<!-- fin bandeau_template.html      -->

	<div id='container'>

<!-- d�but corps menu	-->
	
<?php 
	if (count($menuTitre)) {
		foreach ($menuTitre as $newEntreeMenu) {
?>
		<h2 class="<?php echo $newEntreeMenu->classe ?>">
			<img src="<?php echo $newEntreeMenu->icone['chemin'] ?>" alt="<?php echo $newEntreeMenu->icone['alt'] ?>" /> - <?php echo $newEntreeMenu->texte ?>
		</h2>	
<?php 
			if (count($menuPage)) {
				foreach ($menuPage as $newentree) {
				if ($newentree->indexMenu==$newEntreeMenu->indexMenu) {
?>
					<div class='div_tableau'>
						<p class="colonne3 colonne3gauche">
							<img src="<?php echo $newentree->icone['chemin'] ?>" width='19' height='19' title="<?php echo $newentree->icone['titre'] ?>" alt="<?php echo $newentree->icone['alt'] ?>" />
						</p>
						<h3 class="colonne3 colonne3centre">
							<a href="<?php echo substr($newentree->chemin,1) ?>">
								<?php echo $newentree->titre ?>
							</a>
						</h3>
						<p class="colonne3 colonne3droite">
							<?php echo $newentree->expli ?>
						</p>
					</div>
<?php 
					}
				}
				unset($newentree);
			}
		}
		unset($newEntreeMenu);
	}
?>

<!-- Fin menu	g�n�ral -->


<!-- D�but du pied -->
	<div id='EmSize' style='visibility:hidden; position:absolute; left:1em; top:1em;'></div>

	<script type='text/javascript'>
		var ele=document.getElementById('EmSize');
		var em2px=ele.offsetLeft
		//alert('1em == '+em2px+'px');
	</script>


	<script type='text/javascript'>
		temporisation_chargement='ok';
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

