<?php
namespace Base\Commands;

use Base\Helpers\EnvHelper;
use Base\Interfaces\CommandInterface;

class GenerateSecretKeyCommand implements CommandInterface
{
    public function getName(): string
    {
        return "auth:generate-secret";
    }

    public function getDescription(): string
    {
        return "Generate and set a new secret key in the .env file.";
    }

    public function execute(array $arguments = []): void
    {
        $secret = bin2hex(random_bytes(32)); // 256-bit secret key
        $envHelper = new EnvHelper();

        if ($envHelper->set("JWT_SECRET", $secret)) {
            echo "Secret key generated and added to .env file.\n";
        } else {
            echo "Failed to update the .env file. Please ensure it is writable.\n";
        }

        echo "Your new secret key is: {$secret}\n";
    }
}
