<?php

class LoginController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLoginForm() {
        require_once __DIR__ . '/../views/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['Mdp_uti'])) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['Id_uti'];
                header('Location: /home');
                exit;
            } else {
                $error = 'Email ou mot de passe incorrect';
                require_once __DIR__ . '/../views/login.php';
            }
        } else {
            $this->showLoginForm();
        }
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /login');
    }
}