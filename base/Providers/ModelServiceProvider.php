<?php
namespace Base\Providers;

use Base\Core\Container;
use Base\Helpers\ModelSerializerHelper;
use Base\Interfaces\ModelSerializerHelperInterface;
use Base\Interfaces\ProviderInterface;
use Base\ORM\OrmManagerInterface;
use Base\Tools\UuidManager;

class ModelServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(ModelSerializerHelperInterface::class, function () {
            return new ModelSerializerHelper();
        });

        $modelsPath = BASE_PATH . "/app/Models";

        if (!is_dir($modelsPath)) {
            return;
        }

        foreach (glob("{$modelsPath}/*.php") as $file) {
            $className = "App\\Models\\" . basename($file, ".php");

            if (
                class_exists($className) &&
                is_subclass_of($className, \Base\ORM\BaseModelInterface::class)
            ) {
                $container->bind($className, function () use (
                    $container,
                    $className
                ) {
                    $orm = $container->resolve(OrmManagerInterface::class);
                    $uuidManager = $container->resolve(UuidManager::class);

                    return new $className($orm, $uuidManager);
                });
            }
        }
    }
}
