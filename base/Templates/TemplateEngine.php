<?php

namespace Base\Templates;

/**
 * TemplateEngine interface
 *
 * Defines the contract for rendering templates within the Forge framework.
 * Any class implementing this interface must provide a method for rendering templates
 * with support for passing dynamic data.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
interface TemplateEngine
{
    /**
     * Render a template with the provided data.
     *
     * @param string $template The template file path (e.g., "home.index").
     *                         Dot notation will be resolved to file paths.
     * @param array  $data     An associative array of data to be passed to the template.
     *
     * @return string The rendered template as a string.
     */
    public function render(string $template, array $data = []): string;
}
