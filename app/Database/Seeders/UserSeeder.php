<?php

namespace App\Database\Seeders;

use Base\Database\BaseSeeder;
use Base\Database\DatabaseAdapterInterface;
use App\Models\User;

class UserSeeder extends BaseSeeder
{
    public function __construct(protected DatabaseAdapterInterface $db) {}

    public function run(): void
    {
        $this->seedModel(User::class, [
            ["name" => "John Doe", "lastName" => "Doe"],
            ["name" => "Jane Smith", "lastName" => "Smith"],
        ]);
    }

    public function rollback(): void
    {
        echo "Rolling back User data...\n";

        // Rollback logic for UserSeeder
        this->rollbackModel(User::class, [
            "name" => ["John Doe", "Jane Smith"],
        ]);
    }
}
