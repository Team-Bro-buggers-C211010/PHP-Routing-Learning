<?php


// User routes here...

use Controller\NotesController;
use Controller\UserController;
use Model\NotesGateway;
use Model\UserGateway;


$router->get('/users', function ($query): void {
    $db = new Database();
    $gateway = new UserGateway($db->getConnection());
    $controller = new UserController($gateway);

    if (isset($query['id'])) {
        $controller->getUser($query);
    } else {
        $controller->getAllUsers();
    }
});

$router->post('/users', function (): void {
    $db = new Database();
    $gateway = new UserGateway($db->getConnection());
    $controller = new UserController($gateway);
    $controller->createUser();
});

$router->put('/users', function (): void {
    $db = new Database();
    $gateway = new UserGateway($db->getConnection());
    $controller = new UserController($gateway);
    $controller->updateUser();
});

$router->delete('/users', function (): void {
    $db = new Database();
    $gateway = new UserGateway($db->getConnection());
    $controller = new UserController($gateway);
    $controller->deleteUser();
});


// notes routes here ....

$router->get('/notes', function (): void {
    $db = new Database();
    $gateway = new NotesGateway($db->getConnection());
    $controller = new NotesController($gateway);
    $controller->getAllNotes();
});