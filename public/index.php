<?php
declare( strict_types = 1 );

use Framework\Dotenv;

define( "ROOT_PATH",dirname( __DIR__ ) );

// pretty_print( ROOT_PATH );

spl_autoload_register( function( string $class_name ){
    // pretty_print( $class_name );
    $class_name =  str_ireplace( "\\", "/", $class_name );     
    require ROOT_PATH . "/src/$class_name.php";
});

$dotenv = new Dotenv;
$dotenv->load(ROOT_PATH . "/.env");
// pretty_print( $_ENV );

set_error_handler("Framework\ErrorHandler::handleError");

set_exception_handler( "Framework\ErrorHandler::handleException" );

function pretty_print( $data, $identify = '' ) {    
    echo "<pre>";
    echo "------", $identify, "-------- \n";
    print_r( $data );
    echo "</pre>";
}

$router = require ROOT_PATH . "/config/routes.php";
$container = require ROOT_PATH . "/config/services.php";
// pretty_print($container);

$request = Framework\Request::createFromGlobals();
// pretty_print( $request );
// exit;

$dispatcher = new Framework\Dispatcher( $router, $container );
$response = $dispatcher->handle( $request );
$response->send();