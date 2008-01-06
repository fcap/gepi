<?php

/**
 * Ensemble des fonctions qui permettent de cr�er un nouveau cours en v�rifiant les pr�c�dents
 *
 * @version $Id$
 * @copyright 2007
 */

// Fonction qui renvoie l'id du cr�neau suivant de celui qui est appel�
function creneauSuivant($creneau){
	$cherche_creneaux = array();
	$cherche_creneaux = retourne_id_creneaux();
	$ch_index = array_search($creneau, $cherche_creneaux);
	if (isset($cherche_creneaux[$ch_index+1])) {
		$reponse = $cherche_creneaux[$ch_index+1];
	}else{
		$reponse = "aucun";
	}
	return $reponse;
} // creneauSuivant()

// Fonction qui renvoie l'id du cr�neau pr�c�dent de celui qui est appel�
function creneauPrecedent($creneau){
	$cherche_creneaux = array();
	$cherche_creneaux = retourne_id_creneaux();
	$ch_index = array_search($creneau, $cherche_creneaux);
	if (isset($cherche_creneaux[$ch_index-1])) {
		$reponse = $cherche_creneaux[$ch_index-1];
	}else{
		$reponse = "aucun";
	}
	return $reponse;
} // creneauPrecedent()

// Fonction qui renvoie le nombre de cr�neaux pr�c�dents celui qui est appel�
function nombreCreneauxPrecedent($creneau){
	// On r�cup�re l'heure du creneau appel�
	$heure_creneau_appele = mysql_fetch_array(mysql_query("SELECT heuredebut_definie_periode FROM absences_creneaux WHERE id_definie_periode = '".$creneau."'"));
	$requete = mysql_query("SELECT id_definie_periode FROM absences_creneaux WHERE heuredebut_definie_periode < '".$heure_creneau_appele["heuredebut_definie_periode"]."' AND type_creneaux != 'pause' ORDER BY heuredebut_definie_periode");
	$nbre = mysql_num_rows($requete);

	return $nbre;
} // nombreCreneauxPrecedent()

// Fonction qui renvoie le nombre de cr�neaux qui suivent celui qui est appel�
function nombreCreneauxApres($creneau){
	// On r�cup�re l'heure du creneau appel�
	$heure_creneau_appele = mysql_fetch_array(mysql_query("SELECT heuredebut_definie_periode FROM absences_creneaux WHERE id_definie_periode = '".$creneau."'"));
	$requete = mysql_query("SELECT id_definie_periode FROM absences_creneaux WHERE heuredebut_definie_periode > '".$heure_creneau_appele["heuredebut_definie_periode"]."' AND type_creneaux != 'pause' ORDER BY heuredebut_definie_periode");
	$nbre = mysql_num_rows($requete);

	return $nbre;
} // nombreCreneauxApres()

// Fonction qui renvoie l'inverse de heuredeb_dec
function inverseHeuredeb_dec($heuredeb_dec){
	if ($heuredeb_dec == "0.5") {
		$retour = "0";
	}elseif($heuredeb_dec == "0"){
		$retour = "0.5";
	}else{
		$retour = NULL;
	}

	return $retour;
} // inverseHeuredeb_dec()

// Fonction qui v�rifie que le professeur n'a pas d�j� cours � ce moment l� et sur la dur�e
function verifProf($nom, $jour, $creneau, $duree, $heuredeb_dec, $type_semaine){

	$proflibre = "oui";
	// Le nouveau cours doit �tre cr�er ce $jour, sur ce $creneau sur une $duree donn�e.
	$requete = mysql_query("SELECT * FROM edt_cours, j_groupes_professeurs WHERE
			edt_cours.jour_semaine='".$jour."' AND
			edt_cours.id_definie_periode='".$creneau."' AND
			edt_cours.id_groupe=j_groupes_professeurs.id_groupe AND
			login='".$nom."' AND
			(edt_cours.id_semaine = '".$type_semaine."' OR edt_cours.id_semaine = '0') AND
			edt_cours.heuredeb_dec = '".$heuredeb_dec."'");
	$verif = mysql_num_rows($requete);
		if ($verif >= 1) {
			$proflibre = "non";
		}elseif($verif == 0) {
			// Dans le cas o� la dur�e est de 1/2 cr�neau, on retourne "oui"
			if ($duree == 1) {
				return "oui";
			}
		}
	// On v�rifie alors pour tous les cr�neaux pr�c�dents sans oublier les 1/2 cr�neaux
		$creneau_t = $creneau;
		$nbre_test = (nombreCreneauxPrecedent($creneau)) + 1;
		for($a = 1; $a < $nbre_test; $a++){
			$creneau_t = creneauPrecedent($creneau_t);
			$requete = mysql_query("SELECT duree FROM edt_cours, j_groupes_professeurs WHERE
				edt_cours.jour_semaine = '".$jour."' AND
				edt_cours.id_definie_periode = '".$creneau_t."' AND
				edt_cours.id_groupe = j_groupes_professeurs.id_groupe AND
				login = '".$nom."' AND
				(edt_cours.id_semaine = '".$type_semaine."' OR edt_cours.id_semaine = '0') AND
				edt_cours.heuredeb_dec = '".$heuredeb_dec."'")
					OR DIE('Erreur dans la requete n� '.$a.' du type FOR : '.mysql_error());
			$verif = mysql_fetch_array($requete);
			// On v�rifie que la dur�e n'exc�de pas le cours appel�
				if ($verif["duree"] > (2 * $a)) {
					$proflibre = "non";
				}
			//echo "FOR($a) : |".$verif["duree"]."|".$heuredeb_dec."|".$creneau."<br />";
		}
	// On d�cale l'heure de d�but et on refait la m�me op�ration
	$inv_heuredeb_dec = inverseHeuredeb_dec($heuredeb_dec);
	$creneau_u = $creneau;
		for($a = 1; $a < $nbre_test; $a++){
			$creneau_u = creneauPrecedent($creneau_u);
			$requete = mysql_query("SELECT duree FROM edt_cours, j_groupes_professeurs WHERE
				edt_cours.jour_semaine = '".$jour."' AND
				edt_cours.id_definie_periode = '".$creneau_u."' AND
				edt_cours.id_groupe = j_groupes_professeurs.id_groupe AND
				login = '".$nom."' AND
				(edt_cours.id_semaine = '".$type_semaine."' OR edt_cours.id_semaine = '0') AND
				edt_cours.heuredeb_dec = '".$inv_heuredeb_dec."'") OR DIE('Erreur dans la requete n� '.$a.' du type FOR2 : '.mysql_error());
			$verif = mysql_fetch_array($requete);
			// On v�rifie que la dur�e n'exc�de pas le cours appel�
				if ($verif["duree"] > ((2 * $a) +1)) {
					$proflibre = "non";
				}
			//echo "FOR2($a) : ".$verif["duree"]."|".$inv_heuredeb_dec."|".$creneau."<br />";
		}
	$creneau_v = $creneau;
// On v�rifie aussi si ce nouveau cours n'empi�te pas sur un cours suivant pour le m�me professeur
	if ($duree >= 2 AND $heuredeb_dec == "0") {
		// On v�rifie s'il n'y a pas d�j� un cours qui commence en 0.5
		$requete = mysql_query("SELECT duree FROM edt_cours, j_groupes_professeurs WHERE
			edt_cours.jour_semaine = '".$jour."' AND
			edt_cours.id_definie_periode = '".$creneau_v."' AND
			edt_cours.id_groupe = j_groupes_professeurs.id_groupe AND
			login = '".$nom."' AND
			(edt_cours.id_semaine = '".$type_semaine."' OR edt_cours.id_semaine = '0') AND
			edt_cours.heuredeb_dec = '0.5'")
				OR DIE('Erreur dans la requete : '.mysql_error());
		$verif1 = mysql_num_rows($requete);
		if ($verif1 >= 1) {
			$proflibre = "non";
		}
	}
	// Si la dur�e est sup�rieure � 1 cr�neau (donc sup�rieure � 2)
	if ($duree >= 2){
		// Il convient alors de v�rifier le tout en fonction de cette dur�e
			$nbre_test = nombreCreneauxApres($creneau_v) + 1;
		for($c = 1; $c < $nbre_test; $c++){
			if ($duree >= ($c * 2)) {
				$creneau_v = creneauSuivant($creneau_v);
				$requete = mysql_query("SELECT duree FROM edt_cours, j_groupes_professeurs WHERE
					edt_cours.jour_semaine = '".$jour."' AND
					edt_cours.id_definie_periode = '".$creneau_v."' AND
					edt_cours.id_groupe = j_groupes_professeurs.id_groupe AND
					login = '".$nom."' AND
					(edt_cours.id_semaine = '".$type_semaine."' OR edt_cours.id_semaine = '0') AND
					edt_cours.heuredeb_dec = '".$heuredeb_dec."'")
					OR DIE('Erreur dans la requete FOR3a : '.mysql_error());
				$verif = mysql_num_rows($requete);

				if ($verif >= 1) {
					$proflibre = "non";
				}
			}
			//echo "FOR3a($c) : ".$verif."|".$heuredeb_dec."|".$creneau."<br />";
			if ($duree >= ($c * 2 + 1)) {
				$heuredeb_dec_i = inverseHeuredeb_dec($heuredeb_dec);
				$requete = mysql_query("SELECT duree FROM edt_cours, j_groupes_professeurs WHERE
					edt_cours.jour_semaine = '".$jour."' AND
					edt_cours.id_definie_periode = '".$creneau_v."' AND
					edt_cours.id_groupe = j_groupes_professeurs.id_groupe AND
					login = '".$nom."' AND
					(edt_cours.id_semaine = '".$type_semaine."' OR edt_cours.id_semaine = '0') AND
					edt_cours.heuredeb_dec = '".$heuredeb_dec_i."'")
					OR DIE('Erreur dans la requete FOR3b : '.mysql_error());
				$verif = mysql_num_rows($requete);

				if ($verif >= 1) {
					$proflibre = "non";
				}
			}
			//echo "FOR3b($c) : ".$verif."|".$heuredeb_dec."|".$creneau."<br />";
		}
	}


	return $proflibre;

} // verifProf()

// Fonction qui v�rifie si la salle est libre pour ce nouveau cours
function verifSalle($salle, $jour, $creneau, $duree, $heuredeb_dec, $type_semaine){

		$sallelibre = "oui";
	// On commence par v�rifier le creneau demand� TEST1
	$requete = mysql_query("SELECT id_cours FROM edt_cours WHERE
			id_salle = '".$salle."'
			AND jour_semaine = '".$jour."'
			AND id_definie_periode = '".$creneau."'
			AND (id_semaine = '".$type_semaine."' OR id_semaine = '0')
			AND heuredeb_dec = '".$heuredeb_dec."'")
				OR DIE('Erreur dans la v�rification TEST1'.mysql_error());
	$verif = mysql_num_rows($requete);
		if ($verif >= 1) {
			$sallelibre = "non";
		}
	//echo "TEST1 : ".$verif."|".$sallelibre."<br />";

	// On v�rifie alors les cr�neaux pr�c�dents TEST2
	$nbre_tests = nombreCreneauxPrecedent($creneau) + 1;
	$test_creneau = $creneau;
	$heuredeb_dec_i = inverseHeuredeb_dec($heuredeb_dec);

		// d'abord sur le m�me $heuredeb_dec
	for($a = 1; $a < $nbre_tests; $a++) {
		$test_creneau = creneauPrecedent($test_creneau);
			$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
			id_salle = '".$salle."'
			AND jour_semaine = '".$jour."'
			AND id_definie_periode = '".$test_creneau."'
			AND (id_semaine = '".$type_semaine."' OR id_semaine = '0')
			AND heuredeb_dec = '".$heuredeb_dec."'")
				OR DIE('Erreur dans la v�rification TEST2a'.mysql_error());
		$verif = mysql_fetch_array($requete);
		// Si la duree du cours pr�c�dent exc�de le cours qu'on veut cr�er, c'est pas possible (sauf si la semaine en question l'exige)
		if ($verif["duree"] > (2 * $a)) {
			$sallelibre = "non";
		}
		//echo "TEST2a(".$a.") : ".$verif."|".$sallelibre."<br />";

		// Puis on v�rifie en inverseHeuredeb_dec($heuredeb_dec)
			$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
			id_salle = '".$salle."'
			AND jour_semaine = '".$jour."'
			AND id_definie_periode = '".$test_creneau."'
			AND (id_semaine = '".$type_semaine."' OR id_semaine = '0')
			AND heuredeb_dec = '".$heuredeb_dec_i."'")
				OR DIE('Erreur dans la v�rification TEST2b'.mysql_error());
		$verif = mysql_fetch_array($requete);
		// Si la duree du cours pr�c�dent exc�de le cours qu'on veut cr�er, c'est pas possible (sauf si la semaine en question l'exige)
		if ($verif["duree"] > ((2 * $a) + 1)) {
			$sallelibre = "non";
		}
		//echo "TEST2b(".$a.") : ".$verif."|".$sallelibre."<br />";

	} // fin du for($a

	// En fonction du cours appel�, on v�rifie les cr�neaux suivant si la dur�e exc�de 1 cr�neau
	// TEST3
	$nbre_tests = nombreCreneauxApres($creneau) + 1;
	$creneau_s = $creneau;
	$creneau_a = creneauSuivant($creneau);
		// On v�rifie d'abord le demi-creneau suivant
		if ($duree > 1) {
			if ($heuredeb_dec == 0) {
				$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
				id_salle = '".$salle."'
				AND jour_semaine = '".$jour."'
				AND id_definie_periode = '".$creneau."'
				AND (id_semaine = '".$type_semaine."' OR id_semaine = '0')
				AND heuredeb_dec = '0.5'")
					OR DIE('Erreur dans la v�rification TEST3a :'.mysql_error());
			}elseif ($heuredeb_dec == "0.5"){
				$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
				id_salle = '".$salle."'
				AND jour_semaine = '".$jour."'
				AND id_definie_periode = '".$creneau_a."'
				AND (id_semaine = '".$type_semaine."' OR id_semaine = '0')
				AND heuredeb_dec = '0'")
					OR DIE('Erreur dans la v�rification TEST3b :'.mysql_error());
			}
			$verif = mysql_num_rows($requete);
			if ($verif >= 1) {
				$sallelibre = "non";
			}
		}

	for($b = 1; $b < $nbre_tests; $b++) {
			$creneau_s = creneauSuivant($creneau_s);
		if ($duree > ($b * 2)) {
			$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
				id_salle = '".$salle."'
				AND jour_semaine = '".$jour."'
				AND id_definie_periode = '".$creneau_s."'
				AND (id_semaine = '".$type_semaine."' OR id_semaine = '0')
				AND heuredeb_dec = '".$heuredeb_dec."'")
					OR DIE('Erreur dans la v�rification TEST3c :'.mysql_error());
			$verif = mysql_num_rows($requete);
		}elseif($duree > (($b * 2) + 1)) {
			$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
				id_salle = '".$salle."'
				AND jour_semaine = '".$jour."'
				AND id_definie_periode = '".$creneau_s."'
				AND (id_semaine = '".$type_semaine."' OR id_semaine = '0')
				AND heuredeb_dec = '".$heuredeb_dec_i."'")
					OR DIE('Erreur dans la v�rification TEST3d :'.mysql_error());
			$verif = mysql_num_rows($requete);
		}
		if ($verif >= 1) {
			$sallelibre = "non";
		}
	} // fin du for($b...

	return $sallelibre;
} // verifSalle()

// Fonction qui v�rifie si un groupe est libre pour le cours appel�
function verifGroupe($groupe, $jour, $creneau, $duree, $heuredeb_dec, $type_semaine){

		$groupelibre = "oui";
		$heuredeb_dec_i = inverseHeuredeb_dec($heuredeb_dec);
	// On v�rifie le cr�neau demand� TEST
	$requete = mysql_query("SELECT id_cours FROM edt_cours WHERE
		id_groupe = '".$groupe."' AND
		jour_semaine = '".$jour."' AND
		id_definie_periode = '".$creneau."' AND
		(id_semaine = '".$type_semaine."' OR id_semaine = '0') AND
		heuredeb_dec = '".$heuredeb_dec."'")
			OR DIE('Erreur dans la verification TESTa :'.mysql_error());
	$verif = mysql_num_rows($requete);
	if ($verif >= 1) {
		$groupelibre = "non";
	}
	// Dans le cas o� le cours commence au milieu du cr�neau, il faut v�rifier le demi-creneau pr�c�dent
	if ($heuredeb_dec == "O.5") {
		$requete = mysql_query("SELECT id_cours FROM edt_cours WHERE
			id_groupe = '".$groupe."' AND
			jour_semaine = '".$jour."' AND
			id_definie_periode = '".$creneau."' AND
			(id_semaine = '".$type_semaine."' OR id_semaine = '0') AND
			heuredeb_dec = '0'")
				OR DIE('Erreur dans la verification TESTb :'.mysql_error());
		$verif = mysql_fetch_array($requete);
		if ($verif["duree"] > 1) {
			$groupelibre = "non";
		}
	}
	// On v�rifie les cr�neaux avant TEST1
	$nbre_tests = nombreCreneauxPrecedent($creneau) + 1;
	$creneau_test = $creneau;
	for($a = 1; $a < $nbre_tests; $a++){
		$creneau_test = creneauPrecedent($creneau_test);
		// Premier test sur le cr�neau pr�c�dent avec le m�me d�but (m�me $heuredeb_dec)
		$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
			id_groupe = '".$groupe."' AND
			jour_semaine = '".$jour."' AND
			id_definie_periode = '".$creneau_test."' AND
			(id_semaine = '".$type_semaine."' OR id_semaine = '0') AND
			heuredeb_dec = '".$heuredeb_dec."'")
				OR DIE('Erreur dans la verification TEST1a :'.mysql_error());
		$verif = mysql_fetch_array($requete);
		if ($verif["duree"] > (2 * $a)) {
			$groupelibre = "non";
		}
		// Deuxi�me test sur le cr�neau pr�c�dent avec le d�but invers� (inverseHeuredeb_dec($heuredeb_dec))
		$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
			id_groupe = '".$groupe."' AND
			jour_semaine = '".$jour."' AND
			id_definie_periode = '".$creneau_test."' AND
			(id_semaine = '".$type_semaine."' OR id_semaine = '0') AND
			heuredeb_dec = '".$heuredeb_dec_i."'")
				OR DIE('Erreur dans la verification TEST1b :'.mysql_error());
		$verif = mysql_fetch_array($requete);
		if ($verif["duree"] > ((2 * $a) + 1)) {
			$groupelibre = "non";
		}
		// Quand un cours commence sur le d�but du cr�neau, on v�rifie le demi-cours pr�c�dent
		// dont la dur�e ne doit pas exc�der 1 (seulement dans le premier tour de la boucle)
		if($heuredeb_dec == "0" AND $a == 1){
			if ($verif["duree"] > 1) {
				$groupelibre = "non";
			}
		}
	} // fin du for($a...

	// Si la dur�e du cours demand� d�passe 1, on v�rifie alors les cr�neaux suivants TEST2
	$nbre_tests = nombreCreneauxApres($creneau) + 1;
	$creneau_test = $creneau;

	// SI le cours commence au milieu du creneau, on v�rifie le demi-creneau suivant
	if ($heuredeb_dec == "0.5" AND $duree > 1) {
		$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
			id_groupe = '".$groupe."' AND
			jour_semaine = '".$jour."' AND
			id_definie_periode = '".creneauSuivant($creneau)."' AND
			(id_semaine = '".$type_semaine."' OR id_semaine = '0') AND
			heuredeb_dec = '0'")
				OR DIE('Erreur dans la verification TEST2 :'.mysql_error());
		$verif = mysql_num_rows($requete);
		if ($verif >= 1) {
			$groupelibre = "non";
		}
	}

	for($b = 1; $b < $nbre_tests; $b++) {
		$creneau_test = creneauSuivant($creneau_test);

		if ($duree >= 2 * $b) {
			// Premier test sur le cr�neau suivant avec le m�me d�but (m�me $heuredeb_dec)
			$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
				id_groupe = '".$groupe."' AND
				jour_semaine = '".$jour."' AND
				id_definie_periode = '".$creneau_test."' AND
				(id_semaine = '".$type_semaine."' OR id_semaine = '0') AND
				heuredeb_dec = '".$heuredeb_dec."'")
					OR DIE('Erreur dans la verification TEST2a :'.mysql_error());
			$verif = mysql_num_rows($requete);
			if ($verif >= 1) {
				$groupelibre = "non";
			}
			// Deuxi�me test sur le cr�neau suivant avec le d�but invers� (inverseHeuredeb_dec($heuredeb_dec))
			$requete = mysql_query("SELECT id_cours, duree FROM edt_cours WHERE
				id_groupe = '".$groupe."' AND
				jour_semaine = '".$jour."' AND
				id_definie_periode = '".$creneau_test."' AND
				(id_semaine = '".$type_semaine."' OR id_semaine = '0') AND
				heuredeb_dec = '".$heuredeb_dec_i."'")
					OR DIE('Erreur dans la verification TEST2b :'.mysql_error());
			$verif_b = mysql_num_rows($requete);
			if ($verif_b >= 1) {
				$groupelibre = "non";
			}
		}
	//echo "TEST2b($b)".$creneau_test."|".$verif."|".$verif_b."|".$duree."|".$heuredeb_dec."|".$heuredeb_dec_i."<br />";
	} // fin du fror($b...

	return $groupelibre;
} // verifGroupe()
?>