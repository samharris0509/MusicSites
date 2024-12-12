<?php
require_once __DIR__ . '/Database.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);


class SongModel {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // List all songs with optional filters
    public function getAll($filters = []) {
        
            $sql = "SELECT 
                    songs.id, 
                    songs.title, 
                    albums.title AS album, 
                    genres.name AS genre, 
                    COALESCE(artists.name, 'Unknown') AS artist 
                FROM songs
                LEFT JOIN albums ON songs.album_id = albums.id
                LEFT JOIN genres ON songs.genre_id = genres.id
                LEFT JOIN artists ON songs.artist_id = artists.id";
        
        
        $params = [];

        if (!empty($filters)) {
            $clauses = [];
            if (!empty($filters['album_id'])) {
                $clauses[] = 'songs.album_id = :album_id';
                $params['album_id'] = $filters['album_id'];
            }
            if (!empty($filters['artist_id'])) {
                $clauses[] = 'songs.artist_id = :artist_id';
                $params['artist_id'] = $filters['artist_id'];
            }
            if (!empty($filters['genre_id'])) {
                $clauses[] = 'songs.genre_id = :genre_id';
                $params['genre_id'] = $filters['genre_id'];
            }

            if (count($clauses) > 0) {
                $sql .= ' WHERE ' . implode(' AND ', $clauses);
            }
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single song with related data
    public function getById($id) {
        $sql = "SELECT songs.*, albums.title AS album, genres.name AS genre, artists.name AS artist 
                FROM songs
                JOIN albums ON songs.album_id = albums.id
                JOIN genres ON songs.genre_id = genres.id
                JOIN artists ON songs.artist_id = artists.id
                WHERE songs.id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add new song with related data
    public function add($data) {
        $artist_id = $this->getOrCreateArtist($data['artist']);
        $album_id = $this->getOrCreateAlbum($data['album']);
        $genre_id = $this->getOrCreateGenre($data['genre']);

        $sql = "INSERT INTO songs (title, artist_id, album_id, genre_id, release_date) 
                VALUES (:title, :artist_id, :album_id, :genre_id, :release_date)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'artist_id' => $artist_id,
            'album_id' => $album_id,
            'genre_id' => $genre_id,
            'release_date' => $data['release_date']
        ]);
        return $this->conn->lastInsertId();
    }

    // Update existing song
    public function update($id, $data) {
        $artist_id = $this->getOrCreateArtist($data['artist']);
        $album_id = $this->getOrCreateAlbum($data['album']);
        $genre_id = $this->getOrCreateGenre($data['genre']);
    
        $sql = "UPDATE songs SET 
                title = :title,
                artist_id = :artist_id,
                album_id = :album_id,
                genre_id = :genre_id
                WHERE id = :id";
    
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'artist_id' => $artist_id,
            'album_id' => $album_id,
            'genre_id' => $genre_id
        ]);
    }
    

    // Delete song
    public function delete($id) {
        $sql = "DELETE FROM songs WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    private function getOrCreateArtist($name) {
        $sql = "SELECT id FROM artists WHERE name = :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result['id'];
        }
        
        $sql = "INSERT INTO artists (name) VALUES (:name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $this->conn->lastInsertId();
    }

    private function getOrCreateAlbum($title) {
        $sql = "SELECT id FROM albums WHERE title = :title";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['title' => $title]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result['id'];
        }
        
        $sql = "INSERT INTO albums (title) VALUES (:title)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['title' => $title]);
        return $this->conn->lastInsertId();
    }

    private function getOrCreateGenre($name) {
        $sql = "SELECT id FROM genres WHERE name = :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result['id'];
        }
        
        $sql = "INSERT INTO genres (name) VALUES (:name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $this->conn->lastInsertId();
    }
}
