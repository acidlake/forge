<?php

namespace Base\Core;

use ArrayAccess;

class ConfigObject implements ArrayAccess
{
    private array $data;

    /**
     * Constructor to initialize the configuration data.
     *
     * @param array $data The configuration data.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Magic method to access properties dynamically.
     *
     * @param string $key The property name.
     * @return mixed The property value or a new ConfigObject for nested arrays.
     */
    public function __get(string $key)
    {
        return isset($this->data[$key])
            ? (is_array($this->data[$key])
                ? new self($this->data[$key])
                : $this->data[$key])
            : null;
    }

    /**
     * Magic method to check if a property exists.
     *
     * @param string $key The property name.
     * @return bool True if the property exists, false otherwise.
     */
    public function __isset(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Check if an offset exists.
     *
     * @param mixed $offset The offset to check.
     * @return bool True if the offset exists, false otherwise.
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Get the value of an offset.
     *
     * @param mixed $offset The offset to retrieve.
     * @return mixed The value at the offset.
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get($offset);
    }

    /**
     * Set the value of an offset.
     *
     * @param mixed $offset The offset to set.
     * @param mixed $value The value to set.
     * @throws \RuntimeException Because configuration is immutable.
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \RuntimeException("Configuration is immutable.");
    }

    /**
     * Unset the value of an offset.
     *
     * @param mixed $offset The offset to unset.
     * @throws \RuntimeException Because configuration is immutable.
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new \RuntimeException("Configuration is immutable.");
    }
}
