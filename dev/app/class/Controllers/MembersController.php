<?php
namespace App\Controllers;
use App;
use App\Pages\Page;
use Core\Auth\DbAuth;
use Core\Utilities\Utilities;

/**
 * Contrôleur dédié aux membres du site
 */
class MembersController extends AppController
{

    /**
     * Définie le chemin vers les vues et charge les modèles nécessaires
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('Members');
    }

    /**
     * Page : Formulaire d'inscription
     */
    public function signin()
    {
        // On vérifie que l'utilisateur n'est pas connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si il l'est
        if($auth->logged('front')) {
            // On le redirige vers la page d'accueil
            header('Location: /');
        }

        $confirmation_message = '';
        $errors = array();
        // Le formulaire a été posté
        if(isset($_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['password'], $_POST['password_confirmation']))
        {
            if($_POST['firstname'] == null) // Le champ prénom n'a pas été rempli
            {
                $errors['firstname'] = 'Ce champ est obligatoire.';
            }

            if($_POST['lastname'] == null) // Le champ nom n'a pas été rempli
            {
                $errors['lastname'] = 'Ce champ est obligatoire.';
            }

            if($_POST['email'] == null) // L'e-mail n'a pas été rempli
            {
                $errors['email'] = 'Ce champ est obligatoire.';
            }
            elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) // Le format de l'email n'est pas bon
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

            // Le formulaire a été correctement rempli
            if(empty($errors))
            {
                // Génération aléatoire d'une clé
                $activation_key = sha1(microtime(TRUE)*100000);
                // On inscrit le membre (modèle)
                $member = $this->Members->setMember($_POST['lastname'],$_POST['firstname'], $_POST['email'], $_POST['password'], $activation_key, 0);

                if(is_int($member))
                {
                    // On envoie un mail pour demander la confirmation de l'adresse mail
                    $this->sendConfirmationEmail($_POST['email'], $_POST['firstname'], $activation_key);
                    // header('Location: /members/signin-confirmation-mail?email=' . $_POST['email']);
                    // Et on affiche la page de confirmation
                    $email = $_POST['email'];
                    // Préparation de la page
                    $page = new Page(array(
                        'title' => 'Confirmation de votre adresse e-mail',
                        'class_body' => 'signin'
                    ));
                    // Rendu du contenu
                    $variables = compact('email');
                    $content = $this->render('members/signin/confirmation.php', $variables);
                    // Rendu de la page
                    echo $page->render($content);
                }
                else
                {
                    $confirmation_message = $member;
                }
            }
        }
        // Si aucun membre n'a été créé
        if(!isset($member) || is_string($member)) {
            // On affiche le formulaire
            // Préparation de la page
            $page = new Page(array(
                'title' => 'Inscription',
                'class_body' => 'signin'
            ));
            // Rendu du contenu
            $variables = compact('errors', 'confirmation_message');
            $content = $this->render('members/signin/form.php', $variables);
            // Rendu de la page
            echo $page->render($content);
        }
    }

    /**
     * Action : Activation d'un compte
     *
     */
    public function activateAccount()
    {
        // Récupération des variables nécessaires à l'activation
        $email = $_GET['email'];
        $activation_key = $_GET['key'];
        // On active le compte
        $activation = $this->Members->activateAccount($email, $activation_key);

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Inscription réussie',
            'class_body' => 'signin'
        ));
        // Rendu du contenu
        $variables = compact('activation');
        $content = $this->render('members/signin/validation.php', $variables);
        // Rendu de la page
        echo $page->render($content);
    }

    /**
     * Page : Formulaire de connexion
     */
    public function login()
    {
        // On vérifie que l'utilisateur n'est pas connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si il l'est
        if($auth->logged('front')) {
            // On le redirige vers la page d'accueil
            header('Location: /');
        }

        $errors = array();
        // Le formulaire a été posté
        if(isset($_POST['email'], $_POST['password']))
        {
            // On vérifie s'il contient des erreurs
            if($_POST['email'] == null || $_POST['password'] == null) // L'e-mail ou le mot de passe n'ont pas été remplis
            {
                $errors['login'] = 'Veuillez renseigner votre e-mail et votre mot de passe';
            }

            // Le formulaire a été correctement rempli
            if(empty($error))
            {
                // Hachage du mot de passe
                $chopped_password = sha1($_POST['password']);
                // On cherche le membre correspondant en bdd
                $member = $this->Members->findMember($_POST['email'], $chopped_password);
                // Si aucun membre n'a été trouvé
                if(!$member)
                {
                    $errors['login'] = 'Mauvais identifiant ou mot de passe';
                }
                // On a trouvé un membre
                else
                {
                    // On cherche si le compte est bien activé
                    $active = $this->Members->findActive($member->id);
                    // Si le compte n'est pas activé
                    if(!$active)
                    {
                        $errors['login'] = 'Ce compte n\'est pas activé. Veuillez cliquer sur le lien contenu dans le mail que nous vous avons envoyé lors de votre inscription pour activer votre compte.';
                    }
                    // Le compte est activé
                    else
                    {
                        // Si l'utilisateur a coché la case pour rester connecté, création d'un cookie
                        if(isset($_POST['keep_me_logged']))
                        {
                            setcookie('front[email]', $_POST['email'], time() + 365*24*3600, null, null, false, true);
                            setcookie('front[password]', $chopped_password, time() + 365*24*3600, null, null, false, true);
                        }

                        // Enregistrement des informations du membre en session
                        session_start();
                        $_SESSION['front']['id'] = $member->id;
                        $_SESSION['front']['lastname'] = $member->lastname;
                        $_SESSION['front']['firstname'] = $member->firstname;
                        $_SESSION['front']['email'] = $member->email;
                        $_SESSION['front']['picture'] = $member->picture;
                        // Puis redirection vers la page de profil
                        header('Location: /members/profile/' . $member->id);
                        die();
                    }
                }
            }
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Connexion',
            'class_body' => 'login',
            'scripts' => array('login.js')
        ));
        // Rendu du contenu
        $variables = compact('errors');
        $content = $this->render('members/login.php', $variables);
        // Rendu de la page
        echo $page->render($content);
    }

    /**
     * Envoi le mail de confirmation suite à l'inscription sur le site
     * @param string $email E-mail du membre
     * @param string $firstname Prénom du membre
     * @param string $activation_key Clé générée lors de l'inscription
     */
    private function sendConfirmationEmail($email, $firstname, $activation_key)
    {
        // Récupère l'adresse mail smtp pour l'envoi
        $site_name = App::getInstance()->getConfig()->get('title');
        $smtp_email = App::getInstance()->getConfig()->get('smtp_email');
        $url_http = App::getInstance()->getConfig()->get('url_http');
        // Utilise l'encodage interne UTF-8
        mb_internal_encoding("UTF-8");
        $newline = "\r\n";
        // Déclaration des messages au format texte et au format HTML
        ob_start();
        require ROOT . '/app/views/members/emails/confirmation-email-txt.php';
        $txt_message = ob_get_clean();
        ob_start();
        require ROOT . '/app/views/members/emails/confirmation-email-html.php';
        $html_message = ob_get_clean();
        // Création de la boundary
        $boundary = md5(rand());
        // Définition du sujet
        $subject = $site_name . ' - Confirmation de votre adresse e-mail';
        // Création du header de l'e-mail
        $header = 'From: ' . mb_encode_mimeheader($site_name) . ' <' . mb_encode_mimeheader($smtp_email) . '>' . $newline;
        $header.= 'Reply-to: ' . mb_encode_mimeheader($site_name) . ' <' . mb_encode_mimeheader($smtp_email) . '>' . $newline;
        $header.= 'MIME-Version: 1.0' . $newline;
        $header.= 'Content-Type: multipart/alternative; boundary=' . $boundary . $newline;
        // Création du message
        $message = $newline . '--' . $boundary . $newline;
        // Ajout du message au format texte
        $message.= 'Content-Type: text/plain; charset=UTF-8' . $newline;
        $message.= 'Content-Transfer-Encoding: 8bit' . $newline;
        $message.= $newline . $txt_message . $newline;
        $message.= $newline . '--' . $boundary . $newline;
        // Ajout du message au format HTML
        $message.= 'Content-Type: text/html; charset=UTF-8' . $newline;
        $message.= 'Content-Transfer-Encoding: 8bit' . $newline;
        $message.= $newline . $html_message . $newline;
        $message.= $newline . '--' . $boundary . '--' . $newline;
        $message.= $newline . '--' . $boundary . '--' . $newline;

        // Envoi de l'e-mail
        mail($email, $subject, $message, $header);
    }

    /**
     * Page : Formulaire de mot de passe oublié
     */
    public function forgottenPassword()
    {
        // On vérifie que l'utilisateur n'est pas connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si il l'est
        if($auth->logged('front')) {
            // On le redirige vers la page d'accueil
            header('Location: /');
        }

        $errors = array();
        // Le formulaire a été posté
        if(isset($_POST['email']))
        {
            if($_POST['email'] == null) // L'e-mail n'a pas été rempli
            {
                $errors['email'] = 'Ce champ est obligatoire.';
            }
            elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) // Le format de l'email n'est pas bon
            {
                $errors['email'] = 'L\'adresse e-mail n\'est pas valide';
            }

            // Le formulaire a été correctement rempli
            if(empty($errors))
            {
                // On récupère le membre correspondant à l'email
                $member = $this->Members->getMemberByEmail($_POST['email']);
                if($member)
                {
                    // On envoie un mail pour vérifier la validité de la demande
                    $this->sendForgottenPasswordEmail($_POST['email'], $member->firstname, $member->activation_key);
                    // Et on affiche la page de confirmation
                    $email = $_POST['email'];
                    // Préparation de la page
                    $page = new Page(array(
                        'title' => 'Mot de passe oublié',
                        'class_body' => 'forgotten-password'
                    ));
                    // Rendu du contenu
                    $variables = compact('email');
                    $content = $this->render('members/forgotten-password/confirmation.php', $variables);
                    // Rendu de la page
                    echo $page->render($content);
                }
                else
                {
                    $errors['email'] = 'Cette adresse e-mail n\'est utilisée par aucun membre';
                }
            }
        }
        // Si aucun membre n'a été créé
        if(!isset($member) || !$member) {
            // On affiche le formulaire
            // Préparation de la page
            $page = new Page(array(
                'title' => 'Mot de passe oublié',
                'class_body' => 'forgotten-password'
            ));
            // Rendu du contenu
            $variables = compact('errors');
            $content = $this->render('members/forgotten-password/forgotten_form.php', $variables);
            // Rendu de la page
            echo $page->render($content);
        }

    }

    /**
     * Page : Formulaire de mot de passe oublié
     */
    public function regeneratePassword()
    {
        // On vérifie que l'utilisateur n'est pas connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si il l'est
        if($auth->logged('front')) {
            // On le redirige vers la page d'accueil
            header('Location: /');
        }

        $errors = array();
        // Récupération des variables nécessaires à l'activation
        $email = $_GET['email'];
        $key = $_GET['key'];
        // On vérifie que la clé correspond bien au membre rattaché à cet e-mail
        $member = $this->Members->getMemberByEmail($_GET['email']);
        // Si on a bien un membre rattaché à l'email et que la clé correspond
        if($member && $member->activation_key == $key) {
            // On vérifie que le formulaire a bien été rempli
            if(isset($_POST['password'], $_POST['password_confirmation']))
            {
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

                // Le formulaire a été correctement rempli
                if(empty($errors)) {
                    // On modifie le mot de passe de ce membre
                    $edited_member = $this->Members->editMember($member->id, null, null, null, $_POST['password'], null);
                    // Si le mot de passe a bien été modifié en bdd
                    if($edited_member) {
                        $_SESSION['success'] = 'Votre mot de passe a bien été modifié.';
                        // Si le compte du membre a été activé
                        if($member->active) {
                            // On connecte directement le membre
                            $_SESSION['success'] .= ' Vous êtes désormais connecté.';
                                // Enregistrement des informations du membre en session
                            session_start();
                            $_SESSION['front']['id'] = $member->id;
                            $_SESSION['front']['lastname'] = $member->lastname;
                            $_SESSION['front']['firstname'] = $member->firstname;
                            $_SESSION['front']['email'] = $member->email;
                            $_SESSION['front']['picture'] = $member->picture;
                            // Puis redirection vers la page de profil
                            header('Location: /members/profile/' . $member->id);
                            die();
                        }
                    }
                }
            }
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Mot de passe oublié',
            'class_body' => 'forgotten-password'
        ));
        // Rendu du contenu
        $variables = compact('errors', 'email', 'key');
        $content = $this->render('members/forgotten-password/regenerate_form.php', $variables);
        // Rendu de la page
        echo $page->render($content);
    }

    /**
     * Envoi le mail suite à la demande de réinitialisation du mot de passe
     * @param string $email E-mail du membre
     * @param string $firstname Prénom du membre
     * @param string $activation_key Clé générée lors de l'inscription
     */
    private function sendForgottenPasswordEmail($email, $firstname, $activation_key)
    {
        // Récupère l'adresse mail smtp pour l'envoi
        $site_name = App::getInstance()->getConfig()->get('title');
        $smtp_email = App::getInstance()->getConfig()->get('smtp_email');
        $url_http = App::getInstance()->getConfig()->get('url_http');
        // Utilise l'encodage interne UTF-8
        mb_internal_encoding("UTF-8");
        $newline = "\r\n";
        // Déclaration des messages au format texte et au format HTML
        ob_start();
        require ROOT . '/app/views/members/emails/forgotten-password-email-txt.php';
        $txt_message = ob_get_clean();
        ob_start();
        require ROOT . '/app/views/members/emails/forgotten-password-email-html.php';
        $html_message = ob_get_clean();
        // Création de la boundary
        $boundary = md5(rand());
        // Définition du sujet
        $subject = $site_name . ' - Mot de passe oublié';
        // Création du header de l'e-mail
        $header = 'From: ' . mb_encode_mimeheader($site_name) . ' <' . mb_encode_mimeheader($smtp_email) . '>' . $newline;
        $header.= 'Reply-to: ' . mb_encode_mimeheader($site_name) . ' <' . mb_encode_mimeheader($smtp_email) . '>' . $newline;
        $header.= 'MIME-Version: 1.0' . $newline;
        $header.= 'Content-Type: multipart/alternative; boundary=' . $boundary . $newline;
        // Création du message
        $message = $newline . '--' . $boundary . $newline;
        // Ajout du message au format texte
        $message.= 'Content-Type: text/plain; charset=UTF-8' . $newline;
        $message.= 'Content-Transfer-Encoding: 8bit' . $newline;
        $message.= $newline . $txt_message . $newline;
        $message.= $newline . '--' . $boundary . $newline;
        // Ajout du message au format HTML
        $message.= 'Content-Type: text/html; charset=UTF-8' . $newline;
        $message.= 'Content-Transfer-Encoding: 8bit' . $newline;
        $message.= $newline . $html_message . $newline;
        $message.= $newline . '--' . $boundary . '--' . $newline;
        $message.= $newline . '--' . $boundary . '--' . $newline;

        // Envoi de l'e-mail
        mail($email, $subject, $message, $header);
    }

    /**
     * Action : Déconnecte l'utilisateur
     */
    public function logout()
    {
        // Suppression des variables de session et de la session
        $_SESSION['front'] = array();
        // Suppression des cookies de connexion automatique
        setcookie('front[email]', '');
        setcookie('front[password]', '');
        // Puis redirection vers la page d'accueil
        header('Location: /home');
    }

    /**
     * Page de profil
     */
    public function profile()
    {
        // En premier lieu, on vérifie que l'utilisateur est connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si on tente d'afficher la page sans être connecté
        if(!$auth->logged('front')) {
            header('Location: /members/login');
        }

        // Si l'id du membre n'existe pas, on redirige vers la page d'accueil
        if(!isset($_GET['member_id']))
        {
            header('Location: /home');
            die();
        }

        // On a bien un id membre
        // On recherche le membre correspondant à cet id
        $member = $this->Members->getMember($_GET['member_id']);
        // Si aucun membre n'a été trouvé, on redirige
        if(!$member)
        {
            header('Location: /home');
            die();
        }

        // On a trouvé un membre
        // Si le membre, c'est la personne connectée au site
        // on affiche la version du profil correspondant
        if($member->id == $_SESSION['front']['id'])
        {
            $errors = array();

            // Si le formulaire de recherche de membre a été posté
            if(!empty($_POST))
            {
                // On vérifie s'il contient des erreurs
                if(!isset($_POST['email']) || $_POST['email'] == null) // L'e-mail n'a pas été rempli
                {
                    $errors['search'] = 'Ce champ est obligatoire.';
                }
                elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])) // Le format de l'email n'est pas bon
                {
                    $errors['search'] = 'L\'adresse e-mail n\'est pas valide';
                }

                // Le formulaire a été correctement rempli
                if(empty($errors))
                {
                    $searched_member = $this->Members->getMemberByEmail($_POST['email']);
                    // Si on a trouvé un membre, on redirige
                    if($searched_member)
                    {
                        header('Location: /members/profile/' . $searched_member->id);
                        die();
                    }
                    // Si on a pas trouvé de membre
                    else
                    {
                        $errors['search'] = 'Aucun membre n\'a été trouvé';
                    }
                }
            }

            // Préparation de la page
            $page = new Page(array(
                'title' => $member->firstname . ' ' . $member->lastname,
                'class_body' => 'members-my-profile',
                'scripts' => array('my-profile.js')
            ));
            // Rendu du contenu
            $variables = compact('errors', 'member');
            $content = $this->render('members/my-profile/view.php', $variables);
            // Rendu de la page
            echo $page->render($content);
        }
        // Si le membre c'est quelqu'un d'autre
        // on affiche la version du profil correspondant
        else
        {
            // On récupère la collection
            $this->loadModel('Collections');
            $collection = $this->Collections->getCollection($member->id);
            $ranges = $collection->ranges;
            $minifigures_count = $collection->minifigures_count;
            $sets_count = $collection->sets_count;

            // Préparation de la page
            $page = new Page(array(
                'title' => $member->firstname . ' ' . $member->lastname,
                'class_body' => 'members-profile',
                'scripts' => array('profile.js')
            ));
            // Rendu du contenu
            $variables = compact(
                'member',
                'ranges',
                'minifigures_count',
                'sets_count'
            );
            $content = $this->render('members/profile.php', $variables);
            // Rendu de la page
            echo $page->render($content);
        }

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page d'édition du profil
     */
    public function edit()
    {
        $errors = array();

        // Si l'id du membre n'existe pas, on redirige vers la page d'accueil
        if(!isset($_SESSION['front']['id']))
        {
            header('Location: /home');
            die();
        }

        // On a bien un id membre
        // On recherche le membre correspondant à cet id
        $member = $this->Members->getMember($_SESSION['front']['id']);
        // Si aucun membre n'a été trouvé, on redirige
        if(!$member)
        {
            header('Location: /home');
            die();
        }

        // On a trouvé un membre, on poursuit
        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if(empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post')
        {
            $errors['picture'] = 'Fichier trop gros. (3Mo maximum)';
        }

        // Si le formulaire a été posté
        if(isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['city'], $_POST['region'], $_FILES['profile_picture'], $_POST['message']))
        {
            // On vérifie s'il contient des erreurs
            if($_POST['firstname'] == null) //Le champ nom n'a pas été rempli
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
                $edited_member = $this->Members->editMember($_SESSION['front']['id'], $_POST['lastname'], $_POST['firstname'], $_POST['email'], null, null, $_POST['city'], $_POST['region'], $_POST['message']);

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
                        Utilities::resizeAndSavePicture($_FILES['profile_picture']['name'], $_FILES['profile_picture']['tmp_name'], 96, 96, '/assets/img/members/', $_SESSION['front']['id'] . '-' . $member_alias);
                        // On update le membre en bdd avec le nom de la photo de profil
                        $this->Members->editMember($_SESSION['front']['id'], null, null, null, null, $_SESSION['front']['id'] . '-' . $member_alias . '.' . $extension);
                        // On met à jour l'information en session
                        $_SESSION['front']['picture'] = $_SESSION['front']['id'] . '-' . $member_alias . '.' . $extension;
                    }
                    // Aucune nouvelle photo n'a été uploadée
                    else
                    {
                        // Si on a choisi de supprimer la photo
                        if(isset($_POST['delete_picture']))
                        {
                            // Et ben on la supprime
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/members/' . $member->picture);
                            // Et on update le membre en bdd pour vider le nom de la photo
                            $this->Members->editMember($_SESSION['front']['id'], null, null, null, null, '');
                            // On met à jour l'information en session
                            $_SESSION['front']['picture'] = '';
                        }
                        // Si le nom du membre a changé et que ce membre a une photo de profil
                        elseif(($member->firstname != $_POST['firstname'] || $member->lastname != $_POST['lastname']) && $member->picture != '')
                        {
                            $extension = strtolower(substr(strrchr($member->picture, '.'), 1));
                            // On renomme la photo
                            rename ($_SERVER['DOCUMENT_ROOT'] . '/assets/img/members/' . $member->picture, $_SERVER['DOCUMENT_ROOT'] . '/assets/img/members/' . $_SESSION['front']['id'] . '-' . $member_alias . '.' . $extension);
                            // On update la gamme en bdd avec le nom de la photo
                            $this->Members->editMember($_SESSION['front']['id'], null, null, null, null, $_SESSION['front']['id'] . '-' . $member_alias . '.' . $extension);
                            // On met à jour l'information en session
                            $_SESSION['front']['picture'] = $_SESSION['front']['id'] . '-' . $member_alias . '.' . $extension;
                        }
                    }
                    // On met à jour les informations en session
                    $_SESSION['front']['lastname'] = $_POST['lastname'];
                    $_SESSION['front']['firstname'] = $_POST['firstname'];
                    $_SESSION['front']['email'] = $_POST['email'];


                    // On redirige vers la page de profil
                    $_SESSION['success'] = 'Votre profil a bien été mis à jour';
                    header('Location: /members/profile/' . $_SESSION['front']['id']);
                    die();
                }
                else
                {
                    $_SESSION['error'] = $edited_member;
                }
            }

        }

        // Liste des régions
        $regions = array(
            '',
            'Alsace-Champagne-Ardenne-Lorraine',
            'Aquitaine-Limousin-Poitou-Charentes',
            'Auvergne-Rhône-Alpes',
            'Bourgogne-Franche-Comté',
            'Bretagne',
            'Centre-Val de Loire',
            'Corse',
            'Île-de-France',
            'Languedoc-Roussillon-Midi-Pyrénées',
            'Nord-Pas-de-Calais-Picardie',
            'Normandie',
            'Pays de la Loire',
            'Provence-Alpes-Côte d\'Azur'
        );

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Editer mon profil',
            'class_body' => 'members-my-profile-edit'
        ));
        // Rendu du contenu
        $variables = compact('errors', 'member', 'regions');
        $content = $this->render('members/my-profile/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page de contact d'un membre
     */
    public function contact()
    {
        // En premier lieu, on vérifie que l'utilisateur est connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si on tente d'afficher la page sans être connecté
        if(!$auth->logged('front')) {
            header('Location: /members/login');
        }

        // Si l'id du membre n'existe pas, on redirige vers la page d'accueil
        if(!isset($_GET['member_id']))
        {
            header('Location: /home');
            die();
        }

        // On a bien un id membre
        // On recherche le membre correspondant à cet id
        $member = $this->Members->getMember($_GET['member_id']);
        // Si aucun membre n'a été trouvé, on redirige
        if(!$member)
        {
            header('Location: /home');
            die();
        }

        $errors = array();

        // Si le formulaire a été posté
        if(!empty($_POST))
        {
            // On vérifie qu'il n'y a pas d'erreurs
            if(!array_key_exists('message', $_POST) || $_POST['message'] == '') {
                $_SESSION['error'] = 'Erreur lors de la soumission du formulaire';
                $errors['message'] = 'Vous n\'avez pas renseigné votre message';
            }

            // S'il n'y a pas d'erreurs
            if(empty($errors))
            {
                // On envoie le mail
                if($this->sendEmailContact($member->email, $_SESSION['front']['lastname'], $_SESSION['front']['firstname'], $_SESSION['front']['email'], $_POST['message'])) {
                    $_SESSION['success'] = 'Votre message a bien été envoyé';
                    header('Location: /members/profile/' . $member->id);
                    die();
                }
                else
                {
                    $_SESSION['error'] = 'Votre message n\'a pas pu être envoyé';
                }
            }
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Contacter ' . $member->firstname . ' ' . $member->lastname,
            'class_body' => 'members-contact'
        ));
        // Rendu du contenu
        $variables = compact('errors', 'member');
        $content = $this->render('members/contact.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Envoi du mail pour contacter un membre
     * @param string $receiver_email Email du destinataire
     * @param string $lastname Nom de l'expéditeur
     * @param string $firstname Prénom de l'expéditeur
     * @param string $email Email de l'expéditeur
     * @param string $message Message de l'expéditeur
     */
    private function sendEmailContact($receiver_email, $lastname, $firstname, $email, $message)
    {
        // Récupère l'adresse mail smtp pour l'envoi
        $site_name = App::getInstance()->getConfig()->get('title');
        $smtp_email = App::getInstance()->getConfig()->get('smtp_email');
        // Utilise l'encodage interne UTF-8
        mb_internal_encoding("UTF-8");
        $newline = "\r\n";
        // Déclaration du message au format texte
        ob_start();
        require ROOT . '/app/views/members/emails/contact-member-txt.php';
        $txt_message = ob_get_clean();
        // Conversion des sauts de ligne pour la version HTML
        $message = nl2br($message);
        // Déclaration du message au format HTML
        ob_start();
        require ROOT . '/app/views/members/emails/contact-member-html.php';
        $html_message = ob_get_clean();
        // Création de la boundary
        $boundary = md5(rand());
        // Définition du sujet
        $subject = $site_name . ' - Un membre vient de vous envoyer un message';
        // Création du header de l'e-mail
        $header = 'From: ' . mb_encode_mimeheader($firstname . ' ' . $lastname) . ' <' . mb_encode_mimeheader($smtp_email) . '>' . $newline;
        $header.= 'Reply-to: ' . $firstname . ' ' . $lastname . ' <' . $email . '>' . $newline;
        $header.= 'MIME-Version: 1.0' . $newline;
        $header.= 'Content-Type: multipart/alternative; boundary=' . $boundary . $newline;
        // Création du message
        $message = $newline . '--' . $boundary . $newline;
        // Ajout du message au format texte
        $message.= 'Content-Type: text/plain; charset=UTF-8' . $newline;
        $message.= 'Content-Transfer-Encoding: 8bit' . $newline;
        $message.= $newline . $txt_message . $newline;
        $message.= $newline . '--' . $boundary . $newline;
        // Ajout du message au format HTML
        $message.= 'Content-Type: text/html; charset=UTF-8' . $newline;
        $message.= 'Content-Transfer-Encoding: 8bit' . $newline;
        $message.= $newline . $html_message . $newline;
        $message.= $newline . '--' . $boundary . '--' . $newline;
        $message.= $newline . '--' . $boundary . '--' . $newline;

        // Envoi de l'e-mail
        return mail($receiver_email, $subject, $message, $header);
    }

}