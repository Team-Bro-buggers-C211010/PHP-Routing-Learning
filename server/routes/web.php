<?php

use Controllers\UserController;
use Controllers\NotesController;
use Services\UserService;
use Services\NotesService;
use Models\User;
use Models\Note;

// Initialize database connection
$db = new Database();

// Instantiate models
$userModel = new User($db->getConnection());
$noteModel = new Note($db->getConnection());

// Instantiate services
$userService = new UserService($userModel);
$notesService = new NotesService($noteModel);

// Instantiate controllers
$userController = new UserController($userService);
$notesController = new NotesController($notesService);


// User routes Here.......

// here "use ($userController)" is set the $userController in the closure outer scope to use it in inner function.
$router->get('/users', function ($query) use ($userController): void {
    if (isset($query['id'])) {
        $userController->getUser($query);
    } else {
        $userController->getAllUsers($query);
    }
});

$router->post('/users', function () use ($userController): void {
    $userController->createUser();
});

$router->put('/users', function () use ($userController): void {
    $userController->updateUser();
});

$router->delete('/users', function () use ($userController): void {
    $userController->deleteUser();
});

// Notes routes
$router->get('/notes', function () use ($notesController): void {
    $notesController->getAllNotes();
});