<?php

namespace Base\Tools;

use Base\Core\ContainerHelper;
use Base\Interfaces\ConfigurationManagerInterface;
use Base\Interfaces\ConfigHelperInterface;

class ConfigHelper implements ConfigHelperInterface
{
    /**
     * Retrieve a configuration value using the ConfigManager.
     *
     * @param string $name The dot-separated configuration key (e.g., "app.name").
     * @return mixed|null The configuration value, or null if not found.
     */
    public static function get(string $name): mixed
    {
        $configManager = ContainerHelper::getContainer()->resolve(
            ConfigurationManagerInterface::class
        );

        [$group, $key] = self::parseConfigKey($name);
        return $configManager->get($group, $key);
    }

    /**
     * Parse a dot-separated configuration key into group and key.
     *
     * @param string $name The dot-separated configuration key (e.g., "app.name").
     * @return array An array containing the group and key.
     */
    private static function parseConfigKey(string $name): array
    {
        $parts = explode(".", $name, 2); // Split into two parts: group and key
        $group = $parts[0]; // The first part is the group
        $key = $parts[1] ?? null; // The second part (if available) is the key
        return [$group, $key];
    }
}
