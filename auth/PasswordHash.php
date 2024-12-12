<?php
// Registration Example:
$password = 'user_password'; // the plain text password entered by the user
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username, $email, $hashedPassword]);

// Login Example:
$enteredPassword = 'user_password'; // the password entered by the user during login
// Fetch the stored hash from the database
$storedHash = $user['password']; // This comes from the database query

if (password_verify($enteredPassword, $storedHash)) {
    // Password is correct
} else {
    // Invalid password
}

