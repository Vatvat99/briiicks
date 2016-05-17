<?php
use Core\Database\MysqlDatabase;
use Core\Config;
use App\Pages\Page;

/**
 * Classe gérant les éléments communs à l'application (Singleton)
 */
class App {

	/**
	 * @var object $_instance Mémorise l'instance unique (Singleton) de la classe
	 */
	private static $_instance;

	/**
	 * @var object Mémorise l'instance de la base de donnée
	 */
	private $db_instance;

	/**
	 * Retourne l'instance unique (Singleton) de la classe
	 */
	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new App();
		}
		return self::$_instance;
	}

	/**
	 * Charge les autoloader et démarre la session
	 */
	public static function load() {
		session_start();
		require ROOT . '/core/Autoloader.php';
		Core\Autoloader::register('autoload');
		require ROOT . '/app/class/Autoloader.php';
		App\Autoloader::register('autoload');
	}

    /**
     * Retourne l'instance de Config
     */
    public function getConfig() {

        if(isset($_SERVER['ENVIRONMENT']) && $_SERVER['ENVIRONMENT']) {
            switch ($_SERVER['ENVIRONMENT']) {
                case 'development':
                    $config_file = 'config_dev.php';
                    break;
                case 'production':
                    $config_file = 'config_prod.php';
                    break;
                default:
                    die('L\'environnement d\'exécution est mal paramétré.');
                    break;
            }
        } else {
            die('L\'environnement d\'exécution n\'est pas paramétré.');
        }

        return Config::getInstance(ROOT . '/config/' . $config_file);
    }

	/**
	 * Retourne une instance d'un modèle (Factory)
     * @param string $name Nom du modèle
     * @param string $app_section Section de l'application à laquelle appartient le modèle
	 */
	public function getModel($name, $app_section) {
        $model_namespace = null;
        if($app_section != null) {
            $model_namespace = ucfirst($app_section . '\\');
        }
		$class_name = '\\App\\Models\\' . $model_namespace . ucfirst($name) . 'Model';
        $config = $this->getConfig();
        if($app_section != null) {
            $section_config = $config->get($app_section);
            return new $class_name($this->getDb(), $section_config['tables_prefix']);
        } else {
            return new $class_name($this->getDb(), $config->get('tables_prefix'));
        }
	}

	/**
	 * Retourne une instance d'une bdd (Factory)
	 */
	public function getDb() {
		if(is_null($this->db_instance)) {
			$config = $this->getConfig();
			$this->db_instance = new MysqlDatabase($config->get('db_name'), $config->get('db_user'), $config->get('db_pass'), $config->get('db_host'));
		}
		return $this->db_instance;
	}

    /**
     * Affiche une page d'erreur
     * @param string $status_code Code http de l'erreur (ex: 404)
     */
    public static function error($status_code)
    {
        // Préparation de la page
        $page = new Page(array(
            'title' => $status_code,
            'class_body' => 'error'
        ));
        // Rendu du contenu
        ob_start();
        require ROOT . '/app/views/errors/404.php';
        $content = ob_get_clean();
        // Rendu de la page
        echo $page->render($content);
    }
	
}