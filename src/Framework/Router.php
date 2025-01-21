<?php 
declare( strict_types = 1 );

namespace Framework;

class Router {
    private array $routes = [];
    
    public function add(string $path, array $params = []): void
    {
        $this->routes[] = [
            'path' => $path,
            'params' => $params
        ];
    }
    
    // convert "/{controller}/{action}" to #^/(?<controller>[a-z]+)/(?<action>[a-z]+)$#
    private function getPatternFromRoutePath( string $route_path ): string {
        // print_r( $route_path );
        
        $route_path = trim( $route_path, "/" );
        $segments = explode("/", $route_path);
        
        // print_r( $segments );
        
        $segments = array_map( function( string $segment ): string {
            // From: {controller}/{id}/{action}
            // To: #^/(?<controller>[a-z]+)/(<id>[^\])/(?<action>[a-z]+)$#
            if ( preg_match( "#^\{([a-z][a-z0-9]*)\}$#", $segment, $matches ) ) {
                $segment = '(?<' . $matches[1] . '>[^/]*)';   
                return $segment;         
            }
            
            // From: {controller}/{id:\d+}/{action}
            // To: #^/(?<controller>[a-z]+)/(<id>[^\])/(?<action>[a-z]+)$#
            if ( preg_match( "#^\{([a-z][a-z0-9]*):(.+)\}$#", $segment, $matches ) ) {
                $segment = '(?<' . $matches[1] . '>' . $matches[2] . ')';  
                return $segment;          
            }
            
            return $segment;      
        }, $segments);   
        
        // pretty_print( $segments );
        return "#^" . implode("/", $segments) . "$#iu";  
    }
    public function match( string $path ): array|bool  {
        
        
        $path = urldecode( $path );
                // var_dump( $path );
        $path = trim( $path, "/" );
        // using regular expression
        // 1. match fixed pattern
        // $pattern = "#^/home/index$#";
        // 2. match any pattern
        foreach ($this->routes as $route) {
            // $pattern = "#^/(?<controller>[a-z]+)/(?<action>[a-z]+)$#";
            
            // #################################################
            // ######### find out variable route functionality ##########
            // get route from variables. For example: {contrller}/{action}
            // echo $pattern, "\n", $route['path'], "\n";
            // print_r( $route['path'] );
            // echo "\n";
            
            $pattern = $this->getPatternFromRoutePath( $route['path'] );            
            // echo $pattern, "\n";
            
            if ( preg_match( $pattern, $path, $matches ) ){
                $matches = array_filter( $matches, 'is_string', ARRAY_FILTER_USE_KEY );
                
                $params = array_merge( $matches, $route['params'] );
                // pretty_print( $params );
                return $params;
            }
        }
        return false;
    }
}