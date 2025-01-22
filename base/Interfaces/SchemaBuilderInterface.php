<?php
namespace Base\Interfaces;

/**
 * SchemaBuilderInterface defines the contract for schema builders in the Forge framework.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 */
interface SchemaBuilderInterface
{
    public function create(string $table, callable $blueprint): void;
    public function drop(string $table): void;
    public function dropIfExists(string $table): void;
    public function table(string $table, callable $blueprint): void;
}
