<?php

namespace Base\Commands;

use Base\Core\SeederManager;
use Base\Interfaces\CommandInterface;

class SeedCommand implements CommandInterface
{
    public function __construct(protected SeederManager $seederManager) {}

    public function getName(): string
    {
        return "seed";
    }

    public function getDescription(): string
    {
        return "Run all registered seeders.";
    }

    public function execute(array $arguments = []): void
    {
        $this->seederManager->run();
    }
}
