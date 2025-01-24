<?php

namespace Base\Router;

class MiddlewareHandler
{
    private array $middlewareStack = [];

    public function register(callable|array $middleware): void
    {
        if (is_array($middleware)) {
            $this->middlewareStack = array_merge(
                $this->middlewareStack,
                $middleware
            );
        } else {
            $this->middlewareStack[] = $middleware;
        }
    }

    public function applyMiddleware(
        array $middleware,
        callable $handler
    ): callable {
        foreach (array_reverse($middleware) as $mw) {
            $handler = $mw($handler);
        }
        return $handler;
    }
}
