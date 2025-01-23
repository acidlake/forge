<?php
namespace Base\Providers;

use Base\Core\Container;
use Base\Core\SeederManager;
use Base\Database\BaseSchemaBuilder;
use Base\Core\MigrationManager;
use Base\Interfaces\ProviderInterface;
use Base\Database\DatabaseAdapterInterface;
use Base\ORM\DefaultOrmManager;
use Base\ORM\OrmManagerInterface;

class DatabaseServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        // Bind DatabaseAdapterInterface
        $container->bind(DatabaseAdapterInterface::class, function (
            Container $container
        ) {
            $dsn = sprintf(
                "%s:host=%s;dbname=%s",
                getenv("DB_CONNECTION") ?: "mysql",
                getenv("DB_HOST") ?: "127.0.0.1",
                getenv("DB_DATABASE") ?: "forge"
            );

            $pdo = new \PDO(
                $dsn,
                getenv("DB_USERNAME") ?: "root",
                getenv("DB_PASSWORD") ?: "root"
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return new \Base\ORM\DatabaseAdapter($pdo);
        });

        // Bind BaseSchemaBuilder
        $container->bind(BaseSchemaBuilder::class, function ($container) {
            $adapter = $container->resolve(DatabaseAdapterInterface::class);
            return new BaseSchemaBuilder($adapter);
        });

        // Bind MigrationManager
        $container->bind(MigrationManager::class, function ($container) {
            $adapter = $container->resolve(DatabaseAdapterInterface::class);
            $schema = $container->resolve(BaseSchemaBuilder::class);
            return new MigrationManager($adapter, $schema);
        });

        // Bind SeederManager
        $container->bind(SeederManager::class, function (Container $container) {
            $adapter = $container->resolve(DatabaseAdapterInterface::class);
            return new SeederManager($adapter);
        });

        // Bind OrmManagerInterface
        $container->bind(OrmManagerInterface::class, function (
            Container $container
        ) {
            $adapter = $container->resolve(DatabaseAdapterInterface::class);
            return new DefaultOrmManager($adapter);
        });
    }
}
