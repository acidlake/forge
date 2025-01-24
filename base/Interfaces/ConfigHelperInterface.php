<?php
namespace Base\Interfaces;

interface ConfigHelperInterface
{
    /**
     * Retrieve a configuration value.
     *
     * @param string $name The name of the configuration to retrieve.
     * @return mixed|null The configuration value, or null if not found.
     */
    public static function get(string $name): mixed;
}
