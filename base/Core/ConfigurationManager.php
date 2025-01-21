<?php

namespace Base\Core;

use Base\Interfaces\ConfigurationManagerInterface;
use Base\Tools\EnvLoader;

/**
 * ConfigurationManager handles loading and managing configuration files for the Forge framework.
 *
 * This class supports:
 * - Loading `.env` files for environment-specific configurations.
 * - Merging framework and application-specific configuration files, with the application overriding the framework.
 * - Resolving `env()` references in configuration files to their corresponding environment variable values.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @class ConfigurationManager
 * @version 1.1.0
 * @category Configuration Management
 * @package Base\Core
 * @env-compatibility Supports loading .env files and resolving env() variables in configs.
 * @author Jeremias
 * @copyright 2025
 */
class ConfigurationManager implements ConfigurationManagerInterface
{
    /**
     * Stores all loaded configuration data.
     *
     * @var array
     */
    private array $configs = [];

    /**
     * Constructor to initialize and load configurations.
     *
     * @param string      $frameworkConfigPath The path to the framework's configuration directory.
     * @param string|null $appConfigPath       The path to the application's configuration directory (optional).
     * @param string|null $envPath             The path to the `.env` file (optional).
     */
    public function __construct(
        string $frameworkConfigPath,
        string $appConfigPath = null,
        string $envPath = null
    ) {
        // Load .env file if provided
        if ($envPath !== null) {
            EnvLoader::load($envPath);
        }

        // Load framework configs
        $this->loadConfigs($frameworkConfigPath);

        // Override with app configs
        if ($appConfigPath !== null) {
            $this->loadConfigs($appConfigPath, true);
        }
    }

    /**
     * Load configuration files from a directory.
     *
     * @param string $path     The directory containing configuration files.
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

            $this->configs[$configName] = array_merge(
                $this->configs[$configName],
                $this->resolveEnvVariables($configData)
            );
        }
    }

    /**
     * Resolve `env()` placeholders in configuration data.
     *
     * This method replaces strings in the format `env(KEY)` with their corresponding
     * environment variable values or null if the variable is not set.
     *
     * @param array $config The configuration array to process.
     *
     * @return array The processed configuration array with resolved `env()` values.
     */
    private function resolveEnvVariables(array $config): array
    {
        array_walk_recursive($config, function (&$value) {
            if (
                is_string($value) &&
                preg_match('/^env\(([^,]+)(?:,\s*(.+))?\)$/', $value, $matches)
            ) {
                $envKey = trim($matches[1]);
                $defaultValue = isset($matches[2])
                    ? trim($matches[2], " '\"")
                    : null; // Extract default value if provided
                $value =
                    getenv($envKey) !== false ? getenv($envKey) : $defaultValue; // Use .env value or default
            }
        });

        return $config;
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
