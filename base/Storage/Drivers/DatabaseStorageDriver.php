<?php
namespace Base\Storage\Drivers;

use Base\Interfaces\StorageManagerInterface;
use PDO;

class DatabaseStorageDriver implements StorageManagerInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function set(string $key, mixed $value, ?int $ttl = null): void
    {
        $expiresAt = $ttl ? time() + $ttl : null;

        $stmt = $this->pdo->prepare(
            "INSERT INTO storage (key, value, expires_at) VALUES (:key, :value, :expires_at)
            ON DUPLICATE KEY UPDATE value = :value, expires_at = :expires_at"
        );
        $stmt->execute([
            ":key" => $key,
            ":value" => serialize($value),
            ":expires_at" => $expiresAt,
        ]);
    }

    public function get(string $key): mixed
    {
        $stmt = $this->pdo->prepare(
            "SELECT value, expires_at FROM storage WHERE key = :key"
        );
        $stmt->execute([":key" => $key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        if ($row["expires_at"] && $row["expires_at"] < time()) {
            $this->delete($key);
            return null;
        }

        return unserialize($row["value"]);
    }

    public function delete(string $key): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM storage WHERE key = :key");
        $stmt->execute([":key" => $key]);
    }

    public function exists(string $key): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM storage WHERE key = :key");
        $stmt->execute([":key" => $key]);
        return (bool) $stmt->fetchColumn();
    }
}
