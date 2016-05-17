<?php
namespace App\Controllers\Admin;
use App;
use Core\Auth\DbAuth;
use App\Controllers\AppController;

/**
 * Gère les contrôleurs de l'administration (MVC)
 */
class AdminController extends AppController {

	/**
	 * Vérifie que l'utilisateur est bien connecté à l'administration
	 */
	public function __construct() {
		parent::__construct();
		$app = App::getInstance();
		$auth = new DbAuth($app->getDb());
        // Si on tente d'afficher une autre page que celle de login sans être connecté
		if(!$auth->logged('back') && $_GET['page'] != 'admin/users/login') {
            header('Location: /admin/users/login');
		}
        // Si on tente d'afficher la page de login alors qu'on est connecté
        if($auth->logged('back') && $_GET['page'] == 'admin/users/login') {
            header('Location: /admin/home/index');
        }
	}

}