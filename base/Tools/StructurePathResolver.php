<?php

namespace Base\Tools;

use Base\Tools\ConfigHelper;

class StructurePathResolver
{
    /**
     * Get the path for a given key based on the structure type.
     *
     * @param string $key The key for the path (e.g., 'seeders', 'models').
     * @param string $default Default path key to fallback to.
     * @return string
     */
    public static function resolvePath(
        string $key,
        string $default = "default"
    ): string {
        $structureType = ConfigHelper::get("structure.type", "default");
        return ConfigHelper::get(
            "structure.paths.{$structureType}.{$key}",
            ConfigHelper::get("structure.paths.{$default}.{$key}")
        );
    }

    /**
     * Get the namespace for a given path.
     *
     * @param string $path The path to resolve the namespace for.
     * @return string
     */
    public static function resolveNamespace(string $path): string
    {
        $baseNamespace = "App";
        $relativePath = str_replace(BASE_PATH, "", $path);

        // Normalize and remove leading/trailing slashes
        $relativePath = trim(str_replace("/", "\\", $relativePath), "\\");

        // Remove any redundant 'app' prefix
        $relativePath = preg_replace("/^app\\\\/i", "", $relativePath);

        return "{$baseNamespace}\\{$relativePath}";
    }
}
