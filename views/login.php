<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà démarrée
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!-- Importation des styles -->
    <link rel="stylesheet" href="/views/style/common.css">
</head>
<body>
    <main>
        <!-- Formulaire de connexion -->
        <form action="/login" method="POST">
            <h1>Se connecter à Game Collection</h1>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Se connecter</button>
            <a href="/register" class="logout-spacing">S'inscrire</a>
        </form>
    </main>
    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>