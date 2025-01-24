<?php
namespace Base\Database;

use Base\Core\Blueprint;
use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Interfaces\SchemaBuilderInterface;

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

    public function create(string $table, callable $blueprint): void
    {
        $schema = new Blueprint($table);
        $blueprint($schema);

        $sql = $schema->toSql();
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

    public function table(string $table, callable $blueprint): void
    {
        $schema = new Blueprint($table, true);
        $blueprint($schema);

        $sql = $schema->toSql();
        $this->adapter->query($sql);
    }

    /**
     * Drop all tables in the database.
     *
     * @return void
     */
    public function dropAllTables(): void
    {
        $tables = $this->adapter->getTables();

        foreach ($tables as $table) {
            $this->adapter->query("DROP TABLE IF EXISTS {$table} CASCADE");
            echo "Dropped table: {$table}\n";
        }
    }
}
