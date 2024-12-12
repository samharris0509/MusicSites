<?php
require_once __DIR__ . '/Database.php';

class ArtistModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }



    // Get all artists from the database
    public function getAll() {
        $sql = "SELECT * FROM artists";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get an artist by ID
    public function getById($id) {
        $sql = "SELECT id, name, description, created_at FROM artists WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get an artist by name
    public function getByName($name) {
        $sql = "SELECT id, name, description FROM artists WHERE name = :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new artist to the database
    public function add($data) {
        $sql = "INSERT INTO artists (name, description, created_at) VALUES (:name, :description, NOW())";
        $stmt = $this->conn->prepare($sql);
        
        // Use a default description if not provided
        $description = $data['description'] ?? 'No description available';
        
        $stmt->execute([
            'name' => $data['name'],
            'description' => $description
        ]);
        
        return $this->conn->lastInsertId();
    }

    // Update existing artist
    public function update($id, $data) {
        $sql = "UPDATE artists SET 
                name = :name, 
                description = :description 
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? 'No description available'
        ]);
    }

    // Delete artist
    public function delete($id) {
        $sql = "DELETE FROM artists WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>
