<?php
require_once __DIR__ . '/../controllers/GameController.php';

$controller = new GameController();
$controller->modifyGame();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un jeu</title>
    <link rel="stylesheet" href="/views/style/add_game.css">
</head>
<body>
<main>
    <h1><?= htmlspecialchars($game['Nom_jeu']) ?></h1>
    <p><?= htmlspecialchars($game['Desc_jeu'] ?? 'Pas de description disponible') ?></p>
    <p>Temps passé : <?= htmlspecialchars($game['Temps_jeu']) ?> h</p>

    <section>
        <h2>Ajouter du temps passé sur le jeu</h2>
        <form action="/update-time" method="POST">
            <label for="temps">Temps passé sur le jeu</label>
            <input type="number" id="temps" name="temps" value="<?= htmlspecialchars($game['Temps_jeu']) ?>" min="1" required>
            <input type="hidden" name="id_jeu" value="<?= htmlspecialchars($game['Id_jeu']) ?>">
            <button type="submit">Ajouter</button>
        </form>
    </section>

    <form action="/delete-game" method="POST">
        <input type="hidden" name="id_jeu" value="<?= htmlspecialchars($game['Id_jeu']) ?>">
        <button type="submit" class="delete-button">Supprimer le jeu de ma bibliothèque</button>
    </form>
</main>
</body>
<footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>