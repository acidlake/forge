<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * AssetHandler processes `{asset('path/to/asset')}` syntax in templates.
 *
 * Converts `{asset('path/to/asset')}` into PHP code that calls the `asset()` function.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class AssetHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{asset('path/to/asset')}` syntax.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering.
     *
     * @return string The processed template content with `{asset}` syntax replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            // Match the {asset('...')} syntax
            "/\{asset\((['\"])(.*?)\\1\)\}/",
            function ($matches) {
                $path = $matches[2];
                return "<?php echo asset('{$path}'); ?>";
            },
            $content
        );
    }
}
