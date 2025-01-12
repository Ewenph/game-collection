<?php
require_once __DIR__ . '/../vendor/autoload.php';

class User {
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


    

    
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des données : " . $e->getMessage());
        }
    }
    

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Utilisateur WHERE Id_uti = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM Utilisateur WHERE Mail_uti = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nom, $prenom, $email, $password) {
        $stmt = $this->db->prepare("
            INSERT INTO Utilisateur (Nom_uti, Pren_uti, Mail_uti, Mdp_uti) 
            VALUES (:nom, :prenom, :email, :password)
        ");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM Utilisateur WHERE Id_uti = :id");
        $stmt->execute(['id' => $id]);
    }

    public function update($id, $nom, $prenom, $email, $password) {
        $stmt = $this->db->prepare("
            UPDATE Utilisateur 
            SET Nom_uti = :nom, Pren_uti = :prenom, Mail_uti = :email, Mdp_uti = :password 
            WHERE Id_uti = :id
        ");
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'id' => $id
        ]);
    }
}