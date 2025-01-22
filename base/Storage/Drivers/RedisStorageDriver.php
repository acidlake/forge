<?php
namespace Base\Storage\Drivers;

use Base\Interfaces\StorageManagerInterface;
use Redis;

class RedisStorageDriver implements StorageManagerInterface
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function set(string $key, mixed $value, ?int $ttl = null): void
    {
        $this->redis->set($key, serialize($value), $ttl);
    }

    public function get(string $key): mixed
    {
        $data = $this->redis->get($key);
        return $data ? unserialize($data) : null;
    }

    public function delete(string $key): void
    {
        $this->redis->del($key);
    }

    public function exists(string $key): bool
    {
        return $this->redis->exists($key) > 0;
    }
}
