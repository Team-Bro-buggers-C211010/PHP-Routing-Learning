<?php

class Database
{
    public function __construct(
        private string $host = "localhost",
        private string $dbName = "php-db",
        private string $username = "root",
        private string $password = ""
    ) {}

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";

        try {
            return new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Database connection failed", "details" => $e->getMessage()]);
            exit;
        }
    }
}
