<?php
namespace Base\Interfaces;

interface NotificationDriverInterface
{
    /**
     * Send a notification.
     *
     * @param array $data The notification data.
     * @return bool True if the notification was sent successfully, false otherwise.
     */
    public function send(array $data): bool;
}
