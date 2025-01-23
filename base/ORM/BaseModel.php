<?php

namespace Base\ORM;

use Base\Core\ContainerAwareTrait;
use Base\Core\ContainerHelper;
use Base\ORM\BaseModelInterface;
use Base\ORM\OrmManagerInterface;
use Base\Tools\UuidManager;

/**
 * BaseModel
 *
 * Abstract class providing ORM features for models.
 * Models extending this class gain functionality for interacting with the database.
 */
abstract class BaseModel implements BaseModelInterface
{
    use ContainerAwareTrait;

    /**
     * @var string $table The table name associated with the model.
     */
    protected string $table;

    /**
     * @var bool $uuid Whether UUIDs are enabled for this model.
     */
    protected bool $uuid = false;

    /**
     * @var string $keyStrategy The strategy for generating UUIDs.
     */
    protected string $keyStrategy = "uuidv4";

    /**
     * @var array $fillable Fields that can be mass assigned.
     */
    protected array $fillable = [];

    /**
     * @var ?bool $runtimeUuid Runtime override for enabling/disabling UUIDs.
     */
    private ?bool $runtimeUuid = null;

    protected OrmManagerInterface $orm;
    protected UuidManager $uuidManager;

    /**
     * Constructor initializes dependencies and sets the table name in the ORM.
     */
    public function __construct()
    {
        $container = $this->getContainer();
        $this->orm = $container->resolve(OrmManagerInterface::class);
        $this->uuidManager = $container->resolve(UuidManager::class);

        if (isset($this->table)) {
            $this->orm->setTable($this->table);
        }

        $this->orm->setModel($this);
    }

    /**
     * Get the container instance.
     *
     * @return \Base\Core\Container
     */
    protected function getContainer(): \Base\Core\Container
    {
        return ContainerHelper::getContainer();
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
     * Retrieve all records from the database.
     *
     * @return array
     */
    public static function all(): array
    {
        return static::resolve()->_all();
    }

    /**
     * Retrieve all records (non-static implementation).
     *
     * @return array
     */
    protected function _all(): array
    {
        return $this->orm->all();
    }

    public static function paginate(
        int $perPage = 10,
        int $currentPage = 1
    ): array {
        return static::resolve()->_paginate($perPage, $currentPage);
    }

    protected function _paginate(int $perPage, int $currentPage): array
    {
        return $this->orm->paginate($perPage, $currentPage);
    }

    /**
     * Find a record by its ID.
     *
     * @param string|int $id
     * @return object|null
     */
    public static function find(string|int $id): ?object
    {
        return static::resolve()->_find($id);
    }

    /**
     * Find a record by its ID (non-static implementation).
     *
     * @param string|int $id
     * @return object|null
     */
    protected function _find(string|int $id): ?object
    {
        return $this->orm->find($id);
    }

    /**
     * Find a record by a specific field.
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
     * Find a record by a specific field (non-static implementation).
     *
     * @param string $field
     * @param mixed $value
     * @return object|null
     */
    protected function _findBy(string $field, mixed $value): ?object
    {
        return $this->orm->findBy($field, $value);
    }

    /**
     * Apply conditions to a query.
     *
     * @param array $conditions
     * @return static
     */
    public static function where(array $conditions): static
    {
        return static::resolve()->_where($conditions);
    }

    /**
     * Apply conditions to a query (non-static implementation).
     *
     * @param array $conditions
     * @return static
     */
    protected function _where(array $conditions): static
    {
        $this->orm->where($conditions);
        return $this;
    }

    /**
     * Set the table name for the model.
     *
     * @param string $table
     * @return $this
     */
    public function setTable(string $table): self
    {
        $this->table = $table;
        $this->orm->setTable($table);
        return $this;
    }

    /**
     * Save the given data to the database.
     *
     * @param array $data
     * @return object
     */
    public function save(array $data): object
    {
        $data = $this->sanitizeInput($data);

        if ($this->isUuidEnabled() && !isset($data["id"])) {
            $data["id"] = $this->uuidManager->generate($this->keyStrategy);
        }

        if (isset($data["id"])) {
            $existingRecord = $this->orm->find($data["id"]);
            if ($existingRecord) {
                return $this->orm->update($data["id"], $data)
                    ? (object) $data
                    : null;
            }
        }

        return $this->orm->insert($data);
    }

    /**
     * Delete a record by its ID.
     *
     * @param string|int $id
     * @return bool
     */
    public function delete(string|int $id): bool
    {
        return $this->orm->delete($id);
    }

    /**
     * Enable or disable UUID generation for the model.
     *
     * @param bool $enable
     * @return $this
     */
    public function enableUuid(bool $enable): self
    {
        $this->runtimeUuid = $enable;
        return $this;
    }

    /**
     * Check if UUID generation is enabled for the model.
     *
     * @return bool
     */
    protected function isUuidEnabled(): bool
    {
        return $this->runtimeUuid !== null ? $this->runtimeUuid : $this->uuid;
    }

    /**
     * Execute a raw query.
     *
     * @param string $query
     * @param array $bindings
     * @return mixed
     */
    public function rawQuery(string $query, array $bindings = []): mixed
    {
        return $this->orm->rawQuery($query, $bindings);
    }

    /**
     * Sanitize input data based on the fillable fields.
     *
     * @param array $data
     * @return array
     */
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

    /**
     * Determine if the model uses soft deletes.
     *
     * @return bool
     */
    public function usesSoftDeletes(): bool
    {
        return property_exists($this, "usesSoftDeletes") &&
            $this->usesSoftDeletes;
    }
}
