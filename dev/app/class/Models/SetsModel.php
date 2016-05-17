<?php
namespace App\Models;
use Core\Models\Models;

/**
 * Classe associée aux sets
 */
class SetsModel extends Models {

    /**
     * @var string $table Nom de la table
     */
    protected $table;

    /**
     * @var string $entity_class Nom de la classe associée aux entrées de la table
     */
    protected $entity_class = 'App\Entities\SetEntity';

    /**
     *	Récupère toutes les entrées
     */
    public function all() {

        return $this->query(
            'SELECT id AS id,
            number AS number,
            name AS name,
            alias AS alias,
            picture AS picture,
            release_year AS release_year,
            price AS price
            FROM ' . $this->table . '
            ORDER BY release_year DESC, name'
        );

    }

    /**
     *	Récupère toutes les entrées triées par nom
     */
    public function allOrderedByName() {

        return $this->query(
            'SELECT id AS id,
            number AS number,
            name AS name,
            alias AS alias,
            picture AS picture,
            release_year AS release_year,
            price AS price
            FROM ' . $this->table . '
            ORDER BY name'
        );

    }

    /**
     *	Récupère une partie des entrées
     */
    public function some($first_entry, $entries_number) {

        return $this->query(
            'SELECT id AS id,
            number AS number,
            name AS name,
            alias AS alias,
            picture AS picture,
            release_year AS release_year,
            price AS price
            FROM ' . $this->table . '
            ORDER BY release_year DESC, name
            LIMIT ' . $first_entry . ', ' . $entries_number
        );

    }

    /**
     * Insère une nouveau set
     */
    public function add($number, $name, $alias, $picture, $release_year, $price, $serie_id, $minifigures_id) {

        // Vérifie que le set n'est pas déjà présent en base
        $data = $this->query(
            'SELECT COUNT(*) AS corresponding_sets_number
            FROM ' . $this->table . '
            WHERE number = ?',
            array($number),
            true
        );
        // Si le set est présent en base, on retourne un message
        if(isset($data) AND $data->corresponding_sets_number >= 1)
        {
            return 'Ce set existe déjà.';
        }
        // Le set n'est pas présent
        else
        {
            // On enregistre le nouveau set
            $this->create(
                array(
                    'number' => $number,
                    'name' => $name,
                    'alias' => $alias,
                    'picture' => $picture,
                    'release_year' => $release_year,
                    'price' => $price
                )
            );

            $set_id = (int) $this->db->lastInsertId();
            // On enregistre l'association à la série
            $this->query(
                'INSERT INTO briiicks_series_sets(serie_id, set_id)
            VALUES(?, ?)',
                array(
                    $serie_id,
                    $set_id
                )
            );

            // Si on a des id de figurines
            if(is_array($minifigures_id))
            {
                // On enregistre l'association aux figurines
                foreach ($minifigures_id as $minifigure_id) {
                    $this->query(
                        'INSERT INTO briiicks_sets_minifigures(set_id, minifigure_id)
                    VALUES(?, ?)',
                        array(
                            $set_id,
                            $minifigure_id
                        )
                    );
                }
            }
            return $set_id;
        }

    }

    /**
     * Modifie un set
     */
    public function edit($set_id, $number, $name, $alias, $picture, $release_year, $price, $serie_id, $minifigures_id) {

        // On modifie le set
        $this->query(
            'UPDATE ' . $this->table . '
            SET number = COALESCE(NULLIF(?, null), number),
                name = COALESCE(NULLIF(?, null), name),
                alias = COALESCE(NULLIF(?, null), alias),
                picture = COALESCE(NULLIF(?, null), picture),
                release_year = COALESCE(NULLIF(?, null), release_year),
                price = COALESCE(NULLIF(?, null), price)
            WHERE id = ?',
            array(
                $number,
                $name,
                $alias,
                $picture,
                $release_year,
                $price,
                $set_id
            )
        );

        // On modifie l'association à la série
        $this->query(
            'UPDATE briiicks_series_sets
			SET serie_id = COALESCE(NULLIF(?, null), serie_id)
			WHERE set_id = ?',
            array(
                $serie_id,
                $set_id
            )
        );

        // Si on a des id de figurines
        if($minifigures_id != null)
        {
            // On modifie l'association aux figurines, en supprimant les anciennes
            $this->query(
                'DELETE FROM briiicks_sets_minifigures WHERE set_id = ?',
                array($set_id)
            );
            // Et en rajoutant les nouvelles
            foreach ($minifigures_id as $minifigure_id) {
                $this->query(
                    'INSERT INTO briiicks_sets_minifigures(set_id, minifigure_id)
                    VALUES(?, ?)',
                    array(
                        $set_id,
                        $minifigure_id
                    )
                );
            }
        }
        return true;

    }

    /**
     * Cherche en bdd le set correspondant à un id donné
     * @param $set_id Id du set
     */
    public function getSet($set_id)
    {

        // Sélection du set correspondant à l'id
        $result = $this->query(
            'SELECT st.id AS id,
                st.number AS number,
                st.name AS name,
                st.alias AS alias,
                st.picture AS picture,
                st.release_year AS release_year,
                st.price AS price,
                sr.id AS serie_id,
                sr.name AS serie_name,
                rg.id AS range_id,
                rg.name AS range_name
            FROM ' . $this->table . ' st
            LEFT JOIN briiicks_series_sets sr_st ON st.id = sr_st.set_id
            LEFT JOIN briiicks_series sr ON sr_st.serie_id = sr.id
            LEFT JOIN briiicks_ranges_series rg_sr ON sr.id = rg_sr.serie_id
            LEFT JOIN briiicks_ranges rg ON rg_sr.range_id = rg.id
            WHERE st.id = ?',
            array($set_id),
            true
        );
        // Et on récupère les figurines associées à ce set
        $result_minifigures = $this->query(
            'SELECT mf.id AS id
			FROM briiicks_minifigures mf
			LEFT JOIN briiicks_sets_minifigures st_mf ON mf.id = st_mf.minifigure_id
			WHERE st_mf.set_id = ?',
            array($set_id)
        );

        // Présentation des données dans un array imbriqué
        $result->minifigures_id = array();
        foreach ($result_minifigures as $minifigure) {
            $result->minifigures_id[] = $minifigure->id;
        }

        // On a trouvé un set correspondant à l'id
        if (isset($result))
        {
            return $result;
        }
        else {
            return false;
        }
    }

    /**
     * Récupère en bdd une liste de sets
     * @param string $range_alias Alias de la gamme à laquelle appartiennent les sets
     * @param string $serie_alias Alias de la série à laquelle appartiennent les sets
     */
    function getSetsList($range_alias = '', $serie_alias = '')
    {
        // On récupère toutes les gammes
        // Si je n'ai aucun alias -> on retourne tous les sets
        if ($range_alias == '' AND $serie_alias == '')
        {
            $return['ranges'] = $this->query(
                'SELECT rg.id AS id,
                    rg.name AS name,
                    rg.alias AS alias
                FROM briiicks_ranges rg
                ORDER BY rg.priority, rg.name'
            );
        }
        // Si j'ai un alias gamme mais pas d'alias série -> on récupère tous les sets de la gamme
        if ($range_alias != '' AND $serie_alias == '')
        {
            $return['ranges'] = $this->query(
                'SELECT rg.id AS id,
            rg.name AS name,
            rg.alias AS alias
            FROM briiicks_ranges rg
            WHERE rg.alias = ?
            ORDER BY rg.priority, rg.name',
                array($range_alias)
            );
        }
        // Si j'ai un alias série -> on retourne tous les sets de la série
        if ($serie_alias != '')
        {
            $return['ranges'] = $this->query(
                'SELECT rg.id AS id,
                    rg.name AS name,
                    rg.alias AS alias
                FROM briiicks_ranges rg
                INNER JOIN briiicks_ranges_series rg_sr ON rg_sr.range_id = rg.id
                INNER JOIN briiicks_series sr ON sr.id = rg_sr.serie_id
                WHERE sr.alias = ?
                ORDER BY rg.priority, rg.name',
                array($serie_alias)
            );
        }

        // Pour chaque gamme, on récupère toutes les séries
        $sets_count = 0;
        $i = 0;
        foreach ($return['ranges'] as $range)
        {
            $sets_in_range_count = 0;
            // Si je n'ai pas d'alias série, je récupère toutes les séries
            if($serie_alias == '') {
                $return['ranges'][$i]->series = $this->query(
                    'SELECT sr.id AS id,
                        sr.name AS name,
                        sr.alias AS alias
                    FROM briiicks_series sr
                    INNER JOIN briiicks_ranges_series rg_sr ON rg_sr.serie_id = sr.id
                    WHERE rg_sr.range_id = ?
                    ORDER BY sr.priority, sr.name',
                    array($range->id)
                );
            }
            // Si j'ai un alias série, je récupère seulement la série correspondante
            else {
                $return['ranges'][$i]->series = $this->query(
                    'SELECT sr.id AS id,
                        sr.name AS name,
                        sr.alias AS alias
                    FROM briiicks_series sr
                    INNER JOIN briiicks_ranges_series rg_sr ON rg_sr.serie_id = sr.id
                    WHERE rg_sr.range_id = ?
                    AND sr.alias = ?
                    ORDER BY sr.priority, sr.name',
                    array(
                        $range->id,
                        $serie_alias
                    )
                );
            }

            // Pour chaque série, on récupère tous les sets
            $j = 0;
            foreach ($return['ranges'][$i]->series as $serie)
            {

                $return['ranges'][$i]->series[$j]->sets = $this->query(
                    'SELECT st.id AS id,
                    st.number AS number,
                    st.name AS name,
                    st.alias AS alias,
                    st.picture AS picture,
                    COUNT(case when of.type LIKE \'%Vente%\' then 1 else null end) AS sell_total,
                    COUNT(case when of.type LIKE \'%Echange%\' then 1 else null end) AS exchange_total,
                    MIN(of.price) AS price
                    FROM briiicks_sets st
                    INNER JOIN briiicks_series_sets sr_st ON sr_st.set_id = st.id
                    LEFT JOIN briiicks_offers_items of_it ON of_it.item_id = st.id
                    LEFT JOIN briiicks_offers of ON of.id = of_it.offer_id
                    WHERE sr_st.serie_id = ?
                    GROUP BY st.id
                    ORDER BY st.id',
                        array($serie->id)
                );

                $sets_in_range_count += count($return['ranges'][$i]->series[$j]->sets);
                $sets_count += count($return['ranges'][$i]->series[$j]->sets);
                $return['ranges'][$i]->series[$j]->sets_count = count($return['ranges'][$i]->series[$j]->sets);
                $j++;
            }
            $return['ranges'][$i]->sets_count = $sets_in_range_count;
            $i++;
        }
        $return['sets_count'] = $sets_count;

        // Si il y a des résultats on les retourne
        if (isset($return))
        {
            return $return;
        }

    }

}