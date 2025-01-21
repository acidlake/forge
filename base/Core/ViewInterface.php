<?php
namespace Base\Core;

interface ViewInterface
{
    /**
     * Render a view template.
     *
     * @param string $template The template file to render.
     * @param array $data The data to pass to the template.
     * @return string The rendered output.
     */
    public function render(string $template, array $data = []): string;
}
