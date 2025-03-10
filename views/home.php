<?php
require_once __DIR__ . '/../controllers/HomeController.php';

// Initialisation du contrôleur et appel de la méthode d'index
$controller = new HomeController();
$controller->index();

require_once __DIR__ . '/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes jeux</title>
    <!-- Importation des styles -->
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/views/style/home.css">
</head>
<body>
    <main class="home-page">
        <section class="hero">
            <div class="hero-content">
                <!-- Message de bienvenue -->
                <h1>SALUT <?= htmlspecialchars($user['Pren_uti']) ?> !</h1>
                <p>PRÊT À AJOUTER DES JEUX À TA COLLECTION ?</p>
            </div>
        </section>
        <section class="games-section">
            <h2>Mes jeux</h2>
            <div class="games-grid">
                <?php if (count($games) > 0): ?>
                    <?php foreach ($games as $game): ?>
                        <a href="/modify_game?id=<?= htmlspecialchars($game['Id_jeu']) ?>" class="game-link">
                            <div class="game-card">
                                <div class="game-image-container">
                                    <img src="<?= htmlspecialchars($game['Url_jeu']) ?>" alt="<?= htmlspecialchars($game['Nom_jeu']) ?>" class="game-image">
                                </div>
                                <div class="game-details">
                                    <!-- Affichage des détails du jeu -->
                                    <h3><?= htmlspecialchars($game['Nom_jeu']) ?></h3>
                                    <p>Plateforme : <?= isset($game['Plateformes']) ? htmlspecialchars($game['Plateformes']) : 'Plateformes inconnues' ?></p>
                                    <p>Éditeur : <?= htmlspecialchars($game['Editeur_jeu']) ?></p>
                                    <p>Date de sortie : <?= htmlspecialchars($game['Date_sortie_jeu']) ?></p>
                                    <p>Temps passé : <?= htmlspecialchars($game['Temps_jeu']) ?> h</p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Vous n'avez pas encore ajouté de jeux à votre collection.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer>
        Game Collection - 2025 - Tous droits réservés
    </footer>
</body>
</html>