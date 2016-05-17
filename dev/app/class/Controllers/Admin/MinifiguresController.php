<?php
namespace App\Controllers\Admin;
use App\Pages\Admin\Page;
use App;
use Core\Utilities\Utilities;

/**
 * Contrôleur des pages figurines
 */
class MinifiguresController extends AdminController {

    /**
     * Charge les modèles nécessaires
     */
    public function __construct() {
        parent::__construct();
        $this->loadModel('Minifigures');
        $this->loadModel('Ranges');
        $this->loadModel('Series');
    }

    /**
     * Page listant les figurines
     */
    public function listing()
    {
        $minifigures_per_page = 32;
        // On récupère le nombre de figurines
        $minifigures_number = $this->Minifigures->count();
        // On calcule le nombre de page
        $pages_number = ceil($minifigures_number / $minifigures_per_page);
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
        $first_entry = ($current_page - 1) * $minifigures_per_page;
        // On récupère la liste des minifigures
        $minifigures_list = $this->Minifigures->some($first_entry, $minifigures_per_page);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Figurines',
            'class_body' => 'minifigures',
            'scripts' => array('admin/minifigures.js')
        ));
        // Rendu du contenu
        $variables = compact(
            'minifigures_list',
            'minifigures_number',
            'current_page',
            'pages_number'
        );
        $content = $this->render('admin/minifigures/listing.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'ajouter une figurine
     */
    public function add()
    {
        $errors = array();
        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }
        //Le formulaire a été posté
        if (isset($_POST['name'], $_POST['range'], $_POST['release_year'], $_FILES['picture'])) {
            // On vérifie s'il y a des erreurs
            if ($_POST['name'] == null) // Le champ nom n'a pas été rempli
            {
                $errors['name'] = 'Ce champ est obligatoire.';
            }
            if ($_POST['range'] == null) // Le champ gamme n'a pas été rempli
            {
                $errors['range'] = 'Ce champ est obligatoire.';
            }
            if (!isset($_POST['serie']) || $_POST['serie'] == null) // Le champ série n'a pas été rempli
            {
                $errors['serie'] = 'Ce champ est obligatoire.';
            }
            if ($_POST['release_year'] != '' && !preg_match('#^[0-9]{4}$#', $_POST['release_year'])) // Le champ année de sortie ne contient pas une année
            {
                $errors['release_year'] = 'L\'année de sortie doit être un nombre à 4 chiffres.';
            }
            if ($_FILES['picture']['size'] != 0) // Si un visuel est envoyé
            {
                if ($_FILES['picture']['error'] > 0) // Erreur d'upload du visuel
                {
                    $errors['picture'] = 'Erreur lors du transfert.';
                } elseif ($_FILES['picture']['size'] > App::getInstance()->getConfig()->get('max_file_size')) // Si le fichier est trop gros
                {
                    $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
                } else {
                    $extension = strtolower(substr(strrchr($_FILES['picture']['name'], '.'), 1));
                    if (!in_array($extension, App::getInstance()->getConfig()->get('img_authorized_extensions'))) {
                        $errors['picture'] = 'Type de fichier non-autorisé. (seulement jpg, gif, png)';
                    }
                }
            }

            // Le formulaire a été correctement rempli
            if (empty($errors)) {
                // On enregistre la figurine
                $minifigure_alias = Utilities::aliasFormat($_POST['name']);
                $minifigure_id = $this->Minifigures->add($_POST['name'], $minifigure_alias, $_POST['serie'], $_POST['release_year']);
                // Si l'enregistrement de la figurine à généré une erreur, on l'affiche
                if (!is_int($minifigure_id)) {
                    $_SESSION['error'] = $minifigure_id;
                } // L'enregistrement s'est bien déroulé
                else {
                    // Si un visuel a été uploadé
                    if ($_FILES['picture']['size'] != 0 && $_FILES['picture']['error'] == 0) {
                        // On le redimensionne et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 57, 57, '/assets/img/minifigures/57x57/', $minifigure_id . '-' . $minifigure_alias);
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 209, 238, '/assets/img/minifigures/209x238/', $minifigure_id . '-' . $minifigure_alias);

                        // On update la figurine en bdd avec le nom du visuel
                        $editedMinifigure = $this->Minifigures->edit($minifigure_id, null, null, $minifigure_id . '-' . $minifigure_alias . '.' . $extension, null, null);

                        if (!$editedMinifigure) {
                            $_SESSION['error'] = 'Erreur lors de l\'enregistrement du logo';
                        }
                    }

                    // On redirige vers la liste des figurines
                    $_SESSION['success'] = 'La nouvelle figurine a bien été créée';
                    header('Location: /admin/minifigures/listing');
                    die();
                }
            }
            // On récupère la liste des gammes et des séries
            $ranges_list = $this->Ranges->all();
            $range_id = ($_POST['range'] != null) ? $_POST['range'] : $ranges_list[0]->id;
            $series_list = $this->Series->getSeriesFromRange($range_id);
        }
        // Le formulaire n'a pas été posté
        else {
            // On récupère la liste des gammes et des séries
            $ranges_list = $this->Ranges->all();
            $series_list = $this->Series->getSeriesFromRange($ranges_list[0]->id);
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Nouvelle figurine',
            'class_body' => 'minifigures-add',
            'scripts' => array('admin/minifigures.js')
        ));
        // Rendu du contenu
        $variables = compact('errors', 'ranges_list', 'series_list');
        $content = $this->render('admin/minifigures/add.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'éditer une figurine
     */
    public function edit() {

        $errors = array();
        // Si l'id de la figurine n'existe pas, on affiche la liste des figurines
        if(!isset($_GET['id']))
        {
            header('Location: /admin/minifigures/listing');
            die();
        }
        // Si on a un id figurine
        else
        {
            // On recherche la figurine correspondant à l'id
            $minifigure = $this->Minifigures->getMinifigure($_GET['id']);
            // Si aucune figurine n'a été trouvée on affiche la liste des figurines
            if(!$minifigure)
            {
                header('Location: /admin/minifigures/listing');
                die();
            }
        }

        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }

        //Le formulaire a été posté
        if(isset($_POST['id'], $_POST['name'], $_POST['range'], $_POST['release_year'], $_FILES['picture']))
        {
            // On vérifie les erreurs
            if($_POST['name'] == null) // Le champ nom n'a pas été rempli
            {
                $errors['name'] = 'Ce champ est obligatoire.';
            }

            if($_POST['range'] == null) // Le champ gamme n'a pas été rempli
            {
                $errors['range'] = 'Ce champ est obligatoire.';
            }

            if(!isset($_POST['serie']) || $_POST['serie'] == null) // Le champ série n'a pas été rempli
            {
                $errors['serie'] = 'Ce champ est obligatoire.';
            }

            if(!preg_match('#^[0-9]{4}$#', $_POST['release_year'])) // Le champ année de sortie ne contient pas une année
            {
                $errors['release_year'] = 'L\'année de sortie doit être un nombre à 4 chiffres.';
            }

            if ($_FILES['picture']['size'] != 0) // Si un visuel est envoyé
            {
                if($_FILES['picture']['error'] > 0) // Erreur d'upload du visuel
                {
                    $errors['picture'] = 'Erreur lors du transfert.';
                }
                elseif ($_FILES['picture']['size'] > App::getInstance()->getConfig()->get('max_file_size')) // Si le fichier est trop gros
                {
                    $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
                }
                else {
                    $extension = strtolower(substr(strrchr($_FILES['picture']['name'], '.'), 1));
                    if (!in_array($extension, App::getInstance()->getConfig()->get('img_authorized_extensions')))
                    {
                        $errors['picture'] = 'Type de fichier non-autorisé. (seulement jpg, gif, png)';
                    }
                }
            }

            // Le formulaire a été correctement rempli
            if(empty($errors))
            {
                $minifigure_alias = Utilities::aliasFormat($_POST['name']);
                // On édite la figurine
                $edited_minifigure = $this->Minifigures->edit($_POST['id'], $_POST['name'], $minifigure_alias,  null, $_POST['release_year'], $_POST['serie']);

                if($edited_minifigure === true)
                {
                    // Si une nouvelle photo a été uploadée
                    if ($_FILES['picture']['size'] != 0 && $_FILES['picture']['error'] == 0)
                    {
                        // Si la figurine avait déjà une photo
                        if($minifigure->picture != '')
                        {
                            // On la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/minifigures/' . $minifigure->picture);
                        }

                        // On redimensionne la nouvelle photo et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 57, 57, '/assets/img/minifigures/57x57/', $_POST['id'] . '-' . $minifigure_alias );
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 209, 238, '/assets/img/minifigures/209x238/', $_POST['id'] . '-' . $minifigure_alias );

                        // On update la figurine en bdd avec le nom de la photo
                        $this->Minifigures->edit($_POST['id'], null, null, $_POST['id'] . '-' . $minifigure_alias . '.' . $extension, null, null);
                    }
                    // Aucune nouvelle photo n'a été uploadée
                    else
                    {
                        // Si on a choisi de supprimer la photo
                        if(isset($_POST['delete_picture']))
                        {
                            // Et ben on la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/minifigures/57x57/' . $minifigure->picture);
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/minifigures/209x238/' . $minifigure->picture);
                            // Et on update la figurine en bdd pour vider le nom de la photo
                            $this->Minifigures->edit($_POST['id'], null, null, '', null, null);
                        }
                        // Si le nom de la figurine a changé et que cette figurine a une photo
                        elseif($minifigure->name != $_POST['name'] && $minifigure->picture != '')
                        {

                            $extension = strtolower(substr(strrchr($minifigure->picture, '.'), 1));
                            // On renomme la photo
                            rename ($_SERVER['DOCUMENT_ROOT'] . '/assets/img/minifigures/57x57/' . $minifigure->picture, $_SERVER['DOCUMENT_ROOT'] . '/assets/img/minifigures/57x57/' . $_POST['id'] . '-' . $minifigure_alias . '.' . $extension);
                            rename ($_SERVER['DOCUMENT_ROOT'] . '/assets/img/minifigures/209x238/' . $minifigure->picture, $_SERVER['DOCUMENT_ROOT'] . '/assets/img/minifigures/209x238/' . $_POST['id'] . '-' . $minifigure_alias . '.' . $extension);
                            // On update la figurine en bdd avec le nom de la photo
                            $this->Minifigures->edit($_POST['id'], null, null, $_POST['id'] . '-' . $minifigure_alias . '.' . $extension, null, null);
                        }
                    }

                    // On redirige vers la liste des figurines
                    $_SESSION['success'] = 'La figurine a bien été modifiée';
                    header('Location: /admin/minifigures/listing');
                    die();
                }
                else
                {
                    $_SESSION['error'] = $edited_minifigure;
                }
            }
            // On récupère la liste des gammes et des séries
            $ranges_list = $this->Ranges->all();
            $range_id = (isset($_POST['range'])) ? $_POST['range']: $minifigure->range_id;
            $series_list = $this->Series->getSeriesFromRange($range_id);
        }
        //Le formulaire n'a pas été posté
        else {
            // On récupère la liste des gammes et des séries
            $ranges_list = $this->Ranges->all();
            $series_list = $this->Series->getSeriesFromRange($minifigure->range_id);
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Modifier la figurine',
            'class_body' => 'minifigures-edit',
            'scripts' => array('admin/minifigures.js')
        ));
        // Rendu du contenu
        $variables = compact('errors', 'minifigure', 'ranges_list', 'series_list');
        $content = $this->render('admin/minifigures/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Action permettant de supprimer une figurine
     */
    public function delete() {

        // Si l'id de la figurine n'existe pas, on affiche la liste des figurines
        if(!isset($_GET['id']))
        {
            header('Location: /admin/minifigures/listing');
            die();
        }
        // Si on a bien un id de figurine
        else
        {
            // On recherche la figurine correspondant à l'id
            $minifigure = $this->Minifigures->getMinifigure($_GET['id']);
            // Si aucune figurine n'a été trouvée on affiche la liste des figurines
            if(!$minifigure)
            {
                header('Location: /admin/minifigures/listing');
                die();
            }
            // Si on a trouvé une figurine en bdd
            else
            {
                // On supprime la figurine correspondant à l'id
                $deleted_minifigure = $this->Minifigures->delete($_GET['id']);
                // Si la suppression a été effectuée
                if($deleted_minifigure === true)
                {
                    // Si un visuel existe
                    if ($minifigure->picture != '')
                    {
                        // On le supprime
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/minifigures/57x57/' . $minifigure->picture);
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/minifigures/209x238/' . $minifigure->picture);
                    }

                    // On redirige vers la liste des figurines avec le message de confirmation
                    $_SESSION['success'] = 'La figurine a bien été supprimée';
                    header('Location: /admin/minifigures/listing');
                    die();
                }
                // Si la suppression n'a pas été effectuée
                else
                {
                    // On redirige vers la liste des figurines avec le message d'erreur
                    $_SESSION['error'] = $deleted_minifigure;
                    header('Location: /admin/minifigures/listing');
                    die();
                }
            }
        }

    }

    /**
     * Ajax : Retourne l'ensemble des figurines comprises dans une série
     */
    public function getMinifiguresFromSerie()
    {
        $this->loadModel('Minifigures');
        if (isset($_GET['serie_id'])) {
            $minifigures_list = $this->Minifigures->getMinifiguresFromSerie($_GET['serie_id']);
            echo json_encode($minifigures_list);
        }
    }

}