<?php

namespace Base\Core;

use Exception;

/**
 * Dependency Injection Container for managing bindings and resolving dependencies.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
class Container
{
    /**
     * Array of bindings in the container.
     *
     * @var array<string, callable>
     */
    private array $bindings = [];

    /**
     * Binds an abstract type to a concrete implementation.
     *
     * @param string   $abstract The abstract type or interface to bind.
     * @param callable $concrete The concrete implementation (closure or callable).
     * @param bool     $override Whether to override an existing binding. Default is true.
     *
     * @throws Exception If binding already exists and override is set to false.
     *
     * @return void
     */
    public function bind(
        string $abstract,
        callable $concrete,
        bool $override = true
    ): void {
        if (!$override && isset($this->bindings[$abstract])) {
            throw new Exception(
                "Binding for {$abstract} already exists and cannot be overridden."
            );
        }

        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Resolves an abstract type to its concrete implementation.
     *
     * @param string $abstract The abstract type or interface to resolve.
     *
     * @throws Exception If no binding is found for the abstract type.
     *
     * @return mixed The resolved instance of the abstract type.
     */
    public function resolve(string $abstract)
    {
        if (!isset($this->bindings[$abstract])) {
            throw new Exception("No binding found for {$abstract}");
        }

        return call_user_func($this->bindings[$abstract]);
    }
}
