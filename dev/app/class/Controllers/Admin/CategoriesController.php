<?php
namespace App\Controllers\Admin;
use App\Controllers\Admin\AdminController;
use Core\Html\BootstrapForm;
use App\Pages\Admin\Page;

/**
 * Contrôleur des catégories 
 */
class CategoriesController extends AdminController {

	/**
	 * Définie le chemin vers les vues, vérifie que l'utilisateur est bien connecté, et charge les modèles nécessaires
	 */
	public function __construct() {
		parent::__construct();
		$this->loadModel('Categories');
	}

	/**
	 * Affiche la liste des catégories
	 */
	public function index() {
        // Traitement
		$categories = $this->Categories->all();
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Catégories',
        ));
        // Rendu du contenu
        $variables = compact('categories');
        $content = $this->render('admin/categories/index.php', $variables);
        // Rendu de la page
        echo $page->render($content);
	}

	/**
	 * Affiche le formulaire d'ajout d'une catégorie
	 */
	public function add() {
        // Traitement
		if(!empty($_POST)) {
			$result = $this->Categories->create([
				'title' => $_POST['title']
			]);
			if($result) { 
				return $this->index();
			}
		}
		$form = new BootstrapForm($_POST);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Nouvelle catégorie',
        ));
        // Rendu du contenu
        $variables = compact('form');
        $content = $this->render('admin/categories/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);
	}

	/**
	 * Affiche le formulaire d'édition d'une catégorie
	 */
	public function edit() {
        // Traitement
		if(!empty($_POST)) {
			$result = $this->Categories->update($_GET['id'], [
				'title' => $_POST['title']
			]);
			if($result) {
				return $this->index();
			}
		}
		$category = $this->Categories->find($_GET['id']);
		$form = new BootstrapForm($category);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Editer une catégorie',
        ));
        // Rendu du contenu
        $variables = compact('form');
        $content = $this->render('admin/categories/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);
	}

	/**
	 * Supprime une catégorie
	 */
	public function delete() {
		if(!empty($_POST)) {
			$result = $this->Categories->delete($_POST['id']);
			return $this->index();
		}
	}

}