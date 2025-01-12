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
        <?php if ($game): ?>
            <section>
                <h1><?= htmlspecialchars($game['Nom_jeu']) ?></h1>
                <p><?= htmlspecialchars($game['Desc_jeu'] ?? 'Pas de description disponible') ?></p>
                <p>Temps passé : <?= htmlspecialchars($game['Temps_jeu']) ?> h</p>
                
                <h2>Mettre à jour le temps passé sur le jeu</h2>
                <form action="/modify_game" method="POST">
                    <label for="temps">Temps passé sur le jeu</label>
                    <input type="number" id="temps" name="temps" value="<?= htmlspecialchars($game['Temps_jeu']) ?>" min="1" required>
                    <input type="hidden" name="id_jeu" value="<?= htmlspecialchars($game['Id_jeu']) ?>">
                    <button type="submit" name="update_time">Mettre à jour</button>
                </form>

                <form action="/modify_game" method="POST">
                    <input type="hidden" name="id_jeu" value="<?= htmlspecialchars($game['Id_jeu']) ?>">
                    <button type="submit" name="delete_game" class="delete-button">Supprimer le jeu de ma bibliothèque</button>
                </form>
            </section>

            <aside>
                <img src="<?= htmlspecialchars($game['Url_jeu']) ?>" alt="<?= htmlspecialchars($game['Nom_jeu']) ?>" width="300">
            </aside>
        <?php else: ?>
            <p>Jeu non trouvé.</p>
        <?php endif; ?>
    </main>
    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>