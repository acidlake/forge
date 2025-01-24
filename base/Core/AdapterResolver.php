<?php

namespace Base\Core;

/**
 * AdapterResolver class responsible for resolving adapters.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
class AdapterResolver
{
    /**
     * Resolves an adapter class based on the given interface, base adapter, and app-specific adapter.
     *
     * This method returns a callable that instantiates either the app-specific adapter or the base adapter,
     * depending on the existence of the app-specific adapter class.
     *
     * @param string $interface        The fully qualified name of the interface the adapter should implement.
     * @param string $baseAdapterClass The fully qualified class name of the base adapter.
     * @param string $appAdapterClass  The fully qualified class name of the app-specific adapter.
     *
     * @return callable A callable that returns an instance of the resolved adapter.
     */
    public static function resolve(
        string $interface,
        string $baseAdapterClass,
        string $appAdapterClass
    ): callable {
        return function () use ($baseAdapterClass, $appAdapterClass) {
            if (class_exists($appAdapterClass)) {
                return new $appAdapterClass();
            }
            return new $baseAdapterClass();
        };
    }
}
