<?php
namespace App\Controllers\Admin;
use App;
use Core\Html\BootstrapForm;
use App\Pages\Admin\Page;

/**
 * Contrôleur des articles
 */
class PostsController extends AdminController {

	/**
	 * Définie le chemin vers les vues, vérifie que l'utilisateur est bien connecté, et charge les modèles nécessaires
	 */
	public function __construct() {
		parent::__construct();
		$this->loadModel('Posts');
	}

	/**
	 * Affiche la liste des articles
	 */
	public function index() {
        // Traitement
		$posts = $this->Posts->all();
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Accueil',
            'class_body' => 'home',
        ));
        // Rendu du contenu
        $variables = compact('posts');
        $content = $this->render('admin/posts/index.php', $variables);
        // Rendu de la page
        echo $page->render($content);
	}

	/**
	 * Affiche le formulaire d'ajout d'un article
	 */
	public function add() {
        // Traitement
		if(!empty($_POST)) {
			$result = $this->Posts->create([
				'title' => $_POST['title'], 
				'content' => $_POST['content'], 
				'category_id' => $_POST['category_id']
			]);
			if($result) { 
				return $this->index();
			}
		}
		$this->loadModel('Categories');
		$categories = $this->Categories->extract('id', 'title');
		$form = new BootstrapForm($_POST);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Nouvel article',
        ));
        // Rendu du contenu
        $variables = compact('form', 'categories');
        $content = $this->render('admin/posts/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);
	}

	/**
	 * Affiche le formulaire d'édition d'un article
	 */
	public function edit() {
        // Traitement
		if(!empty($_POST)) {
			$result = $this->Posts->update($_GET['id'], [
				'title' => $_POST['title'], 
				'content' => $_POST['content'], 
				'category_id' => $_POST['category_id']
			]);
			if($result) {
				return $this->index();
			}
		}
		$post = $this->Posts->find($_GET['id']);
		$this->loadModel('Categories');
		$categories = $this->Categories->extract('id', 'title');
		$form = new BootstrapForm($post);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Editer l\'article',
        ));
        // Rendu du contenu
        $variables = compact('form', 'categories');
        $content = $this->render('admin/posts/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);
	}

	/**
	 * Supprime un article
	 */
	public function delete() {
		if(!empty($_POST)) {
			$result = $this->Posts->delete($_POST['id']);
			return $this->index();
		}
	}

}