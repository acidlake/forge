<?php

namespace App\Commands;

use Base\Interfaces\CommandInterface;

/**
 * HelloCommand is an example application command.
 *
 * This command demonstrates how to create and execute a simple CLI command
 * within the Forge framework's application layer.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @application Hello Command Example
 * @copyright 2025
 */
class HelloCommand implements CommandInterface
{
    /**
     * Get the name of the command.
     *
     * The name is used to identify and execute the command in the CLI.
     *
     * @return string The name of the command.
     */
    public function getName(): string
    {
        return "hello";
    }

    /**
     * Get the description of the command.
     *
     * The description provides a brief overview of what the command does.
     *
     * @return string The description of the command.
     */
    public function getDescription(): string
    {
        return "Example application command";
    }

    /**
     * Execute the command.
     *
     * Prints a greeting message. If an argument is provided, it will use that
     * as the name in the greeting. Otherwise, it defaults to "World."
     *
     * @param array $arguments Command-line arguments passed to the command.
     *
     * @return void
     */
    public function execute(array $arguments = []): void
    {
        $name = $arguments[0] ?? "World";
        echo "Hello, {$name}! \n";
    }
}
