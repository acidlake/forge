<?php
namespace Base\Core;

use Base\Interfaces\ConfigManagerInterface;

class ConfigurationManager implements ConfigManagerInterface
{
    private array $configs = [];

    public function __construct()
    {
        $this->loadConfigs(BASE_PATH . "/base/config", "Base");
        $this->loadConfigs(BASE_PATH . "/app/config", "App");
    }

    private function loadConfigs(string $path, string $namespace): void
    {
        if (!is_dir($path)) {
            return;
        }

        foreach (glob("{$path}/*.php") as $file) {
            $configName = basename($file, ".php");
            $configData = require $file;

            if (!isset($this->configs[$configName])) {
                $this->configs[$configName] = [];
            }

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
