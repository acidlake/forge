<?php

namespace App\Commands;

use Base\Interfaces\CommandInterface;

class HelloCommand implements CommandInterface
{
    public function getName(): string
    {
        return "hello";
    }

    public function getDescription(): string
    {
        return "Starts the database migrations";
    }

    public function execute(array $arguments = []): void
    {
        $name = $arguments[0] ?? "World";
        echo "Hello, {$name}! \n";
    }
}
