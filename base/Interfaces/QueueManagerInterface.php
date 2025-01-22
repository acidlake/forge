<?php
namespace Base\Interfaces;

interface QueueManagerInterface
{
    public function push(
        string $jobClass,
        array $data = [],
        int $delay = 0
    ): void;

    public function processNext(): void;
}
