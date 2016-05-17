<?php
namespace App\Controllers;
use App;
use App\Pages\Page;

/**
 * Contrôleur de la page d'accueil
 */
class HomeController extends AppController {

    /**
     * Définie le chemin vers les vues et charge les modèles nécessaires
     */
    public function __construct() {
        parent::__construct();
        $this->loadModel('Ranges');
    }

    /**
     * Page : page d'accueil
     */
    public function index()
    {
        // On récupère la liste des gammes
        $ranges = $this->Ranges->getRangesWithSeries();
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Accueil',
            'class_body' => 'home',
            'scripts' => array('home.js')
        ));
        // Rendu du contenu
        $variables = compact('ranges');
        $content = $this->render('home/index.php', $variables);
        // Rendu de la page
        echo $page->render($content);
    }
}