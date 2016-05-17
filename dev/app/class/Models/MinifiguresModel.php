<?php
namespace App\Models;
use Core\Models\Models;

/**
 * Classe associée aux figurines
 */
class MinifiguresModel extends Models {

    /**
     * @var string $table Nom de la table
     */
    protected $table;

    /**
     * @var string $entity_class Nom de la classe associée aux entrées de la table
     */
    protected $entity_class = 'App\Entities\MinifigureEntity';

    /**
     *	Récupère toutes les entrées
     */
    public function all() {
        return $this->query(
            'SELECT m.id AS id,
			m.name AS name,
			m.alias AS alias,
			m.picture AS picture,
			m.release_year AS release_year,
			sr.name AS serie_name,
			st.number AS set_number,
			st.name AS set_name,
			r.name AS range_name
			FROM ' . $this->table . ' m
			LEFT JOIN briiicks_series_minifigures sr_m ON m.id = sr_m.minifigure_id
			LEFT JOIN briiicks_series sr ON sr_m.serie_id = sr.id
			LEFT JOIN briiicks_sets_minifigures st_m ON m.id = st_m.minifigure_id
			LEFT JOIN briiicks_sets st ON st_m.set_id = st.id
			LEFT JOIN briiicks_ranges_series r_sr ON sr.id = r_sr.serie_id
			LEFT JOIN briiicks_ranges r ON r_sr.range_id = r.id
			ORDER BY r.priority, r.name, sr.priority, sr.name, m.name'
        );
    }

    /**
     *	Récupère toutes les entrées triées par nom
     */
    public function allOrderedByName() {
        return $this->query(
            'SELECT m.id AS id,
			m.name AS name,
			m.alias AS alias,
			m.picture AS picture,
			m.release_year AS release_year,
			sr.name AS serie_name,
			st.number AS set_number,
			st.name AS set_name,
			r.name AS range_name
			FROM ' . $this->table . ' m
			LEFT JOIN briiicks_series_minifigures sr_m ON m.id = sr_m.minifigure_id
			LEFT JOIN briiicks_series sr ON sr_m.serie_id = sr.id
			LEFT JOIN briiicks_sets_minifigures st_m ON m.id = st_m.minifigure_id
			LEFT JOIN briiicks_sets st ON st_m.set_id = st.id
			LEFT JOIN briiicks_ranges_series r_sr ON sr.id = r_sr.serie_id
			LEFT JOIN briiicks_ranges r ON r_sr.range_id = r.id
			ORDER BY m.name'
        );
    }

    /**
     *	Récupère une partie des entrées
     */
    public function some($first_entry, $entries_number) {
        return $this->query(
            'SELECT m.id AS id,
			m.name AS name,
			m.alias AS alias,
			m.picture AS picture,
			m.release_year AS release_year,
			sr.name AS serie_name,
			st.number AS set_number,
			st.name AS set_name,
			r.name AS range_name
			FROM ' . $this->table . ' m
			LEFT JOIN briiicks_series_minifigures sr_m ON m.id = sr_m.minifigure_id
			LEFT JOIN briiicks_series sr ON sr_m.serie_id = sr.id
			LEFT JOIN briiicks_sets_minifigures st_m ON m.id = st_m.minifigure_id
			LEFT JOIN briiicks_sets st ON st_m.set_id = st.id
			LEFT JOIN briiicks_ranges_series r_sr ON sr.id = r_sr.serie_id
			LEFT JOIN briiicks_ranges r ON r_sr.range_id = r.id
			ORDER BY r.priority, r.name, sr.priority, sr.name, m.name
            LIMIT ' . $first_entry . ', ' . $entries_number
        );
    }

    /**
     * Insère une nouvelle figurine
     */
    public function add($name, $alias, $serie_id, $release_year) {
        $data = $this->query(
            'SELECT COUNT(*) AS corresponding_minifigures_number
			FROM ' . $this->table . ' m
			LEFT JOIN briiicks_series_minifigures sr_m ON m.id = sr_m.minifigure_id
			WHERE m.alias = ?
			AND m.release_year = ?
			AND sr_m.serie_id = ?',
            array(
                $alias,
                $serie_id,
                $release_year
            ),
            true
        );
        // Si la figurine est présente en base, on retourne un message
        if(isset($data) AND $data->corresponding_minifigures_number >= 1)
        {
            return 'Cette figurine existe déjà.';
        }
        // La figurine n'est pas présente
        else
        {
            // On enregistre la nouvelle figurine
            $this->create(
                array(
                    'name' => $name,
                    'alias' => $alias,
                    'release_year' => $release_year
                )
            );
            $minifigure_id = (int) $this->db->lastInsertId();

            // On enregistre l'association à la série
            $this->query(
                'INSERT INTO briiicks_series_minifigures(serie_id, minifigure_id)
            VALUES(?, ?)', array($serie_id, $minifigure_id)
            );
            return $minifigure_id;
        }
    }

    /**
     * Modifie une figurine
     */
    function edit($minifigure_id, $name, $alias, $picture, $release_year, $serie_id) {
        // On modifie la figurine
        $this->query(
            'UPDATE ' . $this->table . '
            SET name = COALESCE(NULLIF(?, null), name),
                alias = COALESCE(NULLIF(?, null), alias),
                picture = COALESCE(NULLIF(?, null), picture),
                release_year = COALESCE(NULLIF(?, null), release_year)
            WHERE id = ?',
            array(
                $name,
                $alias,
                $picture,
                $release_year,
                $minifigure_id
            )
        );
        // On modifie l'association à la série
        $this->query(
            'UPDATE briiicks_series_minifigures
            SET serie_id = COALESCE(NULLIF(?, null), serie_id)
            WHERE minifigure_id = ?',
            array(
                $serie_id,
                $minifigure_id
            )
        );
        return true;
    }

    /**
     * Cherche en bdd la figurine correspondant à un id donné
     * @param int $minifigure_id Id de la figurine
     */
    public function getMinifigure($minifigure_id)
    {

        // Sélection de la figurine correspondant à l'id
        $result = $this->query(
            'SELECT m.id AS id,
            m.name AS name,
            m.alias AS alias,
            m.picture AS picture,
            m.release_year AS release_year,
            sr.id AS serie_id,
            sr.name AS serie_name,
            st.number AS set_number,
            st.name AS set_name,
            r.id AS range_id,
            r.name AS range_name
            FROM ' . $this->table . ' m
            LEFT JOIN briiicks_series_minifigures sr_m ON m.id = sr_m.minifigure_id
            LEFT JOIN briiicks_series sr ON sr_m.serie_id = sr.id
            LEFT JOIN briiicks_sets_minifigures st_m ON m.id = st_m.minifigure_id
            LEFT JOIN briiicks_sets st ON st_m.set_id = st.id
            LEFT JOIN briiicks_ranges_series r_sr ON sr.id = r_sr.serie_id
            LEFT JOIN briiicks_ranges r ON r_sr.range_id = r.id
            WHERE m.id = ?',
            array($minifigure_id),
            true
        );

        // On a trouvé une figurine correspondant à l'id
        if (isset($result)) {
            return $result;
        }
        return false;
    }

    /**
     * Récupère en bdd l'ensemble des figurines comprises dans une série
     * @param int $serie_id Id de la série
     */
    public function getMinifiguresFromSerie($serie_id) {

        return $this->query(
            'SELECT m.id AS id,
                m.name AS name,
                m.alias AS alias,
                m.picture AS picture,
                m.release_year AS release_year
            FROM ' . $this->table . ' m
            INNER JOIN briiicks_series_minifigures s_m ON s_m.minifigure_id = m.id
            WHERE s_m.serie_id = ?
            ORDER BY m.name',
            array($serie_id)
        );

    }

    /**
     * Récupère en bdd une liste de figurines
     * @param string $range_alias Alias de la gamme à laquelle appartiennent les figurines
     * @param string $serie_alias Alias de la série à laquelle appartiennent les figurines
     */
    function getMinifiguresList($range_alias = '', $serie_alias = '')
    {
        // On récupère toutes les gammes
        // Si je n'ai aucun alias -> on retourne toutes les figurines
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
        // Si j'ai un alias gamme mais pas d'alias série -> on récupère toutes les figurines de la gamme
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
        // Si j'ai un alias série -> on retourne toutes les figurines de la série
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
        $minifigures_count = 0;
        $i = 0;
        foreach ($return['ranges'] as $range)
        {
        $minifigures_in_range_count = 0;
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

            // Pour chaque série, on récupère toutes les figurines, associées aux nombre d'annonces contenant la figurine, et le prix le plus bas
            $j = 0;
            foreach ($return['ranges'][$i]->series as $serie)
            {

                $return['ranges'][$i]->series[$j]->minifigures = $this->query(
                    'SELECT mf.id AS id,
                        mf.name AS name,
                        mf.alias AS alias,
                        mf.picture AS picture,
                        COUNT(case when of.type LIKE \'%Vente%\' then 1 else null end) AS sell_total,
                        COUNT(case when of.type LIKE \'%Echange%\' then 1 else null end) AS exchange_total,
                        MIN(of.price) AS price
                    FROM briiicks_minifigures mf
                    INNER JOIN briiicks_series_minifigures sr_mf ON sr_mf.minifigure_id = mf.id
                    LEFT JOIN briiicks_offers_items of_it ON of_it.item_id = mf.id
                    LEFT JOIN briiicks_offers of ON of.id = of_it.offer_id
                    WHERE sr_mf.serie_id = ?
                    GROUP BY mf.id
                    ORDER BY mf.id',
                    array($serie->id)
                );

                $minifigures_in_range_count += count($return['ranges'][$i]->series[$j]->minifigures);
                $minifigures_count += count($return['ranges'][$i]->series[$j]->minifigures);
                $return['ranges'][$i]->series[$j]->minifigures_count = count($return['ranges'][$i]->series[$j]->minifigures);
                $j++;
            }
            $return['ranges'][$i]->minifigures_count = $minifigures_in_range_count;
            $i++;
        }
        $return['minifigures_count'] = $minifigures_count;

        // Si il y a des résultats on les retourne
        if (isset($return))
        {
            return $return;
        }

    }

}