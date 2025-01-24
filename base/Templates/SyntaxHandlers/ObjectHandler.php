<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * ObjectHandler processes template syntax for accessing object properties.
 *
 * Handles syntax like `{{ $object->property }}` safely.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class ObjectHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle object property access syntax.
     *
     * Matches `{{ $object->property }}` syntax and replaces it with PHP code
     * to safely access the object property.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering.
     *
     * @return string The processed template content with object property access syntax replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            '/\{\{\s*(\$\w+)->(\w+)\s*\}\}/',
            function ($matches) {
                $object = $matches[1];
                $property = $matches[2];
                return "<?php echo isset({$object}->{$property}) ? {$object}->{$property} : ''; ?>";
            },
            $content
        );
    }
}
