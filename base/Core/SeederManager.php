<?php

namespace Base\Core;

use Base\Interfaces\SeederInterface;
use Base\Database\DatabaseAdapterInterface;
use Exception;

class SeederManager
{
    protected DatabaseAdapterInterface $db;
    protected array $seeders = [];

    public function __construct(DatabaseAdapterInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Register a seeder.
     *
     * @param string $seederClass The fully qualified class name of the seeder.
     */
    public function register(string $seederClass): void
    {
        if (!class_exists($seederClass)) {
            throw new Exception("Seeder class {$seederClass} not found.");
        }

        $this->seeders[] = $seederClass;
    }

    /**
     * Run all registered seeders.
     */
    public function run(): void
    {
        foreach ($this->seeders as $seederClass) {
            /** @var SeederInterface $seeder */
            $seeder = new $seederClass($this->db);
            echo "Running seeder: {$seederClass}\n";
            $seeder->run();
        }
    }

    /**
     * Rollback all seeders.
     */
    public function rollback(): void
    {
        foreach (array_reverse($this->seeders) as $seederClass) {
            /** @var SeederInterface $seeder */
            $seeder = new $seederClass($this->db);
            if (method_exists($seeder, "rollback")) {
                echo "Rolling back seeder: {$seederClass}\n";
                $seeder->rollback();
            }
        }
    }
}
