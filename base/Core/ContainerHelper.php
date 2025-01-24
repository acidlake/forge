<?php

namespace Base\Core;

/**
 * Helper class for managing a global singleton instance of the dependency injection container.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
class ContainerHelper
{
    /**
     * The singleton instance of the container.
     *
     * @var Container|null
     */
    private static ?Container $container = null;

    /**
     * Retrieve the global container instance.
     *
     * If the container instance does not already exist, a new instance will be created.
     *
     * @return Container The global container instance.
     */
    public static function getContainer(): Container
    {
        if (self::$container === null) {
            throw new \RuntimeException("Container is not set.");
        }
        return self::$container;
    }

    public static function setContainer(Container $container): void
    {
        self::$container = $container;
    }
}
