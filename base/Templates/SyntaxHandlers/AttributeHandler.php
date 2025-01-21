<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * AttributeHandler processes custom syntax for dynamic HTML attributes.
 *
 * Replaces custom attribute placeholders in templates with dynamically generated
 * attributes based on the provided data array.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class AttributeHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to replace custom attribute placeholders.
     *
     * Matches syntax like `<tag $variable>` and replaces it with dynamically generated
     * attributes from the provided data.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array containing dynamic attributes.
     *
     * @throws \RuntimeException If the dynamic attribute variable is missing or not an array.
     *
     * @return string The processed template content with dynamic attributes.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            '/<(\w+)\s+\$(\w+)\s*>/',
            function ($matches) use ($data) {
                $tag = $matches[1];
                $variableName = $matches[2];

                if (
                    !isset($data[$variableName]) ||
                    !is_array($data[$variableName])
                ) {
                    throw new \RuntimeException(
                        "Dynamic attributes variable '{$variableName}' must be an array."
                    );
                }

                $attributes = $this->buildAttributesString(
                    $data[$variableName]
                );

                return "<{$tag} {$attributes}>";
            },
            $content
        );
    }

    /**
     * Build a string of HTML attributes from an associative array.
     *
     * Escapes attribute values for safety and constructs a string suitable for
     * insertion into an HTML tag.
     *
     * @param array $attributes An associative array of attributes (key-value pairs).
     *
     * @return string The generated HTML attributes as a string.
     */
    private function buildAttributesString(array $attributes): string
    {
        $result = [];
        foreach ($attributes as $key => $value) {
            $escapedValue = htmlspecialchars(
                (string) $value,
                ENT_QUOTES,
                "UTF-8"
            );
            $result[] = "{$key}=\"{$escapedValue}\"";
        }
        return implode(" ", $result);
    }
}
