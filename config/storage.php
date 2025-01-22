<?php

use Base\Helpers\EnvHelper;

return [
    "session" => [
        "driver" => EnvHelper::get("SESSION_STORAGE_DRIVER", "file"),
        "path" => EnvHelper::get(
            "SESSION_STORAGE_PATH",
            BASE_PATH . "/storage/sessions"
        ),
    ],
    "files" => [
        "driver" => EnvHelper::get("FILE_STORAGE_DRIVER", "local"),
        "path" => EnvHelper::get(
            "FILE_STORAGE_PATH",
            BASE_PATH . "/storage/files"
        ),
    ],
];
