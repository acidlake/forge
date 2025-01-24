<?php
namespace Base\Authentication\OTP\Adapters;

use Base\Interfaces\OTPDeliveryAdapterInterface;

class GoogleAuthenticatorAdapter implements OTPDeliveryAdapterInterface
{
    public function send(string $userId, string $otp): void
    {
        // TODO: Generate QR Code CODE
        // create a helper to generate QR Code
        // create an encrypt decrypt helper
        // note: Allow to swap the implementations
        echo "Google Authenticator OTP for User {$userId}: {$otp}";
    }
}
