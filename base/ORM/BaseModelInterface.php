<?php

namespace Base\ORM;

interface BaseModelInterface
{
    public function setTable(string $table): self;

    public static function find(string|int $id): ?object;

    public static function findBy(string $field, mixed $value): ?object;

    public static function where(array $conditions): self;

    public static function all(): array;

    public function save(array $data): object;

    public function delete(string|int $id): bool;

    public function enableUuid(bool $enable): self;

    public function rawQuery(string $query, array $bindings = []): mixed;
}
