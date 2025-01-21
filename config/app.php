<?php

use Base\Helpers\EnvHelper;

/**
 * Application Configuration for the Forge framework.
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
     * Application Name
     *
     * The name of the application or framework.
     *
     * @var string
     */
    "name" => EnvHelper::get("APP_NAME", "Forge Framework"),

    /**
     * Application Version
     *
     * The current version of the application or framework.
     *
     * @var string
     */
    "version" => EnvHelper::get("APP_VERSION", "0.1.1"),

    /**
     * Application Environment
     *
     * Specifies the current environment (e.g., development, production, staging).
     *
     * @var string
     */
    "environment" => EnvHelper::get("APP_ENV", "development"),
];
