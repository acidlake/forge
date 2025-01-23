<?php
namespace Base\Interfaces;

use Base\Core\Container;

interface ProviderInterface
{
    public function register(Container $container): void;
}
