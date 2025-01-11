<?php
require_once __DIR__ . '/../models/User.php';

class RegisterController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showRegisterForm() {
        require_once __DIR__ . '/../views/register.php';
    }

    public function register() {
        $nom = $_POST['lastname'];
        $prenom = $_POST['firstname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['confirm_password'];

        if ($password !== $passwordConfirmation) {
            echo 'Les mots de passe ne correspondent pas';
            return;
        }

        $this->userModel->create($nom, $prenom, $email, $password);
        header('Location: /game-collection/login');
    }
}