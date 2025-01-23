<?php

namespace Base\Providers;

use Base\Core\Container;
use Base\Interfaces\ConfigurationManagerInterface;
use Base\Core\ConfigurationManager;
use Base\Interfaces\ProviderInterface;

class ConfigurationServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(ConfigurationManagerInterface::class, function () {
            return new ConfigurationManager(
                CORE_CONFIG_PATH, // Path to core configurations
                APP_CONFIG_PATH, // Path to application configurations
                ENV_PATH // Path to environment-specific configurations
            );
        });
    }
}
