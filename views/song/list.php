<?php


require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../../models/SongModel.php';
require_once __DIR__ . '/../../models/ArtistModel.php';
require_once __DIR__ . '/../../models/GenreModel.php';
require_once __DIR__ . '/../../models/AlbumModel.php';
require_once __DIR__ . '/../../includes/session.php';
requireLogin();



error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize models
$_songModel = new SongModel();
$_artistModel = new ArtistModel();
$_genreModel = new GenreModel();
$_albumModel = new AlbumModel();

// Handle Delete Action
if (isset($_POST['delete'])) {
    $_songModel->delete($_POST['id']);
    header('Location: list.php');
    exit;
}

// Handle Update Action
if (isset($_POST['update'])) {
    $data = [
        'title' => $_POST['title'],
        'artist' => $_POST['artist'],
        'album' => $_POST['album'],
        'genre' => $_POST['genre']
    ];
    $_songModel->update($_POST['id'], $data);
    header('Location: list.php');
    exit;
}

// Get all filter options
$artists = $_artistModel->getAll();
$genres = $_genreModel->getAll();
$albums = $_albumModel->getAll();

// Get filter values if set
$filters = [];
if (isset($_GET['artist_id']) && !empty($_GET['artist_id'])) {
    $filters['artist_id'] = $_GET['artist_id'];
}
if (isset($_GET['genre_id']) && !empty($_GET['genre_id'])) {
    $filters['genre_id'] = $_GET['genre_id'];
}
if (isset($_GET['album_id']) && !empty($_GET['album_id'])) {
    $filters['album_id'] = $_GET['album_id'];
}

// Get filtered songs
$songs = $_songModel->getAll($filters);
?>

<link rel="stylesheet" href="../css/styles.css">
<style>
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000;
    }
    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        width: 70%;
        border-radius: 5px;
    }
    .action-buttons {
        margin-top: 10px;
    }
    .action-button {
        padding: 5px 10px;
        margin: 0 5px;
        border-radius: 3px;
        cursor: pointer;
    }
    .update-button {
        background-color: #4CAF50;
        color: white;
        border: none;
    }
    .delete-button {
        background-color: #f44336;
        color: white;
        border: none;
    }
    .song-info {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
</style>

<h2>Song List</h2>

<form method="GET" action="list.php">
    <div class="filter-section">
        <!-- Artist Filter -->
        <div class="filter-group">
            <label for="artist_id">Filter by Artist:</label>
            <select name="artist_id" id="artist_id">
                <option value="">All Artists</option>
                <?php foreach ($artists as $artist): ?>
                    <option value="<?= $artist['id']; ?>" <?= isset($_GET['artist_id']) && $_GET['artist_id'] == $artist['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($artist['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Genre Filter -->
        <div class="filter-group">
            <label for="genre_id">Filter by Genre:</label>
            <select name="genre_id" id="genre_id">
                <option value="">All Genres</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id']; ?>" <?= isset($_GET['genre_id']) && $_GET['genre_id'] == $genre['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($genre['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Album Filter -->
        <div class="filter-group">
            <label for="album_id">Filter by Album:</label>
            <select name="album_id" id="album_id">
                <option value="">All Albums</option>
                <?php foreach ($albums as $album): ?>
                    <option value="<?= $album['id']; ?>" <?= isset($_GET['album_id']) && $_GET['album_id'] == $album['id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($album['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-buttons">
            <button type="submit">Apply Filters</button>
            <?php if (!empty($_GET)): ?>
                <a href="list.php" class="button-link">Clear Filters</a>
            <?php endif; ?>
        </div>
    </div>
</form>

<a href="add.php" class="button-link">Add New Song</a>

<ul style="list-style: none; padding: 0;">
    <?php if (!empty($songs)): ?>
        <?php foreach ($songs as $song): ?>
            <li class="song-info">
                <div>
                    <div><span style="font-weight: bold;">Title:</span> <?= htmlspecialchars($song['title'] ?? '') ?></div>
                    <div><span style="font-weight: bold;">Artist:</span> <?= htmlspecialchars($song['artist'] ?? 'Unknown') ?></div>
                    <div><span style="font-weight: bold;">Album:</span> <?= htmlspecialchars($song['album'] ?? '') ?></div>
                    <div><span style="font-weight: bold;">Genre:</span> <?= htmlspecialchars($song['genre'] ?? '') ?></div>
                </div>
                <div class="action-buttons">
                    <button class="action-button update-button" onclick="openUpdateModal(<?= $song['id'] ?>, '<?= htmlspecialchars($song['title'] ?? '') ?>', '<?= htmlspecialchars($song['artist'] ?? '') ?>', '<?= htmlspecialchars($song['album'] ?? '') ?>', '<?= htmlspecialchars($song['genre'] ?? '') ?>')">Update</button>
                    <form method="POST" action="list.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $song['id'] ?>">
                        <button type="submit" name="delete" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this song?')">Delete</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No songs available.</p>
    <?php endif; ?>
</ul>

<!-- Update Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <h3>Update Song</h3>
        <form method="POST" action="list.php">
            <input type="hidden" name="id" id="updateId">
            <div>
                <label for="updateTitle">Title:</label>
                <input type="text" id="updateTitle" name="title" required>
            </div>
            <div>
                <label for="updateArtist">Artist:</label>
                <input type="text" id="updateArtist" name="artist" required>
            </div>
            <div>
                <label for="updateAlbum">Album:</label>
                <input type="text" id="updateAlbum" name="album" required>
            </div>
            <div>
                <label for="updateGenre">Genre:</label>
                <input type="text" id="updateGenre" name="genre" required>
            </div>
            <button type="submit" name="update">Update</button>
            <button type="button" onclick="closeUpdateModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function openUpdateModal(id, title, artist, album, genre) {
    document.getElementById('updateModal').style.display = 'block';
    document.getElementById('updateId').value = id;
    document.getElementById('updateTitle').value = title;
    document.getElementById('updateArtist').value = artist;
    document.getElementById('updateAlbum').value = album;
    document.getElementById('updateGenre').value = genre;
}

function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('updateModal')) {
        closeUpdateModal();
    }
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
