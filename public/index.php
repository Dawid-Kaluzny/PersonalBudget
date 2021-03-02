<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */
 
/**
 * Routing
 */
require '../Core/Router.php';

$router = new Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');

// Match the requested route
$url = $_SERVER['QUERY_STRING'];

if ($router->match($url)) {
	echo '<pre>';
	var_dump($router->getParams());
	echo '</pre>';
} else {
	echo "No route found for URL '$url'";
}
