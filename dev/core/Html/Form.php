<?php
namespace Core\Html;

/**
 * Class Form
 * Génère un formulaire
 */
class Form {

	/**
	 * @var array|object $data Données du formulaire
	 */
	private $data;

	/**
	 * @var string $surround Balise Html destinée à entourer les champs de formulaire
	 */
	public $surround = 'p';

	/**
	 * @param array $data Données du formulaire
	 */
	public function __construct($data = array()) {
		$this->data = $data;
	}

	/**
	 * @param string $html Code Html à entourer
	 * @return string
	 */
	protected function surround($html) {
		return '<' . $this->surround . '>' . $html . '</' . $this->surround . '>';
	}

	/**
	 * Récupère la valeur d'un champ
	 * @param string $key Clé correspondant au nom du champ dont on veut récupérer la valeur
	 * @return string
	 */
	protected function getValue($key) {
		if(is_object($this->data)) {
			return isset($this->data->$key) ? $this->data->$key : null;
		}
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

	/**
	 * @param string $name Nom du champ
	 * @param string $label Intitulé du champ
	 * @param array $options
	 * @return string
	 */
	public function input($name, $label, $options = array()) {
		$type = array_key_exists('type', $options) ? $options['type'] : 'text';
		return $this->surround('<input type="' . $type . '" name="' . $name . '" value="' . $this->getValue($name) . '">');
	}

	/**
	 * @return string
	 */
	public function submit() {
		return $this->surround('<button type="submit">Envoyer</button>');
	}

}