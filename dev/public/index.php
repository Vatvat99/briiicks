<?php
define('ROOT', dirname(__DIR__));
require ROOT . '/app/class/App.php';
App::load();

if(isset($_GET['page']))
{
	$page = $_GET['page'];
}
else
{
	$page = 'home';
}

$page = explode('/', $page);
$controller_folder = ROOT . '/app/class/Controllers/';

if(count($page) == 3)
{
    $controller_file = $controller_folder . ucfirst($page[0]) . '/' . ucfirst($page[1]) . 'Controller.php';
    $controller = '\App\Controllers\\' . ucfirst($page[0]) . '\\' . ucfirst($page[1]) . 'Controller';
    $action = $page[2];
}
elseif(count($page) == 2)
{
    $controller_file = $controller_folder . ucfirst($page[0]) . 'Controller.php';
	$controller = '\App\Controllers\\' . ucfirst($page[0]) . 'Controller';
	$action = $page[1];
}
elseif(count($page) == 1)
{
    if($page[0] == 'admin')
    {
        $controller_file = $controller_folder . ucfirst($page[0]) . '/UsersController.php';
        $controller = '\App\Controllers\\' . ucfirst($page[0]) . '\UsersController';
        $action = 'login';
    }
    else
    {
        $controller_file = $controller_folder . ucfirst($page[0]) . 'Controller.php';
        $controller = '\App\Controllers\\' . ucfirst($page[0]) . 'Controller';
        $action = 'index';
    }
}

// On vérifie que le controlleur existe
if(file_exists($controller_file)){
    // Si c'est le cas, on l'instancie
    $controller = new $controller();
}
else {
    // Sinon, erreur 404
    header("HTTP/1.0 404 Not Found");
    $controller = new \App\Controllers\ErrorsController();
    $controller->error('404');
    die();
}
// On vérifie que la méthode existe
if(method_exists($controller, $action))
{
    // Si c'est le cas, on l'appelle
    $controller->$action();
}
else {
    // Sinon, erreur 404
    header("HTTP/1.0 404 Not Found");
    $controller = new \App\Controllers\ErrorsController();
    $controller->error('404');
    die();
}
