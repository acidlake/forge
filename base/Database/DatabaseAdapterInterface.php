<?php

namespace Base\Database;

interface DatabaseAdapterInterface
{
    public function fetch(string $query, array $bindings = []): array;

    public function fetchAll(string $query, array $bindings = []): array;

    public function execute(string $query, array $bindings = []): bool;
    public function fetchOne(string $query, array $bindings = []): ?array;
}
?>
