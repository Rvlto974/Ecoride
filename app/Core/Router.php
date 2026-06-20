<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $action): void
    {
        $this->add('GET', $path, $action);
    }

    public function post(string $path, array $action): void
    {
        $this->add('POST', $path, $action);
    }

    private function add(string $method, string $path, array $action): void
    {
        $this->routes[$method][$this->normalize($path)] = $action;
    }

    private function normalize(string $path): string
    {
        return '/' . trim($path, '/');
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = $this->normalize(parse_url($uri, PHP_URL_PATH) ?? '/');
        $action = $this->routes[$method][$path] ?? null;

        if ($action === null) {
            http_response_code(404);
            echo '404 - Page introuvable';
            return;
        }

        [$controllerClass, $methodName] = $action;
        (new $controllerClass())->$methodName();
    }
}
