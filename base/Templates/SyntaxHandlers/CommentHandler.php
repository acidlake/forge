<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Templates\SyntaxHandlerInterface;

class CommentHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        // Remove comments in the format {# comment content #}
        return preg_replace("/\{#.*?#\}/s", "", $content);
    }
}
