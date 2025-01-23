<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
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

    public function execute(array $arguments = []): void
    {
        $modelName = $arguments[0] ?? null;

        if (!$modelName) {
            echo "Error: Model name is required.\n";
            return;
        }

        // Get model path from configuration
        $config = ConfigHelper::get("structure.models", "app/Models");
        $directory = BASE_PATH . "/" . $config;

        // Ensure the directory exists
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = "{$directory}/{$modelName}.php";
        $namespace = str_replace("/", "\\", $config);

        $template = <<<PHP
<?php

namespace {$namespace};

use Base\Core\BaseModel;

class {$modelName} extends BaseModel
{
    /**
     * Table name associated with the model.
     *
     * @var string
     */
    protected string \$table = 'table_name';

    /**
     * Indicates if UUID should be used as the primary key.
     *
     * @var bool
     */
    protected bool \$uuid = false;

    /**
     * Fillable fields for mass assignment.
     *
     * @var array
     */
    protected array \$fillable = [
        // Add your fillable fields here.
    ];
}
PHP;

        file_put_contents($filePath, $template);
        echo "Model created: {$filePath}\n";
    }
}
