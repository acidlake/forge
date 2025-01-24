<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Tools\ConfigHelper;
use Base\Helpers\PathHelper;
use Base\Helpers\StringHelper;

class MakeMigrationCommand implements CommandInterface
{
    public function getName(): string
    {
        return "make:migration";
    }

    public function getDescription(): string
    {
        return "Create a new migration file.";
    }

    public function execute(array $arguments = []): void
    {
        $migrationName = $arguments[0] ?? null;

        if (!$migrationName) {
            echo "Error: Migration name is required.\n";
            return;
        }

        // Determine the project structure type
        $structureType = ConfigHelper::get("structure.type", "default");
        $migrationPath = ConfigHelper::get(
            "structure.paths.{$structureType}.migrations",
            ConfigHelper::get("structure.paths.default.migrations")
        );

        // Ensure the directory exists
        if (!is_dir($migrationPath)) {
            mkdir($migrationPath, 0755, true);
        }

        // Resolve namespace based on migration path
        $baseNamespace = ConfigHelper::get(
            "structure.namespaces.{$structureType}.migrations",
            "App\\Database\\Migrations"
        );
        $namespace = rtrim($baseNamespace ?? "App\\Database\\Migrations", "\\");

        $tableName = StringHelper::toSnakeCase(
            str_replace("Create", "", $migrationName)
        );

        // Create the migration file
        $timestamp = date("YmdHis");
        $className = ucfirst($migrationName); // Ensure the class name starts with a letter
        $fileName = "{$timestamp}_{$migrationName}.php";
        $filePath = PathHelper::normalize("{$migrationPath}/{$fileName}");

        $template = <<<PHP
<?php

namespace $namespace;

use Base\Core\MigrationBuilder;
use Base\Core\Blueprint;

class Create{$className}
{
    public function up(): void
    {
        MigrationBuilder::create('$tableName', function (Blueprint \$table) {
            \$table->autoIncrement("id");
            \$table->string('column_name', 255);
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        MigrationBuilder::dropIfExists("$tableName");
    }
}
PHP;

        if (file_exists($filePath)) {
            echo "Error: Migration {$className} already exists at {$filePath}.\n";
            return;
        }

        file_put_contents($filePath, $template);
        echo "Migration created: {$filePath}\n";
    }
}
