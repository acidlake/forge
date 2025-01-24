<?php

namespace Base\Interfaces;

/**
 * Interface ViewInterface
 *
 * Defines the contract for rendering view templates in the Forge framework.
 * Provides a method for rendering templates with dynamic data.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
interface ViewInterface
{
    /**
     * Render a view template with the provided data.
     *
     * @param string $template The template file to render (e.g., 'home.index').
     * @param array  $data     An associative array of data to pass to the template.
     *
     * @return string The rendered output as a string.
     */
    public function render(string $template, array $data = []): string;
}
