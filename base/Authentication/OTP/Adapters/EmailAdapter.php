<?php
namespace Base\Authentication\OTP\Adapters;

use Base\Interfaces\OTPDeliveryAdapterInterface;

class EmailAdapter implements OTPDeliveryAdapterInterface
{
    public function send($userId, string $otp): void
    {
        // Fetch the user's email from the database.
        $userEmail = $this->getUserEmail($userId);

        // Send the email (placeholder for actual email logic).
        echo "Sent OTP {$otp} to email: {$userEmail}";
    }

    private function getUserEmail($userId): string
    {
        // Fetch user email from the database. Placeholder for actual implementation.
        return "user@example.com";
    }
}
