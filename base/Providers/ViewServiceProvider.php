<?php

namespace Base\Providers;

use Base\Interfaces\ProviderInterface;
use Base\Templates\ViewEngine;
use Base\Templates\View;
use Base\Core\Container;

class ViewServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(View::class, function () {
            return new ViewEngine(VIEW_PATH);
        });
    }
}
