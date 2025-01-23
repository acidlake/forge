<?php

namespace Base\Helpers;

/**
 * Class ValidationHelper
 * Provides utility methods for common data validation operations.
 */
class ValidationHelper
{
    /**
     * Validates if a given string is a valid email address.
     *
     * This method uses PHP's `filter_var()` function with the `FILTER_VALIDATE_EMAIL`
     * filter to check if the given string is a valid email address.
     *
     * @param string $email The email address to validate.
     *
     * @return bool True if the email is valid, otherwise false.
     */
    public static function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validates if a given string is a valid URL.
     *
     * This method uses PHP's `filter_var()` function with the `FILTER_VALIDATE_URL`
     * filter to check if the given string is a valid URL.
     *
     * @param string $url The URL to validate.
     *
     * @return bool True if the URL is valid, otherwise false.
     */
    public static function isUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validates if a given string is a valid JSON string.
     *
     * This method uses `json_decode()` to attempt to parse the string. If there
     * is no error in decoding, the string is considered valid JSON.
     *
     * @param string $json The string to validate.
     *
     * @return bool True if the string is valid JSON, otherwise false.
     */
    public static function isJson(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Validates if a given string is a valid phone number.
     *
     * This method uses a regular expression to check if the phone number is in a
     * valid format, including optional international codes, spaces, and hyphens.
     *
     * @param string $phone The phone number to validate.
     *
     * @return bool True if the phone number is valid, otherwise false.
     */
    public static function isPhoneNumber(string $phone): bool
    {
        return preg_match('/^\+?[0-9\s\-]+$/', $phone) === 1;
    }
}
