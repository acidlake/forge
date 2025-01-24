<?php

namespace Base\Core;

use Base\Database\DatabaseAdapterInterface;
use Base\Database\BaseMigration;
use Base\Database\BaseSchemaBuilder;
use Base\Tools\ConfigHelper;

class MigrationManager
{
    protected DatabaseAdapterInterface $db;
    protected BaseSchemaBuilder $schema;

    public function __construct(
        DatabaseAdapterInterface $db,
        BaseSchemaBuilder $schema
    ) {
        $this->db = $db;
        $this->schema = $schema;
        $this->ensureMigrationTableExists();
    }

    public function run(): void
    {
        $migrations = $this->getPendingMigrations();

        if (empty($migrations)) {
            echo "No pending migrations to run.\n";
            return;
        }

        foreach ($migrations as $migration) {
            echo "Running migration: {$migration["name"]}...\n";

            try {
                $instance = $this->instantiateMigration($migration["class"]);
                $instance->up();
                $this->markMigrationAsRun($migration["name"]);
                echo "Migration {$migration["name"]} completed.\n";
            } catch (\Throwable $e) {
                echo "Error running migration {$migration["name"]}: {$e->getMessage()}\n";
                break;
            }
        }
    }

    public function rollback(): void
    {
        $migrations = $this->getLastBatchMigrations();

        if (empty($migrations)) {
            echo "No migrations to rollback.\n";
            return;
        }

        foreach ($migrations as $migration) {
            echo "Rolling back migration: {$migration["name"]}...\n";

            try {
                $instance = $this->instantiateMigration($migration["class"]);
                $instance->down();
                $this->markMigrationAsRolledBack($migration["name"]);
                echo "Migration {$migration["name"]} rolled back.\n";
            } catch (\Throwable $e) {
                echo "Error rolling back migration {$migration["name"]}: {$e->getMessage()}\n";
                break;
            }
        }
    }

    protected function ensureMigrationTableExists(): void
    {
        $this->db->execute("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                batch INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    protected function getPendingMigrations(): array
    {
        $migrationPaths = $this->getMigrationPaths();
        $allMigrations = [];

        foreach ($migrationPaths as $path => $namespace) {
            $migrations = $this->scanDirectory($path, $namespace);
            $allMigrations = array_merge($allMigrations, $migrations);
        }

        return array_filter($allMigrations, function ($migration) {
            return !$this->isMigrationRun($migration["name"]);
        });
    }

    protected function getMigrationPaths(): array
    {
        $structureType = ConfigHelper::get("structure.type", "default");
        $pathsConfig = ConfigHelper::get(
            "structure.paths.{$structureType}",
            []
        );

        if (empty($pathsConfig["migrations"])) {
            throw new \RuntimeException(
                "No migration path configured for structure type: {$structureType}"
            );
        }

        return [
            $pathsConfig["migrations"] => "App\\Database\\Migrations",
        ];
    }

    protected function scanDirectory(string $path, string $namespace): array
    {
        if (!is_dir($path)) {
            return [];
        }

        $files = glob("{$path}/*.php");
        $migrations = [];

        foreach ($files as $file) {
            $name = basename($file, ".php");
            $className = "{$namespace}\\{$name}";

            if (!class_exists($className)) {
                require_once $file; // Include the migration file
            }

            $migrations[] = [
                "name" => $name,
                "class" => $className,
            ];
        }

        return $migrations;
    }

    protected function instantiateMigration(string $className): BaseMigration
    {
        if (!class_exists($className)) {
            throw new \RuntimeException(
                "Migration class {$className} not found."
            );
        }

        return new $className($this->db, $this->schema);
    }

    protected function isMigrationRun(string $name): bool
    {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) AS count FROM migrations WHERE name = ?",
            [$name]
        );
        return $result["count"] > 0;
    }

    protected function markMigrationAsRun(string $name): void
    {
        $batch = $this->getCurrentBatch() + 1;
        $this->db->execute(
            "INSERT INTO migrations (name, batch) VALUES (?, ?)",
            [$name, $batch]
        );
    }

    protected function markMigrationAsRolledBack(string $name): void
    {
        $this->db->execute("DELETE FROM migrations WHERE name = ?", [$name]);
    }

    protected function getLastBatchMigrations(): array
    {
        $batch = $this->getCurrentBatch();
        return $this->db->fetchAll("SELECT * FROM migrations WHERE batch = ?", [
            $batch,
        ]);
    }

    protected function getCurrentBatch(): int
    {
        $result = $this->db->fetchOne(
            "SELECT MAX(batch) AS batch FROM migrations"
        );
        return $result["batch"] ?? 0;
    }
}
