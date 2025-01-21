<?php

namespace Base\Core;

class CLI
{
    private array $commands = [];

    public function __construct()
    {
        $this->loadCoreCommands();
        $this->loadUserCommands();
    }

    public function handle(array $argv): void
    {
        $command = $argv[1] ?? null;

        if (!$command || !isset($this->commands[$command])) {
            $this->output(
                "Command not found. Use `list` to see all available commands."
            );
            return;
        }

        call_user_func($this->commands[$command], array_slice($argv, 2));
    }

    private function loadCoreCommands(): void
    {
        $coreCommands = [
            "list" => function () {
                $this->output("Available commands:");
                foreach (array_keys($this->commands) as $command) {
                    $this->output("- $command");
                }
            },
            "serve" => function () {
                $this->output("Starting development server...");
                exec("php -S localhost:8000 -t public");
            },
        ];

        $this->commands = array_merge($this->commands, $coreCommands);
    }

    private function loadUserCommands(): void
    {
        $commandPath = BASE_PATH . "/app/Commands";
        if (is_dir($commandPath)) {
            foreach (glob($commandPath . "/*.php") as $file) {
                $commandClass = "App\\Commands\\" . basename($file, ".php");
                if (class_exists($commandClass)) {
                    $commandInstance = new $commandClass();
                    $this->commands[$commandInstance->getName()] = [
                        $commandInstance,
                        "handle",
                    ];
                }
            }
        }
    }

    private function output(string $message): void
    {
        echo $message . PHP_EOL;
    }
}
