<?php

namespace App\Controllers;

use Base\Core\ContainerHelper;
use Base\Interfaces\ViewInterface;

/**
 * HomeController handles the rendering of the homepage.
 *
 * This example demonstrates how to resolve dependencies, prepare dynamic data,
 * and render a view using the Forge framework.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @application Home Controller Example
 * @copyright 2025
 */
class HomeController
{
    /**
     * Handle the request to the home page.
     *
     * Prepares dynamic data for the homepage and renders the `home.index` view template.
     *
     * @return string The rendered HTML output of the homepage.
     */
    public function index(): string
    {
        /**
         * Resolve the ViewInterface instance from the DI container.
         *
         * @var ViewInterface $view
         */
        $view = ContainerHelper::getContainer()->resolve(ViewInterface::class);

        /**
         * Example list of posts to display on the homepage.
         *
         * @var array $posts
         */
        $posts = [
            [
                "id" => 1,
                "title" => "News one",
            ],
            [
                "id" => 2,
                "title" => "News two",
            ],
        ];

        /**
         * Example attributes for a link on the homepage.
         *
         * @var array $attributes
         */
        $attributes = [
            "href" => "https://example.com",
            "target" => "_blank",
            "class" => "btn btn-primary",
        ];

        /**
         * Data passed to the `home.index` view template.
         *
         * @var array $data
         */
        $data = [
            "title" => "Home",
            "frameworkName" => "Forge",
            "message" => "Welcome to the Forge framework!",
            "isLoggedIn" => true,
            "user" => "Forge",
            "posts" => $posts,
            "attributes" => $attributes,
        ];

        // Render the view template with the prepared data
        return $view->render("home.index", $data);
    }
}
