<?php
namespace Base\Commands;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\CommandInterface;
use Base\Models\Job;

class QueueWorkCommand implements CommandInterface
{
    use ContainerAwareTrait;

    public function getName(): string
    {
        return "queue:work";
    }

    public function getDescription(): string
    {
        return "Process jobs from the queue.";
    }

    public function execute(array $args = []): void
    {
        $queue = $args[0] ?? "default";
        $workerCount = (int) ($args[1] ?? 1);

        // Start worker processes
        for ($i = 0; $i < $workerCount; $i++) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                $this->processQueue($queue);
                exit();
            }
        }

        // Wait for all workers to complete
        while (pcntl_waitpid(0, $status) !== -1);
    }

    private function processQueue(string $queue): void
    {
        while (true) {
            $job = Job::where(["queue" => $queue, "reserved_at" => null])
                ->orderBy("available_at", "asc")
                ->first();

            if ($job) {
                $this->processJob($job);
            } else {
                sleep(1); // Avoid hammering the database
            }
        }
    }

    private function processJob(Job $job): void
    {
        $job->reserved_at = date("Y-m-d H:i:s");
        $job->save();

        $payload = json_decode($job->payload);

        try {
            $jobInstance = unserialize($payload);
            $jobInstance->handle();

            // Delete job after successful processing
            $job->delete();
        } catch (\Exception $e) {
            $job->attempts++;

            if ($job->attempts >= $job->max_attempts) {
                $job->delete();

                // Log failed job
                $this->logFailedJob($job, $e);
            } else {
                // Update the job for retry
                $job->reserved_at = null;
                $job->save();
            }
        }
    }

    private function logFailedJob(Job $job, \Exception $e): void
    {
        // TODO: Implement
        // Notify via Notification Service
        // $notificationService = $this->resolve(
        //     \Base\Notifications\NotificationManager::class
        // );
        //$notificationService->notify("Job Failed", $e->getMessage());
    }
}
