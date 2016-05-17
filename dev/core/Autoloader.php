<?php
namespace Core;

/**
* Permet d'inclure automatiquement les classes lorsqu'on les instancie
* (évite d'avoir à faire des include)
*/
class Autoloader {

	/**
	* Identifie une fonction comme étant un autoloader
	* @param string $function_name Nom de la fonction qui chargera les classes
	*/
	static function register($function_name) {
		spl_autoload_register(array(__CLASS__, $function_name));
	}

	/**
	* Autoloader des classes propres au site
	* @param string $class_name Nom de la classe à charger
	*/
	static function autoload($class) {
		if(strpos($class, __NAMESPACE__ . '\\') === 0) {
			$class = str_replace(__NAMESPACE__ . '\\', '', $class);
			$class = str_replace('\\', '/', $class);
			require __DIR__ . '/' . $class . '.php';
		}
	}
}