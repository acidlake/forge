<?php

namespace Base\Interfaces;

interface MigrationInterface
{
    /**
     * Run the migration (apply changes).
     */
    public function up(): void;

    /**
     * Rollback the migration (revert changes).
     */
    public function down(): void;
}
