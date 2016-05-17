<?php
namespace App\Pages;
use App;

/**
 * Gère l'affichage d'une page de l'application (MVC)
 */
class AppPage extends \Core\Pages\Page {

    /**
     * Génère le contenu de la page HTML
     * @param string $content Contenu de la page
     * @param string $template Nom du template à utiliser
     */
    public function render($content, $template = 'default') {
        ob_start();
        $title = $this->title;
        $meta_description = $this->meta_description;
        $class_body = $this->class_body;

        $scripts = array();
        foreach (array('librairies_scripts' => 'librairies', 'external_scripts' => '', 'scripts' => 'js') as $var => $dir) {
            foreach ($this->{$var} as $file) {
                if($var == 'external_scripts') {
                    $scripts[] = $file;
                }
                else {
                    $pathname = '/assets/' . $dir . '/' . $file;
                    if (file_exists(ROOT. '/public' . $pathname)) {
                        $scripts[] = $pathname . '?v=' . filemtime(ROOT . '/public' . $pathname);
                    }
                    else {
                        die('Fichier inexistant : ' . $pathname);
                    }
                }
            }
        }

        $styles = array();
        foreach (array('librairies_styles' => 'librairies', 'external_styles' => '', 'styles' => 'css') as $variable => $directory) {
            foreach ($this->{$variable} as $file) {
                if($variable == 'external_styles') {
                    $styles[] = $file;
                }
                else {
                    $pathname = '/assets/' . $directory . '/' . $file;
                    if (file_exists(ROOT. '/public' . $pathname)) {
                        $styles[] = $pathname . '?v=' . filemtime(ROOT . '/public' . $pathname);
                    }
                    else {
                        die('Fichier inexistant : ' . $pathname);
                    }
                }
            }
        }
        require($this->templatePath . $template . '.php');
        return ob_get_clean();
    }

}