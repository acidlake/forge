<?php
namespace Base\Core;

use Base\Interfaces\RouterInterface;
use RuntimeException;

/**
 * RouterHelper class for managing a global singleton instance of the router.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 */
class RouterHelper
{
    /**
     * The singleton instance of the router.
     *
     * @var RouterInterface|null
     */
    private static ?RouterInterface $router = null;

    /**
     * Retrieve the global router instance.
     *
     * If the router instance does not already exist, an exception is thrown.
     *
     * @return RouterInterface The global router instance.
     */
    public static function getRouter(): RouterInterface
    {
        if (self::$router === null) {
            throw new RuntimeException("Router is not set.");
        }
        return self::$router;
    }

    /**
     * Set the global router instance.
     *
     * @param RouterInterface $router The router instance to set.
     *
     * @return void
     */
    public static function setRouter(RouterInterface $router): void
    {
        self::$router = $router;
    }
}
