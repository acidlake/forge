<?php
namespace Base\Database;

use Base\Interfaces\SchemaBuilderInterface;
use Base\ORM\DatabaseAdapter;

class MigrationManager
{
    protected string $migrationTable = "migrations";

    public function __construct()
    {
        $this->ensureMigrationTableExists();
    }

    /**
     * Run all migrations.
     */
    public function migrate(): void
    {
        $migrations = $this->getPendingMigrations();
        foreach ($migrations as $migration) {
            require_once $migration["path"];
            $className = $migration["class"];
            echo "Running migration: {$className}\n";
            $migrationInstance = new $className();
            $migrationInstance->up();
            $this->recordMigration($migration["name"]);
        }
    }

    /**
     * Rollback the last batch of migrations.
     */
    public function rollback(): void
    {
        $lastBatch = $this->getLastBatch();
        foreach ($lastBatch as $migration) {
            echo "Rolling back: {$migration["name"]}\n";
            require_once $migration["path"];
            $className = $migration["class"];
            $migrationInstance = new $className();
            $migrationInstance->down();
            $this->removeMigration($migration["name"]);
        }
    }

    /**
     * Ensure the migration table exists.
     */
    protected function ensureMigrationTableExists(): void
    {
        $schema = new BaseSchemaBuilder();
        $schema->table($this->migrationTable, function (
            SchemaBlueprint $table
        ) {
            $table->uuid("id")->primary();
            $table->string("name")->unique();
            $table->integer("batch");
            $table->timestamps();
        });
    }

    /**
     * Get all pending migrations.
     */
    protected function getPendingMigrations(): array
    {
        $executed = $this->getExecutedMigrations();
        $files = glob(BASE_PATH . "/base/Database/Migrations/*.php");

        $pending = [];
        foreach ($files as $file) {
            $name = basename($file, ".php");
            if (!in_array($name, $executed)) {
                $pending[] = [
                    "name" => $name,
                    "path" => $file,
                    "class" => "Base\\Database\\Migrations\\{$name}",
                ];
            }
        }

        return $pending;
    }

    /**
     * Get the last batch of migrations.
     */
    protected function getLastBatch(): array
    {
        $db = new BaseSchemaBuilder();
        $result = $db
            ->table($this->migrationTable)
            ->where("batch", $this->getCurrentBatch())
            ->get();

        return $result->toArray();
    }

    /**
     * Get the executed migrations.
     */
    protected function getExecutedMigrations(): array
    {
        $db = new BaseSchemaBuilder();
        return $db->table($this->migrationTable)->pluck("name");
    }

    /**
     * Record a migration in the migration table.
     */
    protected function recordMigration(string $name): void
    {
        $db = new BaseSchemaBuilder();
        $db->table($this->migrationTable)->insert([
            "id" => uuid(),
            "name" => $name,
            "batch" => $this->getCurrentBatch() + 1,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }

    /**
     * Remove a migration record.
     */
    protected function removeMigration(string $name): void
    {
        $db = new BaseSchemaBuilder();
        $db->table($this->migrationTable)->where("name", $name)->delete();
    }

    /**
     * Get the current batch number.
     */
    protected function getCurrentBatch(): int
    {
        $db = new BaseSchemaBuilder();
        return $db->table($this->migrationTable)->max("batch") ?? 0;
    }
}
