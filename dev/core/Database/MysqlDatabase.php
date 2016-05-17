<?php
namespace Core\Database;
use \PDO;

/**
 * Gère les interactions avec la base de donnée
 */
class MysqlDatabase extends Database {

	/**
	* @var string $db_name Nom de la base de donnée
	*/
	private $db_name;

	/**
	* @var string $db_user Nom d'utilisateur
	*/
	private $db_user;

	/**
	* @var string $db_pass Mot de passe
	*/
	private $db_pass;

	/**
	* @var string $db_host Nom d'hôte
	*/
	private $db_host;

	/**
	* @var object $pdo Instance de PDO
	*/
	private $pdo;

	/**
	 * Initialise les variables
	 * @param string $db_name Nom de la base de donnée
	 * @param string $db_user Nom d'utilisateur
	 * @param string $db_pass Mot de passe
	 * @param string $db_host Nom d'hôte
	 */
	public function __construct($db_name, $db_user = 'root', $db_pass = '', $db_host = 'localhost') {
		$this->db_name = $db_name;
		$this->db_user = $db_user;
		$this->db_pass = $db_pass;
		$this->db_host = $db_host;
	}

	/**
	 * Crée la connexion à la base de donnée
	 */
	private function getPdo() {
		if($this->pdo == null) {
			$pdo = new PDO('mysql:host=' . $this->db_host . ';dbname=' . $this->db_name, $this->db_user, $this->db_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			$this->pdo = $pdo;
		}
		return $this->pdo;
	}

	/**
	 * Envoie une requête SQL
	 * @param string $statement Requête SQL
	 * @param string $class_name Classe associée aux résultats de la requête
	 * @param bool $one Retourne un ou plusieurs résultats
	 * @return bool|object Données retournées par la requête
	 */
	public function query($statement, $class_name = null, $one = false) {
		$request = $this->getPdo()->query($statement);
		if(
			strpos($statement, 'UPDATE') === 0 ||
			strpos($statement, 'INSERT') === 0 ||
			strpos($statement, 'DELETE') === 0
		) {
			return $request;
		}
        if(strpos($statement, 'SELECT COUNT') !== 0) {
            if ($class_name === null) {
                $request->setFetchMode(PDO::FETCH_OBJ);
            } else {
                $request->setFetchMode(PDO::FETCH_CLASS, $class_name);
            }
        }
		if($one) {
			$data = $request->fetch();
		} 
		else {
			$data = $request->fetchAll();
		}
        if(strpos($statement, 'SELECT COUNT') === 0) {
            $data = $data[0];
        }
		return $data;
	}

	/**
	 * Envoie une requête SQL préparée
	 * @param string $statement Requête SQL
	 * @param array $values Valeurs à passer en paramètre de la requête
	 * @param string $class_name Classe associée aux résultats de la requête
	 * @param bool $one Retourne un ou plusieurs résultats
	 * @return bool|object Données retournées par la requête
	 */
	public function prepare($statement, $values, $class_name = null, $one = false) {
		$request = $this->getPdo()->prepare($statement);
		$result = $request->execute($values);
        if(
			strpos($statement, 'UPDATE') === 0 || 
			strpos($statement, 'INSERT') === 0 || 
			strpos($statement, 'DELETE') === 0
		) {
			return $result;
		}
		if(strpos($statement, 'SELECT COUNT') !== 0) {
			if ($class_name === null) {
				$request->setFetchMode(PDO::FETCH_OBJ);
			} else {
				$request->setFetchMode(PDO::FETCH_CLASS, $class_name);
			}
		}
		if($one) {
			$data = $request->fetch();
		}
		else {
			$data = $request->fetchAll();
		}
		if(strpos($statement, 'SELECT COUNT') === 0) {
			$data = $data[0];
		}
		return $data;
	}

	/**
	 * Retourne l'id du dernier enregistrement effectué
	 * @return int
	 */
	public function lastInsertId() {
		return $this->getPdo()->lastInsertId();
	}

}