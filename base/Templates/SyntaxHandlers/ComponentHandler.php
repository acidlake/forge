<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Templates\SyntaxHandlerInterface;

class ComponentHandler implements SyntaxHandlerInterface
{
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
