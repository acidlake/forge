<?php

namespace Base\Core;

use Base\Database\DatabaseAdapterInterface;
use Base\Database\BaseMigration;
use Base\Database\BaseSchemaBuilder;

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
    }

    public function run(): void
    {
        $this->ensureMigrationTableExists();

        $migrations = $this->getPendingMigrations();
        foreach ($migrations as $migration) {
            echo "Running migration: {$migration["name"]}...\n";
            $instance = $this->instantiateMigration($migration["class"]);
            $instance->up();
            $this->markMigrationAsRun($migration["name"]);
        }
    }

    public function rollback(): void
    {
        $this->ensureMigrationTableExists();

        $migrations = $this->getLastBatchMigrations();
        foreach ($migrations as $migration) {
            echo "Rolling back migration: {$migration["name"]}...\n";
            $instance = $this->instantiateMigration($migration["class"]);
            $instance->down();
            $this->markMigrationAsRolledBack($migration["name"]);
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
        $basePath = BASE_PATH . "/base/Database/Migrations/";
        $appPath = BASE_PATH . "/app/Database/Migrations/";

        $migrations = array_merge(
            $this->scanDirectory($basePath, "Base\\Database\\Migrations"),
            $this->scanDirectory($appPath, "App\\Database\\Migrations")
        );

        $pending = [];
        foreach ($migrations as $migration) {
            if (!$this->isMigrationRun($migration["name"])) {
                $pending[] = $migration;
            }
        }

        return $pending;
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

    protected function getLastBatchMigrations(): array
    {
        $batch = $this->db->fetchOne(
            "SELECT MAX(batch) AS batch FROM migrations"
        )["batch"];
        return $this->db->fetchAll("SELECT * FROM migrations WHERE batch = ?", [
            $batch,
        ]);
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

    protected function getCurrentBatch(): int
    {
        $result = $this->db->fetchOne(
            "SELECT MAX(batch) AS batch FROM migrations"
        );
        return $result["batch"] ?? 0;
    }
}
