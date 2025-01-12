<?php
require_once __DIR__ . '/../controllers/GameController.php';

$controller = new GameController();
$controller->addGame();

require_once __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un jeu</title>
    <link rel="stylesheet" href="/views/style/add_game.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un jeu à sa bibliothèque</h1>
        <p>Le jeu que vous souhaitez ajouter n'existe pas ! Vous pouvez le créer, celui-ci sera automatiquement ajouté à votre bibliothèque !</p>
        
        <form action="/add_game" method="POST">
            <label for="nom">Nom du jeu</label>
            <input type="text" id="nom" name="nom" placeholder="Nom du jeu" required>

            <label for="editeur">Éditeur du jeu</label>
            <input type="text" id="editeur" name="editeur" placeholder="Éditeur du jeu">

            <label for="sortie">Sortie du jeu</label>
            <input type="date" id="sortie" name="sortie">

            <!-- Groupement des plateformes -->
            <label>Plateformes</label>
            <div class="platform-group">
                <div>
                    <input type="checkbox" id="playstation" name="platforms[]" value="Playstation">
                    <label for="playstation">Playstation</label>
                </div>
                <div>
                    <input type="checkbox" id="xbox" name="platforms[]" value="Xbox">
                    <label for="xbox">Xbox</label>
                </div>
                <div>
                    <input type="checkbox" id="nintendo" name="platforms[]" value="Nintendo">
                    <label for="nintendo">Nintendo</label>
                </div>
                <div>
                    <input type="checkbox" id="pc" name="platforms[]" value="PC">
                    <label for="pc">PC</label>
                </div>
            </div>

            <label for="description">Description du jeu</label>
            <textarea id="description" name="description" placeholder="Description du jeu"></textarea>

            <label for="cover">URL de la cover</label>
            <input type="url" id="cover" name="cover" placeholder="URL de la cover">

            <label for="site">URL du site</label>
            <input type="url" id="site" name="site" placeholder="URL du site">

            <button type="submit">Ajouter le jeu</button>
        </form>
    </div>

    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>