<?php
namespace App\Models;
use Core\Models\Models;

/**
 * Classe associée aux membres
 */
class MembersModel extends Models {

    /**
     * @var string $table Nom de la table
     */
    protected $table;

    /**
     * @var string $entity_class Nom de la classe associée aux entrées de la table
     */
    protected $entity_class = 'App\Entities\MemberEntity';

    /**
     * Cherche en bdd le membre correspondant à un id donné
     * @param int $id_member Id du membre
     */
    public function getMember($id_member)
    {
        // Sélection du membre correspondant à l'id
        $result = $this->query(
            'SELECT id, lastname, firstname, email, city, region, message, picture FROM ' . $this->table . ' WHERE id = ?',
            array($id_member),
            true
        );
        // On a trouvé un membre correspondant à l'id
        if (isset($result))
        {
            return $result;
        }
        return false;
    }

    /**
     * Cherche en bdd le membre correspondant à un e-mail donné
     * @param string $email E-mail du membre
     */
    public function getMemberByEmail($email)
    {
        // Sélection du membre correspondant à l'e-mail
        $result = $this->query(
            'SELECT id, lastname, firstname, email, city, region, message, picture, activation_key, active FROM ' . $this->table . ' WHERE email = ?',
            array($email),
            true
        );
        // On a trouvé un membre correspondant à l'id
        if (isset($result))
        {
            return $result;
        }
        return false;
    }

    /**
     * Récupère en bdd la liste des membres actifs
     */
    public function getMembersList()
    {
        $result = $this->query(
            'SELECT id, lastname, firstname, email, registration_date, picture
			FROM ' . $this->table . '
			WHERE active = 1
			ORDER BY registration_date'
        );
        // Si il y a des résultats on les retourne
        if (isset($result))
        {
            return $result;
        }
        return false;
    }

    /**
     *	Récupère une partie des entrées
     */
    public function some($first_entry, $entries_number) {

        return $this->query(
            'SELECT id, lastname, firstname, email, registration_date, picture
			FROM ' . $this->table . '
			WHERE active = 1
			ORDER BY registration_date
			LIMIT ' . $first_entry . ', ' . $entries_number
        );

    }

    /**
     * Enregistre un nouveau membre
     * @param string $lastname Nom du membre
     * @param string $firstname Prénom du membre
     * @param string $email Email du membre
     * @param string $password Mot de passe du membre
     * @param string $activation_key Clé permettant d'activer le compte
     * @param int $active Compte actif ou non
     */
    public function setMember($lastname, $firstname, $email, $password, $activation_key = '', $active) {
        // Vérifie que l'adresse mail n'est pas déjà présente en base
        $data = $this->query(
            'SELECT COUNT(*) AS corresponding_email_number FROM ' . $this->table . ' WHERE email = ?',
            array($email),
            true
        );
        // Si l'adresse est présente en base, on retourne un message
        if(isset($data) AND $data->corresponding_email_number >= 1)
        {
            return 'Cette adresse e-mail est déjà utilisée par un membre.';
        }
        // Si on est là, c'est que l'adresse n'est pas présente
        // On hache tout d'abord le mot de passe
        $password = sha1($password);
        // On enregistre le nouveau membre
        $this->create(
            array(
                'lastname' => $lastname,
                'firstname' => $firstname,
                'email' => $email,
                'password' => $password,
                'registration_date' => date('Y-m-d'),
                'activation_key' => $activation_key,
                'active' => $active
            )
        );
        return (int) $this->db->lastInsertId();
    }

    /**
     * Modifie un membre
     * @param int $id_member Id du membre
     * @param string $lastname Nom du membre
     * @param string $firstname Prénom du membre
     * @param string $email Email du membre
     * @param string $password Mot de passe du membre
     * @param string $picture Nom de la photo de profil
     * @param string $city Ville du membre
     * @param string $region Région du membre
     * @param string $message Message apparaissant sur la page de profil
     */
    public function editMember($id_member, $lastname, $firstname, $email, $password, $picture, $city = null, $region = null, $message = null)
    {
        if($email != null)
        {
            // Vérifie que l'adresse email n'est pas déjà utilisée par un autre membre
            $data = $this->query(
                'SELECT COUNT(*) AS corresponding_email_number FROM ' . $this->table . ' WHERE email = ? AND id != ?',
                array(
                    $email,
                    $id_member
                ),
                true
            );
        }
        // Si l'adresse est présente en base, on retourne un message
        if(isset($data) AND $data->corresponding_email_number >= 1)
        {
            return 'Cette adresse e-mail est déjà utilisée par un autre membre.';
        }
        // L'adresse n'est pas utilisée, ou n'a pas été renseignée
        // Si il y a un nouveau mot de passe, on le hache
        if(!empty($password))
        {
            $password = sha1($password);
        }
        // on modifie le membre
        $this->query(
            'UPDATE ' . $this->table . '
        SET lastname = COALESCE(NULLIF(?, null), lastname),
            firstname = COALESCE(NULLIF(?, null), firstname),
            email = COALESCE(NULLIF(?, null), email),
            password = COALESCE(NULLIF(?, null), password),
            picture = COALESCE(NULLIF(?, null), picture),
            city = COALESCE(NULLIF(?, null), city),
            region = COALESCE(NULLIF(?, null), region),
            message = COALESCE(NULLIF(?, null), message)
        WHERE id = ?',
            array(
                $lastname,
                $firstname,
                $email,
                $password,
                $picture,
                $city,
                $region,
                $message,
                $id_member
            )
        );
        return true;
    }

    /**
     * Active le compte d'un nouveau membre après qu'il ait suivi le lien pour valider son adresse mail
     * @param string $email Email du membre
     * @param string $activation_key Clé générée lors de l'inscription
     * @return string
     */
    public function activateAccount($email, $activation_key)
    {
        // Récupération de la clé et du statut actif correspondant à l'email dans la base de données
        $member = $this->query(
            'SELECT activation_key, active
            FROM briiicks_members
            WHERE email = ?',
            array($email),
            true
        );

        // On teste la valeur de la variable $active récupérée en BDD
        if(isset($member->active) AND $member->active == '1') // Si le compte est déjà actif on prévient
        {
            return 'already-active';
        }
        else // Si ce n'est pas le cas on passe aux comparaisons
        {
            if(isset($member->activation_key) AND $activation_key == $member->activation_key) // On compare nos deux clés
            {
                // Si elles correspondent on active le compte
                $this->query(
                    'UPDATE briiicks_members SET active = 1 WHERE email = ?',
                    array($email)
                );
                return 'active';
            }
            else // Si les deux clés sont différentes on provoque une erreur...
            {
                return 'error';
            }
        }
    }

    /**
     * Cherche en bdd le membre correspondant aux identifiants
     * @param string $email Email du membre
     * @param string $password Mot de passe haché du membre
     */
    public function findMember($email, $password) {
        // Sélection de l'id du membre correspondant à l'email et au mot de passe
        return $this->query(
            'SELECT id, lastname, firstname, email, picture
             FROM briiicks_members
             WHERE email = ? AND password = ?',
            array(
                $email,
                $password
            ),
            true
        );
    }

    /**
     * Cherche en bdd si le compte d'un membre est activé
     * @param int $id_member Id du membre
     */
    public function findActive($id_member)
    {
        // Sélection du paramètre "active" correspondant à l'id du membre
        $result = $this->query(
            'SELECT active FROM briiicks_members WHERE id = ?',
            array($id_member),
            true
        );
        // Le compte est actif
        if (isset($result->active))
        {
            return $result->active;
        }
        return false;
    }

}