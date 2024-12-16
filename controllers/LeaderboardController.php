<?php
class LeaderboardController {
    public function index() {
        // Récupérer les données des utilisateurs
        $users = $this->selecInfo();

        // Vérifie ce que renvoie $users
        var_dump($users); // Affiche les données récupérées

        // Passer les données à la vue
        require_once __DIR__ . '/../views/leaderboard.php';
    }

    public function selecInfo() {
        try {
            // Connexion à la base de données
            $db = new PDO('mysql:host=localhost;dbname=collection_jeux;charset=utf8', 'root', ''); // Modifier user/password selon ta configuration
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête SQL pour récupérer les informations des joueurs
            $stmt = $db->prepare("
                SELECT 
                    u.Pren_uti AS Prenom,
                    u.Nom_uti AS Nom,
                    SUM(b.Temps_jeu) AS Total_Heures,
                    j.Nom_jeu AS Jeu_Prefere
                FROM 
                    Utilisateur u
                LEFT JOIN 
                    Bibliothèque b ON u.Id_uti = b.Id_uti
                LEFT JOIN 
                    Jeu j ON b.Id_jeu = j.Id_jeu
                WHERE 
                    b.Temps_jeu = (
                        SELECT MAX(b2.Temps_jeu) 
                        FROM Bibliothèque b2 
                        WHERE b2.Id_uti = u.Id_uti
                    )
                GROUP BY 
                    u.Id_uti
                ORDER BY 
                    Total_Heures DESC
            ");

            $stmt->execute();

            // Retourner les données sous forme de tableau associatif
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
}
?>
