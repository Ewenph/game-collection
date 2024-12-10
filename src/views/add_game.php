<?php
require_once __DIR__ . '/../controllers/GameController.php';

$controller = new GameController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->addGame();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un jeu</title>
</head>
<body>
    <h2>Ajouter un jeu à sa bibliothèque</h2>
    <form action="add_game.php" method="POST">
        <label for="nom">Nom du jeu</label>
        <input type="text" id="nom" name="nom" required>

        <label for="editeur">Éditeur du jeu</label>
        <input type="text" id="editeur" name="editeur">

        <label for="sortie">Date de sortie</label>
        <input type="date" id="sortie" name="sortie">

        <label for="description">Description</label>
        <textarea id="description" name="description"></textarea>

        <label for="url_jeu">URL de la cover</label>
        <input type="url" id="url_jeu" name="url_jeu">

        <label for="url_site">URL du site officiel</label>
        <input type="url" id="url_site" name="url_site">

        <label>Plateformes</label>
        <div>
            <input type="checkbox" id="pc" name="plateformes[]" value="PC">
            <label for="pc">PC</label>

            <input type="checkbox" id="playstation" name="plateformes[]" value="PlayStation">
            <label for="playstation">PlayStation</label>

            <input type="checkbox" id="xbox" name="plateformes[]" value="Xbox">
            <label for="xbox">Xbox</label>

            <input type="checkbox" id="nintendo" name="plateformes[]" value="Nintendo Switch">
            <label for="nintendo">Nintendo Switch</label>

            <input type="checkbox" id="mobile" name="plateformes[]" value="Mobile">
            <label for="mobile">Mobile</label>
        </div>

        <button type="submit">Ajouter le jeu</button>
    </form>
</body>
</html>
