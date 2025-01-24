<?php

namespace Base\Core;

use ArrayAccess;

/**
 * ConfigObject provides a wrapper for configuration data with dynamic property access and immutability.
 *
 * This class allows accessing configuration values as object properties or array offsets.
 * Nested arrays are automatically converted into `ConfigObject` instances for seamless access.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @class ConfigObject
 * @version 1.0.0
 * @category Configuration Management
 * @package Base\Core
 * @immutable This class enforces immutability for configuration data.
 * @author Jeremias
 * @copyright 2025
 * @implements ArrayAccess<mixed,mixed>
 */
class ConfigObject implements ArrayAccess
{
    /**
     * The configuration data.
     *
     * @var array
     */
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
     * If the property value is a nested array, it is automatically wrapped in a `ConfigObject` instance.
     *
     * @param string $key The property name.
     * @return mixed The property value or a new ConfigObject for nested arrays, or null if not found.
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
     * Check if an offset exists in the configuration data.
     *
     * Implements the `ArrayAccess` interface.
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
     * If the offset value is a nested array, it is automatically wrapped in a `ConfigObject` instance.
     *
     * @param mixed $offset The offset to retrieve.
     * @return mixed The value at the offset, or null if not found.
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get($offset);
    }

    /**
     * Set the value of an offset.
     *
     * This method throws an exception because configuration is immutable.
     *
     * @param mixed $offset The offset to set.
     * @param mixed $value The value to set.
     * @throws \RuntimeException Always, because configuration is immutable.
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \RuntimeException("Configuration is immutable.");
    }

    /**
     * Unset the value of an offset.
     *
     * This method throws an exception because configuration is immutable.
     *
     * @param mixed $offset The offset to unset.
     * @throws \RuntimeException Always, because configuration is immutable.
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new \RuntimeException("Configuration is immutable.");
    }

    /**
     * Convert the ConfigObject to an array.
     *
     * @return array The configuration data as an array.
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->data as $key => $value) {
            $result[$key] = $value instanceof self ? $value->toArray() : $value;
        }
        return $result;
    }
}
