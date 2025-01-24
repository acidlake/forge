<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * JsonHandler processes {json(variable)} syntax.
 */
class JsonHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            "/\{json\((.*?)\)\}/",
            function ($matches) {
                $variable = $matches[1];
                return "<?php echo json_encode({$variable}); ?>";
            },
            $content
        );
    }
}
