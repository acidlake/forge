<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * EscapeHandler processes template syntax for escaping HTML content.
 *
 * Handles expressions in the format `{{ escape(expression) }}` to ensure that
 * dynamic content is safely escaped to prevent XSS attacks.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class EscapeHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{{ escape(expression) }}` syntax.
     *
     * Matches and replaces `{{ escape(expression) }}` with PHP code that safely
     * escapes the given expression using `htmlspecialchars`.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering (not used in this handler).
     *
     * @return string The processed template content with escape syntax replaced by PHP code.
     */
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
