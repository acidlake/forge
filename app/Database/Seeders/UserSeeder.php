<?php
namespace App\Seeders;

use Base\Database\BaseSeeder;

class UserSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->db->save("users", [
            [
                "name" => "John Doe",
                "email" => "john@example.com",
                "password" => "secret",
            ],
            [
                "name" => "Jane Doe",
                "email" => "jane@example.com",
                "password" => "secret",
            ],
        ]);

        echo "UserSeeder executed.\n";
    }
}
