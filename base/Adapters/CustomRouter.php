<?php

namespace Base\Adapters;

use Base\Interfaces\RouterInterface;

/**
 * CustomRouter provides a lightweight routing system for the Forge framework.
 *
 * Handles route definitions, middleware application, and request dispatching.
 * Supports HTTP methods (GET, POST, PUT, DELETE), middleware stacking, and route groups.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class CustomRouter implements RouterInterface
{
    /**
     * List of registered routes.
     *
     * @var array
     */
    private array $routes = [];

    /**
     * Stack of global middleware applied to all routes.
     *
     * @var array
     */
    private array $middlewareStack = [];

    /**
     * Register a GET route.
     *
     * @param string         $route   The route pattern.
     * @param callable|array $handler The route handler (callable or [Controller::class, 'method']).
     */
    public function get(string $route, callable|array $handler): void
    {
        $this->addRoute("GET", $route, $handler);
    }

    /**
     * Register a POST route.
     *
     * @param string         $route   The route pattern.
     * @param callable|array $handler The route handler (callable or [Controller::class, 'method']).
     */
    public function post(string $route, callable|array $handler): void
    {
        $this->addRoute("POST", $route, $handler);
    }

    /**
     * Register a PUT route.
     *
     * @param string         $route   The route pattern.
     * @param callable|array $handler The route handler (callable or [Controller::class, 'method']).
     */
    public function put(string $route, callable|array $handler): void
    {
        $this->addRoute("PUT", $route, $handler);
    }

    /**
     * Register a DELETE route.
     *
     * @param string         $route   The route pattern.
     * @param callable|array $handler The route handler (callable or [Controller::class, 'method']).
     */
    public function delete(string $route, callable|array $handler): void
    {
        $this->addRoute("DELETE", $route, $handler);
    }

    /**
     * Register middleware to be applied globally or to specific routes.
     *
     * @param callable|array $middleware A single middleware or an array of middleware.
     */
    public function use(callable|array $middleware): void
    {
        if (is_array($middleware)) {
            foreach ($middleware as $mw) {
                $this->middlewareStack[] = $mw;
            }
        } else {
            $this->middlewareStack[] = $middleware;
        }
    }

    /**
     * Dispatch the current HTTP request to the appropriate route handler.
     *
     * Matches the request method and URI, applies middleware, and invokes the handler.
     * Returns a 404 response if no route matches.
     */
    public function dispatch(): void
    {
        $httpMethod = $_SERVER["REQUEST_METHOD"];
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        foreach ($this->routes as &$route) {
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

    /**
     * Add a route to the routing table.
     *
     * @param string         $method  The HTTP method (e.g., GET, POST).
     * @param string         $route   The route pattern.
     * @param callable|array $handler The route handler.
     */
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

    /**
     * Check if a route matches the current URI.
     *
     * @param string $uri   The current request URI.
     * @param array  $route The route configuration.
     *
     * @return bool True if the route matches, false otherwise.
     */
    private function matchRoute(string $uri, array &$route): bool
    {
        $matches = [];
        if (preg_match($route["pattern"], $uri, $matches)) {
            array_shift($matches);
            $route["params"] = $matches;
            return true;
        }
        return false;
    }

    /**
     * Convert a route pattern into a regex pattern for matching.
     *
     * @param string $route The route pattern (e.g., "/user/{id}").
     *
     * @return string The regex pattern.
     */
    private function convertToRegex(string $route): string
    {
        $route = preg_replace("/\{([a-zA-Z0-9_]+)\}/", '(?P<$1>[^/]+)', $route);
        return "#^" . $route . "$#";
    }

    /**
     * Group routes with a shared set of middleware.
     *
     * @param array    $middleware The middleware to apply to the grouped routes.
     * @param callable $callback   A callback to define the grouped routes.
     */
    public function group(array $middleware, callable $callback): void
    {
        array_push($this->middlewareStack, ...$middleware);
        $callback($this);
        foreach ($middleware as $mw) {
            array_pop($this->middlewareStack);
        }
    }
}
