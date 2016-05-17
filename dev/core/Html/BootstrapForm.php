<?php
namespace Core\Html;

/**
 * Class BootstrapForm
 * Génère un formulaire intégrant les classes Bootstrap
 */
class BootstrapForm extends Form {

	/**
	 * @param string $html Code Html à entourer
	 * @return string
	 */
	protected function surround($html) {
		return '<div class="form-group">' . $html . '</div>';
	}

	/**
	 * @param string $name Nom du champ
	 * @param string $label Intitulé du champ
	 * @param array $options
	 * @return string
	 */
	public function input($name, $label, $options = array()) {
		$type = array_key_exists('type', $options) ? $options['type'] : 'text';
		$label = '<label>' . $label . '</label>';
		$input = '<input type="' . $type . '" name="' . $name . '" value="' . $this->getValue($name) . '" class="form-control">';
		return $this->surround($label . $input);
	}

	/**
	 * @param string $name Nom du champ
	 * @param string $label Intitulé du champ
	 * @param array $options
	 * @return string
	 */
	public function select($name, $label, $options) {
		$label = '<label>' . $label . '</label>';
		$select = '<select name="' . $name . '" class="form-control">';
		foreach ($options as $key => $value) {
			$attributes = '';
			if($key == $this->getValue($name)) {
				$attributes = ' selected="selected"';
			}
			$select.= '<option value="' . $key . '"' . $attributes . '>';
			$select.= $value;
			$select.= '</option>';
		}
		$select.= '</select>';
		return $this->surround($label . $select);
	}

	/**
	 * @param string $name Nom du champ
	 * @param string $label Intitulé du champ
	 * @return string
	 */
	public function textarea($name, $label) {
		$label = '<label>' . $label . '</label>';
		$textarea = '<textarea name="' . $name . '" class="form-control">' . $this->getValue($name) . '</textarea>';
		return $this->surround($label . $textarea);
	}

	/**
	 * @return string
	 */
	public function submit() {
		return $this->surround('<button type="submit" class="btn btn-primary">Envoyer</button>');
	}
	
}