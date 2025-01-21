<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;

class InstallCommand implements CommandInterface
{
    public function getName(): string
    {
        return "install";
    }

    public function getDescription(): string
    {
        return "Installs dependencies and sets up the framework.";
    }

    public function execute(array $arguments = []): void
    {
        echo "Running installation steps...\n";

        // Check and run Composer
        if (file_exists(BASE_PATH . "/composer.json")) {
            echo "Installing Composer dependencies...\n";
            passthru("composer install");
        } else {
            echo "No composer.json found. Skipping Composer installation.\n";
        }

        // Set up writable permissions
        $writableDirs = [BASE_PATH . "/storage", BASE_PATH . "/logs"];

        foreach ($writableDirs as $dir) {
            if (is_dir($dir)) {
                chmod($dir, 0777);
                echo "Set permissions for {$dir}.\n";
            }
        }

        echo "Installation complete.\n";
    }
}
