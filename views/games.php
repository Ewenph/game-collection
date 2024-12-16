<?php
session_start();
$_SESSION['user_id'] = 1; // Simule un utilisateur connecté pour le test
require_once __DIR__ . '/header.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    die('Vous devez être connecté pour ajouter un jeu à votre bibliothèque.');
}

// Connexion à la base de données
try {
    $db = new PDO('mysql:host=localhost;dbname=collection_jeux;charset=utf8', 'root', ''); // Modifier user/password
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Gestion de l'ajout à la bibliothèque
if (isset($_POST['add_to_library'])) {
    $id_jeu = (int)$_POST['id_jeu'];
    $id_uti = (int)$_SESSION['user_id'];

    // Vérifie si le jeu est déjà dans la bibliothèque
    $check = $db->prepare("SELECT COUNT(*) FROM Bibliothèque WHERE Id_jeu = :id_jeu AND Id_uti = :id_uti");
    $check->execute(['id_jeu' => $id_jeu, 'id_uti' => $id_uti]);
    if ($check->fetchColumn() > 0) {
        $message = "Ce jeu est déjà dans votre bibliothèque.";
    } else {
        // Ajoute le jeu dans la bibliothèque
        $insert = $db->prepare("INSERT INTO Bibliothèque (Id_jeu, Id_uti, Temps_jeu) VALUES (:id_jeu, :id_uti, 0)");
        $insert->execute(['id_jeu' => $id_jeu, 'id_uti' => $id_uti]);
        $message = "Jeu ajouté à votre bibliothèque avec succès !";
    }
}

// Gestion de la recherche
$search = '';
if (isset($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
}

// Requête SQL avec recherche
$query = "
    SELECT j.Id_jeu, j.Nom_jeu, j.Desc_jeu, j.Url_jeu, GROUP_CONCAT(p.Nom_plateforme SEPARATOR ', ') AS Plateformes
    FROM Jeu j
    LEFT JOIN Jeu_Plateforme jp ON j.Id_jeu = jp.Id_jeu
    LEFT JOIN Plateforme p ON jp.Id_plateforme = p.Id_plateforme
    WHERE j.Nom_jeu LIKE :search
    GROUP BY j.Id_jeu
";
$stmt = $db->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifie les jeux déjà dans la bibliothèque pour l'utilisateur
$ownedGames = [];
$id_uti = (int)$_SESSION['user_id'];
$ownedQuery = $db->prepare("SELECT Id_jeu FROM Bibliothèque WHERE Id_uti = :id_uti");
$ownedQuery->execute(['id_uti' => $id_uti]);
while ($row = $ownedQuery->fetch(PDO::FETCH_ASSOC)) {
    $ownedGames[] = $row['Id_jeu'];
}
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
        <!-- Formulaire de recherche -->
        <form action="games.php" method="GET" class="search-form">
            <input type="text" name="search" placeholder="Rechercher un jeu" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">RECHERCHER</button>
        </form>

        <?php if (isset($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <h2>Mes jeux</h2>
        <div class="games-grid">
            <?php if (count($games) > 0): ?>
                <?php foreach ($games as $game): ?>
                    <div class="game-card">
                        <img src="<?= htmlspecialchars($game['Url_jeu']) ?>" alt="<?= htmlspecialchars($game['Nom_jeu']) ?>" class="game-image">
                        <div class="game-info">
                            <h3><?= htmlspecialchars($game['Nom_jeu']) ?></h3>
                            <p><?= htmlspecialchars($game['Plateformes'] ?: 'Aucune plateforme') ?></p>
                            <p class="description"><?= htmlspecialchars($game['Desc_jeu'] ?: 'Pas de description') ?></p>
                            <!-- Bouton grisé si le jeu est déjà dans la bibliothèque -->
                            <form action="games.php" method="POST">
                                <input type="hidden" name="id_jeu" value="<?= $game['Id_jeu'] ?>">
                                <?php if (in_array($game['Id_jeu'], $ownedGames)): ?>
                                    <button type="button" class="add-button disabled" disabled>AJOUTÉ</button>
                                <?php else: ?>
                                    <button type="submit" name="add_to_library" class="add-button">AJOUTER À LA BIBLIOTHÈQUE</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun jeu trouvé.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        Game Collection - 2023 - Tous droits réservés
    </footer>
</body>
</html>
