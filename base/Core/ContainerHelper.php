<?php
namespace Base\Core;

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
     * @return Container
     */
    public static function getContainer(): Container
    {
        if (self::$container === null) {
            self::$container = new Container();
        }
        return self::$container;
    }
}
