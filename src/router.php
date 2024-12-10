<?php
function routeRequest() {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    switch ($uri) {
        case '/':
            require_once __DIR__ . '/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            break;
        case '/login':
            require_once __DIR__ . '/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->showLoginForm();
            break;
        case '/login/submit':
            require_once __DIR__ . '/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->login();
            break;
        case '/register':
            require_once __DIR__ . '/controllers/RegisterController.php';
            $controller = new RegisterController();
            $controller->showRegisterForm();
            break;
        case '/register/submit':
            require_once __DIR__ . '/controllers/RegisterController.php';
            $controller = new RegisterController();
            $controller->register();
            break;
        case '/logout':
            require_once __DIR__ . '/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->logout();
            break;
        case '/games':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->index();
            break;
        case '/games/add':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->showAddGameForm();
            break;
        case '/games/add/submit':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->addGame();
            break;
        case '/games/delete':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->deleteGame();
            break;
        case '/leaderboard':
            require_once __DIR__ . '/controllers/LeaderboardController.php';
            $controller = new LeaderboardController();
            $controller->index();
            break;
        default:
            http_response_code(404);
            echo "Page not found";
            break;
    }
}