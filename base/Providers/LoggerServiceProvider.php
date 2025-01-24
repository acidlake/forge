<?php
namespace Base\Providers;

use Base\Core\Container;
use Base\Helpers\EnvHelper;
use Base\Interfaces\ProviderInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Base\Adapters\MonologAdapter;
use Base\Interfaces\LoggerInterface;

class LoggerServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(LoggerInterface::class, function () {
            $monolog = new Logger("app");
            $monolog->pushHandler(
                new StreamHandler(
                    BASE_PATH . EnvHelper::get("LOG_PATH"),
                    Logger::DEBUG
                )
            );
            return new MonologAdapter($monolog);
        });
    }
}
