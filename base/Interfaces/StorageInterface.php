<?php
namespace Base\Interfaces;

interface StorageManagerInterface
{
    /**
     * Store a value in the storage.
     *
     * @param string $key The storage key.
     * @param mixed $value The value to store.
     * @param int|null $ttl Time-to-live in seconds (optional).
     */
    public function set(string $key, mixed $value, ?int $ttl = null): void;

    /**
     * Retrieve a value from the storage.
     *
     * @param string $key The storage key.
     * @return mixed|null The stored value, or null if not found.
     */
    public function get(string $key): mixed;

    /**
     * Delete a value from the storage.
     *
     * @param string $key The storage key.
     */
    public function delete(string $key): void;

    /**
     * Check if a key exists in the storage.
     *
     * @param string $key The storage key.
     * @return bool True if the key exists, false otherwise.
     */
    public function exists(string $key): bool;
}
