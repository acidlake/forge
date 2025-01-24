<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\ViewInterface;
use Base\Interfaces\SyntaxHandlerInterface;

/**
 * IncludeHandler processes `{#include}` syntax for including and rendering templates.
 *
 * Allows including other templates within a template using `{#include "view.path" (params)}` syntax.
 * Parameters can be dynamically evaluated and passed to the included template.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class IncludeHandler implements SyntaxHandlerInterface
{
    /**
     * The view renderer instance for rendering included templates.
     *
     * @var ViewInterface
     */
    private ViewInterface $view;

    /**
     * Constructor for IncludeHandler.
     *
     * @param ViewInterface $view The view renderer instance used for rendering included templates.
     */
    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
    }

    /**
     * Process the template content to handle `{#include}` syntax.
     *
     * Matches `{#include "view.path" (params)}` syntax and replaces it with the rendered output
     * of the specified template, passing the evaluated parameters.
     *
     * @param string $template The template content to process.
     * @param array  $data     An associative array of dynamic data for template rendering.
     *
     * @return string The processed template content with includes rendered.
     */
    public function process(string $template, array $data): string
    {
        $pattern = '/\{#include\s+"([\w\.]+)"(?:\s*\((.*?)\))?\}/';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($data) {
                $viewPath = $matches[1];
                $params = [];

                if (!empty($matches[2])) {
                    $params = $this->parseParams($matches[2], $data);
                }

                return $this->view->render($viewPath, $params);
            },
            $template
        );
    }

    /**
     * Parse the parameter string from the include syntax.
     *
     * Evaluates parameters dynamically, resolving variables from the provided data.
     *
     * @param string $paramString The parameter string to evaluate.
     * @param array  $data        An associative array of dynamic data for resolving variables.
     *
     * @throws \RuntimeException If the parameter string is invalid or cannot be parsed into an array.
     *
     * @return array The evaluated parameters as an associative array.
     */
    private function parseParams(string $paramString, array $data): array
    {
        $paramString = preg_replace_callback(
            '/\$(\w+)/',
            function ($varMatches) use ($data) {
                $varName = $varMatches[1];
                return $data[$varName] ?? "null";
            },
            $paramString
        );

        $parsedParams = eval("return [$paramString];");

        if (!is_array($parsedParams)) {
            throw new \RuntimeException(
                "Invalid parameters in include statement: $paramString"
            );
        }

        return $parsedParams;
    }
}
