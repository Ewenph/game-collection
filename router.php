<?php
function routeRequest() {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Redirection vers la page d'accueil pour l'URL spécifique
    if ($uri === '/game-collection/public/' || $uri === '/game-collection/public') {
        header('Location: /game-collection/src/home');
        exit();
    }

    switch ($uri) {
        case '/game-collection/src/':
            require_once __DIR__ . '/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            break;
        case '/game-collection/src/login':
            require_once __DIR__ . '/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->showLoginForm();
            break;
        case '/game-collection/src/login/submit':
            require_once __DIR__ . '/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->login();
            break;
        case '/game-collection/src/register':
            require_once __DIR__ . '/controllers/RegisterController.php';
            $controller = new RegisterController();
            $controller->showRegisterForm();
            break;
        case '/game-collection/src/register/submit':
            require_once __DIR__ . '/controllers/RegisterController.php';
            $controller = new RegisterController();
            $controller->register();
            break;
        case '/game-collection/src/logout':
            require_once __DIR__ . '/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->logout();
            break;
        case '/game-collection/src/games':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->index();
            break;
        case '/game-collection/src/games/add':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->showAddGameForm();
            break;
        case '/game-collection/src/games/add/submit':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->addGame();
            break;
        case '/game-collection/src/games/delete':
            require_once __DIR__ . '/controllers/GameController.php';
            $controller = new GameController();
            $controller->deleteGame();
            break;
        case '/game-collection/src/leaderboard':
            require_once __DIR__ . '/controllers/LeaderboardController.php';
            $controller = new LeaderboardController();
            $controller->index();
            break;
        default:
            http_response_code(404);
            echo "Page non trouvée";
            break;
    }
}