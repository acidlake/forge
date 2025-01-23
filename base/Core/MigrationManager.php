<?php

namespace Base\Core;

use Base\Database\DatabaseAdapterInterface;

class MigrationManager
{
    protected DatabaseAdapterInterface $db;

    public function __construct(DatabaseAdapterInterface $db)
    {
        $this->db = $db;
    }

    public function run(): void
    {
        $this->ensureMigrationTableExists();

        $migrations = $this->getPendingMigrations();
        foreach ($migrations as $migration) {
            echo "Running migration: {$migration["name"]}...\n";
            $instance = new ($migration["class"])();
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
            $instance = new ($migration["class"])();
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
        $migrationsPath = CONFIG_PATH . "/migrations/";
        $files = glob("{$migrationsPath}/*.php");

        $pending = [];
        foreach ($files as $file) {
            $name = basename($file, ".php");
            $className = $this->getClassNameFromFile($file);

            if (!$this->isMigrationRun($name)) {
                $pending[] = [
                    "name" => $name,
                    "class" => $className,
                ];
            }
        }

        return $pending;
    }

    protected function getLastBatchMigrations(): array
    {
        // Get the latest batch
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

    private function getClassNameFromFile(string $filePath): string
    {
        $relativePath = str_replace(BASE_PATH . "/", "", $filePath);
        return str_replace(["/", ".php"], ["\\", ""], $relativePath);
    }
}
