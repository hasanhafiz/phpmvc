<?php 

declare(strict_types = 1);

namespace Framework;

class Request {
    public string $uri;
    
    public array $get;
    public array $post;
    public array $files;
    public array $cookie;
    public array $server;
    
    public function __construct(string $uri,
                                array $get,
                                array $post,
                                array $files,
                                array $cookie,
                                array $server)
    {
        $this->uri = $uri;        
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
        $this->cookie = $cookie;
        $this->server = $server;
        
        // pretty_print($this);
        // exit;
    }
    
    // Explanation: self vs static
    // PHP new self vs new static: Now that we changed the code in our example to use static instead of self, you can see the difference is that self references the current class, whereas the static keyword allows the function to bind to the calling class at runtime. 
    // https://www.geeksforgeeks.org/new-self-vs-new-static-in-php/
    public static function createFromGlobals() {
        return new static(
            $_SERVER["REQUEST_URI"],
            $_GET,
            $_POST,
            $_FILES,
            $_COOKIE,
            $_SERVER
        );
    }
}
