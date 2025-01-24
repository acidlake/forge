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

    private function normalizePath(string $path): string
    {
        return rtrim(
            str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $path),
            DIRECTORY_SEPARATOR
        );
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
                "No migration paths configured for structure type: {$structureType}"
            );
        }

        $migrationPaths = [];

        if ($structureType === "modular" && isset($pathsConfig["modules"])) {
            // Handle modular paths
            $modulesPath = $this->normalizePath($pathsConfig["modules"]);
            $modules = glob("{$modulesPath}/*", GLOB_ONLYDIR);

            foreach ($modules as $modulePath) {
                $moduleName = basename($modulePath);
                $migrationsPath = $this->normalizePath(
                    "{$modulePath}/Database/Migrations"
                );

                if (is_dir($migrationsPath)) {
                    $namespace = "App\\Modules\\{$moduleName}\\Database\\Migrations";
                    $migrationPaths[$migrationsPath] = $namespace;
                }
            }
        } else {
            // Default or other structures
            $migrationsPath = $this->normalizePath($pathsConfig["migrations"]);
            $namespace = $this->resolveNamespace(
                $migrationsPath,
                $structureType
            );
            $migrationPaths[$migrationsPath] = $namespace;
        }

        return $migrationPaths;
    }

    private function resolveNamespace(
        string $path,
        string $structureType
    ): string {
        if ($structureType === "ddd") {
            return "App\\Infrastructure\\Migrations";
        }

        return "App\\Database\\Migrations";
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

            if (!file_exists($file)) {
                echo "Skipping missing file: {$file}\n";
                continue;
            }

            if (
                !preg_match(
                    "/namespace\s+{$namespace};/",
                    file_get_contents($file)
                )
            ) {
                echo "Skipping file with mismatched namespace: {$file}\n";
                continue;
            }

            require_once $file;

            if (!class_exists($className)) {
                echo "Class {$className} not found in file: {$file}\n";
                continue;
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

        try {
            return new $className($this->db, $this->schema);
        } catch (\Throwable $e) {
            echo "Failed to instantiate migration {$className}: {$e->getMessage()}\n";
            throw $e;
        }
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
