<?php 

use App\Database;
use Framework\Container;
use Framework\TemplateViewerInterface;

$container = new Container;
$container->set( App\Database::class, function(){
    $database = new Database($_ENV["DB_HOST"], $_ENV["DB_NAME"], $_ENV["DB_USER"], "");
    return $database;
} );

$container->set(TemplateViewerInterface::class, function(){
    return new Framework\PHPTemplateViewer;
});

return $container;