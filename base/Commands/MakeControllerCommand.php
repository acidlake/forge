<?php

namespace Base\Commands;

use Base\Interfaces\CommandInterface;
use Base\Tools\ConfigHelper;

class MakeControllerCommand implements CommandInterface
{
    public function getName(): string
    {
        return "make:controller";
    }

    public function getDescription(): string
    {
        return "Create a new controller class.";
    }

    public function execute(array $arguments = []): void
    {
        $controllerName = $arguments[0] ?? null;

        if (!$controllerName) {
            echo "Error: Controller name is required.\n";
            return;
        }

        // Get controller path from configuration
        $config = ConfigHelper::get("structure.controllers", "app/Controllers");
        $directory = BASE_PATH . "/" . $config;

        // Ensure the directory exists
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = "{$directory}/{$controllerName}.php";
        $namespace = str_replace("/", "\\", $config);

        $template = <<<PHP
<?php

namespace {$namespace};

use Base\Interfaces\ViewInterface;

class {$controllerName}
{
    /**
     * Display a listing of the resource.
     *
     * @param ViewInterface \$view
     * @return string
     */
    public function index(ViewInterface \$view): string
    {
        return \$view->render('default.index', [
            'title' => 'Welcome',
            'message' => 'This is a generated controller.',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create(): void
    {
        // Add logic for creating a resource.
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(): void
    {
        // Add logic for storing a resource.
    }

    /**
     * Display the specified resource.
     *
     * @param int \$id
     * @return void
     */
    public function show(int \$id): void
    {
        // Add logic for displaying a specific resource.
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int \$id
     * @return void
     */
    public function edit(int \$id): void
    {
        // Add logic for editing a resource.
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int \$id
     * @return void
     */
    public function update(int \$id): void
    {
        // Add logic for updating a resource.
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int \$id
     * @return void
     */
    public function destroy(int \$id): void
    {
        // Add logic for deleting a resource.
    }
}
PHP;

        file_put_contents($filePath, $template);
        echo "Controller created: {$filePath}\n";
    }
}
