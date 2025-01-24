<?php

use Base\Helpers\EnvHelper;

/**
 * Production Configuration for the Forge framework.
 *
 * This configuration file defines settings optimized for production environments,
 * including debug mode and logging. It is designed to ensure minimal exposure of
 * sensitive information and optimal performance.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @config Production Configuration
 * @version 1.0.0
 * @category Configuration
 * @env-compatibility Supports environment-based customization for production settings.
 * @defaults Provides optimized defaults for production environments.
 * @author Jeremias
 * @copyright 2025
 */

return [
    /**
     * Enable or disable debug mode.
     *
     * Debug mode is disabled in production to prevent sensitive information from being exposed.
     *
     * @var bool
     */
    "debug" => EnvHelper::get("APP_DEBUG", false),

    /**
     * Logging Configuration
     *
     * Settings related to application logging for production environments. This includes:
     *
     * - level: The verbosity level of logs.
     *   - error: Logs only critical errors to ensure minimal log clutter.
     *
     * - path: The file path where production logs will be written.
     *
     * @var array
     */
    "logging" => [
        "level" => EnvHelper::get("LOG_LEVEL", "error"), // Default log level is "error".
        "path" => EnvHelper::get("LOG_PATH", BASE_PATH . "/logs/app.log"), // Default log path.
    ],
];
