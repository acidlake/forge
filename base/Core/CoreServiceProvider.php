<?php

namespace Base\Core;

use Base\Adapters\CustomRouter;
use Base\Adapters\MonologAdapter;
use Base\Helpers\EnvHelper;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\ConfigurationManagerInterface;
use Base\Interfaces\LoggerInterface;
use Base\Interfaces\RouterInterface;
use Base\Templates\DefaultViewEngine;
use Base\Tools\ConfigHelper;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Base\Interfaces\ViewInterface;

/**
 * CoreServiceProvider class responsible for registering core services into the container.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * Registers core services into the dependency injection container.
     *
     * @param Container $container The dependency injection container instance.
     *
     * @return void
     */
    public function register(Container $container): void
    {
        // Register environment helper
        $container->bind(EnvHelper::class, function () {
            EnvHelper::initialize();
            return new EnvHelper();
        });

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
                    BASE_PATH . EnvHelper::get("LOG_PATH"),
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

        // Register the ConfigManager
        $container->bind(ConfigurationManagerInterface::class, function () {
            return new ConfigurationManager(
                CORE_CONFIG_PATH,
                APP_CONFIG_PATH,
                ENV_PATH
            );
        });

        // Register default config helper
        $container->bind(ConfigHelperInterface::class, function () {
            return new ConfigHelper();
        });
    }
}
