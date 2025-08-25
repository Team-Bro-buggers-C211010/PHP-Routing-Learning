<?php

namespace Validators;

class UserValidator {
    public static function validateUserData(array $data): array {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = "Name is required";
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required";
        }

        return $errors;
    }

    public static function validateUserId(array $data): array {
        $errors = [];

        if (empty($data['id']) || !is_numeric($data['id'])) {
            $errors[] = "Valid ID is required";
        }

        return $errors;
    }
}