<?php
namespace Base\Interfaces;

interface ORMDatabaseAdapterInterface
{
    public function find(string $table, string $key, mixed $value): ?array;
    public function where(string $table, array $conditions): array;
    public function save(string $table, array $data, string $key): bool;
    public function delete(string $table, string $field, mixed $value): bool;
    public function all(string $table): array;
}
