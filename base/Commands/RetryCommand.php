<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Queue\QueueManager;

class RetryCommand implements CommandInterface
{
    public function getName(): string
    {
        return "queue:retry";
    }

    public function getDescription(): string
    {
        return "Retry failed jobs";
    }

    public function execute(array $arguments = []): void
    {
        // Logic for retrying failed jobs
        echo "Retrying failed jobs...\n";
    }
}
