<?php
/*
 * $Id$
*/
?>
 
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<!-- <meta http-equiv="refresh" content="[tbs_refresh.tempsmax]; URL=[tbs_refresh.lien]/logout.php?auto=3&amp;debut_session=[tbs_refresh.debut]&amp;session_id=[tbs_refresh.id_session]" /> -->

	<!-- d�claration par d�faut pour les scripts et les mises en page -->
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<meta http-equiv="Content-Style-Type" content="text/css" />

	<title><?php echo "$titre_page : $tbs_gepiSchoolName" ?></title>
	
	
<!-- ================= Affichage du favicon =================== -->
	<link rel="SHORTCUT ICON" href="<?php echo $tbs_gepiPath?>/favicon.ico" />


<!-- D�but des fichiers en javascript -->

<?php
	if ($tbs_message_enregistrement!="") {
		echo "
			<script type='text/javascript'>
				//<![CDATA[ 
					alert($tbs_message_enregistrement);
				//]]>
			</script>
		";
	}
?>

	<script type="text/javascript">
		//<![CDATA[ 
		function changement() {
			change = 'yes';
		}
		//]]>
	</script>

	<!-- Gestion de l'expiration des session - Patrick Duthilleul -->
	<script type="text/javascript">
		//<![CDATA[
			var debut=new Date()

			/* =================================================
			 =
			 =
			 =
			 =================================================== */
			function display_alert(heure) {
				if ($('alert_message')) {
					$('alert_message').update("A "+ heure + ", il vous reste moins de 3 minutes avant d'�tre d�connect� ! \nPour �viter cela, rechargez cette page en ayant pris soin d'enregistrer votre travail !");

					if (Prototype.Browser.IE) {
						document.documentElement.scroll = "no";
						document.documentElement.style.overflow = 'hidden';
					}
					else {
						document.body.scroll = "no";
						document.body.style.overflow = 'hidden';				
					}					
					var viewport = document.viewport.getDimensions(); // Gets the viewport as an object literal
					var width = viewport.width; // Usable window width
					var height = viewport.height; // Usable window height
					if( typeof( window.pageYOffset ) == 'number' ) 
						{y = window.pageYOffset;}
					else if (typeof(document.documentElement.scrollTop) == 'number') {
						y=document.documentElement.scrollTop;
					}
					$('alert_cache').setStyle({width: "100%"});
					$('alert_cache').setStyle({height: height+"px"});
					$('alert_cache').setStyle({top: y+"px"});
					$('alert_cache').setStyle({display: 'block'});
					$('alert_cache').setOpacity(0.5);
					$('alert_entete').setStyle({top: y-46+Math.abs((height-200)/2)+"px"});
					$('alert_entete').setStyle({left: Math.abs((width-300)/2)+"px"});
					$('alert_entete').setOpacity(1);
					$('alert_entete').setStyle({display: 'block'});
					$('alert_popup').setStyle({top: y+Math.abs((height-200)/2)+"px"});
					$('alert_popup').setStyle({left: Math.abs((width-300)/2)+"px"});
					$('alert_popup').setOpacity(1);
					$('alert_popup').setStyle({display: 'block'});
					$('alert_bouton_ok').observe('click', function(event) {
						$('alert_popup').setStyle({display: 'none'});	
						$('alert_cache').setStyle({display: 'none'});
						$('alert_entete').setStyle({display: 'none'});
						if (Prototype.Browser.IE) {
							document.documentElement.scroll = "yes";
							document.documentElement.style.overflow = 'scroll';
						}
						else {
							document.body.scroll = "yes";
							document.body.style.overflow = 'scroll';				
						}						
					
					});
					$('alert_bouton_reload').observe('click', function(event) {
						location.reload(true); 				
					
					});	
				}
				else {
					alert("A "+ heure + ", il vous reste moins de 3 minutes avant d'�tre d�connect� ! \nPour �viter cela, rechargez cette page en ayant pris soin d'enregistrer votre travail !");
				}
			
			}
			/* =================================================
			 =
			 =
			 =
			 =================================================== */			
			function show_message_deconnexion() {
				var seconds_before_alert = 180;
				var seconds_int_betweenn_2_msg = 30;

				var digital=new Date()
				var seconds=(digital-debut)/1000
				//if (1==1) {
				if (seconds>1800 - seconds_before_alert) {
					var seconds_reste = Math.floor(1800 - seconds);
					now=new Date()
					var hrs=now.getHours();
					var mins=now.getMinutes();
					var secs=now.getSeconds();

					var heure = hrs + " H " + mins + "' " + secs + "'' ";
					//alert("A "+ heure + ", il vous reste moins de 3 minutes avant d'�tre d�connect� ! \nPour �viter cela, rechargez cette page en ayant pris soin d'enregistrer votre travail !");
					display_alert(heure);
				}

				setTimeout("show_message_deconnexion()",seconds_int_betweenn_2_msg*1000)
			}
		//]]>
	</script>

	<!-- christian -->
	<script type="text/javascript">
		//<![CDATA[ 
		function ouvre_popup(url) {
				eval("window.open('/mod_miseajour/utilisateur/fenetre.php','fen','width=600,height=500,menubar=no,scrollbars=yes')");
				fen.focus();
			}
		//]]>
	</script>


	<script type="text/javascript" src="<?php echo $tbs_gepiPath ?>/lib/functions.js"></script>
	<?php
		if (count($tbs_librairies)) {
			foreach ($tbs_librairies as $value) {
				if ($value!="") {
					echo "<script type=\"text/javascript\" src=\"$value\"></script>\n";
				}
			}
			unset($value);
		}
	?>

	<!-- Variable pass�e � 'ok' en fin de page via le /lib/footer.inc.php -->
	<script type='text/javascript'>
		//<![CDATA[ 
			temporisation_chargement='n';
		//]]>
	</script>	
<!-- fin des fichiers en javascript -->

<!-- D�but des styles -->
	<?php
		if (count($tbs_CSS)) {
			foreach ($tbs_CSS as $value) {
				if ($value!="") {
					echo "<link rel=\"$value[rel]\" type=\"$value[type]\" href=\"$value[fichier]\" media=\"$value[media]\" />\n";
		// [tbs_CSS.title;att=title]
				}
			}
			unset($value);
		}
	?>
	
<!-- Fin des styles -->



