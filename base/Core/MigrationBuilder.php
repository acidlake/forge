<?php

namespace Base\Core;

use Base\Core\Blueprint;
use Base\Database\DatabaseAdapterInterface;

class MigrationBuilder
{
    /**
     * The database adapter used to execute SQL queries.
     *
     * @var DatabaseAdapterInterface|null
     */
    protected static ?DatabaseAdapterInterface $db = null;

    /**
     * Initialize the migration builder with a database adapter.
     *
     * @param DatabaseAdapterInterface $adapter The database adapter instance.
     */
    public static function init(DatabaseAdapterInterface $adapter): void
    {
        self::$db = $adapter;
    }

    /**
     * Ensure the database adapter is initialized before using it.
     *
     * @throws \RuntimeException If the database adapter is not initialized.
     */
    protected static function ensureInitialized(): void
    {
        if (self::$db === null) {
            throw new \RuntimeException(
                "MigrationBuilder is not initialized. Call MigrationBuilder::init() with a valid DatabaseAdapterInterface."
            );
        }
    }

    /**
     * Create a new table using the Blueprint syntax.
     *
     * Example:
     * ```php
     * MigrationBuilder::create('users', function (Blueprint $table) {
     *     $table->uuidPrimary(); // Add a primary key using UUID
     *     $table->string('name');
     *     $table->timestamps();
     * });
     * ```
     *
     * @param string   $tableName The name of the table to create.
     * @param callable $callback  A callback that receives a `Blueprint` instance to define the table schema.
     */
    public static function create(string $tableName, callable $callback): void
    {
        self::ensureInitialized();

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
     * Example:
     * ```php
     * MigrationBuilder::dropIfExists('users');
     * ```
     *
     * @param string $tableName The name of the table to drop.
     */
    public static function dropIfExists(string $tableName): void
    {
        self::ensureInitialized();

        $sql = "DROP TABLE IF EXISTS `{$tableName}`";
        self::$db->execute($sql);
    }

    /**
     * Modify an existing table by adding columns.
     *
     * Example:
     * ```php
     * MigrationBuilder::table('users', function (Blueprint $table) {
     *     $table->string('address')->nullable();
     * });
     * ```
     *
     * @param string   $tableName The name of the table to modify.
     * @param callable $callback  A callback that receives a `Blueprint` instance to define the new columns.
     */
    public static function table(string $tableName, callable $callback): void
    {
        self::ensureInitialized();

        $blueprint = new Blueprint();

        // Let the user define the new columns
        $callback($blueprint);

        // Generate SQL for ALTER TABLE
        $sql = "ALTER TABLE `{$tableName}` ADD " . $blueprint->build();

        // Execute the SQL
        self::$db->execute($sql);
    }
}
