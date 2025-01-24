<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * DateHandler processes custom template syntax for date formatting.
 *
 * Handles expressions in the format `{date('format', 'time')}` or `{date('format', variable)}`.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class DateHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{date('format', time)}` syntax.
     *
     * Matches and replaces `{date('format', time)}` with PHP code that calls the `date()` function,
     * dynamically parsing "now" and variable timestamps.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering.
     *
     * @return string The processed template content with `date()` syntax replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            "/\{date\(\s*'(.*?)'\s*,\s*(.*?)\s*\)\}/",
            function ($matches) {
                $format = $matches[1];
                $time = $matches[2];

                // Handle special cases for `now` and variables
                $timeCode =
                    $time === "'now'" || $time === "now" ? "time()" : $time;

                return "<?php echo date('{$format}', {$timeCode}); ?>";
            },
            $content
        );
    }
}
