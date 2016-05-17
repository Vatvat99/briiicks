<?php
namespace App\Controllers;
use Core\Controllers\Controllers;
use App;

/**
 * Gère les contrôleurs de l'application (MVC)
 */
class AppController extends Controllers {

	/**
	 * Nom du template utilisé
	 */
	protected $template = 'default';

	/**
	 * Définie le chemin vers les vues
	 */
	public function __construct() {
		$this->viewPath = ROOT . '/app/views/';
	}

	/**
	 * Charge un modèle
     * @param string $model_name Nom du modèle à charger
     * @param string $app_section Section de l'application à laquelle appartient le modèle
	 */
	protected function loadModel($model_name, $app_section = null) {
		$this->$model_name = App::getInstance()->getModel($model_name, $app_section);
	}

}