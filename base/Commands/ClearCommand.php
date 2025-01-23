<?php

namespace Base\Commands;

use Base\Database\BaseSchemaBuilder;
use Base\Interfaces\CommandInterface;

class ClearCommand implements CommandInterface
{
    public function __construct() {}
    public function getName(): string
    {
        return "clear";
    }

    public function getDescription(): string
    {
        return "Drops all database tables.";
    }

    public function execute(array $arguments = []): void
    {
        echo "Clearing the database...\n";

        // Initialize SchemaBuilder
        $schema = new BaseSchemaBuilder();

        // Drop all tables
        $schema->dropAllTables();
        echo "All tables dropped successfully.\n";

        echo "Database clear operation completed.\n";
    }
}
