<?php
namespace App\Database\Seeders;

use Base\Database\BaseSeeder;
use App\Models\User;

class UserSeeder extends BaseSeeder
{
    public function run(): void
    {
        User::new([
            "name" => "Otro mas",
        ]);

        echo "UserSeeder executed.\n";
    }
}
