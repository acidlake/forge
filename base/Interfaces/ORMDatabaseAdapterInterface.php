<?php
namespace Base\Interfaces;

interface ORMDatabaseAdapterInterface
{
    public function find(string $table, string $key, mixed $value): ?array;
    public function where(string $table, array $conditions): array;
    public function save(string $table, array $data, string $key): bool;
    public function delete(string $table, string $field, mixed $value): bool;
    public function all(string $table): array;
    /**
     * Execute a raw SQL query.
     *
     * @param string $sql The raw SQL query.
     * @param array $params Parameters to bind in the query.
     * @return mixed The result of the query execution.
     */
    public function query(string $sql, array $params = []): mixed;
    public function getTables(): array;
}
