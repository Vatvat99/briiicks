<?php
namespace App\Controllers\Admin;
use App\Pages\Admin\Page;
use App;
use Core\Utilities\Utilities;

/**
 * Contrôleur des pages membres
 */
class MembersController extends AdminController
{

    /**
     * Charge les modèles nécessaires
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Members');
    }

    /**
     * Page listant les membres
     */
    public function listing()
    {
        $members_per_page = 32;
        // On récupère le nombre de membres
        $members_number = $this->Members->count();
        // On calcule le nombre de page
        $pages_number = ceil($members_number / $members_per_page);
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
        $first_entry = ($current_page - 1) * $members_per_page;
        // On récupère la liste des membres
        $members_list = $this->Members->some($first_entry, $members_per_page);
        // Préparation de la page
        $page = new Page(array(
            'title' => 'Annonces',
            'class_body' => 'members',
            'scripts' => array('admin/members.js')
        ));
        // Rendu du contenu
        $variables = compact(
            'members_list',
            'members_number',
            'current_page',
            'pages_number'
        );
        $content = $this->render('admin/members/listing.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'ajouter un membre
     */
    public function add() {
        $errors = array();
        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post')
        {
            $errors['profile_picture'] = 'Fichier trop gros. (3Mo maximum)';
        }

        //Le formulaire a été posté
        if(isset($_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password'], $_POST['password_confirmation'], $_FILES['profile_picture']))
        {
            // On vérifie s'il contient des erreurs
            if($_POST['firstname'] == null) //Le champ prénom n'a pas été rempli
            {
                $errors['firstname'] = 'Ce champ est obligatoire.';
            }

            if($_POST['lastname'] == null) //Le champ nom n'a pas été rempli
            {
                $errors['lastname'] = 'Ce champ est obligatoire.';
            }

            if($_POST['email'] == null) //L'e-mail n'a pas été rempli
            {
                $errors['email'] = 'Ce champ est obligatoire.';
            }
            elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) //Le format de l'email n'est pas bon
            {
                $errors['email'] = 'L\'adresse e-mail n\'est pas valide';
            }

            if($_POST['password'] == null) // Le mot de passe n'a pas été rempli
            {
                $errors['password'] = 'Ce champ est obligatoire.';
            }

            if($_POST['password_confirmation'] == null) // La confirmation du mot de passe n'a pas été rempli
            {
                $errors['password_confirmation'] = 'Ce champ est obligatoire.';
            }
            elseif($_POST['password'] != $_POST['password_confirmation']) // Le mot de passe et sa confirmation ne sont pas identiques
            {
                $errors['password_confirmation'] = 'Les mots de passe ne correspondent pas.';
            }

            if ($_FILES['profile_picture']['size'] != 0) // Si une photo est envoyée
            {
                if($_FILES['profile_picture']['error'] > 0) // Erreur d'upload de la photo de profil
                {
                    $errors['profile_picture'] = 'Erreur lors du transfert.';
                }
                elseif ($_FILES['profile_picture']['size'] > App::getInstance()->getConfig()->get('max_file_size')) // Si le fichier est trop gros
                {
                    $errors['profile_picture'] = 'Fichier trop gros. (3Mo maximum)';
                }
                else {
                    $extension = strtolower(substr(strrchr($_FILES['profile_picture']['name'], '.'), 1));
                    if (!in_array($extension, App::getInstance()->getConfig()->get('img_authorized_extensions')))
                    {
                        $errors['profile_picture'] = 'Type de fichier non-autorisé. (seulement jpg, gif, png)';
                    }
                }
            }

            // Le formulaire a été correctement rempli
            if(empty($errors))
            {
                // On enregistre le membre
                $member_alias = Utilities::aliasFormat($_POST['firstname'] . ' ' . $_POST['lastname']);
                $member_id = $this->Members->setMember($_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password'], '', 1);
                // Si l'enregistrement s'est bien déroulé
                if(is_int($member_id))
                {
                    // Si une photo de profil a été uploadée
                    if ($_FILES['profile_picture']['size'] != 0 && $_FILES['profile_picture']['error'] == 0)
                    {
                        // On la redimensionne et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['profile_picture']['name'], $_FILES['profile_picture']['tmp_name'], 96, 96, '/assets/img/members/', $member_id . '-' . $member_alias);

                        // On update le membre en bdd avec le nom de la photo de profil
                        $this->Members->editMember($member_id, null, null, null, null, $member_id . '-' . $member_alias . '.' . $extension);
                    }

                    // On redirige vers la liste des membres
                    $_SESSION['success'] = 'Le nouveau membre a bien été créé';

                    header('Location: /admin/members/listing');
                    die();
                }
                // Si l'enregistrement du membre à généré une erreur, on l'affiche
                else
                {
                    $_SESSION['error'] = $member_id;
                }
            }
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Nouveau membre',
            'class_body' => 'members-add'
        ));
        // Rendu du contenu
        $variables = compact('errors');
        $content = $this->render('admin/members/add.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page permettant d'éditer un membre
     */
    public function edit()
    {
        $errors = array();
        // Si l'id du membre n'existe pas, on affiche la liste des membres
        if(!isset($_GET['id']))
        {
            header('Location: /admin/members/listing');
            die();
        }
        // Si on a un id membre
        // On recherche le membre correspondant à l'id
        $member = $this->Members->getMember($_GET['id']);
        // Si aucun membre n'a été trouvé on affiche la liste des membres
        if(!$member)
        {
            header('Location: /admin/members/listing');
            die();
        }
        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }

        // Si le formulaire a été posté
        if(isset($_POST['id'], $_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password'], $_POST['password_confirmation'], $_FILES['profile_picture']))
        {
            // On vérifie s'il contient des erreurs
            if($_POST['firstname'] == null) //Le champ prénom n'a pas été rempli
            {
                $errors['firstname'] = 'Ce champ est obligatoire.';
            }

            if($_POST['lastname'] == null) //Le champ nom n'a pas été rempli
            {
                $errors['lastname'] = 'Ce champ est obligatoire.';
            }

            if($_POST['email'] == null) //L'e-mail n'a pas été rempli
            {
                $errors['email'] = 'Ce champ est obligatoire.';
            }
            elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) //Le format de l'email n'est pas bon
            {
                $errors['email'] = 'L\'adresse e-mail n\'est pas valide';
            }
            elseif($_POST['password'] != $_POST['password_confirmation']) //Le mot de passe et sa confirmation ne sont pas identiques
            {
                $errors['password_confirmation'] = 'Les mots de passe ne correspondent pas.';
            }

            if ($_FILES['profile_picture']['size'] != 0) // Si une nouvelle photo est envoyée
            {
                if($_FILES['profile_picture']['error'] > 0) // Erreur d'upload de la photo de profil
                {
                    $errors['profile_picture'] = 'Erreur lors du transfert.';
                }
                elseif ($_FILES['profile_picture']['size'] > App::getInstance()->getConfig()->get('max_file_size')) // Si le fichier est trop gros
                {
                    $errors['profile_picture'] = 'Fichier trop gros. (3Mo maximum)';
                }
                else {
                    $extension = strtolower(substr(strrchr($_FILES['profile_picture']['name'], '.'), 1));
                    if (!in_array($extension, App::getInstance()->getConfig()->get('img_authorized_extensions')))
                    {
                        $errors['profile_picture'] = 'Type de fichier non-autorisé. (seulement jpg, gif, png)';
                    }
                }
            }

            //Le formulaire a été correctement rempli
            if(empty($errors))
            {
                $member_alias = Utilities::aliasFormat($_POST['firstname'] . ' ' . $_POST['lastname']);
                // On modifie le membre
                $edited_member = $this->Members->editMember($_POST['id'], $_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password'], null);

                if($edited_member === true)
                {
                    // Si une nouvelle photo de profil a été uploadée
                    if ($_FILES['profile_picture']['size'] != 0 && $_FILES['profile_picture']['error'] == 0)
                    {
                        // Si le membre avait déjà une photo de profil
                        if($member->picture != '')
                        {
                            // On la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/members/' . $member->picture);
                        }
                        // On redimensionne la nouvelle photo et on l'enregistre
                        Utilities::resizeAndSavePicture($_FILES['profile_picture']['name'], $_FILES['profile_picture']['tmp_name'], 96, 96, '/assets/img/members/', $_POST['id'] . '-' . $member_alias);
                        // On update le membre en bdd avec le nom de la photo de profil
                        $this->Members->editMember($_POST['id'], null, null, null, null, $_POST['id'] . '-' . $member_alias . '.' . $extension);
                    }
                    // Aucune nouvelle photo n'a été uploadée
                    else
                    {
                        // Si on a choisi de supprimer la photo
                        if(isset($_POST['delete_picture']))
                        {
                            // Et ben on la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/members/' . $member->picture);
                            // Et on update la série en bdd pour vider le nom de la photo
                            $this->Members->editMember($_POST['id'], null, null, null, null, '');
                        }
                        // Si le nom du membre a changé et que ce membre a une photo de profil
                        elseif(($member->firstname != $_POST['firstname'] || $member->lastname != $_POST['lastname']) && $member->picture != '')
                        {
                            $extension = strtolower(substr(strrchr($member->picture, '.'), 1));
                            // On renomme la photo
                            rename ($_SERVER['DOCUMENT_ROOT'] . '/assets/img/members/' . $member->picture, $_SERVER['DOCUMENT_ROOT'] . '/assets/img/members/' . $_POST['id'] . '-' . $member_alias . '.' . $extension);
                            // On update la gamme en bdd avec le nom de la photo
                            $this->Members->editMember($_POST['id'], null, null, null, null, $_POST['id'] . '-' . $member_alias . '.' . $extension);
                        }
                    }
                    // On redirige vers la liste des membres
                    $_SESSION['success'] = 'Le membre a bien été modifié';
                    header('Location: /admin/members/listing');
                    die();
                }
                else
                {
                    $_SESSION['error'] = $edited_member;
                }
            }
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Modifier le membre',
            'class_body' => 'member-edit'
        ));
        // Rendu du contenu
        $variables = compact('errors', 'member');
        $content = $this->render('admin/members/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Action permettant de supprimer un membre
     */
    public function delete()
    {
        // Si l'id du membre n'existe pas, on affiche la liste des membres
        if(!isset($_GET['id']))
        {
            header('Location: /admin/members/listing');
            die();
        }
        // Si on a bien un id de membre
        else
        {
            // On recherche le membre correspondant à l'id
            $member = $this->Members->getMember($_GET['id']);
            // Si aucun membre n'a été trouvé on affiche la liste des membres
            if(!$member)
            {
                header('Location: /admin/members/listing');
                die();
            }
            // Si on a trouvé un membre en bdd
            else
            {
                // On supprime le membre correspondant à l'id
                $deleted = $this->Members->delete($_GET['id']);
                // Si la suppression n'a pas été effectuée on affiche la liste des membres
                if(!$deleted)
                {
                    $_SESSION['error'] = 'Echec de la suppression du membre';
                    header('Location: /admin/members/listing');
                    die();
                }
                // Si la suppression a été effectuée
                else
                {
                    // Si il y a une photo de profil
                    if ($member->picture != '')
                    {
                        // On la supprime
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/members/' . $member->picture);
                    }
                    // On redirige vers la liste des membres avec le message de confirmation
                    $_SESSION['success'] = 'Le membre a bien été supprimé';
                    header('Location: /admin/members/listing');
                    die();
                }
            }
        }
    }

}