<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;

class ServeCommand implements CommandInterface
{
    public function getName(): string
    {
        return "serve";
    }

    public function getDescription(): string
    {
        return "Starts the built-in PHP development server.";
    }

    public function execute(array $arguments = []): void
    {
        $host = $arguments[0] ?? "127.0.0.1";
        $port = $arguments[1] ?? "8000";

        $documentRoot = BASE_PATH . "/public";
        echo "Starting server at http://{$host}:{$port}\n";
        echo "Press Ctrl+C to stop the server.\n";

        passthru("php -S {$host}:{$port} -t {$documentRoot}");
    }
}
