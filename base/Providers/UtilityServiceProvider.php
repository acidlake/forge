<?php
namespace Base\Providers;

use Base\Core\Container;
use Base\Helpers\EnvValueParser;
use Base\Helpers\KeyGenerator;
use Base\Interfaces\EnvValueParserInterface;
use Base\Interfaces\KeyGeneratorInterface;
use Base\Interfaces\ProviderInterface;

class UtilityServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(EnvValueParserInterface::class, function () {
            return new EnvValueParser();
        });

        $container->bind(KeyGeneratorInterface::class, function () {
            return new KeyGenerator();
        });
    }
}
