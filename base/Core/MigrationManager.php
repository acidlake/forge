<?php

namespace Base\Core;

use Base\Database\DatabaseAdapterInterface;
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
        MigrationBuilder::init($db);
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

    protected function instantiateMigration(string $className): object
    {
        if (!class_exists($className)) {
            throw new \RuntimeException(
                "Migration class {$className} not found."
            );
        }

        return new $className();
    }

    protected function getPendingMigrations(): array
    {
        $migrationPaths = $this->getMigrationPaths();
        $allMigrations = [];

        foreach ($migrationPaths as $path => $namespace) {
            $migrations = $this->scanDirectory($path, $namespace);
            $allMigrations = array_merge($allMigrations, $migrations);
        }

        return array_filter(
            $allMigrations,
            fn($migration) => !$this->isMigrationRun($migration["name"])
        );
    }

    protected function scanDirectory(string $path, string $namespace): array
    {
        if (!is_dir($path)) {
            return [];
        }

        $files = glob($this->normalizePath("{$path}/*.php"));
        $migrations = [];

        foreach ($files as $file) {
            $filename = basename($file, ".php");
            $className = $this->getClassName($filename, $namespace);

            if (!file_exists($file)) {
                echo "Skipping missing file: {$file}\n";
                continue;
            }

            require_once $file;

            if (!class_exists($className)) {
                echo "Class {$className} not found in file: {$file}\n";
                continue;
            }

            $migrations[] = [
                "name" => $filename,
                "class" => $className,
            ];
        }

        return $migrations;
    }

    private function getClassName(string $filename, string $namespace): string
    {
        $className = preg_replace("/^\d+_/", "", $filename); // Remove timestamp
        $className = str_replace("_", "", ucwords($className, "_")); // Convert to PascalCase
        return "{$namespace}\\{$className}";
    }

    private function normalizePath(string $path): string
    {
        return rtrim(
            preg_replace("/[\/\\\\]+/", DIRECTORY_SEPARATOR, $path),
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
            $modulesPath = $this->normalizePath($pathsConfig["modules"]);
            $modules = glob("{$modulesPath}/*", GLOB_ONLYDIR);

            foreach ($modules as $modulePath) {
                $moduleName = basename($modulePath);
                $migrationsPath = "{$modulePath}/Database/Migrations";

                if (is_dir($migrationsPath)) {
                    $namespace = "App\\Modules\\{$moduleName}\\Database\\Migrations";
                    $migrationPaths[$migrationsPath] = $namespace;
                }
            }
        } else {
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
        return $structureType === "ddd"
            ? "App\\Infrastructure\\Migrations"
            : "App\\Database\\Migrations";
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

    protected function getCurrentBatch(): int
    {
        $result = $this->db->fetchOne(
            "SELECT MAX(batch) AS batch FROM migrations"
        );
        return $result["batch"] ?? 0;
    }

    public function rollback(): void
    {
        $migrations = $this->getLastBatchMigrations();

        if (empty($migrations)) {
            echo "No migrations to rollback.\n";
            return;
        }

        foreach (array_reverse($migrations) as $migration) {
            echo "Rolling back migration: {$migration["name"]}...\n";

            try {
                $className = $this->getClassNameForRollback($migration["name"]);
                $instance = $this->instantiateMigration($className);
                $instance->down();
                $this->markMigrationAsRolledBack($migration["name"]);
                echo "Migration {$migration["name"]} rolled back.\n";
            } catch (\Throwable $e) {
                echo "Error rolling back migration {$migration["name"]}: {$e->getMessage()}\n";
                break;
            }
        }
    }

    protected function getClassNameForRollback(string $migrationName): string
    {
        $paths = $this->getMigrationPaths();

        foreach ($paths as $path => $namespace) {
            $filePath = "{$path}/{$migrationName}.php";

            if (file_exists($filePath)) {
                // Include the migration file if not already included
                if (
                    !class_exists(
                        $this->getClassName($migrationName, $namespace)
                    )
                ) {
                    require_once $filePath;
                }

                // Derive the class name
                $className = $this->getClassName($migrationName, $namespace);

                // Check if the class exists
                if (class_exists($className)) {
                    return $className;
                }
            }
        }

        throw new \RuntimeException(
            "Class for migration {$migrationName} not found."
        );
    }
    protected function getLastBatchMigrations(): array
    {
        $batch = $this->getCurrentBatch();
        return $this->db->fetchAll(
            "SELECT * FROM migrations WHERE batch = ? ORDER BY id DESC",
            [$batch]
        );
    }

    protected function markMigrationAsRolledBack(string $name): void
    {
        $this->db->execute("DELETE FROM migrations WHERE name = ?", [$name]);
    }
}
