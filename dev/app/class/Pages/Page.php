<?php
namespace App\Pages;
use App;
use App\Pages\AppPage;

/**
 * Gère l'affichage d'une page du front (MVC)
 */
class Page extends AppPage {

    /**
     * Instanciation de la page HTML et définie le chemin vers les templates
     * @param array $parameters Liste des paramètres nécessaires à l'instanciation
     */
    public function __construct($parameters = array()) {
        $this->templatePath = ROOT . '/app/views/templates/';
        $config = App::getInstance()->getConfig();
        $this->title = $config->get('title');
        $this->meta_description = $config->get('meta_description');

        $this->librairies_styles = $config->get('common_librairies_styles');
        if (array_key_exists('librairies_styles', $parameters)) {
            foreach ($parameters['librairies_styles'] as $style) {
                $this->librairies_styles[] = $style;
            }
        }

        $this->external_styles = $config->get('common_external_styles');
        if (array_key_exists('external_styles', $parameters)) {
            foreach ($parameters['external_scripts'] as $script) {
                $this->external_scripts[] = $script;
            }
        }

        $this->styles = $config->get('common_styles');
        if (array_key_exists('styles', $parameters)) {
            foreach ($parameters['styles'] as $style) {
                $this->styles[] = $style;
            }
        }

        $this->librairies_scripts = $config->get('common_librairies_scripts');
        if (array_key_exists('librairies_scripts', $parameters)) {
            foreach ($parameters['librairies_scripts'] as $script) {
                $this->librairies_scripts[] = $script;
            }
        }

        $this->external_scripts = $config->get('common_external_scripts');
        if (array_key_exists('external_scripts', $parameters)) {
            foreach ($parameters['external_scripts'] as $script) {
                $this->external_scripts[] = $script;
            }
        }

        $this->scripts = $config->get('common_scripts');
        if (array_key_exists('scripts', $parameters)) {
            foreach ($parameters['scripts'] as $script) {
                $this->scripts[] = $script;
            }
        }

        parent::__construct($parameters);
    }

}