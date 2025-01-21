<?php
namespace Base\Core;

class ProviderLoader
{
    public static function load(Container $container): void
    {
        foreach (
            glob(__DIR__ . "/../../app/Providers/*.php")
            as $providerFile
        ) {
            require_once $providerFile;
            $providerClass = basename($providerFile, ".php");
            $fullyQualifiedClass = "App\\Providers\\$providerClass";

            if (class_exists($fullyQualifiedClass)) {
                $provider = new $fullyQualifiedClass();
                if ($provider instanceof ServiceProvider) {
                    $provider->register($container);
                }
            }
        }
    }
}
