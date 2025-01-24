<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * ArrayHandler processes template syntax for accessing array elements.
 *
 * Handles syntax like `{{ $array['key'] }}` and `{{ $array["key"] }}` safely.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class ArrayHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle array access syntax.
     *
     * Matches `{{ $array['key'] }}` or `{{ $array["key"] }}` syntax
     * and replaces it with PHP code to safely access the array element.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering.
     *
     * @return string The processed template content with array access syntax replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            '/\{\{\s*(\$\w+)\[([\'"])(.*?)\2\]\s*\}\}/',
            function ($matches) {
                $array = $matches[1];
                $key = $matches[3];
                return "<?php echo isset({$array}['{$key}']) ? {$array}['{$key}'] : ''; ?>";
            },
            $content
        );
    }
}
