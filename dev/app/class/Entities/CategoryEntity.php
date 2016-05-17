<?php
namespace App\Entities;
use Core\Entities\Entities;

/**
 * Classe associée à une catégorie
 */
class CategoryEntity extends Entities {

	/**
	 *	Génère le lien vers l'article
	 */
	public function getUrl() {
		return '/posts/category?id=' . $this->id;
	}

}