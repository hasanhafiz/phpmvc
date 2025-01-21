<?php 
declare( strict_types = 1 );

namespace Framework;
use App\Database;
use PDO;

abstract class Model {
    
    protected $table;
    protected $errors = [];    
    
    public function __construct(protected Database $db)
    {
        $this->table = $this->getTableName();
        // pretty_print( $this->table );
    }
    
    protected function validate( array $data ): void
    {
    }
    protected function addError( string $field, string $message ): void {
        $this->errors[$field] = $message;
    }    
    
    public function getErrors(): array|null {
        return $this->errors;
    }
    
    public function getInsertId() {
        $conn = $this->db->getConnection();
        return $conn->lastInsertId();
    }
    private function getTableName(): string {
        
        if ( $this->table !== null ) {
            return $this->table;
        }
        
        $parts = explode( "\\", $this::class );
        $table = array_pop( $parts );
        return $this->table = strtolower($table) . 's'; // table name will be alwasys plural
    }
    
    public function findAll(): array {
        
        $pdo = $this->db->getConnection();
        
        $stmt = $pdo->query("SELECT * FROM {$this->table} ORDER BY name");
        return $stmt->fetchAll( PDO::FETCH_ASSOC );        
    }
    
    public function find(string $id ): object|bool {
        
        $conn = $this->db->getConnection();        
        $sql = "SELECT * FROM {$this->table} WHERE id=:id";
        $stmt = $conn->prepare( $sql );
        $stmt->bindValue( ":id", $id, PDO::PARAM_INT );
        $stmt->execute();
        return $stmt->fetch( PDO::FETCH_OBJ );
    }
    
    public function insert( array $data ): bool {        
        
        $this->validate( $data );
        if ( ! empty( $this->errors ) ) {
            return false;
        }
        
        $columns = implode(", ", array_keys( $data ));
        $placeholders = implode(", ", array_fill( 0, count($data), "?" ));
        
        $sql = "INSERT INTO {$this->getTableName()} ($columns)
        VALUES ($placeholders)";
        
        $conn = $this->db->getConnection();   
        $stmt = $conn->prepare( $sql ); 
        
        $i = 1;
        foreach ($data as $value) {
            $type = match( gettype($value) ) {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };            
            $stmt->bindValue($i++, $value, $type);
        }
        return $stmt->execute();
    }
    
    public function update( string $id, array $data ): bool {        
        
        $this->validate( $data );
        if ( ! empty( $this->errors ) ) {
            return false;
        }
        
        // UPDATE products SET name='?', description = '?' WHERE id = 2;
        // UPDATE products SET name='new name', description = 'new description' WHERE id = 2;
        
        $sql = "UPDATE {$this->getTableName()} ";
        unset( $data['id'] );
        $assignments = array_keys( $data );
        
        array_walk( $assignments, function( &$value ){
            $value = "$value = ?";
        });
        
        $sql .= "SET " . implode( ", ", $assignments );
        $sql .= " WHERE id = ?";
        // exit( $sql );
        
        $conn = $this->db->getConnection();   
        $stmt = $conn->prepare( $sql ); 
        
        $i = 1;
        foreach ($data as $value) {
            $type = match( gettype($value) ) {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };            
            $stmt->bindValue($i++, $value, $type);
        }
        
        $stmt->bindValue($i, $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }    
    
    public function destroy( string $id ): bool {        
        $sql = "DELETE FROM {$this->getTableName()} WHERE id = :id";
        $conn = $this->db->getConnection();   
        $stmt = $conn->prepare( $sql );  
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}