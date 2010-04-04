<?php


/**
 * Skeleton subclass for performing query and update operations on the 'edt_creneaux' table.
 *
 * Table contenant les creneaux de chaque journee (M1, M2...S1, S2...)
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.gepi
 */
class EdtCreneauPeer extends BaseEdtCreneauPeer {

  /**
   * Les types de creneaux possibles
   */
  public static $_type_creneaux = array("cours", "pause", "repas", "vie scolaire");

  /**
   * Renvoie la liste des creneaux de la journee
   *
   * @return PropelObjectCollection EdtCreneau
   */
    public static function getAllEdtCreneauxOrderByTime(){
	    $criteria = new Criteria();
	    $criteria->addAscendingOrderByColumn(EdtCreneauPeer::HEUREDEBUT_DEFINIE_PERIODE);
	    return self::doSelect($criteria);
    }

	/**
	 *
	 * Renvoi le creneau actuel
	 *
	 * @return     EdtCreneau EdtCreneau
	 *
	 */
	public static function getEdtCreneauActuel($v = 'now') {
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		//$dt = new DateTime();
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		return EdtCreneauQuery::create()->filterByHeuredebutDefiniePeriode($dt->format("H:i:s"), Criteria::LESS_EQUAL)
		    ->filterByHeurefinDefiniePeriode($dt->format("H:i:s"), Criteria::GREATER_THAN)->findOne();
	}

	/**
	 *
	 * Renvoi le premier creneau de la semaine
	 *
	 * @return     EdtCreneau EdtCreneau
	 *
	 */
	public static function getFirstEdtCreneau() {
		throw new PropelException("Pas encore implemente");
		return new EdtCreneau();
	}

} // EdtCreneauPeer
