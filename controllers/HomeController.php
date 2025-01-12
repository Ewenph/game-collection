<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/UserGame.php';

class HomeController {
    private $userModel;
    private $userGameModel;

    public function __construct() {
        $this->userModel = new User();
        $this->userGameModel = new UserGame();
    }

    // Affiche la page d'accueil avec les jeux de l'utilisateur
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->findById($user_id);
        $games = $this->userGameModel->getUserGames($user_id);

        require_once __DIR__ . '/../views/home.php';
    }
}