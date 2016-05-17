<?php
namespace App\Controllers\Admin;
use App\Pages\Admin\Page;
use App;
use Core\Utilities\Utilities;

/**
 * Contrôleur des pages sets
 */
class SetsController extends AdminController {

    /**
     * Charge les modèles nécessaires
     */
    public function __construct() {
        parent::__construct();
        $this->loadModel('Sets');
        $this->loadModel('Ranges');
        $this->loadModel('Series');
        $this->loadModel('Minifigures');
    }

    /**
     * Page listant les sets
     */
    public function listing()
    {
        $sets_per_page = 32;
        // On récupère le nombre de sets
        $sets_number = $this->Sets->count();
        // On calcule le nombre de page
        $pages_number = ceil($sets_number / $sets_per_page);
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
        $first_entry = ($current_page - 1) * $sets_per_page;
        // On récupère la liste des sets
        $sets_list = $this->Sets->some($first_entry, $sets_per_page);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Sets',
            'class_body' => 'sets',
            'scripts' => array('admin/sets.js')
        ));
        // Rendu du contenu
        $variables = compact(
            'sets_list',
            'sets_number',
            'current_page',
            'pages_number'
        );
        $content = $this->render('admin/sets/listing.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'ajouter un set
     */
    public function add()
    {
        $errors = array();
        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }
        //Le formulaire a été posté
        if (isset($_POST['number'], $_POST['name'], $_POST['range'], $_POST['release_year'], $_POST['price'], $_FILES['picture'])) {
            if($_POST['number'] == null) // Le champ numéro n'a pas été rempli
            {
                $errors['number'] = 'Ce champ est obligatoire.';
            }
            elseif(!preg_match('#^\d+$#', $_POST['number'])) // Le champ numéro ne contient pas un numéro
            {
                $errors['number'] = 'Le numéro ne doit contenir que des chiffres.';
            }

            if($_POST['name'] == null) // Le champ nom n'a pas été rempli
            {
                $errors['name'] = 'Ce champ est obligatoire.';
            }

            if(!preg_match('#^[0-9]{4}$#', $_POST['release_year'])) // Le champ année de sortie ne contient pas une année
            {
                $errors['release_year'] = 'L\'année de sortie doit être un nombre à 4 chiffres.';
            }

            if(!preg_match('#^[0-9]{1,3},[0-9]{2}$#', $_POST['price'])) // Le champ prix n'est pas au bon format
            {
                $errors['price'] = 'Le prix n\'est pas au bon format.';
            }

            if($_POST['range'] == null) // Le champ gamme n'a pas été rempli
            {
                $errors['range'] = 'Ce champ est obligatoire.';
            }

            if(!isset($_POST['serie']) || $_POST['serie'] == null) // Le champ série n'a pas été rempli
            {
                $errors['serie'] = 'Ce champ est obligatoire.';
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
                // On enregistre le set
                $set_alias = Utilities::aliasFormat($_POST['name']);
                $price = str_replace(',', '.', $_POST['price']);
                $minifigures_id = (isset($_POST['minifigures'])) ? $_POST['minifigures'] : null;
                $set_id = $this->Sets->add($_POST['number'], $_POST['name'], $set_alias, '', $_POST['release_year'], $price, $_POST['serie'], $minifigures_id);
                // Si l'enregistrement du set à généré une erreur, on l'affiche
                if(!is_int($set_id)) {
                    $_SESSION['error'] = $set_id;
                }
                // L'enregistrement s'est bien déroulé
                else
                {
                    // Si un visuel a été uploadé
                    if ($_FILES['picture']['size'] != 0 && $_FILES['picture']['error'] == 0)
                    {
                        // On le redimensionne et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 57, 57, '/assets/img/sets/57x57/', $set_id . '-' . $set_alias);
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 209, 238, '/assets/img/sets/209x238/', $set_id . '-' . $set_alias);

                        // On update le set en bdd avec le nom du visuel
                        $editedSet = $this->Sets->edit($set_id, null, null, null, $set_id . '-' . $set_alias . '.' . $extension, null, null, null);

                        if(!$editedSet) {
                            $_SESSION['error'] = 'Erreur lors de l\'enregistrement du visuel';
                        }
                    }

                    // On redirige vers la liste des sets
                    $_SESSION['success'] = 'Le nouveau set a bien été créé';

                    header('Location: /admin/sets/listing');
                    die();
                }
            }
        }

        // On récupère la liste des gammes, des séries, et des figurines
        $ranges_list = $this->Ranges->all();
        $series_list = '';
        $minifigures_list = '';
        if(isset($ranges_list) && count($ranges_list) > 0)
        {
            $series_list = $this->Series->getSeriesFromRange($ranges_list[0]->id);
        }
        if(isset($series_list) && count($series_list) > 0)
        {
            $minifigures_list = $this->Minifigures->getMinifiguresFromSerie($series_list[0]->id);
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Nouveau set',
            'class_body' => 'sets-add',
            'scripts' => array('admin/sets.js')
        ));
        // Rendu du contenu
        $variables = compact('errors', 'ranges_list', 'series_list', 'minifigures_list');
        $content = $this->render('admin/sets/add.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page : Permet d'éditer un set
     */
    public function edit() {

        $errors = array();
        // Si l'id du set n'existe pas, on affiche la liste des sets
        if(!isset($_GET['id']))
        {
            header('Location: /admin/sets/listing');
            die();
        }
        // Si on a un id set
        else
        {
            // On recherche le set correspondant à l'id
            $set = $this->Sets->getSet($_GET['id']);
            // Si aucun set n'a été trouvé on affiche la liste des sets
            if(!$set)
            {
                header('Location: /admin/sets/listing');
                die();
            }
        }

        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }

        //Le formulaire a été posté
        if(isset($_POST['id'], $_POST['number'], $_POST['name'], $_POST['range'], $_POST['release_year'], $_POST['price'], $_FILES['picture']))
        {
            // On vérifie les erreurs
            if($_POST['number'] == null) // Le champ numéro n'a pas été rempli
            {
                $errors['number'] = 'Ce champ est obligatoire.';
            }
            elseif(!preg_match('#^\d+$#', $_POST['number'])) // Le champ numéro ne contient pas un numéro
            {
                $errors['number'] = 'Le numéro ne doit contenir que des chiffres.';
            }

            if($_POST['name'] == null) // Le champ nom n'a pas été rempli
            {
                $errors['name'] = 'Ce champ est obligatoire.';
            }

            if(!preg_match('#^[0-9]{4}$#', $_POST['release_year'])) // Le champ année de sortie ne contient pas une année
            {
                $errors['release_year'] = 'L\'année de sortie doit être un nombre à 4 chiffres.';
            }
            if(!preg_match('#^[0-9]{1,3},[0-9]{2}$#', $_POST['price'])) // Le champ prix n'est pas au bon format
            {
                $errors['price'] = 'Le prix n\'est pas au bon format.';
            }

            if($_POST['range'] == null) // Le champ gamme n'a pas été rempli
            {
                $errors['range'] = 'Ce champ est obligatoire.';
            }

            if(!isset($_POST['serie']) || $_POST['serie'] == null) // Le champ série n'a pas été rempli
            {
                $errors['serie'] = 'Ce champ est obligatoire.';
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
            if(empty($errors)) {
                $set_alias = Utilities::aliasFormat($_POST['name']);
                $price = str_replace(',', '.', $_POST['price']);
                // On édite le set
                $edited_set = $this->Sets->edit($_POST['id'], $_POST['number'], $_POST['name'], $set_alias, null, $_POST['release_year'], $price, $_POST['serie'], $_POST['minifigures']);

                if ($edited_set === true) {
                    // Si une nouvelle photo a été uploadée
                    if ($_FILES['picture']['size'] != 0 && $_FILES['picture']['error'] == 0) {
                        // Si le set avait déjà une photo
                        if ($set->picture != '') {
                            // On la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/sets/' . $set->picture);
                        }

                        // On redimensionne la nouvelle photo et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 57, 57, '/assets/img/sets/57x57/', $_POST['id'] . '-' . $set_alias);
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 209, 238, '/assets/img/sets/209x238/', $_POST['id'] . '-' . $set_alias);

                        // On update le set en bdd avec le nom de la photo
                        $this->Sets->edit($_POST['id'], null, null, null, $_POST['id'] . '-' . $set_alias . '.' . $extension, null, null, null, null);
                    } // Aucune nouvelle photo n'a été uploadée
                    else {
                        // Si on a choisi de supprimer la photo
                        if (isset($_POST['delete_picture'])) {
                            // Et ben on la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/sets/57x57/' . $set->picture);
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/sets/209x238/' . $set->picture);
                            // Et on update le set en bdd pour vider le nom de la photo
                            $this->Sets->edit($_POST['id'], null, null, null, '', null, null, null, null);
                        } // Si le nom du set a changé et que ce set a une photo
                        elseif ($set->name != $_POST['name'] && $set->picture != '') {

                            $extension = strtolower(substr(strrchr($set->picture, '.'), 1));
                            // On renomme la photo
                            rename($_SERVER['DOCUMENT_ROOT'] . '/assets/img/sets/57x57/' . $set->picture, $_SERVER['DOCUMENT_ROOT'] . '/assets/img/sets/57x57/' . $_POST['id'] . '-' . $set_alias . '.' . $extension);
                            rename($_SERVER['DOCUMENT_ROOT'] . '/assets/img/sets/209x238/' . $set->picture, $_SERVER['DOCUMENT_ROOT'] . '/assets/img/sets/209x238/' . $_POST['id'] . '-' . $set_alias . '.' . $extension);
                            // On update le set en bdd avec le nom de la photo
                            $this->Sets->edit($_POST['id'], null, null, null, $_POST['id'] . '-' . $set_alias . '.' . $extension, null, null, null, null);
                        }
                    }

                    // On redirige vers la liste des sets
                    $_SESSION['success'] = 'Le set a bien été modifié';
                    header('Location: /admin/sets/listing');
                    die();
                } else {
                    $_SESSION['error'] = $edited_set;
                }
            }

        }

        // On récupère la liste des gammes, des séries, et des figurines
        $ranges_list = $this->Ranges->all();
        $series_list = '';
        $minifigures_list = '';
        if(isset($ranges_list) && count($ranges_list) > 0)
        {
            $range_id = (isset($_POST['range'])) ? $_POST['range']: $set->range_id;
            $series_list = $this->Series->getSeriesFromRange($range_id);
        }
        if(isset($series_list) && count($series_list) > 0)
        {
            $serie_id = (isset($_POST['serie'])) ? $_POST['serie']: $set->serie_id;
            $minifigures_list = $this->Minifigures->getMinifiguresFromSerie($serie_id);
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Modifier le set',
            'class_body' => 'sets-edit',
            'scripts' => array('admin/sets.js')
        ));
        // Rendu du contenu
        $variables = compact('errors', 'set', 'ranges_list', 'series_list', 'minifigures_list');
        $content = $this->render('admin/sets/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Action permettant de supprimer un set
     */
    public function delete() {

        // Si l'id du set n'existe pas, on affiche la liste des sets
        if(!isset($_GET['id']))
        {
            header('Location: /admin/sets/listing');
            die();
        }
           // Si on a bien un id de set
        else {
            // On recherche le set correspondant à l'id
            $set = $this->Sets->getSet($_GET['id']);
            // Si aucun set n'a été trouvé on affiche la liste des sets
            if (!$set) {
                header('Location: /admin/sets/listing');
                die();
            } // Si on a trouvé un set en bdd
            else {
                // On supprime le set correspondant à l'id
                $deleted_set = $this->Sets->delete($_GET['id']);
                // Si la suppression a été effectuée
                if ($deleted_set === true) {
                    // Si un visuel existe
                    if ($set->picture != '') {
                        // On le supprime
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/sets/57x57/' . $set->picture);
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/sets/209x238/' . $set->picture);
                    }

                    // On redirige vers la liste des sets avec le message de confirmation
                    $_SESSION['success'] = 'Le set a bien été supprimé';
                    header('Location: /admin/sets/listing');
                    die();
                } // Si la suppression n'a pas été effectuée
                else {
                    // On redirige vers la liste des sets avec le message d'erreur
                    $_SESSION['error'] = $deleted_set;
                    header('Location: /admin/sets/listing');
                    die();
                }
            }
        }

    }

}