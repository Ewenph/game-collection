<?php
function routeRequest() {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    switch ($uri) {
        case '/':
            require_once __DIR__ . '/controllers/HomeController.php';
            break;
        case '/login':
            require_once __DIR__ . '/controllers/LoginController.php';
            break;
        case '/register':
            require_once __DIR__ . '/controllers/RegisterController.php';
            break;
        default:
            http_response_code(404);
            echo "Page not found";
            break;
    }
}