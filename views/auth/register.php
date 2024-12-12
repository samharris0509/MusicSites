<?php require_once __DIR__ . '/../partials/header.php'; ?>
<link rel="stylesheet" href="css/styles.css">

<h2>Register</h2>

<form method="POST" action="auth/process_register.php"></form>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php" class="button-link">login</a>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>
