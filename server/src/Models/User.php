<?php

namespace Models;

use PDO;
use Helpers\DatabaseErrorHandler;
use Helpers\Jwt;

class User
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getAll(string $name = ""): array
    {
        if (!isset($_COOKIE['auth_token'])) {
            throw new \Exception("No token found");
        }

        $token = $_COOKIE['auth_token'];
        $payload = Jwt::verify($token);

        if (!$payload) {
            throw new \Exception("Invalid or expired token");
        }

        if ($name) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE name LIKE ?");
            $stmt->execute(["%$name%"]);
        } else {
            $stmt = $this->conn->query("SELECT * FROM users");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById(int $id): array|false
    {
        try {

            if (!isset($_COOKIE["auth_token"])) {
                echo "No token found." . PHP_EOL;
                return false;
            }

            $token = $_COOKIE['auth_token'];
            $payload = Jwt::verify($token);

            if (!$payload) {
                // Invalid or expired token
                echo "Session expired or invalid token." . PHP_EOL;
                return false;
            }

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return DatabaseErrorHandler::handle($e);
        }
    }

    public function create(array $data): array
    {

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $stmt->bindValue(':name', htmlspecialchars($data['name'])); // sanitize the name to make all <,>,&," as &lt, &gt, &amp, &quot. For notshows as tags but as char. 
            $stmt->bindValue(':email', $data['email']);
            $stmt->execute();

            $token = Jwt::sign($data);
            setcookie('auth_token', $token, time() + 3600 * 24, '/', '', false, false);

            return [
                "message" => "User created successfully",
                "id" => $this->conn->lastInsertId()
            ];
        } catch (\PDOException $e) {
            return DatabaseErrorHandler::handle($e);
        }
    }

    public function update(array $data): array
    {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
            $stmt->execute();

            return ["message" => "User updated successfully"];
        } catch (\PDOException $e) {
            return DatabaseErrorHandler::handle($e);
        }
    }

    public function delete(array $data): array
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);
            $stmt->execute();

            return ["message" => "User deleted successfully"];
        } catch (\PDOException $e) {
            return DatabaseErrorHandler::handle($e);
        }
    }
}
