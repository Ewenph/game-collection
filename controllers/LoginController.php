<?php

class LoginController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // public function showLoginForm() {
    //     require_once __DIR__ . '/views/login.php';
    // }

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['Mdp_uti'])) {
            session_start();
            $_SESSION['user_id'] = $user['Id_uti'];
            header('Location: /home');
        } else {
            echo 'Email ou mot de passe incorrect';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}