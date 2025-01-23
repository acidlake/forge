<?php

namespace Base\Core;

use Base\Providers\AuthenticationServiceProvider;
use Base\Providers\CommandLoaderServiceProvider;
use Base\Providers\CommandServiceProvider;
use Base\Providers\ConfigurationServiceProvider;
use Base\Providers\DatabaseServiceProvider;
use Base\Providers\EnvironmentServiceProvider;
use Base\Providers\LogServiceProvider;
use Base\Providers\LoggerServiceProvider;
use Base\Providers\ModelServiceProvider;
use Base\Providers\NotificationServiceProvider;
use Base\Providers\RouterServiceProvider;
use Base\Providers\StorageServiceProvider;
use Base\Providers\UtilityServiceProvider;
use Base\Providers\UUIdServiceProvider;
use Base\Providers\ViewServiceProvider;

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
    use ContainerAwareTrait;

    private array $commands = [];

    public function has(string $abstract): bool
    {
        return isset($this->bindings[$abstract]);
    }

    /**
     * Registers core services into the dependency injection container.
     *
     * @param Container $container The dependency injection container instance.
     *
     * @return void
     */
    public function register(Container $container): void
    {
        (new EnvironmentServiceProvider())->register($container);
        (new CommandServiceProvider())->register($container);
        (new CommandLoaderServiceProvider())->register($container);
        (new LogServiceProvider())->register($container);
        (new LoggerServiceProvider())->register($container);
        (new AuthenticationServiceProvider())->register($container);
        (new DatabaseServiceProvider())->register($container);
        (new ModelServiceProvider())->register($container);
        (new RouterServiceProvider())->register($container);
        (new StorageServiceProvider())->register($container);
        (new NotificationServiceProvider())->register($container);
        (new ConfigurationServiceProvider())->register($container);
        (new UtilityServiceProvider())->register($container);
        (new ViewServiceProvider())->register($container);
        (new UUIdServiceProvider())->register($container);
    }
}
