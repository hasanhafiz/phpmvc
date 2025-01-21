<?php 
declare( strict_types = 1 );

namespace App\Models;
use PDO;
use Framework\Model;

class Product extends Model{
    protected $table = 'products';
    
    protected function validate( array $data ): void {
        if ( empty($data['name']) ) {
            $this->addError("name", "Name field is required");
        }
    } 
    
    public function getTotal(): int {
        $sql = "SELECT COUNT(*) as total FROM products";
        $conn = $this->db->getConnection();
        
        $stmt = $conn->query( $sql );
        $row = $stmt->fetch( PDO::FETCH_OBJ );
        
        // pretty_print( $row );
        return $row->total;
    }
    
}