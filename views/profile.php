<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà démarrée
}
require_once __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    <link rel="stylesheet" href="/views/style/common.css">
</head>
<body>
    <main>
        <form action="/profile" method="POST">
            <h1>Mon profil</h1>
            <!-- Affichage des erreurs -->
            <?php if (!empty($errors)): ?>
                <div>
                    <?php foreach ($errors as $error): ?>
                        <p class="error"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire de mise à jour du profil -->
            <label for="lastname">Nom :</label>
            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($user['Nom_uti']) ?>" required>

            <label for="firstname">Prénom :</label>
            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($user['Pren_uti']) ?>" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['Mail_uti']) ?>" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm-password">Confirmation du mot de passe :</label>
            <input type="password" id="confirm-password" name="confirm_password" required>

            <!-- Boutons de soumission -->
            <button type="submit">Modifier</button>
            <button type="submit" name="delete_account" class="delete-button">Supprimer mon compte</button>
            <a href="/login" class="logout-button logout-spacing">Se déconnecter</a>
        </form>
    </main>
    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>