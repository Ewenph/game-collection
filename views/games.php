<?php
require_once __DIR__ . '/header.php';

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=localhost;dbname=collection_jeux;charset=utf8', 'root', ''); // Modifier les identifiants si nécessaire
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Requête pour récupérer les jeux avec leurs plateformes et images
$query = "
    SELECT j.Id_jeu, j.Nom_jeu, j.Desc_jeu, j.Url_jeu, GROUP_CONCAT(p.Nom_plateforme SEPARATOR ', ') AS Plateformes
    FROM Jeu j
    LEFT JOIN Jeu_Plateforme jp ON j.Id_jeu = jp.Id_jeu
    LEFT JOIN Plateforme p ON jp.Id_plateforme = p.Id_plateforme
    GROUP BY j.Id_jeu
";
$stmt = $db->query($query);
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Jeux</title>
    <link rel="stylesheet" href="style/games.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un jeu à sa bibliothèque</h1>
        <form action="games.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Rechercher un jeu">
            <button type="submit">RECHERCHER</button>
        </form>

        <h2>Mes jeux</h2>
        <div class="games-grid">
            <?php foreach ($games as $game): ?>
                <div class="game-card">
                    <!-- Image du jeu -->
                    <img src="<?= htmlspecialchars($game['Url_jeu']) ?>" alt="<?= htmlspecialchars($game['Nom_jeu']) ?>" class="game-image">
                    
                    <!-- Informations du jeu -->
                    <div class="game-info">
                        <h3><?= htmlspecialchars($game['Nom_jeu']) ?></h3>
                        <p><?= htmlspecialchars($game['Plateformes'] ?: 'Aucune plateforme') ?></p>
                        <p class="description"><?= htmlspecialchars($game['Desc_jeu'] ?: 'Pas de description') ?></p>
                        <button class="add-button">AJOUTER À LA BIBLIOTHÈQUE</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        Game Collection - 2023 - Tous droits réservés
    </footer>
</body>
</html>
