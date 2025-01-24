<?php

use Base\Helpers\EnvHelper;

/**
 * Debug Configuration for the Forge framework.
 *
 * This configuration file defines settings related to debugging and logging.
 * It allows enabling or disabling debug mode and specifying logging configurations
 * such as log level and file paths.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @config Debug Configuration
 * @version 1.0.0
 * @category Configuration
 * @author Jeremias
 * @env-compatibility Supports environment-based customization of debugging settings.
 * @defaults Provides sensible defaults for development environments.
 * @copyright 2025
 */

return [
    /**
     * Enable or disable debug mode.
     *
     * Debug mode should only be enabled in development environments. Enabling it
     * in production environments may expose sensitive information.
     *
     * @var bool
     */
    "debug" => EnvHelper::get("APP_DEBUG", true),

    /**
     * Logging Configuration
     *
     * Settings related to application logging. This includes the log level and
     * the path where log files will be stored.
     *
     * - level: The verbosity level of logs. Common options include:
     *   - debug: Detailed logs for development.
     *   - info: General informational messages.
     *   - error: Only log errors.
     *
     * - path: The file path where logs will be written.
     *
     * @var array
     */
    "logging" => [
        "level" => EnvHelper::get("LOG_LEVEL", "debug"), // Default log level is "debug".
        "path" => EnvHelper::get("LOG_PATH", BASE_PATH . "/logs/dev.log"), // Default log path.
    ],
];
