<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * CommentHandler removes custom comments from template content.
 *
 * This handler processes template content to strip comments written in
 * the `{# comment content #}` format.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class CommentHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to remove custom comments.
     *
     * Matches and removes all comments in the format `{# comment content #}`.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of data (not used in this handler).
     *
     * @return string The processed template content with comments removed.
     */
    public function process(string $content, array $data): string
    {
        // Remove comments in the format {# comment content #}
        return preg_replace("/\{#.*?#\}/s", "", $content);
    }
}
