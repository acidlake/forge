<?php
namespace Base\ORM;

use Base\Core\ContainerHelper;
use Base\ORM\BaseModelInterface;
use Base\ORM\OrmManagerInterface;
use Base\Tools\UuidManager;

abstract class BaseModel implements BaseModelInterface
{
    protected string $table;
    protected bool $uuid = false;
    protected string $keyStrategy = "uuidv4";
    protected array $fillable = [];

    public function __construct(
        protected OrmManagerInterface $orm,
        protected UuidManager $uuidManager
    ) {
        if (isset($this->table)) {
            $this->orm->setTable($this->table);
        }
    }

    /**
     * Resolve an instance of the model using the DI container.
     *
     * @return static
     */
    public static function resolve(): static
    {
        $container = ContainerHelper::getContainer();
        return $container->resolve(static::class);
    }

    /**
     * Static proxy for `all`.
     *
     * @return array
     */
    public static function all(): array
    {
        return static::resolve()->_all();
    }

    /**
     * Retrieve all records.
     *
     * @return array
     */
    protected function _all(): array
    {
        return $this->orm->all();
    }

    /**
     * Static proxy for `find`.
     *
     * @param string|int $id
     * @return object|null
     */
    public static function find(string|int $id): ?object
    {
        return static::resolve()->_find($id);
    }

    /**
     * Find a record by ID.
     *
     * @param string|int $id
     * @return object|null
     */
    protected function _find(string|int $id): ?object
    {
        return $this->orm->find($id);
    }

    /**
     * Static proxy for `findBy`.
     *
     * @param string $field
     * @param mixed $value
     * @return object|null
     */
    public static function findBy(string $field, mixed $value): ?object
    {
        return static::resolve()->_findBy($field, $value);
    }

    /**
     * Find a record by a specific field.
     *
     * @param string $field
     * @param mixed $value
     * @return object|null
     */
    protected function _findBy(string $field, mixed $value): ?object
    {
        return $this->orm->findBy($field, $value);
    }

    // Other methods (find, findBy, all)...

    /**
     * Static proxy for `where`.
     *
     * @param array $conditions
     * @return static
     */
    public static function where(array $conditions): static
    {
        return static::resolve()->_where($conditions);
    }

    /**
     * Apply conditions to a query.
     *
     * @param array $conditions
     * @return static
     */
    protected function _where(array $conditions): static
    {
        $this->orm->where($conditions);
        return $this;
    }

    public function setTable(string $table): self
    {
        $this->table = $table;
        $this->orm->setTable($table);
        return $this;
    }

    public function save(array $data): object
    {
        $data = $this->sanitizeInput($data);

        if ($this->uuid && !isset($data["id"])) {
            $data["id"] = $this->uuidManager->generate($this->keyStrategy);
        }

        if (isset($data["id"])) {
            return $this->orm->update($data["id"], $data)
                ? (object) $data
                : null;
        }

        return $this->orm->insert($data);
    }

    public function delete(string|int $id): bool
    {
        return $this->orm->delete($id);
    }

    public function enableUuid(bool $enable): self
    {
        $this->uuid = $enable;
        return $this;
    }

    public function rawQuery(string $query, array $bindings = []): mixed
    {
        return $this->orm->rawQuery($query, $bindings);
    }

    private function sanitizeInput(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_filter(
            $data,
            fn($key) => in_array($key, $this->fillable),
            ARRAY_FILTER_USE_KEY
        );
    }
}
