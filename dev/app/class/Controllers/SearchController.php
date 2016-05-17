<?php
namespace App\Controllers;
use App;
use App\Pages\Page;
use Core\Auth\DbAuth;

/**
 * Contrôleur de la page des résultats de recherche
 */
class SearchController extends AppController
{

    /**
     * Définie le chemin vers les vues et charge les modèles nécessaires
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Sets');
        $this->loadModel('Minifigures');
        $this->loadModel('Ranges');
        $this->loadModel('Series');
    }

    /**
     * Page : Résultats de recherche
     */
    public function index()
    {
        // Le formulaire de recherche a été posté
        if(isset($_POST['selected_range_alias'], $_POST['selected_serie_alias']))
        {
            if($_POST['selected_range_alias'] == 'none')
            {
                $selected_range_alias = '';
            }
            else
            {
                $selected_range_alias = $_POST['selected_range_alias'];
            }
            if($_POST['selected_serie_alias'] == 'none')
            {
                $selected_serie_alias = '';
            }
            else
            {
                $selected_serie_alias = $_POST['selected_serie_alias'];
            }
        }
        // Le formulaire de recherche n'a pas été posté
        else
        {
            $selected_range_alias = '';
            $selected_serie_alias = '';
        }

        if(isset($_POST['item_type']) && $_POST['item_type'] == 'set')
        {
            $item_type = 'set';
            // On récupère la liste des sets correspondant à la recherche
            $items_list = $this->Sets->getSetsList($selected_range_alias, $selected_serie_alias);
        }
        else
        {
            $item_type = 'minifigure';
            // On récupère la liste des figurines correspondant à la recherche
            $items_list = $this->Minifigures->getMinifiguresList($selected_range_alias, $selected_serie_alias);
        }
        // On récupère la liste des gammes
        $ranges = $this->Ranges->old_getRanges();
        // On récupère la liste des séries
        foreach ($ranges as $key => $range)
        {
            $ranges[$key]->series = $this->Series->getSeriesFromRange($range->id);
        }

        // Si l'utilisateur est connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        if($auth->logged('front')) {
            // On récupère également la collection de l'utilisateur
            $this->loadModel('Collections');
            $collection = $this->Collections->getCollection($_SESSION['front']['id']);
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Recherche',
            'class_body' => 'search',
            'scripts' => array('search.js')
        ));
        // Rendu du contenu
        $variables = compact(
            'selected_range_alias',
            'selected_serie_alias',
            'items_list',
            'item_type',
            'ranges',
            'collection'
        );
        $content = $this->render('search/index.php', $variables);
        // Rendu de la page
        echo $page->render($content);
    }

}