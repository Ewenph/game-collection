<?php
function routeRequest() {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    switch ($uri) {
        case '/game-collection/home':
            require_once __DIR__ . '/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            break;
        case '/game-collection/login':
            require_once __DIR__ . '/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->showLoginForm();
            break;
        case '/game-collection/login/submit':
            require_once __DIR__ . '/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->login();
            break;
        case '/game-collection/register':
            require_once __DIR__ . '/controllers/RegisterController.php';
            $controller = new RegisterController();
            $controller->showRegisterForm();
            break;
        case '/game-collection/register/submit':
            require_once __DIR__ . '/controllers/RegisterController.php';
            $controller = new RegisterController();
            $controller->register();
            break;
        case '/game-collection/logout':
            require_once __DIR__ . '/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->logout();
            break;
        case '/game-collection/games':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->index();
            break;
        case '/game-collection/games/add':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->showAddGameForm();
            break;
        case '/game-collection/games/add/submit':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->addGame();
            break;
        case '/game-collection/games/delete':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->deleteGame();
            break;
        case '/game-collection/leaderboard':
            require_once __DIR__ . '/controllers/LeaderboardController.php';
            $controller = new LeaderboardController();
            $controller->index();
            break;
        default:
            require_once __DIR__ . '/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            break;
    }
}