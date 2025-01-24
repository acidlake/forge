<?php
use Base\Helpers\EnvHelper;
/**
 * Database Configuration for the Forge framework.
 *
 * This configuration file defines the default database connection and its settings.
 * It supports loading values from an `.env` file with sensible default values.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @config Database Configuration
 * @version 1.1.0
 * @category Configuration
 * @env-compatibility Supports loading configuration values using the env() helper.
 * @defaults Provides default values for all configurations if environment variables are not set.
 * @author Jeremias
 * @copyright 2025
 */

return [
    /**
     * Default Database Connection
     *
     * Specifies the default database connection to use from the `connections` array.
     *
     * @var string
     */
    "default" => EnvHelper::get("DB_CONNECTION", "mysql"),

    "key_strategy" => "uuid", // Options: 'uuid', 'id', 'custom', 'nanoid', 'snowflake', 'short-hash', 'composite', 'timestamp', 'random-string'

    "key_length" => 36, // Optional for UUID/NanoID

    "orm" => [
        "mode" => "document", // Oprions: 'document', 'relational'
    ],

    /**
     * Database Connections
     *
     * Defines the available database connections. Each connection supports loading
     * environment variables with default values to ensure flexibility and ease of use.
     *
     * @var array
     */
    "connections" => [
        "mysql" => [
            "host" => EnvHelper::get("DB_HOST", "127.0.0.1"), // Database server hostname or IP address
            "port" => EnvHelper::get("DB_PORT", 3306), // Database server port
            "database" => EnvHelper::get("DB_DATABASE", "forge"), // Name of the database
            "username" => EnvHelper::get("DB_USERNAME", "root"), // Database username
            "password" => EnvHelper::get("DB_PASSWORD", ""), // Database password
            "dsn" => EnvHelper::get(
                "DB_DSN",
                "mysql:host=localhost;dbname=forge;port=3306"
            ),
        ],
    ],
];
