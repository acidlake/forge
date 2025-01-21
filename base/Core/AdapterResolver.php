<?php
namespace Base\Core;

class AdapterResolver
{
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
