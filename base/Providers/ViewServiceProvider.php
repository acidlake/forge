<?php

namespace Base\Providers;

use Base\Interfaces\ProviderInterface;
use Base\Templates\DefaultViewEngine;
use Base\Interfaces\ViewInterface;
use Base\Core\Container;

class ViewServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(ViewInterface::class, function () {
            return new DefaultViewEngine(VIEW_PATH);
        });
    }
}
