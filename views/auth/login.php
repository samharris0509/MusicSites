<?php require_once __DIR__ . '/../partials/header.php'; ?>
<link rel="stylesheet" href="css/styles.css">

<h2>Login</h2>

<form method="POST" action="auth/process_login.php"></form>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php" class="button-link">Register</a>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
