<?php
require_once __DIR__ . '/../models/User.php';

class LeaderboardController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Affiche le classement des utilisateurs
    public function index() {
        $users = $this->userModel->getLeaderboard();
        require_once __DIR__ . '/../views/leaderboard.php';
    }
}