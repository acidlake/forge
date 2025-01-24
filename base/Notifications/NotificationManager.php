<?php
namespace Base\Notifications;

use Base\Interfaces\NotificationManagerInterface;

class NotificationManager implements NotificationManagerInterface
{
    private array $drivers = [];

    public function __construct(array $drivers)
    {
        $this->drivers = $drivers;
    }

    public function send(string $channel, array $data): bool
    {
        if (!isset($this->drivers[$channel])) {
            throw new \RuntimeException(
                "Notification channel '{$channel}' not supported."
            );
        }

        return $this->drivers[$channel]->send($data);
    }
}
