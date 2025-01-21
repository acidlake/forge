<?php

namespace Base\Templates;

/**
 * TemplateEngine interface defines the contract for rendering templates.
 */
interface TemplateEngine
{
    /**
     * Render a template with the provided data.
     *
     * @param string $template The template file path (e.g., "home.index").
     * @param array $data The data to be passed to the template.
     * @return string The rendered template as a string.
     */
    public function render(string $template, array $data = []): string;
}
