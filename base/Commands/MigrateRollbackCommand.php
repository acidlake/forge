<?php

namespace Base\Commands;

use Base\Core\MigrationManager;
use Base\Interfaces\CommandInterface;

class MigrateRollbackCommand implements CommandInterface
{
    protected MigrationManager $migrationManager;

    public function __construct(MigrationManager $migrationManager)
    {
        $this->migrationManager = $migrationManager;
    }

    public function getName(): string
    {
        return "migrate:rollback";
    }

    public function getDescription(): string
    {
        return "Rollback the last batch of migrations.";
    }

    public function execute(array $arguments = []): void
    {
        $this->migrationManager->rollback();
    }
}
