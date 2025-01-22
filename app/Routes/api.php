<?php

use App\Controllers\Api\UserController;
use Base\Interfaces\RouterInterface;

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

    $router->api("/api/v1", function (RouterInterface $router) {
        /**
         * GET /api/status
         *
         * Example endpoint to check the API status.
         * Responds with a JSON object indicating that the API is working.
         */
        $router->get("/status", function () {
            echo json_encode(["status" => "API is working!"]);
        });

        /**
         * POST /api/data
         *
         * Example endpoint to simulate data creation.
         * Responds with a JSON object confirming the data was created.
         */
        $router->post("/data", function () {
            echo json_encode(["message" => "Data created successfully"]);
        });

        $router->get("/users", [UserController::class, "index"]);

        $router->post("/users", [UserController::class, "store"]);
    });
};
