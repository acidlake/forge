<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;

class MakeTraitCommand implements CommandInterface
{
    public function getName(): string
    {
        return "make:trait";
    }

    public function getDescription(): string
    {
        return "Create a new trait class.";
    }

    public function execute(array $args = []): void
    {
        // Ensure the trait name is provided
        $traitName = $args[0] ?? null;

        if (!$traitName) {
            echo "Error: Trait name is required.\n";
            return;
        }

        $traitName = ucfirst($args[0]); // Capitalize the first letter
        $namespace = $args[1] ?? "App\\Traits"; // Default namespace
        $path =
            BASE_PATH .
            "/" .
            str_replace("\\", "/", $namespace) .
            "/$traitName.php";

        // Ensure the directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Generate the trait content
        $traitContent = $this->getTraitContent($traitName, $namespace);

        // Check if the file already exists
        if (file_exists($path)) {
            echo "The trait $traitName already exists at $path. \n";
            return;
        }

        // Write the trait to the file
        file_put_contents($path, $traitContent);

        echo "Trait $traitName created successfully at $path. \n";
    }

    private function getTraitContent(
        string $traitName,
        string $namespace
    ): string {
        return <<<PHP
<?php

namespace $namespace;

/**
 * @method static static resolve() Resolve the instance of the model
 */
trait $traitName
{
    // Add your methods here
}
PHP;
    }
}
