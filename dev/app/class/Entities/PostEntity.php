<?php
namespace App\Entities;
use Core\Entities\Entities;

/**
 * Classe associée à un article
 */
class PostEntity extends Entities {

	/**
	 *	Génère le lien vers l'article
	 */
	public function getUrl() {
		return '/posts/single?id=' . $this->id;
	}

	/**
	 *	Génère le résumé du contenu d'un article
	 */
	public function getExcerpt() {
		$html = '<p>' . substr($this->content, 0, 100) . '...</p>';
		$html.= '<p><a href="' . $this->getUrl() . '">Voir la suite</a></p>';
		return $html;
	}

}