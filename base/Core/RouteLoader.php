<?php
namespace Base\Core;

class RouteLoader
{
    /**
     * Register application-specific routes.
     *
     * @param RouterInterface $router
     */
    public static function load(RouterInterface $router): void
    {
        // Loop through all route files in app/Routes/
        foreach (glob(BASE_PATH . "/app/Routes/*.php") as $routeFile) {
            // Include the route file and pass the router to the callback
            $routeCallback = require $routeFile;

            // Ensure the file returns a valid callable
            if (is_callable($routeCallback)) {
                $routeCallback($router);
            } else {
                throw new \Exception("Invalid route callback in {$routeFile}");
            }
        }
    }
}
