<?php
namespace App\Controllers\Admin;
use App\Pages\Admin\Page;
use App;

/**
 * Contrôleur des pages annonces
 */
class OffersController extends AdminController
{

    /**
     * Charge les modèles nécessaires
     */
    public function __construct() {
        parent::__construct();
        $this->loadModel('Offers');
    }

    /**
     * Page listant les annonces
     */
    public function listing()
    {
        $offers_per_page = 32;
        // On récupère le nombre de figurines
        $offers_number = $this->Offers->count();
        // On calcule le nombre de page
        $pages_number = ceil($offers_number / $offers_per_page);
        // On récupère la page courante
        if(isset($_GET['p'])) {
            $current_page = intval($_GET['p']);
            if($current_page > $pages_number) {
                $current_page = 1;
            }
        } else {
            $current_page = 1;
        }
        // On calcule la première entrée à récupérer
        $first_entry = ($current_page - 1) * $offers_per_page;
        // On récupère la liste des annonces
        $offers_list = $this->Offers->some($first_entry, $offers_per_page);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Annonces',
            'class_body' => 'offers'
        ));
        // Rendu du contenu
        $variables = compact(
            'offers_list',
            'offers_number',
            'current_page',
            'pages_number'
        );
        $content = $this->render('admin/offers/listing.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'éditer une annonce
     */
    public function edit() {
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Modifier l\'annonce',
            'class_body' => 'offers'
        ));
        // Rendu du contenu
        $content = $this->render('admin/offers/edit.php');
        // Rendu de la page
        echo $page->render($content);
    }

}