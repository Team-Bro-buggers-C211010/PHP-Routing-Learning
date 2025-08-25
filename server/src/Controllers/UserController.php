<?php

namespace Controllers;

use Services\UserService;
use Validators\UserValidator;

class UserController {
    public function __construct(private UserService $service) {}

    public function getAllUsers(array $query): void {
        $name = $query["name"] ?? "";
        $users = $this->service->getAllUsers(htmlspecialchars($name));
        echo json_encode($users);
    }

    public function getUser(array $query): void {
        $id = $query['id'] ?? null;
        if (!$id || !is_numeric(value: $id)) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid or missing user ID"]);
            return;
        }

        $user = $this->service->getUserById((int)$id);
        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "User not found"]);
            return;
        }

        echo json_encode($user);
    }

    public function createUser(): void {
        $data = json_decode(file_get_contents("php://input"), true);
        $errors = UserValidator::validateUserData($data);
        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            return;
        }

        echo json_encode($this->service->createUser($data));
    }

    public function updateUser(): void {
        $data = json_decode(file_get_contents("php://input"), true);
        $errors = UserValidator::validateUserData($data);
        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            return;
        }

        echo json_encode($this->service->updateUser($data));
    }

    public function deleteUser(): void {
        $data = json_decode(file_get_contents("php://input"), true);
        $errors = UserValidator::validateUserId($data);
        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            return;
        }

        echo json_encode($this->service->deleteUser($data));
    }
}