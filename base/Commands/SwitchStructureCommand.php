<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Tools\ConfigHelper;

class SwitchStructureCommand implements CommandInterface
{
    public function getName(): string
    {
        return "forge:structure";
    }

    public function getDescription(): string
    {
        return "Switch the project structure and scaffold initial directories.";
    }

    public function execute(array $args = []): void
    {
        $structureType = $args[0] ?? null;

        if (!$structureType) {
            echo "Error: Structure type is required.\n";
            return;
        }

        $availableStructures = ConfigHelper::get("structure.types");
        if (!in_array($structureType, $availableStructures, true)) {
            echo "Error: Invalid structure type.\n";
            return;
        }

        $paths = ConfigHelper::get("structure.paths.{$structureType}");

        foreach ($paths as $type => $relativePath) {
            $fullPath = BASE_PATH . "/$relativePath";

            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
                echo "Created $type directory: $fullPath\n";
            }
        }

        echo "Project structure switched to $structureType.\n";
    }
}
