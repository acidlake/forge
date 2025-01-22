<?php
namespace Base\Interfaces;

interface OTPManagerInterface
{
    /**
     * Generate a new OTP for a user.
     *
     * @param int|string $userId
     * @return string The generated OTP.
     */
    public function generateOTP(string $userId): string;

    /**
     * Validate an OTP for a user.
     *
     * @param int|string $userId
     * @param string $otp
     * @return bool True if valid, false otherwise.
     */
    public function validateOTP(string $userId, string $otp): bool;

    /**
     * Send the OTP to the user.
     *
     * @param int|string $userId
     * @param string $otp
     * @return void
     */
    public function sendOTP(string $userId, string $otp): void;
}
