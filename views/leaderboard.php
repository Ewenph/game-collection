<?php
require_once __DIR__ . '/../controllers/LeaderboardController.php';

$controller = new LeaderboardController();
$users = $controller->selecInfo();

require_once __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement</title>
    <link rel="stylesheet" href="/game-collection/views/style/common.css">
</head>
<body>
<main>
    <h1>Classement des utilisateurs</h1>
    <table>
        <thead>
            <tr>
                <th>Utilisateur</th>
                <th>Temps de jeu total</th>
                <th>Jeu préféré</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['Prenom'] . ' ' . $user['Nom']) ?></td>
                    <td><?= htmlspecialchars($user['Total_Heures']) ?> heures</td>
                    <td><?= htmlspecialchars($user['Jeu_Prefere'] ?? 'Aucun jeu') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<footer>
    Game Collection - 2024 - Tous droits réservés
</footer>
</body>
</html>