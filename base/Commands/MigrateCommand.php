<?php
namespace Base\Commands;

use Base\Database\MigrationManager;
use Base\Interfaces\CommandInterface;

class MigrateCommand implements CommandInterface
{
    public function getName(): string
    {
        return "migrate";
    }

    public function getDescription(): string
    {
        return "Run database migrations to create or update tables.";
    }

    public function execute(array $arguments = []): void
    {
        $migrationManager = new MigrationManager();
        $migrationManager->migrate();
        echo "Migrations completed successfully.\n";
    }
}
