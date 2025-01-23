<?php

namespace App\Controllers;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\JWTInterface;
use Base\Interfaces\NotificationManagerInterface;
use Base\Interfaces\OTPManagerInterface;
use Base\Interfaces\StorageManagerInterface;
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

        /**
         * Resolve the ViewInterface instance from the DI container.
         *
         * @var User $user
         */
        $user = User::find(1);
        print_r($user->name);
        /**
         * @var JWTInterface $jwt
         */
        $jwt = $this->resolve(JWTInterface::class);
        $token =
            "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJuYW1lIjoicGVyZXoifQ.agPRwGf2kdciEIfR4LeA5E71g_np4f87hO52KdX5IT8";
        $secretKey = "your-256-bit-secret";
        $algorithm = "HS256";
        $payload = ["name" => "perez"];

        print_r(
            $jwt::decode(
                token: $token,
                secretKey: $secretKey,
                algorithm: $algorithm
            )
        );

        echo "<br />";
        echo "Generating OTP";
        echo "<br />";
        /**
         * @var OTPManagerInterface $otpManager
         */
        $user = "jeremias2@gmail.com";
        $otpManager = $this->resolve(OTPManagerInterface::class);
        $otp = $otpManager->generateOTP($user);
        print $otp;
        $otpManager->sendOTP($user, $otp);
        echo "TODO: Implement OTPManager validateOTP method";
        $isValid = $otpManager->validateOTP($user, "02329");
        if ($isValid) {
            echo "OTP Valid";
        } else {
            echo "OTP Not valid";
        }
        echo "<br />";
        echo "<br />";
        echo "send email";
        $this->sendEmail();

        /**
         * @var StorageManagerInterface $storage
         */
        $key = "otp:{$user}";
        $storage = $this->resolve(StorageManagerInterface::class);
        $storage->set($key, $otp, 300);
        echo "Saved to storage";
        print $storage->get($key);

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
    public function sendEmail()
    {
        /**
         * @var NotificationManagerInterface $notifications
         */
        $notifications = $this->resolve(NotificationManagerInterface::class);

        $data = [
            "to" => "jeremias2@gmail.com",
            "subject" => "Welcome to Forge",
            "message" =>
                "<h1>Thank you for signing up!</h1><p>Welcome to Forge</p>",
            "isHtml" => true,
        ];

        $success = $notifications->send("email", $data);

        return $success ? "Email sent!" : "Failed to send email.";
    }
}
