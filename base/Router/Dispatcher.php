<?php

namespace Base\Router;

use Base\Router\Http\Request;
use RuntimeException;

class Dispatcher
{
    private RouteCollection $routeCollection;
    private MiddlewareHandler $middlewareHandler;

    public function __construct(
        RouteCollection $routeCollection,
        MiddlewareHandler $middlewareHandler
    ) {
        $this->routeCollection = $routeCollection;
        $this->middlewareHandler = $middlewareHandler;
    }

    public function dispatch(array $route, Request $request): void
    {
        print_r("Router: Starting dispatch");
        $handler = $route["handler"];

        // Resolve controller definitions
        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;

            if (!class_exists($class) || !method_exists($class, $method)) {
                throw new RuntimeException(
                    "Controller or method not found: " . implode("::", $handler)
                );
            }

            $instance = new $class();
            $handler = [$instance, $method];
        }

        // Apply middleware
        foreach (array_reverse($route["middleware"]) as $middleware) {
            $handler = $middleware($handler);
        }

        // Append the request to the parameters
        array_unshift($route["params"], $request);

        // Execute the handler
        $response = call_user_func_array($handler, $route["params"]);

        if (is_string($response)) {
            echo $response;
        }
    }
}
