<?php
namespace Base\Core;

abstract class ServiceProvider
{
    /**
     * Register services and bindings into the DI Container.
     *
     * @param Container $container
     */
    abstract public function register(Container $container): void;
}
