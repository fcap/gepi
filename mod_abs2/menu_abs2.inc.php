<?php
/*
$Id$
 */

echo "<ul class='css-tabs' id='menutabs'>\n";

echo "<li><a href='index.php' ";
if($onglet_abs=='index') {echo "class='current' ";}
echo "title='Accueil du module'>Index</a></li>\n";

echo "<li><a href='saisie_absences.php' ";
if($onglet_abs=='saisie') {echo "class='current' ";}
echo "title='Saisie des absences et des retards'>Saisie</a></li>\n";

// Tests � remplacer par des tests sur les droits attribu�s aux statuts
if(($_SESSION['statut']=='cpe')||
    ($_SESSION['statut']=='scolarite')) {
    echo "<li><a href='suivi_absences.php' ";
    if($onglet_abs=='suivi') {echo "class='current' ";}
    echo "title='Traitement et suivi des absences et des retards'>Suivi</a></li>\n";

    echo "<li><a href='#' ";
    if($onglet_abs=='bilans') {echo "class='current' ";}
    echo "title='Bilans'>Bilans</a></li>\n";

    echo "<li><a href='#' ";
    if($onglet_abs=='stat') {echo "class='current' ";}
    echo "title='Statistiques'>Statistiques</a></li>\n";

    echo "<li><a href='#' ";
    if($onglet_abs=='courrier') {echo "class='current' ";}
    echo "title='Gestion du courrier'>Courrier</a></li>\n";

    echo "<li><a href='#' ";
    if($onglet_abs=='parametrage') {echo "class='current' ";}
    echo "title='Param�tres : types, actions, motifs, justifications, cr�neaux'>Param�tres</a></li>\n";
}
echo "<li><a href='fiche_eleve.php' ";
if($onglet_abs=='fiche_eleve') {echo "class='current' ";}
echo "title='Informations sur les �l�ves'>Fiches �l�ve</a></li>\n";

if (getSettingValue("active_mod_discipline") == "y") {
    echo "<li><a href='../mod_discipline/index.php' ";
    if($onglet_abs=='discipline') {echo "class='current' ";}
    echo "title='Module discipline'>Discipline</a></li>";
}

echo "</ul>\n";

?>