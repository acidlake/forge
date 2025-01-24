<?php

namespace Base\Helpers;

/**
 * StringHelper
 *
 * Provides reusable utility functions for string manipulation.
 */
class StringHelper
{
    /**
     * Convert a string to snake_case.
     *
     * This method converts a string from camelCase or PascalCase to snake_case.
     * Example: `myVariableName` → `my_variable_name`
     *
     * @param string $input The input string to convert.
     * @return string The string in snake_case format.
     */
    public static function toSnakeCase(string $input): string
    {
        return strtolower(preg_replace("/(?<!^)[A-Z]/", '_$0', $input));
    }

    /**
     * Convert a string to camelCase.
     *
     * This method converts a string from snake_case or kebab-case to camelCase.
     * Example: `my_variable_name` → `myVariableName`
     *
     * @param string $input The input string to convert.
     * @return string The string in camelCase format.
     */
    public static function toCamelCase(string $input): string
    {
        return lcfirst(
            str_replace(" ", "", ucwords(str_replace("_", " ", $input)))
        );
    }

    /**
     * Convert a string to kebab-case.
     *
     * This method converts a string from camelCase or PascalCase to kebab-case.
     * Example: `myVariableName` → `my-variable-name`
     *
     * @param string $input The input string to convert.
     * @return string The string in kebab-case format.
     */
    public static function toKebabCase(string $input): string
    {
        return strtolower(preg_replace("/(?<!^)[A-Z]/", '-$0', $input));
    }

    /**
     * Capitalize the first letter of each word.
     *
     * This method capitalizes the first letter of each word in a string.
     * Example: `hello_world` → `Hello World`
     *
     * @param string $input The input string to capitalize.
     * @return string The capitalized string.
     */
    public static function capitalizeWords(string $input): string
    {
        return ucwords(str_replace("_", " ", $input));
    }

    /**
     * Convert a string to PascalCase.
     *
     * This method converts a string from kebab-case, snake_case, or any space-separated
     * format to PascalCase.
     * Example: `my_variable_name` → `MyVariableName`
     *
     * @param string $input The input string to convert.
     * @return string The string in PascalCase format.
     */
    public static function toPascalCase(string $input): string
    {
        return str_replace(
            " ",
            "",
            ucwords(str_replace(["-", "_"], " ", $input))
        );
    }

    /**
     * Check if a string starts with a given substring.
     *
     * This method checks if the string starts with the specified needle.
     *
     * @param string $haystack The string to check.
     * @param string $needle The substring to search for at the start.
     * @return bool True if the string starts with the needle, otherwise false.
     */
    public static function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    /**
     * Check if a string ends with a given substring.
     *
     * This method checks if the string ends with the specified needle.
     *
     * @param string $haystack The string to check.
     * @param string $needle The substring to search for at the end.
     * @return bool True if the string ends with the needle, otherwise false.
     */
    public static function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }

    /**
     * Truncate a string to a specified length.
     *
     * This method truncates the input string to the specified length and adds a suffix
     * (default is "..." if the string is longer than the specified length).
     *
     * @param string $text The input string to truncate.
     * @param int $length The length to truncate to.
     * @param string $suffix The suffix to append (default is "...")
     * @return string The truncated string with the suffix.
     */
    public static function truncate(
        string $text,
        int $length,
        string $suffix = "..."
    ): string {
        return strlen($text) > $length
            ? substr($text, 0, $length) . $suffix
            : $text;
    }

    /**
     * Convert a string to uppercase.
     *
     * This method converts the entire string to uppercase.
     *
     * @param string $input The input string to convert.
     * @return string The string in uppercase.
     */
    public static function toUpperCase(string $input): string
    {
        return strtoupper($input);
    }

    /**
     * Convert a string to lowercase.
     *
     * This method converts the entire string to lowercase.
     *
     * @param string $input The input string to convert.
     * @return string The string in lowercase.
     */
    public static function toLowerCase(string $input): string
    {
        return strtolower($input);
    }

    /**
     * Remove all whitespace from a string.
     *
     * This method removes all spaces, tabs, and line breaks from a string.
     *
     * @param string $input The input string to clean.
     * @return string The cleaned string with no whitespace.
     */
    public static function removeWhitespace(string $input): string
    {
        return preg_replace("/\s+/", "", $input);
    }

    /**
     * Replace all occurrences of a substring within a string.
     *
     * This method replaces all occurrences of a specified substring with another substring.
     *
     * @param string $input The input string to search within.
     * @param string $search The substring to search for.
     * @param string $replace The substring to replace the search value with.
     * @return string The string with replaced values.
     */
    public static function replaceAll(
        string $input,
        string $search,
        string $replace
    ): string {
        return str_replace($search, $replace, $input);
    }

    /**
     * Check if a string contains a given substring.
     *
     * This method checks if the string contains the specified substring.
     *
     * @param string $haystack The string to check.
     * @param string $needle The substring to search for.
     * @return bool True if the string contains the needle, otherwise false.
     */
    public static function contains(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) !== false;
    }
}
