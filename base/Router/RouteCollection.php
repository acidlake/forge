<?php

namespace Base\Router;

class RouteCollection
{
    private array $routes = [];

    public function add(
        string $method,
        string $uri,
        callable|array $handler,
        array $middleware
    ): void {
        $this->routes[] = [
            "method" => $method,
            "pattern" => $this->convertToRegex($uri),
            "handler" => $handler,
            "middleware" => $middleware,
            "params" => [],
        ];
    }

    public function match(string $method, string $uri): ?array
    {
        foreach ($this->routes as $route) {
            if (
                $route["method"] === $method &&
                preg_match($route["pattern"], $uri, $matches)
            ) {
                array_shift($matches);
                $route["params"] = $matches;
                return $route;
            }
        }

        return null;
    }

    public function all(): array
    {
        return $this->routes;
    }

    private function convertToRegex(string $route): string
    {
        $route = preg_replace("/\{([a-zA-Z0-9_]+)\}/", '(?P<$1>[^/]+)', $route);
        return "#^" . $route . "$#";
    }
}
