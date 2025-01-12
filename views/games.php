<div class="container">
    <h1>Ajouter un jeu à sa bibliothèque</h1>
    <!-- Formulaire de recherche -->
    <form action="/games" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Rechercher un jeu" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">RECHERCHER</button>
    </form>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
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
                        <form action="/games" method="POST">
                            <input type="hidden" name="id_jeu" value="<?= $game['Id_jeu'] ?>">
                            <?php if ($game['Possede']): ?>
                                <button type="button" class="add-button disabled" disabled>Déjà possédé</button>
                            <?php else: ?>
                                <button type="submit" name="add_to_library" class="add-button">AJOUTER À LA BIBLIOTHÈQUE</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>