<?php
session_start();
$_SESSION['user_id'] = 3; // Simule un utilisateur connecté pour le test
require_once __DIR__ . '/header.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die('Vous devez être connecté pour voir vos jeux.');
}

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=localhost;dbname=collection_jeux;charset=utf8', 'root', ''); // Modifier user/password
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Récupération des jeux de l'utilisateur
$user_id = $_SESSION['user_id'];
$query = "
    SELECT 
        j.Id_jeu,
        j.Nom_jeu, 
        j.Url_jeu, 
        GROUP_CONCAT(p.Nom_plateforme SEPARATOR ', ') AS Plateformes, 
        b.Temps_jeu 
    FROM Bibliothèque b
    JOIN Jeu j ON b.Id_jeu = j.Id_jeu
    LEFT JOIN Jeu_Plateforme jp ON j.Id_jeu = jp.Id_jeu
    LEFT JOIN Plateforme p ON jp.Id_plateforme = p.Id_plateforme
    WHERE b.Id_uti = :user_id
    GROUP BY j.Id_jeu
";
$stmt = $db->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes jeux</title>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/game-collection/views/style/common.css">
</head>
<body>
    <main class="home-page">
        <section class="hero">
            <div class="hero-content">
                <h1>SALUT {PRENOM} !</h1>
                <p>PRÊT À AJOUTER DES JEUX À TA COLLECTION ?</p>
            </div>
        </section>
        <section class="games-section">
            <h2>Mes jeux</h2>
            <div class="games-grid">
                <?php if (count($games) > 0): ?>
                    <?php foreach ($games as $game): ?>
                        <a href="/game-collection/views/modify_game.php?id_jeu=<?= htmlspecialchars($game['Id_jeu']) ?>" class="game-link">
                            <div class="game-card">
                                <div class="game-image-container">
                                    <img src="<?= htmlspecialchars($game['Url_jeu']) ?>" alt="<?= htmlspecialchars($game['Nom_jeu']) ?>" class="game-image">
                                </div>
                                <div class="game-details">
                                    <h3><?= htmlspecialchars($game['Nom_jeu']) ?></h3>
                                    <p><?= htmlspecialchars($game['Plateformes'] ?: 'Plateformes inconnues') ?></p>
                                    <p><?= htmlspecialchars($game['Temps_jeu']) ?> h</p>
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
        Game Collection - 2023 - Tous droits réservés
    </footer>
</body>
</html>
