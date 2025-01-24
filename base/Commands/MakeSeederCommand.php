<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Tools\ConfigHelper;

class MakeSeederCommand implements CommandInterface
{
    public function getName(): string
    {
        return "make:seeder";
    }

    public function getDescription(): string
    {
        return "Create a new seeder class.";
    }

    public function execute(array $args = []): void
    {
        // Ensure the seeder name is provided
        $seederName = $args[0] ?? null;

        if (!$seederName) {
            echo "Error: Seeder name is required.\n";
            return;
        }

        // Determine the project structure type and seeder path
        $structureType = ConfigHelper::get("structure.type", "default");
        $seederPath = ConfigHelper::get(
            "structure.paths.{$structureType}.seeders",
            ConfigHelper::get("structure.paths.default.seeders")
        );

        // Normalize namespace
        $namespace = $this->resolveNamespace($seederPath);

        // Ensure the directory exists
        if (!is_dir($seederPath)) {
            mkdir($seederPath, 0755, true);
        }

        // Derive file path
        $filePath =
            rtrim($seederPath, DIRECTORY_SEPARATOR) .
            DIRECTORY_SEPARATOR .
            "{$seederName}.php";

        if (file_exists($filePath)) {
            echo "The seeder {$seederName} already exists at {$filePath}.\n";
            return;
        }

        // Generate the seeder template
        $content = $this->getSeederContent($seederName, $namespace);

        // Write the seeder file
        file_put_contents($filePath, $content);
        echo "Seeder created: {$filePath}\n";
    }

    private function getSeederContent(
        string $seederName,
        string $namespace
    ): string {
        $modelName = str_replace("Seeder", "", $seederName);

        return <<<PHP
<?php

namespace $namespace;

use Base\Interfaces\SeederInterface;
use Base\Database\DatabaseAdapterInterface;
use App\Models\\$modelName;

class $seederName implements SeederInterface
{
    public function __construct(protected DatabaseAdapterInterface \$db) {}

    public function run(): void
    {
        echo "Seeding $modelName data...\n";

        $modelName::createMany([
            [
                "uuid" => $modelName::generateUUID(),
                "name" => "Sample Name 1",
                "email" => "sample1@example.com",
                "role" => "admin",
            ],
            [
                "uuid" => $modelName::generateUUID(),
                "name" => "Sample Name 2",
                "email" => "sample2@example.com",
                "role" => "user",
            ],
        ]);
    }

    public function rollback(): void
    {
        echo "Rolling back $modelName data...\n";

        $modelName::whereIn("email", [
            "sample1@example.com",
            "sample2@example.com",
        ])->delete();
    }
}
PHP;
    }

    private function resolveNamespace(string $path): string
    {
        $baseNamespace = "App";
        $relativePath = str_replace(BASE_PATH, "", $path);

        // Remove leading/trailing slashes and normalize path
        $relativePath = trim(str_replace("/", "\\", $relativePath), "\\");

        // Ensure 'app' is not repeated in the namespace
        $relativePath = preg_replace("/^app\\\\/i", "", $relativePath);

        return "{$baseNamespace}\\{$relativePath}";
    }
}
