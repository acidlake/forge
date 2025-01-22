<?php

namespace Base\Commands;

use Base\Database\BaseSchemaBuilder;
use Base\Database\MigrationManager;
use Base\Database\SeederManager;
use Base\Interfaces\CommandInterface;

class ResetCommand implements CommandInterface
{
    public function getName(): string
    {
        return "reset";
    }

    public function getDescription(): string
    {
        return "Drops all database tables, runs all migrations, and optionally seeds the database.";
    }

    public function execute(array $arguments = []): void
    {
        echo "Resetting the database...\n";

        // Initialize SchemaBuilder
        $schema = new BaseSchemaBuilder();

        // Drop all tables
        echo "Dropping all tables...\n";
        $schema->dropAllTables();
        echo "All tables dropped successfully.\n";

        // Run migrations
        echo "Running migrations...\n";
        $migrationManager = new MigrationManager();
        $migrationManager->migrate();
        echo "Migrations completed successfully.\n";

        // Run seeders if requested
        if (in_array("--seed", $arguments)) {
            echo "Running seeders...\n";
            $seederManager = new SeederManager();
            $seederManager->run();
            echo "Seeders executed successfully.\n";
        }

        echo "Database reset completed successfully.\n";
    }
}
