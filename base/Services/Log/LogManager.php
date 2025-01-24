<?php
namespace Base\Services\Log;

use Base\Interfaces\LogServiceInterface;
use Exception;

class LogManager
{
    private LogServiceInterface $service;

    public function __construct(LogServiceInterface $service)
    {
        $this->service = $service;
    }

    public function logError(string $message, array $context = []): void
    {
        try {
            $this->service->logError($message, $context);
        } catch (Exception $e) {
            error_log("Failed to log error: " . $e->getMessage());
        }
    }

    public function logInfo(string $message, array $context = []): void
    {
        try {
            $this->service->logInfo($message, $context);
        } catch (Exception $e) {
            error_log("Failed to log info: " . $e->getMessage());
        }
    }

    public function logWarning(string $message, array $context = []): void
    {
        try {
            $this->service->logWarning($message, $context);
        } catch (Exception $e) {
            error_log("Failed to log warning: " . $e->getMessage());
        }
    }
}
