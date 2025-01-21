<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

class PartialHandler implements SyntaxHandlerInterface
{
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
