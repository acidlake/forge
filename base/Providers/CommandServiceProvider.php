<?php
namespace Base\Providers;

use Base\Core\Container;
use Base\Core\CLI;
use Base\Database\MigrationManager;
use Base\Core\SeederManager;
use Base\Interfaces\ProviderInterface;

class CommandServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(CLI::class, function ($container) {
            return new CLI($container);
        });

        $container->bind("Base\\Commands\\HelpCommand", function ($container) {
            $cli = $container->resolve(CLI::class);
            return new \Base\Commands\HelpCommand($cli);
        });

        $container->bind("Base\\Commands\\MigrateRollbackCommand", function (
            $container
        ) {
            $migrationManager = $container->resolve(MigrationManager::class);
            return new \Base\Commands\MigrateRollbackCommand($migrationManager);
        });

        $container->bind("Base\\Commands\\ListCommand", function ($container) {
            $cli = $container->resolve(CLI::class);
            return new \Base\Commands\ListCommand($cli);
        });

        $container->bind("Base\\Commands\\MigrateCommand", function (
            $container
        ) {
            $migrationManager = $container->resolve(MigrationManager::class);
            return new \Base\Commands\MigrateCommand($migrationManager);
        });

        $container->bind("Base\\Commands\\SeedCommand", function ($container) {
            $seederManager = $container->resolve(SeederManager::class);
            return new \Base\Commands\SeedCommand($seederManager);
        });
    }
}
