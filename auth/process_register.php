<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        header('Location: /register.php?error=password_mismatch');
        exit();
    }

    if ($auth->register($username, $email, $password)) {
        header('Location: /login.php?success=registered');
        exit();
    } else {
        header('Location: /register.php?error=registration_failed');
        exit();
    }
}
