<?php
namespace Base\Commands;

use Base\Core\ContainerHelper;
use Base\Interfaces\CommandInterface;
use Base\Core\MigrationManager;

class ValidateMigrationsCommand implements CommandInterface
{
    public function getName(): string
    {
        return "migrations:validate";
    }

    public function getDescription(): string
    {
        return "Validate all migrations for syntax and structural errors.";
    }

    public function execute(array $args = []): void
    {
        $migrationManager = new MigrationManager(
            ContainerHelper::getContainer()->resolve("DatabaseAdapter"),
            ContainerHelper::getContainer()->resolve("SchemaBuilder")
        );

        $migrations = $migrationManager->getPendingMigrations();

        foreach ($migrations as $migration) {
            echo "Validating migration: {$migration["name"]}...\n";

            try {
                $instance = $migrationManager->instantiateMigration(
                    $migration["class"]
                );
                echo "Migration {$migration["name"]} is valid.\n";
            } catch (\Throwable $e) {
                echo "Error in migration {$migration["name"]}: {$e->getMessage()}\n";
            }
        }
    }
}
