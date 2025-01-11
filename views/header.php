<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Collection</title>
    <link rel="stylesheet" href="/game-collection/views/style/header.css">
</head>
<body>
    <header class="header">
        <nav>
            <ul class="nav-list">
                <li><a href="/game-collection/views/home.php"><img src="/game-collection/assets/logo.png" alt="Logo" class="logo"></a></li>
                <li><a href="/game-collection/views/games.php">MA BIBLIOTHÈQUE</a></li>
                <li><a href="/game-collection/views/add_game.php">AJOUTER UN JEU</a></li>
                <li><a href="/game-collection/views/leaderboard.php">CLASSEMENT</a></li>
                <li><a href="/game-collection/views/profile.php">PROFIL</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
