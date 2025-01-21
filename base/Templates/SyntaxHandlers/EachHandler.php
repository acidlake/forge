<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * EachHandler processes custom template syntax for iterating collections.
 *
 * Handles `{#each}` blocks in templates, with optional support for fallback
 * content using `{:noitems}` when the collection is empty.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class EachHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{#each}` blocks.
     *
     * Converts `{#each}` blocks into PHP `foreach` loops, supporting optional
     * fallback content with `{:noitems}` for empty collections.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering.
     *
     * @return string The processed template content with `{#each}` blocks replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        // Handle {#each} blocks with {:noitems}
        $content = preg_replace_callback(
            '/\{#each\s+([\$\w\->\[\]\'"]+)\s+as\s+(\$\w+)(?:,\s*(\$\w+))?\}(.*?)\{:noitems\}(.*?)\{\/each\}/s',
            function ($matches) {
                $collection = $matches[1];
                $item = $matches[2];
                $index = $matches[3] ?? null;
                $loopContent = $matches[4];
                $noItemsContent = $matches[5];

                $phpCode = "<?php if (!empty({$collection})): ?>";
                $phpCode .=
                    "<?php foreach ({$collection} as " .
                    ($index ? "{$index} => " : "") .
                    "{$item}): ?>";
                $phpCode .= str_replace(
                    ["{{", "}}"],
                    ["<?= ", "; ?>"],
                    $loopContent
                );
                $phpCode .= "<?php endforeach; ?>";
                $phpCode .= "<?php else: ?>";
                $phpCode .= str_replace(
                    ["{{", "}}"],
                    ["<?= ", "; ?>"],
                    $noItemsContent
                );
                $phpCode .= "<?php endif; ?>";

                return $phpCode;
            },
            $content
        );

        // Handle {#each} blocks without fallback
        $content = preg_replace_callback(
            '/\{#each\s+([\$\w\->\[\]\'"]+)\s+as\s+(\$\w+)(?:,\s*(\$\w+))?\}(.*?)\{\/each\}/s',
            function ($matches) {
                $collection = $matches[1];
                $item = $matches[2];
                $index = $matches[3] ?? null;
                $loopContent = $matches[4];

                $phpCode = "<?php if (!empty({$collection})): ?>";
                $phpCode .=
                    "<?php foreach ({$collection} as " .
                    ($index ? "{$index} => " : "") .
                    "{$item}): ?>";
                $phpCode .= str_replace(
                    ["{{", "}}"],
                    ["<?= ", "; ?>"],
                    $loopContent
                );
                $phpCode .= "<?php endforeach; ?>";
                $phpCode .= "<?php endif; ?>";

                return $phpCode;
            },
            $content
        );

        return $content;
    }
}
