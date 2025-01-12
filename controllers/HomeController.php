<?php

class HomeController {
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
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarre la session si elle n'est pas déjà démarrée
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $user = $this->getUserInfo($user_id);
        $games = $this->getUserGames($user_id);

        require_once __DIR__ . '/../views/home.php';
    }

    private function getUserInfo($user_id) {
        $query = "SELECT Pren_uti FROM Utilisateur WHERE Id_uti = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getUserGames($user_id) {
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
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}