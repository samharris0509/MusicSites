<?php
require_once __DIR__ . '/Database.php';

class AlbumModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // List all albums
    public function getAll() {
        $sql = "SELECT * FROM albums";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single album
    public function getById($id) {
        $sql = "SELECT * FROM albums WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add new album
    public function add($data) {
        $sql = "INSERT INTO albums (title, artist_id, release_date) 
                VALUES (:title, :artist_id, :release_date)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'artist_id' => $data['artist_id'],
            'release_date' => $data['release_date'] ?? date('Y-m-d')
        ]);
        return $this->conn->lastInsertId();
    }

    // Update album
    public function update($id, $data) {
        $sql = "UPDATE albums SET 
                title = :title,
                artist_id = :artist_id,
                release_date = :release_date 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'artist_id' => $data['artist_id'],
            'release_date' => $data['release_date'] ?? date('Y-m-d')
        ]);
    }

    // Delete album
    public function delete($id) {
        $sql = "DELETE FROM albums WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>
