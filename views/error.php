<?php require_once __DIR__ . '/partials/header.php'; ?>

<h2>Error</h2>

<p><?= isset($errorMessage) ? htmlspecialchars($errorMessage) : "An unexpected error occurred. Please try again later."; ?></p>

<a href="/home">Go back to the Home Page</a>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
