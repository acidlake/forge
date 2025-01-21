<?php
namespace Base\Helpers;

class EnvHelper
{
    /**
     * Get the environment variable or default value.
     *
     * @param string $key The environment variable key.
     * @param mixed $default The default value if the key is not found.
     * @return mixed The environment variable value or the default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        // Cast numeric values
        if (is_numeric($value)) {
            return $value + 0; // Convert to int or float
        }

        // Handle boolean-like values
        $lower = strtolower($value);
        if ($lower === "true" || $lower === "false") {
            return $lower === "true";
        }

        return $value;
    }
}
