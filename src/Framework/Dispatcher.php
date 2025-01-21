<?php 
declare( strict_types = 1 );

namespace Framework;

use Framework\Request;
use UnexpectedValueException;
use App\Middleware\ChangeResponseExample;
use Framework\Exceptions\PageNotFoundException;

class Dispatcher {
    
    // private Request $request;
    
    // public function setRequest( Request $request ) {
    //     $this->request = $request;
    // }    
    
    public function __construct(private Router $router, private Container $container)
    {
    }
    
    public function handle( Request $request ): Response {
        
        $path = $this->getPath( $request->uri );
        
        $params = $this->router->match( $path );
        
        // pretty_print( $params );
        
        if ( $params === false ) {
            throw new PageNotFoundException(" No route matched for path {$request->uri}");
        }
        
        $action  = $this->getActionName( $params );        
        $controller = $this->getControllerName( $params );
        
        $controller_object = $this->container->get( $controller ); 
        
        $controller_object->setViewer( $this->container->get( TemplateViewerInterface::class ) );
        
        // pretty_print( $this->container->get( Response::class )  );
        
        $controller_object->setResponse( $this->container->get( Response::class ) );
        
        $args = $this ->getActionArguments( $controller, $action, $params );        
        // return $controller_object->$action( ...$args );        
        $controller_handler = new ControllerRequestHandler(
            $controller_object,
            $action,
            $args
        );
        
        // return $controller_handler->handle( $request );
        // instead of return by controller handler, lets do this by middleware
        $middleware = $this->container->get( ChangeResponseExample::class );
        pretty_print( $middleware );
        
        return $middleware->process( $request, $controller_handler );
    }    
    
    private function getControllerName(array $params ): string {
        $controller = $params['controller'];
        $controller = str_ireplace ("-", "", ucwords( strtolower( $controller ), "-" ) );
        $namespace = "App\Controllers";
        
        if ( array_key_exists( 'namespace', $params ) ) {
            $namespace = "App\Controllers" . "\\" . $params['namespace'];
        }
        
        return  $namespace . "\\" . $controller;
    }
    
    private function getActionName(array $params ): string {
        $action = $params['action'];
        $action = lcfirst( str_ireplace ("-", "", ucwords( strtolower( $action ), "-" ) ) );
        return $action;
    }
        
    private function getActionArguments( string $controller, string $action, array $params ): array {
        $method = new \ReflectionMethod( $controller, $action );
        $args = [];
        // pretty_print( $method );
        /*
        Array
        (
            [0] => ReflectionParameter Object
                (
                    [name] => id
                )
                (
                    [name] => page
                )
        )
        */
        // pretty_print( $params );
        
        foreach ($method->getParameters() as $parameter) {
            $name = $parameter->getName(); 
            $args[$name] = $params[$name];
            //echo $name, " = ", $params[$name],  " ";           
        }
        return $args;
    }
    
    public function getPath( string $uri ): string {
        $path = parse_url( $uri, PHP_URL_PATH );

        if ( $path === false ) {
            throw new UnexpectedValueException("Malformed URL: '{$uri}'");
        }        
        return $path; 
    }
}