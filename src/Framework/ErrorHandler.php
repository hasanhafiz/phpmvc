<?php 
declare( strict_types = 1 );
  
namespace Framework;
use Throwable;
use ErrorException;
use Framework\Exceptions\PageNotFoundException;

class ErrorHandler {
    public static function handleError(
        int $errorno,
        string $errorstr,
        string $errorfile,
        int $errorline
    ): bool
    {
        throw new ErrorException( $errorstr, 0, $errorno, $errorfile, $errorline );
    }
    
    public static function handleException( Throwable $exception ){
    
        if ( $exception instanceof PageNotFoundException ) {
            http_response_code(400);
            $template = "400.php";
        } else {
            http_response_code(500);
            $template = "500.php";  
        }        
 
        if ( $_ENV["SHOW_ERRORS"] ) {
            ini_set("display_errors", "1");
        } else {
            ini_set("display_errors", "0");
            ini_set( "log_errors", "1" );
            // echo ini_get( "error_log" );
            require ROOT_PATH . "/views/$template";
        }
        throw $exception;
    }
}