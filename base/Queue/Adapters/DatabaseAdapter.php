<?php

namespace Base\Queue\Adapters;

use Base\Interfaces\QueueAdapterInterface;
use PDO;

class DatabaseAdapter implements QueueAdapterInterface
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function push(
        string $jobClass,
        array $data = [],
        int $delay = 0
    ): void {
        $availableAt = time() + $delay;
        $stmt = $this->pdo->prepare("
            INSERT INTO jobs (job_class, data, available_at, created_at)
            VALUES (:job_class, :data, :available_at, :created_at)
        ");
        $stmt->execute([
            ":job_class" => $jobClass,
            ":data" => json_encode($data),
            ":available_at" => $availableAt,
            ":created_at" => time(),
        ]);
    }

    public function pop(): ?array
    {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->query("
            SELECT * FROM jobs
            WHERE available_at <= :now
            LIMIT 1 FOR UPDATE
        ");
        $stmt->execute([":now" => time()]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($job) {
            $this->delete($job["id"]);
        }

        $this->pdo->commit();

        return $job ?: null;
    }

    public function delete(string $jobId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM jobs WHERE id = :id");
        $stmt->execute([":id" => $jobId]);
    }

    public function release(string $jobId, int $delay = 0): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE jobs SET available_at = :available_at WHERE id = :id
        ");
        $stmt->execute([":available_at" => time() + $delay, ":id" => $jobId]);
    }
}
