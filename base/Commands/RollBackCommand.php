<?php

namespace Base\Commands;

use Base\Database\MigrationManager;
use Base\Interfaces\CommandInterface;

class RollbackCommand implements CommandInterface
{
    public function getName(): string
    {
        return "rollback";
    }

    public function getDescription(): string
    {
        return "Rollback the last batch of migrations.";
    }

    public function execute(array $arguments = []): void
    {
        $migrationManager = new MigrationManager();
        $migrationManager->rollback();
        echo "Rollback completed successfully.\n";
    }
}
