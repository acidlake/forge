<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * ComponentHandler processes and renders custom components in templates.
 *
 * Supports self-closing components (e.g., `<include-component />`) and
 * components with slots (e.g., `<include-component>...</include-component>`).
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class ComponentHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to replace custom component syntax with rendered output.
     *
     * Handles both self-closing components and components with slots.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for component rendering.
     *
     * @return string The processed template content with components rendered.
     */
    public function process(string $content, array $data): string
    {
        // Match self-closing components (e.g., <include-... />)
        $content = preg_replace_callback(
            "/<include-([\w\.]+)([^>]*)\s*\/>/",
            function ($matches) use ($data) {
                return $this->renderComponent($matches[1], $matches[2], $data);
            },
            $content
        );

        // Match components with slots (e.g., <include-...>...</include-...>)
        $content = preg_replace_callback(
            '/<include-([\w\.]+)([^>]*)>(.*?)<\/include-\1>/s',
            function ($matches) use ($data) {
                return $this->renderComponent(
                    $matches[1],
                    $matches[2],
                    $data,
                    $matches[3]
                );
            },
            $content
        );

        return $content;
    }

    /**
     * Render a component by including its corresponding template file.
     *
     * Parses attributes, merges with provided data, and includes the component template file.
     *
     * @param string $component  The component name in dot notation (e.g., 'components.header').
     * @param string $attributes The attributes string from the component tag.
     * @param array  $data       An associative array of dynamic data for component rendering.
     * @param string $slot       The inner content (slot) for the component (optional).
     *
     * @throws \RuntimeException If the component file does not exist.
     *
     * @return string The rendered output of the component.
     */
    private function renderComponent(
        string $component,
        string $attributes,
        array $data,
        string $slot = ""
    ): string {
        // Parse attributes into an associative array
        preg_match_all(
            '/(\w+)=["\']([^"\']+)["\']/',
            $attributes,
            $matches,
            PREG_SET_ORDER
        );
        $params = [];
        foreach ($matches as $match) {
            $params[$match[1]] = $match[2];
        }

        // Merge with data and add $slot
        $params = array_merge($data, $params, ["slot" => $slot]);

        // Resolve component file path
        $componentPath = str_replace(".", "/", $component);
        $fullPath = VIEW_PATH . "/" . $componentPath . ".php";

        if (!file_exists($fullPath)) {
            throw new \RuntimeException(
                "Component not found: {$componentPath}"
            );
        }

        // Extract variables for the component
        extract($params);

        // Render the component
        ob_start();
        include $fullPath;
        return ob_get_clean();
    }
}
