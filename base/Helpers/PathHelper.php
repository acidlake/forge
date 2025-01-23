<?php

namespace Base\Helpers;

/**
 * Class PathHelper
 * Provides utility methods for handling file and directory paths.
 */
class PathHelper
{
    /**
     * Normalizes a file path by converting all directory separators to the system's default.
     *
     * This method replaces any instances of forward slashes (`/`) or backslashes (`\`) in the provided
     * path with the system's directory separator (`DIRECTORY_SEPARATOR`), ensuring the path is correctly formatted
     * for the current operating system.
     *
     * @param string $path The file or directory path to normalize.
     *
     * @return string The normalized path with consistent directory separators.
     */
    public static function normalize(string $path): string
    {
        return preg_replace("/[\/\\\\]+/", DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Extracts the file extension from a given filename.
     *
     * This method uses `pathinfo()` to retrieve the extension of a file based on its name.
     *
     * @param string $filename The filename or path to extract the extension from.
     *
     * @return string The file extension (without the leading dot), or an empty string if no extension is found.
     */
    public static function getExtension(string $filename): string
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * Determines if a given path is an absolute path.
     *
     * This method checks if the provided path starts with a directory separator (`/` or `\`),
     * which is a typical indicator of an absolute path.
     *
     * @param string $path The file or directory path to check.
     *
     * @return bool True if the path is absolute, otherwise false.
     */
    public static function isAbsolute(string $path): bool
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR);
    }

    public static function resolve(string $path, string $basePath): string
    {
        // Convert to absolute if not already
        if (!self::isAbsolute($path)) {
            $path =
                rtrim($basePath, DIRECTORY_SEPARATOR) .
                DIRECTORY_SEPARATOR .
                ltrim($path, DIRECTORY_SEPARATOR);
        }

        // Normalize the path and ensure trailing slash
        return rtrim(self::normalize($path), DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR;
    }
}
