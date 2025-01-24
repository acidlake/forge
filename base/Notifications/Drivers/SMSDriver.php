<?php
namespace Base\Notifications\Drivers;

use Base\Interfaces\NotificationDriverInterface;

class SMSDriver implements NotificationDriverInterface
{
    private string $accountSid;
    private string $authToken;
    private string $from;

    public function __construct(
        string $accountSid,
        string $authToken,
        string $from
    ) {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->from = $from;
    }

    public function send(array $data): bool
    {
        $to = $data["to"];
        $message = $data["message"];

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->accountSid}/Messages.json";
        $postData = http_build_query([
            "From" => $this->from,
            "To" => $to,
            "Body" => $message,
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt(
            $ch,
            CURLOPT_USERPWD,
            "{$this->accountSid}:{$this->authToken}"
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 201;
    }
}
