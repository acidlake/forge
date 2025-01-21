<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Templates\SyntaxHandlerInterface;

class SetVariableHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            '/\{#set\s+([\$\w]+)\s*=\s*(.*?)\}/',
            function ($matches) {
                $variable = $matches[1];
                $expression = $matches[2];
                return "<?php $variable = $expression; ?>";
            },
            $content
        );
    }
}
