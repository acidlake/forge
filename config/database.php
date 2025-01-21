<?php
return [
    "default" => "mysql",
    "connections" => [
        "mysql" => [
            "host" => "127.0.0.1",
            "port" => 3306,
            "database" => "forge",
            "username" => "root",
            "password" => "",
        ],
    ],
];
<?php

/**
 * Database Configuration for the Forge framework.
 *
 * This configuration file defines the default database connection and its settings.
 * You can add multiple database connections and select the default one to use.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @config Database Configuration
 * @version 1.0.0
 * @category Configuration
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
    "default" => "mysql",

    /**
     * Database Connections
     *
     * Defines the available database connections. Each connection specifies
     * the host, port, database name, username, and password for the connection.
     *
     * Example:
     * - MySQL connection details.
     *
     * @var array
     */
    "connections" => [
        "mysql" => [
            "host" => "127.0.0.1",        // Database server hostname or IP address
            "port" => 3306,              // Database server port
            "database" => "forge",       // Name of the database
            "username" => "root",        // Database username
            "password" => "",            // Database password
        ],
    ],
];
