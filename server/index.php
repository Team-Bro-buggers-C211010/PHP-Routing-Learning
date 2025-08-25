<?php

ob_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/loadenv.php';

// Auto load the file when using namespace and call the file
spl_autoload_register(function ($class): void {
    $baseDir = __DIR__ . '/src/';
    $path = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($path)) {
        require_once $path;
    }
});

use Router\Router;
use Middleware\Cors;

Cors::handle(); // :: is used to access constant or constant value or functions. Not need to create an instance.

$router = new Router();

require_once __DIR__ . '/routes/web.php';

$router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

ob_end_flush();
