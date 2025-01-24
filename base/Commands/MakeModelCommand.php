<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Helpers\StringHelper;
use Base\Tools\ConfigHelper;

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

        $structureType = ConfigHelper::get("structure.type", "default");
        $modelPath = ConfigHelper::get(
            "structure.paths.{$structureType}.models",
            ConfigHelper::get("structure.paths.default.models")
        );

        $namespace = str_replace("/", "\\", $modelPath);
        $path = BASE_PATH . "/$modelPath/$modelName.php";

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        if (file_exists($path)) {
            echo "The model $modelName already exists at $path.\n";
            return;
        }
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
