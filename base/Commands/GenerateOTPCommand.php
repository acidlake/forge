<?php
namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Interfaces\OTPManagerInterface;

class GenerateOTPCommand implements CommandInterface
{
    private OTPManagerInterface $otpManager;

    public function __construct(OTPManagerInterface $otpManager)
    {
        $this->otpManager = $otpManager;
    }

    public function getName(): string
    {
        return "otp:generate";
    }

    public function getDescription(): string
    {
        return "Generate and send an OTP for a user.";
    }

    public function execute(array $arguments = []): void
    {
        $userId = $arguments[0] ?? null;

        if (!$userId) {
            echo "Please provide a user ID.\n";
            return;
        }

        $otp = $this->otpManager->generateOTP($userId);
        $this->otpManager->sendOTP($userId, $otp);

        echo "OTP generated and sent to user {$userId}.\n";
    }
}
