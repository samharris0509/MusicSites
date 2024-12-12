<?php
require_once __DIR__ . '/../../includes/session.php';
?>


<!DOCTYPE html>
<html>
<head>
    <title>Music Site</title>
    <link rel="stylesheet" href="http://localhost:8080/MusicSites/css/styles.css">
</head>
<body>
    <header>
        <h1>Music Site</h1>
        <nav>
            <a href="http://localhost:8080/MusicSites/index.php">Home</a>
            <a href="http://localhost:8080/MusicSites/views/song/list.php">Songs</a>
            <a href="http://localhost:8080/MusicSites/views/artist/list.php">Artists</a>
            <a href="http://localhost:8080/MusicSites/views/auth/login.php">Login</a>
        </nav>
    </header>
    <main>
    <?php if (isLoggedIn()): ?>
        <div class="user-info">
            Welcome, <?= htmlspecialchars($_SESSION['username']) ?> | 
            <a href="/auth/logout.php">Logout</a>
        </div>
    <?php endif; ?>