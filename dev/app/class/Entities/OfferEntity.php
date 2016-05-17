<?php
namespace App\Entities;
use Core\Entities\Entities;
use Core\Utilities\Date;

/**
 * Classe associée à une annonce
 */
class OfferEntity extends Entities {

    /**
     *	Génère la date de publication de l'annonce
     */
    public function getDate() {

        $timestamp = strtotime($this->datetime);

        $today_date = date('Ymd');
        $offer_date = date('Ymd', $timestamp);
        if($today_date == $offer_date) {
            return 'aujourd\'hui';
        }

        $months_list = Date::getMonths('fr');
        $day = (string) date('j', $timestamp);
        $month = $months_list[(int) date('n', $timestamp) - 1];
        $year = (string) date('Y', $timestamp);
        return $day . ' ' . $month . ' ' . $year;
    }

    /**
     * Génère le nombre de jour restant avant la suppression de l'annonce
     */
    public function getRemaining_days() {

        $today_timestamp = time();
        $publication_timestamp = strtotime($this->datetime);
        $removal_timestamp = $publication_timestamp + (2*31*24*60*60);

        $remaining_days = round(($removal_timestamp - $today_timestamp) / (60*60*24));

        return $remaining_days;

    }

    /**
     *	Génère l'heure de publication de l'annonce
     */
    public function getTime() {

        $timestamp = strtotime($this->datetime);
        return date("H\hi", $timestamp);

    }

    /**
     * Génère le nom complet de l'auteur d'une annonce
     */
    public function getAuthor() {
        return $this->member_firstname . ' ' . $this->member_lastname;
    }

    /**
     * Génère le lien vers le détail d'une annonce
     */
    public function getLink() {
        return '/offers/view?id=' . $this->id;
    }

    /**
     * Génère le lien vers l'édition d'une annonce
     */
    public function getEdit_link() {
        return '/offers/edit?id=' . $this->id;
    }

    /**
     * Génère le lien vers la suppression d'une annonce
     */
    public function getDelete_link() {
        return '/offers/delete?id=' . $this->id;
    }

}