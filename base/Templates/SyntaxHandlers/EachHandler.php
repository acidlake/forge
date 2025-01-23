<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * EachHandler processes custom template syntax for iterating collections.
 *
 * Handles `{#each}`, `{#while}`, and `{#range}` blocks in templates, with optional support for fallback
 * content using `{:noitems}` when the collection is empty or conditions fail.
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
     * Process the template content to handle `{#each}`, `{#while}`, and `{#range}` blocks.
     *
     * Converts `{#each}` blocks into `foreach` loops, `{#while}` into `while` loops,
     * and `{#range}` into `for` loops, supporting optional fallback content.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering.
     *
     * @return string The processed template content with loops replaced by PHP code.
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

                $phpIndex = $index ? "{$index} => " : "";

                return <<<PHP
<?php if (!empty({$collection})): ?>
    <?php foreach ({$collection} as {$phpIndex}{$item}): ?>
        {$loopContent}
    <?php endforeach; ?>
___PSEUDO_INLINE_PLACEHOLDER___<?php else: ?>
    {$noItemsContent}
<?php endif; ?>
PHP;
            },
            $content
        ); // Handle {#each} blocks without {:noitems}
        $content = preg_replace_callback(
            '/\{#each\s+([\$\w\->\[\]\'"]+)\s+as\s+(\$\w+)(?:,\s*(\$\w+))?\}(.*?)\{\/each\}/s',
            function ($matches) {
                $collection = $matches[1];
                $item = $matches[2];
                $index = $matches[3] ?? null;
                $loopContent = $matches[4];
                $phpIndex = $index ? "{$index} => " : "";
                return <<<PHP
<?php if (!empty({$collection})): ?>
    <?php foreach ({$collection} as {$phpIndex}{$item}): ?>
        {$loopContent}
    <?php endforeach; ?>
___PSEUDO_INLINE_PLACEHOLDER___<?php endif; ?>
PHP;
            },
            $content
        ); // Handle {#while} blocks
        $content = preg_replace_callback(
            "/\{#while\s+\((.*?)\)\}(.*?)\{\/while\}/s",
            function ($matches) {
                $condition = $matches[1];
                $loopContent = $matches[2];
                return <<<PHP
<?php while ({$condition}): ?>
    {$loopContent}
<?php endwhile; ?>
PHP;
            },
            $content
        ); // Handle {#range} blocks
        $content = preg_replace_callback(
            '/\{#range\s+(\d+)\s+to\s+(\d+)\s+as\s+(\$\w+)\}(.*?)\{\/range\}/s',
            function ($matches) {
                $start = $matches[1];
                $end = $matches[2];
                $variable = $matches[3];
                $loopContent = $matches[4];
                return <<<PHP
<?php for ({$variable} = {$start}; {$variable} <= {$end}; {$variable}++): ?>
    {$loopContent}
<?php endfor; ?>
PHP;
            },
            $content
        );
        return $content;
    }
}
