<?php
namespace App\Models;
use Core\Models\Models;

/**
 * Classe associée aux séries
 */
class SeriesModel extends Models {

    /**
     * @var string $table Nom de la table
     */
    protected $table;

    /**
     * @var string $entity_class Nom de la classe associée aux entrées de la table
     */
    protected $entity_class = 'App\Entities\SerieEntity';

    /**
     * Cherche en bdd la série correspondant à un id donné
     * @param int $serie_id Id de la série
     */
    public function getSerie($serie_id)
    {
        // Sélection de la série correspondant à l'id
        $result = $this->query(
            'SELECT s.id AS id,
            s.name AS name,
            s.alias AS alias,
            s.picture AS picture,
            s.priority AS priority,
            r.id AS range_id,
            r.name AS range_name,
            r.alias AS range_alias,
            r.color AS range_color,
            r.picture AS range_picture
            FROM ' . $this->table . ' s
            INNER JOIN briiicks_ranges_series r_s ON r_s.serie_id = s.id
            INNER JOIN briiicks_ranges r ON r.id = r_s.range_id
            WHERE s.id = ?',
            array($serie_id),
            true
        );
        // On a trouvé une série correspondant à l'id
        if (isset($result))
        {
            return $result;
        }
        return false;
    }

    /**
     * Récupère en bdd la liste des séries
     */
    public function getSeriesList()
    {
        $result = $this->query(
            'SELECT s.id AS id,
            s.name AS name,
            s.alias AS alias,
            s.picture AS picture,
            r.id AS range_id,
            r.name AS range_name,
            r.alias AS range_alias,
            r.color AS range_color,
            r.picture AS range_picture
            FROM ' . $this->table . ' s
            INNER JOIN briiicks_ranges_series r_s ON r_s.serie_id = s.id
            INNER JOIN briiicks_ranges r ON r.id = r_s.range_id
            ORDER BY r.priority, r.name, s.priority, s.name'
        );
        // Si il y a des résultats on les retourne
        if (isset($result))
        {
            return $result;
        }
        return false;
    }

    /**
     *	Récupère une partie des entrées
     */
    public function some($first_entry, $entries_number) {

        return $this->query(
            'SELECT s.id AS id,
            s.name AS name,
            s.alias AS alias,
            s.picture AS picture,
            r.id AS range_id,
            r.name AS range_name,
            r.alias AS range_alias,
            r.color AS range_color,
            r.picture AS range_picture
            FROM ' . $this->table . ' s
            INNER JOIN briiicks_ranges_series r_s ON r_s.serie_id = s.id
            INNER JOIN briiicks_ranges r ON r.id = r_s.range_id
            ORDER BY r.priority, r.name, s.priority, s.name
            LIMIT ' . $first_entry . ', ' . $entries_number
        );

    }

    /**
     * Récupère en bdd l'ensemble des séries comprises dans une gamme
     * @param int $range_id Id de la gamme
     * @return mixed
     */
    public function getSeriesFromRange($range_id)
    {
        $result = $this->query(
            'SELECT s.id AS id,
            s.name AS name,
            s.alias AS alias,
            s.picture AS picture
            FROM ' . $this->table . ' s
            INNER JOIN briiicks_ranges_series r_s ON r_s.serie_id = s.id
            WHERE r_s.range_id = ?
            ORDER BY s.priority, s.name',
            array($range_id)
        );
        // Si il y a des résultats on les retourne
        if (isset($result))
        {
            return $result;
        }
    }

    /**
     * Enregistre une nouvelle série
     * @param string $name Nom de la série
     * @param string $alias Alias de la série
     * @param int $range_id Id de la gamme à laquelle la série appartient
     */
    public function setSerie($name, $alias, $range_id) {

        // On vérifie que la gamme existe en base
        $data = $this->query(
            'SELECT COUNT(*) AS corresponding_ranges_number FROM briiicks_ranges WHERE id = ?',
            array($range_id),
            true
        );
        // Si la gamme n'est pas présente en base, on retourne un message
        if(isset($data) AND $data->corresponding_ranges_number < 1)
        {
            return 'La gamme à laquelle appartient cette série n\'existe pas.';
        }
        // La gamme existe bien
        else
        {
            // On vérifie que la série n'est pas déjà présente en base
            $data = $this->query(
                'SELECT id FROM ' . $this->table . ' WHERE alias = ?',
                array($alias),
                true
            );
            // Si on a trouvé un id correspondant à l'alias
            if(isset($data))
            {
                // On cherche la gamme à laquelle cette série appartient
                $data_2 = $this->query(
                    'SELECT range_id FROM briiicks_ranges_series WHERE serie_id = ?',
                    array($data->id),
                    true
                );
                // Si la série appartient à la même gamme que la série que l'on veut enregistrer
                if(isset($data_2) && $data_2->range_id == $range_id)
                {
                    // On retourne un message
                    return 'Cette série existe déjà.';
                }
            }

            // La série ne correspond à aucune série déjà présente en base
            // On enregistre la nouvelle série
            $this->create(
                array(
                    'name' => $name,
                    'alias' => $alias
                )
            );
            $serie_id = (int) $this->db->lastInsertId();
            // On enregistre la correspondance avec la gamme
            $this->query(
                'INSERT INTO briiicks_ranges_series(range_id, serie_id) VALUES(?, ?)', array($range_id, $serie_id)
            );
            return $serie_id;
        }

    }

    /**
     * Modifie une série
     * @param int $serie_id Id de la série
     * @param string $name Nom de la série
     * @param string $alias Alias de la série
     * @param string $picture Nom du visuel
     * @param int $range_id Id de la gamme à laquelle appartient la série
     */
    public function editSerie($serie_id, $name, $alias, $picture, $range_id)
    {
        if($range_id != null)
        {
            // Vérifie que la gamme existe en base
            $data = $this->query(
                'SELECT COUNT(*) AS corresponding_ranges_number  FROM briiicks_ranges WHERE id = ?',
                array($range_id),
                true
            );
            // Si la gamme n'est pas présente en base, on retourne un message
            if(isset($data) AND $data->corresponding_ranges_number < 1)
            {
                return 'La gamme à laquelle appartient cette série n\'existe pas.';
            }
        }

        // Si on arrive là, c'est que la gamme existe bien
        // Si on a renseigné un alias
        if($alias != null)
        {
            // On vérifie que la série n'est pas déjà présente en base
            $data = $this->query(
                'SELECT id FROM ' . $this->table . ' WHERE alias = ? AND id != ?',
                array($alias, $serie_id),
                true
            );
            // Si on a trouvé un id correspondant à l'alias
            if(isset($data->id) && $range_id != null)
            {
                // On cherche la gamme à laquelle cette série appartient
                $data_2 = $this->query('SELECT range_id FROM briiicks_ranges_series WHERE serie_id = ?',
                    array($data->id),
                    true
                );
                // Si la série appartient à la même gamme que la série que l'on veut enregistrer
                if(isset($data_2) && $data_2->range_id == $range_id)
                {
                    // On retourne un message
                    return 'Cette série existe déjà.';
                }
            }
        }

        // Si on est arrivé là, c'est que la série ne correspond à aucune série déjà présente en base
        // On modifie la série
        $this->query(
            'UPDATE ' . $this->table . '
            SET name = COALESCE(NULLIF(?, null), name),
                alias = COALESCE(NULLIF(?, null), alias),
                picture = COALESCE(NULLIF(?, null), picture)
            WHERE id = ?',
            array(
                $name,
                $alias,
                $picture,
                $serie_id
            )
        );
        // On modifie l'association de la série et de la gamme
        $this->query(
            'UPDATE briiicks_ranges_series
        SET range_id = COALESCE(NULLIF(?, null), range_id)
        WHERE serie_id = ?',
            array($range_id, $serie_id)
        );
        return true;
    }

    /**
     * Supprime une série
     * @param int $serie_id Id de la série
     */
    public function deleteSerie($serie_id)
    {
        // On vérifie si la série contient des sets
        $result = $this->query(
            'SELECT COUNT(*)
            AS sets_count
            FROM briiicks_series_sets
            WHERE serie_id = ?',
            array($serie_id),
            true
        );
        // Si c'est le cas, on retourne un message d'erreur
        if (isset($result) && $result->sets_count >= 1)
        {
            return 'Impossible de supprimer cette série car celle-ci contient un ou plusieurs sets.';
        }

        // On vérifie si la série contient des figurines
        $result = $this->query(
            'SELECT COUNT(*)
            AS minifigures_count
            FROM briiicks_series_minifigures
            WHERE serie_id = ?',
            array($serie_id),
            true
        );
        // Si c'est le cas, on retourne un message d'erreur
        if (isset($result) && $result->minifigures_count >= 1)
        {
            return 'Impossible de supprimer cette série car celle-ci contient une ou plusieurs figurines.';
        }

        // Si on est arrivé là, c'est qu'on peut supprimer
        // On supprime la série correspondant à l'id
        return $this->delete($serie_id);
    }

}