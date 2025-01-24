<?php

namespace App\Database\Seeders;

use Base\Database\BaseSeeder;
use App\Database\Seeders\UserSeeder;
use Base\Database\DatabaseAdapterInterface;

class DatabaseSeeder extends BaseSeeder
{
    public function __construct(protected DatabaseAdapterInterface $db) {}

    public function run(): void
    {
        echo "Running DatabaseSeeder...\n";

        $this->call([UserSeeder::class]);
    }

    public function rollback(): void
    {
        echo "Rolling back DatabaseSeeder...\n";

        // Rollback the seeders in reverse order
        $this->callRollback([UserSeeder::class]);
    }
}
