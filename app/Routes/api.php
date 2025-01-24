<?php

use App\Controllers\Api\UserController;
use Base\Interfaces\RouterInterface;

return function (RouterInterface $router) {
    $router->api("/api/v1", function (RouterInterface $router) {
        $router->get("/status", function () {
            echo json_encode(["status" => "API is working!"]);
        });

        $router->post("/data", function () {
            echo json_encode(["message" => "Data created successfully"]);
        });

        $router->get("/users", [UserController::class, "index"]);

        $router->post("/users", [UserController::class, "store"]);
    });
};
