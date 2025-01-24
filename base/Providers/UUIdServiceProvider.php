<?php

namespace Base\Providers;

use Base\Core\Container;
use Base\Interfaces\ProviderInterface;
use Base\Tools\UuidManager;
use Base\Interfaces\UuidStrategyInterface;

class UUIdServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(UuidManager::class, function () {
            $manager = new UuidManager();

            // Auto-discover user-defined strategies
            foreach (glob(BASE_PATH . "/app/UuidStrategies/*.php") as $file) {
                $className = "App\\UuidStrategies\\" . basename($file, ".php");
                if (
                    class_exists($className) &&
                    is_subclass_of($className, UuidStrategyInterface::class)
                ) {
                    $strategy = new $className();
                    $manager->register($strategy->getName(), $strategy);
                }
            }

            return $manager;
        });
    }
}
