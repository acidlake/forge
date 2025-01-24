<?php

namespace Base\Helpers;

/**
 * Class HttpHelper
 * Provides utility methods for handling HTTP-related operations.
 */
class HttpHelper
{
    /**
     * Determines whether the request is an AJAX request.
     *
     * Checks if the request was made via JavaScript (using XMLHttpRequest).
     *
     * @return bool True if the request is an AJAX request, otherwise false.
     */
    public static function isAjax(): bool
    {
        return strtolower($_SERVER["HTTP_X_REQUESTED_WITH"] ?? "") ===
            "xmlhttprequest";
    }

    /**
     * Retrieves the client IP address from the request.
     *
     * This method checks the `REMOTE_ADDR` server variable to get the IP
     * address of the client making the request. If the variable is not set,
     * it returns "127.0.0.1" as a fallback.
     *
     * @return string The IP address of the client making the request.
     */
    public static function clientIp(): string
    {
        return $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1";
    }

    /**
     * Parses a query string into an associative array.
     *
     * This method uses PHP's `parse_str` function to convert a query string
     * into a key-value array.
     *
     * @param string $query The query string to parse.
     *
     * @return array An associative array of the parsed query parameters.
     */
    public static function parseQueryString(string $query): array
    {
        $result = [];
        parse_str($query, $result);
        return $result;
    }
}
