<?php

namespace Base\Tools;

/**
 * EnvLoader loads environment variables from a `.env` file into PHP's environment.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class EnvLoader
{
    /**
     * Load the .env file into the environment.
     *
     * @param string $envPath Path to the `.env` file.
     *
     * @return void
     * @throws \RuntimeException If the `.env` file cannot be found or read.
     */
    public static function load(string $envPath): void
    {
        if (!file_exists($envPath)) {
            throw new \RuntimeException(
                "The .env file was not found at: {$envPath}"
            );
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if ($line === "" || str_starts_with($line, "#")) {
                continue;
            }

            // Remove inline comments
            if (strpos($line, "#") !== false) {
                $line = preg_replace('/\s+#.*$/', "", $line);
            }

            // Parse key-value pairs
            if (strpos($line, "=") === false) {
                // Skip malformed lines without an '='
                continue;
            }

            [$key, $value] = explode("=", $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Handle and normalize quotes
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1); // Remove surrounding quotes
            }

            // Fix mismatched quotes
            $value = trim($value, '"\'');

            // Check if the value is a comma-separated list (array-like)
            if (strpos($value, ",") !== false) {
                // Convert to array by splitting and trimming
                $value = array_map("trim", explode(",", $value));
            }

            if (is_array($value)) {
                $value = implode(",", $value);
            }

            // Set in environment
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}
