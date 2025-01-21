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
            // Skip comments
            if (str_starts_with(trim($line), "#")) {
                continue;
            }

            // Parse key-value pairs
            [$key, $value] = explode("=", $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Handle quoted values
            if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                $value = substr($value, 1, -1);
            } elseif (
                str_starts_with($value, "'") &&
                str_ends_with($value, "'")
            ) {
                $value = substr($value, 1, -1);
            }

            // Set in environment
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}
