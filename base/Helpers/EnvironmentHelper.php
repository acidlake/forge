<?php

namespace Base\Helpers;

/**
 * Class EnvironmentHelper
 * Provides utility methods for interacting with environment variables.
 */
class EnvironmentHelper
{
    /**
     * Retrieves the value of an environment variable.
     *
     * This method fetches the value of an environment variable based on the provided key.
     * If the environment variable is not set, it returns the provided default value.
     *
     * @param string $key The name of the environment variable to retrieve.
     * @param mixed $default The default value to return if the environment variable is not set (optional).
     *
     * @return mixed The value of the environment variable or the default value if the variable is not set.
     */
    public static function get(string $key, $default = null): mixed
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Checks if an environment variable is set.
     *
     * This method checks if a specific environment variable is present in the `$_ENV` superglobal.
     *
     * @param string $key The name of the environment variable to check.
     *
     * @return bool True if the environment variable is set, otherwise false.
     */
    public static function has(string $key): bool
    {
        return isset($_ENV[$key]);
    }
}
