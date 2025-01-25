<?php

use Base\Helpers\EnvHelper;

return [
    "custom" => [
        "enabled" => EnvHelper::get("CUSTOM_ERROR_TEMPLATE", "false"),
        "path" => EnvHelper::getPath(
            "CUSTOM_ERROR_TEMPLATE_PATH",
            "base/UI/errors/",
            BASE_PATH
        ),
    ],
];
