<?php
namespace App\Models\Admin;
use Core\Models\Models;

/**
 * Classe associée aux utilisateurs
 */
class UsersModel extends Models
{

    /**
     * @var string $table Nom de la table
     */
    protected $table;

    /**
     * @var string $entity_class Nom de la classe associée aux entrées de la table
     */
    protected $entity_class = 'App\Entities\MemberEntity';

    /**
     * Cherche en bdd l'utilisateur correspondant à un id donné
     * @param int $id_user Id de l'utilisateur
     */
    public function getUser($id_user)
    {
        // Sélection de l'utilisateur correspondant à l'id
        $result = $this->query(
            'SELECT id, lastname, firstname, email FROM ' . $this->table . ' WHERE id = ?',
            array($id_user),
            true
        );
        // On a trouvé un utilisateur correspondant à l'id
        if (isset($result))
        {
            return $result;
        }
        return false;
    }

    /**
     * Récupère en bdd la liste des utilisateurs
     */
    public function getUsersList()
    {
        $result = $this->query(
            'SELECT id, lastname, firstname, email, creation_date, is_admin
			FROM ' . $this->table
        );
        // Si il y a des résultats on les retourne
        if (isset($result))
        {
            return $result;
        }
        return false;
    }

    /**
     *	Récupère une partie des utilisateurs
     */
    public function some($first_entry, $entries_number) {

        return $this->query(
            'SELECT id, lastname, firstname, email, creation_date, is_admin
			FROM ' . $this->table . '
			LIMIT ' . $first_entry . ', ' . $entries_number
        );

    }

    /**
     * Enregistre un nouvel utilisateur
     * @param string $lastname Nom de l'utilisateur
     * @param string $firstname Prénom de l'utilisateur
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe de l'utilisateur
     */
    public function setUser($lastname, $firstname, $email, $password)
    {
        // Vérifie que l'adresse mail n'est pas déjà présente en base
        $data = $this->query(
            'SELECT COUNT(*) AS corresponding_email_number FROM ' . $this->table . ' WHERE email = ?',
            array($email),
            true
        );
        // Si l'adresse est présente en base, on retourne un message
        if(isset($data) AND $data->corresponding_email_number >= 1)
        {
            return 'Cette adresse e-mail est déjà utilisée par un utilisateur.';
        }
        // Si on est là, c'est que l'adresse n'est pas présente
        // On hache tout d'abord le mot de passe
        $password = sha1($password);
        // on enregistre le nouvel utilisateur
        $this->create(
            array(
                'lastname' => $lastname,
                'firstname' => $firstname,
                'email' => $email,
                'password' => $password,
                'creation_date' => date('Y-m-d')
            )
        );
        return (int) $this->db->lastInsertId();
    }

    /**
     * Modifie un utilisateur
     * @param int $id_user Id de l'utilisateur
     * @param string $lastname Nom de l'utilisateur
     * @param string $firstname Prénom de l'utilisateur
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe de l'utilisateur
     */
    public function editUser($id_user, $lastname, $firstname, $email, $password)
    {
        // Vérifie que l'adresse mail n'est pas déjà utilisée par un autre utilisateur
        $data = $this->query(
            'SELECT COUNT(*) AS corresponding_email_number
            FROM ' . $this->table . '
            WHERE email = ? AND id != ?',
            array(
                $email,
                $id_user
            ),
            true
        );
        // Si l'adresse est présente en base, on retourne un message
        if(isset($data) AND $data->corresponding_email_number >= 1)
        {
            return 'Cette adresse e-mail est déjà utilisée par un autre utilisateur.';
        }

        // Si on est là, c'est que l'adresse n'est pas utilisée
        // Si il y a un nouveau mot de passe, on le hache
        if(!empty($password))
        {
            $password = sha1($password);
        }
        // on modifie l'utilisateur
        $this->query(
            'UPDATE ' . $this->table . '
            SET lastname = COALESCE(NULLIF(?, null), lastname),
                firstname = COALESCE(NULLIF(?, null), firstname),
                email = COALESCE(NULLIF(?, null), email),
                password = COALESCE(NULLIF(?, null), password)
            WHERE id = ?',
            array(
                $lastname,
                $firstname,
                $email,
                $password,
                $id_user
            )
        );
        return true;
    }

}