<?php

namespace Base\Commands;

use Base\Core\ContainerAwareTrait;
use Base\Core\ContainerHelper;
use Base\Database\BaseSchemaBuilder;
use Base\Interfaces\CommandInterface;
use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Interfaces\SchemaBuilderInterface;

class RollBackCommand implements CommandInterface
{
    use ContainerAwareTrait;

    public function getName(): string
    {
        return "migrate:rollback";
    }

    public function getDescription(): string
    {
        return "Rollback the last migration batch..";
    }

    public function execute(array $arguments = []): void
    {
        echo "Rolling back the last migration batch...\n";

        // Resolve the database adapter
        $adapter = $this->resolve(ORMDatabaseAdapterInterface::class);

        // Load framework migrations
        $frameworkMigrations = $this->loadMigrations(
            BASE_PATH . "/base/Database/Migrations"
        );

        // Load application migrations
        $appMigrations = $this->loadMigrations(
            BASE_PATH . "/app/Database/Migrations"
        );

        $allMigrations = array_merge($frameworkMigrations, $appMigrations);

        foreach ($allMigrations as $migrationClass) {
            $migration = new $migrationClass(
                $adapter,
                new BaseSchemaBuilder($adapter)
            );

            $migration->down();

            // Record migration in the migrations table
            $adapter->query(
                "DELETE FROM migrations WHERE migration = :migration",
                ["migration" => $migrationClass]
            );
            echo "Migrated: {$migrationClass}\n";
        }
    }

    private function loadMigrations(string $directory): array
    {
        $migrations = [];

        if (is_dir($directory)) {
            foreach (glob("{$directory}/*.php") as $file) {
                require_once $file;
                $className = $this->getClassNameFromFile($file);
                if (class_exists($className)) {
                    $migrations[] = $className;
                }
            }
        }

        return $migrations;
    }

    private function getClassNameFromFile(string $file): string
    {
        $fileName = basename($file, ".php");
        return str_replace("/", "\\", $fileName);
    }

    private function getCurrentBatch(ORMDatabaseAdapterInterface $adapter): int
    {
        $result = $adapter->query("SELECT MAX(batch) as batch FROM migrations");
        return $result[0]["batch"] ?? 0;
    }
}
