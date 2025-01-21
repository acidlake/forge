<?php
namespace Base\Tools;

use Base\Core\ContainerHelper;
use Base\Interfaces\ConfigManagerInterface;
use Base\Interfaces\ConfigHelperInterface;

class ConfigHelper implements ConfigHelperInterface
{
    /**
     * Retrieve a configuration value using the ConfigManager.
     *
     * @param string $name The name of the configuration to retrieve.
     * @return mixed|null The configuration value, or null if not found.
     */
    public static function get(string $name): mixed
    {
        $configManager = ContainerHelper::getContainer()->resolve(
            ConfigManagerInterface::class
        );
        return $configManager->get($name);
    }
}
