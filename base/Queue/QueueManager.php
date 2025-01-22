<?php

namespace Base\Queue;

use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\QueueAdapterInterface;
use Base\Core\ContainerAwareTrait;

class QueueManager
{
    use ContainerAwareTrait;

    protected QueueAdapterInterface $adapter;

    public function __construct()
    {
        $config = $this->resolve(ConfigHelperInterface::class);
        $adapterClass = $config->get("queue.default_adapter");
        $this->adapter = $this->resolve($adapterClass);
    }

    public function push(
        string $jobClass,
        array $data = [],
        int $delay = 0
    ): void {
        $this->adapter->push($jobClass, $data, $delay);
    }

    public function processNext(): void
    {
        $jobData = $this->adapter->pop();

        if ($jobData) {
            $this->handleJob($jobData);
        }
    }

    protected function handleJob(array $jobData): void
    {
        $jobClass = $jobData["job_class"];
        $job = new $jobClass($jobData["data"]);

        try {
            $job->handle();
        } catch (\Exception $e) {
            $job->failed($e);
            $this->adapter->release(
                $jobData["id"],
                $jobData["retry_delay"] ?? 0
            );
        }
    }
}
