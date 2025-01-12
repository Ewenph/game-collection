<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class Game {
    private $db;

    public function __construct() {
        $this->connect_to_database();
    }

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

    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM Jeu");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Jeu WHERE Id_jeu = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($search, $user_id) {
        $query = "
            SELECT j.Id_jeu, j.Nom_jeu, j.Desc_jeu, j.Url_jeu, GROUP_CONCAT(p.Nom_plateforme SEPARATOR ', ') AS Plateformes,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM Bibliothèque b WHERE b.Id_jeu = j.Id_jeu AND b.Id_uti = :id_uti
                ) THEN 1
                ELSE 0
            END AS Possede
            FROM Jeu j
            LEFT JOIN Jeu_Plateforme jp ON j.Id_jeu = jp.Id_jeu
            LEFT JOIN Plateforme p ON jp.Id_plateforme = p.Id_plateforme
            WHERE j.Nom_jeu LIKE :search
            GROUP BY j.Id_jeu
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['search' => "%$search%", 'id_uti' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO Jeu (Nom_jeu, Editeur_jeu, Date_sortie_jeu, Desc_jeu, id_multiplateforme, Url_jeu, Url_site) 
            VALUES (:nom, :editeur, :sortie, :description, :id_multiplateforme, :url_jeu, :url_site)
        ");
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $data['id'] = $id;
        $stmt = $this->db->prepare("
            UPDATE Jeu 
            SET Nom_jeu = :nom, Editeur_jeu = :editeur, Date_sortie_jeu = :sortie, Desc_jeu = :description, id_multiplateforme = :id_multiplateforme, Url_jeu = :url_jeu, Url_site = :url_site
            WHERE Id_jeu = :id
        ");
        $stmt->execute($data);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM Jeu WHERE Id_jeu = :id");
        $stmt->execute(['id' => $id]);
    }

    public function addPlatforms($id_jeu, $plateformes) {
        foreach ($plateformes as $plateforme) {
            $stmt = $this->db->prepare("SELECT Id_plateforme FROM Plateforme WHERE Nom_plateforme = :plateforme");
            $stmt->execute(['plateforme' => $plateforme]);
            $result = $stmt->fetch();

            if ($result) {
                $id_plateforme = $result['Id_plateforme'];

                $stmt = $this->db->prepare("
                    INSERT INTO Jeu_Plateforme (Id_jeu, Id_plateforme) 
                    VALUES (:id_jeu, :id_plateforme)
                ");
                $stmt->execute([
                    'id_jeu' => $id_jeu,
                    'id_plateforme' => $id_plateforme
                ]);
            }
        }
    }

    public function removePlatforms($id_jeu) {
        $stmt = $this->db->prepare("DELETE FROM Jeu_Plateforme WHERE Id_jeu = :id_jeu");
        $stmt->execute(['id_jeu' => $id_jeu]);
    }
}