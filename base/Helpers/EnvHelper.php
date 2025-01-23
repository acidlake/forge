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
    public static function get(
        string $key,
        mixed $default = null,
        bool $asArray = false
    ): mixed {
        $value = $_ENV[$key] ?? ($_SERVER[$key] ?? getenv($key));
        if (!$value) {
            error_log(
                "Env variable {$key} is not set. Defaulting to: {$default}"
            );
        }

        // If requesting as an array and the value contains a comma, parse it
        if ($asArray && $value && strpos($value, ",") !== false) {
            return array_map("trim", explode(",", $value));
        }

        // Return raw value or default
        return $value ?: $default;
    }

    /**
     * Set or update an environment variable in the .env file.
     *
     * @param string $key The environment variable name.
     * @param string $value The value to set for the variable.
     *
     * @return bool True on success, false on failure.
     */
    public function set(string $key, string $value): bool
    {
        if (!defined("ENV_PATH")) {
            error_log("ENV_PATH constant is not defined.");
            return false;
        }

        if (!file_exists(ENV_PATH) || !is_writable(ENV_PATH)) {
            error_log(
                "The .env file does not exist or is not writable at path: " .
                    ENV_PATH
            );
            return false;
        }

        // Read the current contents of the .env file
        $envContents = file(
            ENV_PATH,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );
        $updated = false;

        foreach ($envContents as &$line) {
            // Match existing key and update it
            if (str_starts_with($line, "{$key}=")) {
                $line = "{$key}={$value}";
                $updated = true;
                break;
            }
        }

        // If the key was not found, add it to the end of the file
        if (!$updated) {
            $envContents[] = "{$key}={$value}";
        }

        // Write the updated contents back to the .env file
        $newContents = implode(PHP_EOL, $envContents) . PHP_EOL;

        return file_put_contents(ENV_PATH, $newContents) !== false;
    }

    public static function getPath(
        string $key,
        string $default,
        string $basePath
    ): string {
        $path = self::get($key, $default);

        // Resolve path to absolute
        if (!PathHelper::isAbsolute($path)) {
            $path = PathHelper::normalize(
                $basePath .
                    DIRECTORY_SEPARATOR .
                    ltrim($path, DIRECTORY_SEPARATOR)
            );
        }

        return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
}
