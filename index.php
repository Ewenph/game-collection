<?php
session_start();

// Models
include 'models/Game.php';
include 'models/User.php';
include 'models/UserGame.php';

// Controllers
include 'controllers/GameController.php';
include 'controllers/HomeController.php';
include 'controllers/LeaderboardController.php';
include 'controllers/LoginController.php';
include 'controllers/RegisterController.php';
include 'controllers/ProfileController.php';

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        $controller = new LoginController();
        $controller->showLoginForm();
        break;
    case '/login':
        $controller = new LoginController();
        $controller->login();
        break;
    case '/home':
        include 'views/home.php';
        break;
    case '/register':
        $controller = new RegisterController();
        $controller->register();
        break;
    case '/profile':
        $controller = new ProfileController();
        $controller->updateProfile();
        break;
    case '/games':
        include 'views/games.php';
        break;
    case '/add_game':
        include 'views/add_game.php';
        break;
    case '/leaderboard':
        $controller = new LeaderboardController();
        $controller->index();
        break;
    case '/modify_game':
        include 'views/modify_game.php';
        break;
    default:
        http_response_code(404);
        include 'views/404.php';
        break;
}
?>