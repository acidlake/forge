<?php

namespace App\Database\Seeders;

use Base\Interfaces\SeederInterface;
use Base\Database\DatabaseAdapterInterface;
use App\Models\Test;

class TestSeeder implements SeederInterface
{
    public function __construct(protected DatabaseAdapterInterface $db) {}

    public function run(): void
    {
        echo "Seeding Test data...
";

        Test::createMany([
            [
                "uuid" => Test::generateUUID(),
                "name" => "Sample Name 1",
                "email" => "sample1@example.com",
                "role" => "admin",
            ],
            [
                "uuid" => Test::generateUUID(),
                "name" => "Sample Name 2",
                "email" => "sample2@example.com",
                "role" => "user",
            ],
        ]);
    }

    public function rollback(): void
    {
        echo "Rolling back Test data...
";

        Test::whereIn("email", [
            "sample1@example.com",
            "sample2@example.com",
        ])->delete();
    }
}