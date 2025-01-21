<?php

namespace App\Commands;

use Base\Commands\CommandInterface;

class HelloCommand implements CommandInterface
{
    public function getName(): string
    {
        return "hello";
    }

    public function handle(array $args): void
    {
        $name = $args[0] ?? "World";
        echo "Hello, {$name}!" . PHP_EOL;
    }
}
