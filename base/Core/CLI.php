<?php
namespace Base\Core;

use Base\Interfaces\CommandInterface;

class CLI
{
    private array $commands = [];
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function registerCommand(CommandInterface $command): void
    {
        $name = $command->getName();
        $this->commands[$name] = $command;
    }

    public function handle(array $argv): void
    {
        $commandName = $argv[1] ?? "help";

        if (array_key_exists($commandName, $this->commands)) {
            $this->commands[$commandName]->execute(array_slice($argv, 2));
        } else {
            echo "Command '{$commandName}' not found.\n";
            if (array_key_exists("help", $this->commands)) {
                $this->commands["help"]->execute([]);
            }
        }
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
}
