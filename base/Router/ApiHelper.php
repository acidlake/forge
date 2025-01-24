<?php

namespace Base\Router;

use Base\Core\ContainerAwareTrait;

class ApiHelper
{
    use ContainerAwareTrait;

    public function setupApi(
        Router $router,
        string $prefix,
        callable $callback
    ): void {
        $router->group($this->getDefaultMiddleware(), function () use (
            $router,
            $prefix,
            $callback
        ) {
            $router->setPrefix($prefix);
            $callback($router);
            $router->clearPrefix();
        });
    }

    private function getDefaultMiddleware(): array
    {
        // Define default API middleware
        return [
                // Add middleware logic here
            ];
    }
}
