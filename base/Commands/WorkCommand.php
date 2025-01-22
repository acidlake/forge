<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Queue\QueueManager;

class WorkCommand implements CommandInterface
{
    public function getName(): string
    {
        return "queue:work";
    }

    public function getDescription(): string
    {
        return "Begin queue job processing";
    }

    public function execute(array $arguments = []): void
    {
        $queue = new QueueManager();

        while (true) {
            $queue->processNext();
            sleep(1);
        }
    }
}
