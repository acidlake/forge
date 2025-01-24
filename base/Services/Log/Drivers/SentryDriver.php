<?php
namespace Base\Services\Log\Drivers;

use Base\Interfaces\LogServiceInterface;
use Sentry\ClientBuilder;
use Sentry\State\HubInterface;

class SentryDriver implements LogServiceInterface
{
    private HubInterface $sentry;

    public function __construct(string $dsn)
    {
        $this->sentry = ClientBuilder::create(["dsn" => $dsn])->getClient();
    }

    public function logError(string $message, array $context = []): void
    {
        $this->sentry->captureMessage($message, \Sentry\Severity::error());
    }

    public function logInfo(string $message, array $context = []): void
    {
        $this->sentry->captureMessage($message, \Sentry\Severity::info());
    }

    public function logWarning(string $message, array $context = []): void
    {
        $this->sentry->captureMessage($message, \Sentry\Severity::warning());
    }
}
