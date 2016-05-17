<?php
namespace App\Controllers\Admin;
use App\Pages\Admin\Page;
use App;
use Core\Utilities\Utilities;

/**
 * Contrôleur des pages séries
 */
class SeriesController extends AdminController
{

    /**
     * Charge les modèles nécessaires
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Series');
        $this->loadModel('Ranges');
    }

    /**
     * Page listant les séries
     */
    public function listing()
    {
        $series_per_page = 32;
        // On récupère le nombre de séries
        $series_number = $this->Series->count();
        // On calcule le nombre de page
        $pages_number = ceil($series_number / $series_per_page);
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
        $first_entry = ($current_page - 1) * $series_per_page;
        // On récupère la liste des séries
        $series_list = $this->Series->some($first_entry, $series_per_page);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Séries',
            'class_body' => 'series',
            'scripts' => array('admin/series.js')
        ));
        // Rendu du contenu
        $variables = compact(
            'series_list',
            'series_number',
            'current_page',
            'pages_number'
        );
        $content = $this->render('admin/series/listing.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'ajouter une série
     */
    public function add()
    {
        $errors = array();
        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }

        // Si le formulaire a été posté
        if(isset($_POST['name'], $_POST['range'], $_FILES['picture']))
        {
            // On vérifie s'il contient des erreurs
            if($_POST['name'] == null) // Le champ nom n'a pas été rempli
            {
                $errors['name'] = 'Ce champ est obligatoire.';
            }

            if($_POST['range'] == null) // Le champ gamme n'a pas été rempli
            {
                $errors['range'] = 'Ce champ est obligatoire.';
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
                // On enregistre la série
                $serie_alias = Utilities::aliasFormat($_POST['name']);
                $serie_id = $this->Series->setSerie($_POST['name'], $serie_alias, $_POST['range']);
                // Si l'enregistrement de la série à généré une erreur, on l'affiche
                if(!is_int($serie_id)) {
                    $_SESSION['error'] = $serie_id;
                }
                // L'enregistrement s'est bien déroulé
                else
                {
                    // Si un visuel a été uploadé
                    if ($_FILES['picture']['size'] != 0 && $_FILES['picture']['error'] == 0)
                    {
                        // On le redimensionne et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 96, 96, '/assets/img/series/', $serie_id . '-' . $serie_alias);

                        // On update la série en bdd avec le nom du visuel
                        $editedSerie = $this->Series->editSerie($serie_id, null, null, $serie_id . '-' . $serie_alias . '.' . $extension, null);

                        if(!$editedSerie) {
                            $_SESSION['error'] = 'Erreur lors de l\'enregistrement du logo';
                        }
                    }

                    // On redirige vers la liste des séries
                    $_SESSION['success'] = 'La nouvelle série a bien été créée';
                    header('Location: /admin/series/listing');
                    die();
                }
            }

        }

        // On récupère la liste des gammes
        $ranges_list = $this->Ranges->all();

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Nouvelle série',
            'class_body' => 'series-add'
        ));
        // Rendu du contenu
        $variables = compact('errors', 'ranges_list');
        $content = $this->render('admin/series/add.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'éditer une série
     */
    public function edit() {

        $errors = array();
        // Si l'id de la série n'existe pas, on affiche la liste des séries
        if(!isset($_GET['id']))
        {
            header('Location: /admin/series/listing');
            die();
        }
        // Si on a un id série
        else
        {
            // On recherche la série correspondant à l'id
            $serie = $this->Series->getSerie($_GET['id']);
        }

        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post')
        {
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }

        // Si le formulaire a été posté
        if(isset($_POST['serie_id'], $_POST['name'], $_POST['range'], $_FILES['picture']))
        {
            // On vérifie s'il contient des erreurs
            if($_POST['name'] == null) //Le champ nom n'a pas été rempli
            {
                $errors['name'] = 'Ce champ est obligatoire.';
            }

            if($_POST['range'] == null) //Le champ gamme n'a pas été rempli
            {
                $errors['range'] = 'Ce champ est obligatoire.';
            }

            if ($_FILES['picture']['size'] != 0) // Si une nouvelle photo est envoyée
            {
                if($_FILES['picture']['error'] > 0) // Erreur d'upload de la photo
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

            //Le formulaire a été correctement rempli
            if(empty($errors))
            {
                $serie_alias = Utilities::aliasFormat($_POST['name']);
                // On édite la série
                $edited_serie = $this->Series->editSerie($_POST['serie_id'], $_POST['name'], $serie_alias,  null, $_POST['range']);

                if($edited_serie === true)
                {
                    // Si une nouvelle photo a été uploadée
                    if ($_FILES['picture']['size'] != 0 && $_FILES['picture']['error'] == 0)
                    {
                        // Si la série avait déjà une photo
                        if($serie->picture != '')
                        {
                            // On la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/series/' . $serie->picture);
                        }
                        // On redimensionne la nouvelle photo et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 96, 96, '/assets/img/series/', $_POST['serie_id'] . '-' . $serie_alias );
                        // On update la série en bdd avec le nom de la photo
                        $this->Series->editSerie($_POST['serie_id'], null, null, $_POST['serie_id'] . '-' . $serie_alias . '.' . $extension, null);
                    }
                    // Aucune nouvelle photo n'a été uploadée
                    else
                    {
                        // Si on a choisi de supprimer la photo
                        if(isset($_POST['delete_picture']))
                        {
                            // Et ben on la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/series/' . $serie->picture);
                            // Et on update la série en bdd pour vider le nom de la photo
                            $this->Series->editSerie($_POST['serie_id'], null, null, '', null);
                        }
                        // Si le nom de la série a changé et que cette série a une photo
                        elseif($serie->name != $_POST['name'] && $serie->picture != '')
                        {
                            $extension = strtolower(substr(strrchr($serie->picture, '.'), 1));
                            // On renomme la photo
                            rename ($_SERVER['DOCUMENT_ROOT'] . '/assets/img/series/' . $serie->picture, $_SERVER['DOCUMENT_ROOT'] . '/assets/img/series/' . $_POST['serie_id'] . '-' . $serie_alias . '.' . $extension);
                            // On update la série en bdd avec le nom de la photo
                            $this->Series->editSerie($_POST['serie_id'], null, null, $_POST['serie_id'] . '-' . $serie_alias . '.' . $extension, null);
                        }
                    }

                    // On redirige vers la liste des séries
                    $_SESSION['success'] = 'La série a bien été modifiée';
                    header('Location: /admin/series/listing');
                    die();
                }
                else
                {
                    $_SESSION['error'] = $edited_serie;
                }
            }

        }

        // On récupère la liste des gammes
        $ranges_list = $this->Ranges->all();
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Modifier la série',
            'class_body' => 'series-edit'
        ));
        // Rendu du contenu
        $variables = compact('errors', 'serie', 'ranges_list');
        $content = $this->render('admin/series/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Action permettant de supprimer une série
     */
    public function delete()
    {
        // Si l'id de la série n'existe pas, on affiche la liste des séries
        if(!isset($_GET['id']))
        {
            header('Location: /admin/series/listing');
            die();
        }
        // Si on a bien un id de série
        else
        {
            // On recherche la série correspondant à l'id
            $serie = $this->Series->getSerie($_GET['id']);
            // Si aucune série n'a été trouvée on affiche la liste des séries
            if(!$serie)
            {
                header('Location: /admin/series/listing');
                die();
            }
            // Si on a trouvé une série en bdd
            else
            {
                // On supprime la série correspondant à l'id
                $deleted_serie = $this->Series->deleteSerie($_GET['id']);
                // Si la suppression a été effectuée
                if($deleted_serie === true)
                {
                    // Si un visuel existe
                    if ($serie->picture != '')
                    {
                        // On le supprime
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/series/' . $serie->picture);
                    }

                    // On redirige vers la liste des séries avec le message de confirmation
                    $_SESSION['success'] = 'La série a bien été supprimée';
                    header('Location: /admin/series/listing');
                    die();
                }
                // Si la suppression n'a pas été effectuée
                else
                {
                    // On redirige vers la liste des séries avec le message d'erreur
                    $_SESSION['error'] = $deleted_serie;
                    header('Location: /admin/series/listing');
                    die();
                }
            }
        }
    }

    /**
     * Ajax : Retourne l'ensemble des séries comprises dans une gamme
     */
    public function getSeriesFromRange() {
        $this->loadModel('Series');
        if (isset($_GET['range_id']))
        {
            $series_list = $this->Series->getSeriesFromRange($_GET['range_id']);
            echo json_encode($series_list);
        }
    }

}