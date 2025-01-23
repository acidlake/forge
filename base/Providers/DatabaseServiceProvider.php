<?php
namespace Base\Providers;

use Base\Core\Container;
use Base\Core\SeederManager;
use Base\Database\BaseSchemaBuilder;
use Base\Database\MigrationManager;
use Base\Helpers\EnvHelper;
use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Interfaces\ProviderInterface;
use Base\Interfaces\SchemaBuilderInterface;
use Base\ORM\DatabaseAdapter;
use Base\ORM\OrmManagerInterface;

class DatabaseServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(ORMDatabaseAdapterInterface::class, function () {
            $dsn = sprintf(
                "%s:host=%s;dbname=%s",
                EnvHelper::get("DB_CONNECTION", "mysql"),
                EnvHelper::get("DB_HOST", "127.0.0.1"),
                EnvHelper::get("DB_DATABASE", "forge")
            );

            $pdo = new \PDO(
                $dsn,
                EnvHelper::get("DB_USERNAME", "root"),
                EnvHelper::get("DB_PASSWORD", "")
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return new DatabaseAdapter($pdo);
        });

        $container->bind(SchemaBuilderInterface::class, function ($container) {
            $adapter = $container->resolve(ORMDatabaseAdapterInterface::class);
            return new BaseSchemaBuilder($adapter);
        });

        $container->bind(MigrationManager::class, function ($container) {
            $adapter = $container->resolve(ORMDatabaseAdapterInterface::class);
            return new MigrationManager($adapter);
        });

        $container->bind(SeederManager::class, function ($container) {
            $databaseAdapter = $container->resolve(
                ORMDatabaseAdapterInterface::class
            );
            return new SeederManager($databaseAdapter);
        });

        $container->bind(OrmManagerInterface::class, function () use (
            $container
        ) {
            $databaseAdapter = $container->resolve(
                ORMDatabaseAdapterInterface::class
            );
            return new \Base\ORM\DefaultOrmManager($databaseAdapter);
        });
    }
}
