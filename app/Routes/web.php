<?php

namespace App\Routes;

use App\Controllers\Api\UserController;
use App\Controllers\HomeController;
use Base\Interfaces\RouterInterface;

return function (RouterInterface $router) {
    $router->get("/functional", function () {
        return "Functional route works!";
    });

    $router->get("/", [HomeController::class, "index"]);
    $router->get("/users", [UserController::class, "index"]);

    $router->get("/user/{id}", function ($id) {
        echo "User ID: $id";
    });

    $router->post("/submit", function () {
        echo "Form submitted!";
    });
};
