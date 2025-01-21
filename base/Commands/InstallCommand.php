<?php

namespace Base\Commands;

use Base\Commands\CommandInterface;

class InstallCommand implements CommandInterface
{
    public function getName(): string
    {
        return "install";
    }

    public function handle(array $args): void
    {
        echo "Setting up the framework..." . PHP_EOL;
        // Perform setup tasks (e.g., permissions, dependencies)
        echo "Framework installed successfully!" . PHP_EOL;
    }
}
