<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

class DefaultValueHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            "/\{\{\s*(.*?)\s*\|\s*default\((.*?)\)\s*\}\}/",
            function ($matches) {
                $variable = $matches[1];
                $default = $matches[2];
                return "<?php echo isset($variable) ? $variable : $default; ?>";
            },
            $content
        );
    }
}
