<?php
namespace Base\Router;

use Base\Core\RouterHelper;
use Base\Router\RouteCollection;
use Base\Router\ResourceRegistrar;
use Base\Router\MiddlewareHandler;
use Base\Router\Http\Request;

class Router
{
    private RouteCollection $routes;
    private MiddlewareHandler $middlewareHandler;
    private ResourceRegistrar $resourceRegistrar;

    public function __construct()
    {
        $this->routes = new RouteCollection();
        $this->middlewareHandler = new MiddlewareHandler();
        $this->resourceRegistrar = new ResourceRegistrar($this);
    }

    public function get(string $uri, callable|array $handler): self
    {
        $this->routes->add("GET", $uri, $handler);
        return $this;
    }

    public function post(string $uri, callable|array $handler): self
    {
        $this->routes->add("POST", $uri, $handler);
        return $this;
    }

    public function put(string $uri, callable|array $handler): self
    {
        $this->routes->add("PUT", $uri, $handler);
        return $this;
    }

    public function delete(string $uri, callable|array $handler): self
    {
        $this->routes->add("DELETE", $uri, $handler);
        return $this;
    }

    public function resource(
        string $name,
        string $controller,
        array $options = []
    ): self {
        $this->resourceRegistrar->register($name, $controller, $options);
        return $this;
    }

    public function dispatch(Request $request): void
    {
        $router = RouterHelper::getRouter();
        $routes = $router->getRoutes();

        $route = $this->routes->match(
            $request->getMethod(),
            $request->getUri()
        );

        if (!$route) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $handler = $route["handler"];

        $this->middlewareHandler->apply($route["middleware"], function () use (
            $handler,
            $route
        ) {
            if (is_callable($handler)) {
                call_user_func_array($handler, $route["params"]);
            } elseif (is_array($handler) && count($handler) === 2) {
                [$class, $method] = $handler;
                (new $class())->$method(...$route["params"]);
            }
        });
    }

    public function middlewareGroup(string $name, array $middlewares): void
    {
        $this->middlewareHandler->registerGroup($name, $middlewares);
    }
}
