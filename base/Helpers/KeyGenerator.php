<?php

namespace Base\Helpers;

use Base\Interfaces\KeyGeneratorInterface;

class KeyGenerator implements KeyGeneratorInterface
{
    /**
     * Generate a primary key based on the given strategy.
     *
     * @param string $strategy   The key generation strategy (e.g., 'uuid', 'custom', 'nanoid', 'snowflake', 'short-hash', 'composite', 'timestamp', 'random-string').
     * @param int    $keyLength  Length of the key for applicable strategies.
     * @param array  $keyFields  Fields used for composite keys.
     *
     * @return string The generated primary key.
     *
     * @throws \RuntimeException If the strategy is not supported or invalid.
     */
    public function generate(
        string $strategy = "uuid",
        int $keyLength = 36,
        array $keyFields = []
    ): string {
        switch ($strategy) {
            case "uuid":
                return uuid_create();
            case "nanoid":
                return bin2hex(random_bytes($keyLength / 2));
            case "snowflake":
                return self::generateSnowflakeID();
            case "custom":
                throw new \RuntimeException(
                    "Custom key must be provided explicitly."
                );
            case "short-hash":
                return substr(md5(microtime()), 0, $keyLength);
            case "composite":
                return self::generateCompositeKey($keyFields);
            case "timestamp":
                return time() . bin2hex(random_bytes(4));
            case "random-string":
                return bin2hex(random_bytes($keyLength / 2));
            default:
                throw new \RuntimeException(
                    "Unsupported key strategy: {$strategy}"
                );
        }
    }

    /**
     * Generate a composite key using the provided fields.
     *
     * @param array $keyFields The fields to include in the composite key.
     *
     * @return string The generated composite key.
     */
    private static function generateCompositeKey(array $keyFields): string
    {
        $fields = implode("-", $keyFields);
        return md5($fields);
    }

    /**
     * Generate a Snowflake ID.
     *
     * @return string The generated Snowflake ID.
     */
    private static function generateSnowflakeID(): string
    {
        // Use a library or implement Snowflake ID logic here
        return "123456789012345678";
    }
}
