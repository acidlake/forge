<?php

namespace Base\Core;

use Base\Interfaces\SeederInterface;
use Base\Database\DatabaseAdapterInterface;
use Base\Tools\StructurePathResolver;
use Exception;

class SeederManager
{
    protected DatabaseAdapterInterface $db;

    public function __construct(DatabaseAdapterInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Run a specific seeder or the default `DatabaseSeeder`.
     *
     * @param string $seederClass The fully qualified class name of the seeder.
     */
    public function run(string $seederClass): void
    {
        // Resolve the namespace of the seeder
        $seederClass = $this->resolveSeederClass($seederClass);

        if (!class_exists($seederClass)) {
            throw new Exception("Seeder class {$seederClass} not found.");
        }

        /** @var SeederInterface $seeder */
        $seeder = new $seederClass($this->db);
        $seeder->run();
    }

    /**
     * Rollback a specific seeder or the default `DatabaseSeeder`.
     *
     * @param string $seederClass The fully qualified class name of the seeder.
     */
    public function rollback(string $seederClass): void
    {
        // Resolve the namespace of the seeder
        $seederClass = $this->resolveSeederClass($seederClass);

        if (!class_exists($seederClass)) {
            throw new Exception("Seeder class {$seederClass} not found.");
        }

        /** @var SeederInterface $seeder */
        $seeder = new $seederClass($this->db);
        if (method_exists($seeder, "rollback")) {
            $seeder->rollback();
        }
    }

    /**
     * Resolve the fully qualified class name of a seeder based on structure type.
     *
     * @param string $seederClass
     * @return string
     */
    private function resolveSeederClass(string $seederClass): string
    {
        if (class_exists($seederClass)) {
            return $seederClass;
        }

        $seederPath = StructurePathResolver::resolvePath("seeders");
        return StructurePathResolver::resolveNamespace($seederPath) .
            "\\{$seederClass}";
    }
}
