<?php

namespace App\Jobs;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\NotificationManagerInterface;
use Base\Queue\Job;

class SendEmailJob extends Job
{
    use ContainerAwareTrait;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        /**
         * @var NotificationManagerInterface $notificationManager
         */
        $notificationManager = $this->resolve(
            NotificationManagerInterface::class
        );

        // $data = [
        //     "to" => "jeremias2@gmail.com",
        //     "subject" => "Welcome to Forge",
        //     "message" =>
        //         "<h1>Thank you for signing up!</h1><p>Welcome to Forge</p>",
        //     "isHtml" => true,
        // ];
        //
        // // Dispatching the job
        // $job = new SendEmailJob([
        //     'to' => 'user@example.com',
        //     'subject' => 'Welcome to Forge',
        //     'body' => 'Thank you for signing up!'
        // ]);
        // $job->dispatch();

        // Send email logic
        echo "Sending email to: " . $this->data["to"];

        $notificationManager->send("email", $this->data);
    }

    public function failed(\Exception $e): void
    {
        // Log failure
        echo "Failed to send email: " . $e->getMessage();
    }
}
