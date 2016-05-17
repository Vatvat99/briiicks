<?php
namespace App\Controllers;
use App;
use App\Pages\Page;
use Core\Auth\DbAuth;

/**
 * Contrôleur des collections
 */
class CollectionController extends AppController
{

    /**
     * Définie le chemin vers les vues, vérifie qu'on est connecté, et charge les modèles nécessaires
     */
    public function __construct()
    {
        parent::__construct();
        // Charge les modèles
        $this->loadModel('Collections');
    }

    /**
     * Page : Affiche la collection
     */
    public function index()
    {
        // En premier lieu, on vérifie que l'utilisateur est connecté
        $app = App::getInstance();
        $auth = new DbAuth($app->getDb());
        // Si on tente d'afficher une page sans être connecté
        if(!$auth->logged('front')) {
            header('Location: /members/login');
        }

        // On récupère la collection
        $collection = $this->Collections->getCollection($_SESSION['front']['id']);
        $ranges = $collection->ranges;
        $minifigures_count = $collection->minifigures_count;
        $sets_count = $collection->sets_count;

        // On récupère les annonces en cours
        $this->loadModel('Offers');
        $offers_list = $this->Offers->getOffersByMember($_SESSION['front']['id']);

        // On présente les données avant de les passer à la vue
        foreach($offers_list as $offer) {
            $offer->price = str_replace(',00', '', str_replace('.', ',', $offer->price));
        }

        // Préparation de la page
        $page = new Page(array(
            'title' => 'Ma collection',
            'class_body' => 'members-my-collection',
            'scripts' => array('collection.js')
        ));
        // Rendu du contenu
        $variables = compact(
            'ranges',
            'minifigures_count',
            'sets_count',
            'offers_list'
        );
        $content = $this->render('collection/index.php', $variables);
        // Rendu de la page
        echo $page->render($content);

        // On vide les données qu'on ne veut pas réafficher si on actualise la page
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }

    /**
     * Ajax : Ajoute un élément à la collection
     */
    public function add()
    {
        // Si l'utilisateur est bien connecté
        if(isset($_SESSION['front']['id']))
        {
            if(isset($_GET['item_id'], $_GET['item_count'], $_GET['item_type']))
            {
                // On ajoute l'élément à la liste des éléments que l'on veut insérer dans la collection
                $item_list = array();
                $item_list[] = array(
                    'id' => $_GET['item_id'],
                    'count' => $_GET['item_count'],
                    'type' => $_GET['item_type']
                );

                // Si il s'agit d'un set, on sélectionne les figurines de ce set pour les ajouter à la collection
                if($_GET['item_type'] == 'set')
                {
                    // On recherche les figurines que contient ce set
                    $minifigures_list = $this->Collections->query(
                        'SELECT minifigure_id AS id, minifigure_count AS count FROM briiicks_sets_minifigures WHERE set_id = ?',
                        array($_GET['item_id'])
                    );
                    // Si on a trouvé des figurines
                    if($minifigures_list)
                    {
                        // On les ajoute à la liste des éléments à insérer dans la collection
                        foreach($minifigures_list as $minifigure)
                        {
                            $minifigure_in_set_count = (int) $minifigure->count;

                            $minifigure->count = $minifigure_in_set_count * $_GET['item_count'];

                            $item_list[] = array(
                                'id' => $minifigure->id,
                                'count' => $minifigure->count, // le nombre d'éléments à rajouter, pas le nombre total
                                'type' => 'minifigure'
                            );
                        }
                    }
                }

                // Puis on insère tous les éléments
                foreach($item_list as $item_to_insert)
                {
                    // On vérifie d'abord que l'élément n'est pas déjà dans la collection
                    $existing_item = $this->Collections->getItemFromUser($item_to_insert['id'], $item_to_insert['type'], $_SESSION['front']['id']);
                    // Si l'élément existe déjà
                    if($existing_item)
                    {
                        // On met à jour l'enregistrement existant avec le nouveau nombre d'éléments
                        $this->Collections->update(
                            $existing_item->id,
                            array(
                                'count' => $existing_item->count + $item_to_insert['count']
                            )
                        );
                    }
                    // Si l'élément n'existe pas
                    else
                    {
                        // On l'ajoute à la collection
                        $this->Collections->create(
                            array(
                                'member_id' => $_SESSION['front']['id'],
                                'item_id' => $item_to_insert['id'],
                                'is_minifigure' => ($item_to_insert['type'] == 'minifigure') ? true : false,
                                'is_set' => ($item_to_insert['type'] == 'set') ? true : false,
                                'count' => $item_to_insert['count']
                            )
                        );
                    }
                }
                echo json_encode(true);
                die();
            }
            echo json_encode(false);
            die();
        }
        // Si l'utilisateur n'est pas connecté
        else {
            echo json_encode(false);
            die();
        }
    }

    /**
     * Ajax : Modifie un élément de la collection
     */
    public function edit()
    {
        // Si l'utilisateur est bien connecté
        if(isset($_SESSION['front']['id']))
        {
            if(isset($_GET['item_id'], $_GET['item_count'], $_GET['item_type']))
            {
                // On ajoute l'élément à la liste des éléments que l'on veut modifier
                $item_list = array();
                $item_list[] = array(
                    'id' => $_GET['item_id'],
                    'count' => $_GET['item_count'],
                    'type' => $_GET['item_type']
                );

                $return_list = array();

                // Si il s'agit d'un set
                if($_GET['item_type'] == 'set')
                {
                    // On récupère le nombre d'occurence de ce set dans la collection
                    $old_set = $this->Collections->getItemFromUser($_GET['item_id'], 'set', $_SESSION['front']['id']);
                    $old_set_count = (int) $old_set->count;

                    // On recherche les figurines que contient ce set
                    $minifigures_list = $this->Collections->query(
                        'SELECT minifigure_id AS id, minifigure_count AS count FROM briiicks_sets_minifigures WHERE set_id = ?',
                        array($_GET['item_id'])
                    );
                    // Si on a trouvé des figurines
                    if($minifigures_list)
                    {
                        // On les ajoute à la liste des éléments à modifier
                        foreach($minifigures_list as $minifigure)
                        {
                            // On calcule le nouveau nombre de figurines

                            // On récupère le nombre de figurines que le set contient dans la collection
                            $this_minifigure = $this->Collections->getItemFromUser($minifigure->id, 'minifigure', $_SESSION['front']['id']);
                            $minifigure_in_collection_count = (int) $this_minifigure->count;
                            $minifigure_in_set_count = (int) $minifigure->count;
                            $new_set_count = (int) $_GET['item_count'];

                            // Si on supprime des sets
                            if($old_set_count > $_GET['item_count'])
                            {
                                // Le nouveau nombre est égal à l'ancien moins le nombre de set supprimés
                                $minifigure->count = ($minifigure_in_collection_count - ($old_set_count - $new_set_count) * $minifigure_in_set_count);
                            }
                            // Si on ajoute des sets
                            else
                            {
                                // Le nouveau nombre est égal à l'ancien plus le nombre de set ajoutés
                                $minifigure->count = ($minifigure_in_collection_count + ($new_set_count - $old_set_count) * $minifigure_in_set_count);
                            }

                            $item_list[] = array(
                                'id' => $minifigure->id,
                                'count' => $minifigure->count,
                                'type' => 'minifigure'
                            );
                            $return_list[$minifigure->id] = $minifigure->count;
                        }
                    }
                }

                // Puis on modifie tous les éléments
                foreach($item_list as $item_to_edit)
                {
                    // On vérifie d'abord que l'élément est dans la collection
                    $existing_item = $this->Collections->getItemFromUser($item_to_edit['id'], $item_to_edit['type'], $_SESSION['front']['id']);
                    // Si l'élément existe
                    if($existing_item)
                    {
                        // Si le nombre de cet élément est supérieur à 0
                        if($item_to_edit['count'] > 0)
                        {
                            // On met à jour l'enregistrement existant avec le nouveau nombre d'éléments
                            $this->Collections->update(
                                $existing_item->id,
                                array(
                                    'count' => $item_to_edit['count']
                                )
                            );
                        }
                        // Le nombre de cet élément est inférieur à 0
                        else
                        {
                            // On supprime l'enregistrement
                            $this->Collections->delete($existing_item->id);
                        }
                    }
                }
                echo json_encode($return_list);
                die();
            }
        }
        // Si il y a eu un problème
        echo json_encode(false);
        die();
    }

    /**
     * Ajax : Supprime un élément de la collection
     */
    public function delete()
    {
        // Si l'utilisateur est bien connecté
        if(isset($_SESSION['front']['id']))
        {
            if(isset($_GET['item_id'], $_GET['item_type']))
            {
                // On ajoute l'élément à la liste des éléments que l'on veut supprimer
                $item_list = array();
                $item_list[] = array(
                    'id' => $_GET['item_id'],
                    'type' => $_GET['item_type']
                );

                $return_list = array();

                // Si il s'agit d'un set
                if($_GET['item_type'] == 'set')
                {
                    // On récupère le nombre d'occurence de ce set dans la collection
                    $set_in_collection = $this->Collections->getItemFromUser($_GET['item_id'], 'set', $_SESSION['front']['id']);

                    // On recherche les figurines que contient ce set
                    $minifigures_in_set = $this->Collections->query(
                        'SELECT minifigure_id AS id, minifigure_count AS count FROM briiicks_sets_minifigures WHERE set_id = ?',
                        array($_GET['item_id'])
                    );
                    // Si on a trouvé des figurines
                    if($minifigures_in_set)
                    {
                        // On les ajoute à la liste des éléments à supprimer/modifier
                        foreach($minifigures_in_set as $minifigure_in_set)
                        {
                            // On récupère le nombre de figurines que le set contient présentes dans la collection
                            $minifigure_in_collection = $this->Collections->getItemFromUser($minifigure_in_set->id, 'minifigure', $_SESSION['front']['id']);

                            if($minifigure_in_collection)
                            {
                                // On calcule le nouveau nombre de figurine dans la collection
                                $minifigure_in_set->count = $minifigure_in_collection->count - $set_in_collection->count * $minifigure_in_set->count;

                                $item_list[] = array(
                                    'id' => $minifigure_in_set->id,
                                    'count' => $minifigure_in_set->count,
                                    'type' => 'minifigure'
                                );
                                $return_list[$minifigure_in_set->id] = $minifigure_in_set->count;
                            }
                        }
                    }
                }

                // Puis on supprime tous les éléments
                foreach($item_list as $item_to_delete)
                {
                    // On vérifie d'abord que l'élément est dans la collection
                    $existing_item = $this->Collections->getItemFromUser($item_to_delete['id'], $item_to_delete['type'], $_SESSION['front']['id']);
                    // Si l'élément existe
                    if($existing_item)
                    {
                        // Si on a une entrée count et que le nombre de cet élément est supérieur à 0
                        if(array_key_exists('count', $item_to_delete) && $item_to_delete['count'] > 0)
                        {
                            // On met à jour l'enregistrement existant avec le nouveau nombre d'éléments
                            $this->Collections->update(
                                $existing_item->id,
                                array(
                                    'count' => $item_to_delete['count']
                                )
                            );
                        }
                        // Le nombre de cet élément est inférieur à 0
                        else
                        {
                            // On supprime l'enregistrement
                            $this->Collections->delete($existing_item->id);
                        }
                    }
                }
                echo json_encode($return_list);
                die();
            }
        }
        // Si il y a eu un problème
        echo json_encode(false);
        die();
    }

}