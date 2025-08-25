<?php

namespace Services;

use Models\User;

class UserService {
    public function __construct(private User $model) {}

    public function getAllUsers($name): array {
        return $this->model->getAll($name);
    }

    public function getUserById(int $id): array|false {
        return $this->model->getUserById($id);
    }

    public function createUser(array $data): array {
        return $this->model->create($data);
    }

    public function updateUser(array $data): array {
        return $this->model->update($data);
    }

    public function deleteUser(array $data): array {
        return $this->model->delete($data);
    }
}