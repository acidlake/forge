<?php

namespace Base\Database\Drivers;

use Base\Database\DatabaseAdapterInterface;
use PDO;

class MysqlAdapter implements DatabaseAdapterInterface
{
    private PDO $pdo;

    public function __construct(
        string $dsn,
        string $username,
        string $password,
        array $options = []
    ) {
        $this->pdo = new PDO($dsn, $username, $password, $options);
    }

    public function fetch(string $query, array $bindings = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($bindings);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function fetchOne(string $query, array $bindings = []): ?array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($bindings);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function fetchAll(string $query, array $bindings = []): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute(string $query, array $bindings = []): bool
    {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($bindings);
    }

    public function query(string $query, array $params = []): mixed
    {
        $this->pdo->exec($query);
    }
}
?>
