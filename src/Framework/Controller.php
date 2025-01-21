<?php 

declare(strict_types = 1);

namespace Framework;

use Framework\Request;
use Framework\Response;

abstract class Controller {
    
    protected Request $request;
    
    protected Response $response;
    
    protected TemplateViewerInterface $viewer;
    
    public function setRequest( Request $request ): void {
        $this->request = $request;
    }    
    
    public function setViewer( TemplateViewerInterface $viewer ): void {
        $this->viewer = $viewer;
    }
    
    public function setResponse( Response $response ): void {
        $this->response = $response;
    }
    
    protected function view( string $template, array $data = [] ): Response {        
        $this->response->setBody( $this->viewer->render( $template, $data )); 
        return $this->response;        
    }
    
    public function redirect( string $url ): Response {
        $this->response->redirect( $url );
        // pretty_print( $this->response, "Controller:redirect()" );
        return $this->response;
    } 
}