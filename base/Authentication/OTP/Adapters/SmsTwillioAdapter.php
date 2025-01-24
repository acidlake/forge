<?php
namespace Base\Authentication\Adapters;

use Base\Interfaces\OTPDeliveryAdapterInterface;

class SmsTwillioAdapter implements OTPDeliveryAdapterInterface
{
    public function send($userId, string $otp): void
    {
        // Fetch the user's phone number from the database.
        $userPhone = $this->getUserPhone($userId);

        // Send the SMS (placeholder for actual SMS logic).
        echo "Sent OTP {$otp} to phone: {$userPhone}";
    }

    private function getUserPhone($userId): string
    {
        // Fetch user phone from the database. Placeholder for actual implementation.
        return "+123456789";
    }
}
