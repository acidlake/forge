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
        if (!is_string($value)) {
            throw new \InvalidArgumentException(
                __METHOD__ .
                    "(): Argument #1 (\$value) must be of type string, " .
                    gettype($value) .
                    " given."
            );
        }

        return array_map("trim", explode(",", $value));
    }
}
