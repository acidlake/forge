<?php

namespace Base\Interfaces;

/**
 * Interface CommandInterface
 *
 * Defines the contract for CLI commands within the Forge framework.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
interface CommandInterface
{
    /**
     * Get the name of the command.
     *
     * @return string The name of the command.
     */
    public function getName(): string;
    public function getDescription(): string;

    /**
     * Execute the command with the given arguments.
     *
     * @param array $arguments The arguments passed to the command.
     *
     * @return void
     */
    public function execute(array $arguments = []): void;
}
