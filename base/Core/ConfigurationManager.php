<?php

namespace Base\Core;

use Base\Interfaces\ConfigurationManagerInterface;

/**
 * ConfigurationManager handles the loading and management of configuration files.
 *
 * This class supports loading framework and application-specific configurations, with the ability
 * to override framework configurations using application configurations. It also provides methods
 * to retrieve configuration groups or specific keys dynamically.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @class ConfigurationManager
 * @version 1.0.0
 * @category Configuration Management
 * @package Base\Core
 * @author Jeremias
 * @copyright 2025
 */
class ConfigurationManager implements ConfigurationManagerInterface
{
    /**
     * Array to hold all loaded configurations.
     *
     * @var array
     */
    private array $configs = [];

    /**
     * Constructor to initialize and load configurations.
     *
     * @param string      $frameworkConfigPath The path to the framework's configuration directory.
     * @param string|null $appConfigPath       The path to the application's configuration directory (optional).
     */
    public function __construct(
        string $frameworkConfigPath,
        string $appConfigPath = null
    ) {
        $this->loadConfigs($frameworkConfigPath);

        if ($appConfigPath !== null && is_dir($appConfigPath)) {
            $this->loadConfigs($appConfigPath, true); // Load app configs to override framework configs
        }
    }

    /**
     * Load configuration files from the specified directory.
     *
     * @param string $path     The directory path containing configuration files.
     * @param bool   $override Whether to override existing configurations.
     *
     * @return void
     */
    private function loadConfigs(string $path, bool $override = false): void
    {
        foreach (glob("{$path}/*.php") as $file) {
            $configName = basename($file, ".php");
            $configData = require $file;

            if (!isset($this->configs[$configName]) || $override) {
                $this->configs[$configName] = [];
            }

            // Merge framework configs with app configs, prioritizing app configs
            $this->configs[$configName] = array_merge(
                $this->configs[$configName],
                $configData
            );
        }
    }

    /**
     * Get a configuration group as a ConfigObject.
     *
     * @param string $group The name of the configuration group.
     *
     * @return ConfigObject|null The configuration group wrapped in a ConfigObject, or null if not found.
     */
    public function getGroup(string $group): ?ConfigObject
    {
        return isset($this->configs[$group])
            ? new ConfigObject($this->configs[$group])
            : null;
    }

    /**
     * Get a specific configuration value or group.
     *
     * If the `$key` parameter is null, the entire configuration group is returned as a ConfigObject.
     * Otherwise, the specific configuration value is returned.
     *
     * @param string      $group The name of the configuration group.
     * @param string|null $key   The specific key within the group, using dot notation for nested values.
     *
     * @return mixed The configuration value, a ConfigObject, or null if not found.
     */
    public function get(string $group, ?string $key = null): mixed
    {
        if (!isset($this->configs[$group])) {
            return null;
        }

        $data = $this->configs[$group];
        if ($key === null) {
            return new ConfigObject($data);
        }

        $keys = explode(".", $key);
        foreach ($keys as $k) {
            if (!isset($data[$k])) {
                return null;
            }
            $data = $data[$k];
        }

        return is_array($data) ? new ConfigObject($data) : $data;
    }
}
