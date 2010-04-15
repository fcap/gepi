/*
 * $Id$
 */

Event.observe(window, 'load', function() {

  if($('menutabs')) Event.observe('menutabs', 'click', teste);
  TableKit.load();
  if ($('select_donnees')) {
    test_periode();
    if($('recherche_indiv')) auto_complete();
  }
  if($('select_evolution')){
    teste_evolutions();
  }
});

//Mise en place des observateurs pour le formulaire de selection
function test_periode(){
  $('au').observe('change', set_priority_calendar);
  $('du').observe('change', set_priority_calendar);
  var buttons = $('select_donnees').getInputs('radio', 'id_calendrier');
  buttons.each(function(x){
    x.observe('click', set_priority_periode);
  });
  $('month').observe('change', set_priority_month);
  $('etab_all0').observe('click', post_form);
  $('eleve_all').observe('click', post_form);
  $('pers_all').observe('click', post_form);  
  if($($('choix'))) $('choix').observe('click', post_form);
  if($($('choix2')))$('choix2').observe('click', post_form);
  $('indiv').observe('click', function(){
    $('du').disable();
    $('au').disable();
    $('month').disable();    
  });
  $('classe').observe('click', function(){
    $('du').disable();
    $('au').disable();
    if($('choix')) $('choix').disable();
    $('month').disable();    
  }); 
  $('select_donnees').observe('submit', post_form);

}
function teste_evolutions(){
  var buttons = $('select_evolution').getInputs('radio', 'evolution');
  buttons.each(function(x){
    x.observe('click', function(){
      $('select_evolution').submit();
    });
  });
}

function set_priority_calendar(){
  var buttons = $('select_donnees').getInputs('radio', 'id_calendrier');
  buttons.invoke('disable');
  $('month').disable();
  $('select_donnees').submit();
}
function set_priority_periode(){
  $('du').disable();
  $('au').disable();
  $('month').disable();
  $('select_donnees').submit();
}
function set_priority_month(){
  $('du').disable();
  $('au').disable();
  var buttons = $('select_donnees').getInputs('radio', 'id_calendrier');
  buttons.invoke('disable');
  $('select_donnees').submit();
}
function post_form(){
  var buttons = $('select_donnees').getInputs('radio', 'id_calendrier');
  buttons.invoke('disable');
  $('month').disable();
  $('select_donnees').submit();
}//Fin de l'observation pour  le formulaire de selection

//auto_completion ajax
function auto_complete(){
  new Ajax.Autocompleter ('nom','auto_complete','recherche_indiv.php',{
        method: 'post',
        paramName: 'nom',
        minChars: 2,
        indicator:'indicateur',
        afterUpdateElement: auto_completed });
}

function auto_completed(input, li){
  //on r�cup�re le login et on l'impose au champ cach�
  $('login').value =  li.id;
  // on desactive le choix des dates pour le post
  $('month').disable();
  $('select_donnees').submit();                
}//fin auto completion

// script de stephane pour la selection rapide
function modif_case(pref,statut,max){
  for(k=0;k<max;k++){
    if(document.getElementById(pref+'_'+k)){
      document.getElementById(pref+'_'+k).checked=statut;
    }
  }
}
/*
Scriptaculous Sortable pour categories
	*/

function initSortable() {
  Sortable.create('Categories_incidents1',{
    tag:'div',
    only:'bloc',    
    handle:'handle'
  });
  Sortable.create('Categories_incidents2',{
    tag:'div',
    only:'bloc',    
    handle:'handle'
  })
}
//script pour les onglets de Julien
function inittab() {
  //cacher toutes les div
  $$('div.panel').invoke('hide');
  //afficher la premi�re
  var les_a = $$('#menutabs a');
  les_a[0].addClassName('current');
  afficherDiv('tab0');
  
}
function teste(event){
  var cliquera = Event.findElement(event,"a") ? Event.findElement(event,"a") : null;
  var testurl = cliquera.href.split("#");
  var les_a = $$('#menutabs a');
  for (i=0; i<les_a.length; i++){
    if (les_a[i].className == 'current'){
      les_a[i].removeClassName('current');
    }
  }
  cliquera.className = 'current';
  // M�thode "pousse-toi de l�" pas beau mais efficace et tu sais ce que tu caches
  for(i=0 ; i<les_a.length; i++){
    cacherDiv('tab'+i);
  }
  // Il te reste � afficher le bon div
  afficherDiv(testurl[1]);
  Event.stop(event);
}
function afficherDiv(id){
  Element.show(id);
}
function cacherDiv(id){
  Element.hide(id);
}