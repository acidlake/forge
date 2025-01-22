<?php
namespace Base\Interfaces;

/**
 * Interface for database seeders.
 */
interface SeederInterface
{
    /**
     * Seed the database with initial data.
     */
    public function run(): void;
}
