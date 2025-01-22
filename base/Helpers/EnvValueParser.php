<?php
namespace Base\Helpers;

use Base\Interfaces\EnvValueParserInterface;

class EnvValueParser implements EnvValueParserInterface
{
    /**
     * Convert a comma-separated string into an array, trimming any surrounding spaces.
     *
     * @param string $value The value to parse, e.g., "127.0.0.1, 192.168.1.1"
     *
     * @return array The parsed array, e.g., ["127.0.0.1", "192.168.1.1"]
     */
    public static function parseCommaSeparatedString(string $value): array
    {
        // Split the string by commas and trim spaces from each part
        return array_map("trim", explode(",", $value));
    }
}
