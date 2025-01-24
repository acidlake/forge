<?php
namespace Base\Router;

class MiddlewareHandler
{
    private array $groups = [];

    public function registerGroup(string $name, array $middlewares): void
    {
        $this->groups[$name] = $middlewares;
    }

    public function apply(array $middlewares, callable $next): void
    {
        foreach ($middlewares as $middleware) {
            $middleware($next);
        }
    }
}
