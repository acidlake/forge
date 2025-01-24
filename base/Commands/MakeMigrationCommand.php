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

        // Normalize namespace
        $namespace = $this->resolveNamespace($migrationPath);

        // Ensure the directory exists
        if (!is_dir($migrationPath)) {
            mkdir($migrationPath, 0755, true);
        }

        // Generate file name and class name
        $timestamp = date("YmdHis");
        $className = $this->getClassName($migrationName);
        $fileName = "{$timestamp}_{$migrationName}.php";
        $filePath =
            rtrim($migrationPath, DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR .
            $fileName;

        // Derive table name
        $tableName = $this->getTableName($migrationName);

        // Define the migration template
        $template = <<<PHP
<?php

namespace $namespace;

use Base\Core\MigrationBuilder;
use Base\Core\Blueprint;

class {$className}
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
        MigrationBuilder::dropIfExists('$tableName');
    }
}
PHP;

        // Write the migration file
        file_put_contents($filePath, $template);
        echo "Migration created: {$filePath}\n";
    }

    private function getClassName(string $migrationName): string
    {
        return str_replace(
            " ",
            "",
            ucwords(str_replace("_", " ", $migrationName))
        );
    }

    private function getTableName(string $migrationName): string
    {
        $name = preg_replace('/^create_|_table$/i', "", $migrationName);
        return strtolower(str_replace("_", "_", $name)); // Retain underscores
    }

    private function resolveNamespace(string $path): string
    {
        $baseNamespace = "App";
        $relativePath = str_replace(BASE_PATH, "", $path);

        // Remove leading or trailing slashes and normalize path
        $relativePath = trim(str_replace("/", "\\", $relativePath), "\\");

        // Ensure 'app' is not repeated
        $relativePath = preg_replace("/^app\\\\/i", "", $relativePath);

        return "{$baseNamespace}\\{$relativePath}";
    }
}
