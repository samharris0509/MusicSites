<?php
require_once 'models/ArtistModel.php';

class ArtistController extends BaseController {
    private $artistModel;

    public function __construct() {
        $this->artistModel = new ArtistModel();
    }

    public function listArtists() {
        $artists = $this->artistModel->getAllArtists();
        $this->render('artist/list', ['artists' => $artists]);
    }

    // Add a new artist
    public function addArtist() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];

            // Validate artist name
            if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
                $this->render('artist/add', ['error' => 'Invalid name. Only letters and spaces are allowed.']);
                return;
            }

            // Insert the new artist into the database
            $this->artistModel->addArtist([
                'name' => $name, 
                'description' => $description
            ]);

            // Redirect to the artist list page after adding the artist
            header('Location: /artist/list');
            exit();
        }

        // Render the add artist form
        $this->render('artist/add');
    }
}
?>