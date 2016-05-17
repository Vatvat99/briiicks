<?php
namespace Core\Entities;

/**
 * Classe associée aux entrées des tables d'une bdd
 */
class Entities {

	/**
	 *	Méthode magique appelée lorsqu'une méthode inconnue est appelée
	 * @param string $key nom de la méthode appelée
	 */
	public function __get($key) {
		$method = 'get' . ucfirst($key);
		$this->$key = $this->$method();
		return $this->$key;
	}

}