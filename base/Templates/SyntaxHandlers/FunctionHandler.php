<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

class FunctionHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            "/\{([\w_]+)\((.*?)\)\}/",
            function ($matches) {
                $function = $matches[1];
                $params = $matches[2];
                return "<?php echo {$function}({$params}); ?>";
            },
            $content
        );
    }
}
