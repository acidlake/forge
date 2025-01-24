<?php

namespace Base\Helpers;

class SyntaxHighlighterHelper
{
    /**
     * Highlights PHP code with syntax colors.
     *
     * @param string $code The PHP code to highlight.
     * @return string The highlighted HTML code.
     */
    public static function highlight(string $code): string
    {
        // Escape HTML entities to prevent XSS
        $escapedCode = htmlspecialchars($code, ENT_QUOTES | ENT_HTML5, "UTF-8");

        // Use PHP's highlight_string to generate basic highlighting
        ob_start();
        highlight_string("<?php\n" . $escapedCode);
        $highlightedCode = ob_get_clean();

        // Strip the opening PHP tag added by highlight_string
        return preg_replace("/&lt;\?php<br\s*\/?>/", "", $highlightedCode);
    }
}
