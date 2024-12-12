<?php
class Database {
    private $host = 'localhost'; // Your host, typically localhost
    private $dbName = 'my_music'; // Your database name
    private $username = 'root'; // Your database username (change if necessary)
    private $password = ''; // Your database password (change if necessary)
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=$this->host;dbname=$this->dbName",
                $this->username,
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function prepare($query) {
        return $this->pdo->prepare($query);
    }
}
?>
