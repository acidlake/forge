<?php
namespace Base\Traits;

use DateTime;

/**
 * @method static static resolve() Resolve the instance of the model
 */
trait SoftDeletes
{
    protected bool $usesSoftDeletes = true;

    public function delete(string|int $id): bool
    {
        if ($this->usesSoftDeletes) {
            $deletedAt = (new DateTime())->format("Y-m-d H:i:s");
            return $this->orm->update($id, ["deleted_at" => $deletedAt]);
        }
        return $this->orm->delete($id);
    }

    public function forceDelete(string|int $id): bool
    {
        return $this->orm->delete($id);
    }

    public function restore(string|int $id): bool
    {
        if (!$this->usesSoftDeletes) {
            return false;
        }

        return $this->orm->update($id, ["deleted_at" => null]);
    }

    public static function withTrashed(): static
    {
        $instance = static::resolve();
        $instance->orm->enableIncludeTrashed();
        return $instance;
    }

    public static function onlyTrashed(): static
    {
        $instance = static::resolve();
        $instance->orm->enableOnlyTrashed();
        return $instance;
    }
}
