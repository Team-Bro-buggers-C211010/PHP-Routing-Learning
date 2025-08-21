<?php

namespace Router;

class Router
{
    private array $routes = [];

    private function add(string $path, string $method, callable $handler): void
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
            } else {
                call_user_func($handler);
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(["error" => "Internal server error", "details" => $e->getMessage()]);
        }
    }

    public function get(string $uri, callable $callbackFunction): void
    {
        $this->add(
            $uri,
            'GET',
            $callbackFunction
        );
    }

    public function post(string $uri, callable $callbackFunction): void {
        $this->add(
            $uri,
            'POST',
            $callbackFunction
        );
    }

    public function put(string $uri, callable $callbackFunction): void {
        $this->add(
            $uri,
            "PUT",
            $callbackFunction
        );
    }

    public function delete(string $uri, callable $callbackFunction): void {
        $this->add(
            $uri,
            "DELETE",
            $callbackFunction
        );
    }
}
