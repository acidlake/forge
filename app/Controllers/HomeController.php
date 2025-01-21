<?php

namespace App\Controllers;

use Base\Core\ContainerHelper;
use Base\Core\ViewInterface;

class HomeController
{
    public function index(): string
    {
        /** @var ViewInterface $view */
        $view = ContainerHelper::getContainer()->resolve(ViewInterface::class);

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

        $attributes = [
            "href" => "https://example.com",
            "target" => "_blank",
            "class" => "btn btn-primary",
        ];

        $data = [
            "title" => "Home",
            "frameworkName" => "Forge",
            "message" => "Welcome to the Forge framework!",
            "isLoggedIn" => true,
            "user" => "Forge",
            "posts" => $posts,
            "attributes" => $attributes,
        ];

        return $view->render("home.index", $data);
    }
}
