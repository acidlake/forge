<?php
namespace Base\Providers;

use Base\Interfaces\LogServiceInterface;
use Base\Services\Log\LogManager;
use Base\Services\Log\Drivers\SentryDriver;
use Base\Core\Container;

class LogServiceProvider
{
    public static function register(Container $container): void
    {
        // $container->bind(LogServiceInterface::class, function () {
        //     $logConfig = require BASE_PATH . "/config/log.php";

        //     if ($logConfig["driver"] === "sentry") {
        //         return new SentryDriver($logConfig["sentry"]["dsn"]);
        //     }

        //     // Default to a file log service
        //     return new FileLogService(BASE_PATH . "/logs/app.log");
        // });

        $container->bind(LogManager::class, function ($container) {
            return new LogManager(
                $container->resolve(LogServiceInterface::class)
            );
        });
    }
}
