<?php

namespace Base\Core;

use Base\Interfaces\MigrationInterface;
use Base\Models\BaseModel;
use Exception;

class MigrationManager
{
    protected BaseModel $migrationsModel;

    public function __construct(BaseModel $model)
    {
        $this->migrationsModel = $model;
        $this->ensureMigrationsTable();
    }

    /**
     * Apply all pending migrations.
     */
    public function migrate(array $migrations): void
    {
        foreach ($migrations as $migration) {
            $migrationName = get_class($migration);

            if ($this->isMigrationApplied($migrationName)) {
                echo "Migration {$migrationName} already applied.\n";
                continue;
            }

            try {
                $migration->up();
                $this->markAsApplied($migrationName);
                echo "Migration {$migrationName} applied successfully.\n";
            } catch (Exception $e) {
                echo "Failed to apply {$migrationName}: {$e->getMessage()}\n";
            }
        }
    }

    /**
     * Rollback the last applied migration.
     */
    public function rollback(array $migrations): void
    {
        $lastMigration = $this->getLastAppliedMigration();

        if (!$lastMigration) {
            echo "No migrations to rollback.\n";
            return;
        }

        foreach ($migrations as $migration) {
            if (get_class($migration) === $lastMigration) {
                try {
                    $migration->down();
                    $this->markAsRolledBack($lastMigration);
                    echo "Migration {$lastMigration} rolled back successfully.\n";
                    return;
                } catch (Exception $e) {
                    echo "Failed to rollback {$lastMigration}: {$e->getMessage()}\n";
                    return;
                }
            }
        }

        echo "Migration class {$lastMigration} not found.\n";
    }

    /**
     * Ensure the `migrations` table exists.
     */
    protected function ensureMigrationsTable(): void
    {
        if (!$this->migrationsModel->tableExists()) {
            $this->migrationsModel
                ->schema()
                ->create("migrations", function ($table) {
                    $table->uuid("id")->primary();
                    $table->string("migration");
                    $table
                        ->timestamp("applied_at")
                        ->default("CURRENT_TIMESTAMP");
                });
        }
    }

    protected function isMigrationApplied(string $migrationName): bool
    {
        return $this->migrationsModel
            ->where("migration", $migrationName)
            ->exists();
    }

    protected function markAsApplied(string $migrationName): void
    {
        $this->migrationsModel->insert(["migration" => $migrationName]);
    }

    protected function markAsRolledBack(string $migrationName): void
    {
        $this->migrationsModel->where("migration", $migrationName)->delete();
    }

    protected function getLastAppliedMigration(): ?string
    {
        $lastMigration = $this->migrationsModel
            ->orderBy("applied_at", "desc")
            ->first();

        return $lastMigration ? $lastMigration->migration : null;
    }
}
