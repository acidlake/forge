<?php

namespace Base\Core;

use Base\Interfaces\ConfigurationManagerInterface;

class ConfigurationManager implements ConfigurationManagerInterface
{
    private array $configs = [];

    public function __construct(
        string $frameworkConfigPath,
        string $appConfigPath = null
    ) {
        $this->loadConfigs($frameworkConfigPath);

        if ($appConfigPath !== null && is_dir($appConfigPath)) {
            $this->loadConfigs($appConfigPath, true); // Load app configs to override
        }
    }

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

    public function getGroup(string $group): ?ConfigObject
    {
        return isset($this->configs[$group])
            ? new ConfigObject($this->configs[$group])
            : null;
    }

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
