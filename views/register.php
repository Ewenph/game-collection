<?php
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['lastname'];
    $prenom = $_POST['firstname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConfirmation = $_POST['confirm_password'];

    if ($password !== $passwordConfirmation) {
        $error = 'Les mots de passe ne correspondent pas';
    } else {
        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $error = 'Un utilisateur avec cet email existe déjà';
        } else {
            $userModel->create($nom, $prenom, $email, $password);
            header('Location: /login');
            exit;
        }
    }
}
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
        <a href="/login">Se connecter</a>
    </form>
    <footer>
        Game Collection - 2024 - Tous droits réservés
    </footer>
</body>
</html>