<?php
namespace App\Controllers\Admin;
use Core\Html\Form;
use Core\Auth\DbAuth;
use App;
use App\Pages\Admin\Page;

/**
 * Contrôleur des utilisateurs
 */
class UsersController extends AdminController {

    /**
     * Charge les modèles nécessaires
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Users', 'admin');
    }

    /**
     * Page : Liste les utilisateurs
     */
    public function listing()
    {
        $users_per_page = 32;
        // On récupère le nombre d'utilisateurs
        $users_number = $this->Users->count();
        // On calcule le nombre de page
        $pages_number = ceil($users_number / $users_per_page);
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
        $first_entry = ($current_page - 1) * $users_per_page;
        // On récupère la liste des utilisateurs
        $users_list = $this->Users->some($first_entry, $users_per_page);

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Utilisateurs',
            'class_body' => 'users',
            'scripts' => array('admin/users.js')
        ));
        // Rendu du contenu
        $variables = compact(
            'users_list',
            'users_number',
            'current_page',
            'pages_number'
        );
        $content = $this->render('admin/users/listing.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page : Permet d'ajouter un utilisateur
     */
    public function add()
    {
        $errors = array();
        //Le formulaire a été posté
        if(isset($_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password'], $_POST['password_confirmation'])) {
            // On vérifie s'il contient des erreurs
            if ($_POST['firstname'] == null) //Le champ prénom n'a pas été rempli
            {
                $errors['firstname'] = 'Ce champ est obligatoire.';
            }

            if ($_POST['lastname'] == null) //Le champ nom n'a pas été rempli
            {
                $errors['lastname'] = 'Ce champ est obligatoire.';
            }

            if ($_POST['email'] == null) //L'e-mail n'a pas été rempli
            {
                $errors['email'] = 'Ce champ est obligatoire.';
            } elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) //Le format de l'email n'est pas bon
            {
                $errors['email'] = 'L\'adresse e-mail n\'est pas valide';
            }

            if ($_POST['password'] == null) //Le mot de passe n'a pas été rempli
            {
                $errors['password'] = 'Ce champ est obligatoire.';
            }

            if ($_POST['password_confirmation'] == null) //La confirmation du mot de passe n'a pas été rempli
            {
                $errors['password_confirmation'] = 'Ce champ est obligatoire.';
            } elseif ($_POST['password'] != $_POST['password_confirmation']) //Le mot de passe et sa confirmation ne sont pas identiques
            {
                $errors['password_confirmation'] = 'Les mots de passe ne correspondent pas.';
            }

            //Le formulaire a été correctement rempli
            if (empty($errors)) {
                // On enregistre l'utilisateur
                $user = $this->Users->setUser($_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password']);

                if (is_int($user)) {
                    // On redirige vers la liste des utilisateurs
                    $_SESSION['success'] = 'Le nouvel utilisateur a bien été créé';
                    header('Location: /admin/users/listing');
                    die();
                } else {
                    $_SESSION['error'] = $user;
                }
            }
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Nouvel utilisateur',
            'class_body' => 'users-add'
        ));
        // Rendu du contenu
        $variables = compact('errors');
        $content = $this->render('admin/users/add.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut afficher qu'une seule fois
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page : Permet d'éditer un utilisateur
     */
    public function edit()
    {
        $errors = array();
        // Si l'id de l'utilisateur n'existe pas, on affiche la liste des utilisateurs
        if(!isset($_GET['id']))
        {
            header('Location: /admin/users/listing');
            die();
        }
        // Si on est là, c'est qu'on a un id utilisateur
        // On recherche l'utilisateur correspondant à l'id
        $user = $this->Users->getUser($_GET['id']);
        // Si aucun utilisateur n'a été trouvé on affiche la liste des utilisateurs
        if(!$user)
        {
            header('Location: /admin/users/listing');
            die();
        }

        // Si le formulaire a été posté
        if(isset($_POST['id'], $_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password'], $_POST['password_confirmation']))
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

            // Le formulaire a été correctement rempli
            if(empty($errors))
            {
                // On modifie l'utilisateur
                $user = $this->Users->editUser($_POST['id'], $_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password']);

                if($user === true)
                {
                    // On redirige vers la liste des utilisateurs
                    $_SESSION['success'] = 'L\'utilisateur a bien été modifié';
                    header('Location: /admin/users/listing');
                    die();
                }
                else
                {
                    $_SESSION['error'] = $user;
                }
            }
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Modifier l\'utilisateur',
            'class_body' => 'users-edit'
        ));
        // Rendu du contenu
        $variables = compact('errors', 'user');
        $content = $this->render('admin/users/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut afficher qu'une seule fois
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Permet de supprimer un membre
     */
    public function delete() {
        // Si l'id de l'utilisateur n'existe pas, on affiche la liste des utilisateurs
        if(!isset($_GET['id']))
        {
            header('Location: /admin/users/listing');
            die();
        }
        // Si on a bien un id d'utilisateurs
        else
        {
            // On supprime l'utilisateur correspondant
            $deleted = $this->Users->delete($_GET['id']);
            // Si la suppression n'a pas été effectuée on affiche la liste des utilisateurs
            if(!$deleted)
            {
                $_SESSION['error'] = 'Echec de la suppression de l\'utilisateur';
                header('Location: /admin/users/listing');
                die();
            }
            // Si la suppression a été effectuée
            else
            {
                // On redirige vers la liste des utilisateurs avec le message de confirmation
                $_SESSION['success'] = 'L\'utilisateur a bien été supprimé';
                header('Location: /admin/users/listing');
                die();
            }
        }
    }

	/**
	 * Page : Formulaire de connection à l'administration
	 */
	public function login()
    {
        // Traitement
		$errors = null;
        // Le formulaire a été posté
		if(!empty($_POST))
        {
            // Vérification des erreurs
            if($_POST['email'] == null || $_POST['password'] == null) // L'e-mail ou le mot de passe n'ont pas été remplis
            {
                $errors = 'Veuillez renseigner votre e-mail et votre mot de passe';
            }
            // Le formulaire a été correctement rempli
            if($errors == null)
            {
                $auth = new DbAuth(App::getInstance()->getDb(), 'briiicks_back_users');
                // On cherche l'utilisateur correspondant en bdd
                $user = $auth->findUser($_POST['email'], $_POST['password']);
                // Si aucun utilisateur n'a été trouvé
                if(!$user)
                {
                    $errors = 'Mauvais identifiant ou mot de passe';
                }
                else
                { // Un utilisateur a été trouvé
                    // On vérifie s'il a choisit de rester connecté
                    if(isset($_POST['keep_me_logged']))
                    {
                        $keep_logged = true;
                    } else
                    {
                        $keep_logged = false;
                    }
                    // Et on le connecte
                    $auth->login($user, 'back', $keep_logged);
                    // Et on enregistre les infos supplémentaires
                    $_SESSION['back']['lastname'] = $user->lastname;
                    $_SESSION['back']['firstname'] = $user->firstname;
                    $_SESSION['back']['email'] = $user->email;
                    header('Location: /admin/home/index');
                }
            }
        }
		$form = new Form($_POST);
		// Préparation de la page
        $page = new Page(array(
            'title' => 'Connexion',
            'class_body' => 'login',
            'scripts' => array('login.js')
        ));
        // Rendu du contenu
        $variables = compact('form', 'errors');
        $content = $this->render('admin/users/login.php', $variables);
        // Rendu de la page
        echo $page->render($content, 'login');
	}

    /**
     * Déconnecte l'utilisateur
     */
    public function logout()
    {
        // Suppression des variables de session et de la session
        $_SESSION['back'] = array();
        // Suppression des cookies de connexion automatique
        setcookie('back[email]', '');
        setcookie('back[password]', '');
        // Puis redirection vers la page d'accueil
        header('Location: /admin/users/login');
    }

}