<?php
namespace Base\Interfaces;

interface DatabaseInterface
{
    public function query(string $sql, array $params = []): mixed;
    public function fetch(string $sql, array $params = []): mixed;
    public function fetchAll(string $sql, array $params = []): array;
    public function insert(string $table, array $data): int;
    public function update(
        string $table,
        array $data,
        string $where,
        array $params = []
    ): int;
    public function delete(
        string $table,
        string $where,
        array $params = []
    ): int;
}
