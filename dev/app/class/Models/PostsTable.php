<?php
namespace App\Models;
use Core\Models\Models;

/**
 * Classe associée aux articles
 */
class PostsTable extends Models {

	/**
	* @var string $table Nom de la table
	*/
	protected $table;

	/**
	* @var string $entity_class Nom de la classe associée aux entrées de la table
	*/
	protected $entity_class = 'App\Entities\PostEntity';

	/**
	 *	Récupère un seul article
	 * @param int $id Id de l'article à récupérer
	 * @return \App\Entities\PostEntity
	 */
	public function find($id) {
		return $this->query('
			SELECT posts.id, posts.title, posts.content, categories.title as categorie, category_id 
			FROM ' . $this->table . '
			LEFT JOIN categories  
			ON category_id = categories.id
			WHERE posts.id = ?
			',[$id], true
		);
	}

	/**
	 *	Récupère les derniers articles
	 * @return array
	 */
	public function last() {
		return $this->query(
			'SELECT posts.id, posts.title, posts.content, categories.title AS category 
			FROM ' . $this->table . '
			LEFT JOIN categories 
			ON posts.category_id = categories.id 
			ORDER BY posts.date DESC'
		);
	}

	/**
	 *	Récupère les derniers articles d'une catégorie
	 * @param int $category_id Id de la catégorie
	 * @return array
	 */
	public function lastByCategory($category_id) {
		return $this->query(
			'SELECT posts.id, posts.title, posts.content, categories.title AS category 
			FROM ' . $this->table . ' 
			LEFT JOIN categories 
			ON posts.category_id = categories.id 
			WHERE posts.category_id = ? 
			ORDER BY posts.date DESC', 
			[$category_id]
		);
	}

}