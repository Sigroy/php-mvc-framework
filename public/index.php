<?php
declare(strict_types=1);

//require '../App/Controllers/Posts.php';
//require '../Core/Router.php';

//use MVCFramework\Core\Router;

spl_autoload_register(function ($class) {
    $root = dirname(__DIR__); // get the app directory
    $class = str_replace('MVCFramework\\', '', $class);
    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_readable($file)) {
        require $file;
    }
});

$router = new MVCFramework\Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
//$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
//$router->add('posts/new', ['controller' => 'Posts', 'action' => 'new']);
$router->add('{controller}/{action}');
$router->add('{controller}', ['action' => 'index']);
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
$router->add('{controller}/{id:\d+}/{action}');

// Display the routines table
//echo '<pre>';
//var_dump($router->getRoutes());
//echo htmlspecialchars(print_r($router->getRoutes(), true));
//echo '</pre>';

// Match the requested route
$url = $_SERVER['QUERY_STRING'];

// string(9) "posts/new"

//if ($router->match($url)) {
//    echo '<pre>';
//    var_dump($router->getParams());
//    echo '</pre>';
//} else {
//    echo "No route found for $url";
//}

$router->dispatch($_SERVER['QUERY_STRING']);

echo '<br>';
var_dump($router->getParams());