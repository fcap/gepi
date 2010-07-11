<?php



/**
 * Skeleton subclass for representing a row from the 'a_notifications' table.
 *
 * Notification (a la famille) des absences
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.gepi
 */
class AbsenceEleveNotification extends BaseAbsenceEleveNotification {

    public static $STATUT_INITIAL = 0;
    public static $STATUT_EN_COURS = 1;
    public static $STATUT_ECHEC = 2;
    public static $STATUT_SUCCES = 3;
    public static $STATUT_SUCCES_AR = 4;

    public static $LISTE_LABEL_STATUT = array(0 => "initial", 1 => "en cours", 2 => "�chec", 3 => "succ�s", 4 => "succes avec A/R");

    public static $TYPE_COURRIER = 0;
    public static $TYPE_EMAIL = 1;
    public static $TYPE_SMS = 2;
    public static $TYPE_TELEPHONIQUE = 3;

    public static $LISTE_LABEL_STATUT = array(0 => "initial", 1 => "en cours", 2 => "�chec", 3 => "succ�s", 4 => "succes avec A/R");
} // AbsenceEleveNotification
