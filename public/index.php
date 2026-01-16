<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

// Load configuration
$config = require __DIR__ . '/../app/Config/config.php';
$routes = require __DIR__ . '/../app/Config/routes.php';

// Get the current request path
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

//var_dump($requestPath);
//var_dump($requestMethod);die();
// In public/index.php, replace the routing logic with:
$foundRoute = null;
foreach ($routes as $route) {
    if ($route['method'] === $requestMethod && $route['path'] === $requestPath) {
        $foundRoute = $route;
        break;
    }
}

if ($foundRoute) {
    $handler = $foundRoute['handler'];
    if (is_callable($handler)) {
        echo $handler();
    } elseif (is_array($handler) && count($handler) === 2) {
        $controller = new $handler[0]();
        $method = $handler[1];
        echo $controller->$method();
    }
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not Found', 'path' => $requestPath]);
}