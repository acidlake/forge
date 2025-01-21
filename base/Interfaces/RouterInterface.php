<?php
namespace Base\Interfaces;

interface RouterInterface
{
    /**
     * Register a GET route.
     *
     * @param string $route
     * @param callable $handler
     */
    public function get(string $route, callable $handler): void;

    /**
     * Register a POST route.
     *
     * @param string $route
     * @param callable $handler
     */
    public function post(string $route, callable $handler): void;

    /**
     * Register a PUT route.
     *
     * @param string $route
     * @param callable $handler
     */
    public function put(string $route, callable $handler): void;

    /**
     * Register a DELETE route.
     *
     * @param string $route
     * @param callable $handler
     */
    public function delete(string $route, callable $handler): void;

    /**
     * Register middleware(s).
     *
     * @param callable|array $middleware A single middleware or an array of middleware.
     */
    public function use(callable|array $middleware): void;

    /**
     * Group middleware(s).
     *
     */
    public function group(array $middleware, callable $callback): void;

    /**
     * Dispatch the registered routes and handle the current request.
     */
    public function dispatch(): void;
}
