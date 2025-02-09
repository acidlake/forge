<?php

namespace Base\Router;

use Base\Core\ContainerAwareTrait;
use Base\Core\RouterHelper;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\EnvValueParserInterface;
use Base\Interfaces\RouterInterface;
use Base\Tools\MiddlewareHelper;
use Base\Router\Http\Request;

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
class Router implements RouterInterface
{
    use ContainerAwareTrait;
    /**
     * List of registered routes.
     *
     * @var array
     */
    private array $routes = [];
    private array $namedRoutes = [];
    private array $middlewareStack = [];

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function get(
        string $route,
        callable|array $handler,
        ?string $name = null
    ): void {
        $this->addRoute("GET", $route, $handler, $name);
    }

    public function post(
        string $route,
        callable|array $handler,
        ?string $name = null
    ): void {
        $this->addRoute("POST", $route, $handler, $name);
    }

    public function put(
        string $route,
        callable|array $handler,
        ?string $name = null
    ): void {
        $this->addRoute("PUT", $route, $handler, $name);
    }

    public function delete(
        string $route,
        callable|array $handler,
        ?string $name = null
    ): void {
        $this->addRoute("DELETE", $route, $handler, $name);
    }

    public function resource(string $name, string $controller): void
    {
        $this->get("/$name", [$controller, "index"], "{$name}.index");
        $this->get("/$name/create", [$controller, "create"], "{$name}.create");
        $this->post("/$name", [$controller, "store"], "{$name}.store");
        $this->get("/$name/{id}", [$controller, "show"], "{$name}.show");
        $this->get("/$name/{id}/edit", [$controller, "edit"], "{$name}.edit");
        $this->put("/$name/{id}", [$controller, "update"], "{$name}.update");
        $this->delete(
            "/$name/{id}",
            [$controller, "destroy"],
            "{$name}.destroy"
        );
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
        $router = RouterHelper::getRouter();
        $routes = $router->getRoutes();

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

                //Create a Request instance
                $request = $this->createRequest();

                // Append the request to the parameters
                array_unshift($route["params"], $request);

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
        // Check if the route already starts with regex delimiters
        if (str_starts_with($route, "#^") && str_ends_with($route, "$#")) {
            return $route; // Return the pattern as is
        }

        // Replace placeholders like {id} with named capture groups
        $route = preg_replace("/\{([a-zA-Z0-9_]+)\}/", '(?P<$1>[^/]+)', $route);

        // Escape special regex characters in the route
        $route = preg_quote($route, "#");

        // Reapply the named capture groups after escaping
        $route = str_replace("\(\?P<", "(?P<", $route);
        $route = str_replace("\>[^\/]+\)", ">[^/]+)", $route);

        // Add start (^) and end ($) delimiters
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
        $originalStack = $this->middlewareStack;
        $this->middlewareStack = array_merge(
            $this->middlewareStack,
            $middleware
        );

        try {
            $callback($this);
        } finally {
            $this->middlewareStack = $originalStack;
        }
    }

    public function api(string $prefix, callable $callback): void
    {
        /**
         * Resolve configuration and environment parsers.
         * @var ConfigHelperInterface $configHelper
         * @var EnvValueParserInterface $parser
         */
        $configHelper = $this->resolve(ConfigHelperInterface::class);
        $parser = $this->resolve(EnvValueParserInterface::class);

        $ipwhitelist = $parser->parseCommaSeparatedString(
            $configHelper->get("security.ipwhitelist")
        );
        $rateLimitMaxRequests = $configHelper->get(
            "security.rate_limit_max_request"
        );
        $rateLimitTimeFrame = $configHelper->get(
            "security.rate_limit_time_frame"
        );
        $circuitBreakerFailureThreshold = $configHelper->get(
            "security.circuit_breaker_failure_threshold"
        );
        $circuitBreakerTimeFrame = $configHelper->get(
            "security.circuit_breaker_time_frame"
        );

        /**
         * Middleware applied to API routes.
         *
         * These middlewares handle tasks like JSON responses, CORS, security headers, rate limiting, etc.
         *
         * @var array $apiMiddlewares
         */
        $apiMiddlewares = [
            MiddlewareHelper::jsonResponse(), // Ensure responses are in JSON format.
            MiddlewareHelper::cors(), // Handle Cross-Origin Resource Sharing (CORS).
            MiddlewareHelper::securityHeaders(), // Apply security-related HTTP headers.
            MiddlewareHelper::compress(), // Enable response compression.
            MiddlewareHelper::rateLimit(
                $rateLimitMaxRequests,
                $rateLimitTimeFrame
            ), // Limit to 10 requests per minute.
            MiddlewareHelper::circuitBreaker(
                $circuitBreakerFailureThreshold,
                $circuitBreakerTimeFrame
            ), // Trigger circuit breaker after 5 failures in 60 seconds.
            MiddlewareHelper::ipWhitelist($ipwhitelist), // Restrict access to specific IPs.
        ];

        $this->group($apiMiddlewares, function () use ($prefix, $callback) {
            $originalRoutes = $this->routes;
            $this->routes = [];

            $callback($this);
            foreach ($this->routes as &$route) {
                // Prepend the prefix to the route's pattern
                $route["pattern"] = $this->convertToRegex(
                    $prefix . substr($route["pattern"], 2, -2)
                ); // Adjust regex
            }

            $this->routes = array_merge($originalRoutes, $this->routes);
        });
    }

    /**
     * Create a Request instance from global variables.
     *
     * @return Request
     */
    private function createRequest(): Request
    {
        return new Request(
            $_GET, // Query parameters
            $_POST, // Request body
            $_FILES, // Uploaded files
            $_COOKIE, // Cookies
            $_SERVER // Server data
        );
    }

    public function route(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new \RuntimeException("Route not found: {$name}");
        }

        $route = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $route = str_replace("{{$key}}", $value, $route);
        }

        return $route;
    }
}
