<?php

namespace Base\Core;

use Base\Interfaces\BlueprintInterface;
use Base\Core\Blueprint;
use Base\Database\DatabaseAdapterInterface;

class MigrationBuilder
{
    protected static DatabaseAdapterInterface $db;

    public static function init(DatabaseAdapterInterface $adapter): void
    {
        self::$db = $adapter;
    }

    /**
     * Create a new table using the Blueprint syntax.
     *
     * @param string   $tableName
     * @param callable $callback
     */
    public static function create(string $tableName, callable $callback): void
    {
        $blueprint = new Blueprint();

        // Let the user define the schema using the callback
        $callback($blueprint);

        // Generate SQL
        $sql = "CREATE TABLE `{$tableName}` (" . $blueprint->build() . ")";

        // Execute the SQL
        self::$db->execute($sql);
    }

    /**
     * Drop a table if it exists.
     *
     * @param string $tableName
     */
    public static function dropIfExists(string $tableName): void
    {
        $sql = "DROP TABLE IF EXISTS `{$tableName}`";
        self::$db->execute($sql);
    }

    /**
     * Add a column to an existing table.
     *
     * @param string   $tableName
     * @param callable $callback
     */
    public static function table(string $tableName, callable $callback): void
    {
        $blueprint = new Blueprint();

        // Let the user define the new columns
        $callback($blueprint);

        // Generate SQL for ALTER TABLE
        $sql = "ALTER TABLE `{$tableName}` ADD " . $blueprint->build();

        // Execute the SQL
        self::$db->execute($sql);
    }
}
