<?php 



require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../../models/ArtistModel.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $artistModel = new ArtistModel();
    
    $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description']
    ];
    
    $artistModel->add($data);
    
    // Set success message
    $success = "Artist successfully added to the database!";
}
?>

<link rel="stylesheet" href="../css/styles.css">

<h2>Add a New Artist</h2>

<?php if (isset($success)): ?>
    <div class="success-message">
        <?= $success ?>
    </div>
<?php endif; ?>

<!-- Form to add a new artist -->
<form method="POST" action="">
    <div class="form-group">
        <label for="name">Artist Name:</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
    </div>

    <div class="button-group">
        <button type="submit">Add Artist</button>
        <a href="list.php" class="button-link">Back to Artist List</a>
    </div>
</form>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
