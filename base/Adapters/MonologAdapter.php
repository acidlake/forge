<?php

namespace Base\Adapters;

use Base\Core\LoggerInterface;
use Monolog\Logger;

class MonologAdapter implements LoggerInterface
{
    private Logger $monolog;

    public function __construct(Logger $monolog)
    {
        $this->monolog = $monolog;
    }

    public function log(
        string $level,
        string $message,
        array $context = []
    ): void {
        $this->monolog->log($level, $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log(Logger::INFO, $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log(Logger::DEBUG, $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log(Logger::WARNING, $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log(Logger::ERROR, $message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->log(Logger::CRITICAL, $message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        $this->log(Logger::ALERT, $message, $context);
    }

    public function emergency(string $message, array $context = []): void
    {
        $this->log(Logger::EMERGENCY, $message, $context);
    }
}
