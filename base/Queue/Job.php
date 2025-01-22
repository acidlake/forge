<?php

namespace Base\Queue;

abstract class Job
{
    public array $data = [];
    public int $maxRetries = 3;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    abstract public function handle(): void;

    public function failed(\Exception $e): void
    {
        // Log failure or notify user
    }
}
