<?php

namespace App\Jobs;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\NotificationDriverInterface;
use Base\Interfaces\NotificationManagerInterface;
use Base\Queue\Job;

class SendEmailJob extends Job
{
    use ContainerAwareTrait;

    public function handle(): void
    {
        /**
         * @var NotificationDriverInterface $notifications
         */
        $notifications = $this->resolve(NotificationManagerInterface::class);

        $data = [
            "to" => "jeremias2@gmail.com",
            "subject" => "Welcome to Forge",
            "message" =>
                "<h1>Thank you for signing up!</h1><p>Welcome to Forge</p>",
            "isHtml" => true,
        ];

        // Send email logic
        echo "Sending email to: " . $this->data["email"];

        $success = $notifications->send("email", $data);
    }

    public function failed(\Exception $e): void
    {
        // Log failure
        echo "Failed to send email: " . $e->getMessage();
    }
}
