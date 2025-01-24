<?php

namespace App\Routes;

use App\Controllers\Api\UserController;
use App\Controllers\HomeController;
use Base\Core\ContainerHelper;
use Base\Interfaces\RouterInterface;
use Base\Interfaces\ViewInterface;

/**
 * Web Routes for the application.
 *
 * This file defines example routes for web-based functionality in the application.
 * These routes are designed to handle requests for the web interface and are separate
 * from the API routes.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @application Web Routes Example
 * @copyright 2025
 */

return function (RouterInterface $router) {
    /**
     * Resolve the ViewInterface instance from the DI container.
     *
     * The view instance can be used to render templates in the web routes.
     *
     * @var ViewInterface $view
     */
    $view = ContainerHelper::getContainer()->resolve(ViewInterface::class);

    /**
     * GET /functional
     *
     * Example of a functional route that returns a string response.
     * Responds with "Functional route works!".
     */
    $router->get("/functional", function () {
        return "Functional route works!";
    });

    /**
     * GET /
     *
     * Example of a route handled by a controller.
     * Calls the `index` method of `HomeController`.
     */
    $router->get("/", [HomeController::class, "index"]);
    $router->get("/users", [UserController::class, "index"]);

    /**
     * GET /user/{id}
     *
     * Example of a dynamic route with a parameter.
     * Responds with "User ID: {id}" where `{id}` is the dynamic value passed in the URL.
     *
     * @param string $id The dynamic user ID from the route.
     */
    $router->get("/user/{id}", function ($id) {
        echo "User ID: $id";
    });

    /**
     * POST /submit
     *
     * Example of a POST route to handle form submissions.
     * Responds with "Form submitted!".
     */
    $router->post("/submit", function () {
        echo "Form submitted!";
    });
};
