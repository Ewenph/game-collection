<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class UserGame {
    private $db;

    public function __construct() {
        $this->connect_to_database();
    }

    // Connexion à la base de données
    private function connect_to_database() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
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

    // Ajouter un jeu à la bibliothèque de l'utilisateur
    public function addGameToUser($userId, $gameId) {
        $stmt = $this->db->prepare("INSERT INTO Bibliothèque (Id_jeu, Id_uti, Temps_jeu) VALUES (:gameId, :userId, 0)");
        $stmt->execute(['gameId' => $gameId, 'userId' => $userId]);
    }

    // Supprimer un jeu de la bibliothèque de l'utilisateur
    public function removeGameFromUser($userId, $gameId) {
        $stmt = $this->db->prepare("DELETE FROM Bibliothèque WHERE Id_jeu = :gameId AND Id_uti = :userId");
        $stmt->execute(['gameId' => $gameId, 'userId' => $userId]);
    }

    // Mettre à jour le temps de jeu pour un utilisateur et un jeu
    public function updateGameTime($userId, $gameId, $time) {
        $stmt = $this->db->prepare("UPDATE Bibliothèque SET Temps_jeu = :time WHERE Id_jeu = :gameId AND Id_uti = :userId");
        $stmt->execute(['time' => $time, 'gameId' => $gameId, 'userId' => $userId]);
    }

    // Récupérer les jeux de l'utilisateur
    public function getUserGames($userId) {
        $stmt = $this->db->prepare("
            SELECT j.*, b.Temps_jeu, GROUP_CONCAT(p.Nom_plateforme SEPARATOR ', ') AS Plateformes
            FROM Jeu j
            JOIN Bibliothèque b ON j.Id_jeu = b.Id_jeu
            LEFT JOIN Jeu_Plateforme jp ON j.Id_jeu = jp.Id_jeu
            LEFT JOIN Plateforme p ON jp.Id_plateforme = p.Id_plateforme
            WHERE b.Id_uti = :userId
            GROUP BY j.Id_jeu
        ");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}