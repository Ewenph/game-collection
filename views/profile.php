<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà démarrée
}
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../models/User.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userModel = new User();
$user = $userModel->findById($_SESSION['user_id']);

if (!$user) {
    // Si l'utilisateur n'est pas trouvé, redirige vers la page de connexion
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_account'])) {
        // Suppression du compte
        $userModel->delete($_SESSION['user_id']);
        session_destroy();
        header('Location: /register');
        exit;
    } else {
        // Mise à jour du profil
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password === $confirm_password) {
            $userModel->update($_SESSION['user_id'], $lastname, $firstname, $email, $password);
            header('Location: /profile');
            exit;
        } else {
            $error = 'Les mots de passe ne correspondent pas';
        }
    }
}
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
    <main style="padding-top: 60px;"> <!-- Ajout de padding-top pour éviter le chevauchement -->
        <form action="/profile" method="POST">
            <h1>Mon profil</h1>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
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

            <button type="submit">Modifier</button>
            <button type="submit" name="delete_account" class="delete-button">Supprimer mon compte</button>
            <a href="/login" class="logout-button">Se déconnecter</a>
        </form>
    </main>
    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>