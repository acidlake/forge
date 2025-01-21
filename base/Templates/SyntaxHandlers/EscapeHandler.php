<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Templates\SyntaxHandlerInterface;

class EscapeHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            "/\{\{\s*escape\((.*?)\)\s*\}\}/",
            function ($matches) {
                $expression = $matches[1];
                return "<?php echo htmlspecialchars($expression, ENT_QUOTES, 'UTF-8'); ?>";
            },
            $content
        );
    }
}
