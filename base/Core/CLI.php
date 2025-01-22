<?php

namespace Base\Core;

use Base\Commands\GenerateOTPCommand;
use Base\Commands\ListCommand;
use Base\Interfaces\CommandInterface;
use Base\Commands\HelpCommand;
use Base\Interfaces\OTPManagerInterface;

/**
 * CLI class for handling and managing commands.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
class CLI
{
    /**
     * List of registered commands.
     *
     * @var array<string, CommandInterface>
     */
    private array $commands = [];

    /**
     * CLI constructor.
     * Initializes core and user commands.
     */
    public function __construct()
    {
        $this->loadCoreCommands();
        $this->loadUserCommands();

        $this->commands["help"] = new HelpCommand($this);
        $this->commands["list"] = new ListCommand($this);
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
     * Handles the execution of a command based on provided arguments.
     *
     * @param array $argv Array of arguments passed to the CLI.
     *
     * @return void
     */
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

    /**
     * Loads the core commands from the base command directory.
     *
     * @return void
     */
    private function loadCoreCommands(): void
    {
        $this->loadCommands(BASE_PATH . "/base/Commands", "Base\\Commands\\");
    }

    /**
     * Loads the user-defined commands from the application command directory.
     *
     * @return void
     */
    private function loadUserCommands(): void
    {
        $this->loadCommands(BASE_PATH . "/app/Commands", "App\\Commands\\");
    }

    /**
     * Loads commands from a specified directory and namespace.
     *
     * @param string $directory Path to the directory containing command files.
     * @param string $namespacePrefix Namespace prefix for the commands.
     *
     * @return void
     */
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

    /**
     * Registers a command by its class name.
     *
     * @param string $className Fully qualified class name of the command.
     *
     * @return void
     */
    private function registerCommand(string $className): void
    {
        $reflection = new \ReflectionClass($className);

        if (
            $reflection->getConstructor() &&
            $reflection->getConstructor()->getNumberOfParameters() > 0
        ) {
            $command = $reflection->newInstance($this);
        } else {
            $command = new $className();
        }

        $this->commands[$command->getName()] = $command;
    }

    /**
     * Derives the fully qualified class name from a file path.
     *
     * @param string $filePath Path to the file.
     * @param string $namespacePrefix Namespace prefix for the commands.
     *
     * @return string Fully qualified class name.
     */
    private function getClassNameFromFile(
        string $filePath,
        string $namespacePrefix
    ): string {
        $relativePath = str_replace([BASE_PATH . "/", ".php"], "", $filePath);
        $className = str_replace("/", "\\", $relativePath);

        return $namespacePrefix . substr($className, strlen($namespacePrefix));
    }

    /**
     * Outputs a message to the console.
     *
     * @param string $message The message to output.
     *
     * @return void
     */
    private function output(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
