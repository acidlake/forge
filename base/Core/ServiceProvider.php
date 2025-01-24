<?php

namespace Base\Core;

/**
 * Abstract class ServiceProvider
 *
 * Provides a blueprint for registering services and bindings into the Dependency Injection (DI) Container.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
abstract class ServiceProvider
{
    /**
     * Register services and bindings into the DI Container.
     *
     * This method must be implemented by any concrete service provider to define
     * its bindings and configurations in the container.
     *
     * @param Container $container The DI Container instance where services and bindings will be registered.
     *
     * @return void
     */
    abstract public function register(Container $container): void;
}
