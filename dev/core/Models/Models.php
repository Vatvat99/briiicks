<?php
namespace Core\Models;
use Core\Database\Database;

/**
 * Classe associée à l'ensemble des tables
 */
class Models {

	/**
	* @var string $table Nom de la table
	*/
	protected $table;

	/**
	* @var string $entity_class Nom de la classe associée aux entrées de la table
	*/
	protected $entity_class;

	/**
	* @var object $db Instance de la bdd
	*/
	protected $db;

	/**
	 * Stocke le nom de la table et l'instance de la bdd
     * @param object $db Instance de la bdd
     * @param string $table_prefix Préfixe de la table
	 */
	public function __construct(Database $db, $table_prefix) {
		$this->db = $db;
		if(is_null($this->table)) {
			$pieces = explode('\\', get_class($this));
			$this->table = $table_prefix . strtolower(str_replace('Model', '', end($pieces)));
		}
	}

	/**
	 *	Récupère toutes les entrées
	 */
	public function all() {
		return $this->query('SELECT * FROM ' . $this->table);
	}

	/**
	 * Récupère une partie des entrées
	 * @param int $first_entry Position de la première entrée à récupérer
	 * @param int $entries_number Nombre d'entrées à récupérer
	 */
	public function some($first_entry, $entries_number) {
		return $this->query('SELECT * FROM ' . $this->table . ' LIMIT ' . $first_entry . ', ' . $entries_number);
	}

    /**
     *	Compte toutes les entrées
     */
    public function count() {
        return $this->query('SELECT COUNT(*) FROM ' . $this->table);
    }

	/**
	 * Extrait toutes les entrées sous la forme d'un tableau ayant comme clé et valeur les deux champs que l'on a spécifié
	 * @param string $key Champ de la table à retourner comme clé du tableau
	 * @param string $value Champ de la table à retourner comme valeur du tableau
	 */
	public function extract($key, $value) {
		$records = $this->all();
		$return = array();
		foreach ($records as $v) {
			$return[$v->$key] = $v->$value;
		}
		return $return;
	}

	/**
	 *	Récupère une seule entrée
	 * @param int $id Id de l'entrée à récupérer
	 */
	public function find($id) {
		return $this->query('
			SELECT * 
			FROM ' . $this->table . '
			WHERE id = ?
			',array($id), true
		);
	}

	/**
	 *	Insère une nouvelle entrée
	 * @param array $fields Champs à modifier
	 */
	public function create($fields) {
		$sql_parts = array();
		$attributes = array();
		foreach($fields as $key => $value) {
			$sql_parts[] = $key . ' = ?';
			$attributes[] = $value;
		}
        $set_part = implode(', ', $sql_parts);
		return $this->query(
			'INSERT INTO ' . $this->table . ' SET ' . $set_part, $attributes, true
		);
	}

	/**
	 *	Modifie une entrée
	 * @param int $id Id de l'entrée à modifier
	 * @param array $fields Champs à modifier
	 */
	public function update($id, $fields) {
		$sql_parts = array();
		$attributes = array();
		foreach($fields as $key => $value) {
			$sql_parts[] = $key . '= ?';
			$attributes[] = $value;
		}
		$attributes[] = $id;
		$set_part = implode(', ', $sql_parts);
		return $this->query(
			'UPDATE ' . $this->table . ' 
			SET ' . $set_part . '
			WHERE id = ?
			', $attributes
		);
	}

	/**
	 * Modifie une ou plusieurs entrées correspondants à un ou plusieurs champs donnés
	 * @param array $fields_to_find Champs permettant de trouver les entrées à modifier
	 * @param array $fields_to_update Champs à modifier
	 */
	public function updateBy($fields_to_find, $fields_to_update)
	{
		// Préparation de la partie "Set" de la requête
		$sql_parts_to_update = array();
		$attributes = array();
		foreach($fields_to_update as $key => $value) {
			$sql_parts_to_update[] = $key . '= ?';
			$attributes[] = $value;
		}
		$set_part = implode(', ', $sql_parts_to_update);
		// Préparation de la partie "Where" de la requête
		$sql_parts_to_find = array();
		foreach($fields_to_find as $key => $value) {
			$sql_parts_to_find[] = $key . '= ?';
			$attributes[] = $value;
		}
		$where_part = implode(' AND ', $sql_parts_to_find);
		// Exécution de la requête
		return $this->query(
			'UPDATE ' . $this->table . '
			SET ' . $set_part . '
			WHERE ' . $where_part, $attributes
		);
	}

	/**
	 *	Supprime une entrée
	 * @param int $id Id de l'entrée à supprimer
	 */
	public function delete($id) {
		return $this->query(
			'DELETE FROM ' . $this->table . ' 
			WHERE id = ?
			', array($id));
	}

	/**
	 * Effectue une requête
	 * @param string $statement Requête à effectuer
	 * @param array $attributes Valeurs des attributs dans le cas d'une requête préparée
	 * @param bool $one Renvoie un ou plusieurs résultats
	 */
	public function query($statement, $attributes = null, $one = false) {
		if($attributes) {
            if(strpos($statement, 'SELECT COUNT') === 0) {
                return $this->db->prepare(
                    $statement,
                    $attributes,
                    null,
                    $one
                );
            } else {
                return $this->db->prepare(
                    $statement,
                    $attributes,
                    $this->entity_class,
                    $one
                );
            }
        } else {
            if(strpos($statement, 'SELECT COUNT') === 0) {
                return $this->db->query(
                    $statement,
                    null,
                    true
                );
            } else {
                return $this->db->query(
                    $statement,
                    $this->entity_class,
                    $one
                );
            }
		}
	}

}