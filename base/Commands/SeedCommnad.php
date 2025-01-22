<?php
namespace Base\Commands;

use Base\Database\BaseSeeder;
use Base\Interfaces\CommandInterface;
use Base\Interfaces\SeederInterface;

class SeedCommand implements CommandInterface
{
    private string $seederPath;

    public function __construct()
    {
        $this->seederPath = BASE_PATH . "/app/Database/Seeders";
    }

    public function getName(): string
    {
        return "seed";
    }

    public function getDescription(): string
    {
        return "Seed the database with initial data.";
    }

    public function execute(array $arguments = []): void
    {
        $seederClass = $arguments[0] ?? "App\\Seeders\\DatabaseSeeder";

        if (!class_exists($seederClass)) {
            echo "Seeder class {$seederClass} not found.\n";
            return;
        }

        $seeder = new $seederClass();
        if ($seeder instanceof BaseSeeder) {
            echo "Running seeder: {$seederClass}\n";
            $seeder->run();
            echo "Seeder completed.\n";
        } else {
            echo "Class {$seederClass} is not a valid seeder.\n";
        }
    }
}
