<?php
namespace Base\Authentication\OTP;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\OTPDeliveryAdapterInterface;
use Base\Interfaces\OTPManagerInterface;

class OTPManager implements OTPManagerInterface
{
    use ContainerAwareTrait;

    private string $deliveryMethod;

    public function __construct(array $config)
    {
        $this->deliveryMethod = $config["otp"]["delivery"];
    }

    public function generateOTP(string $userId): string
    {
        /**
         * @var ConfigHelperInterface $config
         */
        $config = $this->resolve(ConfigHelperInterface::class);
        $length = $config::get("auth.otp.length");

        $otp = str_pad(
            random_int(0, pow(10, $length) - 1),
            $length,
            "0",
            STR_PAD_LEFT
        );

        return $otp;
    }

    public function validateOTP(string $userId, string $otp): bool
    {
        // TODO: Pending implementation
        // Validate against stored OTP in secure storage (e.g., cache or database)
        return true; // For simplicity
    }

    public function sendOTP(string $userId, string $otp): void
    {
        $this->resolveAdapter($userId, $otp);
    }

    private function resolveAdapter(string $userId, string $otp): void
    {
        // Normalize the delivery method (convert snake_case or custom strings to PascalCase)
        $formattedDeliveryMethod = str_replace(
            " ",
            "",
            ucwords(str_replace("_", " ", $this->deliveryMethod))
        );

        // Build the fully qualified class name dynamically
        $deliveryClass = "Base\\Authentication\\OTP\\Adapters\\{$formattedDeliveryMethod}Adapter";

        // Check if the adapter class exists
        if (!class_exists($deliveryClass)) {
            throw new \RuntimeException(
                "Delivery method '{$this->deliveryMethod}' is not supported."
            );
        }

        // Ensure the adapter implements a common interface (e.g., DeliveryAdapterInterface)
        if (
            !is_subclass_of($deliveryClass, OTPDeliveryAdapterInterface::class)
        ) {
            throw new \RuntimeException(
                "Adapter '{$deliveryClass}' must implement OTPDeliveryAdapterInterface."
            );
        }

        // Instantiate the adapter and send the OTP
        $adapter = new $deliveryClass();
        $adapter->send($userId, $otp);
    }

    private function storeOTP($userId, string $otp): void
    {
        // Store OTP with expiration (e.g., in a cache or database).
        // This is a placeholder. Actual implementation depends on your storage strategy.
    }

    private function retrieveOTP($userId): ?string
    {
        // Retrieve OTP from storage. Placeholder for actual implementation.
        return null;
    }
}
