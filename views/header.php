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
    <!-- Importation des styles -->
    <link rel="stylesheet" href="/views/style/header.css">
</head>
<body>
    <header class="header">
        <nav>
            <ul class="nav-list">
                <!-- Liens de navigation -->
                <li><a href="/home" class="logo-link"><img src="/assets/logo.png" alt="Logo" class="logo"></a></li>
                <li><a href="/home">MA BIBLIOTHÈQUE</a></li>
                <li><a href="/games">AJOUTER UN JEU</a></li>
                <li><a href="/leaderboard">CLASSEMENT</a></li>
                <li><a href="/profile">PROFIL</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>