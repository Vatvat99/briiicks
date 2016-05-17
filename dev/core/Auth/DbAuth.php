<?php
namespace Core\Auth;
use Core\Database\Database;

/**
 * Gère l'authentification par base de données
 */
class DbAuth {

	/**
	 * @var object Mémorise l'instance de la base de données
	 */
	private $db;

    /**
     * @var string Nom de la table des utilisateurs
     */
    private $users_table;


    /**
	 * Stocke la connexion à la bdd
	 */
	public function __construct(Database $db, $users_table = null) {
		$this->db = $db;
		$this->users_table = $users_table;
	}

    /**
     * Cherche si un utilisateur existe en bdd
     * @param string $email Adresse e-mail de l'utilisateur
     * @param string $password Mot de passe
     * @return object $user Utilisateur trouvé
     */
    public function findUser($email, $password) {
        $user = $this->db->prepare('SELECT * FROM '. $this->users_table . ' WHERE email = ?', array($email), null, true);
        if($user) {
            if($user->password === sha1($password)) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Vérifie si un compte utilisateur est actif
     * @param int $id Id du compte utilisateur
     * @return bool
     */
    public function findActive($id) {
        $active = $this->db->prepare('SELECT active FROM '. $this->users_table . ' WHERE id = ?', array($id), null, true);
        if($active) {
            return true;
        }
        return false;
    }

	/**
	 * Connecte l'utilisateur
     * @param object $user Utilisateur
     * @param string $app_section Section de l'application à laquelle connecter l'utilisateur
     * @param bool $keep_logged Indique si l'utilisateur souhaite ou non rester connecté
	 */
	public function login($user, $app_section, $keep_logged) {
        $_SESSION[$app_section]['id'] = $user->id;
        if($keep_logged) {
            setcookie($app_section . '[email]', $user->email, time() + 365*24*3600, null, null, false, true);
            setcookie($app_section . '[password]', $user->password, time() + 365*24*3600, null, null, false, true);
        }
	}

	/**
	 * Vérifie si l'utilisateur est connecté
     * @param string $app_section Section de l'application à laquelle l'utilisateur est censé être connecté
	 * @return bool
	 */
	public function logged($app_section) {
        if(isset($_SESSION[$app_section])) {
            return array_key_exists('id', $_SESSION[$app_section]);
        }
        return false;
	}

	/**
	 * Retourne l'id de l'utilisateur
	 * @return int
	 */
	public function getUserId() {
		if($this->logged()) {
			return $_SESSION['auth'];
		}
		return false;
	}

}