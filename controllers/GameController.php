<?php
class GameController {

    public function index() {
        require_once __DIR__ . '/../views/games.php';
    }

    public function showGame() {
        require_once __DIR__ . '/../views/game.php';
    }

    public function showAddGameForm() {
        require_once __DIR__ . '/../views/add_game.php';
    }

    public function deleteGame() {
        try {
            $db = new PDO('mysql:host=localhost;dbname=collection_jeux;charset=utf8', 'root', ''); // Modifier user/password selon ta configuration

            $id = $_GET['id'];

            $stmt = $db->prepare("DELETE FROM Jeu WHERE Id_jeu = :id");
            $stmt->execute(['id' => $id]);

            header("Location: games.php");
            exit;

        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function addGame() {
        try {
            $db = new PDO('mysql:host=localhost;dbname=collection_jeux;charset=utf8', 'root', ''); // Modifier user/password selon ta configuration

            // Récupération des champs du formulaire
            $nom = htmlspecialchars($_POST['nom']);
            $editeur = htmlspecialchars($_POST['editeur'] ?? '');
            $sortie = $_POST['sortie'] ?? null;
            $description = htmlspecialchars($_POST['description'] ?? '');
            $url_jeu = htmlspecialchars($_POST['cover'] ?? ''); // Assure-toi que le champ est 'cover' dans ton formulaire
            $url_site = htmlspecialchars($_POST['site'] ?? '');
            $plateformes = $_POST['platforms'] ?? [];

            // Détermine si le jeu est multiplateforme
            $id_multiplateforme = count($plateformes) > 1 ? 1 : 0;

            // Insertion du jeu dans la table Jeu
            $stmt = $db->prepare("
                INSERT INTO Jeu (Nom_jeu, Editeur_jeu, Date_sortie_jeu, Desc_jeu, id_multiplateforme, Url_jeu, Url_site) 
                VALUES (:nom, :editeur, :sortie, :description, :id_multiplateforme, :url_jeu, :url_site)
            ");
            $stmt->execute([
                'nom' => $nom,
                'editeur' => $editeur,
                'sortie' => $sortie,
                'description' => $description,
                'id_multiplateforme' => $id_multiplateforme,
                'url_jeu' => $url_jeu,
                'url_site' => $url_site
            ]);

            // Récupération de l'ID du jeu inséré
            $id_jeu = $db->lastInsertId();

            // Association des plateformes avec le jeu dans la table Jeu_Plateforme
            foreach ($plateformes as $plateforme) {
                // Récupérer l'ID de la plateforme
                $stmt = $db->prepare("SELECT Id_plateforme FROM Plateforme WHERE Nom_plateforme = :plateforme");
                $stmt->execute(['plateforme' => $plateforme]);
                $result = $stmt->fetch();

                if ($result) {
                    $id_plateforme = $result['Id_plateforme'];

                    // Insérer dans Jeu_Plateforme
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

            // Redirection après ajout
            header("Location: add_game.php?success=1");
            exit;

        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function modifyGame($id_jeu) {
        try {
            $db = new PDO('mysql:host=localhost;dbname=collection_jeux;charset=utf8', 'root', ''); // Modifier user/password selon ta configuration
    
            // Récupération des champs du formulaire
            $nom = htmlspecialchars($_POST['nom']);
            $editeur = htmlspecialchars($_POST['editeur'] ?? '');
            $sortie = $_POST['sortie'] ?? null;
            $description = htmlspecialchars($_POST['description'] ?? '');
            $url_jeu = htmlspecialchars($_POST['cover'] ?? ''); // Assure-toi que le champ est 'cover' dans ton formulaire
            $url_site = htmlspecialchars($_POST['site'] ?? '');
            $plateformes = $_POST['platforms'] ?? [];
    
            // Détermine si le jeu est multiplateforme
            $id_multiplateforme = count($plateformes) > 1 ? 1 : 0;
    
            // Mise à jour des informations dans la table Jeu
            $stmt = $db->prepare("
                UPDATE Jeu 
                SET 
                    Nom_jeu = :nom, 
                    Editeur_jeu = :editeur, 
                    Date_sortie_jeu = :sortie, 
                    Desc_jeu = :description, 
                    id_multiplateforme = :id_multiplateforme, 
                    Url_jeu = :url_jeu, 
                    Url_site = :url_site
                WHERE Id_jeu = :id_jeu
            ");
            $stmt->execute([
                'nom' => $nom,
                'editeur' => $editeur,
                'sortie' => $sortie,
                'description' => $description,
                'id_multiplateforme' => $id_multiplateforme,
                'url_jeu' => $url_jeu,
                'url_site' => $url_site,
                'id_jeu' => $id_jeu
            ]);
    
            // Suppression des associations existantes dans la table Jeu_Plateforme
            $stmt = $db->prepare("DELETE FROM Jeu_Plateforme WHERE Id_jeu = :id_jeu");
            $stmt->execute(['id_jeu' => $id_jeu]);
    
            // Association des nouvelles plateformes avec le jeu dans la table Jeu_Plateforme
            foreach ($plateformes as $plateforme) {
                // Récupérer l'ID de la plateforme
                $stmt = $db->prepare("SELECT Id_plateforme FROM Plateforme WHERE Nom_plateforme = :plateforme");
                $stmt->execute(['plateforme' => $plateforme]);
                $result = $stmt->fetch();
    
                if ($result) {
                    $id_plateforme = $result['Id_plateforme'];
    
                    // Insérer dans Jeu_Plateforme
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
    
            // Redirection après modification
            header("Location: modify_game.php?id=$id_jeu&success=1");
            exit;
    
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
    
}
