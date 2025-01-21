<?php 
declare( strict_types = 1 );

namespace Framework;

use Closure;
use ReflectionClass;
use ReflectionNamedType;
use Exception;

class Container {
    
    private array $registry = [];
    
    public function set( $name, Closure $value ): void {
        $this->registry[$name] = $value;
        // pretty_print( $this->registry );
    }
    
    public function get( string $class_name ): object {
        
        if ( array_key_exists( $class_name, $this->registry ) ){
            // echo  $class_name, " : inside array_key_exists \n";            
            // var_dump( $this->registry[$class_name] );      
            
            return $this->registry[$class_name]();
        }
        
        $reflector = new ReflectionClass( $class_name );
        $constructor = $reflector->getConstructor();        
        $dependencies = [];
        if ( $constructor === null ) {
            return new $class_name;
        }
        
        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();
            // echo "-------- <br/>";
            // pretty_print( $constructor );
            // pretty_print( $parameter );
            // echo "-------- <br/>";
            
            if ( $type === null ) {
                throw new Exception("Constructor parameter '{$parameter->getName()}' in the $class_name class has no type declaration (can not be null).");
            }
            
            if ( ! ($type instanceof ReflectionNamedType) ) {
                exit("Constructor parameter '{$parameter->getName()}' in the $class_name class is an invalid type: $type. - only single named method types supported!");
            }
            
            if ( $type->isBuiltin() ) {
                exit("Unable to resolve constructor parameter '{$parameter->getName()}' of type '$type' in the $class_name class");
            }
            
            $dependencies[] = $this->get( (string)$type );
        }
        
        // pretty_print( $dependencies );
        
        return new $class_name( ...$dependencies );            
    }    
}
