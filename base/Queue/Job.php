<?php

namespace Base\Queue;

use Base\Models\Job as JobModel;

abstract class Job
{
    /**
     * Handle the job logic.
     *
     * This method must be implemented in the derived class.
     */
    abstract public function handle(): void;

    /**
     * Dispatch the job to the queue.
     */
    public function dispatch(string $queue = "default"): void
    {
        $payload = json_encode($this);

        $job = new JobModel();
        $job->save([
            "queue" => $queue,
            "payload" => $payload,
            "attempts" => 0,
            "max_attempts" => 3,
            "available_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
