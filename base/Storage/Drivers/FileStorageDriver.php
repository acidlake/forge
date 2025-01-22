<?php
namespace Base\Storage\Drivers;

use Base\Interfaces\StorageManagerInterface;

class FileStorageDriver implements StorageManagerInterface
{
    private string $storagePath;

    public function __construct(string $storagePath)
    {
        $this->storagePath = rtrim($storagePath, "/") . "/";
    }

    public function set(string $key, mixed $value, ?int $ttl = null): void
    {
        $filePath = $this->getFilePath($key);
        $data = [
            "value" => $value,
            "expires_at" => $ttl ? time() + $ttl : null,
        ];
        file_put_contents($filePath, serialize($data));
    }

    public function get(string $key): mixed
    {
        $filePath = $this->getFilePath($key);

        if (!file_exists($filePath)) {
            return null;
        }

        $data = unserialize(file_get_contents($filePath));

        if ($data["expires_at"] && $data["expires_at"] < time()) {
            unlink($filePath);
            return null;
        }

        return $data["value"];
    }

    public function delete(string $key): void
    {
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function exists(string $key): bool
    {
        return $this->get($key) !== null;
    }

    private function getFilePath(string $key): string
    {
        return $this->storagePath . md5($key) . ".store";
    }
}
