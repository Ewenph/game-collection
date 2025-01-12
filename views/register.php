<?php
require_once __DIR__ . '/../controllers/RegisterController.php';

$controller = new RegisterController();
$controller->register();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="/views/style/common.css">
</head>
<body>
    <main>
        <form action="/register" method="POST">
            <h1>Inscription</h1>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <label for="lastname">Nom :</label>
            <input type="text" id="lastname" name="lastname" required>
            
            <label for="firstname">Prénom :</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm-password">Confirmation du mot de passe :</label>
            <input type="password" id="confirm-password" name="confirm_password" required>
            
            <button type="submit">S'inscrire</button>
            <a href="/login" class="logout-spacing">Se connecter</a>
        </form>
    </main>
    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>