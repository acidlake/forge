<?php
namespace Base\Core;

use Base\Adapters\CustomRouter;
use Base\Adapters\MonologAdapter;
use Base\Templates\DefaultViewEngine;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Base\Core\ViewInterface;

class CoreServiceProvider extends ServiceProvider
{
    public function register(Container $container): void
    {
        // Register the router
        $container->bind(
            RouterInterface::class,
            AdapterResolver::resolve(
                RouterInterface::class,
                CustomRouter::class,
                "App\\Adapters\\CustomRouter"
            )
        );

        // Register the logger
        $container->bind(LoggerInterface::class, function () {
            // Create a Monolog instance with a StreamHandler
            $monolog = new Logger("app");
            $monolog->pushHandler(
                new StreamHandler(
                    __DIR__ . "/../../logs/app.log",
                    Logger::DEBUG
                )
            );

            // Return the MonologAdapter with the Monolog instance
            return new MonologAdapter($monolog);
        });

        // Register default view engine
        $container->bind(ViewInterface::class, function () {
            return new DefaultViewEngine(VIEW_PATH);
        });
    }
}
