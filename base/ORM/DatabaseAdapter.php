<?php
namespace Base\ORM;

use Base\Interfaces\ORMDatabaseAdapterInterface;
use PDO;

class DatabaseAdapter implements ORMDatabaseAdapterInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function find(string $table, string $field, mixed $value): ?array
    {
        $query = "SELECT * FROM {$table} WHERE {$field} = :value LIMIT 1";
        $statement = $this->pdo->prepare($query);
        $statement->execute(["value" => $value]);

        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function where(string $table, array $conditions): array
    {
        $whereClause = implode(
            " AND ",
            array_map(fn($key) => "$key = :$key", array_keys($conditions))
        );

        $query = "SELECT * FROM {$table} WHERE {$whereClause}";
        $statement = $this->pdo->prepare($query);
        $statement->execute($conditions);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(string $table, array $data, string $key): bool
    {
        if (isset($data[$key])) {
            // Update if the primary key exists
            $value = $data[$key];
            unset($data[$key]);

            return $this->update($table, $data, $key, $value);
        }

        // Insert if the primary key does not exist
        return $this->insert($table, $data) > 0;
    }

    public function all(string $table): array
    {
        $query = "SELECT * FROM {$table}";
        $statement = $this->pdo->query($query);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $table, array $data): int
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(
            ", ",
            array_map(fn($key) => ":$key", array_keys($data))
        );

        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $statement = $this->pdo->prepare($query);
        $statement->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(
        string $table,
        array $data,
        string $field,
        mixed $value
    ): bool {
        $setClause = implode(
            ", ",
            array_map(fn($key) => "$key = :$key", array_keys($data))
        );

        $query = "UPDATE {$table} SET {$setClause} WHERE {$field} = :whereValue";
        $data["whereValue"] = $value;

        $statement = $this->pdo->prepare($query);
        return $statement->execute($data);
    }

    public function delete(string $table, string $field, mixed $value): bool
    {
        $query = "DELETE FROM {$table} WHERE {$field} = :value";
        $statement = $this->pdo->prepare($query);

        return $statement->execute(["value" => $value]);
    }
}
