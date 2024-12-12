<?php
require_once 'BaseController.php';
require_once __DIR__ . '/../models/SongModel.php';
require_once __DIR__ . '/../models/AlbumModel.php';
require_once __DIR__ . '/../models/ArtistModel.php';
require_once __DIR__ . '/../models/GenreModel.php';

class SongController extends BaseController {
    private $songModel;
    private $albumModel;
    private $artistModel;
    private $genreModel;

    public function __construct() {
        // Initialize the models, assuming they are connected to the `my_music` database
        $this->songModel = new SongModel();
        $this->albumModel = new AlbumModel();
        $this->artistModel = new ArtistModel();
        $this->genreModel = new GenreModel();
    }

    // List all songs, optionally filtered by album, artist, or genre
    public function listSongs() {
        $filters = [];

        // Apply filters based on GET parameters
        if (isset($_GET['album_id']) && $_GET['album_id'] != '') {
            $filters['album_id'] = $_GET['album_id'];
        }
        if (isset($_GET['artist_id']) && $_GET['artist_id'] != '') {
            $filters['artist_id'] = $_GET['artist_id'];
        }
        if (isset($_GET['genre_id']) && $_GET['genre_id'] != '') {
            $filters['genre_id'] = $_GET['genre_id'];
        }

        // Get filtered song list from model
        $songs = $this->songModel->getAllSongs($filters);
        $albums = $this->albumModel->getAllAlbums();
        $artists = $this->artistModel->getAllArtists();
        $genres = $this->genreModel->getAllGenres();

        // Pass data to the view
        $this->render('song/list', [
            'songs' => $songs,
            'albums' => $albums,
            'artists' => $artists,
            'genres' => $genres
        ]);
    }

    // View details of a specific song
    public function viewSong($id) {
        // Get the song details by ID
        $song = $this->songModel->getSongById($id);

        if (!$song) {
            // If song not found, show an error
            echo "Song not found!";
            return;
        }

        // Pass song details to the view
        $this->render('song/view', ['song' => $song]);
    }

    // Add a new song
    public function addSong() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and validate inputs
            $title = $_POST['title'] ?? '';
            $artist_id = $_POST['artist_id'] ?? '';
            $album_id = $_POST['album_id'] ?? '';
            $genre_id = $_POST['genre_id'] ?? '';
            $release_date = $_POST['release_date'] ?? '';

            // Validate the inputs
            if (!preg_match("/^[a-zA-Z0-9\s]+$/", $title)) {
                echo "Invalid title. Only letters, numbers, and spaces are allowed.";
                return;
            }
            if (empty($title) || empty($artist_id) || empty($album_id) || empty($genre_id) || empty($release_date)) {
                echo "All fields are required.";
                return;
            }

            // Add the new song to the database
            $this->songModel->addSong([
                'title' => $title,
                'artist_id' => $artist_id,
                'album_id' => $album_id,
                'genre_id' => $genre_id,
                'release_date' => $release_date
            ]);

            // Redirect to the song list after adding
            header("Location: /song/list");
            exit();
        }

        // Get albums, artists, and genres for dropdowns in the add form
        $albums = $this->albumModel->getAllAlbums();
        $artists = $this->artistModel->getAllArtists();
        $genres = $this->genreModel->getAllGenres();

        // Pass data to the view
        $this->render('song/add', [
            'albums' => $albums,
            'artists' => $artists,
            'genres' => $genres
        ]);
    }
}
?>
