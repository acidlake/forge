<?php
namespace Base\Database;

use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Interfaces\SchemaBuilderInterface;
use Base\Database\SchemaBlueprint;

/**
 * BaseSchemaBuilder provides the default implementation for schema building in the Forge framework.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 */
class BaseSchemaBuilder implements SchemaBuilderInterface
{
    private ORMDatabaseAdapterInterface $adapter;

    public function __construct(ORMDatabaseAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function create(string $table, callable $callback): void
    {
        $blueprint = new SchemaBlueprint($table);
        $callback($blueprint);

        $sql = $blueprint->toSql();
        $this->adapter->query($sql);
    }

    public function drop(string $table): void
    {
        $sql = "DROP TABLE {$table}";
        $this->adapter->query($sql);
    }

    public function dropIfExists(string $table): void
    {
        $sql = "DROP TABLE IF EXISTS {$table}";
        $this->adapter->query($sql);
    }

    public function table(string $table, callable $callback): void
    {
        $blueprint = new SchemaBlueprint($table, true);
        $callback($blueprint);

        $sql = $blueprint->toSql();
        $this->adapter->query($sql);
    }
}
