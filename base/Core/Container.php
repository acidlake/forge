<?php
namespace Base\Core;

use Exception;

class Container
{
    private array $bindings = [];

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

    public function resolve(string $abstract)
    {
        if (!isset($this->bindings[$abstract])) {
            throw new Exception("No binding found for {$abstract}");
        }

        return call_user_func($this->bindings[$abstract]);
    }
}
