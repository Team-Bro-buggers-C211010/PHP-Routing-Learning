<?php

namespace Controller;

use Helper\Validator;

class UserController {
    public function __construct(private $gateway) {}

    public function getAllUsers(): void {
        echo json_encode($this->gateway->getAll());
    }

    public function getUser(array $query): void {
        $id = $query['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid or missing user ID"]);
            return;
        }

        $user = $this->gateway->getUserById((int)$id);

        if (!$user) {
            http_response_code(404);
            echo json_encode(["error" => "User not found"]);
            return;
        }

        echo json_encode($user);
    }

    public function createUser(): void {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON"]);
            return;
        }

        $errors = Validator::validateUserData($data);

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            return;
        }

        $result = $this->gateway->create($data);
        echo json_encode($result);
    }

    public function updateUser(): void {
        $updatedData = json_decode(file_get_contents("php://input"), true);
        $errors = Validator::validateUserData($updatedData);

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            return;
        }

        $result = $this->gateway->update($updatedData);
        echo json_encode($result);
    }

    public function deleteUser(): void {
        $updatedData = json_decode(file_get_contents("php://input"), true);
        $errors = Validator::validateUserId($updatedData);

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            return;
        }

        $result = $this->gateway->delete($updatedData);
        echo json_encode($result);
    }
}