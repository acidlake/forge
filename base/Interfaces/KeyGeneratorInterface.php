<?php

namespace Base\Interfaces;

interface KeyGeneratorInterface
{
    /**
     * Generate a primary key based on the given strategy.
     *
     * @param string $strategy   The key generation strategy (e.g., 'uuid', 'auto_increment', 'custom', 'nanoid', 'snowflake', 'short-hash', 'composite', 'timestamp', 'random-string').
     * @param int    $keyLength  Length of the key for applicable strategies.
     * @param array  $keyFields  Fields used for composite keys.
     *
     * @return string The generated primary key.
     */
    public function generate(
        string $strategy = "uuid",
        int $keyLength = 36,
        array $keyFields = []
    ): string;
}
