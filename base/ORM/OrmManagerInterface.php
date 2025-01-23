<?php

namespace Base\ORM;

interface OrmManagerInterface
{
    public function setTable(string $table): self;

    public function find(string|int $id): ?object;

    public function findBy(string $field, mixed $value): ?object;

    public function where(array $conditions): self;

    public function all(): array;

    public function insert(array $data): object;

    public function update(string|int $id, array $data): bool;

    public function delete(string|int $id): bool;

    public function rawQuery(string $query, array $bindings = []): mixed;

    public function enableNoSQLMode(bool $enable): self;
}
