<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Core\CLI;

class HelpCommand implements CommandInterface
{
    private CLI $cli;

    public function __construct(CLI $cli)
    {
        $this->cli = $cli;
    }

    public function getName(): string
    {
        return "help";
    }

    public function getDescription(): string
    {
        return "Displays a list of available commands.";
    }

    public function execute(array $arguments = []): void
    {
        echo "Available commands:\n";

        foreach ($this->cli->getCommands() as $name => $command) {
            echo "- {$name}: " . $command->getDescription() . "\n";
        }

        echo "\nUse `php forge <command>` to execute a command.\n";
    }
}
