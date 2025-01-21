<?php

namespace Base\Interfaces;

/**
 * Interface RouterInterface
 *
 * Defines the contract for a router implementation in the Forge framework.
 * Allows registering routes and middleware for various HTTP methods.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
interface RouterInterface
{
    /**
     * Register a GET route.
     *
     * @param string   $route   The route path (e.g., '/example').
     * @param callable $handler The handler function for the route.
     *
     * @return void
     */
    public function get(string $route, callable $handler): void;

    /**
     * Register a POST route.
     *
     * @param string   $route   The route path (e.g., '/example').
     * @param callable $handler The handler function for the route.
     *
     * @return void
     */
    public function post(string $route, callable $handler): void;

    /**
     * Register a PUT route.
     *
     * @param string   $route   The route path (e.g., '/example').
     * @param callable $handler The handler function for the route.
     *
     * @return void
     */
    public function put(string $route, callable $handler): void;

    /**
     * Register a DELETE route.
     *
     * @param string   $route   The route path (e.g., '/example').
     * @param callable $handler The handler function for the route.
     *
     * @return void
     */
    public function delete(string $route, callable $handler): void;

    /**
     * Register middleware(s) to be applied to routes.
     *
     * @param callable|array $middleware A single middleware function or an array of middleware functions.
     *
     * @return void
     */
    public function use(callable|array $middleware): void;

    /**
     * Group middleware(s) and apply them to a set of routes.
     *
     * @param array    $middleware An array of middleware functions to apply.
     * @param callable $callback   A callback defining the group of routes.
     *
     * @return void
     */
    public function group(array $middleware, callable $callback): void;

    /**
     * Dispatch the registered routes and handle the current HTTP request.
     *
     * @return void
     */
    public function dispatch(): void;
}
