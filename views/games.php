<?php require_once __DIR__ . '/header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Jeux</title>
</head>
<body>
<h1>Classement des temps passés</h1>
<table>
        <thead>
            <tr>
                <th>Joueur</th>
                <th>Temps_passés</th>
                <th>Jeu favori</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jeux as $jeu): ?>
                <tr>
                    <td><?= htmlspecialchars($jeu['Id_uti']) ?></td>
                    <td><?= htmlspecialchars($jeu['Temps_jeu']) ?></td>
                    <td><?= htmlspecialchars($jeu['Editeur_jeu']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>