<?php

namespace Base\Adapters;

use Base\Interfaces\LoggerInterface;
use Monolog\Logger;

/**
 * MonologAdapter provides a bridge between the Forge framework and the Monolog logging library.
 *
 * This adapter implements the `LoggerInterface` and delegates logging operations to a Monolog instance.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @autor Jeremias
 * @copyright 2025
 */
class MonologAdapter implements LoggerInterface
{
    /**
     * The Monolog instance used for logging operations.
     *
     * @var Logger
     */
    private Logger $monolog;

    /**
     * Constructor for MonologAdapter.
     *
     * @param Logger $monolog The Monolog instance to delegate logging operations to.
     */
    public function __construct(Logger $monolog)
    {
        $this->monolog = $monolog;
    }

    /**
     * Log a message with a specific severity level.
     *
     * @param string $level   The severity level (e.g., DEBUG, INFO, WARNING).
     * @param string $message The log message.
     * @param array  $context Additional context for the log entry.
     *
     * @return void
     */
    public function log(
        string $level,
        string $message,
        array $context = []
    ): void {
        $this->monolog->log($level, $message, $context);
    }

    /**
     * Log an informational message.
     *
     * @param string $message The log message.
     * @param array  $context Additional context for the log entry.
     *
     * @return void
     */
    public function info(string $message, array $context = []): void
    {
        $this->log(Logger::INFO, $message, $context);
    }

    /**
     * Log a debug message.
     *
     * @param string $message The log message.
     * @param array  $context Additional context for the log entry.
     *
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log(Logger::DEBUG, $message, $context);
    }

    /**
     * Log a warning message.
     *
     * @param string $message The log message.
     * @param array  $context Additional context for the log entry.
     *
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log(Logger::WARNING, $message, $context);
    }

    /**
     * Log an error message.
     *
     * @param string $message The log message.
     * @param array  $context Additional context for the log entry.
     *
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        $this->log(Logger::ERROR, $message, $context);
    }

    /**
     * Log a critical message.
     *
     * @param string $message The log message.
     * @param array  $context Additional context for the log entry.
     *
     * @return void
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log(Logger::CRITICAL, $message, $context);
    }

    /**
     * Log an alert message.
     *
     * @param string $message The log message.
     * @param array  $context Additional context for the log entry.
     *
     * @return void
     */
    public function alert(string $message, array $context = []): void
    {
        $this->log(Logger::ALERT, $message, $context);
    }

    /**
     * Log an emergency message.
     *
     * @param string $message The log message.
     * @param array  $context Additional context for the log entry.
     *
     * @return void
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->log(Logger::EMERGENCY, $message, $context);
    }
}
