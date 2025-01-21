<?php

namespace Base\Interfaces;

/**
 * Interface LoggerInterface
 *
 * Defines a contract for logging functionality within the Forge framework.
 * Allows logging at various levels with contextual data.
 *
 * @framework Forge
 * @author Jeremias
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
interface LoggerInterface
{
    /**
     * Log a message at a specific level.
     *
     * @param string $level   The logging level (e.g., 'info', 'debug', 'error').
     * @param string $message The log message.
     * @param array  $context Contextual data to include in the log.
     *
     * @return void
     */
    public function log(
        string $level,
        string $message,
        array $context = []
    ): void;

    /**
     * Log an informational message.
     *
     * @param string $message The log message.
     * @param array  $context Contextual data to include in the log.
     *
     * @return void
     */
    public function info(string $message, array $context = []): void;

    /**
     * Log a debug message.
     *
     * @param string $message The log message.
     * @param array  $context Contextual data to include in the log.
     *
     * @return void
     */
    public function debug(string $message, array $context = []): void;

    /**
     * Log a warning message.
     *
     * @param string $message The log message.
     * @param array  $context Contextual data to include in the log.
     *
     * @return void
     */
    public function warning(string $message, array $context = []): void;

    /**
     * Log an error message.
     *
     * @param string $message The log message.
     * @param array  $context Contextual data to include in the log.
     *
     * @return void
     */
    public function error(string $message, array $context = []): void;

    /**
     * Log a critical message.
     *
     * @param string $message The log message.
     * @param array  $context Contextual data to include in the log.
     *
     * @return void
     */
    public function critical(string $message, array $context = []): void;

    /**
     * Log an alert message.
     *
     * @param string $message The log message.
     * @param array  $context Contextual data to include in the log.
     *
     * @return void
     */
    public function alert(string $message, array $context = []): void;

    /**
     * Log an emergency message.
     *
     * @param string $message The log message.
     * @param array  $context Contextual data to include in the log.
     *
     * @return void
     */
    public function emergency(string $message, array $context = []): void;
}
