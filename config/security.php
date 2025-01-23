<?php
use Base\Helpers\EnvHelper;

/**
 * Security Configuration for the Forge framework.
 *
 * This file defines basic application settings such as the framework name,
 * version, and environment mode. Values are dynamically loaded using the `EnvHelper::get`
 * method, which retrieves environment variables with fallback defaults.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @config Application Configuration
 * @version 0.2.0
 * @category Configuration
 * @env-compatibility Supports dynamic loading using EnvHelper::get.
 * @defaults Provides default values for all configurations if environment variables are not set.
 * @author Jeremias
 * @copyright 2025
 */

return [
    /**
     * Application IP Whitelist
     *
     * List of allowed ip address to connect to the server
     *
     * @var string
     */
    "ipwhitelist" => EnvHelper::get(
        "IP_WHITELIST_ADDRESSES",
        "127.0.0.1, 192.168.1.1"
    ),

    "rate_limit_max_request" => EnvHelper::get("RATE_LIMIT_MAX_REQUESTS", 6),
    "rate_limit_time_frame" => EnvHelper::get("RATE_LIMIT_TIME_FRAME", 60),

    "circuit_breaker_failure_threshold" => EnvHelper::get(
        "CIRCUIT_BREAKER_FAILURE_THRESHOLD",
        5
    ),
    "circuit_breaker_time_frame" => EnvHelper::get(
        "CIRCUIT_BREAKER_TIME_FRAME",
        60
    ),
];
