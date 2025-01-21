<?php

namespace Base\Adapters;

use Base\Core\RouterInterface;

class CustomRouter implements RouterInterface
{
    private array $routes = [];
    private array $middlewareStack = [];

    public function get(string $route, callable|array $handler): void
    {
        $this->addRoute("GET", $route, $handler);
    }

    public function post(string $route, callable|array $handler): void
    {
        $this->addRoute("POST", $route, $handler);
    }

    public function put(string $route, callable|array $handler): void
    {
        $this->addRoute("PUT", $route, $handler);
    }

    public function delete(string $route, callable|array $handler): void
    {
        $this->addRoute("DELETE", $route, $handler);
    }

    public function use(callable|array $middleware): void
    {
        if (is_array($middleware)) {
            foreach ($middleware as $mw) {
                $this->middlewareStack[] = $mw; // Use middlewareStack
            }
        } else {
            $this->middlewareStack[] = $middleware; // Use middlewareStack
        }
    }

    public function dispatch(): void
    {
        $httpMethod = $_SERVER["REQUEST_METHOD"];
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if (
                $route["method"] === $httpMethod &&
                $this->matchRoute($uri, $route)
            ) {
                $handler = $route["handler"];

                // Resolve controller definitions
                if (is_array($handler) && count($handler) === 2) {
                    [$class, $method] = $handler;

                    if (
                        class_exists($class) &&
                        method_exists($class, $method)
                    ) {
                        $instance = new $class();
                        $handler = [$instance, $method];
                    } else {
                        throw new \RuntimeException(
                            "Controller or method not found: " .
                                implode("::", $handler)
                        );
                    }
                }

                // Apply middleware
                foreach (array_reverse($route["middleware"]) as $middleware) {
                    $handler = $middleware($handler);
                }

                $response = call_user_func_array($handler, $route["params"]);

                if (is_string($response)) {
                    echo $response;
                }
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    private function addRoute(
        string $method,
        string $route,
        callable|array $handler
    ): void {
        $this->routes[] = [
            "method" => $method,
            "pattern" => $this->convertToRegex($route),
            "handler" => $handler,
            "params" => [],
            "middleware" => $this->middlewareStack,
        ];
    }

    private function matchRoute(string $uri, array $route): bool
    {
        $matches = [];
        if (preg_match($route["pattern"], $uri, $matches)) {
            array_shift($matches);
            $route["params"] = $matches;
            return true;
        }
        return false;
    }

    private function convertToRegex(string $route): string
    {
        $route = preg_replace("/\{([a-zA-Z0-9_]+)\}/", '(?P<$1>[^/]+)', $route);
        return "#^" . $route . "$#";
    }

    public function group(array $middleware, callable $callback): void
    {
        // Push middleware to stack
        array_push($this->middlewareStack, ...$middleware);

        // Execute group callback
        $callback($this);

        // Remove middleware after the group is executed
        foreach ($middleware as $mw) {
            array_pop($this->middlewareStack);
        }
    }
}
