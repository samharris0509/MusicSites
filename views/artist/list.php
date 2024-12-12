<?php



require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../../models/ArtistModel.php';
require_once __DIR__ . '/../../includes/session.php';
requireLogin();

$_artistModel = new ArtistModel();

// Handle Delete Action
if (isset($_POST['delete'])) {
    $_artistModel->delete($_POST['id']);
    header('Location: list.php');
    exit;
}

// Handle Update Action
if (isset($_POST['update'])) {
    $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description']
    ];
    $_artistModel->update($_POST['id'], $data);
    header('Location: list.php');
    exit;
}

// Get filter value if set
$filter_id = isset($_GET['artist_id']) ? $_GET['artist_id'] : null;
$all_artists = $_artistModel->getAll();
$artists = $filter_id ? [$_artistModel->getById($filter_id)] : $all_artists;
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
    }
    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        width: 70%;
        border-radius: 5px;
    }
    .action-buttons {
        margin-left: 10px;
        display: inline-block;
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
</style>

<h2>Artists</h2>

<!-- Filter Form -->
<form method="GET" action="">
    <label for="artist_id">Filter by Artist:</label>
    <select name="artist_id" id="artist_id">
        <option value="">All</option>
        <?php foreach ($all_artists as $artist): ?>
            <option value="<?= $artist['id']; ?>" <?= isset($_GET['artist_id']) && $_GET['artist_id'] == $artist['id'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($artist['name'] ?? ''); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Filter</button>
    <?php if (isset($_GET['artist_id'])): ?>
        <a href="?" class="button-link">Clear Filter</a>
    <?php endif; ?>
</form>

<a href="add.php" class="button-link">Add New Artist</a>

<!-- Artists List -->
<ul>
    <?php if (!empty($artists)): ?>
        <?php foreach ($artists as $artist): ?>
            <li>
                <strong><?= htmlspecialchars($artist['name'] ?? '') ?></strong>
                <p><?= htmlspecialchars($artist['description'] ?? '') ?></p>
                <div class="action-buttons">
                    <button class="action-button update-button" onclick="openUpdateModal(<?= $artist['id'] ?>, '<?= htmlspecialchars($artist['name'] ?? '') ?>', '<?= htmlspecialchars($artist['description'] ?? '') ?>')">Update</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $artist['id'] ?>">
                        <button type="submit" name="delete" class="action-button delete-button" onclick="return confirm('Are you sure you want to delete this artist?')">Delete</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No artists available.</p>
    <?php endif; ?>
</ul>

<!-- Update Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <h3>Update Artist</h3>
        <form method="POST">
            <input type="hidden" name="id" id="updateId">
            <div>
                <label for="updateName">Name:</label>
                <input type="text" id="updateName" name="name" required>
            </div>
            <div>
                <label for="updateDescription">Description:</label>
                <textarea id="updateDescription" name="description" required></textarea>
            </div>
            <button type="submit" name="update">Update</button>
            <button type="button" onclick="closeUpdateModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function openUpdateModal(id, name, description) {
    document.getElementById('updateModal').style.display = 'block';
    document.getElementById('updateId').value = id;
    document.getElementById('updateName').value = name;
    document.getElementById('updateDescription').value = description;
}

function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == document.getElementById('updateModal')) {
        closeUpdateModal();
    }
}
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>