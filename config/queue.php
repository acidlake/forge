<?php
return [
    "default" => "file", // Can be 'database', 'redis', 'file', etc.
    "connections" => [
        "database" => [
            "table" => "jobs",
        ],
        "redis" => [
            "host" => "127.0.0.1",
            "port" => 6379,
        ],
        "file" => [
            "path" => BASE_PATH . "/storage/queues",
        ],
    ],
];
