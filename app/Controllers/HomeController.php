<?php

namespace App\Controllers;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\ViewInterface;
use Base\Helpers\EnvHelper;
use App\Models\User;

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
    use ContainerAwareTrait;
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
         * Resolve the ConfigHelperInterface instance from the DI container.
         *
         * @var ConfigHelperInterface $config
         */
        $config = $this->resolve(ConfigHelperInterface::class);

        /**
         * Resolve the ViewInterface instance from the DI container.
         *
         * @var ViewInterface $view
         */
        $view = $this->resolve(ViewInterface::class);

        //
        $user = User::find(1);

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
            "posts" => $posts,
            "attributes" => $attributes,
            "user" => $user,
        ];

        if (EnvHelper::is("development")) {
            echo "You're in development!";
        } elseif (EnvHelper::is("production")) {
            echo "You're in production!";
        } else {
            echo "You're in an unknown environment!";
        }

        print_r($config->get("environment.supported"));

        echo "<pre>";
        print_r($user);
        echo "</pre>";

        // Render the view template with the prepared data
        return $view->render("home.index", $data);
    }
}
