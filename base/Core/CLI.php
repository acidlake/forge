<?php

namespace Base\Core;

use Base\Interfaces\CommandInterface;
use ReflectionClass;

/**
 * CLI class for handling and managing commands.
 */
class CLI
{
    private array $commands = [];
    private Container $container;

    /**
     * Constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->loadCommands();
    }

    /**
     * Handle CLI input and execute the appropriate command.
     *
     * @param array $argv
     * @return void
     */
    public function handle(array $argv): void
    {
        $commandName = $argv[1] ?? "help";

        if (isset($this->commands[$commandName])) {
            $this->commands[$commandName]->execute(array_slice($argv, 2));
        } else {
            echo "Command '{$commandName}' not found.\n";
            if (isset($this->commands["help"])) {
                $this->commands["help"]->execute([]);
            }
        }
    }

    /**
     * Get the list of registered commands.
     *
     * @return array<string, CommandInterface> List of registered commands.
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Load commands from core and app directories.
     *
     * @return void
     */
    private function loadCommands(): void
    {
        $this->registerCommands(
            BASE_PATH . "/base/Commands",
            "Base\\Commands\\"
        );
        $this->registerCommands(BASE_PATH . "/app/Commands", "App\\Commands\\");
    }

    /**
     * Register commands by scanning a directory.
     *
     * @param string $directory
     * @param string $namespace
     * @return void
     */
    private function registerCommands(
        string $directory,
        string $namespace
    ): void {
        if (!is_dir($directory)) {
            return;
        }

        foreach (glob("{$directory}/*.php") as $file) {
            $className = "{$namespace}" . basename($file, ".php");

            if (
                class_exists($className) &&
                is_subclass_of($className, CommandInterface::class)
            ) {
                try {
                    $this->registerCommand($className);
                } catch (\Throwable $e) {
                    echo "Failed to register command {$className}: {$e->getMessage()}\n";
                }
            }
        }
    }

    /**
     * Register a single command.
     *
     * @param string $className
     * @return void
     */
    private function registerCommand(string $className): void
    {
        $reflection = new ReflectionClass($className);

        // Handle dependencies
        $constructor = $reflection->getConstructor();
        $command = null;

        if (!$constructor || $constructor->getNumberOfParameters() === 0) {
            $command = new $className(); // No dependencies
        } else {
            $dependencies = array_map(
                fn($param) => $this->container->resolve(
                    $param->getType()->getName()
                ),
                $constructor->getParameters()
            );
            $command = $reflection->newInstanceArgs($dependencies);
        }

        // Register the command
        $this->commands[$command->getName()] = $command;
    }
}
