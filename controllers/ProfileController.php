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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_account'])) {
                $this->userModel->delete($_SESSION['user_id']);
                session_destroy();
                header('Location: /register');
                exit;
            } else {
                $lastname = $_POST['lastname'];
                $firstname = $_POST['firstname'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                if ($password === $confirm_password) {
                    $this->userModel->update($_SESSION['user_id'], $lastname, $firstname, $email, $password);
                    header('Location: /profile');
                    exit;
                } else {
                    $error = 'Les mots de passe ne correspondent pas';
                }
            }
        }

        require_once __DIR__ . '/../views/profile.php';
    }
}