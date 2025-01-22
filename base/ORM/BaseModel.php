<?php
namespace Base\ORM;

use Base\Core\ContainerAwareTrait;
use Base\Core\ContainerHelper;
use Base\Interfaces\KeyGeneratorInterface;
use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Traits\SoftDeletes;
use Base\Traits\Timestamps;

abstract class BaseModel
{
    use SoftDeletes, Timestamps, ContainerAwareTrait;

    protected string $table = ""; // Database table
    protected string $key = "id"; // Primary key column
    protected array $fillable = []; // Columns allowed for mass assignment
    protected array $schema = [];
    protected string $keyStrategy = "id"; // Default strategy
    protected int $keyLength = 36;

    private ORMDatabaseAdapterInterface $adapter;
    private KeyGeneratorInterface $keyGenerator;
    private array $attributes = [];

    public function __construct()
    {
        $this->adapter = $this->resolve(ORMDatabaseAdapterInterface::class);
        $this->keyGenerator = $this->resolve(KeyGeneratorInterface::class);

        foreach ($this->fillable as $property) {
            $this->attributes[$property] = null;
        }
    }

    /**
     * Factory method to create a new model instance with defaults.
     */
    public static function new(array $attributes = []): self
    {
        return new static($attributes);
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
        if (empty($this->{$this->key})) {
            $this->{$this->key} = $this->generatePrimaryKey();
        }

        return $this->adapter->save($this->table, $this->toArray(), $this->key);
    }

    /**
     * Insert multiple records into the database.
     */
    public static function insert(array $records): void
    {
        $db = (new static())->getDatabaseAdapter();
        $db->save((new static())->getTableName(), $records);
    }

    /**
     * Get the table name for the model.
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * Fill attributes with defaults and validate against schema.
     */
    protected function validateAndFillDefaults(array $attributes): array
    {
        $filled = [];

        foreach ($this->schema as $field => $rules) {
            if (array_key_exists($field, $attributes)) {
                $filled[$field] = $attributes[$field];
            } elseif (isset($rules["default"])) {
                $filled[$field] = $this->resolveDefault($rules["default"]);
            } elseif (!empty($rules["required"])) {
                throw new \InvalidArgumentException(
                    "Field '{$field}' is required."
                );
            }
        }

        return $filled;
    }

    /**
     * Resolve default values for a field.
     */
    protected function resolveDefault(mixed $default): mixed
    {
        if (is_callable($default)) {
            return call_user_func($default);
        }
        return $default;
    }

    public function delete(): bool
    {
        return $this->adapter->delete($this->table, $this->{$this->key});
    }

    public function generatePrimaryKey(): string
    {
        return $this->keyGenerator->generate(
            $this->keyStrategy,
            $this->keyLength,
            $this->keyFields ?? []
        );
    }

    /**
     * Get the database adapter from the container.
     */
    protected function getDatabaseAdapter(): ORMDatabaseAdapterInterface
    {
        return ContainerHelper::getContainer()->resolve(
            ORMDatabaseAdapterInterface::class
        );
    }
}
