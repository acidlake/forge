<?php

namespace Base\Core;

use Base\Commands\ListCommand;
use Base\Interfaces\CommandInterface;
use Base\Commands\HelpCommand;

class CLI
{
    private array $commands = [];

    public function __construct()
    {
        $this->loadCoreCommands();
        $this->loadUserCommands();

        $this->commands["help"] = new HelpCommand($this);
        $this->commands["list"] = new ListCommand($this);
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function handle(array $argv): void
    {
        $commandName = $argv[1] ?? "help";

        if (isset($this->commands[$commandName])) {
            $this->commands[$commandName]->execute(array_slice($argv, 2));
        } else {
            echo "Command '{$commandName}' not found.\n";
            $this->commands["help"]->execute([]);
        }
    }

    private function loadCoreCommands(): void
    {
        $this->loadCommands(BASE_PATH . "/base/Commands", "Base\\Commands\\");
    }

    private function loadUserCommands(): void
    {
        $this->loadCommands(BASE_PATH . "/app/Commands", "App\\Commands\\");
    }

    private function loadCommands(
        string $directory,
        string $namespacePrefix
    ): void {
        if (is_dir($directory)) {
            $files = glob("{$directory}/*.php");

            foreach ($files as $file) {
                $className = $this->getClassNameFromFile(
                    $file,
                    $namespacePrefix
                );

                if (
                    class_exists($className) &&
                    is_subclass_of($className, CommandInterface::class)
                ) {
                    $this->registerCommand($className);
                } else {
                    echo "Class not found or invalid: {$className}\n";
                }
            }
        }
    }

    private function registerCommand(string $className): void
    {
        // Use Reflection to determine if the constructor has parameters
        $reflection = new \ReflectionClass($className);

        if (
            $reflection->getConstructor() &&
            $reflection->getConstructor()->getNumberOfParameters() > 0
        ) {
            // Pass $this (CLI instance) for commands requiring it
            $command = $reflection->newInstance($this);
        } else {
            // Instantiate commands without arguments
            $command = new $className();
        }

        $this->commands[$command->getName()] = $command;
    }

    private function getClassNameFromFile(
        string $filePath,
        string $namespacePrefix
    ): string {
        // Remove BASE_PATH and file extension
        $relativePath = str_replace([BASE_PATH . "/", ".php"], "", $filePath);

        // Convert directory separators to namespace separators
        $className = str_replace("/", "\\", $relativePath);

        // Ensure it starts with the correct namespace prefix
        return $namespacePrefix . substr($className, strlen($namespacePrefix));
    }

    private function output(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
