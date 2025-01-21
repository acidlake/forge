<?php
namespace Base\Helpers;

use Base\Core\ConfigObject;
use Base\Core\ContainerHelper;
use Base\Interfaces\ConfigHelperInterface;

class EnvHelper
{
    private static ?array $supportedEnvironments = null;
    private static string $currentEnvironment = "production";

    /**
     * Load the current environment and supported environments.
     */
    public static function initialize(): void
    {
        $config = ContainerHelper::getContainer()->resolve(
            ConfigHelperInterface::class
        );

        // Load supported environments
        $supportedEnvironments = $config->get("environment.supported", []);
        if ($supportedEnvironments instanceof ConfigObject) {
            $supportedEnvironments = $supportedEnvironments->toArray();
        }

        if (!is_array($supportedEnvironments)) {
            throw new \UnexpectedValueException(
                "Expected 'supported environments' to be an array."
            );
        }

        self::$supportedEnvironments = $supportedEnvironments;

        // Validate APP_ENV
        $env = self::get("APP_ENV", "production");
        $env = trim($env, " \t\n\r\0\x0B\"'"); // Remove extra quotes or whitespace
        if (!in_array($env, self::$supportedEnvironments, true)) {
            error_log("Invalid APP_ENV: '{$env}'. Defaulting to 'production'.");
            $env = "production";
        }

        self::$currentEnvironment = $env;
    }

    /**
     * Check if the current environment matches the given name.
     *
     * @param string $environment The environment name to check.
     *
     * @return bool
     */
    public static function isEnvironment(string $environment): bool
    {
        return self::$currentEnvironment === $environment;
    }

    /**
     * Check if the current environment matches a given name.
     *
     * @param string $name
     *
     * @return bool
     */
    public static function is(string $name): bool
    {
        if (!self::$supportedEnvironments) {
            self::initialize();
        }

        if (!in_array($name, self::$supportedEnvironments, true)) {
            throw new \InvalidArgumentException(
                "Unsupported environment: {$name}"
            );
        }

        return self::isEnvironment($name);
    }

    /**
     * Get an environment variable with a default value.
     *
     * @param string $key The environment variable name.
     * @param mixed $default Default value if the variable is not set.
     *
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? ($_SERVER[$key] ?? getenv($key));
        if (!$value) {
            error_log(
                "Env variable {$key} is not set. Defaulting to: {$default}"
            );
        }
        return $value ?: $default;
    }
}
