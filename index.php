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

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        include 'views/login.php';
        break;
    case '/login':
        include 'views/login.php';
        break;
    case '/home':
        include 'views/home.php';
        break;
    case '/register':
        include 'views/register.php';
        break;
    case '/profile':
        include 'views/profile.php';
        break;
    case '/games':
        include 'views/games.php';
        break;
    case '/add_game':
        include 'views/add_game.php';
        break;
    case '/leaderboard':
        include 'views/leaderboard.php';
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