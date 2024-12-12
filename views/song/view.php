<?php require_once __DIR__ . '/../partials/header.php'; 
require_once __DIR__ . '/../includes/session.php';
requireLogin();?>


<!-- Link to CSS -->
<link rel="stylesheet" href="../css/styles.css">

<h2><?= htmlspecialchars($song['title']); ?></h2>
<p><strong>Artist:</strong> <?= htmlspecialchars($song['artist_name']); ?></p>
<p><strong>Album:</strong> <?= htmlspecialchars($song['album']); ?></p>
<p><strong>Genre:</strong> <?= htmlspecialchars($song['genre']); ?></p>
<p><strong>Release Date:</strong> <?= htmlspecialchars($song['release_date']); ?></p>

<!-- Link to return to the song list -->
<a href="/song/list">Back to Song List</a>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
