<?php
namespace Base\Interfaces;

interface OTPDeliveryAdapterInterface
{
    /**
     * Send an OTP to the user.
     *
     * @param int|string $userId
     * @param string $otp
     * @return void
     */
    public function send(string $userId, string $otp): void;
}
