<?php

require 'gepi/om/BaseJGroupesClasses.php';


/**
 * Skeleton subclass for representing a row from the 'j_groupes_classes' table.
 *
 * Table permettant la jointure entre groupe d'eleves et une classe. Cette jointure permet de definir un enseignement, c'est à dire un groupe d'eleves dans une meme classe. Est rarement utilise directement dans le code. Cette jointure permet de definir un coefficient et une valeur ects pour un groupe sur une classe
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    gepi
 */
class JGroupesClasses extends BaseJGroupesClasses {

	/**
	 * Initializes internal state of JGroupesClasses object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

} // JGroupesClasses
