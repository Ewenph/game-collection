<?php
class GameController {
    public function addGame() {
        try {
            // Connexion à la base de données
            $db = new PDO('mysql:host=localhost;dbname=collection_jeux;charset=utf8', 'root', ''); // Modifier user/password selon ta configuration

            // Récupération des données du formulaire
            $nom = htmlspecialchars($_POST['nom']);
            $editeur = htmlspecialchars($_POST['editeur'] ?? '');
            $sortie = $_POST['sortie'] ?? null;
            $description = htmlspecialchars($_POST['description'] ?? '');
            $url_jeu = htmlspecialchars($_POST['url_jeu'] ?? '');
            $url_site = htmlspecialchars($_POST['url_site'] ?? '');
            $plateformes = $_POST['plateformes'] ?? [];

            // Insertion du jeu dans la table `Jeu`
            $stmt = $db->prepare("
                INSERT INTO Jeu (Nom_jeu, Editeur_jeu, Date_sortie_jeu, Desc_jeu, Url_jeu, Url_site) 
                VALUES (:nom, :editeur, :sortie, :description, :url_jeu, :url_site)
            ");
            $stmt->execute([
                'nom' => $nom,
                'editeur' => $editeur,
                'sortie' => $sortie,
                'description' => $description,
                'url_jeu' => $url_jeu,
                'url_site' => $url_site
            ]);

            // Récupération de l'ID du jeu inséré
            $id_jeu = $db->lastInsertId();

            // Association des plateformes avec le jeu
            foreach ($plateformes as $plateforme) {
                // Récupérer l'ID de la plateforme
                $stmt = $db->prepare("SELECT Id_plateforme FROM Plateforme WHERE Nom_plateforme = :plateforme");
                $stmt->execute(['plateforme' => $plateforme]);
                $result = $stmt->fetch();

                if ($result) {
                    $id_plateforme = $result['Id_plateforme'];

                    // Insérer dans `Jeu_Plateforme`
                    $stmt = $db->prepare("
                        INSERT INTO Jeu_Plateforme (Id_jeu, Id_plateforme) 
                        VALUES (:id_jeu, :id_plateforme)
                    ");
                    $stmt->execute([
                        'id_jeu' => $id_jeu,
                        'id_plateforme' => $id_plateforme
                    ]);
                }
            }

            // Redirection après succès
            header("Location: add_game.php?success=1");
            exit;

        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
