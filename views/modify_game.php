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
    <link rel="stylesheet" href="/views/style/add_game.css">
</head>
<body>
    <div class="container">
    <?php var_dump($game); ?>
        <?php if ($game): ?>
        
            <h1>Modifier le jeu : <?= htmlspecialchars($game['Nom_jeu']) ?></h1>
            <p><?= htmlspecialchars($game['Desc_jeu'] ?? 'Pas de description disponible') ?></p>
            <p>Temps passé : <?= htmlspecialchars($game['Temps_jeu']) ?> h</p>

            <form action="/modify_game" method="POST">
                <input type="hidden" name="id_jeu" value="<?= htmlspecialchars($game['Id_jeu']) ?>">

                <label for="nom">Nom du jeu</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($game['Nom_jeu']) ?>" required>

                <label for="editeur">Éditeur du jeu</label>
                <input type="text" id="editeur" name="editeur" value="<?= htmlspecialchars($game['Editeur_jeu']) ?>">

                <label for="sortie">Sortie du jeu</label>
                <input type="date" id="sortie" name="sortie" value="<?= htmlspecialchars($game['Date_sortie_jeu']) ?>">

                <!-- Groupement des plateformes -->
                <label>Plateformes</label>
                <div class="platform-group">
                    <div>
                        <input type="checkbox" id="playstation" name="platforms[]" value="Playstation" <?= in_array('Playstation', $game['Plateformes'] ?? []) ? 'checked' : '' ?>>
                        <label for="playstation">Playstation</label>
                    </div>
                    <div>
                        <input type="checkbox" id="xbox" name="platforms[]" value="Xbox" <?= in_array('Xbox', $game['Plateformes'] ?? []) ? 'checked' : '' ?>>
                        <label for="xbox">Xbox</label>
                    </div>
                    <div>
                        <input type="checkbox" id="nintendo" name="platforms[]" value="Nintendo" <?= in_array('Nintendo', $game['Plateformes'] ?? []) ? 'checked' : '' ?>>
                        <label for="nintendo">Nintendo</label>
                    </div>
                    <div>
                        <input type="checkbox" id="pc" name="platforms[]" value="PC" <?= in_array('PC', $game['Plateformes'] ?? []) ? 'checked' : '' ?>>
                        <label for="pc">PC</label>
                    </div>
                </div>

                <label for="description">Description du jeu</label>
                <textarea id="description" name="description" placeholder="Description du jeu"><?= htmlspecialchars($game['Desc_jeu']) ?></textarea>

                <label for="cover">URL de la cover</label>
                <input type="url" id="cover" name="cover" value="<?= htmlspecialchars($game['Url_jeu']) ?>">

                <label for="site">URL du site</label>
                <input type="url" id="site" name="site" value="<?= htmlspecialchars($game['Url_site']) ?>">

                <button type="submit">Modifier le jeu</button>
            </form>

            <form action="/delete-game" method="POST">
                <input type="hidden" name="id_jeu" value="<?= htmlspecialchars($game['Id_jeu']) ?>">
                <button type="submit" class="delete-button">Supprimer le jeu de ma bibliothèque</button>
            </form>
        <?php else: ?>
            <p>Jeu non trouvé.</p>
        <?php endif; ?>
    </div>

    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>