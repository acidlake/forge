<?php

use Base\Interfaces\RouterInterface;
use Base\Tools\MiddlewareHelper;

/**
 * API Routes for the application.
 *
 * This file defines example routes for the application, showcasing how to use the Forge framework's
 * routing and middleware capabilities. These routes are specific to the application and are not
 * part of the internal framework.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @application API Routes
 * @copyright 2025
 */

return function (RouterInterface $router) {
    /**
     * Validation rules for API endpoints.
     *
     * These rules are used as an example to validate incoming request payloads.
     *
     * @var array $validationRules
     */
    $validationRules = [
        "name" => [
            "required" => true,
            "min" => 3,
            "max" => 50,
        ],
        "email" => [
            "required" => true,
            "pattern" => '/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/',
        ],
        "password" => [
            "required" => true,
            "min" => 8,
            "max" => 20,
        ],
    ];

    /**
     * Middleware applied to API routes.
     *
     * These middlewares handle tasks like JSON responses, CORS, security headers, rate limiting, etc.
     *
     * @var array $apiMiddlewares
     */
    $apiMiddlewares = [
        MiddlewareHelper::jsonResponse(), // Ensure responses are in JSON format.
        MiddlewareHelper::cors(), // Handle Cross-Origin Resource Sharing (CORS).
        MiddlewareHelper::securityHeaders(), // Apply security-related HTTP headers.
        MiddlewareHelper::compress(), // Enable response compression.
        MiddlewareHelper::rateLimit(10, 60), // Limit to 10 requests per minute.
        MiddlewareHelper::circuitBreaker(5, 60), // Trigger circuit breaker after 5 failures in 60 seconds.
        MiddlewareHelper::ipWhitelist(["127.0.0.1", "192.168.1.1"]), // Restrict access to specific IPs.
    ];

    // Group API routes with middleware
    $router->group($apiMiddlewares, function (RouterInterface $router) {
        /**
         * GET /api/status
         *
         * Example endpoint to check the API status.
         * Responds with a JSON object indicating that the API is working.
         */
        $router->get("/api/status", function () {
            echo json_encode(["status" => "API is working!"]);
        });

        /**
         * POST /api/data
         *
         * Example endpoint to simulate data creation.
         * Responds with a JSON object confirming the data was created.
         */
        $router->post("/api/data", function () {
            echo json_encode(["message" => "Data created successfully"]);
        });
    });
};
