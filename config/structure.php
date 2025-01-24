<?php

use Base\Helpers\EnvHelper;
use Base\Helpers\PathHelper;

return [
    "type" => EnvHelper::get("PROJECT_STRUCTURE_TYPE", "default"), // Options: 'clean', 'modular', 'ddd','default' etc.

    "paths" => [
        "default" => [
            "migrations" => PathHelper::resolve(
                EnvHelper::get("MIGRATIONS_PATH", "app/Database/Migrations"),
                BASE_PATH
            ),
            "models" => PathHelper::resolve(
                EnvHelper::get("MODELS_PATH", "app/Models"),
                BASE_PATH
            ),
            "controllers" => PathHelper::resolve(
                EnvHelper::get("CONTROLLERS_PATH", "app/Controllers"),
                BASE_PATH
            ),
            "seeders" => PathHelper::resolve(
                EnvHelper::get("SEEDERS_PATH", "app/Database/Seeders"),
                BASE_PATH
            ),
            "views" => PathHelper::resolve(
                EnvHelper::get("VIEWS_PATH", "app/Views"),
                BASE_PATH
            ),
        ],
        "clean" => [
            "migrations" => PathHelper::resolve(
                EnvHelper::get("MIGRATIONS_PATH", "app/Database/Migrations"),
                BASE_PATH
            ),
            "seeders" => PathHelper::resolve(
                EnvHelper::get("SEEDERS_PATH", "app/Database/Seeders"),
                BASE_PATH
            ),
            "models" => PathHelper::resolve(
                EnvHelper::get("MODELS_PATH", "app/Domain/Models"),
                BASE_PATH
            ),
            "controllers" => PathHelper::resolve(
                EnvHelper::get(
                    "CONTROLLERS_PATH",
                    "app/Presentation/Controllers"
                ),
                BASE_PATH
            ),
            "views" => PathHelper::resolve(
                EnvHelper::get("VIEWS_PATH", "app/Presentation/Views"),
                BASE_PATH
            ),
        ],
        "modular" => [
            "modules" => PathHelper::resolve(
                EnvHelper::get("MODULES_PATH", "app/Modules"),
                BASE_PATH
            ),
            "shared" => PathHelper::resolve(
                EnvHelper::get("SHARED_PATH", "app/Shared"),
                BASE_PATH
            ),
            "migrations" => PathHelper::resolve(
                EnvHelper::get(
                    "MODULE_MIGRATIONS_PATH",
                    "app/Modules/Shared/Migrations"
                ),
                BASE_PATH
            ),
            "seeders" => PathHelper::resolve(
                EnvHelper::get(
                    "MODULE_SEEDERS_PATH",
                    "app/Modules/Shared/Seeders"
                ),
                BASE_PATH
            ),
        ],
        "ddd" => [
            "migrations" => PathHelper::resolve(
                EnvHelper::get(
                    "MIGRATIONS_PATH",
                    "src/Infrastructure/Migrations"
                ),
                BASE_PATH
            ),
            "seeders" => PathHelper::resolve(
                EnvHelper::get(
                    "MODULE_SEEDERS_PATH",
                    "src/Infrastructure/Seeders"
                ),
                BASE_PATH
            ),
            "models" => PathHelper::resolve(
                EnvHelper::get("MODELS_PATH", "src/Domain/Models"),
                BASE_PATH
            ),
            "controllers" => PathHelper::resolve(
                EnvHelper::get(
                    "CONTROLLERS_PATH",
                    "src/Application/Controllers"
                ),
                BASE_PATH
            ),
            "views" => PathHelper::resolve(
                EnvHelper::get("VIEWS_PATH", "src/Application/Views"),
                BASE_PATH
            ),
        ],
    ],
];
