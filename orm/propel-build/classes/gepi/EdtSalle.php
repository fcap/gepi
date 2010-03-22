<?php

require 'gepi/om/BaseEdtSalle.php';


/**
 * Skeleton subclass for representing a row from the 'salle_cours' table.
 *
 * Liste des salles de classe
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    gepi
 */
class EdtSalle extends BaseEdtSalle {

	/**
	 * Initializes internal state of EdtSalle object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

} // EdtSalle
