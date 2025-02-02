<?php
/*
 *
 * $Id$
 *
 *
 */


// gestion des fonctions sur les absences, dispences, retard, infirmerie

// fonction qui permet de v�rifier si la variable ne contient que des caract�re
function verif_texte($texte_ver) {
	if(!my_ereg("^[a-zA-Z_]+$",$texte_ver)){ $texte_ver = FALSE; } else { $texte_ver = $texte_ver; }
	return $texte_ver;
 }

// fonction qui permet de v�rifier si la variable ne contient que des chiffres
function verif_num($texte_ver) {
	if(!my_ereg("^[0-9]+$",$texte_ver)){ $texte_ver = FALSE; } else { $texte_ver = $texte_ver; }
	return $texte_ver;
 }


/* ************************************************************* */
/* DEBUT - GESTION DES COURIERS                                  */
/* modif_suivi_du_courrier( num�ro id de l'absence )             */
// permet de supprimer un courrier s'il y a besoin par rapport � l'id de l'absence
function modif_suivi_du_courrier($id_absence_eleve, $eleve_absence_eleve='')
{

	global $prefix_base;

	$requete_a_qui_appartient_id = 'SELECT * FROM '.$prefix_base.'absences_eleves WHERE id_absence_eleve = "' . $id_absence_eleve . '"';
    $execution_a_qui_appartient_id = mysql_query($requete_a_qui_appartient_id) or die('Erreur SQL !'.$requete_a_qui_appartient_id.'<br />'.mysql_error());
	while ( $donnee_a_qui_appartient_id = mysql_fetch_array( $execution_a_qui_appartient_id ) ) {

		$eleve_absence_eleve = $donnee_a_qui_appartient_id['eleve_absence_eleve'];

	}

		// on v�rify s'il y a un courrier si oui on le supprime s'il fait parti d'un ensemble de courrier alors on le modifi.
		// premi�re option il existe une lettre qui fait seulement r�f�rence � cette id donc suppression
		$cpt_lettre_suivi = mysql_result(mysql_query("SELECT count(*) FROM ".$prefix_base."lettres_suivis WHERE quirecois_lettre_suivi = '".$eleve_absence_eleve."' AND partde_lettre_suivi = 'absences_eleves' AND type_lettre_suivi = '6' AND partdenum_lettre_suivi = ',".$id_absence_eleve.",'"),0);
		if( $cpt_lettre_suivi == 1 )
		{

	              $requete = "DELETE
	              			    FROM ".$prefix_base."lettres_suivis
	              			   WHERE partde_lettre_suivi = 'absences_eleves'
	              			  	 AND type_lettre_suivi = '6'
	              			  	 AND partdenum_lettre_suivi = ',".$id_absence_eleve.",'";
	              mysql_query($requete) or die('Erreur SQL !'.$requete.'<br />'.mysql_error());

		}
		else
		{

			// deuxi�me option il existe une lettre qui fait r�f�rence � cette id mais � d'autre aussi donc modification
			$cpt_lettre_suivi = mysql_result(mysql_query("SELECT count(*) FROM ".$prefix_base."lettres_suivis WHERE quirecois_lettre_suivi = '".$eleve_absence_eleve."' AND partde_lettre_suivi = 'absences_eleves' AND type_lettre_suivi = '6' AND partdenum_lettre_suivi LIKE '%,".$id_absence_eleve.",%'"),0);
			if( $cpt_lettre_suivi == 1 )
			{

		    	$requete = mysql_query("SELECT *
		    						      FROM ".$prefix_base."lettres_suivis
		    						     WHERE partde_lettre_suivi = 'absences_eleves'
		    						       AND type_lettre_suivi = '6'
		    						       AND partdenum_lettre_suivi LIKE '%,".$id_absence_eleve.",%'"
		    						  );

		    	$donnee = mysql_fetch_array($requete);
		    	$remplace_sa = ','.$id_absence_eleve.',';
		    	$modifier_par = my_ereg_replace($remplace_sa,',',$donnee['partdenum_lettre_suivi']);
		    	$requete = "UPDATE ".$prefix_base."lettres_suivis
		    				SET partdenum_lettre_suivi = '".$modifier_par."',
		    					envoye_date_lettre_suivi = '',
		    					envoye_heure_lettre_suivi = '',
		    					quienvoi_lettre_suivi = ''
		    				WHERE partde_lettre_suivi = 'absences_eleves'
		    				  AND type_lettre_suivi = '6'
		    				  AND partdenum_lettre_suivi LIKE '%,".$id_absence_eleve.",%'";
	            mysql_query($requete) or die('Erreur SQL !'.$requete.'<br />'.mysql_error());

			}

		}
}
/* ******************************************** */

// fonction permettant de supprimer un ou plusieurs id dans une table donn�e
// � partir d'un tableau qui contiendrais les ids
// $tableau_des_ids: tableau avec les num�ro id
// $prefix_base: pr�fix de la base s'il y en a
// $table: nom de la table choisie
// $selection: avoir un variable s�lection
function supprime_id($tableau_des_ids, $prefix_base, $table, $selection)
 {
	$id_init = '0';
	while(!empty($tableau_des_ids[$id_init]))
	 {

		// on attribue les variables
		$id_selectionne = $tableau_des_ids[$id_init];
		if ( isset($selection[$id_init]) and $selection[$id_init] != '' )
		{

			$cocher = 'oui';

		}
		else
		{

			$cocher = 'non';

		}

		// si les variables sont correct et non vide on continue
		if(verif_texte($table) and verif_num($id_selectionne) and $id_selectionne != '' and $table != '' and $cocher === 'oui')
		{


			// on v�rifie s'il y a du courrier
			if ( $table === 'absences_eleves' )
			{

				modif_suivi_du_courrier($id_selectionne);

			}

			// suppression dans la table absence_rb
       		suppr_absences_rb($id_selectionne);

          	$requete = "DELETE
           			    FROM ".$prefix_base.$table."
           			    WHERE id_absence_eleve ='".$id_selectionne."'";
            mysql_query($requete) or die('Erreur SQL !'.$requete.'<br />'.mysql_error());

        }

	 $id_init = $id_init + 1;

	 }

 }

// fonction g�rant l'insertion d'une absences ou plusieurs absence
// par rapport � un tableau d'information qui contient les informations ci-dessous
// du, au, de, a, motif, justification, justification plus d'info
function ajout_abs($tableau_des_donnees)
 {
	$id_init = '0';
	while(!empty($tableau_des_donnees[$id_init]['id']))
	{

	 	$id_init = $id_init + 1;

	}
 }


/* *************************************************************** */
/* Fonction g�rant l'insertion d'absence dans la table absences_rb */
function gerer_absence($id='',$eleve_id,$retard_absence,$groupe_id='',$edt_id='',$jour_semaine='',$creneau_id='',$debut_ts,$fin_ts,$date_saisie,$login_saisie='',$action)
{

	global $prefix_base;

	/*
	$eleve_id -> login de l'�l�ve
	$retard_absence -> R ou A
	$groupe_id -> vide
	$edt_id -> vide
	$jour_semaine -> vide
	$creneau_id -> vide
	$debut_ts -> debut en timestamp / mktime(heure, minute, 0, mois, jour, annee);
	$fin_ts -> fin en timestamp / mktime(heure, minute, 0, mois, jour, annee);
	$date_saisie -> date de saisi en timestamp / mktime(heure, minute, 0, mois, jour, annee);
	$login_saisie -> login de la personne pour la saisi
	$action -> ajouter
	*/

	if ( $action === 'ajouter' )
	{

		// on v�rifie qu'une absence ne se trouve pas entre le d�but et la fin de celle saisie
		$cpt_ligne = mysql_result(mysql_query("SELECT count(*)
										   		 FROM " . $prefix_base . "absences_rb
										   		WHERE eleve_id = '" . $eleve_id . "'
										   		  AND retard_absence = '" . $retard_absence . "'
										   		  AND debut_ts >=  '" . $debut_ts . "'
										   		  AND fin_ts <= '" . $fin_ts . "'"
										 	  ),0);

		// s'il n'y aucun enregistrement qui correspond alors on l'ajoute
		if ( $cpt_ligne == 0 )
		{

			$saisie_sql = "INSERT INTO absences_rb
						   		(eleve_id, retard_absence, groupe_id, edt_id, jour_semaine, creneau_id, debut_ts, fin_ts, date_saisie, login_saisie)
						   VALUES
						   		('" . $eleve_id . "', '" . $retard_absence . "', '" . $groupe_id . "', '0', '" . $jour_semaine . "', '" . $creneau_id . "', '" . $debut_ts . "', '" . $fin_ts . "', '" . $date_saisie . "', '" . $_SESSION["login"] . "')";
			$insere_abs = mysql_query($saisie_sql) OR DIE ('Erreur SQL !'.$saisie_sql.'<br />'.mysql_error());//('Impossible d\'enregistrer l\'absence de '.$eleve_absent[$a]);

		}
		else
		{

			// nous allons lister toutes les enregistrement
			$requete = ("SELECT *
				 		   FROM " . $prefix_base . "absences_rb
					 	  WHERE eleve_id = '" . $eleve_id . "'
					   		AND retard_absence = '" . $retard_absence . "'
					   		AND debut_ts >=  '" . $debut_ts . "'
					   		AND fin_ts <= '" . $fin_ts . "'"
				   	   );

			$execution = mysql_query($requete) or die('Erreur SQL !'.$requete.'<br />'.mysql_error());
        	while ($donnee = mysql_fetch_array($execution))
        	{

				// si le debut est la fin sont compris entre les deux valeur mais �gale � aucun d�but
				// on les supprimes
				if ( $debut_ts > $donnee['debut_ts'] and $fin_ts < $donnee['fin_ts'] )
				{

					gerer_absence($donnee['id'],$eleve_id,$retard_absence,'','','','',$donnee['debut_ts'],$donnee['fin_ts'],$donnee['date_saisie'],'','supprimer');

				}

				// si le debut est �gale � la valeur de d�but et que la fin est inf�rieur � la fin
				// ???????????????????????
				// en attente de plus d'information

			}

		}

	}

	if ( $action === 'supprimer' )
	{

		if ( !verif_num($id) )
		{

			$req_delete = "DELETE
						     FROM " . $prefix_base . "absences_rb
						    WHERE id = '" . $id . "'
						      AND retard_absence = '" . $retard_absence . "'
						      AND debut_ts >=  '" . $debut_ts . "'
					   		  AND fin_ts <= '" . $fin_ts . "'
					  	  ";

        	$req_sql = mysql_query($req_delete);

		}

	}

}
/*                                                                 */
/* *************************************************************** */


/* *************************************************************** */
/* Fonction g�rant la suppression des absences dans la table absences_rb */
function suppr_absences_rb($id)
{

	global $prefix_base;

	/*
	$id -> id de la table absences_eleves
	$type -> R ou A
	*/

	if ( $id != '' )
    {

		// on v�rifie qu'une absence ne se trouve pas entre le d�but et la fin de celle saisie
		$cpt_ligne = mysql_result(mysql_query("SELECT count(*)
											   FROM " . $prefix_base . "absences_eleves
											   WHERE id_absence_eleve = '" . $id . "'"
											 ),0);

		// s'il y un enregistrement
		if ( $cpt_ligne != 0 )
		{

			// on ne connait pas l'id dans la table absences_rb donc il vas falloir utilise d'autre information avant la supprimession
			$requete = "SELECT *
						FROM " . $prefix_base . "absences_eleves
						WHERE id_absence_eleve = '" . $id . "' ";

	        $resultat = mysql_query($requete) or die('Erreur SQL !'.$requete.'<br />'.mysql_error());
    	    while ( $donnee = mysql_fetch_array($resultat) )
        	{

	        	$type_absence = $donnee['type_absence_eleve'];
    	        $eleve_absent = $donnee['eleve_absence_eleve'];
        	    $d_date_absence_eleve = $donnee['d_date_absence_eleve'];
            	$a_date_absence_eleve = $donnee['a_date_absence_eleve'];
            	$d_heure_absence_eleve = $donnee['d_heure_absence_eleve'];
            	$a_heure_absence_eleve = $donnee['a_heure_absence_eleve'];

        	}

			if ( $type_absence === 'R' )
			{

				$a_heure_absence_eleve = $d_heure_absence_eleve;

			}

        	$explode_heuredeb = explode(":", $d_heure_absence_eleve);
			$explode_heurefin = explode(":", $a_heure_absence_eleve);
			$explode_date_debut = explode('/', date_fr($d_date_absence_eleve));
			$explode_date_fin = explode('/', date_fr($a_date_absence_eleve));
			$debut_ts = mktime($explode_heuredeb[0], $explode_heuredeb[1], 0, $explode_date_debut[1], $explode_date_debut[0], $explode_date_debut[2]);
			$fin_ts = mktime($explode_heurefin[0], $explode_heurefin[1], 0, $explode_date_fin[1], $explode_date_fin[0], $explode_date_fin[2]);

			if ( $debut_ts != '' and $fin_ts != '' )
			{

				$req_delete = "DELETE
						   	   FROM " . $prefix_base . "absences_rb
						   	   WHERE retard_absence = '" . $type_absence . "'
				    		 	 AND debut_ts >=  '" . $debut_ts . "'
			   		 			 AND fin_ts <= '" . $fin_ts . "'
			   		 			 AND eleve_id = '" . $eleve_absent . "'
			  			  	  ";

       			$req_sql = mysql_query($req_delete);

			}

		}

	}

}
/*                                                                 */
/* *************************************************************** */


/* *************************************************************** */
/* Fonction g�rant la modification des absences dans la table absences_rb */
function modifier_absences_rb($id,$debut_ts_modif,$fin_ts_modif)
{

	global $prefix_base;

	/*
	$id -> id de la table absences_eleves
	$type -> R ou A
	*/

	if ( $id != '' )
    {

		// on v�rifie qu'une absence ne se trouve pas entre le d�but et la fin de celle saisie
		$cpt_ligne = mysql_result(mysql_query("SELECT count(*)
											   FROM " . $prefix_base . "absences_eleves
											   WHERE id_absence_eleve = '" . $id . "'"
											 ),0);

		// s'il y un enregistrement
		if ( $cpt_ligne != 0 )
		{

			// on ne connait pas l'id dans la table absences_rb donc il vas falloir utilise d'autre information avant la supprimession
			$requete = "SELECT *
						FROM " . $prefix_base . "absences_eleves
						WHERE id_absence_eleve = '" . $id . "' ";

	        $resultat = mysql_query($requete) or die('Erreur SQL !'.$requete.'<br />'.mysql_error());
    	    while ( $donnee = mysql_fetch_array($resultat) )
        	{

	        	$type_absence = $donnee['type_absence_eleve'];
    	        $eleve_absent = $donnee['eleve_absence_eleve'];
        	    $d_date_absence_eleve = $donnee['d_date_absence_eleve'];
            	$a_date_absence_eleve = $donnee['a_date_absence_eleve'];
            	$d_heure_absence_eleve = $donnee['d_heure_absence_eleve'];
            	$a_heure_absence_eleve = $donnee['a_heure_absence_eleve'];

        	}

			if ( $type_absence === 'R' )
			{

				$a_heure_absence_eleve = $d_heure_absence_eleve;

			}

        	$explode_heuredeb = explode(":", $d_heure_absence_eleve);
			$explode_heurefin = explode(":", $a_heure_absence_eleve);
			$explode_date_debut = explode('/', date_fr($d_date_absence_eleve));
			$explode_date_fin = explode('/', date_fr($a_date_absence_eleve));
			$debut_ts = mktime($explode_heuredeb[0], $explode_heuredeb[1], 0, $explode_date_debut[1], $explode_date_debut[0], $explode_date_debut[2]);
			$fin_ts = mktime($explode_heurefin[0], $explode_heurefin[1], 0, $explode_date_fin[1], $explode_date_fin[0], $explode_date_fin[2]);

			if ( $debut_ts != '' and $fin_ts != '' )
			{

				// on cherche l'id de la table absence_rb
				$requete = "SELECT *
							FROM " . $prefix_base . "absences_rb
							WHERE retard_absence = '" . $type_absence . "'
				    		  AND debut_ts >=  '" . $debut_ts . "'
			   		 		  AND fin_ts <= '" . $fin_ts . "'
			   		 		  AND eleve_id = '" . $eleve_absent . "'
			   		 	   ";

	        $resultat = mysql_query($requete) or die('Erreur SQL !'.$requete.'<br />'.mysql_error());
    	    while ( $donnee = mysql_fetch_array($resultat) )
        	{

        		$id_absences_rb = $donnee['id'];

        						$req_modifier = "UPDATE " . $prefix_base . "absences_rb
								 SET debut_ts = '" . $debut_ts_modif . "',
								     fin_ts = '" . $fin_ts_modif . "'
						   	   	 WHERE id = '" . $id_absences_rb . "'
			  			  	  ";

       			$req_sql = mysql_query($req_modifier);

			}




			}

		}

	}

}
/*                                                                 */
/* *************************************************************** */
?>
