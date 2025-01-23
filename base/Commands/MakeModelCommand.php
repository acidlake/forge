<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Helpers\StringHelper;

class MakeModelCommand implements CommandInterface
{
    public function getName(): string
    {
        return "make:model";
    }

    public function getDescription(): string
    {
        return "Create a new model class.";
    }

    public function execute(array $args = []): void
    {
        // Ensure the model name is provided
        $modelName = $args[0] ?? null;

        if (!$modelName) {
            echo "Error: Model name is required.\n";
            return;
        }

        $modelName = ucfirst($modelName); // Capitalize the first letter
        $namespace = $args[1] ?? "App\\Models"; // Default namespace
        $path =
            BASE_PATH .
            "/" .
            str_replace("\\", "/", $namespace) .
            "/$modelName.php";

        // Ensure the directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Generate the model content
        $modelContent = $this->getModelContent($modelName, $namespace);

        // Check if the file already exists
        if (file_exists($path)) {
            echo "The model $modelName already exists at $path. \n";
            return;
        }

        // Write the model to the file
        file_put_contents($path, $modelContent);

        echo "Model $modelName created successfully at $path. \n";
    }

    private function getModelContent(
        string $modelName,
        string $namespace
    ): string {
        return <<<PHP
<?php

namespace $namespace;

use Base\ORM\BaseModel;

/**
 * $modelName Model
 *
 * Represents the {$modelName}s table in the database.
 *
 * @property string \$id
 * @property string \$name
 */
class $modelName extends BaseModel
{
    protected string \$table = "{$this->toSnakeCase(
            $modelName
        )}s"; // Table name
    protected bool \$uuid = false; // Set to true if using UUIDs
    protected string \$keyStrategy = "uuidv4"; // UUID strategy (if applicable)
    protected array \$fillable = ["id", "name"]; // Fillable attributes

    public string \$id;
    public string \$name;
}
PHP;
    }

    private function toSnakeCase(string $input): string
    {
        return StringHelper::toSnakeCase($input);
    }
}
