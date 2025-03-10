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

$request = strtok($_SERVER['REQUEST_URI'], '?'); // Chemin avant le '?'
$queryParams = $_GET; // Récupère les paramètres de requête comme `?search=minecraft`

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
        $controller = new HomeController();
        $controller->index();
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
        $controller = new GameController();
        $controller->index();
        break;
    case '/add_game':
        $controller = new GameController();
        $controller->addGame();
        break;
    case '/leaderboard':
        $controller = new LeaderboardController();
        $controller->index();
        break;
    case '/modify_game':
        $controller = new GameController();
        $controller->modifyGame();
        break;
    case '/add_to_library':
            $controller = new GameController();
            $controller->addToLibrary();
            break;
    default:
        http_response_code(404);
        include 'views/404.php';
        break;
}
?>