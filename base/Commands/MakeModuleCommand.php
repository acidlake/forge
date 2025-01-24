<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Tools\ConfigHelper;

class MakeModuleCommand implements CommandInterface
{
    public function getName(): string
    {
        return "make:module";
    }

    public function getDescription(): string
    {
        return "Generate a new module with the predefined structure.";
    }

    public function execute(array $args = []): void
    {
        $moduleName = $args[0] ?? null;

        if (!$moduleName) {
            echo "Error: Module name is required.\n";
            return;
        }

        $structureType = ConfigHelper::get("structure.type", "modular");
        $paths = ConfigHelper::get("structure.paths.{$structureType}.modules");

        foreach ($paths as $type => $relativePath) {
            $fullPath = BASE_PATH . "/$relativePath/$moduleName";

            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
                echo "Created $type directory: $fullPath\n";
            }
        }

        echo "Module $moduleName created successfully.\n";
    }
}
