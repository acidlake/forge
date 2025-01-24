<?php

namespace Base\Interfaces;

/**
 * Interface SyntaxHandlerInterface
 *
 * Defines a contract for processing content with syntax replacements in the Forge framework.
 * Typically used for templating or dynamic content rendering.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
interface SyntaxHandlerInterface
{
    /**
     * Process the content by replacing placeholders or syntax with provided data.
     *
     * @param string $content The content string containing placeholders or syntax to be processed.
     * @param array  $data    An associative array of data to replace placeholders in the content.
     *
     * @return string The processed content with syntax replaced by the corresponding data.
     */
    public function process(string $content, array $data): string;
}
