<?php
namespace Base\Providers;

use Base\Commands\GenerateOTPCommand;
use Base\Commands\HelpCommand;
use Base\Commands\MigrateCommand;
use Base\Commands\MigrateRollbackCommand;
use Base\Commands\RouteListCommand;
use Base\Commands\SeedCommand;
use Base\Core\Container;
use Base\Core\CLI;
use Base\Core\MigrationManager;
use Base\Core\SeederManager;
use Base\Interfaces\CommandInterface;
use Base\Interfaces\OTPManagerInterface;
use Base\Interfaces\ProviderInterface;
use Base\Interfaces\RouterInterface;

class CommandServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(CLI::class, function ($container) {
            static $cli = null;
            if ($cli === null) {
                $cli = new CLI($container);
            }
            return $cli;
        });

        $cli = $container->resolve(CLI::class);

        // Manually register HelpCommand
        $helpCommand = new HelpCommand($cli);
        $container->bind(HelpCommand::class, fn() => $helpCommand);
        $cli->registerCommand($helpCommand);

        // Manually register MigrateCommand
        $migrateCommand = new MigrateCommand(
            $container->resolve(MigrationManager::class)
        );
        $container->bind(MigrateCommand::class, fn() => $migrateCommand);
        $cli->registerCommand($migrateCommand);

        // Register MigrationRollBackCommand
        $migrateRollBackCommnad = new MigrateRollbackCommand(
            $container->resolve(MigrationManager::class)
        );
        $cli->registerCommand($migrateRollBackCommnad);

        // Register SeedCommand
        $seedCommand = new SeedCommand(
            $container->resolve(SeederManager::class)
        );
        $container->bind(SeedCommand::class, fn() => $seedCommand);
        $cli->registerCommand($seedCommand);

        // Register GenerateOTPCommand
        $container->bind(GenerateOTPCommand::class, function ($container) {
            return new GenerateOTPCommand(
                $container->resolve(OTPManagerInterface::class)
            );
        });
        $otpCommand = $container->resolve(GenerateOTPCommand::class);
        $cli->registerCommand($otpCommand);

        $router = $container->resolve(RouterInterface::class);
        $routeListCommand = new RouteListCommand($router);
        $container->bind(RouteListCommand::class, fn() => $routeListCommand);
        $cli->registerCommand($routeListCommand);

        // Auto-register other commands
        $this->loadCommands(
            $container,
            BASE_PATH . "/base/Commands",
            "Base\\Commands\\"
        );
        $this->loadCommands(
            $container,
            BASE_PATH . "/app/Commands",
            "App\\Commands\\"
        );

        // Register all loaded commands to CLI
        foreach ($container->getAllBindings() as $abstract => $binding) {
            if (is_subclass_of($abstract, CommandInterface::class)) {
                $command = $container->resolve($abstract);
                if (!isset($cli->getCommands()[$command->getName()])) {
                    $cli->registerCommand($command);
                }
            }
        }
    }

    private function loadCommands(
        Container $container,
        string $path,
        string $namespace
    ): void {
        if (!is_dir($path)) {
            return;
        }

        foreach (glob("{$path}/*.php") as $file) {
            $className = "{$namespace}" . basename($file, ".php");

            if (
                class_exists($className) &&
                is_subclass_of($className, CommandInterface::class)
            ) {
                if (!$container->has($className)) {
                    $container->bind(
                        $className,
                        fn($container) => $this->resolveCommand(
                            $container,
                            $className
                        )
                    );

                    $cli = $container->resolve(CLI::class);
                    $command = $container->resolve($className);
                    $cli->registerCommand($command);
                }
            }
        }
    }

    private function resolveCommand(Container $container, string $className)
    {
        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $className();
        }

        $dependencies = array_map(
            fn($param) => $container->resolve($param->getType()->getName()),
            $constructor->getParameters()
        );

        return $reflection->newInstanceArgs($dependencies);
    }
}
