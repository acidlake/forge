<?php
namespace Base\Providers;

use Base\Core\Container;
use Base\Interfaces\CommandInterface;
use Base\Interfaces\ProviderInterface;
use ReflectionClass;

class CommandLoaderServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        // Load base commands
        $this->loadCommands(
            $container,
            BASE_PATH . "/base/Commands",
            "Base\\Commands\\"
        );

        // Load application commands
        $this->loadCommands(
            $container,
            BASE_PATH . "/app/Commands",
            "App\\Commands\\"
        );
    }

    private function loadCommands(
        Container $container,
        string $directory,
        string $namespace
    ): void {
        if (!is_dir($directory)) {
            return;
        }

        foreach (glob("{$directory}/*.php") as $file) {
            $className = "{$namespace}" . basename($file, ".php");

            // Skip if command is already registered
            if ($container->has($className)) {
                continue;
            }

            // Validate and bind commands
            if (
                class_exists($className) &&
                is_subclass_of($className, CommandInterface::class)
            ) {
                $reflection = new ReflectionClass($className);
                $constructor = $reflection->getConstructor();

                // Bind commands without dependencies
                if (
                    !$constructor ||
                    $constructor->getNumberOfParameters() === 0
                ) {
                    $container->bind($className, fn() => new $className());
                } else {
                    // Bind commands with dependencies
                    $container->bind($className, function ($container) use (
                        $reflection
                    ) {
                        $dependencies = array_map(
                            fn($param) => $container->resolve(
                                $param->getType()->getName()
                            ),
                            $reflection->getConstructor()->getParameters()
                        );
                        return $reflection->newInstanceArgs($dependencies);
                    });
                }
            }
        }
    }
}
