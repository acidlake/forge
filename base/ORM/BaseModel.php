<?php
namespace Base\ORM;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Traits\SoftDeletes;
use Base\Traits\Timestamps;

abstract class BaseModel
{
    use SoftDeletes, Timestamps, ContainerAwareTrait;

    protected string $table = ""; // Database table
    protected string $key = "id"; // Primary key column
    protected array $fillable = []; // Columns allowed for mass assignment

    private ORMDatabaseAdapterInterface $adapter;
    private array $attributes = [];

    public function __construct()
    {
        $this->adapter = $this->resolve(ORMDatabaseAdapterInterface::class);

        foreach ($this->fillable as $property) {
            $this->attributes[$property] = null;
        }
    }

    public function __get(string $name): mixed
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        throw new \InvalidArgumentException(
            "Property '{$name}' does not exist on this model."
        );
    }

    public function __set(string $name, mixed $value): void
    {
        if (in_array($name, $this->fillable)) {
            $this->attributes[$name] = $value;
            return;
        }

        throw new \InvalidArgumentException(
            "Cannot set property '{$name}' that is not fillable."
        );
    }

    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public static function find(string|int $id): ?self
    {
        $instance = new static();
        $result = $instance->adapter->find(
            $instance->table,
            $instance->key,
            $id
        );
        return $result ? $instance->fill($result) : null;
    }

    public static function findBy(string $field, mixed $value): ?self
    {
        $instance = new static();
        $result = $instance->adapter->find($instance->table, $field, $value);
        return $result ? $instance->fill($result) : null;
    }

    public static function where(array $conditions): QueryBuilder
    {
        $instance = new static();
        return (new QueryBuilder($instance->table, $instance->adapter))->where(
            $conditions
        );
    }

    public static function all(): array
    {
        $instance = new static();
        $results = $instance->adapter->all($instance->table);
        return array_map(fn($data) => (new static())->fill($data), $results);
    }

    public function save(): bool
    {
        return $this->adapter->save($this->table, $this->toArray(), $this->key);
    }

    public function delete(): bool
    {
        return $this->adapter->delete($this->table, $this->{$this->key});
    }
}
