<?php
namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Interfaces\SeederInterface;

class SeedCommand implements CommandInterface
{
    private string $seederPath;

    public function __construct()
    {
        $this->seederPath = BASE_PATH . "/app/Seeders";
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
        echo "Running seeders...\n";

        $seederFiles = glob("{$this->seederPath}/*.php");
        foreach ($seederFiles as $file) {
            $seederName = basename($file, ".php");

            require_once $file;
            $seederClass = "App\\Seeders\\{$seederName}";

            if (
                class_exists($seederClass) &&
                is_subclass_of($seederClass, SeederInterface::class)
            ) {
                $seeder = new $seederClass();
                $seeder->run();
                echo "Seeded: {$seederName}\n";
            }
        }

        echo "Seeding complete.\n";
    }
}
