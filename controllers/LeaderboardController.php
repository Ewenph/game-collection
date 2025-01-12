<?php
class LeaderboardController {
    private $db;
    private $dbname = 'td21-1';
    private $username = 'td21-1';
    private $password = 'BJCkZcFAIUeJqL4E';

    public function index() {
        $users = $this->selecInfo();

        // Debug temporaire pour vérifier le contenu de $users
        if (empty($users)) {
            echo "Aucune donnée n'a été récupérée depuis la base.";
            var_dump($users);
        } else {
            echo "Données récupérées avec succès :";
            var_dump($users);
        }

        require_once __DIR__ . '/../views/leaderboard.php';
    }

    public function selecInfo() {
        try {
            // Connexion à la base de données
            $db = new PDO("mysql:host=localhost;dbname={$this->dbname};charset=utf8", $this->username, $this->password);
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
