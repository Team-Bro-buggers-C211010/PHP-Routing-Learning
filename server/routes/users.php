<?php

use Controller\UserController;
use Model\UserGateway;

$router->add('/users', 'GET', function ($query) {
    $db = new Database();
    $gateway = new UserGateway($db->getConnection());
    $controller = new UserController($gateway);

    if (isset($query['id'])) {
        $controller->getUser($query);
    } else {
        $controller->getAllUsers();
    }
});

$router->add('/users', 'POST', function () {
    $db = new Database();
    $gateway = new UserGateway($db->getConnection());
    $controller = new UserController($gateway);
    $controller->createUser();
});