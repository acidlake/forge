<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

class VariableHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            '/\{\{\s*(\$\w+)\s*\}\}/',
            function ($matches) use ($data) {
                $variable = $matches[1];
                return "<?php echo isset({$variable}) ? {$variable} : ''; ?>";
            },
            $content
        );
    }
}
