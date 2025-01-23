<?php

namespace Base\ORM;

use Base\Database\DatabaseAdapterInterface;
use RuntimeException;

class DefaultOrmManager implements OrmManagerInterface
{
    protected string $table;
    protected array $conditions = [];
    protected bool $noSqlMode = false;

    public function __construct(protected DatabaseAdapterInterface $db) {}

    public function setTable(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function find(string|int $id): ?object
    {
        if ($this->noSqlMode) {
            return $this->findBy("_id", $id);
        }

        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $result = $this->db->fetch($query, ["id" => $id]);
        return $result ? (object) $result : null;
    }

    public function findBy(string $field, mixed $value): ?object
    {
        $query = "SELECT * FROM {$this->table} WHERE {$field} = :value LIMIT 1";
        $result = $this->db->fetch($query, ["value" => $value]);
        return $result ? (object) $result : null;
    }

    public function where(array $conditions): self
    {
        $this->conditions = $conditions;
        return $this;
    }

    public function all(): array
    {
        $whereClause = $this->buildWhereClause();
        $query = "SELECT * FROM {$this->table} {$whereClause}";
        return array_map(
            fn($row) => (object) $row,
            $this->db->fetchAll($query)
        );
    }

    public function insert(array $data): object
    {
        $keys = array_keys($data);
        $placeholders = array_map(fn($key) => ":$key", $keys);

        $query =
            "INSERT INTO {$this->table} (" .
            implode(", ", $keys) .
            ") VALUES (" .
            implode(", ", $placeholders) .
            ")";
        $this->db->execute($query, $data);

        // Return the inserted data
        return (object) $data;
    }

    public function update(string|int $id, array $data): bool
    {
        $setClause = implode(
            ", ",
            array_map(fn($key) => "$key = :$key", array_keys($data))
        );
        $data["id"] = $id;

        $query = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        return $this->db->execute($query, $data);
    }

    public function delete(string|int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->db->execute($query, ["id" => $id]);
    }

    public function rawQuery(string $query, array $bindings = []): mixed
    {
        return $this->db->execute($query, $bindings);
    }

    public function enableNoSQLMode(bool $enable): self
    {
        $this->noSqlMode = $enable;
        return $this;
    }

    private function buildWhereClause(): string
    {
        if (empty($this->conditions)) {
            return "";
        }

        $clauses = [];
        foreach ($this->conditions as $key => $value) {
            $clauses[] = "{$key} = :{$key}";
        }

        return "WHERE " . implode(" AND ", $clauses);
    }

    private function generateUuid(): string
    {
        return bin2hex(random_bytes(16));
    }
}
