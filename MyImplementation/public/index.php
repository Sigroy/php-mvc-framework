<?php

require '../Core/Router.php';

use MyImplementation\Core\Router;

$router = new Router();

$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
