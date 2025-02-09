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

    public function execute(array $args = []): void
    {
        // Ensure the controller name is provided
        $controllerName = $args[0] ?? null;

        if (!$controllerName) {
            echo "Error: Controller name is required.\n";
            return;
        }

        $structureType = ConfigHelper::get("structure.type", "default");
        $controllerPath = ConfigHelper::get(
            "structure.paths.{$structureType}.controllers",
            ConfigHelper::get("structure.paths.default.controllers")
        );

        $namespace = str_replace("/", "\\", $controllerPath);
        $path = BASE_PATH . "/$controllerPath/$controllerName.php";

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        if (file_exists($path)) {
            echo "The controller $controllerName already exists at $path.\n";
            return;
        }
    }

    private function getControllerContent(
        string $controllerName,
        string $namespace,
        bool $isResource
    ): string {
        $methods = $isResource
            ? $this->getResourceMethods()
            : $this->getDefaultIndexMethod();

        return <<<PHP
<?php

namespace $namespace;

use Base\Controllers\BaseApiController;
use Base\Interfaces\RequestInterface as Request;
use Base\Interfaces\ViewInterface;

class $controllerName extends BaseApiController
{
    $methods
}
PHP;
    }

    private function getDefaultIndexMethod(): string
    {
        return <<<PHP
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
PHP;
    }

    private function getResourceMethods(): string
    {
        return <<<PHP
    /**
     * Display a listing of the resource.
     *
     * @param ViewInterface \$view
     * @return string
     */
    public function index(ViewInterface \$view): string
    {
        return \$view->render('default.index', [
            'title' => 'Resource Index',
            'message' => 'This is a resource index method.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request \$request
     * @return array
     */
    public function store(Request \$request): array
    {
        // TODO: Implement store method
        return [];
    }

    /**
     * Display the specified resource.
     *
     * @param string \$id
     * @return array
     */
    public function show(string \$id): array
    {
        // TODO: Implement show method
        return [];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request \$request
     * @param string \$id
     * @return array
     */
    public function update(Request \$request, string \$id): array
    {
        // TODO: Implement update method
        return [];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string \$id
     * @return bool
     */
    public function destroy(string \$id): bool
    {
        // TODO: Implement destroy method
        return false;
    }
PHP;
    }
}
