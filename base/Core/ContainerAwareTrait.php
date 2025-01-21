<?php

namespace Base\Core;

trait ContainerAwareTrait
{
    protected function resolve(string $class)
    {
        return ContainerHelper::getContainer()->resolve($class);
    }
}
