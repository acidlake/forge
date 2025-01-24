<?php
namespace Base\Notifications\Drivers;

use Base\Interfaces\NotificationDriverInterface;

class EmailDriver implements NotificationDriverInterface
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private string $encryption;
    private string $from;

    public function __construct(
        string $host,
        int $port,
        string $username,
        string $password,
        string $from,
        string $encryption = "tls"
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->encryption = $encryption;
        $this->from = $from;
    }

    public function send(array $data): bool
    {
        $to = $data["to"];
        $subject = $data["subject"];
        $message = $data["message"];
        $isHtml = $data["isHtml"] ?? false; // New flag to determine if the message is HTML

        // Construct the email headers
        $headers = "From: {$this->from}\r\n";
        $headers .= "To: {$to}\r\n";
        $headers .= "Subject: {$subject}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";

        // Set Content-Type for HTML or plain text
        if ($isHtml) {
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n"; // HTML content
        } else {
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n"; // Plain text content
        }

        // Format the email
        $emailContent = $headers . "\r\n" . $message;

        // Send email via SMTP
        return $this->sendSMTP($to, $emailContent);
    }

    private function sendSMTP(string $to, string $emailContent): bool
    {
        $host =
            $this->encryption === "none"
                ? $this->host
                : "{$this->encryption}://{$this->host}";

        $socket = fsockopen($host, $this->port, $errno, $errstr, 30);
        if (!$socket) {
            throw new \RuntimeException(
                "Failed to connect to SMTP server: {$errstr} ({$errno})"
            );
        }

        $this->expect($socket, 220);

        fwrite($socket, "EHLO localhost\r\n");
        $this->expect($socket, 250);

        fwrite($socket, "MAIL FROM: <{$this->from}>\r\n");
        $this->expect($socket, 250);

        fwrite($socket, "RCPT TO: <{$to}>\r\n");
        $this->expect($socket, 250);

        fwrite($socket, "DATA\r\n");
        $this->expect($socket, 354);

        fwrite($socket, $emailContent . "\r\n.\r\n");
        $this->expect($socket, 250);

        fwrite($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    }

    private function write($socket, string $data): void
    {
        fwrite($socket, $data . "\r\n");
    }

    private function expect($socket, int $expectedCode): void
    {
        $response = "";
        while ($line = fgets($socket, 512)) {
            $response .= $line;
            // Stop reading if this is the last line of the response (does not start with a dash)
            if (preg_match("/^\d{3}(?!-)/", $line)) {
                break;
            }
        }

        $responseCode = (int) substr($response, 0, 3);

        if ($responseCode !== $expectedCode) {
            throw new \RuntimeException(
                "Unexpected SMTP response: {$response}"
            );
        }
    }
}
