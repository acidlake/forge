<?php

namespace Base\Interfaces;

interface QueueAdapterInterface
{
    public function push(
        string $jobClass,
        array $data = [],
        int $delay = 0
    ): void;
    public function pop(): ?array;
    public function delete(string $jobId): void;
    public function release(string $jobId, int $delay = 0): void;
}
