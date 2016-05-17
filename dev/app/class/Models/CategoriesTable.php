<?php
namespace App\Models;
use Core\Models\Models;

/**
 * Classe associée aux catégories
 */
class CategoriesTable extends Models {

	/**
	* @var string $table Nom de la table
	*/
	protected $table;

	/**
	* @var string $entity_class Nom de la classe associée aux entrées de la table
	*/
	protected $entity_class = 'App\Entities\CategoryEntity';

}