<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Tools\ConfigHelper;

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

        // Create the migration file
        $timestamp = date("YmdHis");
        $className = ucfirst($migrationName);
        $fileName = "{$timestamp}_{$migrationName}.php";
        $filePath = "{$migrationPath}/{$fileName}";

        $template = <<<PHP
<?php

use Base\Core\Blueprint;
use Base\Core\Migration;

class {$className} extends Migration
{
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint \$table) {
            \$table->id();
            \$table->string('column_name');
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('table_name');
    }
}
PHP;

        file_put_contents($filePath, $template);
        echo "Migration created: {$filePath}\n";
    }
}
