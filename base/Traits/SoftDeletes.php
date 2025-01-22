<?php
namespace Base\Traits;

use Base\ORM\QueryBuilder;

trait SoftDeletes
{
    /**
     * Soft delete the model by setting the deleted_at timestamp.
     *
     * @return bool
     */
    public function delete(): bool
    {
        if (property_exists($this, "adapter") && $this->adapter) {
            $timestamp = date("Y-m-d H:i:s");
            return $this->adapter->save(
                $this->table,
                ["deleted_at" => $timestamp, $this->key => $this->{$this->key}],
                $this->key
            );
        }
        return false;
    }

    /**
     * Restore a soft-deleted model.
     *
     * @return bool
     */
    public function restore(): bool
    {
        if (property_exists($this, "adapter") && $this->adapter) {
            return $this->adapter->save(
                $this->table,
                ["deleted_at" => null, $this->key => $this->{$this->key}],
                $this->key
            );
        }
        return false;
    }

    /**
     * Check if the model is soft deleted.
     *
     * @return bool
     */
    public function trashed(): bool
    {
        return $this->deleted_at !== null;
    }

    /**
     * Modify queries to exclude soft-deleted records.
     *
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    public static function withTrashed(QueryBuilder $query): QueryBuilder
    {
        return $query; // No modifications, include all records
    }

    public static function withoutTrashed(QueryBuilder $query): QueryBuilder
    {
        $instance = new static();
        return $query->where(["deleted_at" => null]);
    }

    public static function onlyTrashed(QueryBuilder $query): QueryBuilder
    {
        $instance = new static();
        return $query->where(["deleted_at IS NOT" => null]);
    }
}
