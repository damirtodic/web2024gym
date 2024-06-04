<?php
require_once __DIR__ . "/../config.php";

class BaseDao{
    protected $connection;
    private $table;

    public function __construct($table){
        $this->table = $table;
        try{
            $this->connection = new PDO(
                "mysql:host=" . Config::DB_HOST() . ";dbname=" . Config::DB_NAME() . ";port=" . Config::DB_PORT(),
                 Config::DB_USER(),
                 Config::DB_PASSWORD(),
                 [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                 ]
                 );
        }
        catch(PDOException $e){
            throw $e;
        }
    }
}
?>