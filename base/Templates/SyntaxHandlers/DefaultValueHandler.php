<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * DefaultValueHandler processes template syntax for default values.
 *
 * Handles expressions in the format `{{ variable | default(value) }}` to provide
 * a default value if the variable is not set.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class DefaultValueHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle default value syntax.
     *
     * Matches and replaces expressions like `{{ variable | default(value) }}` with PHP code
     * that outputs the variable's value or the default value if the variable is not set.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering.
     *
     * @return string The processed template content with default value syntax replaced.
     */
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
