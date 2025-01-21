<?php

namespace Base\Core;

class Autoloader
{
    public static function register(): void
    {
        // Register autoloaders
        spl_autoload_register([self::class, "frameworkAutoloader"]);
        spl_autoload_register([self::class, "toolboxAutoloader"]);
        spl_autoload_register([self::class, "applicationAutoloader"]);
    }

    private static function frameworkAutoloader(string $class): void
    {
        $prefix = "Base\\";
        $baseDir = __DIR__ . "/../";

        if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
            return;
        }

        $relativeClass = str_replace(
            "\\",
            "/",
            substr($class, strlen($prefix))
        );
        $file = $baseDir . $relativeClass . ".php";

        if (file_exists($file)) {
            require $file;
        }
    }

    private static function toolboxAutoloader(string $class): void
    {
        $baseDir = BASE_PATH . "/toolbox/";
        $namespaceMap = require $baseDir . "namespace_map.php";

        foreach ($namespaceMap as $namespace => $path) {
            if (strncmp($namespace, $class, strlen($namespace)) === 0) {
                $relativeClass = str_replace(
                    "\\",
                    "/",
                    substr($class, strlen($namespace))
                );
                $file = $baseDir . $path . "/" . $relativeClass . ".php";

                if (file_exists($file)) {
                    require $file;
                }
                return;
            }
        }
    }

    private static function applicationAutoloader(string $class): void
    {
        $prefix = "App\\";
        $baseDir = BASE_PATH . "/app/";

        if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
            return;
        }

        $relativeClass = str_replace(
            "\\",
            "/",
            substr($class, strlen($prefix))
        );
        $file = $baseDir . $relativeClass . ".php";

        if (file_exists($file)) {
            require $file;
        }
    }
}
