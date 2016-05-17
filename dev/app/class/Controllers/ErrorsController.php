<?php
namespace App\Controllers;
use App;
use App\Pages\Page;

/**
 * Contrôleur des pages d'erreur personnalisées
 */
class ErrorsController extends AppController {

    /**
     * Affiche une page d'erreur
     * @param $status_code
     */
    public function error($status_code)
    {
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Oups...',
            'class_body' => 'error'
        ));
        // Rendu du contenu
        $content = $this->render('errors/404.php');
        // Rendu de la page
        echo $page->render($content);
    }

}