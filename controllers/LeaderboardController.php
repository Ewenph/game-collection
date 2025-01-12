<?php

class LeaderboardController {
    private $db;

    public function __construct() {
        $this->connect_to_database();
    }

    private function connect_to_database() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $dbname = $_ENV["DB_NAME"];
        $username = $_ENV["DB_USER"];
        $password = $_ENV["DB_PASSWORD"];
        try {
            $this->db = new PDO("mysql:host=localhost;dbname={$dbname};charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    }

    public function index() {
        $users = $this->selecInfo();
        require_once __DIR__ . '/../views/leaderboard.php';
    }

    public function selecInfo() {
        try {
            $stmt = $this->db->prepare("
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
                LIMIT 20;
            ");

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
}