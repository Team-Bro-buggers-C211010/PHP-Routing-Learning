<?php

namespace Model;

class UserGateway {
    public function __construct(private \PDO $conn) {}

    public function getAll(): array {
        $stmt = $this->conn->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function getUserById(int $id): array|false {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindValue(":id", $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create(array $data): array {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->bindValue(':name', htmlspecialchars($data['name']));
        $stmt->bindValue(':email', $data['email']);
        $stmt->execute();

        return ["message" => "User created", "id" => $this->conn->lastInsertId()];
    }

    public function update(array $updatedData): array {
        $stmt = $this->conn->prepare("UPDATE users
                                     SET name=:name, email=:email
                                     WHERE id=:id");
        $stmt->bindValue(':name', $updatedData['name']);
        $stmt->bindValue(':email', $updatedData['email']);
        $stmt->bindValue(':id', $updatedData['id'], \PDO::PARAM_INT);
        $stmt->execute();

        return ['message'=> 'Updated user'];
    }

    public function delete(array $data): array {
        $stmt = $this->conn->prepare("DELETE FROM users
                                    WHERE id=:id");
        $stmt->bindValue(':id', $data['id'], \PDO::PARAM_INT);
        $stmt->execute();

        return ['message'=> 'User Deleted'];
    }
}