<?php
namespace Core\Controllers;

/**
 * Gère les contrôleurs (MVC)
 */
class Controllers {

	/**
	 * @var string $viewPath Chemin vers le dossier contenant les vues
	 */
	protected $viewPath;

	/**
	 * @var string $template Nom du template dans lequel afficher les vues
	 */
	protected $template;
	
	/**
	 * Affiche une vue
	 * @param string $view Chemin vers la vue à afficher
	 * @param array $variables Variables à passer à la vue
	 */
	protected function render($view, $variables = array()) {
		ob_start();
		extract($variables);
		require($this->viewPath . $view);
		return ob_get_clean();
	}

	/**
	 * Renvoie une erreur de type 404 Not Found
	 */
	protected function notFound() {
		header('HTTP/1.0 404 Not Found');
		die('Page introuvable');
	}

	/**
	 * Renvoie une erreur de type 403 Forbidden
	 */
	protected function forbidden() {
		header('HTTP/1.0 403 Forbidden');
		die('Accès interdit');
	}

}