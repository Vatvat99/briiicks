<?php
namespace Core;

/**
 * Classe de configuration du site (Singleton)
 */
class Config {

	/**
	 * @var array $setting Contient la configuration
	 */
	private $settings = array();

	/**
	 * @var object $_instance Mémorise l'instance unique (Singleton) de la classe
	 */
	private static $_instance;

	/**
	 * Retourne l'instance unique (Singleton) de la classe
	 */
	public static function getInstance($file) {
		if(is_null(self::$_instance)) {
			self::$_instance = new Config($file);
		}
		return self::$_instance;
	}

	/**
	 * Récupère la configuration et la stocke dans l'instance
	 * @param string $file Fichier de configuration à charger
	 */
	public function __construct($file) {
		$this->settings = require($file);
	}

	/**
	 * Retourne un élément de configuration
	 */
	public function get($key) {
		if(!isset($this->settings[$key])) {
			return null;
		}
		return $this->settings[$key];
	}

}