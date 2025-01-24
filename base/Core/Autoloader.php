<?php

namespace Base\Core;

/**
 * Autoloader class for registering and managing autoloaders.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
class Autoloader
{
    /**
     * Registers the framework, toolbox, and application autoloaders.
     *
     * @return void
     */
    public static function register(): void
    {
        // Register autoloaders
        spl_autoload_register([self::class, "frameworkAutoloader"]);
        spl_autoload_register([self::class, "toolboxAutoloader"]);
        spl_autoload_register([self::class, "applicationAutoloader"]);
    }

    /**
     * Autoloads classes from the framework's namespace.
     *
     * @param string $class The fully qualified class name.
     *
     * @return void
     */
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

    /**
     * Autoloads classes from the toolbox directory.
     *
     * The toolbox uses a namespace-to-path mapping defined in `namespace_map.php`.
     *
     * @param string $class The fully qualified class name.
     *
     * @return void
     */
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

    /**
     * Autoloads classes from the application's namespace.
     *
     * @param string $class The fully qualified class name.
     *
     * @return void
     */
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
