<?php
namespace Base\Notifications\Drivers;

use Base\Interfaces\NotificationDriverInterface;

class PushDriver implements NotificationDriverInterface
{
    private string $firebaseKey;

    public function __construct(string $firebaseKey)
    {
        $this->firebaseKey = $firebaseKey;
    }

    public function send(array $data): bool
    {
        $to = $data["to"];
        $message = $data["message"];

        $postData = json_encode([
            "to" => $to,
            "notification" => ["body" => $message],
        ]);

        $ch = curl_init("https://fcm.googleapis.com/fcm/send");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: key={$this->firebaseKey}",
            "Content-Type: application/json",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 200;
    }
}
