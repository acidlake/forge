<?php
namespace Base\Interfaces;

interface LogServiceInterface
{
    public function logError(string $message, array $context = []): void;
    public function logInfo(string $message, array $context = []): void;
    public function logWarning(string $message, array $context = []): void;
}
