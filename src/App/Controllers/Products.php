<?php 
declare( strict_types = 1 );

namespace App\Controllers;

use Framework\Controller;
use App\Models\Product;
use Framework\Exceptions\PageNotFoundException;
use Framework\Response;

class Products extends Controller{   
    
    public function __construct(protected Product $product)
    {
        // pretty_print( $this->table, 'inside products controller' );
    }            
    public function index(): Response
    {
        $products = $this->product->findAll(); 
        $total = $this->product->getTotal();
        
        return $this->view( 'Products/index.php', [
            'title' => 'Products',
            'products' => $products, 
            'total' => $total
            ] );
    } 
    
    public function show( string $id ): Response
    {
        $product = $this->getProduct( $id );
        $body = $this->view( 'Products/show.php', ['product' => $product, 'title' => 'Product'] );
        return $body;
    }
    
    public function edit( string $id ): Response
    {
        $product = $this->getProduct( $id );
        return $this->view('Products/edit.php', ['product' => $product, 'title' => 'Edit Product'] );   
    }
    
    public function delete( string $id ): Response
    {
        $product = $this->getProduct( $id );
        return $this->view('Products/delete.php', ['product' => $product, 'title' => 'Delete Product'] );   
    }      
    
    public function getProduct( string $id ): object|null {
        $product = $this->product->find( $id );        
        if ( $product === false ) {
            throw new PageNotFoundException( "Product not found." );
        }
        return $product;        
    }
        
    public function showPage( string $title, string $id, string $page )
    {
        echo $title, " ", $id, " ", $page;
    }
    
    public function new(): Response {
        echo $this->viewer->render("shared/header.php", ['title' => 'Add New Product']);
        return $this->view("Products/new.php", ['title' => 'Add New Product']);
    }
    
    public function create(): Response {
        
        // $validated = $this->product->validate( $_POST );
        // var_dump( $validated );
        // pretty_print($this->request);
        // exit;
        $data = [
            'name' => $this->request->post['name'],
            'description' => empty( $this->request->post['description'] ) ? null : $this->request->post['description']
        ]; 
        
        $result = $this->product->insert( $data ) ; 
        if ( $result ) {
            return $this->redirect("/products/{$this->product->getInsertId()}/show");
        } else {
            return $this->view("Products/new.php", ['errors' => $this->product->getErrors(), 'title' => 'Add New Product']);            
        }
    }
    
        public function update( string $id ): Response {
            
        $product = $this->getProduct( $id );
        $product->name = $this->request->post['name'];
        $product->description = empty( $this->request->post['description'] ) ? null : $this->request->post['description'];
        
        $result = $this->product->update( $id, (array)$product ) ; 
        if ( $result ) {
            return $this->redirect("/products/{$id}/show");
        } else {
            return $this->view("Products/edit.php", [
                'errors' => $this->product->getErrors(),
                'product' => $product,
                'title' => 'Edit Product'
            ]);            
        }
    }
        
    public function destroy( string $id ): Response {
        
        $this->getProduct( $id ); 
        
        $result = $this->product->destroy( $id ) ; 
        if ( $result ) {
            return $this->redirect("/products/index");
        } else {
            return $this->view("Products/new.php", ['errors' => $this->product->getErrors(), 'title' => 'Add New Product']);            
        }
    }

}