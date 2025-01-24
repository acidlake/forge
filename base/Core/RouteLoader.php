<?php

namespace Base\Core;

use Base\Interfaces\RouterInterface;
use Exception;

/**
 * RouteLoader class for loading and registering application-specific routes.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
class RouteLoader
{
    /**
     * Load and register all application-specific routes.
     *
     * This method iterates through all route files in the `app/Routes` directory,
     * includes them, and invokes their callbacks to define routes.
     *
     * @param RouterInterface $router The router instance to which routes will be registered.
     *
     * @throws \Exception If a route file does not return a valid callable.
     *
     * @return void
     */
    public static function load(RouterInterface $router): void
    {
        foreach (glob(BASE_PATH . "/app/Routes/*.php") as $routeFile) {
            $routeCallback = require $routeFile;

            if (is_callable($routeCallback)) {
                $routeCallback($router);
            } else {
                throw new Exception("Invalid route callback in {$routeFile}");
            }
        }
    }
}
