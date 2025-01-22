<?php
namespace App\Seeders;

use Base\Database\BaseSeeder;

class DatabaseSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
    }
}
