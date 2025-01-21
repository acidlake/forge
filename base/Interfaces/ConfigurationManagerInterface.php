<?php

namespace Base\Interfaces;

interface ConfigManagerInterface
{
    /**
     * Get a configuration group.
     *
     * @param string $group
     * @return object|null
     */
    public function getGroup(string $group): ?object;

    /**
     * Get a configuration value by group and key.
     *
     * @param string $group
     * @param string|null $key
     * @return mixed
     */
    public function get(string $group, ?string $key = null): mixed;
}
