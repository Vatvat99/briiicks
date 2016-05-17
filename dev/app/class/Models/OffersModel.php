<?php
namespace App\Models;
use Core\Models\Models;

/**
 * Classe associée aux annonces
 */
class OffersModel extends Models
{

    /**
     * @var string $table Nom de la table
     */
    protected $table;

    /**
     * @var string $entity_class Nom de la classe associée aux entrées de la table
     */
    protected $entity_class = 'App\Entities\OfferEntity';

    /**
     * Cherche en bdd l'annonce correspondant à un id donné
     * @param int $offer_id Id de l'annonce
     */
    public function getOffer($offer_id)
    {
        // Sélection de l'annonce correspondant à l'id
        $offer = $this->query(
            'SELECT of.id,
            of.title,
            of.description,
            of.type,
            of.price,
            of.datetime,
            mb.id as member_id,
            mb.lastname as member_lastname,
            mb.firstname as member_firstname,
            mb.city as member_city,
            mb.region as member_region
            FROM ' . $this->table . ' of
            LEFT JOIN briiicks_members mb ON of.member_id = mb.id
            WHERE of.id = ? AND of.active = 1',
            array($offer_id),
            true
        );

        // On a trouvé une annonce correspondant à l'id
        if(isset($offer))
        {
            // Sélection des images liées à l'annonce
            $pictures = $this->query(
                'SELECT filename FROM briiicks_offers_pictures WHERE offer_id = ?',
                array($offer_id)
            );
            // Sélection des minifigures de l'annonce (avec jointure pour avoir le nom de la gamme et de la série)
            $minifigures = $this->query(
                'SELECT mf.id as id,
                mf.name as name,
                mf.alias as alias,
                mf.picture as picture,
                mf.release_year as release_year,
                sr.name as serie_name,
                rg.name as range_name,
                of_it.count as count
                FROM briiicks_offers_items of_it
                LEFT JOIN briiicks_minifigures mf ON mf.id = of_it.item_id
                LEFT JOIN briiicks_series_minifigures sr_mf ON sr_mf.minifigure_id = mf.id
                LEFT JOIN briiicks_series sr ON sr.id = sr_mf.serie_id
                LEFT JOIN briiicks_ranges_series rg_sr ON rg_sr.serie_id = sr.id
                LEFT JOIN briiicks_ranges rg ON rg.id = rg_sr.range_id
                WHERE of_it.is_minifigure = 1 AND of_it.offer_id = ?',
                array($offer_id)
            );
            // Sélection des sets de l'annonce (avec jointure pour avoir le nom de la gamme et de la série)
            $sets = $this->query(
                'SELECT st.id as id,
                st.number as number,
                st.name as name,
                st.alias as alias,
                st.picture as picture,
                st.release_year as release_year,
                sr.name as serie_name,
                rg.name as range_name,
                of_it.count as count
                FROM briiicks_offers_items of_it
                LEFT JOIN briiicks_sets st ON st.id = of_it.item_id
                LEFT JOIN briiicks_series_sets sr_st ON sr_st.set_id = st.id
                LEFT JOIN briiicks_series sr ON sr.id = sr_st.serie_id
                LEFT JOIN briiicks_ranges_series rg_sr ON rg_sr.serie_id = sr.id
                LEFT JOIN briiicks_ranges rg ON rg.id = rg_sr.range_id
                WHERE of_it.is_set = 1 AND of_it.offer_id = ?',
                array($offer_id)
            );

            // On présente l'ensemble...
            $offer->pictures = $pictures;
            $offer->minifigures = $minifigures;
            $offer->sets = $sets;
            // ...et on le retourne
            return $offer;
        }
        return false;
    }

    /**
     * Récupère en bdd la liste des annonces
     */
    public function getOffersList()
    {
        $result = $this->query(
            'SELECT *
			FROM ' . $this->table
        );
        // Si il y a des résultats on les retourne
        if (isset($result))
        {
            return $result;
        }
        return false;
    }

    /**
     * Récupère en bdd la liste des annonces actives d'un membre
     * @param int $member_id Id du membre
     */
    public function getOffersByMember($member_id) {

        return $this->query(
            'SELECT of.id as id,
            of.title as title,
            of.type as type,
            of.price as price,
            of.datetime as datetime,
            of_pc.filename as picture
            FROM ' . $this->table . ' of
            LEFT JOIN briiicks_offers_pictures of_pc ON of.id = of_pc.offer_id
            WHERE of.member_id = ?
            GROUP BY of.id
            ORDER BY of.datetime',
            array($member_id)
        );

    }

    /**
     *	Récupère une partie des annonces
     * @param $first_entry int Première entrée à partir de laquelle récupérer les résultats
     * @param $entries_number int Nombre d'entrées à récupérer
     */
    public function some($first_entry, $entries_number) {

        return $this->query(
            'SELECT of.id as id,
            of.title as title,
            of.type as type,
            of.price as price,
            of.active as active,
            of.datetime as datetime,
            mb.lastname as member_lastname,
            mb.firstname as member_firstname,
            mb.city as member_city,
            mb.region as member_region,
            of_pc.filename as picture
			FROM ' . $this->table . ' of
			LEFT JOIN briiicks_members mb ON of.member_id = mb.id
			LEFT JOIN briiicks_offers_pictures of_pc ON of.id = of_pc.offer_id
			GROUP BY of.id
            ORDER BY of.datetime
			LIMIT ' . $first_entry . ', ' . $entries_number
        );

    }

    /**
     *	Récupère une partie des annonces contenant un certain élément
     * @param $item_id int Id de l'élément devant être contenu dans l'annonce
     * @param $item_type string Type de l'élément (minifigure/set) devant être contenu dans l'annonce
     * @param $first_entry int Première entrée à partir de laquelle récupérer les résultats
     * @param $entries_number int Nombre d'entrées à récupérer
     */
    public function someWithItem($item_id, $item_type, $first_entry, $entries_number) {

        return $this->query(
            'SELECT of.id as id,
            of.title as title,
            of.type as type,
            of.price as price,
            of.active as active,
            of.datetime as datetime,
            mb.lastname as member_lastname,
            mb.firstname as member_firstname,
            mb.city as member_city,
            mb.region as member_region,
            of_pc.filename as picture
			FROM ' . $this->table . ' of
			LEFT JOIN briiicks_members mb ON of.member_id = mb.id
			LEFT JOIN briiicks_offers_pictures of_pc ON of.id = of_pc.offer_id
			LEFT JOIN briiicks_offers_items of_it ON of_it.offer_id = of.id
			WHERE of_it.item_id = ? AND is_' . $item_type . ' = 1
			GROUP BY of.id
            ORDER BY of.datetime
			LIMIT ' . $first_entry . ', ' . $entries_number,
            array($item_id)
        );

    }

    /**
     *	Compte toutes les entrées contenant un certain élément
     * @param $item_id int Id de l'élément devant être contenu dans l'annonce
     * @param $item_type string Type de l'élément (minifigure/set) devant être contenu dans l'annonce
     */
    public function countWithItem($item_id, $item_type) {
        return $this->query(
            'SELECT COUNT(*)
            FROM ' . $this->table . ' of
            LEFT JOIN briiicks_offers_items of_it ON of_it.offer_id = of.id
            WHERE of_it.item_id = ? AND is_' . $item_type . ' = 1',
            array($item_id),
            true
        );
    }

    /**
     * Insère une nouvelle annonce
     * @param string $title Titre de l'annonce
     * @param string $description Texte de l'annonce
     * @param string $type Type de l'annonce
     * @param string $price Prix de l'annonce
     * @param array $sets Liste des sets présents dans l'annonce ainsi que leur nombre d'exemplaires
     * @param array $minifigures Liste des figurines présentes dans l'annonce ainsi que leur nombre d'exemplaires
     * @param int $member_id Id du membre ayant posté l'annonce
     */
    public function add($title, $description, $type, $price, $sets, $minifigures, $member_id)
    {

        // On enregistre la nouvelle annonce
        $this->create(
            array(
                'title' => $title,
                'description' => $description,
                'type' => $type,
                'price' => $price,
                'active' => 1,
                'datetime' => date('Y-m-d H:i:s'),
                'member_id' => $member_id
            )
        );

        $offer_id = (int) $this->db->lastInsertId();

        // Si on a des figurines
        if(is_array($minifigures))
        {
            // On enregistre l'association aux figurines
            foreach ($minifigures as $minifigure_id => $minifigure_count) {
                $this->query(
                    'INSERT INTO briiicks_offers_items (offer_id, item_id, is_minifigure, is_set, count)
                VALUES(?, ?, 1, 0, ?)',
                    array(
                        $offer_id,
                        $minifigure_id,
                        $minifigure_count
                    )
                );
            }
        }
        // Si on a des sets
        if(is_array($sets))
        {
            // On enregistre l'association aux sets
            foreach ($sets as $set_id => $set_count) {
                $this->query(
                    'INSERT INTO briiicks_offers_items (offer_id, item_id, is_minifigure, is_set, count)
                VALUES(?, ?, 0, 1, ?)',
                    array(
                        $offer_id,
                        $set_id,
                        $set_count
                    )
                );
            }
        }
        return $offer_id;

    }

    /**
     * Insère une image liée à une annonce
     * @param int $offer_id Id de l'annonce à laquelle est liée l'image
     * @param string $filename Nom du fichier
     */
    public function savePicture($offer_id, $filename)
    {
        // On enregistre l'image
        return $this->query(
            'INSERT INTO briiicks_offers_pictures (offer_id, filename)
                VALUES(?, ?)',
            array(
                $offer_id,
                $filename
            )
        );
    }

    /**
     * Supprime une image liée à une annonce
     * @param int $offer_id Id de l'annonce à laquelle est liée l'image
     * @param string $filename Nom du fichier
     */
    public function removePicture($offer_id, $filename)
    {
        // on supprime l'image
        return $this->query(
            'DELETE FROM briiicks_offers_pictures
			WHERE offer_id = ? AND filename = ?',
            array(
                $offer_id,
                $filename
            )
        );
    }

    /**
     * Modifie une annonce
     * @param int $offer_id Id de l'annonce à modifier
     * @param string $title Titre de l'annonce
     * @param string $description Texte de l'annonce
     * @param string $type Type de l'annonce
     * @param string $price Prix de l'annonce
     * @param array $sets Liste des sets présents dans l'annonce ainsi que leur nombre d'exemplaires
     * @param array $minifigures Liste des figurines présentes dans l'annonce ainsi que leur nombre d'exemplaires
     */
    public function edit($offer_id, $title, $description, $type, $price, $sets, $minifigures)
    {

        // On modifie le set
        $this->query(
            'UPDATE ' . $this->table . '
            SET title = COALESCE(NULLIF(?, null), title),
                description = COALESCE(NULLIF(?, null), description),
                type = COALESCE(NULLIF(?, null), type),
                price = COALESCE(NULLIF(?, null), price)
            WHERE id = ?',
            array(
                $title,
                $description,
                $type,
                $price,
                $offer_id
            )
        );

        // On supprime les anciens sets /figurines
        $this->query(
            'DELETE FROM briiicks_offers_items
			WHERE offer_id = ?',
            array($offer_id)
        );

        // Et on ajoute les nouveaux sets / figurines
        // Si on a des figurines
        if(is_array($minifigures))
        {
            // On enregistre l'association aux figurines
            foreach ($minifigures as $minifigure_id => $minifigure_count) {
                $this->query(
                    'INSERT INTO briiicks_offers_items (offer_id, item_id, is_minifigure, is_set, count)
                VALUES(?, ?, 1, 0, ?)',
                    array(
                        $offer_id,
                        $minifigure_id,
                        $minifigure_count
                    )
                );
            }
        }
        // Si on a des sets
        if(is_array($sets))
        {
            // On enregistre l'association aux sets
            foreach ($sets as $set_id => $set_count) {
                $this->query(
                    'INSERT INTO briiicks_offers_items (offer_id, item_id, is_minifigure, is_set, count)
                VALUES(?, ?, 0, 1, ?)',
                    array(
                        $offer_id,
                        $set_id,
                        $set_count
                    )
                );
            }
        }
        return true;
    }

}