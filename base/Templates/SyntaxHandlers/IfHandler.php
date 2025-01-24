<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * IfHandler processes custom template syntax for conditional logic.
 *
 * Handles `{#if}`, `{:else if}`, `{:else}`, and `{/if}` syntax to generate PHP
 * conditional statements dynamically within templates.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class IfHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle conditional syntax.
     *
     * Matches and replaces `{#if}`, `{:else if}`, `{:else}`, and `{/if}` blocks
     * with corresponding PHP `if`, `elseif`, `else`, and `endif` statements.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering (not used in this handler).
     *
     * @return string The processed template content with conditional syntax replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace(
            [
                "/\{#if\s+(.*?)\}/", // Match {#if condition}
                "/\{:else if\s+(.*?)\}/", // Match {:else if condition}
                "/\{:else\}/", // Match {:else}
                "/\{\/if\}/", // Match {/if}
            ],
            [
                '<?php if ($1): ?>', // Replace with PHP if statement
                '<?php elseif ($1): ?>', // Replace with PHP elseif statement
                "<?php else: ?>", // Replace with PHP else statement
                "<?php endif; ?>", // Replace with PHP endif statement
            ],
            $content
        );
    }
}
