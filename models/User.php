<?php
class User {
    private $db;
    private $dbname = 'td21-1';
    private $username = 'td21-1';
    private $password = 'BJCkZcFAIUeJqL4E';

    public function __construct() {
        $this->connect_to_database();
    }

    private function connect_to_database() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname={$this->dbname};charset=utf8", $this->username, $this->password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
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
}