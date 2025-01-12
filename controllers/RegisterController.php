<?php

class RegisterController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Affiche le formulaire d'inscription
    public function showRegisterForm() {
        require_once __DIR__ . '/../views/register.php';
    }

    // Gère l'inscription de l'utilisateur
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['lastname'];
            $prenom = $_POST['firstname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirmation = $_POST['confirm_password'];

            if ($password !== $passwordConfirmation) {
                $error = 'Les mots de passe ne correspondent pas';
            } else {
                if ($this->userModel->findByEmail($email)) {
                    $error = 'Un utilisateur avec cet email existe déjà';
                } else {
                    $this->userModel->create($nom, $prenom, $email, $password);
                    header('Location: /login');
                    exit;
                }
            }
        }

        require_once __DIR__ . '/../views/register.php';
    }
}