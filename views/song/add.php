<?php 


require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../../models/SongModel.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $songModel = new SongModel();
    
    $data = [
        'title' => $_POST['title'],
        'artist' => $_POST['artist'],
        'album' => $_POST['album'],
        'genre' => $_POST['genre'],
        'release_date' => $_POST['release_date']
    ];
    
    $songModel->add($data);
    $success = "Song successfully added to the database!";
}
?>

<link rel="stylesheet" href="/css/styles.css">

<h2>Add a New Song</h2>

<?php if (isset($success)): ?>
    <div class="success-message">
        <?= $success ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <div class="form-group">
        <label for="title">Song Title:</label>
        <input type="text" id="title" name="title" required>
    </div>

    <div class="form-group">
        <label for="artist">Artist Name:</label>
        <input type="text" id="artist" name="artist" required>
    </div>

    <div class="form-group">
        <label for="album">Album Name:</label>
        <input type="text" id="album" name="album" required>
    </div>

    <div class="form-group">
        <label for="genre">Genre:</label>
        <input type="text" id="genre" name="genre" required>
    </div>

    <div class="form-group">
        <label for="release_date">Release Date:</label>
        <input type="date" id="release_date" name="release_date" required>
    </div>

    <div class="button-group">
        <button type="submit">Add Song</button>
        <a href="list.php" class="button-link">Back to Song List</a>
    </div>
</form>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>