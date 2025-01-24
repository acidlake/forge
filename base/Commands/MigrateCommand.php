<?php

namespace Base\Commands;

use Base\Core\MigrationManager;
use Base\Interfaces\CommandInterface;

class MigrateCommand implements CommandInterface
{
    protected MigrationManager $migrationManager;

    public function __construct(MigrationManager $migrationManager)
    {
        $this->migrationManager = $migrationManager;
    }

    public function getName(): string
    {
        return "migrate";
    }

    public function getDescription(): string
    {
        return "Run all pending migrations.";
    }

    public function execute(array $arguments = []): void
    {
        $this->migrationManager->run();
    }
}
