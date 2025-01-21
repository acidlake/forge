<?php

namespace Base\Core;

use Base\Core\Container;
use Base\Core\ServiceProvider;

/**
 * ProviderLoader class for loading and registering service providers.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
class ProviderLoader
{
    /**
     * Loads all service providers from the application Providers directory and registers them in the container.
     *
     * @param Container $container The dependency injection container instance.
     *
     * @return void
     */
    public static function load(Container $container): void
    {
        foreach (glob(BASE_PATH . "/app/Providers/*.php") as $providerFile) {
            // Include the provider file
            require_once $providerFile;

            // Extract the class name from the file name
            $providerClass = basename($providerFile, ".php");
            $fullyQualifiedClass = "App\\Providers\\$providerClass";

            // Check if the class exists and is an instance of ServiceProvider
            if (class_exists($fullyQualifiedClass)) {
                $provider = new $fullyQualifiedClass();
                if ($provider instanceof ServiceProvider) {
                    // Register the provider in the container
                    $provider->register($container);
                }
            }
        }
    }
}
