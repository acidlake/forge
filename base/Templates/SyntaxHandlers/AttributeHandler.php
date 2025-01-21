<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

class AttributeHandler implements SyntaxHandlerInterface
{
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
