<?php

namespace Models;
use Helpers\DatabaseErrorHandler;

class Note
{
    public function __construct(private \PDO $conn) {}

    public function getAll(): array
    {
        try {
            $stmt = $this->conn->query("SELECT * FROM note");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return DatabaseErrorHandler::handle($e);
        }
    }
}
