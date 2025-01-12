<?php

class ProfileController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showProfile() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarre la session si elle n'est pas déjà démarrée
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $user = $this->userModel->findById($_SESSION['user_id']);
        if (!$user) {
            header('Location: /login');
            exit;
        }

        require_once __DIR__ . '/../views/profile.php';
    }

    public function updateProfile() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Démarre la session si elle n'est pas déjà démarrée
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    
        $user = $this->userModel->findById($_SESSION['user_id']);
        if (!$user) {
            header('Location: /login');
            exit;
        }

        $errors = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_account'])) {
                $this->userModel->delete($_SESSION['user_id']);
                session_destroy();
                header('Location: /register');
                exit;
            } else {
                $lastname = trim($_POST['lastname']);
                $firstname = trim($_POST['firstname']);
                $email = trim($_POST['email']);
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
    
                // Vérification des longueurs
                $errors = [];
                if (strlen($lastname) > 32) {
                    $errors[] = 'Le nom ne peut pas dépasser 100 caractères.';
                }
                if (strlen($firstname) > 32) {
                    $errors[] = 'Le prénom ne peut pas dépasser 100 caractères.';
                }
                if (strlen($email) > 32) {
                    $errors[] = 'L\'adresse email ne peut pas dépasser 100 caractères.';
                }
    
                // Vérification des mots de passe
                if ($password !== $confirm_password) {
                    $errors[] = 'Les mots de passe ne correspondent pas.';
                }
    
                if (empty($errors)) {
                    // Mettre à jour l'utilisateur si tout est valide
                    $this->userModel->update($_SESSION['user_id'], $lastname, $firstname, $email, $password);
                    header('Location: /profile');
                    exit;
                }
            }
        }
    
        require_once __DIR__ . '/../views/profile.php';
    }
}    