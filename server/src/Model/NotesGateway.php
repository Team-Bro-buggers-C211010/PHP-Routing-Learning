<?php

namespace Model;

class NotesGateway {
    public function __construct(private \PDO $conn) {}

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM notes");
        return $stmt->fetchAll();
    }
}