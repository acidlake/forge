<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;

class MigrateCommand implements CommandInterface
{
    public function getName(): string
    {
        return "migrate";
    }

    public function getDescription(): string
    {
        return "Starts the database migrations";
    }

    public function execute(array $arguments = []): void
    {
        echo "Migrate logic";
    }
}
