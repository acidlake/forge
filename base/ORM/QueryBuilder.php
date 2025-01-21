<?php
namespace Base\ORM;

use Base\ORM\DatabaseAdapter;

class QueryBuilder
{
    private string $table;
    private DatabaseAdapter $adapter;
    private array $conditions = [];

    public function __construct(string $table, DatabaseAdapter $adapter)
    {
        $this->table = $table;
        $this->adapter = $adapter;
    }

    public function where(array $conditions): self
    {
        $this->conditions = array_merge($this->conditions, $conditions);
        return $this;
    }

    public function get(): array
    {
        return $this->adapter->where($this->table, $this->conditions);
    }

    public function first(): ?array
    {
        if (empty($this->conditions)) {
            throw new \InvalidArgumentException(
                "Conditions are required for `first()`."
            );
        }

        $condition = array_key_first($this->conditions);
        $value = $this->conditions[$condition];

        return $this->adapter->find($this->table, $condition, $value);
    }

    public function all(): array
    {
        return $this->adapter->all($this->table);
    }
}
