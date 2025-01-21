<?php

namespace Base\Core;

use Base\Core\ContainerHelper;

/**
 * ContainerAwareTrait provides a utility to resolve dependencies using the Forge framework's DI container.
 *
 * This trait can be used in classes that need to dynamically resolve dependencies from the container
 * without explicitly injecting them.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @trait ContainerAwareTrait
 * @author Jeremias
 * @version 1.0.0
 * @copyright 2025
 */
trait ContainerAwareTrait
{
    /**
     * Resolve a class instance from the DI container.
     *
     * This method allows the implementing class to resolve dependencies dynamically
     * using the Forge framework's container helper.
     *
     * @param string $class The fully qualified class name of the dependency to resolve.
     *
     * @return mixed The resolved instance of the class.
     */
    protected function resolve(string $class)
    {
        return ContainerHelper::getContainer()->resolve($class);
    }
}
