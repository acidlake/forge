<?php

namespace Base\Tools;

use Base\Interfaces\RouterInterface;
use Base\Tools\MiddlewareHelper;

/**
 * InternalRoutes handles the registration of internal framework-specific routes.
 *
 * Provides routes for internal functionality, such as monitoring and diagnostics.
 * Routes are protected with middleware for security and performance optimization.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class InternalRoutes
{
    /**
     * Register internal framework routes.
     *
     * Adds routes used for internal framework operations, such as uptime checks,
     * and applies middleware for security and performance.
     *
     * @param RouterInterface $router The router instance to register the routes with.
     *
     * @return void
     */
    public static function register(RouterInterface $router): void
    {
        $apiMiddlewares = [
            MiddlewareHelper::jsonResponse(), // Ensure responses are in JSON format.
            MiddlewareHelper::cors(), // Handle Cross-Origin Resource Sharing (CORS).
            MiddlewareHelper::securityHeaders(), // Apply security-related HTTP headers.
            MiddlewareHelper::compress(), // Enable response compression.
            MiddlewareHelper::rateLimit(10, 60), // Limit to 10 requests per minute.
            MiddlewareHelper::circuitBreaker(5, 60), // Trigger circuit breaker after 5 failures in 60 seconds.
            MiddlewareHelper::ipWhitelist(["127.0.0.1", "192.168.1.1"]), // Allow access only from specified IPs.
        ];

        $router->group($apiMiddlewares, function (RouterInterface $router) {
            $router->get("/_internal/uptime", function () {
                echo json_encode(["status" => "ok", "uptime" => time()]);
            });
        });

        $router->web("/_docs", function (RouterInterface $router) {
            $router->get("/", function () {
                echo json_encode(["status" => "ok", "uptime" => time()]);
            });
        });
    }
}
