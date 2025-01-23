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

        // Get model path from configuration
        $config = ConfigHelper::get(
            "structure.migrations",
            "app/Database/Migrations"
        );
        $directory = BASE_PATH . "/" . $config;

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $timestamp = date("YmdHis");
        $className = ucfirst($migrationName);
        $fileName = "{$timestamp}_{$migrationName}.php";
        $filePath = "{$directory}/{$migrationName}.php";
        $namespace = str_replace("/", "\\", $config);

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
