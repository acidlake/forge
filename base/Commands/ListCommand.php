<?php
namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Core\CLI;

class ListCommand implements CommandInterface
{
    private CLI $cli;

    public function __construct(CLI $cli)
    {
        $this->cli = $cli;
    }

    public function getName(): string
    {
        return "list";
    }

    public function getDescription(): string
    {
        return "Lists all available commands.";
    }

    public function execute(array $arguments = []): void
    {
        echo "Available commands:\n";

        foreach ($this->cli->getCommands() as $name => $command) {
            echo "- {$name}: " . $command->getDescription() . "\n";
        }
    }
}
