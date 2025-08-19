<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/Router/Router.php';
require_once __DIR__ . '/src/Middleware/Cors.php';

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/src/';
    $path = $baseDir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($path)) {
        require_once $path;
    }
});

use Router\Router;
use Middleware\Cors;

Cors::handle();

$router = new Router();

require_once __DIR__ . '/routes/users.php';

$router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
