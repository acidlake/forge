<?php

namespace App\Seeders;

use Base\Interfaces\SeederInterface;
use Base\Database\DatabaseAdapterInterface;
use App\Models\User;

class UserSeeder implements SeederInterface
{
    public function __construct(protected DatabaseAdapterInterface $db) {}

    public function run(): void
    {
        echo "Seeding users...\n";

        // Beginner-friendly approach: Use the ORM
        User::createMany([
            [
                "uuid" => User::generateUUID(),
                "name" => "John Doe",
                "email" => "john@example.com",
                "role" => "admin",
            ],
            [
                "uuid" => User::generateUUID(),
                "name" => "Jane Doe",
                "email" => "jane@example.com",
                "role" => "user",
            ],
        ]);
    }

    public function rollback(): void
    {
        echo "Rolling back users...\n";

        // Rollback: Delete users by condition
        User::whereIn("email", [
            "john@example.com",
            "jane@example.com",
        ])->delete();
    }
}
