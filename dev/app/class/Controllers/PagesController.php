<?php
namespace App\Controllers;
use App;
use App\Pages\Page;
use Core\Recaptcha\Recaptcha;

/**
 * Contrôleur dédié aux pages de contenu
 */
class PagesController extends AppController
{

    /**
     * Définie le chemin vers les vues et charge les modèles nécessaires
     */
    public function __construct()
    {
        parent::__construct();
        // $this->loadModel('Members');
    }

    /**
     * Page : Formulaire de contact
     */
    public function contact()
    {
        $errors = array();

        // Si le formulaire a été posté
        if(!empty($_POST))
        {
            // On vérifie qu'il n'y a pas d'erreurs
            if(!array_key_exists('lastname', $_POST) || $_POST['lastname'] == '') {
                $_SESSION['error'] = 'Erreur lors de la soumission du formulaire';
                $errors['lastname'] = 'Vous n\'avez pas renseigné votre nom';
            }

            if(!array_key_exists('firstname', $_POST) || $_POST['firstname'] == '') {
                $_SESSION['error'] = 'Erreur lors de la soumission du formulaire';
                $errors['firstname'] = 'Vous n\'avez pas renseigné votre prénom';
            }

            if(!array_key_exists('email', $_POST) || $_POST['email'] == '') {
                $_SESSION['error'] = 'Erreur lors de la soumission du formulaire';
                $errors['email'] = 'Vous n\'avez pas renseigné votre email';
            }
            elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Erreur lors de la soumission du formulaire';
                $errors['email'] = 'Vous n\'avez pas renseigné un email valide';
            }

            if(!array_key_exists('message', $_POST) || $_POST['message'] == '') {
                $_SESSION['error'] = 'Erreur lors de la soumission du formulaire';
                $errors['message'] = 'Vous n\'avez pas renseigné votre message';
            }

            $captcha = new Recaptcha(App::getInstance()->getConfig()->get('recaptcha_key'));
            if($captcha->isValid($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']) === false) {
                $_SESSION['error'] = 'Erreur lors de la soumission du formulaire';
                $errors['captcha'] = 'Le captcha n\'est pas valide';
            }

            // S'il n'y a pas d'erreurs
            if(empty($errors))
            {
                // On envoie le mail
                if($this->sendEmailContact(App::getInstance()->getConfig()->get('email_webmaster'), $_POST['lastname'], $_POST['firstname'], $_POST['email'], $_POST['message'])) {
                    $_SESSION['success'] = 'Votre message a bien été envoyé';
                }
                else
                {
                    $_SESSION['error'] = 'Votre message n\'a pas pu être envoyé';
                }
            }

        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Contacter le webmaster',
            'class_body' => 'contact',
            'external_scripts' => array('https://www.google.com/recaptcha/api.js')
        ));
        // Rendu du contenu
        $variables = compact('errors');
        $content = $this->render('pages/contact.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Envoi du mail pour contacter le webmaster
     * @param string $receiver_email Email du destinataire
     * @param string $lastname Nom du visiteur
     * @param string $firstname Prénom du visiteur
     * @param string $email Email du visiteur
     * @param string $message Message du visiteur
     */
    private function sendEmailContact($receiver_email, $lastname, $firstname, $email, $message)
    {
        $site_name = App::getInstance()->getConfig()->get('title');
        // Utilise l'encodage interne UTF-8
        mb_internal_encoding("UTF-8");
        $newline = "\r\n";
        // Déclaration du message au format texte
        ob_start();
        require ROOT . '/app/views/pages/emails/contact-webmaster-txt.php';
        $txt_message = ob_get_clean();
        // Conversion des sauts de ligne pour la version HTML
        $message = nl2br($message);
        // Déclaration du message au format HTML
        ob_start();
        require ROOT . '/app/views/pages/emails/contact-webmaster-html.php';
        $html_message = ob_get_clean();
        // Création de la boundary
        $boundary = md5(rand());
        // Définition du sujet
        $subject = $site_name . ' - Un visiteur vient de vous envoyer un message';
        // Création du header de l'e-mail
        $header = 'From: ' . mb_encode_mimeheader($firstname . ' ' . $lastname) . ' <' . mb_encode_mimeheader($email) . '>' . $newline;
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