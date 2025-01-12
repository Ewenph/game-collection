<?php
require_once __DIR__ . '/../controllers/GameController.php';

$controller = new GameController();
$controller->modifyGame();


require_once __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un jeu</title>
    <link rel="stylesheet" href="/views/style/modify_game.css">
</head>
<body>
    <main>
        <section>
            <h1><?= htmlspecialchars($game['nom_jeu']) ?></h1>
            <p><?= htmlspecialchars($game['description'] ?? 'Pas de description disponible') ?></p>
            <p>Temps passé : <?= htmlspecialchars($game['temps_jeu']) ?> h</p>
            
            <h2>Ajouter du temps passé sur le jeu</h2>
            <form action="/update-time" method="POST">
                <label for="temps">Temps passé sur le jeu</label>
                <input type="number" id="temps" name="temps" value="<?= htmlspecialchars($game['temps_jeu']) ?>" min="1" required>
                <input type="hidden" name="id_jeu" value="<?= htmlspecialchars($game['id']) ?>">
                <button type="submit">Ajouter</button>
            </form>

            <form action="/delete-game" method="POST">
                <input type="hidden" name="id_jeu" value="<?= htmlspecialchars($game['id']) ?>">
                <button type="submit">Supprimer le jeu de ma bibliothèque</button>
            </form>
        </section>

        <aside>
            <img src="<?= htmlspecialchars($game['image_url']) ?>" alt="<?= htmlspecialchars($game['nom_jeu']) ?>" width="300">
        </aside>
    </main>
</body>

    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>