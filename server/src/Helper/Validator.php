<?php

namespace Helper;

class Validator {
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
}