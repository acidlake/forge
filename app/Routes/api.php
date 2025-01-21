<?php
use Base\Core\RouterInterface;
use Base\Tools\MiddlewareHelper;

return function (RouterInterface $router) {
    // Framework Middlewares

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

    $apiMiddlewares = [
        MiddlewareHelper::jsonResponse(),
        MiddlewareHelper::cors(),
        MiddlewareHelper::securityHeaders(),
        MiddlewareHelper::compress(),
        MiddlewareHelper::rateLimit(10, 60),
        MiddlewareHelper::circuitBreaker(5, 60),
        MiddlewareHelper::ipWhitelist(["127.0.0.1", "192.168.1.1"]),
    ];

    $router->group($apiMiddlewares, function (RouterInterface $router) {
        $router->get("/api/status", function () {
            echo json_encode(["status" => "API is working!"]);
        });

        $router->post("/api/data", function () {
            echo json_encode(["message" => "Data created successfully"]);
        });
    });
};
