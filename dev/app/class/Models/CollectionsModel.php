<?php
namespace App\Models;
use Core\Models\Models;
use \stdClass;

/**
 * Classe associée aux collections
 */
class CollectionsModel extends Models
{

    /**
     * @var string $table Nom de la table
     */
    protected $table;

    /**
     * @var string $entity_class Nom de la classe associée aux entrées de la table
     */
    protected $entity_class = 'App\Entities\CollectionEntity';

    /**
     * Récupère un élément appartenant à un membre
     * @param int $item_id Id de l'élément à récupérer
     * @param int $item_type Type de l'élément à récupérer
     * @param int $user_id Id du membre auquel appartient l'élément
     */
    public function getItemFromUser($item_id, $item_type, $member_id)
    {
        $item_field = 'is_' . $item_type;
        return $this->query(
            'SELECT * FROM briiicks_collections WHERE item_id = ? AND ' . $item_field . ' = 1 AND member_id = ?',
            array(
                $item_id,
                $member_id
            ),
            true
        );
    }

    /**
     * Récupère toutes les figurines d'une collection
     * @param int $member_id Id du membre auquel appartient la collection
     */
    public function getAllMinifigures($member_id)
    {

        return $this->query(
            'SELECT mf.id,
             mf.name,
             mf.alias,
             mf.picture,
             mf.release_year,
             sr.id AS serie_id,
             sr.name AS serie_name,
             rg.id AS range_id,
             rg.name AS range_name,
             rg.color AS range_color,
             cl.count,
             COUNT(case when of.type LIKE \'%Vente%\' then 1 else null end) AS sell_total,
             COUNT(case when of.type LIKE \'%Echange%\' then 1 else null end) AS exchange_total,
             MIN(of.price) AS price
             FROM briiicks_minifigures mf
             INNER JOIN briiicks_collections cl ON cl.item_id = mf.id
             INNER JOIN briiicks_series_minifigures sr_mf ON sr_mf.minifigure_id = cl.item_id
             INNER JOIN briiicks_series sr ON sr.id = sr_mf.serie_id
             INNER JOIN briiicks_ranges_series rg_sr ON rg_sr.serie_id = sr.id
             INNER JOIN briiicks_ranges rg ON rg.id = rg_sr.range_id
             LEFT JOIN briiicks_offers_items of_it ON of_it.item_id = mf.id
             LEFT JOIN briiicks_offers of ON of.id = of_it.offer_id
             WHERE cl.is_minifigure = 1
             AND cl.member_id = ?
             GROUP BY mf.id
             ORDER BY rg.priority, rg.name, sr.priority, sr.name, mf.id',
            array($member_id)
        );

    }

    /**
     * Récupère tous les sets d'une collection
     * @param int $member_id Id du membre auquel appartient la collection
     */
    public function getAllSets($member_id)
    {
        return $this->query(
            'SELECT st.id,
             st.number,
             st.name,
             st.alias,
             st.picture,
             st.release_year,
             st.price,
             sr.id AS serie_id,
             sr.name AS serie_name,
             rg.id AS range_id,
             rg.name AS range_name,
             rg.color AS range_color,
             cl.count,
             COUNT(case when of.type LIKE \'%Vente%\' then 1 else null end) AS sell_total,
             COUNT(case when of.type LIKE \'%Echange%\' then 1 else null end) AS exchange_total,
             MIN(of.price) AS price
             FROM briiicks_sets st
             INNER JOIN briiicks_collections cl ON cl.item_id = st.id
             INNER JOIN briiicks_series_sets sr_st ON sr_st.set_id = cl.item_id
             INNER JOIN briiicks_series sr ON sr.id = sr_st.serie_id
             INNER JOIN briiicks_ranges_series rg_sr ON rg_sr.serie_id = sr.id
             INNER JOIN briiicks_ranges rg ON rg.id = rg_sr.range_id
             LEFT JOIN briiicks_offers_items of_it ON of_it.item_id = st.id
             LEFT JOIN briiicks_offers of ON of.id = of_it.offer_id
             WHERE cl.is_set = 1
             AND cl.member_id = ?
             GROUP BY st.id
             ORDER BY rg.priority, rg.name, sr.priority, sr.name, st.id',
            array($member_id)
        );
    }

    /**
     * Retourne la collection d'un membre
     * @param int $member_id Id du membre auquel appartient la collection
     */
    public function getCollection($member_id)
    {
        $ranges = array();
        $previous_range = '';
        $previous_serie = '';
        $minifigures_count = 0;
        $sets_count = 0;
        // On récupère les figurines
        $minifigures_result = $this->getAllMinifigures($member_id);

        // Et on les trie
        foreach($minifigures_result as $minifigure_result)
        {
            // Si il y a une nouvelle gamme, on la récupère
            if($minifigure_result->range_id != $previous_range)
            {
                // Si une gamme existe (les infos d'une gamme ont déjà été récupérées)
                if(isset($range))
                {
                    // On la rajoute à la liste des gammes
                    $ranges[$range->id] = $range;
                }
                // On récupère les infos de la nouvelle gamme
                $range = new stdClass();
                $range->id = $minifigure_result->range_id;
                $range->name = $minifigure_result->range_name;
                $range->color = $minifigure_result->range_color;
                // On initialise le nombre de figurines présentes dans cette gamme
                $range->minifigures_count = 0;
                // On crée le tableau qui va contenir la liste des séries
                $range->series = array();
                // Et on enregistre l'id
                $previous_range = $minifigure_result->range_id;
            }

            // Si cette gamme contient une nouvelle série, on la récupère
            if($minifigure_result->serie_id != $previous_serie)
            {
                // On récupère les infos de la série
                $serie = new StdClass();
                $serie->id = $minifigure_result->serie_id;
                $serie->name = $minifigure_result->serie_name;
                // On initialise le nombre de figurines présentes dans cette série
                $serie->minifigures_count = 0;
                // On crée le tableau qui va contenir la liste des figurines
                $serie->minifigures = array();
                // On l'ajoute à la liste
                $range->series[$serie->id] = $serie;
                // Et on enregistre l'id
                $previous_serie = $minifigure_result->serie_id;
            }
            // On récupère les infos de la figurine
            $minifigure = new StdClass();
            $minifigure->id = $minifigure_result->id;
            $minifigure->name = $minifigure_result->name;
            $minifigure->alias = $minifigure_result->alias;
            $minifigure->picture = $minifigure_result->picture;
            $minifigure->release_year = $minifigure_result->release_year;
            $minifigure->count = $minifigure_result->count;
            $minifigure->sell_total = $minifigure_result->sell_total;
            $minifigure->exchange_total = $minifigure_result->exchange_total;
            $minifigure->price = $minifigure_result->price;
            // On l'ajoute à la liste
            $serie->minifigures[$minifigure->id] = $minifigure;
            // Et on met à jour les nombres de figurines
            $range->minifigures_count += $minifigure->count;
            $serie->minifigures_count += $minifigure->count;
            $minifigures_count += $minifigure->count;
        }
        // Lorsqu'on a fini de parcourir les figurines, si une gamme existe
        if(isset($range))
        {
            // On n'oublie pas de la rajouter à la liste des gammes
            $ranges[$range->id] = $range;
        }

        // On réinitialise les variables
        $previous_range = '';
        $previous_serie = '';

        // On récupère les sets
        $sets_result = $this->getAllSets($member_id);
        // Et on les trie
        foreach($sets_result as $set_result)
        {
            // Si il y a une nouvelle gamme,
            if($set_result->range_id != $previous_range)
            {
                // Si une gamme existe (les infos d'une gamme ont déjà été récupérées)
                if(isset($range))
                {
                    // On la rajoute à la liste des gammes
                    $ranges[$range->id] = $range;
                }
                // Si on n'a pas récupéré la nouvelle gamme pour les figurines
                if(!array_key_exists($set_result->range_id, $ranges))
                {
                    // On récupère les infos de la nouvelle gamme
                    $range = new stdClass();
                    $range->id = $set_result->range_id;
                    $range->name = $set_result->range_name;
                    $range->color = $set_result->range_color;
                    // On initialise le nombre de sets présents dans cette gamme
                    $range->sets_count = 0;
                    // On crée le tableau qui va contenir la liste des séries
                    $range->series = array();
                }
                // La gamme a déjà été récupérée avec les figurines
                else {
                    $range = $ranges[$set_result->range_id];
                    // On initialise simplement le nombre de sets présents dans cette gamme
                    $range->sets_count = 0;
                }
                // On mémorise l'id de la gamme
                $previous_range = $set_result->range_id;
            }

            // Si cette gamme contient une nouvelle série,
            if($set_result->serie_id != $previous_serie)
            {
                // Et que cette série ne figure pas déjà dans la liste
                if(!array_key_exists($set_result->serie_id, $range->series))
                {
                    // On récupère les infos de la série
                    $serie = new StdClass();
                    $serie->id = $set_result->serie_id;
                    $serie->name = $set_result->serie_name;
                    // On initialise le nombre de figurines présentes dans cette série
                    $serie->sets_count = 0;
                    // On ajoute la série à la liste
                    $range->series[$serie->id] = $serie;
                }
                // La série figure déjà dans la liste
                else
                {
                    $serie = $range->series[$set_result->serie_id];
                    // On initialise simplement le nombre de sets présents dans cette série
                    $serie->sets_count = 0;
                }
                // On crée le tableau qui va contenir la liste des sets
                $serie->sets = array();
                // Et on mémorise l'id de la série
                $previous_serie = $set_result->serie_id;
            }
            // On récupère les infos du set
            $set = new StdClass();
            $set->id = $set_result->id;
            $set->number = $set_result->number;
            $set->name = $set_result->name;
            $set->alias = $set_result->alias;
            $set->picture = $set_result->picture;
            $set->release_year = $set_result->release_year;
            $set->price = $set_result->price;
            $set->count = $set_result->count;
            $set->sell_total = $set_result->sell_total;
            $set->exchange_total = $set_result->exchange_total;
            $set->price = $set_result->price;
            // On l'ajoute à la liste
            $serie->sets[$set->id] = $set;
            // Et on met à jour les nombres de sets
            $range->sets_count += $set->count;
            $serie->sets_count += $set->count;
            $sets_count += $set->count;
        }
        // Lorsqu'on a fini de parcourir les sets, si une gamme existe
        if(isset($range))
        {
            // On n'oublie pas de la rajouter à la liste des gammes
            $ranges[$range->id] = $range;
        }

        // On prépare les données pour l'affichage du graphique
        $total_count = $minifigures_count + $sets_count;
        foreach($ranges as $range)
        {
            $item_count = 0;
            if(isset($range->minifigures_count))
            {
                $item_count += $range->minifigures_count;
            }
            if(isset($range->sets_count))
            {
                $item_count += $range->sets_count;
            }
            $range->percentage = round(($item_count*100) / $total_count, 5);
        }

        // On retourne la collection
        $collection = new stdClass();
        $collection->ranges = $ranges;
        $collection->minifigures_count = $minifigures_count;
        $collection->sets_count = $sets_count;

        return $collection;
    }

}
