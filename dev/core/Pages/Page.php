<?php
namespace Core\Pages;

/**
 * Gère l'affichage d'une page (MVC)
 */
class Page {

	/**
	* @var string $title Contenu de la balise title
	*/
	protected $title;

    /**
     * @var string $meta_description Contenu de la balise meta "description"
     */
    protected $meta_description;

    /**
     * @var array $librairies_styles Liste des styles issus de librairies
     */
    protected $librairies_styles = array();

    /**
     * @var array $external_styles Liste des styles hébergés en ligne
     */
    protected $external_styles = array();

    /**
     * @var array $styles Liste des styles propres aux sites
     */
    protected $styles = array();

    /**
     * @var array $librairies_scripts Liste des scripts issus de librairies
     */
    protected $librairies_scripts = array();

    /**
     * @var array $external_scripts Liste des scripts hébergés en ligne
     */
    protected $external_scripts = array();

    /**
     * @var array $scripts Liste des scripts propres aux sites
     */
    protected $scripts = array();

    /**
     * @var string $class_body Attribut "class" de la balise body
     */
    protected $class_body = '';

    /**
     * @var string $templatePath Chemin vers le dossier contenant les templates
     */
    protected $templatePath;

	/**
	* Instanciation de la page HTML
	* @param array $parameters Liste des paramètre nécessaires à l'instanciation
	*/
	public function __construct($parameters = array()) {
		// Balise title
		if (array_key_exists('title', $parameters)) {
            $this->title = $this->title . ' | ' . $parameters['title'];
        }
        // Balise meta description
        if (array_key_exists('meta_description', $parameters)) {
            $this->meta_description = $parameters['meta_description'];
        }
        // Attribut classe de la balise body
        if (array_key_exists('class_body', $parameters)) {
            $this->class_body = $parameters['class_body'];
        }
	}

    /**
     * Affecte un titre à une page
     */
    public function setTitle($title) {
        $this->title .= $this->title . ' | ' . $title;
    }

}