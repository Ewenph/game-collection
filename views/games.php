<?php
require_once __DIR__ . '/../controllers/GameController.php';

$controller = new GameController();
$controller->index();

require_once __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Jeux</title>
    <link rel="stylesheet" href="/views/style/games.css">
</head>
<body>
<div class="container">
    <h1>Ajouter un jeu à sa bibliothèque</h1>
    <!-- Formulaire de recherche -->
    <form action="/games" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Rechercher un jeu" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">RECHERCHER</button>
    </form>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="message success">Le jeu a été ajouté à votre bibliothèque avec succès !</div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 'already_owned'): ?>
        <div class="message error">Ce jeu est déjà dans votre bibliothèque.</div>
    <?php endif; ?>

    <?php if (!empty($games)): ?>
        <h2>Résultats de la recherche</h2>
        <div class="games-grid">
            <?php foreach ($games as $game): ?>
                <div class="game-card">
                    <img src="<?= htmlspecialchars($game['Url_jeu']) ?>" alt="<?= htmlspecialchars($game['Nom_jeu']) ?>" class="game-image">
                    <div class="game-info">
                        <h3><?= htmlspecialchars($game['Nom_jeu']) ?></h3>
                        <p><?= htmlspecialchars($game['Plateformes'] ?: 'Aucune plateforme') ?></p>
                        <p class="description"><?= htmlspecialchars($game['Desc_jeu'] ?: 'Pas de description') ?></p>
                               <form action="/add_to_library" method="POST">
                                    <input type="hidden" name="game_id" value="<?= $game['Id_jeu'] ?>">
                                    <?php if ($game['Possede']): ?>
                                        <button type="button" class="add-button disabled" disabled>Déjà possédé</button>
                                    <?php else: ?>
                                        <button type="submit" class="add-button">AJOUTER À LA BIBLIOTHÈQUE</button>
                                    <?php endif; ?>
                                </form>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>