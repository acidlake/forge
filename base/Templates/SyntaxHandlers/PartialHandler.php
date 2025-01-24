<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * PartialHandler processes `{#include}` syntax for including and rendering partial templates.
 *
 * Handles `{#include "template.path" with {params}}` syntax to include partial templates,
 * allowing dynamic parameters to be passed.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @autor Jeremias
 * @copyright 2025
 */
class PartialHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{#include}` syntax for partials.
     *
     * Matches `{#include "template.path" with {params}}` syntax and replaces it with the rendered
     * output of the specified partial template, passing the resolved parameters.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering.
     *
     * @throws \RuntimeException If the specified partial template file does not exist.
     *
     * @return string The processed template content with partials rendered.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            '/\{#include\s+"([\w\.]+)"(?:\s+with\s+({.*?}))?\}/',
            function ($matches) use ($data) {
                $template = $matches[1];
                $parameters = $matches[2] ?? "{}";

                // Decode parameters into an array
                $parameters = json_decode($parameters, true) ?: [];
                $parameters = array_merge($data, $parameters);

                // Resolve template path
                $templatePath = str_replace(".", "/", $template) . ".php";
                $fullPath = VIEW_PATH . $templatePath;

                if (!file_exists($fullPath)) {
                    throw new \RuntimeException(
                        "Partial not found: {$templatePath}"
                    );
                }

                // Extract variables and render the partial
                extract($parameters);
                ob_start();
                include $fullPath;
                return ob_get_clean();
            },
            $content
        );
    }
}
