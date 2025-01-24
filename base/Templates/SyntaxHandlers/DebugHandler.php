<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * DebugHandler processes custom template syntax for debugging.
 *
 * Handles expressions like `{debug(variable)}` or `{dump(variable)}`,
 * rendering beautifully formatted debug information with syntax highlighting.
 * Stops further script execution after rendering the debug output.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class DebugHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{debug(variable)}` and `{dump(variable)}` syntax.
     *
     * Matches and replaces `{debug(variable)}` with PHP code that renders debug information in a styled block.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering (not used in this handler).
     *
     * @return string The processed template content with debug syntax replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            "/\{(debug|dump)\((.*?)\)\}/",
            function ($matches) {
                $function = $matches[1];
                $variable = $matches[2];

                // Generate PHP code for debugging
                return <<<PHP
<?php
    echo '<pre style="
        background: #1e1e1e;
        color: #dcdcdc;
        padding: 10px;
        border-radius: 5px;
        overflow: auto;
        font-family: Consolas, monospace;
        font-size: 14px;
        line-height: 1.5;
        word-wrap: break-word;
    ">';
    echo htmlspecialchars(print_r({$variable}, true), ENT_QUOTES, 'UTF-8');
    echo '</pre>';
    exit(); // Stop script execution after debug/dump
?>
PHP;
            },
            $content
        );
    }
}
