<?php

namespace Router;

class Router
{
    private array $routes = [];

    public function add(string $path, string $method, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function resolve(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo json_encode(["error" => "Route not found"]);
            return;
        }

        try {
            if ($method === "GET") {
                call_user_func($handler, $_GET);
            } elseif ($method === "POST") {
                call_user_func($handler, $_POST);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal server error", "details" => $e->getMessage()]);
        }
    }
}
