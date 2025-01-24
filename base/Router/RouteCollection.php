<?php
namespace Base\Router;

class RouteCollection
{
    private array $routes = [];
    private array $constraints = [];

    public function add(
        string $method,
        string $uri,
        callable|array $handler
    ): void {
        $this->routes[] = [
            "method" => $method,
            "uri" => $this->convertToRegex($uri),
            "handler" => $handler,
            "params" => [],
            "middleware" => [],
        ];
    }

    public function match(string $method, string $uri): ?array
    {
        foreach ($this->routes as &$route) {
            if (
                $route["method"] === $method &&
                preg_match($route["uri"], $uri, $matches)
            ) {
                array_shift($matches);
                $route["params"] = $matches;
                return $route;
            }
        }

        return null;
    }

    public function setConstraint(string $parameter, string $pattern): void
    {
        $this->constraints[$parameter] = $pattern;
    }

    private function convertToRegex(string $uri): string
    {
        return "#^" .
            preg_replace_callback(
                "/\{(\w+)\}/",
                function ($matches) {
                    $param = $matches[1];
                    $pattern = $this->constraints[$param] ?? "[^/]+";
                    return "(?P<{$param}>{$pattern})";
                },
                $uri
            ) .
            "$#";
    }
}
