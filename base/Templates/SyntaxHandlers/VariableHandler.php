<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * VariableHandler processes template syntax for displaying variables.
 *
 * Handles `{{ $variable }}` syntax to safely display variables within templates,
 * ensuring undefined variables are handled gracefully.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class VariableHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{{ $variable }}` syntax.
     *
     * Matches `{{ $variable }}` syntax and replaces it with PHP code that safely
     * checks if the variable is set before displaying its value.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering (not used in this handler).
     *
     * @return string The processed template content with variable syntax replaced by PHP code.
     */
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
