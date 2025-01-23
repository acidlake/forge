<?php
namespace Base\Providers;

use Base\Core\Container;
use Base\Helpers\EnvHelper;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\ProviderInterface;
use Base\Tools\ConfigHelper;

class EnvironmentServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(EnvHelper::class, function () {
            EnvHelper::initialize();
            return new EnvHelper();
        });

        $container->bind(ConfigHelperInterface::class, function () {
            return new ConfigHelper();
        });
    }
}
