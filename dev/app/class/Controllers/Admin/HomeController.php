<?php
namespace App\Controllers\Admin;
use App\Pages\Admin\Page;

/**
 * Contrôleur de la page d'accueil
 */
class HomeController extends AdminController {

    /**
     * Charge les modèles nécessaires
     */
    public function __construct() {
        parent::__construct();
        $this->loadModel('Minifigures');
        $this->loadModel('Sets');
        $this->loadModel('Members');
    }

    /**
     * Affiche la page d'accueil
     */
    public function index() {
        // Traitement
        $minifigures_number = $this->Minifigures->count();
        $sets_number = $this->Sets->count();
        $members_number = $this->Members->count();
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Accueil',
            'class_body' => 'home'
        ));
        // Rendu du contenu
        $variables = compact('minifigures_number', 'sets_number', 'members_number');
        $content = $this->render('admin/home/index.php', $variables);
        // Rendu de la page
        echo $page->render($content);
    }

}