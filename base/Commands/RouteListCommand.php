<?php
namespace Base\Commands;

use Base\Core\RouterHelper;
use Base\Helpers\RouteListHelper;
use Base\Interfaces\CommandInterface;
use Base\Interfaces\RouterInterface;

class RouteListCommand implements CommandInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getName(): string
    {
        return "route:list";
    }

    public function getDescription(): string
    {
        return "Display a list of registered routes.";
    }

    public function execute(array $args = []): void
    {
        $router = RouterHelper::getRouter();
        $routes = $router->getRoutes();
        $routeListHelper = new RouteListHelper();

        if (empty($routes)) {
            echo "No routes have been registered.\n";
            return;
        }

        $routeListHelper->renderRouteList($routes);
    }
}
