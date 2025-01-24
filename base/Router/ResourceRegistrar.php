<?php
namespace Base\Router;

class ResourceRegistrar
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function register(
        string $name,
        string $controller,
        array $options = []
    ): void {
        $routes = [
            "index" => [
                "method" => "GET",
                "uri" => "/{$name}",
                "action" => "index",
            ],
            "show" => [
                "method" => "GET",
                "uri" => "/{$name}/{id}",
                "action" => "show",
            ],
            "store" => [
                "method" => "POST",
                "uri" => "/{$name}",
                "action" => "store",
            ],
            "update" => [
                "method" => "PUT",
                "uri" => "/{$name}/{id}",
                "action" => "update",
            ],
            "destroy" => [
                "method" => "DELETE",
                "uri" => "/{$name}/{id}",
                "action" => "destroy",
            ],
        ];

        if (!empty($options["only"])) {
            $routes = array_intersect_key(
                $routes,
                array_flip($options["only"])
            );
        } elseif (!empty($options["except"])) {
            $routes = array_diff_key($routes, array_flip($options["except"]));
        }

        foreach ($routes as $route) {
            $this->router->{$route["method"]}($route["uri"], [
                $controller,
                $route["action"],
            ]);
        }
    }
}
