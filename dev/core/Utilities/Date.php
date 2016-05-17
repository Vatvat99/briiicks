<?php
namespace Core\Utilities;

/**
 * Classe regroupant des fonctions utilitaires concernant le traitement des dates
 */
class Date {

    /**
     * @var array Liste des jours en français
     */
    private static $days_fr = array(
        'lundi',
        'mardi',
        'mercredi',
        'jeudi',
        'vendredi',
        'samedi',
        'dimanche'
    );

    /**
     * @var array Liste des mois en français
     */
    private static $months_fr = array(
        'janvier',
        'février',
        'mars',
        'avril',
        'mai',
        'juin',
        'juillet',
        'août',
        'septembre',
        'octobre',
        'novembre',
        'décembre'
    );

    /**
     * Retourne une liste de jours dans une langue donnée
     * @param string $locale Langue demandée
     * @return array
     */
    static function getDays($locale)
    {
        return self::${'days_' . $locale};
    }

    /**
     * Retourne une liste de mois dans une langue donnée
     * @param string $locale Langue demandée
     * @return array
     */
    static function getMonths($locale)
    {
        return self::${'months_' . $locale};
    }

}