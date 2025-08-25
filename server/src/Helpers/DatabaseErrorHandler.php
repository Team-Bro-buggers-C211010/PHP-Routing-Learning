<?php

namespace Helpers;

use PDOException;

class DatabaseErrorHandler
{
    public static function handle(PDOException $e): array
    {
        // Duplicate entry (e.g. unique constraint violation)
        if ($e->getCode() === '23000') {
            http_response_code(409); // Conflict
            return ["error" => "Email already exists"];
        }

        // Other database errors
        http_response_code(500);
        return ["error" => "Internal server error"];
    }
}
