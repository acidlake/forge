<?php
namespace Base\Providers;

use Base\Core\Container;
use Base\Core\ContainerAwareTrait;
use Base\Helpers\EnvHelper;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\ProviderInterface;
use Base\Storage\Drivers\DatabaseStorageDriver;
use Base\Storage\Drivers\FileStorageDriver;
use Base\Interfaces\StorageManagerInterface;
use PDO;

class StorageServiceProvider implements ProviderInterface
{
    use ContainerAwareTrait;

    public function register(Container $container): void
    {
        $container->bind(StorageManagerInterface::class, function () {
            $configHelper = $this->resolve(ConfigHelperInterface::class);
            $configSession = $configHelper::get("storage.session");
            $driver = $configSession["driver"];

            switch ($driver) {
                case "redis":
                    // Implement Redis storage driver here when ready
                    throw new \RuntimeException(
                        "Redis storage driver not implemented yet."
                    );

                case "database":
                    $pdo = new PDO(
                        EnvHelper::get("DB_DSN"),
                        EnvHelper::get("DB_USERNAME"),
                        EnvHelper::get("DB_PASSWORD")
                    );
                    return new DatabaseStorageDriver($pdo);

                default:
                    return new FileStorageDriver($configSession["path"]);
            }
        });
    }
}
