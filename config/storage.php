<?php

use Base\Helpers\EnvHelper;

return [
    "session" => [
        "driver" => EnvHelper::get("SESSION_STORAGE_DRIVER", "file"),
        "path" => EnvHelper::getPath(
            "SESSION_STORAGE_PATH",
            "storage/sessions",
            BASE_PATH
        ),
    ],
    "files" => [
        "driver" => EnvHelper::get("FILE_STORAGE_DRIVER", "local"),
        "path" => EnvHelper::getPath(
            "FILE_STORAGE_PATH",
            "storage/files",
            BASE_PATH
        ),
    ],
];
