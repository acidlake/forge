<?php

/**
 * Environment Configuration for the Forge framework.
 *
 * This configuration file defines the supported application environments.
 * Environments are used to distinguish between development, staging, and production modes,
 * allowing for environment-specific configurations and behaviors.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @config Environment Configuration
 * @version 1.0.0
 * @category Configuration
 * @env-compatibility Supports dynamic environment-based configuration management.
 * @author Jeremias
 * @copyright 2025
 */

return [
    /**
     * Supported Environments
     *
     * List of environments supported by the application. These values can be used
     * to conditionally load configurations, routes, or services based on the current environment.
     *
     * Examples:
     * - development: Used for local development.
     * - staging: Used for pre-production testing.
     * - production: Used for the live production environment.
     *
     * @var array
     */
    "supported" => ["development", "staging", "production"],
];
