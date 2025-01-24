<?php

namespace Base\Helpers;

/**
 * Class SecurityHelper
 * Provides utility methods for common security-related operations.
 */
class SecurityHelper
{
    /**
     * Hashes a password using the BCRYPT algorithm.
     *
     * This method takes a plain-text password and hashes it using PHP's
     * `password_hash()` function with the BCRYPT algorithm.
     *
     * @param string $password The plain-text password to hash.
     *
     * @return string The hashed password.
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verifies a password against a given hash.
     *
     * This method checks whether the given plain-text password matches the
     * hashed version using PHP's `password_verify()` function.
     *
     * @param string $password The plain-text password to verify.
     * @param string $hash The hashed password to compare against.
     *
     * @return bool True if the password matches the hash, otherwise false.
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Generates a secure random token of the specified length.
     *
     * This method generates a secure random token using PHP's `random_bytes()`
     * function, which is then converted to a hexadecimal string using `bin2hex()`.
     *
     * @param int $length The length of the token (default is 32 bytes).
     *
     * @return string The generated token as a hexadecimal string.
     */
    public static function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Sanitizes input to prevent XSS (Cross-site Scripting) attacks.
     *
     * This method converts special characters in a string to HTML entities
     * using `htmlspecialchars()`, ensuring that the input is safe for rendering
     * in an HTML context.
     *
     * @param string $input The input string to sanitize.
     *
     * @return string The sanitized string.
     */
    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, "UTF-8");
    }
}
