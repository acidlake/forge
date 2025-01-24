<?php

namespace Base\Providers;

use Base\Core\Container;
use Base\Core\ContainerAwareTrait;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\ProviderInterface;
use Base\Notifications\Drivers\EmailDriver;
use Base\Notifications\Drivers\PushDriver;
use Base\Notifications\Drivers\SMSDriver;
use Base\Notifications\NotificationManager;
use Base\Interfaces\NotificationManagerInterface;

class NotificationServiceProvider implements ProviderInterface
{
    use ContainerAwareTrait;

    public function register(Container $container): void
    {
        $container->bind(NotificationManagerInterface::class, function () {
            $configHelper = $this->resolve(ConfigHelperInterface::class);
            $config = $configHelper->get("notifications.channels");

            return new NotificationManager([
                "email" => new EmailDriver(
                    host: $config["email"]["host"],
                    from: $config["email"]["from"],
                    port: $config["email"]["port"],
                    username: $config["email"]["username"],
                    password: $config["email"]["password"],
                    encryption: $config["email"]["encryption"]
                ),
                "sms" => new SMSDriver(
                    $config["sms"]["accountSid"],
                    $config["sms"]["authToken"],
                    $config["sms"]["from"]
                ),
                "push" => new PushDriver($config["push"]["firebaseKey"]),
            ]);
        });
    }
}
