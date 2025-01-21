<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * SetVariableHandler processes `{#set}` syntax for defining variables in templates.
 *
 * Converts `{#set $variable = expression}` syntax into PHP code for dynamically
 * setting variables within templates.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class SetVariableHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{#set}` syntax.
     *
     * Matches `{#set $variable = expression}` syntax and replaces it with PHP code
     * for assigning the given expression to the specified variable.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering (not used in this handler).
     *
     * @return string The processed template content with `{#set}` syntax replaced by PHP code.
     */
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
