<?php 
declare( strict_types = 1 );

namespace App;
use PDO;

class Database {
    
    private ?PDO $pdo = null;
    public function __construct(
        private string $host,
        private string $dbname,
        private string $user,
        private string $password
    )
    {
        // var_dump( $this->host );
        // echo "A database object has been created! \n";
    }
    
    public function getConnection(): PDO {
        
        if ( $this->pdo === null ){
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};chartset=utf8;port=3306";
        
            $this->pdo = new PDO( $dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);              
        }
        
        return $this->pdo;
    }
}