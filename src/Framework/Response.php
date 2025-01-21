<?php 

declare(strict_types = 1);

namespace Framework;

class Response {
    public string $body = "";
    protected array $headers = [];
    public function setBody( string $body ): void {
        $this->body = $body;
    }
    
    public function getBody(): string {
        return $this->body;
    }
    
    public function send(): void {        
        foreach ($this->headers as $header) {
            header( $header );
        }
        echo $this->body;
    }
    
    public function redirect( string $url ): void {
        $this->addHeader("Location: $url");
    }
    
    public function addHeader( string $header ): void {
        $this->headers[] = $header;
    }
}
