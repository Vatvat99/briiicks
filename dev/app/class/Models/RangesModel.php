<?php
namespace App\Models;
use Core\Models\Models;

/**
 * Classe associée aux gammes
 */
class RangesModel extends Models {

    /**
     * @var string $table Nom de la table
     */
    protected $table;

    /**
     * @var string $entity_class Nom de la classe associée aux entrées de la table
     */
    protected $entity_class = 'App\Entities\RangeEntity';

    /**
     * Cherche en bdd la gamme correspondant à un id donné
     * @param int $range_id Id de la gamme
     */
    public function getRange($range_id)
    {
        // Sélection de la gamme correspondant à l'id
        $result = $this->query(
            'SELECT id, name, alias, color, picture FROM ' . $this->table . ' WHERE id = ?',
            array($range_id),
            true
        );
        // On a trouvé une gamme correspondant à l'id
        if (isset($result))
        {
            return $result;
        }
        return false;
    }

    /**
     * Récupère en bdd la liste des gammes de figurines
     */
    function old_getRanges()
    {

        // Sélection de toutes les gammes
        $result = $this->query(
            'SELECT id, name, alias, picture
            FROM briiicks_ranges
            ORDER BY name'
        );
        // On a trouvé des gammes
        if (isset($result))
        {
            return $result;
        }
    }

    /**
     * Enregistre une nouvelle gamme
     * @param string $name Nom de la gamme
     * @param string $alias Alias de la gamme
     * @param string $color Couleur de la gamme
     * @return int|string Id de la gamme créée ou message d'erreur
     */
    public function setRange($name, $alias, $color)
    {
        // Vérifie que la gamme n'existe pas déjà
        $data = $this->query(
            'SELECT COUNT(*) AS corresponding_ranges FROM ' . $this->table . ' WHERE alias = ?',
            array($alias),
            true
        );

        // Si la gamme existe déjà en base, on retourne un message
        if(isset($data) AND $data->corresponding_ranges >= 1)
        {
            return 'Cette gamme existe déjà.';
        }
        // La gamme n'existe pas
        else
        {
            // On enregistre la nouvelle gamme
            $this->create(
                array(
                    'name' => $name,
                    'alias' => $alias,
                    'color' => $color
                )
            );
            return (int) $this->db->lastInsertId();
        }
    }

    /**
     * Modifie une gamme
     * @param int $range_id Id de la gamme
     * @param string $name Nom de la gamme
     * @param string $alias Alias de la gamme
     * @param string $color Couleur de la gamme
     * @param string $picture Nom du visuel
     */
    public function editRange($range_id, $name, $alias, $color, $picture) {
        // On modifie la gamme
        $this->query(
            'UPDATE ' . $this->table . '
			SET name = COALESCE(NULLIF(?, null), name),
				alias = COALESCE(NULLIF(?, null), alias),
				color = COALESCE(NULLIF(?, null), color),
				picture = COALESCE(NULLIF(?, null), picture)
			WHERE id = ?',
            array(
                $name,
                $alias,
                $color,
                $picture,
                $range_id
            )
        );
        return true;
    }

    /**
     * Supprime une gamme
     * @param int $range_id Id de la gamme
     */
    public function deleteRange($range_id)
    {
        // On vérifie si la gamme contient des séries
        $result = $this->query(
            'SELECT COUNT(*)
            AS series_count
            FROM briiicks_ranges_series
            WHERE range_id = ?',
            array($range_id),
            true
        );
        // Si c'est le cas, on retourne un message d'erreur
        if (isset($result) AND $result->series_count >= 1)
        {
            return 'Impossible de supprimer cette gamme car celle-ci contient une ou plusieurs séries.';
        }
        // Si ce n'est pas le cas
        else
        {
            // On supprime la gamme correspondant à l'id
            return $this->delete($range_id);
        }
    }

    /**
     * Récupère en bdd la liste des gammes et leurs séries associées
     */
    public function getRangesWithSeries()
    {
        // On récupère toutes les gammes
        $ranges = $this->query(
            'SELECT rg.id AS id,
            rg.name AS name,
            rg.alias AS alias,
            rg.picture AS picture
            FROM ' . $this->table . ' rg
            ORDER BY rg.priority, rg.name'
        );

        // Pour chaque gamme, on récupère toutes les séries
        $i = 0;
        foreach ($ranges as $range) {
            $minifigures_in_range_count = 0;
            $sets_in_range_count = 0;
            $ranges[$i]->series = $this->query(
                'SELECT sr.id AS id,
                sr.name AS name,
                sr.alias AS alias,
                sr.picture AS picture
                FROM briiicks_series sr
                INNER JOIN briiicks_ranges_series rg_sr ON rg_sr.serie_id = sr.id
                WHERE rg_sr.range_id = ?
                ORDER BY sr.priority, sr.name',
                array($range->id)
            );

            // Pour chaque série
            $j = 0;
            foreach ($ranges[$i]->series as $serie)
            {
                // On compte le nombre de figurines
                $data = $this->query(
                    'SELECT COUNT(*)
                    FROM briiicks_minifigures mf
                    INNER JOIN briiicks_series_minifigures sr_mf ON sr_mf.minifigure_id = mf.id
                    WHERE sr_mf.serie_id = ?',
                    array($serie->id),
                    true
                );
                $ranges[$i]->series[$j]->minifigures_count = $data;
                $minifigures_in_range_count += $ranges[$i]->series[$j]->minifigures_count;
                // Et on compte le nombre de sets
                $data = $this->query(
                    'SELECT COUNT(*)
                    FROM briiicks_sets st
                    INNER JOIN briiicks_series_sets sr_st ON sr_st.set_id = st.id
                    WHERE sr_st.serie_id = ?',
                    array($serie->id),
                    true
                );
                $ranges[$i]->series[$j]->sets_count = $data;
                $sets_in_range_count += $ranges[$i]->series[$j]->sets_count;
                $j++;
            }
            $ranges[$i]->minifigures_count = $minifigures_in_range_count;
            $ranges[$i]->sets_count = $sets_in_range_count;
            $i++;
        }
        return $ranges;
    }

}