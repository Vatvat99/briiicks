<?php
namespace App\Controllers\Admin;
use App\Pages\Admin\Page;
use App;
use Core\Utilities\Utilities;

/**
 * Contrôleur des pages gammes
 */
class RangesController extends AdminController {

    /**
     * Charge les modèles nécessaires
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Ranges');
    }

    /**
     * Page listant les figurines
     */
    public function listing()
    {
        $ranges_per_page = 32;
        // On récupère le nombre de gammes
        $ranges_number = $this->Ranges->count();
        // On calcule le nombre de page
        $pages_number = ceil($ranges_number / $ranges_per_page);
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
        $first_entry = ($current_page - 1) * $ranges_per_page;
        // On récupère la liste des gammes
        $ranges_list = $this->Ranges->some($first_entry, $ranges_per_page);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Gammes',
            'class_body' => 'ranges',
            'scripts' => array('admin/ranges.js')
        ));
        // Rendu du contenu
        $variables = compact(
            'ranges_list',
            'ranges_number',
            'current_page',
            'pages_number'
        );
        $content = $this->render('admin/ranges/listing.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'ajouter une gamme
     */
    public function add()
    {
        $errors = array();
        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }
        // Le formulaire a été posté
        if(isset($_POST['name'], $_POST['color'], $_FILES['picture']))
        {
            // On vérifie s'il y a des erreurs
            if($_POST['name'] == null) //Le champ nom n'a pas été rempli
            {
                $errors['name'] = 'Ce champ est obligatoire.';
            }

            if($_POST['color'] == null) //Le champ couleur n'a pas été rempli
            {
                $errors['color'] = 'Ce champ est obligatoire.';
            }
            elseif(!preg_match('#^\#(([a-fA-F]|[0-9]){2}){3}$#', $_POST['color'])) //Le champ couleur n'est pas un bon format
            {
                $errors['color'] = 'La couleur doit être au format hexadécimal.';
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
                // On enregistre la gamme
                $range_alias = Utilities::aliasFormat($_POST['name']);
                $range_id = $this->Ranges->setRange($_POST['name'], $range_alias, $_POST['color']);
                // Si l'enregistrement de la gamme à généré une erreur, on l'affiche
                if(!is_int($range_id)) {
                    $_SESSION['error'] = $range_id;
                }
                // L'enregistrement s'est bien déroulé
                else
                {
                    // Si un visuel a été uploadé
                    if ($_FILES['picture']['size'] != 0 && $_FILES['picture']['error'] == 0)
                    {
                        // On le redimensionne et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 96, 96, '/assets/img/ranges/', $range_id . '-' . $range_alias);

                        // On update la gamme en bdd avec le nom du visuel
                        $this->Ranges->editRange($range_id, null, null, null, $range_id . '-' . $range_alias . '.' . $extension);
                    }

                    // On redirige vers la liste des gammes
                    $_SESSION['success'] = 'La nouvelle gamme a bien été créée';

                    header('Location: /admin/ranges/listing');
                    die();
                }
            }

        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Nouvelle gamme',
            'class_body' => 'ranges-add',
            'scripts' => array('admin/ranges.js')
        ));
        // Rendu du contenu
        $variables = compact('errors');
        $content = $this->render('admin/ranges/add.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'éditer une gamme
     */
    public function edit() {

        $errors = array();
        // Si l'id de la gamme n'existe pas, on affiche la liste des gammes
        if(!isset($_GET['id']))
        {
            header('Location: /admin/ranges/listing');
            die();
        }
        // Si on a un id gamme
        else
        {
            // On recherche la gamme correspondant à l'id
            $range = $this->Ranges->getRange($_GET['id']);
            // Si aucune gamme n'a été trouvée on affiche la liste des gammes
            if(!$range)
            {
                header('Location: /admin/ranges/listing');
                die();
            }
        }

        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post')
        {
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }

        // Si le formulaire a été posté
        if(isset($_POST['id'], $_POST['name'], $_POST['color'], $_FILES['picture']))
        {
            // On vérifie s'il y a des erreurs
            if($_POST['name'] == null) //Le champ nom n'a pas été rempli
            {
                $errors['name'] = 'Ce champ est obligatoire.';
            }

            if($_POST['color'] == null) //Le champ couleur n'a pas été rempli
            {
                $errors['color'] = 'Ce champ est obligatoire.';
            }
            elseif(!preg_match('#^\#(([a-fA-F]|[0-9]){2}){3}$#', $_POST['color'])) //Le champ couleur n'est pas un bon format
            {
                $errors['color'] = 'La couleur doit être au format hexadécimal.';
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
                $range_alias = Utilities::aliasFormat($_POST['name']);
                // On modifie la gamme
                $edited_range = $this->Ranges->editRange($_POST['id'], $_POST['name'], $range_alias, $_POST['color'], null);

                if($edited_range === true)
                {
                    // Si une nouvelle photo a été uploadée
                    if ($_FILES['picture']['size'] != 0 && $_FILES['picture']['error'] == 0)
                    {
                        // Si la gamme avait déjà une photo
                        if($range->picture != '')
                        {
                            // On la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/ranges/' . $range->picture);
                        }
                        // On redimensionne la nouvelle photo et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['picture']['name'], $_FILES['picture']['tmp_name'], 96, 96, '/assets/img/ranges/', $_POST['id'] . '-' . $range_alias);

                        // On update la gamme en bdd avec le nom de la photo
                        $this->Ranges->editRange($_POST['id'], null, null, null, $_POST['id'] . '-' . $range_alias . '.' . $extension);
                    }
                    // Aucune nouvelle photo n'a été uploadée
                    else
                    {
                        // Si on a choisi de supprimer la photo
                        if(isset($_POST['delete_picture']))
                        {
                            // Et ben on la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/ranges/' . $range->picture);
                            // Et on update la gamme en bdd pour vider le nom de la photo
                            $this->Ranges->editRange($_POST['id'], null, null, null, '');
                        }
                        // Si le nom de la gamme a changé et que cette gamme a une photo
                        elseif($range->name != $_POST['name'] && $range->picture != '')
                        {
                            $extension = strtolower(substr(strrchr($range->picture, '.'), 1));
                            // On renomme la photo
                            rename ($_SERVER['DOCUMENT_ROOT'] . '/assets/img/ranges/' . $range->picture, $_SERVER['DOCUMENT_ROOT'] . '/assets/img/ranges/' . $_POST['id'] . '-' . $range_alias . '.' . $extension);
                            // On update la gamme en bdd avec le nom de la photo
                            $this->Ranges->editRange($_POST['id'], null, null, null, $_POST['id'] . '-' . $range_alias . '.' . $extension);
                        }
                    }

                    // On redirige vers la liste des gammes
                    $_SESSION['success'] = 'La gamme a bien été modifiée';
                    header('Location: /admin/ranges/listing');
                    die();
                }
                else
                {
                    $_SESSION['error'] = $edited_range;
                }
            }
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Modifier la gamme',
            'class_body' => 'ranges-edit'
        ));
        // Rendu du contenu
        $variables = compact('errors', 'range');
        $content = $this->render('admin/ranges/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Action permettant de supprimer une gamme
     */
    public function delete()
    {
        // Si l'id de la gamme n'existe pas, on affiche la liste des gammes
        if(!isset($_GET['id']))
        {
            header('Location: /admin/ranges/listing');
            die();
        }
        // Si on a bien un id de gamme
        else
        {
            // On recherche la gamme correspondant à l'id
            $range = $this->Ranges->getRange($_GET['id']);
            // Si aucune gamme n'a été trouvée on affiche la liste des gammes
            if(!$range)
            {
                header('Location: /admin/ranges/listing');
                die();
            }
            // Si on a trouvé une gamme en bdd
            else
            {
                // On supprime la gamme correspondant à l'id
                $deleted = $this->Ranges->deleteRange($_GET['id']);
                // Si la suppression n'a pas été effectuée on affiche la liste des gammes
                if(is_string($deleted))
                {
                    $_SESSION['error'] = $deleted;
                    header('Location: /admin/ranges/listing');
                    die();
                }
                // Si la suppression a été effectuée
                else
                {
                    // Si un visuel existe
                    if ($range->picture != '')
                    {
                        // On le supprime
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/ranges/' . $range->picture);
                    }
                    // On redirige vers la liste des gammes avec le message de confirmation
                    $_SESSION['success'] = 'La gamme a bien été supprimée';
                    header('Location: /admin/ranges/listing');
                    die();
                }
            }
        }
    }

}