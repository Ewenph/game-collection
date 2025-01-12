<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà démarrée
}
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userModel = new User();
    $user = $userModel->findByEmail($email);

    if ($user && password_verify($password, $user['Mdp_uti'])) {
        $_SESSION['user_id'] = $user['Id_uti'];
        header('Location: /home');
        exit;
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/views/style/common.css">
</head>
<body>
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
    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>