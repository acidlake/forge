<?php
namespace App\Routes;

use App\Controllers\HomeController;
use Base\Core\ContainerHelper;
use Base\Core\RouterInterface;
use Base\Core\ViewInterface;

return function (RouterInterface $router) {
    /** @var ViewInterface $view */
    $view = ContainerHelper::getContainer()->resolve(ViewInterface::class);
    $router->get("/functional", function () {
        return "Functional route works!";
    });

    $router->get("/", [HomeController::class, "index"]);

    $router->get("/user/{id}", function ($id) {
        echo "User ID: $id";
    });

    $router->post("/submit", function () {
        echo "Form submitted!";
    });
};
