<?php

namespace Base\Commands;

use Base\Core\SeederManager;
use Base\Interfaces\CommandInterface;
use Base\Tools\StructurePathResolver;

class SeedCommand implements CommandInterface
{
    public function __construct(protected SeederManager $seederManager) {}

    public function getName(): string
    {
        return "seed";
    }

    public function getDescription(): string
    {
        return "Run database seeders.";
    }

    public function execute(array $arguments = []): void
    {
        $seederPath = StructurePathResolver::resolvePath("seeders");
        $defaultSeeder =
            StructurePathResolver::resolveNamespace($seederPath) .
            "\\DatabaseSeeder";

        $seederName = $arguments[0] ?? $defaultSeeder;

        echo "Running seeder: {$seederName}\n";

        $this->seederManager->run($seederName);
    }
}
