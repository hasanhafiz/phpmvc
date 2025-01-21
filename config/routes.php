<?php 

use Framework\Router;

$router = new Router;

// exit;
// Rule: add most specific route first, then most generic route 
$router->add("/admin/{controller}/{action}", ['namespace' => 'Admin']);
$router->add("{title}/{id:\d+}/{page:\d+}", [ 'controller' => 'products', 'action' => 'showPage' ]);
$router->add("/product/{slug:[\w-]+}", ['controller' => 'products', 'action' => 'show'] );
$router->add("/{controller}/{id:\d+}/{action}");
$router->add( '/Home/index', ['controller' => 'home', 'action' => 'index'] );
$router->add( '/Products', ['controller' => 'products', 'action' => 'index'] );
$router->add( '/', [ 'controller' => 'home', 'action' => 'index' ] );
$router->add("/{controller}/{action}");

return $router;
