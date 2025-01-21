<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * SwitchHandler processes `{#switch}` syntax for conditional branching in templates.
 *
 * Handles `{#switch}`, `{#case}`, and `{:default}` syntax to generate PHP `switch` statements dynamically.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class SwitchHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{#switch}` syntax.
     *
     * Matches `{#switch $variable}`, `{#case value}`, and `{:default}` blocks and replaces
     * them with corresponding PHP `switch`, `case`, and `default` statements.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering (not used in this handler).
     *
     * @return string The processed template content with `{#switch}` syntax replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            '/\{#switch\s+([\$\w]+)\}(.*?)\{:default\}(.*?)\{\/switch\}/s',
            function ($matches) {
                $variable = $matches[1];
                $cases = $matches[2];
                $default = $matches[3];

                $phpCode = "<?php switch ($variable): ?>";
                $phpCode .= preg_replace(
                    "/\{#case\s+(.*?)\}(.*?)/s",
                    "<?php case $1: ?>$2<?php break; ?>",
                    $cases
                );
                $phpCode .= "<?php default: ?>$default<?php endswitch; ?>";

                return $phpCode;
            },
            $content
        );
    }
}
