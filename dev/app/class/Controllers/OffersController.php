<?php
namespace App\Controllers;
use App;
use App\Pages\Page;
use Core\Auth\DbAuth;
use Core\Utilities\Utilities;

/**
 * Contrôleur dédié aux annonces
 */
class OffersController extends AppController
{

    /**
     * Charge les modèles nécessaires
     */
    public function __construct() {
        parent::__construct();
        $this->loadModel('Offers');
    }

    /**
     * Page : Liste des annonces
     */
    public function listing()
    {
        // Si on consulte les annonces d'un élément précis
        if(isset($_GET['id'], $_GET['type'])) {
            // On récupère les informations de l'élément
            if($_GET['type'] == 'minifigure') {
                $this->loadModel('Minifigures');
                $item = $this->Minifigures->getMinifigure($_GET['id']);
                $item->type = $_GET['type'];
            } elseif($_GET['type'] == 'set') {
                $this->loadModel('Sets');
                $item = $this->Sets->getSet($_GET['id']);
                $item->type = $_GET['type'];
            }
        }

        $offers_per_page = 2;
        // On récupère le nombre de figurines
        if(isset($item)) {
            $offers_number = $this->Offers->countWithItem($item->id, $item->type);
        } else {
            $offers_number = $this->Offers->count();
        }

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
        if(isset($item)) {
            $offers_list = $this->Offers->someWithItem($item->id, $item->type, $first_entry, $offers_per_page);
        } else {
            $offers_list = $this->Offers->some($first_entry, $offers_per_page);
        }

        // On présente les données avant de les passer à la vue
        foreach($offers_list as $offer) {
            $offer->price = str_replace(',00', '', str_replace('.', ',', $offer->price));
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Toutes les annonces',
            'class_body' => 'offers'
        ));
        // Rendu du contenu
        $variables = compact(
            'item',
            'offers_list',
            'offers_number',
            'current_page',
            'pages_number',
            'today_date'
        );
        $content = $this->render('offers/listing.php', $variables);
        // Rendu de la page
        echo $page->render($content);
    }

    /**
     * Page : Vue d'une annonce
     */
    public function view()
    {
        // Si l'id de l'annonce n'existe pas, on affiche la liste des annonces
        if(!isset($_GET['id']))
        {
            header('Location: /offers/listing');
            die();
        }
        // Si on est là, c'est qu'on a un id annonce
        // On recherche l'annonce correspondant à l'id
        $offer = $this->Offers->getOffer($_GET['id']);
        // Si aucune annonce n'a été trouvée on affiche la liste des annonces
        if(!$offer)
        {
            header('Location: /offers/listing');
            die();
        }
        // On présente les données avant de les passer à la vue
        $offer->description = nl2br($offer->description);
        $offer->price = str_replace(',00', '', str_replace('.', ',', $offer->price));

        // On recherche les autres annonces du membre
        $other_offers = $this->Offers->getOffersByMember($offer->member_id);
        // On présente les données avant de les passer à la vue
        foreach($other_offers as $other_offer) {
            $other_offer->price = str_replace(',00', '', str_replace('.', ',', $other_offer->price));
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Titre de l\'annonce',
            'class_body' => 'offers-view',
            'librairies_styles' => array('slick/slick.css'),
            'librairies_scripts' => array('slick/slick.min.js'),
            'scripts' => array('offers.js')
        ));
        // Rendu du contenu
        $variables = compact('offer', 'other_offers');
        $content = $this->render('offers/view.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page : Ajout d'une annonce
     */
    public function add()
    {

        // En premier lieu, on vérifie que l'utilisateur est connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si on tente d'afficher une page sans être connecté
        if(!$auth->logged('front')) {
            header('Location: /members/login');
        }

        // On charge les modèles nécessaires
        $this->loadModel('Sets');
        $this->loadModel('Minifigures');

        $errors = array();
        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $errors['pictures'] = 'Fichier trop gros. (3Mo maximum)';
        }
        // Le formulaire a été posté
        if (!empty($_POST)) {

            if(!isset($_POST['type_sell']) && !isset($_POST['type_exchange'])) // Aucun champ type n'a pas été rempli
            {
                $errors['type'] = 'Ce champ est obligatoire.';
            }

            if(isset($_POST['title']) && $_POST['title'] == null) // Le champ titre n'a pas été rempli
            {
                $items_number = 0;
                // On vérifie que l'annonce contient plusieurs éléments
                if(isset($_POST['sets'])) {
                    $items_number += count($_POST['sets']);
                }
                if(isset($_POST['minifigures'])) {
                    $items_number += count($_POST['minifigures']);
                }
                // Si c'est le cas, il y a erreur
                if($items_number > 1) {
                    $errors['title'] = 'Ce champ est obligatoire.';
                }
            }

            if(isset($_POST['price']) && $_POST['price'] != null && (!preg_match('#^[0-9]{1,3},[0-9]{2}$#', $_POST['price']) && !preg_match('#^[0-9]{1,3}$#', $_POST['price']))) // Le champ prix n'est pas au bon format
            {
                $errors['price'] = 'Le prix n\'est pas au bon format.';
            }

            if(!isset($_POST['id'], $_POST['type'])) {
                if(!isset($_POST['sets']) && !isset($_POST['minifigures'])) // Les champs sets et minifigures n'ont pas été remplis
                {
                    $errors['items'] = 'L\'annonce doit comporter au moins un set ou une figurine.';
                }
            }

            if (isset($_FILES['pictures'])) {
                foreach ($_FILES['pictures']['name'] as $key => $value) {
                    if ($_FILES['pictures']['size'][$key] != 0) // Si un visuel est envoyé
                    {
                        if ($_FILES['pictures']['error'][$key] > 0) // Erreur d'upload du visuel
                        {
                            $errors['pictures'] = 'Erreur lors du transfert.';
                        } elseif ($_FILES['pictures']['size'][$key] > App::getInstance()->getConfig()->get('max_file_size')) // Si le fichier est trop gros
                        {
                            $errors['pictures'] = 'Fichier trop gros. (3Mo maximum)';
                        } else {
                            $extension = strtolower(substr(strrchr($_FILES['pictures']['name'][$key], '.'), 1));
                            if (!in_array($extension, App::getInstance()->getConfig()->get('img_authorized_extensions'))) {
                                $errors['pictures'] = 'Type de fichier non-autorisé. (seulement jpg, gif, png)';
                            }
                        }
                    }
                }
            }

            // Le formulaire a été correctement rempli
            if(empty($errors))
            {

                // On prépare les données avant enregistrement
                if(isset($_POST['id'], $_POST['type'])) {
                    // On récupère les informations de l'élément à vendre
                    if($_POST['type'] == 'minifigure') {
                        $item = $this->Minifigures->getMinifigure($_GET['id']);
                        $title = $item->name;
                    } elseif($_POST['type'] == 'set') {
                        $item = $this->Sets->getSet($_GET['id']);
                        $title = $item->name . ' (' . $item->number . ')';
                    }
                } else {
                    $title = $_POST['title'];
                    if($title == null) {
                        if(isset($_POST['sets'])) {
                            $set = $this->Sets->find($_POST['sets'][0]);
                            $title = $set->name . ' (' . $set->number . ')';
                        }
                        if(isset($_POST['minifigures'])) {
                            $minifigure = $this->Minifigures->find($_POST['minifigures'][0]);
                            $title = $minifigure->name;
                        }
                    }
                }

                if(isset($_POST['type_sell'], $_POST['type_exchange'])) {
                    $type = 'Vente/Echange';
                } elseif(isset($_POST['type_sell'])) {
                    $type = 'Vente';
                } else {
                    $type = 'Echange';
                }

                $price = str_replace(',', '.', $_POST['price']);

                if(isset($_POST['id'], $_POST['type'])) {
                    if($_POST['type'] == 'minifigure') {
                        $sets = null;
                        $minifigures = array($_POST['id']);
                        $minifigures_count = array($_POST['count']);
                    } elseif($_POST['type'] == 'minifigure') {
                        $sets = array($_POST['id']);
                        $minifigures = null;
                        $sets_count = array($_POST['count']);
                    }
                } else {
                    $sets = (isset($_POST['sets'])) ? $_POST['sets'] : null;
                    $sets_count = (isset($_POST['sets_count'])) ? $_POST['sets_count'] : null;
                    $minifigures = (isset($_POST['minifigures'])) ? $_POST['minifigures'] : null;
                    $minifigures_count = (isset($_POST['minifigures_count'])) ? $_POST['minifigures_count'] : null;
                }

                if($sets != null) {
                    $sets_to_save = array();
                    $i = 0;
                    foreach ($sets as $set_id) {
                        $sets_to_save[$set_id] = $sets_count[$i];
                        $i++;
                    }
                }

                if($minifigures != null) {
                    $minifigures_to_save = array();
                    $i = 0;
                    foreach ($minifigures as $minifigure_id) {
                        $minifigures_to_save[$minifigure_id] = $minifigures_count[$i];
                        $i++;
                    }
                }

                // On enregistre l'annonce
                $offer_id = $this->Offers->add($title, $_POST['description'], $type, $price, $sets_to_save, $minifigures_to_save, $_SESSION['front']['id']);
                // Si l'enregistrement de l'annonce à généré une erreur, on l'affiche
                if(!is_int($offer_id)) {
                    $_SESSION['error'] = 'L\'annonce n\'a pas pu être créée.';
                }
                // L'enregistrement s'est bien déroulé
                else
                {
                    // Si un visuel a été uploadé
                    foreach($_FILES['pictures']['name'] as $key => $value) {
                        if ($_FILES['pictures']['size'][$key] != 0 && $_FILES['pictures']['error'][$key] == 0)
                        {
                            // On le redimensionne et on l'enregistre
                            Utilities::resizeAndSavePicture($_FILES['pictures']['name'][$key], $_FILES['pictures']['tmp_name'][$key], 57, 57, '/assets/img/offers/57x57/', $offer_id . '-picture-' . ($key + 1));
                            Utilities::resizeAndSavePicture($_FILES['pictures']['name'][$key], $_FILES['pictures']['tmp_name'][$key], 96, 96, '/assets/img/offers/96x96/', $offer_id . '-picture-' . ($key + 1));
                            Utilities::resizeAndSavePicture($_FILES['pictures']['name'][$key], $_FILES['pictures']['tmp_name'][$key], 400, 300, '/assets/img/offers/400x300/', $offer_id . '-picture-' . ($key + 1), true);

                            // On enregistre les visuels en bdd
                            $extension = strtolower(substr(strrchr($_FILES['pictures']['name'][$key], '.'), 1));
                            $savedPicture = $this->Offers->savePicture($offer_id, $offer_id . '-picture-' . ($key + 1) . '.' . $extension);

                            if(!$savedPicture) {
                                $_SESSION['error'] = 'Erreur lors de l\'enregistrement du visuel';
                            }
                        }
                    }

                    // On redirige vers l'annonce
                    $_SESSION['success'] = 'Votre annonce a bien été créée. Celle-ci est dès à présent visible sur le site.';

                    header('Location: /offers/view?id=' . $offer_id);
                    die();
                }
            }
            // Si il y a des erreurs
            else {
                if(isset($_POST['sets'])) {
                    $selected_sets = array();
                    foreach ($_POST['sets'] as $set) {
                        $selected_sets[] = $this->Sets->getSet($set);
                    }
                }
                if(isset($_POST['minifigures'])) {
                    $selected_minifigures = array();
                    foreach ($_POST['minifigures'] as $minifigure) {
                        $selected_minifigures[] = $this->Minifigures->getMinifigure($minifigure);
                    }
                }
            }
        }

        // On récupère l'ensemble des sets et des figurines
        $sets_list = $this->Sets->allOrderedByName();
        $minifigures_list = $this->Minifigures->allOrderedByName();
        // On récupère les infos du membre
        $this->loadModel('Members');
        $member = $this->Members->getMember($_SESSION['front']['id']);
        // Si on passe une annonce pour un élément précis
        if(isset($_GET['id'], $_GET['type'])) {
            // On récupère les informations de l'élément à vendre
            if($_GET['type'] == 'minifigure') {
                $item = $this->Minifigures->getMinifigure($_GET['id']);
                $item->type = $_GET['type'];
            } elseif($_GET['type'] == 'set') {
                $item = $this->Sets->getSet($_GET['id']);
                $item->type = $_GET['type'];
            }
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Déposer une annonce',
            'class_body' => 'offers-add',
            'scripts' => array('offers.js')
        ));
        // Rendu du contenu
        $variables = compact('errors', 'sets_list', 'minifigures_list', 'selected_sets', 'selected_minifigures', 'member', 'item');
        $content = $this->render('offers/add.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Page : Edition d'une annonce
     */
    public function edit()
    {

        // En premier lieu, on vérifie que l'utilisateur est connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si on tente de supprimer une annonce sans être connecté
        if(!$auth->logged('front')) {
            header('Location: /members/login');
        }

        // Si l'id de l'annonce n'existe pas, on affiche la collection
        if(!isset($_GET['id']))
        {
            header('Location: /collection');
            die();
        }
        // Si on est là, c'est qu'on a un id annonce
        // On recherche l'annonce correspondant à l'id
        $offer = $this->Offers->getOffer($_GET['id']);
        // Si aucune annonce n'a été trouvée on affiche la collection
        if(!$offer)
        {
            header('Location: /collection');
            die();
        }
        // On présente les données avant de les passer à la vue
        $offer->price = str_replace(',00', '', str_replace('.', ',', $offer->price));
        if(!empty($offer->minifigures)) {
            $selected_minifigures = $offer->minifigures;
        };
        if(!empty($offer->sets)) {
           $selected_sets = $offer->sets;
        };

        // On charge les modèles nécessaires
        $this->loadModel('Sets');
        $this->loadModel('Minifigures');

        $errors = array();

        // Si une photo a été uploadée mais que sa taille est plus grande que la valeur maximum autorisée en post par php
        if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $errors['pictures'] = 'Fichier trop gros. (3Mo maximum)';
        }
        // Le formulaire a été posté
        if (!empty($_POST)) {

            if(!isset($_POST['type_sell']) && !isset($_POST['type_exchange'])) // Aucun champ type n'a pas été rempli
            {
                $errors['type'] = 'Ce champ est obligatoire.';
            }

            if(isset($_POST['title']) && $_POST['title'] == null) // Le champ titre n'a pas été rempli
            {
                $items_number = 0;
                // On vérifie que l'annonce contient plusieurs éléments
                if(isset($_POST['sets'])) {
                    $items_number += count($_POST['sets']);
                }
                if(isset($_POST['minifigures'])) {
                    $items_number += count($_POST['minifigures']);
                }
                // Si c'est le cas, il y a erreur
                if($items_number > 1) {
                    $errors['title'] = 'Ce champ est obligatoire.';
                }
            }

            if(isset($_POST['price']) && $_POST['price'] != null && (!preg_match('#^[0-9]{1,3},[0-9]{2}$#', $_POST['price']) && !preg_match('#^[0-9]{1,3}$#', $_POST['price']))) // Le champ prix n'est pas au bon format
            {
                $errors['price'] = 'Le prix n\'est pas au bon format.';
            }

            if(!isset($_POST['id'], $_POST['type'])) {
                if(!isset($_POST['sets']) && !isset($_POST['minifigures'])) // Les champs sets et minifigures n'ont pas été remplis
                {
                    $errors['items'] = 'L\'annonce doit comporter au moins un set ou une figurine.';
                }
            }

            if (isset($_FILES['pictures'])) {
                foreach ($_FILES['pictures']['name'] as $key => $value) {
                    if ($_FILES['pictures']['size'][$key] != 0) // Si un visuel est envoyé
                    {
                        if ($_FILES['pictures']['error'][$key] > 0) // Erreur d'upload du visuel
                        {
                            $errors['pictures'] = 'Erreur lors du transfert.';
                        } elseif ($_FILES['pictures']['size'][$key] > App::getInstance()->getConfig()->get('max_file_size')) // Si le fichier est trop gros
                        {
                            $errors['pictures'] = 'Fichier trop gros. (3Mo maximum)';
                        } else {
                            $extension = strtolower(substr(strrchr($_FILES['pictures']['name'][$key], '.'), 1));
                            if (!in_array($extension, App::getInstance()->getConfig()->get('img_authorized_extensions'))) {
                                $errors['pictures'] = 'Type de fichier non-autorisé. (seulement jpg, gif, png)';
                            }
                        }
                    }
                }
            }

            // Le formulaire a été correctement rempli
            if(empty($errors))
            {

                // On prépare les données avant enregistrement
                $items_number = 0;
                if(isset($_POST['sets'])) {
                    $items_number += count($_POST['sets']);
                }
                if(isset($_POST['minifigures'])) {
                    $items_number += count($_POST['minifigures']);
                }
                if($items_number > 1) {
                    $title = $_POST['title'];
                } else {
                    if(isset($_POST['sets'])) {
                        $set = $this->Sets->find($_POST['sets'][0]);
                        $title = $set->name . ' (' . $set->number . ')';
                    }
                    if(isset($_POST['minifigures'])) {
                        $minifigure = $this->Minifigures->find($_POST['minifigures'][0]);
                        $title = $minifigure->name;
                    }
                }

                if(isset($_POST['type_sell'], $_POST['type_exchange'])) {
                    $type = 'Vente/Echange';
                } elseif(isset($_POST['type_sell'])) {
                    $type = 'Vente';
                } else {
                    $type = 'Echange';
                }

                $price = str_replace(',', '.', $_POST['price']);

                $sets = (isset($_POST['sets'])) ? $_POST['sets'] : null;
                $sets_count = (isset($_POST['sets_count'])) ? $_POST['sets_count'] : null;
                $minifigures = (isset($_POST['minifigures'])) ? $_POST['minifigures'] : null;
                $minifigures_count = (isset($_POST['minifigures_count'])) ? $_POST['minifigures_count'] : null;


                if($sets != null) {
                    $sets_to_save = array();
                    $i = 0;
                    foreach ($sets as $set_id) {
                        $sets_to_save[$set_id] = $sets_count[$i];
                        $i++;
                    }
                }

                if($minifigures != null) {
                    $minifigures_to_save = array();
                    $i = 0;
                    foreach ($minifigures as $minifigure_id) {
                        $minifigures_to_save[$minifigure_id] = $minifigures_count[$i];
                        $i++;
                    }
                }

                // On modifie l'annonce
                $edited_offer = $this->Offers->edit($offer->id, $title, $_POST['description'], $type, $price, $sets_to_save, $minifigures_to_save);

                // L'enregistrement s'est bien déroulé
                if($edited_offer === true)
                {

                    // On vérifie que des photos doivent être supprimées
                    $pictures_saved = 0;
                    if(isset($_POST['delete_picture'])) {
                        foreach ($_POST['delete_picture'] as $key => $filename) {
                            // On supprime l'ancienne image du serveur...
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/offers/57x57/' . $filename);
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/offers/96x96/' . $filename);
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/offers/400x300/' . $filename);
                            // ... et de la bdd
                            $this->Offers->removePicture($offer->id, $filename);
                            // Si de nouvelles photos ont été uploadées
                            if ($_FILES['pictures']['size'][$key] != 0 && $_FILES['pictures']['error'][$key] == 0) {
                                // On la redimensionne et on l'enregistre sur le serveur...
                                Utilities::resizeAndSavePicture($_FILES['pictures']['name'][$key], $_FILES['pictures']['tmp_name'][$key], 57, 57, '/assets/img/offers/57x57/', $offer->id . '-picture-' . ($key + 1));
                                Utilities::resizeAndSavePicture($_FILES['pictures']['name'][$key], $_FILES['pictures']['tmp_name'][$key], 96, 96, '/assets/img/offers/96x96/', $offer->id . '-picture-' . ($key + 1));
                                Utilities::resizeAndSavePicture($_FILES['pictures']['name'][$key], $_FILES['pictures']['tmp_name'][$key], 400, 300, '/assets/img/offers/400x300/', $offer->id . '-picture-' . ($key + 1), true);
                                // ... et en bdd
                                $extension = strtolower(substr(strrchr($_FILES['pictures']['name'][$key], '.'), 1));
                                $savedPicture = $this->Offers->savePicture($offer->id, $offer->id . '-picture-' . ($key + 1) . '.' . $extension);

                                if (!$savedPicture) {
                                    $_SESSION['error'] = 'Erreur lors de l\'enregistrement du visuel';
                                }
                            }
                            $pictures_saved = $key + 1;
                        }
                    }
                    // Pour chaque photos uploadées
                    foreach($_FILES['pictures']['name'] as $key => $value) {
                        if ($_FILES['pictures']['size'][$key] != 0 && $_FILES['pictures']['error'][$key] == 0)
                        {
                            // On passe celles qui ont été traitées au moment de la suppression des photos
                            if($key <= ($pictures_saved - 1)) {
                                continue;
                            }
                            if ($_FILES['pictures']['size'][$key] != 0 && $_FILES['pictures']['error'][$key] == 0) {
                                // On redimensionne et on enregistre l'image sur le serveur ...
                                Utilities::resizeAndSavePicture($_FILES['pictures']['name'][$key], $_FILES['pictures']['tmp_name'][$key], 57, 57, '/assets/img/offers/57x57/', $offer->id . '-picture-' . ($key + 1));
                                Utilities::resizeAndSavePicture($_FILES['pictures']['name'][$key], $_FILES['pictures']['tmp_name'][$key], 96, 96, '/assets/img/offers/96x96/', $offer->id . '-picture-' . ($key + 1));
                                Utilities::resizeAndSavePicture($_FILES['pictures']['name'][$key], $_FILES['pictures']['tmp_name'][$key], 400, 300, '/assets/img/offers/400x300/', $offer->id . '-picture-' . ($key + 1), true);
                                // ... et en bdd
                                $extension = strtolower(substr(strrchr($_FILES['pictures']['name'][$key], '.'), 1));
                                $savedPicture = $this->Offers->savePicture($offer->id, $offer->id . '-picture-' . ($key + 1) . '.' . $extension);

                                if(!$savedPicture) {
                                    $_SESSION['error'] = 'Erreur lors de l\'enregistrement du visuel';
                                }
                            }
                        }
                    }

                    // On redirige vers l'annonce
                    $_SESSION['success'] = 'Votre annonce a bien été modifiée.';

                    header('Location: /offers/view?id=' . $offer->id);
                    die();
                }
                // Si l'enregistrement de l'annonce à généré une erreur, on l'affiche
                else {
                    $_SESSION['error'] = 'L\'annonce n\'a pas pu être modifiée.';
                }
            }
            // Si il y a des erreurs
            else {
                if(isset($_POST['sets'])) {
                    $selected_sets = array();
                    foreach ($_POST['sets'] as $set) {
                        $selected_sets[] = $this->Sets->getSet($set);
                    }
                }
                if(isset($_POST['minifigures'])) {
                    $selected_minifigures = array();
                    foreach ($_POST['minifigures'] as $minifigure) {
                        $selected_minifigures[] = $this->Minifigures->getMinifigure($minifigure);
                    }
                }
            }
        }

        // On récupère l'ensemble des sets et des figurines
        $sets_list = $this->Sets->allOrderedByName();
        $minifigures_list = $this->Minifigures->allOrderedByName();
        // On récupère les infos du membre
        $this->loadModel('Members');
        $member = $this->Members->getMember($_SESSION['front']['id']);

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Editer une annonce',
            'class_body' => 'offers-edit',
            'scripts' => array('offers.js')
        ));
        // Rendu du contenu
        $variables = compact('errors', 'sets_list', 'minifigures_list', 'selected_sets', 'selected_minifigures', 'member', 'item', 'offer');
        $content = $this->render('offers/edit.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_POST);
        unset($_SESSION['error']);
        unset($_SESSION['success']);

    }

    /**
     * Action : Suppression d'une annonce
     */
    public function delete()
    {

        // En premier lieu, on vérifie que l'utilisateur est connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si on tente de supprimer une annonce sans être connecté
        if(!$auth->logged('front')) {
            header('Location: /members/login');
        }

        // Si l'id de l'annonce n'existe pas, on affiche la collection
        if(!isset($_GET['id']))
        {
            header('Location: /collection');
            die();
        }
        // Si on a bien un id d'annonce
        else
        {
            // On recherche l'annonce correspondant à l'id
            $offer = $this->Offers->getOffer($_GET['id']);
            // Si aucune annonce n'a été trouvée on affiche la collection
            if(!$offer)
            {
                header('Location: /collection');
                die();
            }
            // Si on a trouvé une annonce en bdd
            else
            {
                // On supprime l'annonce correspondant à l'id
                $deleted = $this->Offers->delete($_GET['id']);
                // Si la suppression n'a pas été effectuée on affiche la collection
                if(!$deleted)
                {
                    $_SESSION['error'] = 'L\'annonce n\'a pas pu être supprimée.';
                    header('Location: /collection');
                    die();
                }
                // Si la suppression a été effectuée
                else
                {
                    // Si des visuels existent
                    if (!empty($offer->pictures))
                    {
                        // On les supprime
                        foreach($offer->pictures as $picture)
                        {
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/offers/57x57/' . $picture->filename);
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/offers/96x96/' . $picture->filename);
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/assets/img/offers/400x300/' . $picture->filename);
                        }
                    }
                    // On redirige vers la collection avec le message de confirmation
                    $_SESSION['success'] = 'L\'annonce "' . $offer->title . '" a bien été supprimée';
                    header('Location: /collection');
                    die();
                }
            }
        }

    }

    /**
     * Ajax : Récupère une figurine/set en bdd
     */
    public function find()
    {
        if(isset($_GET['id'], $_GET['type'])) {
            // On charge les modèles nécessaires
            $this->loadModel('Sets');
            $this->loadModel('Minifigures');
            // On récupère les informations de l'élément à vendre
            if($_GET['type'] == 'minifigure') {
                $item = $this->Minifigures->getMinifigure($_GET['id']);
                $item->type = $_GET['type'];
            } elseif($_GET['type'] == 'set') {
                $item = $this->Sets->getSet($_GET['id']);
                $item->type = $_GET['type'];
            }
            echo json_encode($item);
            die();
        }

        echo json_encode(false);
        die();
    }

}
