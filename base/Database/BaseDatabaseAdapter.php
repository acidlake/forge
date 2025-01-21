<?php
namespace Base\Database;

use Base\Interfaces\DatabaseInterface;
use PDO;

abstract class BaseDatabaseAdapter implements DatabaseInterface
{
    protected PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $this->getDsn($config);
        $this->pdo = new PDO($dsn, $config["username"], $config["password"]);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    abstract protected function getDsn(array $config): string;

    public function query(string $sql, array $params = []): mixed
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): mixed
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert(string $table, array $data): int
    {
        $columns = implode(",", array_keys($data));
        $placeholders = implode(
            ",",
            array_map(fn($col) => ":$col", array_keys($data))
        );
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->query($sql, $data);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(
        string $table,
        array $data,
        string $where,
        array $params = []
    ): int {
        $set = implode(
            ",",
            array_map(fn($col) => "$col = :$col", array_keys($data))
        );
        $sql = "UPDATE $table SET $set WHERE $where";
        $stmt = $this->query($sql, array_merge($data, $params));
        return $stmt->rowCount();
    }

    public function delete(
        string $table,
        string $where,
        array $params = []
    ): int {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
}
