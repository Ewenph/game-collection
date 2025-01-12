<?php
require_once __DIR__ . '/../controllers/LeaderboardController.php';

$controller = new LeaderboardController();
$controller->index();

require_once __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement</title>
    <link rel="stylesheet" href="/views/style/leaderboard.css">
</head>
<body>
<main>
    <h1>Classement des temps passés</h1>
    <table>
        <thead>
            <tr>
                <th>Joueur</th>
                <th>Temps passés</th>
                <th>Jeu favori</th>
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
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>