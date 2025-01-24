<?php
namespace Base\Interfaces;

interface NotificationManagerInterface
{
    /**
     * Send a notification via the specified channel.
     *
     * @param string $channel The notification channel (e.g., "email", "sms").
     * @param array $data The notification data (e.g., recipient, message).
     * @return bool True if the notification was sent successfully, false otherwise.
     */
    public function send(string $channel, array $data): bool;
}
