<?php
require_once __DIR__ . '/Database.php';

class GenreModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // List all genres
    public function getAll() {
        $sql = "SELECT * FROM genres";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single genre
    public function getById($id) {
        $sql = "SELECT * FROM genres WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add new genre
    public function add($data) {
        $sql = "INSERT INTO genres (name) VALUES (:name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $data['name']]);
        return $this->conn->lastInsertId();
    }

    // Update genre
    public function update($id, $data) {
        $sql = "UPDATE genres SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name']
        ]);
    }

    // Delete genre
    public function delete($id) {
        $sql = "DELETE FROM genres WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>
